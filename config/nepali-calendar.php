<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default View
    |--------------------------------------------------------------------------
    |
    | The default view to show when the calendar loads.
    | Supported: "month", "week", "day"
    |
    */
    'default_view' => 'month',

    /*
    |--------------------------------------------------------------------------
    | Event Colors
    |--------------------------------------------------------------------------
    |
    | Default colors for different types of events
    |
    */
    'event_colors' => [
        'default' => 'blue',
        'festival' => 'red',
        'personal' => 'green',
        'meeting' => 'yellow',
        'holiday' => 'purple',
        'birthday' => 'pink',
    ],

    /*
    |--------------------------------------------------------------------------
    | Nepali Months
    |--------------------------------------------------------------------------
    |
    | Nepali month names mapping
    |
    */
    'nepali_months' => [
        1 => 'बैशाख',
        2 => 'जेठ',
        3 => 'आषाढ',
        4 => 'श्रावण',
        5 => 'भाद्र',
        6 => 'आश्विन',
        7 => 'कार्तिक',
        8 => 'मंसिर',
        9 => 'पौष',
        10 => 'माघ',
        11 => 'फाल्गुन',
        12 => 'चैत्र',
    ],

    /*
    |--------------------------------------------------------------------------
    | Nepali Days
    |--------------------------------------------------------------------------
    |
    | Nepali day names
    |
    */
    'nepali_days' => [
        'आइत',
        'सोम',
        'मंगल',
        'बुध',
        'बिही',
        'शुक्र',
        'शनि'
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Events
    |--------------------------------------------------------------------------
    |
    | Sample events to load by default
    |
    */
    'default_events' => [
        [
            'title' => 'दशैं',
            'description' => 'दशैं मुख्य दिन',
            'date' => '2081-07-10',
            'color' => 'red',
            'type' => 'festival'
        ],
        [
            'title' => 'तिहार',
            'description' => 'लक्ष्मी पूजा',
            'date' => '2081-08-15',
            'color' => 'yellow',
            'type' => 'festival'
        ],
        [
            'title' => 'नयाँ वर्ष',
            'description' => 'नेपाली नयाँ वर्ष',
            'date' => '2082-01-01',
            'color' => 'red',
            'type' => 'festival'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Enable/disable calendar features
    |
    */
    'features' => [
        'add_events' => true,
        'delete_events' => true,
        'edit_events' => true,
        'drag_drop' => false,
        'print_view' => false,
        'export' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Styling
    |--------------------------------------------------------------------------
    |
    | Calendar appearance settings
    |
    */
    'styling' => [
        'theme' => 'default', // default, dark, minimal
        'primary_color' => '#3b82f6',
        'border_radius' => '12px',
        'animation' => true,
    ],
];
