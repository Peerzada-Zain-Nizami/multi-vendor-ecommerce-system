# Multi-Vendor E-commerce System

A comprehensive multi-vendor e-commerce management platform built with Laravel 9, designed to handle complex business operations including dropshipping, warehouse management, and multi-channel integrations.

## 📋 Project Overview

This platform is a full-featured multi-vendor e-commerce system that enables businesses to manage multiple sellers, suppliers, products, orders, and shipping operations from a single centralized dashboard. The system supports various business models and integrates seamlessly with popular e-commerce platforms.

### Key Features

- **Multi-User Management**
  - Admin Dashboard with full control
  - Seller/Vendor Portal for product management
  - Supplier Management System
  
- **Dropshipping & Order Management**
  - Complete dropshipping workflow
  - Company orders tracking
  - Return & refund management
  - Order status automation

- **Inventory & Warehouse**
  - Multi-warehouse support
  - Stock tracking and management
  - Room, Rack, and Shelf organization
  - Stock-in/Stock-out operations
  - Final stock calculations

- **E-commerce Integrations**
  - WooCommerce API integration
  - Shopify API integration
  - Real-time product synchronization
  - Order import/export functionality

- **Shipping Management**
  - Multiple shipping company support
  - SMSA Express integration
  - Aramex shipping integration
  - Custom shipping cost calculations
  - City and zone-based shipping

- **Payment Systems**
  - PayPal integration
  - Subscription plans
  - Wallet management
  - Transaction history

- **Business Tools**
  - Multi-language support
  - Business model management
  - Category & product management
  - Barcode generation
  - Excel import/export
  - PDF generation for invoices

## 🛠️ Technical Stack

- **Framework:** Laravel 9.x
- **PHP Version:** 7.3+ | 8.0.2+
- **Database:** MySQL
- **Frontend:** 
  - Vue.js 2.6
  - Alpine.js
  - Tailwind CSS
  - Bootstrap 4
- **Build Tools:** Laravel Mix, Webpack
- **Key Packages:**
  - Laravel Sanctum (API authentication)
  - Laravel Excel (Data import/export)
  - Barcode Generator
  - DomPDF (PDF generation)
  - Shopify & WooCommerce SDKs

## 📦 Installation

### Prerequisites

- PHP >= 7.3 or PHP >= 8.0.2
- Composer
- MySQL Database
- Node.js & NPM
- XAMPP/WAMP/LAMP (for local development)

### Step 1: Clone the Repository

```bash
git clone https://github.com/Peerzada-Zain-Nizami/multi-vendor-ecommerce-system.git
cd multi-vendor-ecommerce-system
```

### Step 2: Install PHP Dependencies

```bash
composer install
```

### Step 3: Install Node Dependencies

```bash
npm install
```

### Step 4: Environment Configuration

1. Copy the example environment file:
```bash
cp .env.example .env
```

2. Configure your database in `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jalal
DB_USERNAME=root
DB_PASSWORD=your_password
```

3. Generate application key:
```bash
php artisan key:generate
```

### Step 5: Database Setup

1. Create a new MySQL database named `jalal`
2. Run migrations:
```bash
php artisan migrate
```

3. (Optional) Seed the database with sample data:
```bash
php artisan db:seed
```

### Step 6: Storage Link

Create symbolic link for storage:
```bash
php artisan storage:link
```

### Step 7: Compile Assets

Development:
```bash
npm run dev
```

Production:
```bash
npm run production
```

### Step 8: Run the Application

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## 🔧 Configuration

### Mail Configuration

Update `.env` file with your mail server details:
```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

### Payment Gateway Setup

#### PayPal Configuration
```env
PAYPAL_MODE=sandbox
PAYPAL_SANDBOX_CLIENT_ID=your_client_id
PAYPAL_SANDBOX_CLIENT_SECRET=your_client_secret
```

### API Integrations

#### WooCommerce
Configure WooCommerce credentials in admin panel under Settings → WooCommerce Setup

#### Shopify
Add Shopify API credentials in Settings → Shopify Integration

## 📱 Usage

### Default Access URLs

- **Admin Panel:** `/admin`
- **Seller Dashboard:** `/seller`
- **Supplier Dashboard:** `/supplier`
- **Warehouse Admin:** `/warehouse`

### User Roles

1. **Admin** - Full system access and configuration
2. **Seller/Vendor** - Product and order management
3. **Supplier** - Inventory and stock management
4. **Warehouse Admin** - Warehouse operations, stock management, and inventory control

## 🚀 Deployment

### Production Deployment

1. Set environment to production in `.env`:
```env
APP_ENV=production
APP_DEBUG=false
```

2. Optimize application:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

3. Set proper permissions:
```bash
chmod -R 755 storage bootstrap/cache
```

## 📚 Project Structure

```
├── app/
│   ├── Console/          # Artisan commands
│   ├── Http/
│   │   ├── Controllers/  # Application controllers
│   │   │   ├── Admin/    # Admin controllers
│   │   │   ├── Seller/   # Seller controllers
│   │   │   └── Supplier/ # Supplier controllers
│   │   ├── Middleware/   # Custom middleware
│   │   └── Requests/     # Form requests
│   ├── Models/           # Eloquent models
│   ├── Imports/          # Excel import classes
│   └── Notifications/    # Notification classes
├── config/               # Configuration files
├── database/
│   ├── migrations/       # Database migrations
│   └── seeders/          # Database seeders
├── public/               # Public assets
├── resources/
│   ├── views/            # Blade templates
│   ├── js/               # JavaScript files
│   └── css/              # Stylesheets
└── routes/
    ├── web.php           # Web routes
    ├── api.php           # API routes
    └── auth.php          # Authentication routes
```

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👥 Support

For support, please open an issue in the GitHub repository or contact the development team.

---

**Built with Laravel 9** • **Powered by PHP** • **Modern E-commerce Solution**
