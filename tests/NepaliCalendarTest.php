<?php

namespace Nepaliayush\NepaliFullCalendar\Tests;

use Orchestra\Testbench\TestCase;
use Nepaliayush\NepaliFullCalendar\NepaliFullCalendarServiceProvider;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider;

class NepaliCalendarTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            NepaliFullCalendarServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('app.key', 'base64:DdC/ajNt1S8Dnn+Y1jXCyiniiWCdZkRopsIACDijEMY=');
        $app['config']->set('nepali-calendar.default_language', 'nepali');
        $app['config']->set('nepali-calendar.default_view', 'month');
        $app['config']->set('nepali-date.default_format', 'Y-m-d');
        $app['config']->set('nepali-date.default_locale', 'en');
    }

    public function test_component_can_be_rendered(): void
    {
        Livewire::test(\Nepaliayush\NepaliFullCalendar\Livewire\NepaliCalendar::class)
            ->assertStatus(200);
    }

    public function test_component_mounts_with_nepali_language(): void
    {
        Livewire::test(\Nepaliayush\NepaliFullCalendar\Livewire\NepaliCalendar::class, ['lang' => 'nepali'])
            ->assertSet('language', 'nepali');
    }

    public function test_component_mounts_with_english_language(): void
    {
        Livewire::test(\Nepaliayush\NepaliFullCalendar\Livewire\NepaliCalendar::class, ['lang' => 'english'])
            ->assertSet('language', 'english');
    }

    public function test_component_has_current_month_and_year(): void
    {
        Livewire::test(\Nepaliayush\NepaliFullCalendar\Livewire\NepaliCalendar::class)
            ->assertSet('month', function ($value) {
                return $value >= 1 && $value <= 12;
            })
            ->assertSet('year', function ($value) {
                return $value >= 2000 && $value <= 2100;
            });
    }

    public function test_next_month_increments_month(): void
    {
        Livewire::test(\Nepaliayush\NepaliFullCalendar\Livewire\NepaliCalendar::class)
            ->call('nextMonth')
            ->assertSet('month', function ($value) {
                return $value >= 1 && $value <= 12;
            });
    }

    public function test_previous_month_decrements_month(): void
    {
        Livewire::test(\Nepaliayush\NepaliFullCalendar\Livewire\NepaliCalendar::class)
            ->call('previousMonth')
            ->assertSet('month', function ($value) {
                return $value >= 1 && $value <= 12;
            });
    }

    public function test_go_to_today_resets_to_current_date(): void
    {
        Livewire::test(\Nepaliayush\NepaliFullCalendar\Livewire\NepaliCalendar::class)
            ->call('nextMonth')
            ->call('goToToday')
            ->assertSet('month', function ($value) {
                return $value >= 1 && $value <= 12;
            });
    }

    public function test_can_select_date(): void
    {
        Livewire::test(\Nepaliayush\NepaliFullCalendar\Livewire\NepaliCalendar::class)
            ->call('selectDate', 15)
            ->assertSet('showEventModal', true)
            ->assertSet('selectedDate', function ($value) {
                return str_contains($value, '-15');
            });
    }

    public function test_can_add_event(): void
    {
        Livewire::test(\Nepaliayush\NepaliFullCalendar\Livewire\NepaliCalendar::class)
            ->set('eventTitle', 'Test Event')
            ->set('eventDate', '2081-01-15')
            ->set('eventColor', 'blue')
            ->call('addEvent')
            ->assertSet('showEventModal', false)
            ->assertSet('eventTitle', '');
    }

    public function test_can_delete_event(): void
    {
        Livewire::test(\Nepaliayush\NepaliFullCalendar\Livewire\NepaliCalendar::class)
            ->call('deleteEvent', '1')
            ->assertSet('events', function ($events) {
                return !collect($events)->contains('id', '1');
            });
    }

    public function test_change_language(): void
    {
        Livewire::test(\Nepaliayush\NepaliFullCalendar\Livewire\NepaliCalendar::class)
            ->call('changeLanguage', 'english')
            ->assertSet('language', 'english');
    }

    public function test_service_provider_is_registered(): void
    {
        $this->assertTrue(
            $this->app->getProvider(NepaliFullCalendarServiceProvider::class) !== null
        );
    }
}
