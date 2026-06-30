# Fleet Management REST API

**Version:** V1

## Overview

The Fleet Management REST API provides secure access to fleet resources, including companies, vehicles, realtime vehicle state, and historical tracking data.

The API is designed for customer portals, mobile applications, enterprise integrations, reporting systems, and third-party software.

All resources are uniquely identified using UUIDs. Clients authenticate using Personal Access Tokens and only receive data they are authorized to access.

The Fleet Management System provides a provider-independent interface over supported GPS tracking providers. Regardless of the underlying tracking platform, all responses are normalized into a consistent data model.

---

## Base URL

Production

https://your-domain.com/api/v1

All examples throughout this documentation assume the production base URL.

---

## API Version

Current version: V1

The version is included in the request URL.

```json
GET /api/v1/vehicles
```

Future versions may introduce new endpoints or capabilities while maintaining backward compatibility whenever possible.

---

## Authentication

All endpoints require Bearer Token authentication.

```json
Authorization: Bearer <access-token>
```

Requests without a valid Personal Access Token receive

```json
401 Unauthorized
```

*`Example:`*

```http
GET /api/v1/vehicles
Authorization: Bearer eyJ...

Accept: application/json
```

## Authorization

Every request is evaluated using two independent authorization layers.

### Token Abilities
Each Personal Access Token defines which API capabilities are available.

Typical abilities include:

- companies.read
- vehicles.read
- history.read
- telemetry.subscribe

If a token lacks the required ability, the API returns

```json
403 Forbidden
```

### Resource Visibility

A valid token does not automatically grant access to every resource.
Every authenticated request is filtered according to the authenticated user's visibility.
Applications only receive companies and vehicles they are permitted to access.

For example

- Company administrators only receive vehicles belonging to their companies.
- System administrators may receive all vehicles.
- Third-party integrations may be limited to specific fleets.

> This filtering is applied automatically to every endpoint.

## Endpoint Summary


| Method | Endpoint                    | Description            |
| ------ | --------------------------- | ---------------------- |
| GET    | /companies                  | List visible companies |
| GET    | /companies/{company}        | Get company details    |
| GET    | /vehicles                   | List vehicles          |
| GET    | /vehicles/{vehicle}         | Get vehicle            |
| GET    | /vehicles/{vehicle}/history | Vehicle history        |


## Response Format

Collection endpoints return paginated responses.

```json
{
    "data": [],
    "links": {},
    "meta": {}
}
```

Single-resource endpoints return

```json
{
    "data": {}
}
```

> The exact structure of each resource is documented in the corresponding endpoint section.

## Pagination

Collection endpoints use Laravel's standard pagination format.

*`Example:`*

```json
{
    "data": [...],
    "links": {
        "first": "...",
        "last": "...",
        "prev": null,
        "next": "..."
    },
    "meta": {
        "current_page": 1,
        "last_page": 8,
        "per_page": 50,
        "total": 364
    }
}
```

> Clients should use the provided navigation links rather than constructing pagination URLs manually.

## Error Handling

The API uses standard HTTP status codes.

Validation failures return a structured error response.

*`Example:`*

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "from": [
            "The from field is required."
        ]
    }
}
```

> Error responses may contain one or more validation messages depending on the request.

## Endpoints

### Companies

Companies represent fleet owners or customer organizations within the Fleet Management System.
The authenticated user only receives companies they are authorized to access.

---

#### Company Object

| Field | Type | Description |
|---------|------|-------------|
| uuid | UUID | Unique company identifier |
| name | string | Company name |
| vehicles_count | integer | Number of vehicles belonging to the company |


*`Example:`*

```json
{
    "uuid": "019eeeb4-ddc3-7204-a8ce-c0f55c48721c",
    "name": "Al Berga Company",
    "vehicles_count": 82
}
```

---

### List Companies

Returns a paginated collection of companies visible to the authenticated user.

#### Endpoint

```http
GET /companies
```

---

#### Query Parameters

This endpoint currently does not support filtering.

---

*`Example:`*

```http
GET /api/v1/companies
Authorization: Bearer <token>
Accept: application/json
```

---

#### Successful Response

```json
{
    "data": [
        {
            "uuid": "019eeeb4-ddc3-7204-a8ce-c0f55c48721c",
            "name": "Al Berga Company",
            "vehicles_count": 82
        },
        {
            "uuid": "019eeeb4-de10-7246-aef5-f88b9dfb0d19",
            "name": "National Oil Transport",
            "vehicles_count": 154
        }
    ],
    "links": {
        "first": "...",
        "last": "...",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 15,
        "total": 2
    }
}
```

---

#### Response Fields


| Field | Type | Description |
|---------|------|-------------|
| data | array | Collection of Company objects |
| links | object | Pagination links |
| meta | object | Pagination metadata |

---

### Get Company

Returns a single company visible to the authenticated user.

#### Endpoint

```http
GET /companies/{company}
```

---

#### Path Parameters


| Parameter | Type | Description |
|------------|------|-------------|
| company | UUID | Company UUID |

---

*`Example:`*

```http
GET /api/v1/companies/019eeeb4-ddc3-7204-a8ce-c0f55c48721c
Authorization: Bearer <token>
Accept: application/json
```

---

#### Successful Response

```json
{
    "data": {
        "uuid": "019eeeb4-ddc3-7204-a8ce-c0f55c48721c",
        "name": "Al Berga Company",
        "vehicles_count": 82
    }
}
```

---

#### Possible Responses

| Status | Description |
|----------|-------------|
| 200 | Company returned successfully |
| 401 | Authentication required |
| 403 | Token lacks required ability |
| 404 | Company not found or not visible to the authenticated user |

---

#### Notes

- Only companies visible to the authenticated user are returned.
- Company UUIDs are immutable.
- `vehicles_count` reflects the number of vehicles currently associated with the company.

### Vehicles

Vehicles represent the primary tracked assets within the Fleet Management System.

Each vehicle contains its static information (plate number, manufacturer, ownership, etc.) together with its latest resolved tracking state, when available.

Only vehicles visible to the authenticated user are returned.

---

#### Vehicle Object

| Field | Type | Description |
|---------|------|-------------|
| uuid | UUID | Vehicle identifier |
| plate_number | string \| null | Vehicle registration number |
| brand | string \| null | Manufacturer |
| model | string \| null | Vehicle model |
| color | string \| null | Vehicle color |
| chassis_number | string \| null | Vehicle chassis (VIN) |
| engine_number | string \| null | Engine number |
| company | Company | Vehicle owner |
| location | Vehicle Location \| null | Latest resolved vehicle state |


*`Example:`*

```json
{
    "uuid": "019f134c-e3c9-720e-a15e-552166f31401",
    "plate_number": "2-11153",
    "brand": "Astra",
    "model": "M588FS",
    "color": "White",
    "chassis_number": null,
    "engine_number": null,
    "company": {
        "uuid": "019f134c-e35e-7131-9114-b2013d219a45",
        "name": "Al Berga Company"
    },
    "location": { ... }
}
```

---

#### List Vehicles

Returns a paginated collection of vehicles visible to the authenticated user.

##### Endpoint

```http
GET /vehicles
```

---

##### Query Parameters

Vehicles may be filtered using one or more query parameters.

When multiple filters are supplied, they are combined using **logical AND**.

---

###### Identification Filters


| Parameter | Type | Description |
|------------|------|-------------|
| uuid | UUID | Vehicle UUID |
| plate_number | string | Exact plate number |


*`Example:`*

```http
GET /vehicles?plate_number=2-11153
```

---

###### Company Filters


| Parameter | Type | Description |
|------------|------|-------------|
| company | UUID | Company UUID |
| company_name | string | Partial company name |


*`Example:`*

```http
GET /vehicles?company=<company_uuid>
```

---

###### Vehicle Filters


| Parameter | Type | Description |
|------------|------|-------------|
| brand | string | Vehicle manufacturer |
| model | string | Vehicle model |
| tracked | boolean | Only tracked or untracked vehicles |


*`Example:`*

```http
GET /vehicles?tracked=true
```

```http
GET /vehicles?brand=Astra
```

---

###### Tracking Filters

Tracking filters operate on the latest resolved realtime state of each vehicle.

**`Connection`**


| Value |
|-------|
| online |
| offline |


*`Example:`*

```http
GET /vehicles?connection=online
```

---

**`Movement`**


| Value |
|-------|
| moving |
| parked |
| idling |


*`Example:`*

```http
GET /vehicles?movement=moving
```

---

**`Ignition`**


| Value |
|-------|
| on |
| off |


*`Example:`*

```http
GET /vehicles?ignition=on
```

---

**`GPS`**


| Value |
|-------|
| true |
| false |


*`Example:`*

```http
GET /vehicles?gps=false
```

---

###### Search

The `search` parameter performs a case-insensitive search across multiple vehicle fields.

Supported fields

- Plate number
- Company name
- Brand
- Model
- Engine number
- Chassis number

*`Example:`*

```http
GET /vehicles?search=Mercedes
```

---

###### Sorting

Supported sort fields

- plate_number
- brand
- model
- created_at

> Descending order is indicated using a leading `-`.

*`Example:`*

```http
GET /vehicles?sort=plate_number
```

```http
GET /vehicles?sort=-created_at
```

---

###### Combining Filters

Multiple filters may be combined.

*`Example:`*

```http
GET /vehicles?
company=<company_uuid>
&connection=online
&movement=moving
&ignition=on
&gps=true
&tracked=true
&sort=plate_number
```

> This request returns only vehicles that satisfy **all** supplied filters.
> The request format/example is broken down into separate lines for readability in the ***documentation only***.

---

*`Example:`*


```http
GET /api/v1/vehicles?company=<uuid>&connection=online&movement=moving
Authorization: Bearer <token>
Accept: application/json
```

---

##### Successful Response

```json
{
    "data": [
        {
            "uuid": "019f134c-e3c9-720e-a15e-552166f31401",
            "plate_number": "2-11153",
            "brand": "Astra",
            "model": "M588FS",
            "company": {
                "uuid": "019f134c-e35e-7131-9114-b2013d219a45",
                "name": "Al Berga Company"
            },
            "location": { ... }
        }
    ],
    "links": {},
    "meta": {}
}
```


##### Response Fields


| Field | Type | Description |
|---------|------|-------------|
| data | array | Collection of Vehicle objects |
| links | object | Pagination links |
| meta | object | Pagination metadata |

---

#### Get Vehicle

Returns a single vehicle.

##### Endpoint

```http
GET /vehicles/{vehicle}
```

---

##### Path Parameters


| Parameter | Type | Description |
|------------|------|-------------|
| vehicle | UUID | Vehicle UUID |

---

*`Example:`*

```http
GET /api/v1/vehicles/019f134c-e3c9-720e-a15e-552166f31401
Authorization: Bearer <token>
Accept: application/json
```

---

##### Successful Response

```json
{
    "data": {
        "uuid": "019f134c-e3c9-720e-a15e-552166f31401",
        "plate_number": "2-11153",
        "brand": "Astra",
        "model": "M588FS",
        "company": {
            "uuid": "019f134c-e35e-7131-9114-b2013d219a45",
            "name": "Al Berga Company"
        },
        "location": { ... }
    }
}
```

---

##### Possible Responses


| Status | Description |
|---------|-------------|
| 200 | Vehicle returned successfully |
| 401 | Authentication required |
| 403 | Token lacks required ability |
| 404 | Vehicle not found or not visible to the authenticated user |

---

##### Notes

- Vehicles are always filtered according to the authenticated user's visibility.
- Tracking filters operate on the latest resolved vehicle state.
- Vehicles without an assigned tracking device may return `location: null`.
- Realtime state is automatically preferred over the last synchronized database state whenever available.


#### Vehicle Location

The `location` object represents the latest known state of a vehicle.
It is a provider-independent abstraction that normalizes telemetry received from different tracking platforms into a single, consistent structure.
Whenever possible, the Fleet Management System returns the latest realtime state. If realtime data is unavailable, the most recently synchronized database state is returned.

If the vehicle has no assigned tracking device or no location is available, the value will be `null`.

---

##### Vehicle Location Object


| Field | Type | Description |
|---------|------|-------------|
| source | string | Origin of the resolved location |
| status | object | Vehicle operational state |
| coordinates | Coordinates \| null | Latest GPS coordinates |
| geo_address | Geo Address \| null | Reverse geocoded address |
| speed | Speed \| null | Current speed |
| gps_status | boolean \| null | Indicates whether the GPS fix is valid |
| angle | integer \| null | Heading in degrees |
| altitude | number \| null | Altitude reported by the tracking device |
| ignition | Ignition \| null | Ignition state |
| oil | number \| null | Fuel level or fuel sensor reading |
| voltage | number \| null | Device or vehicle voltage |
| mileage | Distance \| null | Total reported distance |
| temperature | string \| null | Temperature sensor reading |
| timestamps | Tracking Timestamps | Relevant tracking timestamps |


---

*`Example:`*

```json
{
    "location": {
        "source": "realtime",
        "status": {
            "connection": "online",
            "movement": "moving"
        },
        "coordinates": {
            "latitude": 32.43567,
            "longitude": 13.63410
        },
        "geo_address": {
            "display_name": "شارع مسجد صلاح الدين، ترهونة، ليبيا",
            "city": "ترهونة",
            "state": "محافظة المرقب",
            "country": "ليبيا",
            "country_code": "ly"
        },
        "speed": {
            "kmh": 62,
            "mps": 17.22
        },
        "gps_status": true,
        "angle": 175,
        "altitude": 12,
        "ignition": {
            "status": "on"
        },
        "oil": 81,
        "voltage": 13.7,
        "mileage": {
            "km": 2485047,
            "meters": 2485047000
        },
        "temperature": "28",
        "timestamps": {
            "gps": "2026-06-29 23:37:31",
            "received": "2026-06-29 23:37:34",
            "last_synced": null
        }
    }
}
```

---

##### Source

Indicates where the resolved location originated.


| Value | Description |
|---------|-------------|
| realtime | Live telemetry received through the realtime gateway |
| database | Last synchronized state stored in Fleet Management |


Realtime data is always preferred over synchronized database data whenever available.

---

##### Status

The status object summarizes the current operational state of the vehicle.

```json
{
    "status": {
        "connection": "online",
        "movement": "moving"
    }
}
```

---

##### Connection

Represents communication between the tracking device and the platform.

Possible values


| Value | Description |
|---------|-------------|
| online | Device is currently communicating with the platform |
| offline | Device has not reported recently |


---

##### Movement 

Represents the interpreted movement state.

Possible values

| Value | Description |
|---------|-------------|
| moving | Vehicle is moving |
| idling | Engine is running but vehicle is stationary |
| parked | Vehicle is stationary |

---

##### Coordinates

GPS coordinates expressed in decimal degrees.

```json
{
    "latitude": 32.43567,
    "longitude": 13.63410
}
```

---

##### Geo Address

Human-readable address obtained through reverse geocoding.
This object may be omitted or `null` if reverse geocoding is unavailable.

```json
{
    "display_name": "...",
    "city": "...",
    "state": "...",
    "country": "...",
    "country_code": "ly"
}
```

---

##### Speed

Current vehicle speed.

```json
{
    "kmh": 80,
    "mps": 22.22
}
```

---

##### GPS Status

Boolean indicating whether the GPS coordinates represent a valid satellite fix.


| Value | Description |
|---------|-------------|
| true | Valid GPS fix |
| false | Invalid GPS fix |

---

##### Heading

`angle` represents the vehicle heading in degrees.


| Value |
|--------|
| 0–359 |


*`Example:`*

```
0   = North
90  = East
180 = South
270 = West
```

---

##### Altitude

Height above sea level reported by the tracking device.
Units: metres.

---

##### Ignition

Represents the interpreted ignition state.

```json
{
    "ignition": {
        "status": "on"
    }
}
```

Possible values


| Value |
|--------|
| on |
| off |


---

##### Oil

Fuel sensor value reported by the tracking device.
Availability depends on installed hardware.
May be `null`.

---

##### Voltage

Battery or external power voltage reported by the tracking device.
Availability depends on the installed hardware.
May be `null`.

---

##### Mileage

Vehicle odometer.

```json
{
    "km": 2485047,
    "meters": 2485047000
}
```

---

##### Temperature

Temperature sensor reading reported by the tracking device.
Availability depends on installed hardware.
May be `null`.

---

##### Tracking Timestamps

Tracking timestamps represent different stages in the lifecycle of a telemetry record.


| Field | Description |
|---------|-------------|
| gps | Time reported by the GPS device |
| received | Time the realtime gateway received the telemetry |
| last_synced | Time Fleet Management synchronized the database record |


> All timestamps are returned using the Fleet Management server timezone.

---

##### Location Notes

- The Vehicle Location object is provider-independent.
- Additional tracking providers can be integrated without changing the REST API.
- Realtime data automatically overrides synchronized database data when available.
- Not all tracking devices support every telemetry field.
- Optional fields may be `null` depending on the capabilities of the installed hardware.
- Consumers should not assume every field is always available.
