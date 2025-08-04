#!/bin/bash

# Volt to Theme Conversion Helper Script
# Usage: ./scripts/convert-volt.sh [file|directory] [theme]

set -e

THEME=${2:-default}
TARGET=$1

if [ -z "$TARGET" ]; then
    echo "Usage: $0 <file|directory> [theme]"
    echo ""
    echo "Examples:"
    echo "  $0 resources/views/livewire/pages/guest/example.blade.php"
    echo "  $0 resources/views/livewire/pages/guest/catalog"
    echo "  $0 resources/views/livewire/pages/guest/catalog yolan"
    exit 1
fi

if [ ! -e "$TARGET" ]; then
    echo "Error: $TARGET does not exist"
    exit 1
fi

echo "🔄 Converting Volt components to theming system..."
echo "Target: $TARGET"
echo "Theme: $THEME"
echo ""

if [ -f "$TARGET" ]; then
    # Single file
    echo "Converting single file..."
    php artisan theme:convert-volt "$TARGET" --theme="$THEME"
elif [ -d "$TARGET" ]; then
    # Directory
    echo "Converting directory (batch)..."
    php artisan theme:convert-volt-batch "$TARGET" --theme="$THEME"
else
    echo "Error: $TARGET is neither a file nor directory"
    exit 1
fi

echo ""
echo "✅ Conversion complete!"
echo ""
echo "📝 Next steps:"
echo "1. Update routes if needed (for page components)"
echo "2. Update component references in other files"
echo "3. Test the converted components"
echo "4. Copy theme files to other themes if needed"