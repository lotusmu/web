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

- **Laravel 11** with PHP 8.3+ (targeting PHP 8.4 features)
- **Filament v3** for admin panel
- **Livewire v3 with Volt Class API** for frontend components
- **Flux Pro (fluxui.dev)** for UI components
- **TailwindCSS** for styling
- **AlpineJS** for JavaScript interactions
- **Pest PHP** for testing

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

## Development Guidelines

### Code Architecture

- **Action Classes**: Use action classes with `handle()` method for business logic
- **Dependency Injection**: Inject action classes into controllers/components where possible
- **Livewire Volt**: Use Volt Class API for all Livewire components
- **Simplicity First**: Avoid overcomplicating code - prefer readable, maintainable solutions

### PHP Standards

- Use PHP 8.3+ features (readonly properties, enums, union types, etc.)
- Target PHP 8.4 features when available
- Follow Laravel 11+ conventions and features
- Use typed properties and return types consistently

### Frontend Development

- **Livewire Volt Class API** for component logic
- **Flux Pro components** for UI elements
- **TailwindCSS** for styling (no custom CSS where possible)
- **AlpineJS** for JavaScript interactions

### Database

- Always use migrations for schema changes
- Server-specific settings use `server_id` column
- Activity logging enabled across the application

### Testing

- Use Pest PHP for all tests
- Test files mirror app structure
- Livewire component testing included
- Action classes should be unit tested

### Game Server Integration

- Socket timeout: 0.5s (configurable)
- Status caching: 3 minutes TTL
- Handle connection failures gracefully

### Payment Processing

- All payments require background job processing
- Order status changes trigger history records
- Failed payments are logged and retryable

## Example Action Class Pattern

```php
<?php

namespace App\Actions\Orders;

use App\Models\Order;
use App\Models\User;

class ProcessOrderAction
{
    public function handle(User $user, array $orderData): Order
    {
        // Business logic here
        return Order::create([
            'user_id' => $user->id,
            ...$orderData
        ]);
    }
}
```

## Example Livewire Volt Component

```php
<?php

use Livewire\Volt\Component;
use App\Actions\Orders\ProcessOrderAction;

new class extends Component {
    public string $amount = '';
    
    public function processOrder(ProcessOrderAction $action)
    {
        $order = $action->handle(auth()->user(), [
            'amount' => $this->amount
        ]);
        
        $this->redirect(route('orders.show', $order));
    }
}; ?>

<div>
    <flux:input wire:model="amount" placeholder="Enter amount" />
    <flux:button wire:click="processOrder">Process Order</flux:button>
</div>
```

## Filament Resources

When creating Filament admin resources, follow these patterns:

- Use resource classes for CRUD operations
- Create custom widgets for dashboards
- Use form builders for complex forms
- Implement proper authorization policies
