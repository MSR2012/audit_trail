# `POST /auth/login`

Authenticates a user using email and password.

---

## Description

This endpoint handles user login by validating the email and password. It enforces rate-limiting to prevent brute-force
attacks.

---

## Request

### Headers

| Header         | Type   | Required | Description                |
|----------------|--------|----------|----------------------------|
| `Content-Type` | string | ✅ Yes    | Must be `application/json` |

### Body Parameters

| Parameter  | Type   | Required | Description                   |
|------------|--------|----------|-------------------------------|
| `email`    | string | ✅ Yes    | The user’s email address      |
| `password` | string | ✅ Yes    | The user’s plaintext password |

### Example Request

```json
{
    "email": "user@example.com",
    "password": "securePassword123"
}
```

---

## Success Response

### HTTP Status: `200 OK`

```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "role": "admin"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJh...",
    "refresh_token": "eyJ0eXxdvdgfJKV1QiLCJh..."
}
```

---

## Error Responses

### `401 Unauthorized` — Too Many Failed Attempts

```json
{
    "error_message": "Too many attempts."
}
```

### `401 Unauthorized` — Invalid Credentials

```json
{
    "error_message": "Invalid email or password."
}
```

### `400 Bad Request` — Validation Errors

```json
{
    "error_message": "The email field is required., The password field is required."
}
```
