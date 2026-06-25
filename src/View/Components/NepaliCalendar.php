<?php

namespace Nepaliayush\NepaliFullCalendar\View\Components;

use Illuminate\View\Component;

class NepaliCalendar extends Component
{
    public function __construct(
        public string $language = 'nepali',
        public bool $withTodayButton = true,
        public bool $withLanguageSwitcher = true,
        public string $defaultView = 'month',
        public array $colors = ['blue', 'green', 'red', 'yellow', 'purple', 'pink'],
        public array $styles = [],
    ) {}

    public function render()
    {
        return view('nepali-calendar::components.nepali-calendar');
    }
}
