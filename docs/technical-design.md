# Fleet Management System

# Technical Design Document

Version: 1.0

---

# 1. Introduction

## Purpose

This document describes the technical architecture of the Fleet Management System (FMS).

It serves as the primary architectural reference for developers, maintainers, and system administrators by documenting the major components of the platform, their responsibilities, interactions, security model, and deployment architecture.

This document intentionally focuses on architectural decisions rather than implementation details.

---

# 2. Design Goals

The system has been designed around the following goals:

- High availability
- Provider independence
- Scalability
- Multi-tenancy
- Real-time communication
- Clear separation of responsibilities
- Secure API access
- Extensibility

---

# 3. System Architecture

The platform is organized into multiple logical layers.

```
                        +-----------------------+
                        |    Client Applications |
                        +-----------+-----------+
                                    |
                       REST API / WebSocket Gateway
                                    |
                +-------------------+-------------------+
                |                                       |
          Laravel Application                  WebSocket Gateway
                |                                       |
                +-------------------+-------------------+
                                    |
                            Tracking Layer
                                    |
                   Provider Abstraction Layer
                                    |
                     External Tracking Providers
```

Each layer has a well-defined responsibility and communicates through stable interfaces.

---

# 4. Major Components

The system consists of several independent subsystems.

## Administration

Responsible for:

- Company management
- User management
- Portal management
- Device management
- Vehicle management
- Driver management
- Configuration

---

## REST API

Provides secure HTTP endpoints for external systems.

Responsibilities include:

- Authentication
- Authorization
- Validation
- Business operations
- Reporting

---

## WebSocket Gateway

Provides low-latency real-time communication.

Responsibilities include:

- Connection management
- Authentication
- Authorization
- Topic subscriptions
- Event delivery
- Heartbeat monitoring

---

## Tracking Layer

Provides a provider-independent abstraction for all tracking operations.

Responsibilities include:

- Current state
- Historical tracking
- Vehicle hydration
- Device hydration
- Realtime ingestion

Business code never communicates directly with provider-specific APIs.

---

## Provider Layer

Responsible for integrating external tracking vendors.

Current provider:

- ICruise

Future providers can be added without affecting the rest of the application.

---

# 5. Multi-Tenant Architecture

The platform supports multiple organizations within a single deployment.

Each tenant owns:

- Users
- Vehicles
- Devices
- Drivers
- Trips
- Reports

Visibility between companies is controlled through configurable company visibility rules.

Authorization policies enforce tenant isolation throughout the application.

---

# 6. Authentication

The platform supports authenticated access through Laravel Sanctum Personal Access Tokens.

Authentication is required for:

- REST API
- WebSocket Gateway

The gateway validates tokens during connection authentication before allowing any protected operation.

---

# 7. Authorization

Authorization operates at multiple levels.

## Token Abilities

Determine which gateway operations are permitted.

Examples:

- telemetry.subscribe

---

## Laravel Policies

Determine whether a user may access a specific resource.

Examples:

- Vehicle
- Device
- Company
- Driver

Policies enforce tenant visibility and business rules.

---

## Resource Authorization

Operations involving specific resources validate ownership and visibility before execution.

For example:

- subscribing to a vehicle
- retrieving history
- viewing reports

---

# 8. Realtime Architecture

Realtime data flows through an event-driven pipeline.

```
ICruise

↓

Realtime Client

↓

Mapper

↓

State Resolver

↓

Redis State Store

↓

Gateway Event Dispatcher

↓

Subscription Manager

↓

Connected Clients
```

The gateway never communicates directly with the tracking provider.

Instead, it receives normalized events from the tracking subsystem.

---

# 9. State Management

Vehicle state is divided into two categories.

## Persistent State

Stored inside PostgreSQL.

Examples:

- Vehicles
- Devices
- Drivers
- Companies

---

## Realtime State

Stored inside Redis.

Examples:

- Position
- Speed
- Status
- Telemetry

Realtime state is optimized for high-frequency updates.

---

# 10. WebSocket Protocol

The gateway implements a custom protocol.

Supported operations include:

- Authentication
- Subscription
- Unsubscription
- Heartbeat
- Connection management

Realtime events are delivered only to authorized subscribers.

Protocol details are documented separately.

---

# 11. Tracking Abstraction

Tracking providers implement a common contract.

Business services communicate exclusively with the abstraction layer.

Benefits include:

- provider independence
- simplified testing
- future extensibility
- cleaner architecture

---

# 12. Event-Driven Architecture

Realtime communication is event-driven.

Tracking updates are published as gateway events.

Handlers determine how each event should be distributed.

Benefits include:

- loose coupling
- easier testing
- extensibility
- independent event handlers

---

# 13. Infrastructure

The production environment consists of:

- Ubuntu Server
- Nginx
- PHP-FPM
- PostgreSQL
- Redis
- OpenSwoole
- systemd
- Let's Encrypt

The WebSocket Gateway operates as a dedicated long-running service managed by systemd.

---

# 14. Monitoring

The platform includes monitoring for:

- Gateway lifecycle
- Worker lifecycle
- Heartbeats
- Realtime provider connectivity
- Queue workers

Infrastructure logs are centralized through Laravel logging and system services.

---

# 15. Security

Security principles include:

- Authenticated API access
- Token abilities
- Policy-based authorization
- Tenant isolation
- Request validation
- Secure WebSocket authentication
- Heartbeat monitoring
- Controlled connection lifecycle

---

# 16. Scalability

The architecture has been designed for horizontal growth.

Future scaling strategies include:

- Multiple gateway instances
- Redis pub/sub
- Load-balanced gateways
- Multiple tracking providers
- Dedicated reporting workers

The tracking abstraction ensures that business services remain unaffected as infrastructure evolves.

---

# 17. Future Enhancements

The architecture supports future additions including:

- Additional tracking providers
- Route optimization
- Driver mobile applications
- Notifications
- AI-powered analytics
- Ministry integrations
- High availability deployments
- Distributed gateway clusters

---

# 18. Related Documentation

- Project Overview
- REST API Specification
- WebSocket Protocol Specification
- Gateway Architecture
- Tracking Architecture
- Permissions & Authorization
- Multi-Tenancy
- Redis Architecture
- Deployment Guide
