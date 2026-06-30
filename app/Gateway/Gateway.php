<?php

namespace App\Gateway;

use App\Enums\CloseReason;
use App\Gateway\Connections\Connection;
use App\Gateway\Connections\ConnectionRepository;
use App\Gateway\Exceptions\ForbiddenException;
use App\Gateway\Exceptions\InternalGatewayException;
use App\Gateway\Exceptions\UnauthorizedException;
use App\Gateway\Protocol\Exceptions\ProtocolException;
use App\Gateway\Protocol\Messages\Contracts\OutgoingMessage;
use App\Gateway\Protocol\Messages\MessageFactory;
use App\Gateway\Protocol\Messages\Outgoing\ClosedMessage;
use App\Gateway\Protocol\ProtocolErrorResponder;
use App\Gateway\Routing\MessageRouter;
use App\Gateway\Subscriptions\SubscriptionManager;
use App\Gateway\Transport\Contracts\GatewayTransport;
use OpenSwoole\Http\Request;

class Gateway
{
    public function __construct(
        protected GatewayTransport $transport,
        protected ConnectionRepository $connections,
        protected SubscriptionManager $subscriptions,
        protected MessageFactory $messages,
        protected MessageRouter $router,
        protected ProtocolErrorResponder $errors,
        protected GatewayLogger $logger,
    ) {}

    /**
     * @param callable(): mixed $boot
     */
    public function start(callable $boot): void
    {
        $this->transport->start($this, $boot);
    }

    public function connect(Request $request): void
    {
        $connection = Connection::fromRequest($request);
        $this->connections->put($connection);
    }

    public function receive(int $connectionId, string $payload): void
    {
        $connection = $this->connections->get($connectionId);

        if ($connection === null) {
            return;
        }

        $connection->client()->heartbeat();

        try {
            $payload = $this->messages->decode($payload);
            $class = $this->messages->resolve($payload);

            if ($class::requiresAuthentication() && ! $connection->client()->authenticated()) {
                throw new UnauthorizedException();
            }

            $permission = $class::requiredPermission();

            if ($permission !== null && ! $connection->client()->can($permission)) {
                throw new ForbiddenException();
            }

            $message = $this->messages->hydrate(
                $class,
                $payload,
            );

            $this->router->dispatch(
                $this,
                $connection,
                $message,
            );
        } catch (ProtocolException $e) {
            $this->errors->respond(
                $this,
                $connection,
                $e,
            );
        } catch (\Throwable $e) {

            $this->logger->error(
                message: 'Gateway::recieve failed and didn\'t catch ProtocolException',
                connection: $connection,
                context: [
                    'class' => get_class($e),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ],
            );
            $this->errors->respond(
                $this,
                $connection,
                new InternalGatewayException(),
            );
        }
    }

    public function disconnect(int $connectionId): void
    {
        $connection = $this->connections->get($connectionId);

        if ($connection === null) {
            return;
        }

        $this->cleanup($connection);
    }

    public function connection(int $id): ?Connection
    {
        return $this->connections->get($id);
    }

    public function send(Connection $connection, OutgoingMessage $message): void
    {
        try {
            $payload = $message->toJson();
        } catch (\Throwable $e) {

            $this->logger->error(
                message: 'Gateway::send $message->toJson() failed',
                connection: $connection,
                context: [
                    'class' => get_class($e),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ],
            );

            $this->errors->respond(
                $this,
                $connection,
                new InternalGatewayException(),
            );
        }

        $this->transport->send($connection, $payload);
    }

    public function disconnectConnection(Connection $connection, ?CloseReason $reason = null, ?string $message = null): void
    {
        if ($reason !== null) {
            try {
                $this->send(
                    $connection,
                    new ClosedMessage(
                        $reason,
                        $message ?? '',
                    ),
                );
            } catch (\Throwable $e) {
                $this->logger->error(
                    message: 'Failed to disconnect connection.',
                    connection: $connection,
                    context: [
                        'reason' => $reason->value,
                        'exception' => $e->getMessage(),
                    ],
                );
            }
        }

        if (! $this->transport->disconnect($connection)) {
            $this->cleanup($connection);
        }
    }

    protected function cleanup(Connection $connection): void
    {
        $this->subscriptions->forget($connection->client());

        $this->connections->forget($connection->id());
    }
}
