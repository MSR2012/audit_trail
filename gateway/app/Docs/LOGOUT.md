# `POST /auth/logout`

Logs out the authenticated user by invalidating their current token.

---

## Description

This endpoint terminates the session associated with the provided bearer token.

---

## Request

### Headers

| Header          | Type   | Required | Description                            |
|-----------------|--------|----------|----------------------------------------|
| `Authorization` | string | ✅ Yes    | Bearer token of the authenticated user |

### Example Request (cURL)

```bash
curl -X POST /api/logout \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJh..."
```

---

## Success Response

### HTTP Status: `200 OK`

```json
{
    "success_message": "Logged out successfully."
}
```

---

## Error Responses

### `401 Unauthorized` — Invalid or Expired Token

```json
{
    "error_message": "Invalid token."
}
```
