# `POST /app/ips`

Creates a new IP address entry in the system.

## Description

This endpoint allows you to add a new IP address to the system. It validates the provided IP address, label, and an
optional comment. Upon successful creation, it returns the details of the newly created IP along with a success message.

---

## Request

### Headers

| Header          | Type   | Required | Description                            |
|-----------------|--------|----------|----------------------------------------|
| `Authorization` | string | ✅ Yes    | Bearer token of the authenticated user |

### Body Parameters

| Parameter    | Type     | Required | Description                                                   |
|:-------------|:---------|:---------|:--------------------------------------------------------------|
| `ip_address` | `string` | ✅ Yes    | The IP address to be stored (must be a valid IP format).      |
| `label`      | `string` | ✅ Yes    | A short, descriptive label for the IP address.                |
| `comment`    | `string` | ❌ No     | An optional longer comment or description for the IP address. |

### Example Request

```json
{
    "ip_address": "192.168.1.100",
    "label": "Office PC",
    "comment": "Desktop computer in the main office."
}
```

---

## Success Response

### HTTP Status: `200 OK`

```json
{
    "id": 4,
    "user_id": 105,
    "ip_address": "192.168.1.100",
    "label": "Office PC",
    "comment": "Desktop computer in the main office.",
    "message": "Ip added successfully."
}
```

#### Response Object Structure:

| Field        | Type             | Description                                                                    | Example                           |
|:-------------|:-----------------|:-------------------------------------------------------------------------------|:----------------------------------|
| `id`         | `integer`        | The unique identifier for the newly created IP address.                        | `4`                               |
| `user_id`    | `integer`        | The ID of the user associated with this IP address (from `at-user-id` header). | `105`                             |
| `ip_address` | `string`         | The IP address that was stored.                                                | `"192.168.1.100"`                 |
| `label`      | `string`         | The label assigned to the IP address.                                          | `"Office PC"`                     |
| `comment`    | `string \| null` | The comment provided for the IP address, or `null` if none was given.          | `"Desktop computer..."` \| `null` |
| `message`    | `string`         | A success message indicating the IP was added.                                 | `"Ip added successfully."`        |

---

## Error Responses

### `400 Bad Request` — Validation Errors

Occurs when request body parameters fail validation rules.

```json
{
    "error_message": "The ip address field is required., The ip address must be a valid IP address., The label field is required."
}
```

### `400 Conflict` — Duplicate IP Address

Occurs when an attempt is made to store an IP address that already exists in the system.

```json
{
    "error_message": "Ip address already exists."
}
```
