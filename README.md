# 📄 Invoice Management System — Documentation
### CellHub Manager | Laravel 12 Web Application

---

## 🔑 Admin Login Credentials

> **Note:** Project start karala seeding karanakota me credentials use karanna.

| Field | Value |
|-------|-------|
| **URL** | http://localhost:8000 |
| **Email** | `admin@gmail.com` |
| **Password** | `admin123` |
| **Role** | Admin (All Permissions) |

---

## 🏗️ Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | Laravel 12.1.1 (PHP) |
| **Frontend** | Blade Templates + TailwindCSS |
| **Database** | SQLite (local) |
| **Authentication** | Laravel Breeze |
| **Permissions** | Spatie Laravel Permission |
| **PDF Generation** | barryvdh/laravel-dompdf |
| **Build Tool** | Vite 6 |
| **Icons** | Blade Icons (FontAwesome + Google Material) |

---

## 🚀 Quick Start Guide (Fresh Clone)

### Step 1 — Dependencies Install
```bash
composer install
npm install
```

### Step 2 — Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### Step 3 — Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### Step 4 — Start Servers
```bash
# Terminal 1 — PHP Server
php artisan serve

# Terminal 2 — Vite Dev Server (CSS/JS)
npm run dev
```

### Step 5 — Open Browser
```
http://localhost:8000
```
Login with: `admin@gmail.com` / `admin123`

---

## 📁 Project File Structure

```
Invoice Management System (WEB)/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── DashboardController.php    ← Dashboard + Income chart
│   │   │   ├── InvoiceController.php      ← Invoice CRUD + PDF
│   │   │   ├── ClientController.php       ← Client management
│   │   │   ├── ProductController.php      ← Product management
│   │   │   ├── CategoryController.php     ← Category management
│   │   │   ├── UserController.php         ← User management
│   │   │   ├── RoleController.php         ← Role management
│   │   │   └── SettingsController.php     ← App settings
│   │   └── Requests/                      ← Form validation
│   └── Models/
│       ├── Invoice.php
│       ├── Client.php
│       ├── Product.php
│       ├── Category.php
│       ├── ProductInvoice.php
│       ├── Setting.php
│       └── User.php
├── database/
│   ├── migrations/                        ← Database tables
│   └── seeders/
│       ├── DatabaseSeeder.php             ← Main seeder
│       ├── AdminUserSeeder.php            ← Admin credentials
│       ├── PermissionSeeder.php           ← All permissions
│       ├── SettingSeeder.php              ← Default company settings
│       └── ExampleDataSeeder.php          ← Sample data
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── app.blade.php              ← Main layout (sidebar + nav)
│   │   │   └── navigation.blade.php      ← Top navigation bar
│   │   ├── dashboard/                     ← Dashboard views
│   │   ├── invoices/                      ← Invoice views
│   │   ├── clients/                       ← Client views
│   │   ├── products/                      ← Product views
│   │   ├── categories/                    ← Category views
│   │   ├── stock/                         ← Stock views
│   │   ├── users/                         ← User views
│   │   └── roles/                         ← Role views
│   └── css/app.css                        ← TailwindCSS entry
└── routes/
    ├── web.php                            ← All web routes
    └── auth.php                           ← Auth routes
```

---

## 🗄️ Database Tables

| Table | Description |
|-------|-------------|
| `users` | System users (admin, staff) |
| `roles` | User roles (Admin, Staff, etc.) |
| `permissions` | Individual permissions |
| `clients` | Customer/client records |
| `categories` | Product/service categories |
| `products` | Products with stock tracking |
| `invoices` | Invoice records |
| `product_invoices` | Invoice-product pivot table |
| `settings` | App configuration (company info) |
| `sessions` | User sessions (file-based) |
| `cache` | Application cache |
| `jobs` | Background jobs queue |

---

## ✅ Features

### 📊 Dashboard
- Total clients, invoices, products, income overview
- Invoice status pie chart (Paid / Unpaid / Partially Paid / Overdue / Processing)
- Income trend line chart with date range filter

### 🧾 Invoices
- Create / Edit / Delete invoices
- Add multiple products 
- Discount support (percentage or fixed amount)
- Invoice status: `unpaid`, `paid`, `partially_paid`, `overdue`, `processing`
- Print invoice (browser print)
- Download invoice as **PDF**
- Filter by status, date range, client
- Search by client name
- Pagination (15 per page)

### 👥 Clients
- Create / Edit / Delete clients
- Fields: title, first name, last name, country, passport no, address, company, mobile, email
- Invoice count per client
- Search by first name / last name
- Pagination (15 per page)

### 📦 Products
- Create / Edit / Delete products
- Category assignment
- Unit price + quantity (stock) tracking
- Product image upload
- Unique name per category enforced
- Pagination (15 per page)

### 📁 Categories
- Create / Edit / Delete categories
- Search by name

### ⚙️ Settings
- Company name, phone, address, country, city, zip
- App name + logo upload
- Used on invoice print/download PDFs

### 👤 Users & Roles
- Create users and assign roles
- Create custom roles with specific permissions
- Full permission-based access control

---

## 🔐 Permissions System

All permissions managed by **Spatie Laravel Permission**.

| Module | Permissions |
|--------|------------|
| **Users** | `user-list`, `user-create`, `user-edit`, `user-delete` |
| **Roles** | `role-list`, `role-create`, `role-edit`, `role-delete` |
| **Categories** | `category-list`, `category-create`, `category-edit`, `category-delete` |
| **Products** | `product-list`, `product-create`, `product-edit`, `product-delete` |
| **Clients** | `client-list`, `client-create`, `client-edit`, `client-delete` |
| **Invoices** | `invoice-list`, `invoice-create`, `invoice-edit`, `invoice-delete`, `invoice-download`, `invoice-print` |
| **Settings** | `settings` |

> **Admin** role has ALL permissions automatically assigned.

---

## 🌐 Route Reference

| Method | URL | Controller | Permission |
|--------|-----|------------|------------|
| GET | `/dashboard` | DashboardController@index | auth |
| GET | `/invoices` | InvoiceController@index | invoice-list |
| GET | `/invoices/create` | InvoiceController@create | invoice-create |
| POST | `/invoices` | InvoiceController@store | invoice-create |
| GET | `/invoices/{id}` | InvoiceController@show | invoice-list |
| GET | `/invoices/{id}/edit` | InvoiceController@edit | invoice-edit |
| PUT | `/invoices/{id}` | InvoiceController@update | invoice-edit |
| DELETE | `/invoices/{id}` | InvoiceController@destroy | invoice-delete |
| GET | `/invoices/{id}/print` | InvoiceController@print | invoice-print |
| GET | `/invoices/{id}/download` | InvoiceController@download | invoice-download |
| GET | `/clients` | ClientController@index | client-list |
| GET | `/products` | ProductController@index | product-list |
| GET | `/categories` | CategoryController@index | category-list |
| GET | `/stock-handle` | StockController@index | product-edit |
| GET | `/settings` | SettingsController@index | settings |
| GET | `/users` | UserController@index | user-list |
| GET | `/roles` | RoleController@index | role-list |

---

## 🛠️ Common Artisan Commands

```bash
# Start development servers
php artisan serve                    # PHP server (port 8000)
npm run dev                         # Vite dev server

# Database
php artisan migrate                  # Run new migrations
php artisan migrate:fresh --seed     # Fresh DB + seed all data
php artisan db:seed                  # Seed without fresh
php artisan db:seed --class=AdminUserSeeder  # Seed only admin user

# Cache (run after any code change in production)
php artisan optimize                 # Cache config + routes + views
php artisan optimize:clear           # Clear all cache (use in development)
php artisan view:clear               # Clear only blade cache
php artisan config:clear             # Clear only config cache

# Other
php artisan route:list               # See all routes
php artisan tinker                   # Interactive PHP shell
php artisan make:controller NameController  # Create new controller
php artisan make:model Name -m       # Create model + migration
php artisan make:migration name      # Create migration
```

---

## 🔧 Environment Configuration (.env)

```env
APP_NAME=Laravel
APP_ENV=local               # Change to 'production' for live server
APP_KEY=base64:...          # Auto-generated by key:generate
APP_DEBUG=true              # Set false in production
APP_URL=http://localhost

DB_CONNECTION=sqlite        # Uses database/database.sqlite

SESSION_DRIVER=file         # file is faster for local dev
CACHE_STORE=file            # file is faster for local dev
```

### Switch to MySQL (if needed)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=invoice_db
DB_USERNAME=root
DB_PASSWORD=
```
Then run: `php artisan migrate:fresh --seed`

---

## ⚡ Performance Notes

The following optimizations have been applied:

| Optimization | Details |
|-------------|---------|
| **Laravel Cache** | `php artisan optimize` — config, routes, views cached |
| **DB Indexes** | `invoices.status`, `invoices.invoice_date`, `clients.first_name`, `products.name`, `services.name` |
| **Pagination** | All list pages paginate 15 records (invoices, products, services, clients) |
| **Eager Loading** | `with('client')`, `with('category')`, `with('productInvoices')` — prevents N+1 |
| **Dashboard Query** | Single `groupBy('status')` query instead of 9 separate queries |
| **Stock Bulk Update** | Single `whereIn` pre-load instead of per-product queries |
| **Session/Cache Driver** | `file` instead of `database` — reduces DB load |

> **Tip:** Development walata always `php artisan optimize:clear` use karanna. Production walata `php artisan optimize` use karanna.

---

## 🐛 Troubleshooting

### Page blank / 500 error
```bash
php artisan optimize:clear
php artisan config:clear
# Check storage permissions
```

### Vendor folder missing (fresh clone)
```bash
composer install
```

### APP_KEY error
```bash
php artisan key:generate
```

### Database error / table not found
```bash
php artisan migrate
# or fresh start:
php artisan migrate:fresh --seed
```

### Vite assets not loading (CSS/JS missing)
```bash
npm install
npm run dev
# Make sure Vite server is running alongside PHP server
```

### Pagination links not showing
Pagination shows only when records > 15. With sample data it may not show.

### Permission denied error on login
Run seeder to assign permissions:
```bash
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=AdminUserSeeder
```

---

## 📊 Default Company Settings (from SettingSeeder)

| Field | Default Value |
|-------|--------------|
| **App Name** | CellHub Manager |
| **Company Name** | CellHub Premium Store |
| **Phone** | +94 77 123 4567 |
| **Country** | Sri Lanka |
| **State** | Western Province |
| **City** | Colombo 03 |
| **Zip Code** | 00300 |
| **Fax** | +94 11 234 5678 |
| **Address** | No. 45, Galle Road, Colombo 03, Sri Lanka |

> Settings page (`/settings`) walata gihilla change karanna puluwan.

---

## 📦 Key Composer Packages

| Package | Purpose |
|---------|---------|
| `laravel/framework` v12 | Core framework |
| `laravel/breeze` | Auth (login/register/profile) |
| `spatie/laravel-permission` | Role & permission management |
| `barryvdh/laravel-dompdf` | PDF generation |
| `blade-ui-kit/blade-icons` | SVG icon components |
| `laravel/tinker` | Interactive REPL |

---

*Documentation generated: 2026-06-30 | Project: Invoice Management System (CellHub Manager)*
