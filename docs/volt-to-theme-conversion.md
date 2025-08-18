# Volt to Theme Conversion Tools

This document explains the automated tools for converting Volt components to the theming system architecture.

## Overview

The theming system requires:
1. **Livewire component classes** (PHP logic) in `app/Livewire/Pages/`
2. **Theme blade files** (presentation) in `resources/views/themes/{theme}/pages/`

These tools automate the conversion from Volt components to this structure.

## Available Tools

### 1. Single File Conversion

Convert a single Volt component:

```bash
# Basic conversion to default theme
php artisan theme:convert-volt resources/views/livewire/pages/guest/example.blade.php

# Convert to specific theme
php artisan theme:convert-volt resources/views/livewire/pages/guest/example.blade.php --theme=yolan

# Dry run (preview without creating files)
php artisan theme:convert-volt resources/views/livewire/pages/guest/example.blade.php --dry-run
```

### 2. Batch Directory Conversion

Convert all Volt components in a directory:

```bash
# Convert all files in catalog directory
php artisan theme:convert-volt-batch resources/views/livewire/pages/guest/catalog

# With specific theme
php artisan theme:convert-volt-batch resources/views/livewire/pages/guest/catalog --theme=yolan

# Dry run
php artisan theme:convert-volt-batch resources/views/livewire/pages/guest/catalog --dry-run
```

### 3. Bash Helper Script

Simplified interface:

```bash
# Single file
./scripts/convert-volt.sh resources/views/livewire/pages/guest/example.blade.php

# Directory  
./scripts/convert-volt.sh resources/views/livewire/pages/guest/catalog

# With theme
./scripts/convert-volt.sh resources/views/livewire/pages/guest/catalog yolan
```

## What the Tools Do

### Automatic Processing:

1. **Parse Volt File**
   - Extracts PHP imports (`use` statements)
   - Separates PHP logic from Blade content
   - Identifies component structure

2. **Generate Livewire Class**
   - Creates proper namespace based on file path
   - Extends `BaseComponent` for theming support
   - Adds required `getViewName()` and `getLayoutType()` methods
   - Preserves all original PHP logic

3. **Generate Theme Blade File**
   - Strips PHP logic (moved to Livewire class)
   - Preserves imports needed in Blade (e.g., Enums)
   - Maintains all original Blade content

4. **File Management**
   - Creates directory structure if needed
   - Optionally removes original Volt file
   - Shows preview before creating files

### Example Conversion:

**Before** (Volt):
```php
<?php
use App\Models\Example;
use Livewire\Volt\Component;

new class extends Component {
    public function getData() {
        return Example::all();
    }
}; ?>

<div>
    @foreach($this->getData() as $item)
        <p>{{ $item->name }}</p>
    @endforeach
</div>
```

**After** (Livewire Class):
```php
<?php

namespace App\Livewire\Pages\Guest;

use App\Models\Example;
use App\Livewire\BaseComponent;

class Example extends BaseComponent
{
    public function getData() {
        return Example::all();
    }

    protected function getViewName(): string
    {
        return 'pages.guest.example';
    }

    protected function getLayoutType(): string
    {
        return 'guest';
    }
}
```

**After** (Theme Blade):
```blade
<div>
    @foreach($this->getData() as $item)
        <p>{{ $item->name }}</p>
    @endforeach
</div>
```

## Manual Steps After Conversion

The tools handle most of the work, but you may need to:

### 1. Update Routes
If converting page components, update `routes/web.php`:

```php
// Before
Volt::route('/example', 'pages.guest.example')->name('example');

// After  
Route::get('/example', \App\Livewire\Pages\Guest\Example::class)->name('example');
```

### 2. Update Component References
Update calls to nested components:

```blade
<!-- Before -->
<livewire:pages.guest.example />

<!-- After (if component was renamed) -->
<livewire:pages.guest.example-component />
```

### 3. Copy to Other Themes
Copy theme files to other theme directories:

```bash
cp resources/views/themes/default/pages/guest/example.blade.php \
   resources/views/themes/yolan/pages/guest/example.blade.php
```

## Troubleshooting

### Common Issues:

1. **Complex PHP Logic**
   - Tool may not perfectly parse very complex Volt logic
   - Manual review recommended for complex components

2. **Nested Components**
   - Convert parent components first
   - Update references in theme files

3. **Import Detection**
   - Tool detects imports needed in Blade templates
   - May need manual adjustment for edge cases

### Validation:

Test converted components:
```bash
# Check syntax
php artisan view:clear
php artisan config:clear

# Test routes
php artisan route:list

# Run tests
vendor/bin/pest
```

## Best Practices

1. **Convert in Order**
   - Start with main page components
   - Then convert nested components
   - Update references as you go

2. **Use Dry Run First**
   - Always preview with `--dry-run`
   - Verify the generated code looks correct

3. **Test Incrementally**
   - Convert a few components at a time
   - Test each conversion before proceeding

4. **Backup First**
   - Commit your changes before conversion
   - Easy to revert if needed

## Advanced Usage

### Custom Processing

For complex components that need manual attention:

1. Use `--dry-run` to see what would be generated
2. Create the files manually based on the preview
3. Use the tools as a starting point

### Theme-Specific Customization

After conversion, customize theme files:

```bash
# Convert to default theme first
php artisan theme:convert-volt resources/views/livewire/pages/guest/example.blade.php

# Copy and customize for other themes
cp resources/views/themes/default/pages/guest/example.blade.php \
   resources/views/themes/yolan/pages/guest/example.blade.php

# Edit yolan version for different styling
```

This approach ensures you have a working base version before customization.