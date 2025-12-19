# PHP_Laravel12_Dynamic_Filter_Using_Vue.Js

A modern and responsive product filtering system built with a **Laravel 12** backend and **Vue 3** frontend. This project demonstrates how to implement **real-time filtering, searching, sorting, and pagination** for product listings using RESTful APIs and a clean UI.

---

## Project Overview

This repository is designed to help developers understand how to:

* Build RESTful APIs in Laravel 12 for filtered data
* Consume APIs efficiently using Vue 3 (Composition API)
* Implement real-time search with debounce
* Apply multiple filters together
* Handle sorting and pagination on large datasets

This project is ideal for:

* Laravel + Vue beginners
* MCA / BCA academic projects
* Interview preparation
* Real-world product listing implementations

---

## Features

### Filtering and Search

* Real-time keyword search with debounce
* Category-based filtering
* Price range filtering (minimum & maximum)
* Multiple filters applied simultaneously
* Sorting by price, name, or created date
* Pagination using Laravel paginator

### User Experience

* Responsive UI (mobile & desktop)
* Loading indicators
* Empty result handling
* Reset filters option

---

## Technology Stack

### Backend

* Laravel 12
* PHP 8.2+
* RESTful API
* Eloquent ORM

### Frontend

* Vue 3 (Composition API)
* Tailwind CSS
* Vite build tool

### Database

* MySQL or PostgreSQL

---

## Prerequisites

Ensure the following are installed:

* PHP 8.2 or higher
* Composer
* Node.js 18+
* npm or yarn
* MySQL 8.0+ or PostgreSQL
* Git

---

## Installation Guide

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/laravel-vue-dynamic-filter.git
cd laravel-vue-dynamic-filter
```

### 2. Backend Setup (Laravel)

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Update database credentials in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dynamic_filter_db
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and seeders:

```bash
php artisan migrate --seed
```

### 3. Frontend Setup (Vue)

```bash
npm install
npm run dev
```

### 4. Start Laravel Server

```bash
php artisan serve
```

Application URL:

```
http://localhost:8000
```

---

## Project Structure

```
laravel-vue-dynamic-filter/
├── app/
│   ├── Http/Controllers/Api/ProductController.php
│   ├── Models/Product.php
│   └── Models/Category.php
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── resources/
│   ├── js/components/ProductFilter.vue
│   ├── js/App.vue
│   ├── views/app.blade.php
│   └── css/app.css
├── routes/api.php
├── routes/web.php
├── vite.config.js
├── tailwind.config.js
├── package.json
├── composer.json
└── README.md
```

---

## API Endpoints

### Products

```
GET /api/products
```

Query Parameters:

* `search`
* `category_id`
* `min_price`
* `max_price`
* `sort_by`
* `sort_order`
* `page`

### Categories

```
GET /api/categories
```

---

## Example API Request

```
GET /api/products?search=wireless&category_id=1&min_price=100&max_price=300&sort_by=price&sort_order=asc
```

---

## Vue Component Overview

The `ProductFilter.vue` component handles:

* Filter state management
* API requests using Axios or Fetch
* Pagination updates
* UI rendering and user interaction

---

## Customization

### Add a New Filter

**Vue Component**

```vue
<input v-model="filters.new_field" @input="filterProducts" />
```

**Laravel Controller**

```php
$query->when($request->new_field, function ($q, $value) {
    $q->where('new_column', $value);
});
```

### Change Pagination Count

```php
$products = $query->paginate(20);
```

---

## Testing

### Backend Tests

```bash
php artisan test
```

### Frontend Tests (Optional)

```bash
npm test
```

---

## Deployment (Production)

```bash
composer install --no-dev --optimize-autoloader
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Troubleshooting

* Run `composer dump-autoload` for class loading issues
* Run `npm run dev` if assets are missing
* Verify `.env` database configuration
* Check logs at `storage/logs/laravel.log`

---

## Screenshot

<img width="1456" height="969" alt="image" src="https://github.com/user-attachments/assets/40a90a1b-3e3c-420e-93f5-90478a04450b" />

---

