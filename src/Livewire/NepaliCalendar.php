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
    public $language = 'nepali'; // Default language

    protected $listeners = ['refreshCalendar' => '$refresh'];

    public function mount($lang = 'nepali')
    {
        $this->language = $lang;
        $this->currentEngDate = now();
        $this->todayNepDate = $this->convertToNepaliDate($this->currentEngDate);
        $this->loadSampleEvents();
        $this->updateCalendar();
    }

    // Get translation for a key
    protected function trans($key, $default = null)
    {
        $langFile = $this->language === 'english' ? 'english' : 'nepali';
        $translations = include __DIR__ . "/../../lang/{$langFile}.php";
        
        $keys = explode('.', $key);
        $value = $translations;
        
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                return $default ?? $key;
            }
        }
        
        return $value;
    }

    // Get Nepali months based on language
    public function getNepaliMonths()
    {
        return $this->trans('months');
    }

    // Get Nepali days based on language
    public function getNepaliDays()
    {
        return $this->trans('days_short');
    }

    // Get numbers based on language
    public function getNumbers()
    {
        return $this->trans('numbers');
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
                'color' => $this->getRandomColor()
            ];

            $this->resetEventForm();
            $this->showEventModal = false;
            
            // You can add session flash message here
            session()->flash('message', $this->trans('events.event_saved'));
        }
    }

    public function deleteEvent($eventId)
    {
        $this->events = array_filter($this->events, function ($event) use ($eventId) {
            return $event['id'] !== $eventId;
        });
        
        session()->flash('message', $this->trans('events.event_deleted'));
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
        $this->selectedDate = null;
    }

    private function getRandomColor()
    {
        $colors = ['bg-blue-500', 'bg-green-500', 'bg-red-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500'];
        return $colors[array_rand($colors)];
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
        // Add some sample events with translation
        $this->events = [
            [
                'id' => '1',
                'title' => $this->trans('sample_events.dashain'),
                'description' => $this->trans('sample_events.dashain_desc'),
                'date' => '2081-07-10',
                'color' => 'bg-red-500'
            ],
            [
                'id' => '2',
                'title' => $this->trans('sample_events.tihar'),
                'description' => $this->trans('sample_events.tihar_desc'),
                'date' => '2081-08-15',
                'color' => 'bg-yellow-500'
            ],
            [
                'id' => '3',
                'title' => $this->trans('sample_events.new_year'),
                'description' => $this->trans('sample_events.new_year_desc'),
                'date' => '2082-01-01',
                'color' => 'bg-green-500'
            ]
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
            'trans' => function($key, $default = null) {
                return $this->trans($key, $default);
            }
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