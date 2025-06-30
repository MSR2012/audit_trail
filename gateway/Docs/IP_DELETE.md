# `DELETE /app/ips/{id}`

Deletes an IP address from the system identified by its ID.

### Description

This endpoint allows you to remove an IP address entry from the system. You must provide the `id` of the IP address to
be deleted in the URL path. If the IP address is successfully deleted, a success message is returned. If the IP address
is not found, a `404 Not Found` error is returned.

---

## Request

### Headers

| Header          | Type   | Required | Description                            |
|-----------------|--------|----------|----------------------------------------|
| `Authorization` | string | ✅ Yes    | Bearer token of the authenticated user |

### Path Parameters

| Parameter | Type     | Description                                        |
|:----------|:---------|:---------------------------------------------------|
| `id`      | `string` | The unique identifier of the IP address to delete. |

### Example Request

`DELETE /api/ips/123`

---

## Success Response

### HTTP Status: `200 OK`

```json
{
    "message": "Ip deleted successfully."
}
```

#### Response Object Structure:

| Field     | Type     | Description                                      | Example                      |
|:----------|:---------|:-------------------------------------------------|:-----------------------------|
| `message` | `string` | A success message indicating the IP was deleted. | `"Ip deleted successfully."` |

---

## Error Responses

### `404 Not Found` — IP Not Found

Occurs when no IP address with the specified ID is found for deletion.

```json
{
    "error_message": "Ip not found."
}
```
