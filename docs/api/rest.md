# Fleet Management REST API

## Overview

The Fleet Management REST API provides secure access to fleet resources, including companies, vehicles, and historical tracking information.

All resources are identified using UUIDs and are protected using Personal Access Tokens.

The API is intended for system integration, customer portals, reporting systems, mobile applications, and other third-party software.

---

# Base URL

```
https://your-domain.com/api/v1
```

---

# Authentication

All endpoints require Bearer Token authentication.

```
Authorization: Bearer <access-token>
```

Requests without a valid access token will receive:

```
401 Unauthorized
```

---

# Authorization

Every request is evaluated using two authorization layers.

## Token Abilities

The Personal Access Token determines which API capabilities are available.

Examples:

- vehicles.read
- history.read
- companies.read
- telemetry.subscribe

---

## Resource Visibility

Resources are filtered according to the authenticated user's visibility.

Applications only receive companies and vehicles they are authorized to access.

---

# Response Format

Collection endpoints return paginated responses.

```json
{
    "data": [],
    "links": {},
    "meta": {}
}
```

Single-resource endpoints return:

```json
{
    "data": {}
}
```

---

# Companies

## List Companies

Returns all companies visible to the authenticated user.

### Request

```
GET /companies
```

### Response

| Field | Type | Description |
|---------|------|-------------|
| uuid | UUID | Company identifier |
| name | string | Company name |
| vehicles_count | integer | Number of vehicles owned by the company |

Example

```http
GET /api/v1/companies
```

```json
{
    "data": [
        {
            "uuid": "019eeeb4-ddc3-7204-a8ce-c0f55c48721c",
            "name": "Al Berga Company",
            "vehicles_count": 82
        }
    ]
}
```

---

## Get Company

Returns a single company.

### Request

```
GET /companies/{company}
```

### Path Parameters

| Name | Description |
|------|-------------|
| company | Company UUID |

Example

```http
GET /api/v1/companies/019eeeb4-ddc3-7204-a8ce-c0f55c48721c
```

---

# Vehicles

Vehicles represent tracked fleet assets.

Each vehicle includes its latest resolved location, if available.

---

## List Vehicles

```
GET /vehicles
```

Returns a paginated collection of visible vehicles.

---

### Query Parameters

| Parameter | Type | Description |
|------------|------|-------------|
| uuid | UUID | Vehicle UUID |
| company | UUID | Company UUID |
| company_name | string | Partial company name |
| brand | string | Vehicle manufacturer |
| model | string | Vehicle model |
| plate_number | string | Exact plate number |
| tracked | boolean | Only tracked or untracked vehicles |
| search | string | Searches plate number, company, brand, model, engine number and chassis number |
| sort | string | Sort field. Prefix with `-` for descending order |

Supported sort fields:

- plate_number
- brand
- model
- created_at

---

### Example

```
GET /vehicles?company=<uuid>&tracked=true&search=Mercedes&sort=plate_number
```

---

### Vehicle Resource

| Field | Type |
|---------|------|
| uuid | UUID |
| plate_number | string |
| brand | string |
| model | string |
| color | string |
| chassis_number | string |
| engine_number | string |
| company | Company |
| location | Vehicle Location \| null |

---

## Get Vehicle

Returns a single vehicle.

```
GET /vehicles/{vehicle}
```

Path Parameters

| Parameter | Description |
|------------|-------------|
| vehicle | Vehicle UUID |

---

# Vehicle Location

The `location` object contains the latest known state of the vehicle.

If no tracking device is assigned or no state exists, the value will be `null`.

---

## Source

Indicates where the location originated.

Possible values:

| Value | Description |
|---------|-------------|
| realtime | Latest live telemetry received through the realtime gateway |
| database | Most recently synchronized state stored in the database |

---

## Status

Represents the resolved operational state.

### Connection

- online
- offline

### Movement

- moving
- idling
- parked

---

## Location Fields

| Field | Type |
|---------|------|
| source | string |
| status | object |
| coordinates | object |
| geo_address | object |
| speed | object |
| gps_status | boolean |
| angle | integer |
| altitude | number |
| acc | string |
| oil | number |
| voltage | number |
| mileage | object |
| temperature | string |
| timestamps | object |

---

## Vehicle History

Returns the historical GPS positions and telemetry reported by a vehicle during a specified time range.

> **Endpoint**
>
> `GET /api/v1/vehicles/{vehicle_uuid}/history`

### Authentication

This endpoint requires a valid Personal Access Token.

```
Authorization: Bearer <token>
Accept: application/json
```

---

## Path Parameters

| Parameter      | Type | Required | Description   |
| -------------- | ---- | -------- | ------------- |
| `vehicle_uuid` | UUID | Yes      | Vehicle UUID. |

---

## Query Parameters

| Parameter  | Type     | Required | Description                                                                 |
| ---------- | -------- | -------- | --------------------------------------------------------------------------- |
| `from`     | Datetime | Yes      | Start of the requested period.                                              |
| `to`       | Datetime | Yes      | End of the requested period. Must be greater than or equal to `from`.       |
| `page`     | Integer  | No       | Page number. Default: `1`.                                                  |
| `per_page` | Integer  | No       | Number of history points returned per page. Default: `100`. Maximum: `100`. |

### Example Request

```http
GET /api/v1/vehicles/019eeeb4-de44-701c-9c61-797fd2a0e341/history?from=2026-06-29%2010:00:23&to=2026-06-29%2010:25:35&page=1&per_page=100
```

---

## Successful Response

```json
{
  "data": [
    {
      "coordinates": {
        "latitude": 32.79175,
        "longitude": 13.14903
      },
      "geo_address": null,
      "speed": {
        "kmh": 0,
        "mps": 0
      },
      "gps_status": true,
      "angle": 167,
      "altitude": -1,
      "acc": "0",
      "oil": 0,
      "voltage": 0,
      "mileage": {
        "km": 782,
        "meters": 782000
      },
      "temperature": "-31",
      "timestamps": {
        "gps": "2026-06-29T08:02:55.000000Z",
        "received": null,
        "last_synced": null
      }
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
    "per_page": 100,
    "total": 8
  }
}
```

---

## Response Fields

### History Point

| Field                    | Type                     | Description                                                   |
| ------------------------ | ------------------------ | ------------------------------------------------------------- |
| `coordinates`            | Object | null            | GPS coordinates of the vehicle.                               |
| `geo_address`            | Object | null            | Reverse-geocoded address when available.                      |
| `speed`                  | Object | null            | Vehicle speed.                                                |
| `gps_status`             | Boolean                  | GPS validity flag.                                            |
| `angle`                  | Integer | null           | Heading in degrees.                                           |
| `altitude`               | Number | null            | Altitude reported by the GPS device.                          |
| `acc`                    | String | null            | Ignition status reported by the tracking device.              |
| `oil`                    | Number | null            | Fuel level, when reported by the device.                      |
| `voltage`                | Number | null            | Device or vehicle battery voltage.                            |
| `mileage`                | Object | null            | Vehicle odometer.                                             |
| `temperature`            | String | null            | Temperature sensor value, when available.                     |
| `timestamps.gps`         | ISO-8601 Datetime        | Time at which the GPS fix was recorded.                       |
| `timestamps.received`    | ISO-8601 Datetime | null | Time the platform received the record, when available.        |
| `timestamps.last_synced` | ISO-8601 Datetime | null | Last synchronization timestamp for stored historical records. |

---

## Speed Object

| Field | Type   | Description                   |
| ----- | ------ | ----------------------------- |
| `kmh` | Number | Speed in kilometres per hour. |
| `mps` | Number | Speed in metres per second.   |

---

## Mileage Object

| Field    | Type   | Description             |
| -------- | ------ | ----------------------- |
| `km`     | Number | Distance in kilometres. |
| `meters` | Number | Distance in metres.     |

---

## Coordinates Object

| Field       | Type   | Description                   |
| ----------- | ------ | ----------------------------- |
| `latitude`  | Number | Latitude in decimal degrees.  |
| `longitude` | Number | Longitude in decimal degrees. |

---

## Geo Address Object

This field is populated when a reverse-geocoded address is available.

| Field          | Type   |
| -------------- | ------ |
| `display_name` | String |
| `city`         | String |
| `state`        | String |
| `country`      | String |
| `country_code` | String |

---

## Notes

* Results are ordered chronologically by the tracking provider.
* Each history point represents a GPS sample reported by the tracking device.
* `geo_address` may be `null` for historical records when reverse geocoding is unavailable.
* Some telemetry fields (`oil`, `temperature`, `voltage`) depend on the capabilities of the installed tracking device and may be `null` or unavailable.
* Historical data availability depends on the configured tracking provider and its retention policy.
* Pagination is performed by the Fleet Management System after retrieving the provider's response. Clients should use the returned `links` object to navigate through large result sets.
---

# Common Data Types

## Coordinates

```json
{
    "latitude": 32.79175,
    "longitude": 13.14907
}
```

---

## Speed

```json
{
    "kmh": 80,
    "mps": 22.22
}
```

---

## Distance

```json
{
    "km": 1250.5,
    "meters": 1250500
}
```

---

## Geo Address

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

## Tracking Timestamps

| Field | Description |
|---------|-------------|
| gps | Timestamp reported by the GPS device |
| received | Timestamp received by the realtime gateway |
| last_synced | Timestamp synchronized into Fleet Management |

---

# HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Resource Not Found |
| 422 | Validation Failed |
| 500 | Internal Server Error |
