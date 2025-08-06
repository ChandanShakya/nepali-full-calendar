<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Language
    |--------------------------------------------------------------------------
    |
    | The default language for the calendar (nepali/english)
    */
    'default_language' => 'nepali',

    /*
    |--------------------------------------------------------------------------
    | Use Event Model
    |--------------------------------------------------------------------------
    |
    | Whether to use an Eloquent model for events
    */
    'use_event_model' => false,

    /*
    |--------------------------------------------------------------------------
    | Event Model
    |--------------------------------------------------------------------------
    |
    | The model to use for calendar events
    */
    'event_model' => null,

    /*
    |--------------------------------------------------------------------------
    | Default View
    |--------------------------------------------------------------------------
    |
    | The default view to display (month/week)
    */
    'default_view' => 'month',

    /*
    |--------------------------------------------------------------------------
    | Show Today Button
    |--------------------------------------------------------------------------
    |
    | Whether to show the "Today" button
    */
    'with_today_button' => true,

    /*
    |--------------------------------------------------------------------------
    | Show Language Switcher
    |--------------------------------------------------------------------------
    |
    | Whether to show the language switcher
    */
    'with_language_switcher' => true,

    /*
    |--------------------------------------------------------------------------
    | Event Colors
    |--------------------------------------------------------------------------
    |
    | Available colors for events
    */
    'colors' => ['blue', 'green', 'red', 'yellow', 'purple', 'pink'],

    /*
    |--------------------------------------------------------------------------
    | Load CSS
    |--------------------------------------------------------------------------
    |
    | Whether to automatically load the package CSS
    */
    'load_css' => true,

    /*
    |--------------------------------------------------------------------------
    | Load JS
    |--------------------------------------------------------------------------
    |
    | Whether to automatically load the package JS
    */
    'load_js' => true,

    /*
    |--------------------------------------------------------------------------
    | Styles Configuration
    |--------------------------------------------------------------------------
    |
    | Custom styles for various calendar elements
    */
    'styles' => [
        'container' => 'bg-white rounded-lg shadow-lg overflow-hidden',
        'header' => 'bg-white text-gray-800',
        'navButton' => 'bg-gray-100 hover:bg-gray-200 text-gray-800',
        'title' => 'text-gray-800',
        'todayButton' => 'bg-indigo-600 hover:bg-indigo-700 text-white',
        'languageSelect' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500',
        'dayHeader' => 'bg-white text-gray-500',
        'day' => 'bg-white hover:bg-gray-50',
        'today' => 'bg-indigo-600 text-white',
        'saturday' => 'text-red-500',
        'event' => 'bg-indigo-500 text-white',
    ],
];