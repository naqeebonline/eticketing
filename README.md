# Bus Stand Management & Online Booking System (BSS)

Production-ready Laravel 11 platform for bus stand operations, scheduling, seat-based online booking, payments, and admin analytics.

## Features

- **Multi-role RBAC** — Super Admin, Bus Company, Stand Owner, Staff, Driver, Conductor, Passenger
- **Bus stand & fleet management** — Stands, vehicles, dynamic seat maps, drivers, conductors
- **Route & scheduling** — Multi-stop routes, conflict detection, fare management
- **Online booking** — Search, seat selection, temporary holds, QR tickets
- **Payments** — Cash, Stripe, JazzCash, Easypaisa (gateway-ready)
- **Admin dashboard** — Revenue, bookings, route performance
- **REST API** — Sanctum-authenticated mobile/API clients
- **Notifications** — Email, database (SMS/WhatsApp hooks ready)
- **Extras** — Coupons, loyalty points, referrals, reviews, complaints, live tracking schema

## Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 11, PHP 8.2+ |
| Database | MySQL 8+ |
| Auth | Laravel Sanctum + Spatie Permission |
| Frontend | Blade, Tailwind CSS 3, Alpine.js, Vite |
| Queue | Database driver (Redis-ready) |
| Real-time | Laravel Echo / Pusher (configured) |

## Requirements

- PHP 8.2+ with extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
- Composer 2.x
- Node.js 18+ & npm
- MySQL 8.0+

## Installation

```bash
# Clone / enter project
cd BSS

# Install PHP dependencies
composer install

# Environment
cp .env.example .env
php artisan key:generate

# Configure .env — set DB_* credentials, mail, Pusher, payment keys

# Install frontend
npm install
npm run build

# Database
php artisan migrate --seed

# Storage link
php artisan storage:link

# Run (development)
php artisan serve
# In another terminal:
php artisan queue:work
npm run dev
```

Visit: `http://localhost:8000`

## Demo Accounts (3 roles)

| Role | Email | Password | Access |
|------|-------|----------|--------|
| Super Admin | admin@bssbooking.com | password | Full system + all bus stands |
| Bus Stand Admin | stand@bssbooking.com | password | Own bus stand(s) only |
| Passenger | passenger@bssbooking.com | password | Public booking site |

**Roles in database:** `super_admin`, `admin`, `passenger`

## Project Structure

```
app/
├── Contracts/Repositories/   # Repository interfaces
├── Repositories/             # Eloquent repositories
├── Services/                 # Business logic (Booking, Payment, Auth, Reports)
├── Http/Controllers/
│   ├── Api/V1/              # REST API
│   ├── Admin/               # Admin panel
│   └── Passenger/           # Public booking flow
├── Models/                   # Eloquent models + relationships
├── Enums/                    # Status enums
└── Jobs/                     # Queued jobs
database/migrations/          # Full schema
resources/views/              # Blade + Tailwind UI
routes/api.php                  # API v1 routes
routes/web.php                  # Web + admin routes
docs/API.md                     # API documentation
```

## API

See [docs/API.md](docs/API.md) for full REST documentation.

Base: `/api/v1` — authenticate with `Authorization: Bearer {token}`.

## Deployment

### Production checklist

1. Set `APP_ENV=production`, `APP_DEBUG=false`
2. Run `php artisan config:cache`, `route:cache`, `view:cache`
3. Configure queue worker (Supervisor) for `php artisan queue:work`
4. Schedule cron: `* * * * * php /path/to/artisan schedule:run`
5. Use Redis for cache/queue in production
6. Configure SSL, MySQL backups, and S3 for media (optional)
7. Set real payment gateway credentials in `.env`

### Nginx example

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/bss/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### Supervisor (queues)

```ini
[program:bss-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/bss/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=2
```

## Security

- CSRF on web routes
- Sanctum API tokens with expiration
- Spatie role/permission middleware
- Rate limiting on API (60/min)
- Mass assignment protection on models
- Activity logging (Spatie Activity Log)

## License

MIT
