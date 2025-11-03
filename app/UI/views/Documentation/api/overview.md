# API Overview

Webtool 4.2 provides REST APIs for programmatic access to linguistic data.

## Base URL

```
http://localhost:8001/api
```

## Authentication

API endpoints use token-based authentication. Include your API token in the Authorization header:

```http
Authorization: Bearer YOUR_API_TOKEN
```

## Response Format

All responses are in JSON format:

```json
{
  "status": "success",
  "data": { ... },
  "message": "Operation completed successfully"
}
```

## Error Handling

Errors return appropriate HTTP status codes:

- `400`: Bad Request - Invalid parameters
- `401`: Unauthorized - Missing or invalid token
- `404`: Not Found - Resource doesn't exist
- `500`: Server Error - Internal server error

Error response format:

```json
{
  "status": "error",
  "message": "Error description",
  "errors": { ... }
}
```

## Rate Limiting

API requests are limited to:
- 60 requests per minute for authenticated users
- 10 requests per minute for unauthenticated requests

## Available Endpoints

- [Frames API](frames-api.md)
- [Lexical Units API](lexical-units-api.md)
- [Annotations API](annotations-api.md)
- [Search API](search-api.md)

## Code Examples

### PHP

```php
$response = Http::withToken($token)
    ->get('http://localhost:8001/api/frames');

$frames = $response->json('data');
```

### JavaScript

```javascript
fetch('http://localhost:8001/api/frames', {
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
    }
})
.then(response => response.json())
.then(data => console.log(data));
```

## Versioning

The API uses URL versioning. Current version is v1.

Future versions will be available at `/api/v2`, `/api/v3`, etc.
