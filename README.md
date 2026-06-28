# Fleet Management System

An enterprise fleet management platform designed for real-time vehicle tracking, device management, geofencing, trip management, and reporting.

The system provides a unified platform for managing GPS tracking devices, vehicle fleets, drivers, and operational data while exposing both REST and WebSocket APIs for external integrations.

---

## Features

- Multi-tenant architecture
- Company management
- User management
- Role & permission management
- Vehicle management
- Driver management
- GPS device management
- Device synchronization
- Real-time vehicle tracking
- Vehicle state caching
- Historical tracking
- Geofencing
- Trip management
- Reporting
- REST API
- WebSocket Gateway
- External tracking provider abstraction
- Ministry integration

---

## Technology Stack

### Backend

- Laravel 13
- PHP 8.5
- PostgreSQL
- Redis

### Administration

- FilamentPHP v5

### Realtime

- OpenSwoole
- Custom WebSocket Gateway

### Authentication & Authorization

- Laravel Sanctum
- Spatie Permission
- Laravel Policies

### Maps

- Leaflet
- OpenStreetMap

---

## Project Structure
app/
config/
database/
docs/
infra/
public/
resources/
routes/
storage/



---

## Documentation

### Project

- docs/project-overview.md
- docs/technical-design.md

### APIs

- docs/api/rest.md
- docs/api/websocket.md

### Architecture

- docs/architecture/gateway.md
- docs/architecture/tracking.md
- docs/architecture/permissions.md
- docs/architecture/tenancy.md
- docs/architecture/redis.md

### Deployment

- docs/deployment.md

---

## Infrastructure

Infrastructure-related files are located under:
infra/

This directory contains:

- Installation scripts
- Deployment scripts
- Nginx configuration
- systemd service definitions
- PHP configuration
- Operational scripts

---

## License

Internal project.
