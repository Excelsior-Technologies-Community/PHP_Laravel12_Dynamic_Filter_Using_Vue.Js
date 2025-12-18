# PHP_Laravel12_Dynamic_Filter_Using_Vue.Js

A modern and responsive product filtering system built with Laravel 12 backend and Vue 3 frontend. This project demonstrates real-time filtering, search, sorting, and pagination for product listings using RESTful APIs.

---

## Features

Filtering and Search

* Real-time search with debounce
* Category-based filtering
* Price range filtering (minimum and maximum)
* Multiple filters applied together
* Sorting by price, name, or date
* Pagination using Laravel paginator

User Experience

* Responsive design (mobile and desktop)
* Loading indicators
* Empty result messages
* Reset filters option

Technology Stack

* Backend: Laravel 12 (PHP 8.2+)
* Frontend: Vue 3 (Composition API)
* Styling: Tailwind CSS
* Build Tool: Vite
* Database: MySQL or PostgreSQL
* API: RESTful services

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

## Installation

### Clone the Repository

```bash
git clone https://github.com/yourusername/laravel-vue-dynamic-filter.git
cd laravel-vue-dynamic-filter
```

### Backend Setup

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

### Frontend Setup

```bash
npm install
npm run dev
```

Run backend server:

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

Products

```
GET /api/products
```

Query Parameters:

* search
* category_id
* min_price
* max_price
* sort_by
* sort_order
* page

Categories

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

`ProductFilter.vue` handles:

* Filter state management
* API requests
* Pagination handling
* UI rendering

---

## Customization

Add New Filter

Vue:

```vue
<input v-model="filters.new_field" @input="filterProducts" />
```

Controller:

```php
$query->when($request->new_field, function ($q, $value) {
    $q->where('new_column', $value);
});
```

Change Pagination Count

```php
$products = $query->paginate(20);
```

---

## Testing

Backend tests:

```bash
php artisan test
```

Frontend tests (optional):

```bash
npm test
```

---

## Deployment

Production steps:

```bash
composer install --no-dev --optimize-autoloader
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Troubleshooting

* Run `composer dump-autoload` for class errors
* Run `npm run dev` if assets are missing
* Check `.env` for database issues
* Review logs in `storage/logs/laravel.log`

---

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to your branch
5. Open a pull request

---

## screenshot
<img width="1456" height="969" alt="image" src="https://github.com/user-attachments/assets/40a90a1b-3e3c-420e-93f5-90478a04450b" />

