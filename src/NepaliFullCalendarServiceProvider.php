<?php

namespace Nepaliayush\NepaliFullCalendar;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Nepaliayush\NepaliFullCalendar\Livewire\NepaliCalendar;

class NepaliFullCalendarServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge package configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/nepali-calendar.php',
            'nepali-calendar'
        );
    }

    public function boot()
    {
        // Register Livewire component
        Livewire::component('nepali-calendar', NepaliCalendar::class);

        // Load views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nepali-calendar');

        // Publish assets
        if ($this->app->runningInConsole()) {
            // Publish views
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/nepali-calendar'),
            ], 'nepali-calendar-views');

            // Publish only the specific CSS file we need
            $this->publishes([
                __DIR__ . '/../resources/css/nepali-calendar.css' => public_path('vendor/nepali-calendar/nepali-calendar.css'),
            ], 'nepali-calendar-assets');

            // Publish config
            $this->publishes([
                __DIR__ . '/../config/nepali-calendar.php' => config_path('nepali-calendar.php'),
            ], 'nepali-calendar-config');

            // Publish all assets at once
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/nepali-calendar'),
                __DIR__ . '/../resources/css' => public_path('vendor/nepali-calendar'),
                __DIR__ . '/../config/nepali-calendar.php' => config_path('nepali-calendar.php'),
            ], 'nepali-calendar');
        }
    }
}
