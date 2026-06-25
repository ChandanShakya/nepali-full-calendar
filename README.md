# Nepali Full Calendar

A beautiful and feature-rich Nepali (Bikram Sambat) calendar component for Laravel with Livewire support.

## Features

- Full Nepali (Bikram Sambat) calendar with month navigation
- Today highlighting and date selection
- Event management (add/delete events)
- Bilingual support (Nepali/English)
- Nepali numeral display
- Saturday highlighting (red)
- Configurable colors and styles
- Blade component and Livewire component support
- Pre-compiled CSS with all Tailwind utilities included

## Requirements

- PHP 8.1+
- Laravel 10, 11, or 12
- Livewire 3
- Alpine.js (for modal transitions)

## Installation

```bash
composer require nepaliayush/nepali-full-calendar
```

The service provider is auto-discovered. No manual registration needed.

The package includes pre-compiled CSS with all required Tailwind CSS utilities. No additional CSS setup is required.

### Publish Assets (Optional)

```bash
# Publish everything
php artisan vendor:publish --tag=nepali-calendar

# Or publish individually
php artisan vendor:publish --tag=nepali-calendar-views
php artisan vendor:publish --tag=nepali-calendar-assets
php artisan vendor:publish --tag=nepali-calendar-lang
php artisan vendor:publish --tag=nepali-calendar-config
```

## Usage

### Livewire Component

```blade
@livewire('nepali-calendar')
```

With language option:

```blade
@livewire('nepali-calendar', ['lang' => 'english'])
```

### Blade Component

```blade
<x-nepali-calendar />
```

With options:

```blade
<x-nepali-calendar language="english" :with-today-button="true" />
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=nepali-calendar-config
```

Available options in `config/nepali-calendar.php`:

| Option | Default | Description |
|--------|---------|-------------|
| `default_language` | `'nepali'` | Default language (`nepali` or `english`) |
| `default_view` | `'month'` | Default calendar view |
| `with_today_button` | `true` | Show/hide "Today" button |
| `with_language_switcher` | `true` | Show/hide language switcher |
| `load_css` | `true` | Auto-include package CSS |
| `colors` | `['blue', ...]` | Available event colors |
| `styles` | `[...]` | Custom Tailwind CSS classes |

## Translations

Publish language files to customize translations:

```bash
php artisan vendor:publish --tag=nepali-calendar-lang
```

This publishes files to `lang/vendor/nepali-calendar/`. Available languages:
- `en/calendar.php` - English
- `ne/calendar.php` - Nepali

## Customization

### Custom Views

Publish views to customize the template:

```bash
php artisan vendor:publish --tag=nepali-calendar-views
```

Views will be published to `resources/views/vendor/nepali-calendar/`.

### Custom CSS

Publish the CSS file to override styles:

```bash
php artisan vendor:publish --tag=nepali-calendar-assets
```

CSS will be published to `public/vendor/nepali-calendar/css/nepali-calendar.css`.

### Rebuilding CSS (Development)

If you modify the blade templates and need to regenerate the Tailwind CSS:

```bash
npm install
npm run build
```

## License

MIT License
