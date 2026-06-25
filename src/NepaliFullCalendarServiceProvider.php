<?php

namespace Nepaliayush\NepaliFullCalendar;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Nepaliayush\NepaliFullCalendar\Livewire\NepaliCalendar;

class NepaliFullCalendarServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/nepali-calendar.php',
            'nepali-calendar'
        );
    }

    public function boot()
    {
        Livewire::component('nepali-calendar', NepaliCalendar::class);

        Blade::anonymousComponentPath(__DIR__ . '/../resources/views/components', 'nepali-calendar');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nepali-calendar');
        $this->loadViewsFrom(resource_path('views/vendor/nepali-calendar'), 'nepali-calendar');

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'nepali-calendar');

        $this->publishAssets();

        $this->loadCssFromPublic();
    }

    protected function publishAssets(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/nepali-calendar'),
        ], 'nepali-calendar-views');

        $this->publishes([
            __DIR__ . '/../resources/css/nepali-calendar.css' => public_path('vendor/nepali-calendar/css/nepali-calendar.css'),
        ], 'nepali-calendar-assets');

        $this->publishes([
            __DIR__ . '/../lang' => lang_path('vendor/nepali-calendar'),
        ], 'nepali-calendar-lang');

        $this->publishes([
            __DIR__ . '/../config/nepali-calendar.php' => config_path('nepali-calendar.php'),
        ], 'nepali-calendar-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/nepali-calendar'),
            __DIR__ . '/../resources/css' => public_path('vendor/nepali-calendar/css'),
            __DIR__ . '/../lang' => lang_path('vendor/nepali-calendar'),
            __DIR__ . '/../config/nepali-calendar.php' => config_path('nepali-calendar.php'),
        ], 'nepali-calendar');
    }

    protected function loadCssFromPublic(): void
    {
        $source = __DIR__ . '/../resources/css/nepali-calendar.css';
        $dest = public_path('vendor/nepali-calendar/css/nepali-calendar.css');

        if (!file_exists($dest)) {
            $dir = dirname($dest);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            copy($source, $dest);
        }
    }
}