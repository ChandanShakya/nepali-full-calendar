# Changelog

All notable changes to `nepaliayush/nepali-full-calendar` will be documented in this file.

## [1.0.0] - 2025-01-01

### Added
- Full Nepali (Bikram Sambat) calendar with month navigation
- Today highlighting
- Date selection with event modal
- Event management (add/delete events)
- Bilingual support (Nepali/English)
- Nepali numeral display for dates and years
- Saturday highlighting in red
- Configurable colors and styles
- Blade component support (`<x-nepali-calendar />`)
- Livewire component support (`@livewire('nepali-calendar')`)
- Laravel localization with publishable language files
- Publishable views, config, CSS, and lang files

### Fixed
- CSS asset path mismatch between publish path and blade reference
- Missing `$eventColor` property causing event color selection to fail
- View default not reading from config

### Changed
- Replaced custom translation system with Laravel's built-in `__()` localization
- Moved language files to standard Laravel structure (`lang/en/`, `lang/ne/`)
- Removed empty JavaScript file dependency
- Improved ServiceProvider with proper translation loading
