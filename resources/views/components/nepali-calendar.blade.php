@props([
    'language' => 'nepali',
    'withTodayButton' => true,
    'withLanguageSwitcher' => true,
    'defaultView' => 'month',
    'colors' => ['blue', 'green', 'red', 'yellow', 'purple', 'pink'],
    'styles' => [],
])

@livewire('nepali-calendar', [
    'lang' => $language,
], key($language . $defaultView))
