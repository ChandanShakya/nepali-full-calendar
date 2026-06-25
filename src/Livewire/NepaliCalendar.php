<?php

namespace Nepaliayush\NepaliFullCalendar\Livewire;

use Livewire\Component;
use Anuzpandey\LaravelNepaliDate\LaravelNepaliDate;
use Anuzpandey\LaravelNepaliDate\Enums\NepaliMonth;
use Illuminate\Support\Carbon;

class NepaliCalendar extends Component
{
    public $currentEngDate;
    public $currentNepDate;
    public $month;
    public $year;
    public $daysInMonth;
    public $firstDayOfWeek;
    public $todayNepDate;
    public $view = 'month';
    public $events = [];
    public $selectedDate = null;
    public $showEventModal = false;
    public $eventTitle = '';
    public $eventDescription = '';
    public $eventDate = '';
    public $eventColor = 'blue';
    public $language = 'nepali';

    protected $listeners = ['refreshCalendar' => '$refresh'];

    public function mount($lang = 'nepali')
    {
        $this->language = $lang;
        $this->view = config('nepali-calendar.default_view', 'month');
        $this->eventColor = config('nepali-calendar.colors', ['blue'])[0] ?? 'blue';
        $this->currentEngDate = now();
        $this->todayNepDate = $this->convertToNepaliDate($this->currentEngDate);
        $this->loadSampleEvents();
        $this->updateCalendar();
    }

    public function getNepaliMonths()
    {
        return __('nepali-calendar::calendar.months');
    }

    public function getNepaliDays()
    {
        return __('nepali-calendar::calendar.days_short');
    }

    public function getNumbers()
    {
        return __('nepali-calendar::calendar.numbers');
    }

    // Helper function for more reliable date conversion
    protected function convertToNepaliDate($englishDate)
    {
        try {
            return LaravelNepaliDate::from($englishDate)->toNepaliDate();
        } catch (\Exception $e) {
            // Fallback to current date if conversion fails
            return LaravelNepaliDate::from(now())->toNepaliDate();
        }
    }

    // Helper function for English date conversion
    protected function convertToEnglishDate($nepaliDate)
    {
        try {
            return LaravelNepaliDate::from($nepaliDate)->toEnglishDate();
        } catch (\Exception $e) {
            return now()->format('Y-m-d');
        }
    }

    public function updateCalendar()
    {
        try {
            $nepaliDate = $this->convertToNepaliDate($this->currentEngDate);

            // Extract year and month
            $dateParts = explode('-', $nepaliDate);
            $this->year = (int)$dateParts[0];
            $this->month = (int)$dateParts[1];

            // Validate month range
            if ($this->month < 1 || $this->month > 12) {
                throw new \InvalidArgumentException("Invalid month: {$this->month}");
            }

            // Get days in month
            $this->daysInMonth = LaravelNepaliDate::daysInMonth(
                NepaliMonth::from($this->month),
                $this->year
            );

            // Get first day of week (0=Sunday, 6=Saturday)
            $firstDayDate = sprintf('%04d-%02d-01', $this->year, $this->month);
            $englishDateString = $this->convertToEnglishDate($firstDayDate);
            $this->firstDayOfWeek = Carbon::parse($englishDateString)->dayOfWeek;
        } catch (\Exception $e) {
            // Fallback to current date
            $this->currentEngDate = now()->format('Y-m-d');
            $this->todayNepDate = $this->convertToNepaliDate($this->currentEngDate);
            $this->updateCalendar();
        }
    }

    public function nextMonth()
    {
        if ($this->month == 12) {
            $this->month = 1;
            $this->year++;
        } else {
            $this->month++;
        }

        $newNepaliDate = sprintf('%04d-%02d-01', $this->year, $this->month);
        $this->currentEngDate = $this->convertToEnglishDate($newNepaliDate);
        $this->updateCalendar();
    }

    public function previousMonth()
    {
        if ($this->month == 1) {
            $this->month = 12;
            $this->year--;
        } else {
            $this->month--;
        }

        $newNepaliDate = sprintf('%04d-%02d-01', $this->year, $this->month);
        $this->currentEngDate = $this->convertToEnglishDate($newNepaliDate);
        $this->updateCalendar();
    }

    public function goToToday()
    {
        $this->currentEngDate = now();
        $this->todayNepDate = LaravelNepaliDate::from($this->currentEngDate)->toNepaliDate();
        $this->updateCalendar();
    }

    public function changeView($view)
    {
        $this->view = $view;
    }

    public function changeLanguage($language)
    {
        $this->language = $language;
    }

    public function selectDate($day)
    {
        $this->selectedDate = sprintf('%04d-%02d-%02d', $this->year, $this->month, $day);
        $this->eventDate = $this->selectedDate;
        $this->showEventModal = true;
    }

    public function addEvent()
    {
        if ($this->eventTitle && $this->eventDate) {
            $this->events[] = [
                'id' => uniqid(),
                'title' => $this->eventTitle,
                'description' => $this->eventDescription,
                'date' => $this->eventDate,
                'color' => $this->eventColor,
            ];

            $this->resetEventForm();
            $this->showEventModal = false;

            session()->flash('message', __('nepali-calendar::calendar.events.event_saved'));
        }
    }

    public function deleteEvent($eventId)
    {
        $this->events = array_filter($this->events, function ($event) use ($eventId) {
            return $event['id'] !== $eventId;
        });
        
        session()->flash('message', __('nepali-calendar::calendar.events.event_deleted'));
    }

    public function closeModal()
    {
        $this->showEventModal = false;
        $this->resetEventForm();
    }

    private function resetEventForm()
    {
        $this->eventTitle = '';
        $this->eventDescription = '';
        $this->eventDate = '';
        $this->eventColor = config('nepali-calendar.colors', ['blue'])[0] ?? 'blue';
        $this->selectedDate = null;
    }

    public function isToday($day)
    {
        $currentDate = sprintf('%04d-%02d-%02d', $this->year, $this->month, $day);
        return $currentDate === $this->todayNepDate;
    }

    public function getEventsForDate($day)
    {
        $date = sprintf('%04d-%02d-%02d', $this->year, $this->month, $day);
        return array_filter($this->events, function ($event) use ($date) {
            return $event['date'] === $date;
        });
    }

    private function loadSampleEvents()
    {
        $this->events = [
            [
                'id' => '1',
                'title' => __('nepali-calendar::calendar.sample_events.dashain'),
                'description' => __('nepali-calendar::calendar.sample_events.dashain_desc'),
                'date' => '2081-07-10',
                'color' => 'red',
            ],
            [
                'id' => '2',
                'title' => __('nepali-calendar::calendar.sample_events.tihar'),
                'description' => __('nepali-calendar::calendar.sample_events.tihar_desc'),
                'date' => '2081-08-15',
                'color' => 'yellow',
            ],
            [
                'id' => '3',
                'title' => __('nepali-calendar::calendar.sample_events.new_year'),
                'description' => __('nepali-calendar::calendar.sample_events.new_year_desc'),
                'date' => '2082-01-01',
                'color' => 'green',
            ],
        ];
    }

    // Format day number based on language
    public function formatDayNumber($day)
    {
        $numbers = $this->getNumbers();
        return $numbers[$day] ?? $day;
    }

    // Format year based on language
    public function formatYear($year)
    {
        if ($this->language === 'nepali') {
            $numbers = $this->getNumbers();
            $yearStr = (string)$year;
            $formattedYear = '';
            
            for ($i = 0; $i < strlen($yearStr); $i++) {
                $digit = (int)$yearStr[$i];
                $formattedYear .= $numbers[$digit] ?? $yearStr[$i];
            }
            
            return $formattedYear;
        }
        
        return $year;
    }

    public function render()
    {
        $nepaliMonths = $this->getNepaliMonths();
        $monthName = $nepaliMonths[$this->month] ?? 'Unknown';
        $formattedYear = $this->formatYear($this->year);

        return view('nepali-calendar::livewire.nepali-calendar', [
            'monthName' => $monthName,
            'formattedYear' => $formattedYear,
            'weeks' => $this->getCalendarWeeks(),
            'nepaliDays' => $this->getNepaliDays(),
        ]);
    }

    protected function getCalendarWeeks()
    {
        $weeks = [];
        $day = 1;

        // First week (with empty days before the 1st)
        $week = array_fill(0, $this->firstDayOfWeek, null);

        // Fill the rest of the first week
        for ($i = $this->firstDayOfWeek; $i < 7; $i++) {
            $week[$i] = ($day <= $this->daysInMonth) ? $day++ : null;
        }
        $weeks[] = $week;

        // Remaining weeks
        while ($day <= $this->daysInMonth) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $week[$i] = ($day <= $this->daysInMonth) ? $day++ : null;
            }
            $weeks[] = $week;
        }

        return $weeks;
    }
}