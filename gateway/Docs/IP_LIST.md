# `GET /app/ips`

Retrieves a list of all IP addresses managed by the system.

## Description

This endpoint returns a JSON array containing details of all IP addresses. Each IP address object includes its ID,
associated user ID, the IP address itself, a label, and an optional comment.

## Request

### Headers

| Header          | Type   | Required | Description                            |
|-----------------|--------|----------|----------------------------------------|
| `Authorization` | string | ✅ Yes    | Bearer token of the authenticated user |

### Example Request (cURL)

```bash
curl -X POST /app/ips \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJh..."
```

## Response

* **Content Type**: `application/json`

* **Status Codes**:

    * `200 OK`: Successfully retrieved the list of IP addresses.

#### Successful Response (200 OK)

A JSON array where each element is an object representing an IP address.

**Example Response Body:**

```json
[
    {
        "id": 1,
        "user_id": 101,
        "ip_address": "192.168.1.1",
        "label": "Home Network",
        "comment": "Main router IP"
    },
    {
        "id": 2,
        "user_id": 102,
        "ip_address": "10.0.0.5",
        "label": "VPN Client",
        "comment": null
    },
    {
        "id": 3,
        "user_id": 101,
        "ip_address": "172.16.0.100",
        "label": "Development Server",
        "comment": "Internal server IP for dev environment"
    }
]
```

#### Response Object Structure:

| Field        | Type      | Description                                         | Example                                                       |
|:-------------|:----------|:----------------------------------------------------|:--------------------------------------------------------------|
| `id`         | `integer` | The unique identifier for the IP address.           | `1`                                                           |
| `user_id`    | `integer` | The ID of the user associated with this IP address. | `101`                                                         |
| `ip_address` | `string`  | The IP address itself.                              | `"192.168.1.1"`                                               |
| `label`      | `string`  | A short, descriptive label for the IP address.      | `"Home Network"`                                              |
| `comment`    | `string   | null`                                               | An optional longer comment or description for the IP address. | `"Main router IP"` |
