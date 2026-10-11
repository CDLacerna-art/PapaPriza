# PapaPriza 🍕

A family-style pizza ordering web application built with Laravel. Customers can browse the menu, place pizza orders, track their order status, and cancel pending orders. Admins can view all incoming orders and update their statuses in real time.

---

## Features

| Feature | Who |
|       --|- - -|
| Browse pizza menu | Everyone |
| Place an order (via modal + Fetch API) | Logged-in customers |
| Track and cancel pending orders | Logged-in customers |
| Customer registration & login | Customers |
| View all orders & update order status | Admin |
| Admin login (email-based access control) | Admin |
| Contact / enquiry form | Everyone |

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 13 (PHP 8.3) |
| Database | SQLite (default) — switchable to MySQL/PostgreSQL via `.env` |
| CSS | Custom CSS (`public/css/style.css`) + Tailwind CSS (auth & dashboard pages) |
| Frontend JS | Vanilla JavaScript + Fetch API |
| Auth | Laravel Breeze scaffold (session-based) |
| Build tool | Vite (`npm run build`) |

---

## Requirements

- PHP **8.3** or higher (with `pdo_sqlite` or `pdo_mysql` extension)
- Composer
- Node.js + npm (for the Vite/Tailwind build)
- SQLite (bundled with PHP) **or** MySQL / PostgreSQL

---

## Installation

```bash
# 1. Clone the repository
git clone <your-repo-url> papapriza
cd papapriza

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies and build frontend assets
npm install
npm run build

# 4. Copy the environment file
cp .env.example .env

# 5. Generate the application key
php artisan key:generate

# 6. Configure the database in .env
#    The default is SQLite — no extra setup needed.
#    To use SQLite, make sure the file exists:
touch database/database.sqlite

#    To use MySQL instead, update these lines in .env:
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=papapriza
#    DB_USERNAME=root
#    DB_PASSWORD=

# 7. Run database migrations
php artisan migrate

# 8. Start the development server
php artisan serve
```

Open **http://localhost:8000** in your browser.

---

## Admin Account

The admin is identified by their **email address**. There is no separate admin role in the database — only the user whose email matches `ADMIN_EMAIL` in your `.env` can access the admin panel.

**Steps to set up the admin:**

1. Add this line to your `.env` file:
   ```
   ADMIN_EMAIL=your-admin@example.com
   ```
2. Register an account on the app using that exact email address (or create one via `php artisan tinker`).
3. Log in at **http://localhost:8000/admin/login**.

> If `ADMIN_EMAIL` is not set in `.env`, the default fallback is `admin@papapriza.local` (see `config/app.php`). **Change this before deploying to production.**

---

## Project Structure

```
app/
  Http/
    Controllers/
      AdminController.php         — Admin order list & status update
      AuthController.php          — Admin login / logout
      ContactController.php       — Contact form handling
      CustomerAuthController.php  — Customer register / login / logout
      CustomerOrderController.php — Customer order list & cancel
      OrderController.php         — Place a new order (Fetch API endpoint)
      ProfileController.php       — User profile edit & delete
resources/
  views/
    admin/          — Admin orders page
    auth/           — Breeze auth views (login, register, password reset)
    customer/       — Customer login, register, orders
    layouts/        — Shared app & guest layouts, navigation partial
    components/     — Blade UI components (buttons, inputs, etc.)
    home.blade.php  — Landing page
    menu.blade.php  — Pizza menu + order modal
    contact.blade.php — Contact / enquiry form
database/
  migrations/       — All table migrations
routes/
  web.php           — All web routes
  auth.php          — Breeze auth routes
public/
  css/style.css     — Main custom stylesheet
  Images/           — Logo and image assets
```

---

## Team Roles

| Role | Member |
|---|---|
| Backend Developer | *(name here)* |
| Frontend Developer | *(name here)* |
| Database Administrator | *(name here)* |
| System Support | *(name here)* |
| Documentation | *(name here)* |

---

## License

For educational / academic use only.
