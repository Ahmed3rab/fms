# Fleet Management Gateway API

**Protocol Version:** **V1**

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
    * [Authentication Notes](#authentication-notes)

* [Protocol](#protocol)
    * [Message Envelope](#message-envelope)
    * [Client Messages](#client-messages)
    * [Server Messages](#server-messages)
    * [Message Ordering](#message-ordering)
    * [Unknown Messages](#unknown-messages)
    * [Protocol Notes](#protocol-notes)

* [Connection Lifecycle](#connection-lifecycle)
    * [Message Exchange Pattern](#message-exchange-pattern)

* [Heartbeat](#heartbeat)
    * [Heartbeat Interval](#heartbeat-interval)
    * [Ping](#ping)
    * [Pong](#pong)
    * [Heartbeat Notes](#heartbeat-notes)
    * [Closing Connection](#closing-connection)
        * [Close Reasons](#close-reasons)

* [Subscriptions](#subscriptions)
    * [Subscription Model](#subscription-model)
    * [Subscription Lifecycle](#subscription-lifecycle)
    * [Subscription Message](#subscription-message)
    * [Subscription Fields](#subscription-fields)
    * [Successful Subscription](#successful-subscription)
    * [Removing a Subscription](#removing-a-subscription)
    * [Successful Unsubscription](#successful-unsubscription)
    * [Subscription Notes](#subscription-notes)

* [Available Topics](#available-topics)
    * [Currently Supported Topics](#currently-supported-topics)
    * [Future Topics](#future-topics)

* [Vehicle Topic](#vehicle-topic)
    * [Required Permission](#required-permission)
    * [Subscription](#subscription)
        * [Events](#events)
    * [Subscription Acknowledgement](#subscription-acknowledgement)
    * [Telemetry Event](#telemetry-event)
    * [Unsubscribe Request](#unsubscribe-request)
    * [Unsubscription Acknowledgement](#unsubscription-acknowledgement)
    * [Vehicle Topic Notes](#vehicle-topic-notes)

* [Timestamps](#timestamps)

* [Error Messages](#error-messages)
    * [Error Message](#error-message)
    * [Error Fields](#error-fields)
    * [Error Codes](#error-codes)

* [Appendix A — Message Reference](#appendix-a--message-reference)
* [Appendix B — Topic Reference](#appendix-b--topic-reference)
* [Appendix C — Compatibility](#appendix-c---compatibility)

---

## Overview

The Fleet Management Gateway API provides secure, low-latency access to Real-time fleet telemetry.

Unlike the REST API, which is request-response based, the Gateway API establishes a persistent bidirectional connection between the client and the Fleet Management Gateway. Once connected and authenticated, clients receive Real-time telemetry events as they occur without repeatedly polling the server.

The Gateway API is intended for applications that require live fleet monitoring, dashboards, dispatch systems, mobile applications, command centers, and other Real-time integrations.

Like the REST API, the Gateway API provides a provider-independent abstraction over supported GPS tracking platforms. Regardless of the underlying tracking provider, all Real-time events are normalized into a consistent event model.

The Gateway API is intentionally lightweight. The gateway is responsible for authentication, authorization, subscription management, and Real-time event delivery. Clients are responsible for maintaining the connection and managing subscriptions.

---

## Gateway URL

Production

```text
wss://your-domain.com/ws/v1
```


All examples throughout this documentation assume the production gateway URL.

**`The Gateway API requires a secure (wss://) connection.`**

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

All Gateway connections require authentication using a Personal Access Token.

Authentication occurs after the Gateway connection has been established.

Unlike the REST API, where the token is transmitted in the HTTP Authorization header with every request, the Gateway API authenticates the connection once. After successful authentication, the authenticated session remains valid until the connection is closed, the token expires, or the server terminates the session.

### Obtaining an Access Token

Before connecting to the Gateway, an access token must be created through the Fleet Management Platform.

A user signs in to the platform and issues a Personal Access Token from the API Tokens page.

Each Personal Access Token contains:


| Property        | Description                                                              |
| --------------- | ------------------------------------------------------------------------ |
| Token           | Secret token used to authenticate API requests and Gateway connections |
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

The Gateway Gateway verifies that the authenticated token includes the required ability before allowing subscriptions.

For example, subscribing to Real-time telemetry requires the telemetry.subscribe ability.

If the required ability is missing, the subscription request is rejected.

### Token Expiration

Personal Access Tokens may have an expiration date configured when they are created.

After expiration:

- New Gateway authentication attempts will fail.
- Existing authenticated connections may be terminated by the gateway.
- Clients should obtain a new Personal Access Token before reconnecting.

### Authentication Flow

The Gateway authentication lifecycle consists of four stages.

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
    "timestamp": "2026-07-01T10:19:12+02:00",
    "data": {
        "gateway": {
            "version": "v1",
            "heartbeat": {
                "idle_timeout": 120,
            }
        }
    }
}
```

> gateway.heartbeat values are in seconds.

### Authentication Failure

If authentication fails, the gateway responds with an error event.

```json
{
  "type": "error",
  "timestamp": "2026-06-30T19:38:34+02:00",
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
    "timestamp": "2026-06-30T19:38:34+02:00",
    "data": {
        "reason": "authentication_failed",
        "message": "Authentication failed."
    }
}
```

Clients should treat this message as the final event before the socket is closed.

After a disconnection, subscriptions are not restored automatically. Clients must establish a new Gateway connection, authenticate again, and recreate any required subscriptions.

---

### Authentication Notes

- Authentication is performed once per Gateway connection.
- Every connection must authenticate independently.
- Tokens inherit the permissions of the user who created them.
- Tokens may expire or be revoked at any time.
- Applications should reconnect using a newly issued token when authentication fails due to expiration.
- The gateway does not accept unauthenticated subscription requests.
- The authenticated confirmation event contains the global session configuration payload (gateway). Integration clients should capture this metadata upon handshake to dynamically configure background heartbeat intervals and track API contract versioning.

---

## Protocol

The Fleet Management Gateway API uses a message-based protocol built on top of a persistent Gateway connection.

Every message exchanged between the client and the gateway is encoded as a JSON object.

Each message represents a single protocol event, such as authentication, subscription management, heartbeat, or Real-time telemetry delivery.

The protocol is intentionally provider-independent. Clients interact with Fleet Management concepts rather than the underlying GPS tracking platform.

---

### Message Envelope

All messages exchanged between the client and the gateway follow the same envelope.

```json
{
    "type": "<message-type>",
    "timestamp": "",
    "data": { }
}
```


| Field | Type | Description |
|--------|------|-------------|
| `type` | string | Identifies the message type. |
| `timestamp` | datetime | Message timestamp in ISO-8601 format |
| `data` | object | Message payload. The structure depends on the message type. |


> Using a consistent envelope allows clients to deserialize every message using the same logic before handling the specific event.

---

### Client Messages

Client messages are sent from the application to the Fleet Management Gateway.

The current protocol supports the following message types.


| Type | Description |
|------|-------------|
| `authenticate` | Authenticates the Gateway connection. |
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
| `subscribed` | Subscription acknowledged. |
| `unsubscribed` | Subscription removed. |
| `telemetry` | Real-time vehicle telemetry event. |
| `pong` | Response to a client heartbeat. |
| `error` | Protocol or validation error. |
| `connection_closed` | Final message sent before the gateway closes the connection. |


---

### Message Ordering

Messages generated for a single Gateway connection are delivered in the order they are produced by the gateway. Clients should not assume ordering across multiple gateway instances, separate connections, or reconnections.

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

Every Gateway client should implement the following lifecycle.


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

### Message Exchange Pattern


| Client         | Server          |
| -------------- | --------------- |
| `authenticate` | `authenticated` |
| `subscribe`    | `subscribed`    |
| `unsubscribe`  | `unsubscribed`  |
| `ping`         | `pong`          |


A successful Gateway client should always perform the following steps in order:

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

Gateway connections are long-lived and may remain open for extended periods of time.

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
    "timestamp": "2026-06-30T19:38:34+02:00",
    "data": {
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


---

## Subscriptions

Subscriptions determine which Real-time events are delivered to a connected client.

After successful authentication, clients may subscribe to one or more topics. Each subscription instructs the Fleet Management Gateway to deliver events matching the specified resource.

A single Gateway connection may maintain multiple active subscriptions simultaneously.

Subscriptions remain active until one of the following occurs:

- The client explicitly unsubscribes.
- The Gateway connection is closed.
- The gateway disconnects the client.

---

### Subscription Model

Every subscription consists of two components:


| Field | Description |
|--------|-------------|
| `topic` | The type of resource being monitored. |
| `identifier` | The UUID identifying the specific resource. |


For example, a client may subscribe to:

- A specific vehicle
- An entire company
- A fleet
- A trip
- A device
- Future event streams

---

### Subscription Lifecycle

```text
Client                                 Gateway
  │                                       │
  ├────────── Subscribe ─────────────────►│
  │                                       │
  │◄───────── Subscribed ─────────────────│
  │                                       │
  │◄────────── Telemetry ─────────────────│
  │◄────────── Telemetry ─────────────────│
  │◄────────── Telemetry ─────────────────│
  │                                       │
  ├──────── Unsubscribe ─────────────────►│
  │                                       │
  │◄────────--------- Unsubscribed ───────│
```

---

### Subscription Message

Clients create subscriptions by sending a `subscribe` message.

```json
{
    "type": "subscribe",
    "data": {
        "topic": "vehicle",
        "identifier": "019f134c-e3c9-720e-a15e-552166f31401"
    }
}
```

---

### Subscription Fields


| Field | Type | Description |
|--------|------|-------------|
| `topic` | string | Resource type to subscribe to. |
| `identifier` | UUID | UUID of the resource associated with the topic. |

---

### Successful Subscription

When the subscription is accepted, the gateway responds with:

```json
{
    "type": "subscribed",
    "timestamp": "2026-06-30T19:38:34+02:00",
    "data": {
        "subscription": {
            "topic": "vehicle",
            "identifier": "019f134c-e3c9-720e-a15e-552166f31401"
        }
    }
}
```

Receiving a `subscribed` message indicates that the subscription has been successfully registered and the client will begin receiving matching Real-time events.

---

### Removing a Subscription

Clients may stop receiving events by sending an `unsubscribe` message.

```json
{
    "type": "unsubscribe",
    "data": {
        "topic": "vehicle",
        "identifier": "019f134c-e3c9-720e-a15e-552166f31401"
    }
}
```

---

### Successful Unsubscription

```json
{
    "type": "unsubscribed",
    "timestamp": "2026-06-30T19:38:34+02:00",
    "data": {
        "subscription": {
            "topic": "vehicle",
            "identifier": "019f134c-e3c9-720e-a15e-552166f31401"
        }
    }
}
```

---

### Subscription Notes

- A client may maintain multiple active subscriptions simultaneously.
- Duplicate subscriptions are ignored.
- Unsubscribing from a resource that is not currently subscribed has no effect.
- Subscriptions are scoped to the current Gateway connection.
- Closing the connection automatically removes all active subscriptions.

---

## Available Topics

Topics define the type of Real-time resource a client wishes to monitor.

Each topic represents a logical stream of events within the Fleet Management System. Clients subscribe to a topic together with a resource identifier (UUID) to receive events related to that resource.

The protocol is designed to support multiple topic types. Additional topics may be introduced in future API versions without changing the subscription model.

Clients should ignore unknown message types to remain compatible with future protocol versions.

### Currently Supported Topics

The current version of the Gateway API supports the following topic.

| Topic | Identifier | Description |
|--------|------------|-------------|
| `vehicle` | Vehicle UUID | Receives Real-time telemetry for a single vehicle. |

---

### Future Topics

The Fleet Management Gateway has been designed to support additional Real-time topics as the platform evolves.

Future versions may introduce topics such as:


| Topic | Description |
|--------|-------------|
| `company` | Events for all vehicles belonging to a company. |
| `fleet` | Fleet-wide Real-time telemetry. |
| `device` | Tracking device status and diagnostics. |
| `trip` | Trip lifecycle events. |
| `alert` | Real-time alerts and notifications. |
| `broadcast` | System-wide gateway announcements. |


> The topics listed above are planned capabilities and are **not available in the current version** of the Gateway API.

> Future topics will follow the same subscription model and message envelope described in this specification.

---

## Vehicle Topic

The `vehicle` topic provides Real-time telemetry for a single vehicle.

Clients subscribe using the vehicle UUID.

The vehicle UUID is identical to the UUID exposed by the REST API.

Only telemetry matching the subscribed resource is delivered to the client.

The Vehicle topic is currently the only supported subscription topic. Additional topics may be introduced in future protocol versions while preserving the existing message format.

---

### Required Permission

```
telemetry.subscribe
```

---

### Subscription

```json
{
    "type": "subscribe",
    "data": {
        "topic": "vehicle",
        "identifier": "019f134c-e3c9-720e-a15e-552166f31401"
    }
}
```

---

### Subscription Acknowledgement

If the subscription is accepted, the gateway responds with a `subscribed` message.

```json
{
    "type": "subscribed",
    "timestamp": "2026-06-30T19:38:34+02:00",
    "data": {
        "subscription": {
            "topic": "vehicle",
            "identifier": "019f134c-e3c9-720e-a15e-552166f31401"
        }
    }
}
```

Receiving this message confirms that the subscription has been successfully registered and that Real-time events for the specified vehicle will begin streaming over the current Gateway connection.

---

### Telemetry Event

Whenever new telemetry becomes available for the subscribed vehicle, the gateway sends a `telemetry` message.

```json
{
    "type": "telemetry",
    "timestamp": "2026-06-30T19:53:37+02:00",
    "data": {
        "subscription": {
            "topic": "vehicle",
            "identifier": "019f134c-e3f1-7053-8812-fd4bc2b19ea4"
        },
        "vehicle": {
            "uuid": "019f134c-e3f1-7053-8812-fd4bc2b19ea4",
            "state": {
                "source": "realtime",
                "status": {
                    "connection": "online",
                    "movement": "parked"
                },
                "coordinates": {
                    "latitude": 32.78882,
                    "longitude": 13.14868
                },
                "geo_address": {
                    "display_name": "طريق المطار, منطقة حي الأكواخ سابقا, مشروع الهضبة, طرابلس, ليبيا",
                    "city": "طرابلس",
                    "state": "طرابلس",
                    "country": "ليبيا",
                    "country_code": "ly",
                    "place_id": 54620026,
                    "osm_type": "way",
                    "osm_id": 770880562
                },
                "speed": {
                    "kmh": 0,
                    "mps": 0
                },
                "gps_status": true,
                "angle": 61,
                "altitude": -1,
                "ignition": {
                    "status": "off"
                },
                "oil": 0,
                "voltage": 26.16,
                "mileage": {
                    "km": 27246189,
                    "meters": 27246189000
                },
                "temperature": "-255",
                "timestamps": {
                    "gps": "2026-06-30T17:52:51.000000Z",
                    "received": "2026-06-30T17:52:52.506493Z",
                    "last_synced": null
                }
            }
        }
    }
}

```

The `vehicle.state` object is identical to the Vehicle Location object returned by the REST API.

Using the same normalized data model across both REST and Gateway APIs allows applications to consume Real-time and on-demand vehicle data without maintaining separate object models.

---

### Unsubscribe Request

Clients may stop receiving telemetry by sending an `unsubscribe` message.

```json
{
    "type": "unsubscribe",
    "data": {
        "topic": "vehicle",
        "identifier": "019f134c-e3c9-720e-a15e-552166f31401"
    }
}
```

---

### Unsubscription Acknowledgement

```json
{
    "type": "unsubscribed",
    "timestamp": "2026-06-30T19:55:12+02:00",
    "data": {
        "subscription": {
            "topic": "vehicle",
            "identifier": "019f134c-e3c9-720e-a15e-552166f31401"
        }
    }
}
```

After receiving this message, no further telemetry events for the specified vehicle will be delivered unless the client subscribes again.

---

### Vehicle Topic Notes

- A client may subscribe to multiple vehicles simultaneously.
- Vehicle UUIDs are identical to those used throughout the REST API.
- Every telemetry message contains the subscription that generated the event.
- The `vehicle` payload uses the same provider-independent tracking model as the REST API.
- Telemetry is delivered only while the subscription remains active.
- Applications should tolerate receiving duplicate telemetry events.

---

### Events

A successful subscription receives `telemetry` messages whenever new telemetry becomes available for the subscribed vehicle.

The gateway delivers only telemetry belonging to the requested vehicle.

Multiple vehicle subscriptions may exist simultaneously on the same Gateway connection.

## Timestamps


| Timestamp                    | Meaning                                                   |
| ---------------------------- | --------------------------------------------------------- |
| message.timestamp            | Time the gateway sent the Gateway message               |
| vehicle.state.timestamps.gps         | Time reported by the GPS device                           |
| vehicle.state.timestamps.received    | Time the gateway received the telemetry                   |
| vehicle.state.timestamps.last_synced | Time the telemetry was synchronized into Fleet Management |


> The message.timestamp property is generated by the Gateway and is present in every server message.

---

## Error Messages

When a request cannot be processed, the gateway responds with an `error` message.

An error message does not necessarily terminate the Gateway connection. Depending on the error, the client may correct the request and continue using the existing connection.

### Error Message

```json
{
    "type": "error",
    "timestamp": "2026-06-30T14:52:31+02:00",
    "data": {
        "error": "invalid_payload",
        "message": "Missing token."
    }
}
```

### Error Fields


| Field | Type | Description |
|------|------|-------------|
| error | string | Machine-readable error code |
| timestamp | Datetime | Error message timestamp in ISO-8601 format. |
| message | string | Human-readable error description |


### Error Codes


| Error Code            | Description                                                                    |
| --------------------- | ------------------------------------------------------------------------------ |
| authentication_failed | Authentication failed because the supplied access token is invalid or expired. |
| already_authenticated | Client attempted to authenticate more than once.                               |
| unauthorized          | The client must authenticate before sending this message.                      |
| forbidden             | The authenticated client lacks the required permission.                        |
| invalid_json          | The received message is not valid JSON.                                        |
| invalid_payload       | The message payload is malformed or missing required fields.                   |
| unknown_message       | The specified message type is not supported.                                   |
| invalid_subscription  | The requested subscription is invalid or the resource identifier is not valid. |
| internal_error        | An unexpected gateway error occurred.                                          |


> Additional error codes may be introduced in future protocol versions. Clients should treat unknown error codes as generic failures.

> Unless otherwise specified, an `error` message does not close the Gateway connection. Clients may correct the request and continue using the existing connection.

## Appendix A — Message Reference


| Direction | Message           | Description                        |
| --------- | ----------------- | ---------------------------------- |
| →         | authenticate      | Authenticate the connection.       |
| ←         | authenticated     | Connection authenticated.          |
| →         | ping              | Keep the connection alive.         |
| ←         | pong              | Heartbeat response.                |
| →         | subscribe         | Subscribe to a topic.              |
| ←         | subscribed        | Subscription acknowledged. |
| →         | unsubscribe       | Remove an active subscription.     |
| ←         | unsubscribed      | Subscription removed. |
| ←         | telemetry         | Real-time telemetry event.          |
| ←         | error             | Request could not be processed.    |
| ←         | connection_closed | Gateway is closing the connection. |


---

## Appendix B — Topic Reference


| Topic     | Identifier   | Permission          | Status      |
| --------- | ------------ | ------------------- | ----------- |
| vehicle   | Vehicle UUID | telemetry.subscribe | ✅ Supported |
| company   | Company UUID | —                   | Planned     |
| fleet     | Fleet UUID   | —                   | Planned     |
| device    | Device UUID  | —                   | Planned     |
| trip      | Trip UUID    | —                   | Planned     |
| alert     | Alert UUID   | —                   | Planned     |
| broadcast | —            | —                   | Planned     |


## Appendix C — Compatibility

Clients should:

- Ignore unknown message types.
- Ignore unknown fields.
- Tolerate additional object properties.
- Treat unknown error codes as generic failures.
- Not rely on the order of object properties within JSON messages.
- Ignore additional message types introduced by newer protocol versions.

> These guidelines allow clients to remain compatible with future protocol versions without requiring immediate updates.
