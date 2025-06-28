# `GET /app/ips/{id}`

Retrieves the details of a specific IP address using its unique identifier.

### Description

This endpoint returns a JSON object containing the details of a single IP address, identified by its `id`. If the IP
address is not found, a `404 Not Found` error is returned.

---

## Request

### Headers

| Header          | Type   | Required | Description                            |
|-----------------|--------|----------|----------------------------------------|
| `Authorization` | string | ✅ Yes    | Bearer token of the authenticated user |

### Path Parameters

| Parameter | Type     | Description                                          |
|:----------|:---------|:-----------------------------------------------------|
| `id`      | `string` | The unique identifier of the IP address to retrieve. |

### Example Request

`GET /app/ips/123`

---

## Success Response

### HTTP Status: `200 OK`

```json
{
    "id": 123,
    "user_id": 101,
    "ip_address": "192.168.1.100",
    "label": "Office PC",
    "comment": "Desktop computer in the main office."
}
```

#### Response Object Structure:

| Field        | Type             | Description                                                   | Example                           |
|:-------------|:-----------------|:--------------------------------------------------------------|:----------------------------------|
| `id`         | `integer`        | The unique identifier for the IP address.                     | `123`                             |
| `user_id`    | `integer`        | The ID of the user associated with this IP address.           | `101`                             |
| `ip_address` | `string`         | The IP address itself.                                        | `"192.168.1.100"`                 |
| `label`      | `string`         | A short, descriptive label for the IP address.                | `"Office PC"`                     |
| `comment`    | `string \| null` | An optional longer comment or description for the IP address. | `"Desktop computer..."` \| `null` |

---

## Error Responses

### `404 Not Found` — IP Not Found

Occurs when no IP address with the specified ID is found.

```json
{
    "message": "Ip not found."
}
```
