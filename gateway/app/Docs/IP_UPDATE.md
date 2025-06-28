# `PUT /app/ips/{id}`

Updates the `label` and `comment` for an existing IP address identified by its ID.

### Description

This endpoint allows you to modify the `label` and `comment` of an IP address. The `ip_address` itself cannot be changed
through this endpoint. You must provide the `id` of the IP address to be updated in the URL path.

---

## Request

### Headers

| Header          | Type   | Required | Description                            |
|-----------------|--------|----------|----------------------------------------|
| `Authorization` | string | ✅ Yes    | Bearer token of the authenticated user |

### Path Parameters

| Parameter | Type     | Description                                        |
|:----------|:---------|:---------------------------------------------------|
| `id`      | `string` | The unique identifier of the IP address to update. |

### Body Parameters

| Parameter | Type     | Required | Description                                                                                                         |
|:----------|:---------|:---------|:--------------------------------------------------------------------------------------------------------------------|
| `label`   | `string` | ✅ Yes    | The new short, descriptive label for the IP address.                                                                |
| `comment` | `string` | ❌ No     | The new optional longer comment or description for the IP address. If not provided, it defaults to an empty string. |

### Example Request

```json
{
    "label": "Updated Office PC",
    "comment": "Main desktop in the office, updated label."
}
```

---

## Success Response

### HTTP Status: `200 OK`

```json
{
    "id": 123,
    "user_id": 101,
    "ip_address": "192.168.1.100",
    "label": "Updated Office PC",
    "comment": "Main desktop in the office, updated label.",
    "message": "Ip updated successfully."
}
```

#### Response Object Structure:

| Field        | Type             | Description                                                                                                                   | Example                       |
|:-------------|:-----------------|:------------------------------------------------------------------------------------------------------------------------------|:------------------------------|
| `id`         | `integer`        | The unique identifier for the updated IP address.                                                                             | `123`                         |
| `user_id`    | `integer`        | The ID of the user associated with this IP address.                                                                           | `101`                         |
| `ip_address` | `string`         | The IP address itself (remains unchanged).                                                                                    | `"192.168.1.100"`             |
| `label`      | `string`         | The updated label assigned to the IP address.                                                                                 | `"Updated Office PC"`         |
| `comment`    | `string \| null` | The updated comment for the IP address, or `null` if explicitly set to null or not provided and defaults to `null` in the DB. | `"Main desktop..."` \| `null` |
| `message`    | `string`         | A success message indicating the IP was updated.                                                                              | `"Ip updated successfully."`  |

---

## Error Responses

### `400 Bad Request` — Validation Errors

Occurs when request body parameters fail validation rules.

```json
{
    "errors": {
        "label": [
            "The label field is required."
        ]
    }
}
```

### `404 Not Found` — IP Not Found

Occurs when no IP address with the specified ID is found for update.

```json
{
    "message": "Ip not found."
}
```
