# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Essential Commands
```bash
# Development server
php artisan serve
npm run dev              # Start Vite dev server with hot reload

# Testing
vendor/bin/pest          # Run test suite
vendor/bin/pest --parallel  # Run tests in parallel

# Code quality
php artisan pint         # Format code with Laravel Pint

# Database
php artisan migrate      # Run migrations
php artisan migrate:fresh --seed  # Fresh database with seed data

# Background jobs
php artisan queue:work   # Process background jobs (required for payments)

# Cache management
php artisan optimize     # Cache config, routes, views
php artisan optimize:clear  # Clear all caches
```

### Production Build
```bash
composer install --no-dev --optimize-autoloader
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Architecture Overview

### Technology Stack
- **Laravel 11** with PHP 8.2+
- **Filament v3** for admin panel
- **Livewire v3 + Volt** for frontend components  
- **Flux Pro** for UI components
- **Pest PHP** for testing
- **TailwindCSS** for styling

### Core Application Structure

**Business Logic (Actions)**
- Payment processing and order management
- Partner/affiliate system with promo codes
- Character and guild ranking calculations
- Game server status monitoring
- Castle siege prize distribution

**Models & Data**
- Game entities: Characters, Guilds, Game Servers
- User system with VIP packages and stealth mode
- Commerce: Orders, Token Packages, Payments
- Content: Articles, Downloads, Events
- Support: Ticketing system

**Admin Interface**
- Comprehensive Filament admin panel
- Real-time dashboards and widgets
- User management with 2FA
- Payment and order tracking
- Game server monitoring

### Key Features

**Multi-Payment Gateway**
- Stripe (Laravel Cashier), PayPal, Prime payment processor
- Background job processing for payment webhooks
- Order status tracking with full history

**Game Integration**
- Real-time server status via socket connections
- Player/guild ranking systems with weekly resets
- Virtual wallet system (Zen, Credits, WCoin, etc.)
- Castle siege rewards distribution

**Partner Program**
- Application workflow with approval process
- Automatic promo code generation
- Commission tracking and payouts

**Multi-Language Support**
- 6 languages: EN, BG, ES, PT, RO, RU
- JSON language files + Laravel translations
- Spatie translatable plugin for admin content

### Development Guidelines

**Database**
- Always use migrations for schema changes
- Server-specific settings use `server_id` column
- Activity logging enabled across the application

**Testing**
- Use Pest PHP for all tests
- Test files mirror app structure
- Livewire component testing included

**Game Server Integration**
- Socket timeout: 0.5s (configurable)
- Status caching: 3 minutes TTL
- Handle connection failures gracefully

**Payment Processing**
- All payments require background job processing
- Order status changes trigger history records
- Failed payments are logged and retryable