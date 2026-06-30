# Fleet Management WebSocket API

**Version:** V1

**Status:** Stable

**Last Updated:** June 2026

## Table of Contents

* [Overview](#overview)
* [Gateway URL](#gateway-url)
* [API Version](#api-version)
* [Authentication](#authentication)
* [Authorization](#authorization)
* [Connection Lifecycle](#connection-lifecycle)
* [Protocol](#protocol)
* [Message Format](#message-format)
* [Heartbeat](#heartbeat)
* [Subscriptions](#subscriptions)
* [Available Channels](#available-channels)
    * [Vehicle Updates](#vehicle-updates)
    * [Connection Status](#connection-status)
    * [Movement Status](#movement-status)
    * [GPS Status](#gps-status)
    * [Ignition Status](#ignition-status)
* [Error Messages](#error-messages)
* [Connection Limits](#connection-limits)
* [Examples](#examples)

---

## Overview

The Fleet Management WebSocket API provides secure, low-latency access to realtime fleet telemetry.

Unlike the REST API, which is request-response based, the WebSocket API establishes a persistent bidirectional connection between the client and the Fleet Management Gateway. Once connected and authenticated, clients receive realtime vehicle events as they occur without repeatedly polling the server.

The WebSocket API is intended for applications that require live fleet monitoring, dashboards, dispatch systems, mobile applications, command centers, and other realtime integrations.

Like the REST API, the WebSocket API provides a provider-independent abstraction over supported GPS tracking platforms. Regardless of the underlying tracking provider, all realtime events are normalized into a consistent event model.-

---

## Gateway URL

Production

wss://your-domain.com/ws/v1

All examples throughout this documentation assume the production gateway URL.

**`The WebSocket API requires a secure (wss://) connection.`**

---

## API Version

Current version: V1

The version is included in the gateway URL.

*`Example:`*

```json
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
table
Applications should securely store issued tokens. Tokens should never be embedded in client-side applications or exposed to end users.

### Token Abilities

Each Personal Access Token is assigned one or more abilities.

Examples include:

- vehicles.read
- history.read
- telemetry.subscribe
- companies.read

The WebSocket Gateway verifies that the authenticated token includes the required ability before allowing subscriptions.

For example, subscribing to realtime telemetry requires the telemetry.subscribe ability.

If the required ability is missing, the subscription request is rejected.

### Token Expiration
Personal Access Tokens may have an expiration date configured when they are created.

After expiration:

- New WebSocket authentication attempts will fail.
- Existing authenticated connections may be terminated by the gateway.
- Clients should obtain a new Personal Access Token before reconnecting.

## Authentication Flow

The WebSocket authentication lifecycle consists of four stages.

    Client                            Gateway
    │                                   │
    │                                   │
    ├──────────── Connect ─────────────►│
    │                                   │
    │◄────────── Connected ─────────────│
    │                                   │
    ├──────── Authenticate ──────────-─►│
    │                                   │
    │◄──────── Authenticated ───────────│
    │                                   │
    ├────────── Subscribe ─────────────►│
    │                                   │
    │◄────────── Subscribed ────────────│
    │                                   │
    │◄────── Vehicle Updates ───────────│

Once authenticated, the connection may create one or more subscriptions and receive realtime events until the connection is closed.
