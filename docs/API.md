# BSS Booking API Documentation

Base URL: `{APP_URL}/api/v1`

Authentication: Bearer token (Laravel Sanctum)

## Authentication

### Register
`POST /auth/register`

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "03001234567",
  "password": "secret123",
  "password_confirmation": "secret123"
}
```

### Login
`POST /auth/login`

```json
{
  "email": "john@example.com",
  "password": "secret123"
}
```

Response includes `token` — use as `Authorization: Bearer {token}`.

### OTP
- `POST /auth/otp/send` — `{ "identifier": "email@example.com", "type": "email" }`
- `POST /auth/otp/verify` — `{ "identifier": "...", "otp": "123456", "type": "email" }`

## Route Search

### Search schedules
`GET /routes/search?from=Karachi&to=Lahore&date=2024-06-01`

### List cities
`GET /routes/cities`

## Seats

### Get seat map
`GET /schedules/{schedule_uuid}/seats`

### Hold seats
`POST /schedules/{schedule_uuid}/seats/hold`

```json
{
  "seat_ids": [1, 2, 3]
}
```

## Bookings (authenticated)

### Create booking
`POST /bookings`

```json
{
  "schedule_uuid": "...",
  "coupon_code": "WELCOME10",
  "payment_method": "cash",
  "passengers": [
    {
      "seat_id": 1,
      "full_name": "John Doe",
      "gender": "male",
      "cnic": "12345-1234567-1",
      "passenger_type": "adult"
    }
  ]
}
```

### List / show / cancel
- `GET /bookings` — paginated, filters: `status`, `search`
- `GET /bookings/{uuid}`
- `POST /bookings/{uuid}/cancel` — `{ "reason": "..." }`

### Verify QR
`POST /bookings/verify-qr` — `{ "qr_code": "..." }`

## Payments

`POST /bookings/{uuid}/payments`

```json
{
  "method": "jazzcash",
  "amount": 3500,
  "gateway_data": { "pp_TxnRefNo": "...", "pp_ResponseCode": "000" }
}
```

## Admin (role: super_admin, bus_company, stand_owner, staff)

`GET /admin/dashboard`

## Rate Limiting

60 requests per minute per IP (API middleware).

## Pagination

List endpoints return Laravel pagination: `data`, `links`, `meta`.
