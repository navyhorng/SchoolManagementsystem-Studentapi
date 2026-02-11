# Student API – Laravel Backend

A production-ready RESTful API built with **Laravel** for managing student data, authentication, attendance, and related features.

---

## Tech Stack

* **Backend:** Laravel
* **Database:** MySQL
* **Authentication:** Token / Sanctum (based on project setup)
* **API:** RESTful JSON API

---

## Requirements

* PHP ≥ 8.2
* Composer
* MySQL or compatible database
* Node.js (only if using Laravel frontend tools)

---

## Local Development Setup

### 1. Clone the repository

```bash
git clone https://github.com/your-username/your-repository.git
cd your-repository
```

### 2. Install dependencies

```bash
composer install
```

### 3. Create environment file

```bash
cp .env.example .env
```

Edit `.env` for local development:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=student_local
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Run migrations

```bash
php artisan migrate
```

### 6. Start development server

```bash
php artisan serve
```

API will be available at:

```
http://localhost:8000
```

---

## Production Deployment

### Environment setup

On the production server, create a `.env` file:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://api.yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=student_production
DB_USERNAME=prod_user
DB_PASSWORD=secure_password
```

### Install dependencies

```bash
composer install --optimize-autoloader --no-dev
```

### Generate key (if not set)

```bash
php artisan key:generate
```

### Run migrations

```bash
php artisan migrate --force
```

### Optimize for production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Environment Strategy

This project uses **one codebase** with different environment files:

| Environment       | File                       | Purpose                       |
| ----------------- | -------------------------- | ----------------------------- |
| Local development | `.env` (local values)      | Coding and testing            |
| Production server | `.env` (production values) | Live system                   |
| Template          | `.env.example`             | Shared configuration template |

> **Important:** The `.env` file is not committed to GitHub.

---

## API Base URL

| Environment | Base URL                         |
| ----------- | -------------------------------- |
| Local       | `http://localhost:8000/api`      |
| Production  | `https://api.yourdomain.com/api` |

---

## Security Notes

* Never commit `.env` to GitHub.
* Use strong passwords in production.
* Always set:

  ```env
  APP_DEBUG=false
  ```

---

## License

This project is open-sourced under the MIT license.
