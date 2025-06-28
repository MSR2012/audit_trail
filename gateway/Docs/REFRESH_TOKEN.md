# `POST /auth/refresh_token`

Refreshes the access token using a valid refresh token.

---

## Description

This endpoint allows a user to obtain a new access token by providing a valid, unexpired refresh token. If successful,
it also returns updated token expiration details and user information.

---

## Request

### Body Parameters

| Parameter       | Type   | Required | Description           |
|-----------------|--------|----------|-----------------------|
| `refresh_token` | string | ✅ Yes    | A valid refresh token |

### Example Request

```json
{
    "refresh_token": "your-refresh-token-here"
}
```

## Success Response

### HTTP Status: 200 OK

```
{
  "token": "newlyGeneratedJWTToken",
  "token_expires_at": "2025-07-01T12:00:00Z",
  "refresh_token": "your-refresh-token-here",
  "refresh_token_expires_at": "2025-08-01T12:00:00Z",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "admin"
  }
}
```

## Error Responses

### 401 Unauthorized — Invalid or Expired Refresh Token

```
{
"error_message": "Invalid refresh token."
}
```
