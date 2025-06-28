## `GET /app/audit_log`

Retrieves a list of audit log entries from the system. Access to all logs is restricted to administrators; other users
can only retrieve their own logs.

### Description

This endpoint returns a JSON array containing details of audit log entries.
For 'admin', all audit logs are returned, otherwise, only audit logs associated with the current user will be returned.

---

## Request

### Headers

| Header          | Type   | Required | Description                            |
|-----------------|--------|----------|----------------------------------------|
| `Authorization` | string | ✅ Yes    | Bearer token of the authenticated user |

### Query Parameters

| Parameter   | Type      | Description                                                                                          |
|:------------|:----------|:-----------------------------------------------------------------------------------------------------|
| `userId`    | `integer` | Optional. Filter logs by a specific user ID. (Only effective for admins or if `at-user-id` matches). |
| `ipAddress` | `string`  | Optional. Filter logs by a specific IP address.                                                      |
| `sessionId` | `string`  | Optional. Filter logs by a specific session ID.                                                      |

## Success Response

### HTTP Status: `200 OK`

```json
[
    {
        "user_id": 101,
        "ip_address": "192.168.1.1",
        "action": "user_login",
        "changes": {
            "status": "success",
            "user_agent": "Mozilla/5.0"
        },
        "changes_made_at": "2023-10-27T10:00:00Z"
    },
    {
        "user_id": 101,
        "ip_address": "192.168.1.5",
        "action": "ip_address_added",
        "changes": {
            "new_ip": "10.0.0.1",
            "label": "Home Office"
        },
        "changes_made_at": "2023-10-27T10:15:00Z"
    }
]
```

#### Response Object Structure:

| Field             | Type      | Description                                                                                    | Example                  |
|:------------------|:----------|:-----------------------------------------------------------------------------------------------|:-------------------------|
| `user_id`         | `integer` | The ID of the user associated with the audit log entry.                                        | `101`                    |
| `ip_address`      | `string`  | The IP address from which the action was performed.                                            | `"192.168.1.1"`          |
| `action`          | `string`  | A descriptive string indicating the action performed (e.g., `user_login`, `ip_address_added`). | `"user_login"`           |
| `changes`         | `object`  | A JSON object detailing the changes or context of the action.                                  | `{"status": "success"}`  |
| `changes_made_at` | `string`  | The timestamp when the audit log entry was created (ISO 8601 format).                          | `"2023-10-27T10:00:00Z"` |
