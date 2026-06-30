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
