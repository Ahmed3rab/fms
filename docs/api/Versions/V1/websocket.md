# Fleet Management WebSocket API

**Version:** **V1**

**Status:** Stable

**Last Updated:** June 2026

## Table of Contents

* [Overview](#overview)
* [Gateway URL](#gateway-url)
* [API Version](#api-version)
* [Authentication](#authentication)
    * [Obtaining an Access Token](#obtaining-an-access-token)
    * [Token Abilities](#token-abilities)
    * [Token Expiration](#token-expiration)
    * [Authentication Flow](#authentication-flow)
    * [Authentication Message](#authentication-message)
    * [Successful Authentication](#successful-authentication)
    * [Authentication Failure](#authentication-failure)
    * [Connection Closed](#connection-closed)
* [Protocol](#protocol)
    * [Message Envelope](#message-envelope)
    * [Client Messages](#client-messages)
    * [Server Messages](#server-messages)
    * [Message Ordering](#message-ordering)
    * [Unknown Messages](#unknown-messages)
* [Connection Lifecycle](#connection-lifecycle)
* [Heartbeat](#heartbeat)
    * [Ping](#ping)
    * [Pong](#pong)
* [Subscriptions](#subscriptions)

---

## Overview

The Fleet Management WebSocket API provides secure, low-latency access to Real-time fleet telemetry.

Unlike the REST API, which is request-response based, the WebSocket API establishes a persistent bidirectional connection between the client and the Fleet Management Gateway. Once connected and authenticated, clients receive Real-time telemetry events as they occur without repeatedly polling the server.

The WebSocket API is intended for applications that require live fleet monitoring, dashboards, dispatch systems, mobile applications, command centers, and other Real-time integrations.

Like the REST API, the WebSocket API provides a provider-independent abstraction over supported GPS tracking platforms. Regardless of the underlying tracking provider, all Real-time events are normalized into a consistent event model.

---

## Gateway URL

Production

```text
wss://your-domain.com/ws/v1
```


All examples throughout this documentation assume the production gateway URL.

**`The WebSocket API requires a secure (wss://) connection.`**

---

## API Version

Current version: V1

The version is included in the gateway URL.

*`Example:`*

```text
wss://your-domain.com/ws/v1
```

Future protocol versions may introduce additional event types or capabilities while preserving compatibility with existing clients whenever practical.

## Authentication

All WebSocket connections require authentication using a Personal Access Token.

Authentication occurs after the WebSocket connection has been established.

Unlike the REST API, where the token is transmitted in the HTTP Authorization header with every request, the WebSocket API authenticates the connection once. After successful authentication, the authenticated session remains valid until the connection is closed, the token expires, or the server terminates the session.

### Obtaining an Access Token

Before connecting to the WebSocket Gateway, an access token must be created through the Fleet Management Platform.

A user signs in to the platform and issues a Personal Access Token from the API Tokens page.

Each Personal Access Token contains:


| Property        | Description                                                              |
| --------------- | ------------------------------------------------------------------------ |
| Token           | Secret token used to authenticate API requests and WebSocket connections |
| Name            | Human-readable name used to identify the token                           |
| Abilities       | Permissions granted to the token                                         |
| Expiration Date | Date and time after which the token becomes invalid                      |


> Applications should securely store issued tokens. Tokens should never be embedded in client-side applications or exposed to end users.

### Token Abilities

Each Personal Access Token is assigned one or more abilities.

Examples include:

- vehicles.read
- history.read
- telemetry.subscribe
- companies.read

The WebSocket Gateway verifies that the authenticated token includes the required ability before allowing subscriptions.

For example, subscribing to Real-time telemetry requires the telemetry.subscribe ability.

If the required ability is missing, the subscription request is rejected.

### Token Expiration

Personal Access Tokens may have an expiration date configured when they are created.

After expiration:

- New WebSocket authentication attempts will fail.
- Existing authenticated connections may be terminated by the gateway.
- Clients should obtain a new Personal Access Token before reconnecting.

### Authentication Flow

The WebSocket authentication lifecycle consists of four stages.

    Client                            Gateway
    │                                   │
    │                                   │
    ├──────────── Connect ─────────────►│
    │                                   │
    ├──────── Authenticate ──────────-─►│
    │                                   │
    │◄──────── Authenticated ───────────│

Once authenticated, the connection may create one or more subscriptions and receive Real-time events until the connection is closed.

### Authentication Message

The client authenticates by sending an authenticate message.

```json
{
    "type": "authenticate",
    "data": {
        "token": "<personal-access-token>"
    }
}
```

### Successful Authentication

If authentication succeeds, the gateway responds with:

```json
{
    "type": "authenticated",
        "data": {}
}
```

### Authentication Failure

If authentication fails, the gateway responds with an error event.

```json
{
  "type": "error",
  "data": {
    "error": "authentication_failed",
    "message": "Invalid or expired access token."
  }
}
```

> The gateway may immediately close the connection after sending the error.

### Connection Closed

When the gateway closes the connection, it sends a final message describing the reason.

```json
{
    "type": "connection_closed",
    "data": {
        "reason": "authentication_failed",
        "message": "Authentication failed."
    }
}
```

Clients should treat this message as the final event before the socket is closed.

---

### Authentication Notes

- Authentication is performed once per WebSocket connection.
- Every connection must authenticate independently.
- Tokens inherit the permissions of the user who created them.
- Tokens may expire or be revoked at any time.
- Applications should reconnect using a newly issued token when authentication fails due to expiration.
- The gateway does not accept unauthenticated subscription requests.

---

## Protocol

The Fleet Management WebSocket API uses a message-based protocol built on top of a persistent WebSocket connection.

Every message exchanged between the client and the gateway is encoded as a JSON object.

Each message represents a single protocol event, such as authentication, subscription management, heartbeat, or Real-time telemetry delivery.

The protocol is intentionally provider-independent. Clients interact with Fleet Management concepts rather than the underlying GPS tracking platform.

---

### Message Envelope

All messages exchanged between the client and the gateway follow the same envelope.

```json
{
    "type": "<message-type>",
    "data": { }
}
```


| Field | Type | Description |
|--------|------|-------------|
| `type` | string | Identifies the message type. |
| `data` | object | Message payload. The structure depends on the message type. |


> Using a consistent envelope allows clients to deserialize every message using the same logic before handling the specific event.

---

### Client Messages

Client messages are sent from the application to the Fleet Management Gateway.

The current protocol supports the following message types.


| Type | Description |
|------|-------------|
| `authenticate` | Authenticates the WebSocket connection. |
| `subscribe` | Creates a Real-time subscription. |
| `unsubscribe` | Removes an existing subscription. |
| `ping` | Verifies connection health. |


> Additional client message types may be introduced in future protocol versions.

---

### Server Messages

Server messages are sent by the Fleet Management Gateway to connected clients.


| Type | Description |
|------|-------------|
| `authenticated` | Authentication completed successfully. |
| `subscribed` | Subscription created successfully. |
| `unsubscribed` | Subscription removed successfully. |
| `telemetry` | Real-time vehicle telemetry event. |
| `pong` | Response to a client heartbeat. |
| `error` | Protocol or validation error. |
| `connection_closed` | Final message sent before the gateway closes the connection. |


---

### Message Ordering

Messages generated by a single connection are delivered in the order in which they are produced by the gateway.

Clients should not assume ordering across different subscriptions or across different WebSocket connections.

Applications should always process each received event independently.

---

### Unknown Messages

Clients should ignore message types they do not recognize.

This allows applications to remain compatible with future protocol versions that introduce new message types without breaking existing implementations.

---

### Protocol Notes

- Every server response corresponds to a specific client operation or gateway event.
- Every protocol message uses the same JSON envelope.
- The `type` field identifies the message being exchanged.
- The `data` field contains the message-specific payload.
- Unknown message types should be ignored rather than treated as protocol errors.
- Clients should not rely on undocumented message fields.

---

## Connection Lifecycle

Every WebSocket client should implement the following lifecycle.


    Client                            Gateway
    │                                   │
    │                                   │
    ├──────────── Connect ─────────────►│
    │                                   │
    ├──────── Authenticate ──────────-─►│
    │                                   │
    │◄──────── Authenticated ───────────│
    │                                   │
    │------> Ping --------------------->│
    │<--------------------- Pong -------│
    │                                   │
    │       (Maintain Connection)       │
    │                                   │
    ├────────── Subscribe ─────────────►│
    │                                   │
    │◄────────── Subscribed ────────────│
    │                                   │
    │◄────── Vehicle Telemetry ─────────│
    │◄────── Vehicle Telemetry ─────────│
    │◄────── Vehicle Telemetry ─────────│

Every client-initiated operation has a corresponding server acknowledgement.

### Request / Response Pattern


| Client         | Server          |
| -------------- | --------------- |
| `authenticate` | `authenticated` |
| `subscribe`    | `subscribed`    |
| `unsubscribe`  | `unsubscribed`  |
| `ping`         | `pong`          |

A successful WebSocket client should always perform the following steps in order:

1. **Connect** to the Fleet Management Gateway.
2. **Authenticate** using a valid Personal Access Token.
3. **Maintain the connection** by responding to heartbeat messages.
4. **Subscribe** to one or more Real-time topics.
5. **Receive Real-time events** until the connection is closed.

This sequence forms the foundation of the Fleet Management Real-time protocol.

Subscription requests sent before successful authentication are rejected.

Likewise, clients that fail to maintain an active connection may be disconnected by the gateway.

---

## Heartbeat

WebSocket connections are long-lived and may remain open for extended periods of time.

To verify that both the client and the gateway remain reachable, the Fleet Management Gateway implements a heartbeat mechanism.

Clients should periodically send a `ping` message.

The gateway responds with a corresponding `pong` message containing the current gateway timestamp.

Failure to maintain heartbeat traffic may result in the connection being closed by the gateway.

### Heartbeat Interval

The Fleet Management Gateway continuously monitors authenticated connections.

Clients should periodically send `ping` messages throughout the lifetime of the connection.

If no heartbeat is received within the configured idle timeout, the gateway closes the connection.

The default configuration is:

| Setting                   |       Default |
| ------------------------- | ------------: |
| Idle timeout              |   120 seconds |
| Recommended ping interval | 30–60 seconds |


Applications should send heartbeat messages more frequently than the configured timeout.

---

### Ping

Clients send a heartbeat request using the following message.

```json
{
    "type": "ping",
    "data": {}
}
```

---

### Pong

The gateway responds with:

```json
{
    "type": "pong",
    "data": {
        "timestamp": "2026-06-30T18:52:31+02:00"
    }
}
```

The returned timestamp represents the Fleet Management server time.

---

### Heartbeat Notes

- Heartbeats verify that both endpoints remain connected.
- Heartbeat messages do not require any subscription.
- Heartbeat messages contain no telemetry.
- Clients should continue sending heartbeat requests for the lifetime of the connection.
- The gateway may terminate idle or unresponsive connections.

---

### Closing Connection

#### Close Reasons

| Reason | Description |
|---------|-------------|
| authentication_failed | Authentication failed |
| heartbeat_timeout | Client stopped sending heartbeats |
| protocol_error | Invalid protocol message |
| server_shutdown | Gateway shutting down |

