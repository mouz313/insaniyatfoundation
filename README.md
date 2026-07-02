# 🩸 Blood & Money Donation Management System

A comprehensive Laravel-based donation management platform featuring a **public portal** for donor self-registration and blood availability lookup, and a full-featured **admin panel** with donor management, blood donation tracking, blood request matching, inventory control, campaign management, and monetary donation handling.

## Features

### Public Portal
- **Landing Page** — NGO hero section, stats counters, blood group availability, featured campaigns, how-it-works steps, donor testimonials, and contact CTA
- **Donor Registration** — Self-registration form with city/area cascading dropdowns, referrer search by CNIC/phone, and confirmation page
- **Blood Availability** — Search eligible donors by blood group and city
- **Donor Verification** — Lookup donor details by CNIC
- **Referrer Profile** — Public profile of referring donors
- **Language Support** — English / Urdu (اردو) locale switching
- **Responsive Design** — Bootstrap 5 + custom CSS with animations

### Admin Panel
- **Dashboard** — Aggregate stats: donor counts by blood group, donation trends, money stats, low stock alerts, recent donations, upcoming campaigns (cached 300s)
- **Donor Management**
  - Full CRUD with photo upload
  - Duplicate detection (CNIC/phone)
  - Eligibility checking (age 18–60, weight >45kg, hemoglobin >12.5, health flags, 3-month cooldown)
  - Certificate generation (PDF with QR code)
  - Donor card printing (PDF with QR code, mark-as-printed workflow)
  - Badge gamification (First Drop, Regular, Super, Lifesaver, Highly Reliable, Referrer)
  - Follow-up scheduling
- **Blood Donations** — Track donation events linked to donors, campaigns, and blood requests; filterable by status, donor, blood group, date range
- **Blood Requests & Patient Management**
  - Create/edit/close patient blood requests
  - **Smart Donor Matching** — 5-dimensional scoring algorithm:
    - Blood group compatibility (30 pts)
    - Same city (20 pts)
    - Reliability / prior donations (20 pts)
    - Days since last donation (15 pts)
    - Donation history volume (15 pts)
  - Call logging workflow with outcome tracking (donor found / no answer / refused / call back)
- **Blood Inventory**
  - Track units by all 8 blood groups with batch numbers, expiry dates, and locations
  - Low-stock alerts (configurable threshold)
  - Status management (available / reserved / expired / discarded)
- **Monetary Donations** — Track cash, bank transfer, JazzCash, and Easypaisa donations with receipt PDF generation
- **Campaign / Event Management**
  - Create blood drives with target units, venue, and dates
  - Attendance sheet PDF generation
  - Featured campaigns on landing page
- **Staff & Access Control**
  - Role-based permissions (Spatie `laravel-permission`)
  - Staff user management (excluding super_admin)
  - Granular permissions for donors, donations, inventory, requests, campaigns, reports, settings
- **Reporting** — Generate reports in PDF (DomPDF), Excel (Laravel Excel), and Word (PhpWord) formats for:
  - Donors
  - Blood donations
  - Blood requests
  - Money donations
  - Progress reports
- **Settings** — NGO name/logo/favicon, SMS gateway config, card template, footer management, Artisan command runner
- **Audit Logs** — Full activity trail via Spatie `laravel-activitylog`
- **Global Search** — AJAX search across donors, donations, requests, and campaigns
- **Profile Management** — Admin user profile editing with photo upload

## Tech Stack

| Layer | Technology |
|-------|-----------|
| **Framework** | Laravel 13 |
| **PHP** | ^8.3 |
| **Database** | SQLite (dev) / MySQL (production) |
| **Admin UI** | AdminLTE 3 (`jeroennoten/laravel-adminlte`) |
| **Permissions** | Spatie `laravel-permission` |
| **Activity Logs** | Spatie `laravel-activitylog` |
| **PDF** | DomPDF (`barryvdh/laravel-dompdf`) |
| **QR Codes** | `simplesoftwareio/simple-qrcode` |
| **Excel** | Laravel Excel (`maatwebsite/excel`) |
| **Word** | PhpWord (`phpoffice/phpword`) |
| **Image Processing** | Intervention Image (`intervention/image-laravel`) |
| **Frontend** | Vite + Tailwind CSS (admin), Bootstrap 5 (portal) |
| **Icons** | Font Awesome 6 |
| **Queue/Cache/Session** | Database driver |

## Database Schema

**Core Tables** (~22 application tables):

| Table | Purpose |
|-------|---------|
| `donors` | Central donor records with eligibility fields, health flags (JSON), badges, referral tracking |
| `blood_donations` | Blood donation events linked to donors, campaigns, and blood requests |
| `blood_requests` | Patient/hospital blood requests with status workflow |
| `blood_inventory` | Blood stock tracking by group, batch, expiry, and location |
| `money_donations` | Monetary donation records with receipt numbers |
| `campaigns` | Blood drive events with target units and featured flag |
| `call_logs` | Phone call outcomes during donor matching |
| `follow_ups` | Scheduled donor follow-ups |
| `cities` / `areas` / `universities` | Geography & education lookup tables |
| `badges` / `donor_badge` | Gamification badge definitions and donor awards (pivot) |
| `donor_stories` | Testimonials for the public landing page |
| `settings` / `landing_settings` | Key-value application configuration |
| `users` | Admin/staff authentication |
| `permissions` / `roles` / `model_has_roles` / `role_has_permissions` | Spatie RBAC |
| `activity_log` | Spatie audit trail |

## Installation

### Prerequisites
- PHP ^8.3
- Composer
- Node.js & npm
- SQLite or MySQL

### Setup

```bash
# Clone the repository
git clone <repository-url> donation
cd donation

# Install PHP dependencies
composer install

# Environment setup
cp .env.example .env
php artisan key:generate

# Configure database in .env (SQLite default)
# DB_CONNECTION=sqlite

# Create SQLite database (if using SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate

# Seed initial data (optional)
php artisan db:seed

# Install & build frontend assets
npm install
npm run build

# Storage link for public uploads
php artisan storage:link

# Start development server
php artisan serve
```

### Quick Setup (from scratch)

```bash
composer run setup
```

This runs: `composer install`, creates `.env`, generates key, runs migrations, installs npm, and builds assets.

### Development Server

```bash
composer dev
```

Runs concurrently: `php artisan serve`, `php artisan queue:listen`, `php artisan pail`, and `npm run dev`.

## Default Admin Access

Create an admin user via seeder or tinker:

```bash
php artisan tinker
>>> App\Models\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password')]);
```

Then log in at `/admin/login`.

## SMS Integration

SMS credentials are managed through the **admin settings panel** (not `.env`). Supported gateways:
- **Twilio** (stub — implement `sendViaTwilio()` in `app/Services/SmsService.php`)
- **BulkSMS.pk** (stub — implement `sendViaBulkSms()` in `app/Services/SmsService.php`)

Without configuration, SMS messages are logged to `laravel.log`.

## Architecture Overview

```
├── app/
│   ├── Console/              # Artisan commands
│   ├── Exports/              # Laravel Excel export classes
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/        # 24 admin controllers
│   │   │   ├── Auth/         # Login/logout
│   │   │   └── Portal/       # 4 public controllers
│   │   └── Middleware/       # Custom middleware
│   ├── Models/               # 16 Eloquent models
│   ├── Providers/            # Service providers
│   └── Services/             # MatchingService, SmsService
├── config/                   # 12 config files
├── database/
│   ├── migrations/           # 36 migration files
│   ├── factories/            # Model factories
│   └── seeders/              # Database seeders
├── resources/views/
│   ├── admin/                # 21 admin section directories
│   ├── portal/               # Public portal views
│   ├── auth/                 # Login view
│   └── vendor/               # Published vendor views
├── routes/
│   ├── web.php               # 122 routes (admin + portal)
│   ├── auth.php              # Login/logout routes
│   └── console.php           # Console routes
├── public/
│   ├── build/                # Vite-built assets
│   ├── storage/              # Symlinked storage
│   └── vendor/               # AdminLTE assets
└── tests/                    # PHPUnit tests
```

## Donor Matching Algorithm

The `MatchingService` scores eligible donors for a blood request across 5 dimensions (100 pts total):

| Criterion | Weight | Logic |
|-----------|--------|-------|
| Blood Group | 30 | Exact match = 30, compatible = 21, incompatible = 0 |
| Same City | 20 | Donor city matches request city = 20 |
| Reliability | 20 | Has prior donations = 20 |
| Days Since Donation | 15 | No donation or >730 days = 15; scales 90–730 days; 0 if <90 days |
| Donation History | 15 | `min(15, total_donations × 3)` |

## Eligibility Rules

A donor is eligible to donate blood when all conditions are met:
- Age between 18–60 years
- Weight >45 kg
- Hemoglobin >= 12.5 g/dL
- No flagged health issues (recent illness, pregnancy, recent tattoo, medication, chronic disease, high-risk behavior)
- At least 3 months since last donation

## Deployment Checklist

### Environment (`.env` on server — not in git)
- `APP_ENV=production`
- `APP_DEBUG=false` — critical: prevents stack trace leaks
- `APP_KEY` — generate on production server: `php artisan key:generate`
- `APP_URL` — set to production domain (`https://...`)
- `LOG_LEVEL=error` — `debug` in production logs sensitive data
- `SESSION_ENCRYPT=true`
- `DB_CONNECTION=mysql` — SQLite is dev-only
- Dedicated DB user (not root) with limited privileges
- SMS gateway keys set via admin panel (stored in DB, not `.env`)

### HTTPS
- SSL certificate (Let's Encrypt via Certbot)
- HTTPS forced in `bootstrap/app.php` when `APP_ENV=production`

### Queue Worker (background jobs)
Install Supervisor and create `/etc/supervisor/conf.d/donation-worker.conf`:
```ini
[program:donation-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/donation/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=2
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/donation/storage/logs/worker.log
```
Then: `supervisorctl reread && supervisorctl update && supervisorctl start donation-worker:*`

### Scheduled Tasks (cron)
Add to server crontab:
```cron
* * * * * cd /path/to/donation && php artisan schedule:run >> /dev/null 2>&1
```
Currently scheduled commands:
- `app:database-backup --keep=10` — daily at 02:00
- `app:process-donor-follow-ups` — daily at 03:00
- `app:sync-donor-badges` — weekly on Sunday at 04:00

### Error Monitoring
- Sentry installed (`sentry/sentry-laravel`). Set `SENTRY_LARAVEL_DSN` in `.env`.
- Free tier sufficient for small NGO deployments.

### Database
- Run: `php artisan migrate --force`
- Key indexed columns: `donors.cnic`, `donors.phone`, `donors.blood_group`, `donors.city_id`, `donors.status`
- Automated daily backups via `app:database-backup` command
- Test backup restoration before relying on it

### File Permissions
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
php artisan storage:link
```

### Build & Optimize
```bash
composer install --optimize-autoloader --no-dev
npm install && npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
Re-run these commands on every deploy.

### Smoke Test (immediately after deploy)
- Submit public registration form
- Log into admin panel, verify dashboard loads
- Test SMS delivery (if gateway configured)
- Test PDF exports (certificate, donor card, reports)
- Hit `/up` health check — expect 200

## License

MIT
