@props([
    'language' => config('nepali-calendar.default_language', 'nepali'),
    'useEventModel' => config('nepali-calendar.use_event_model', false),
    'eventModel' => config('nepali-calendar.event_model'),
    'eventFields' => [],
    'customEvents' => [],
    'withTodayButton' => config('nepali-calendar.with_today_button', true),
    'withLanguageSwitcher' => config('nepali-calendar.with_language_switcher', true),
    'defaultView' => config('nepali-calendar.default_view', 'month'),
    'colors' => ['blue', 'green', 'red', 'yellow', 'purple', 'pink'],
    'styles' => [],
])

@php
    // Merge default styles with custom styles
    $styles = array_merge([
        'header' => 'bg-white text-gray-800',
        'dayHeader' => 'bg-white text-gray-500',
        'day' => 'bg-white hover:bg-gray-50',
        'today' => 'bg-indigo-600 text-white',
        'saturday' => 'text-red-500',
        'event' => 'bg-indigo-500 text-white',
    ], $styles);
@endphp

<div 
    wire:ignore.self
    class="nepali-calendar-container"
    x-data="{
        language: '{{ $language }}',
        showEventModal: false,
        eventTitle: '',
        eventDescription: '',
        eventDate: '',
        eventColor: '{{ $colors[0] }}',
        init() {
            // Initialize any Alpine.js functionality here
        }
    }"
>
    <div class="nepali-calendar {{ $styles['container'] ?? 'bg-white rounded-lg shadow-lg overflow-hidden' }}">
        <!-- Calendar Header -->
        <div class="nepali-calendar-header flex flex-col gap-4 p-4 border-b {{ $styles['header'] }}">
            <div class="nepali-calendar-nav flex items-center justify-between">
                <button 
                    wire:click="previousMonth" 
                    class="nepali-calendar-nav-btn {{ $styles['navButton'] ?? 'bg-gray-100 hover:bg-gray-200 text-gray-800' }}"
                    title="{{ __('nepali-calendar::nepali.previous_month') }}"
                >
                    @include('nepali-calendar::icons.chevron-left')
                </button>
                
                <h2 class="nepali-calendar-title text-lg font-semibold {{ $styles['title'] ?? 'text-gray-800' }}">
                    {{ $monthName }} {{ $formattedYear }}
                </h2>
                
                <button 
                    wire:click="nextMonth" 
                    class="nepali-calendar-nav-btn {{ $styles['navButton'] ?? 'bg-gray-100 hover:bg-gray-200 text-gray-800' }}"
                    title="{{ __('nepali-calendar::nepali.next_month') }}"
                >
                    @include('nepali-calendar::icons.chevron-right')
                </button>
            </div>
            
            @if($withTodayButton || $withLanguageSwitcher)
                <div class="nepali-calendar-controls flex items-center justify-end gap-3">
                    @if($withLanguageSwitcher)
                        <div class="nepali-calendar-language-dropdown relative">
                            <select 
                                wire:change="changeLanguage($event.target.value)" 
                                class="nepali-calendar-lang-select {{ $styles['languageSelect'] ?? 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' }}"
                                x-model="language"
                            >
                                <option value="nepali" {{ $language === 'nepali' ? 'selected' : '' }}>नेपाली</option>
                                <option value="english" {{ $language === 'english' ? 'selected' : '' }}>English</option>
                            </select>
                            <div class="dropdown-arrow">
                                @include('nepali-calendar::icons.chevron-down')
                            </div>
                        </div>
                    @endif
                    
                    @if($withTodayButton)
                        <button 
                            wire:click="goToToday" 
                            class="nepali-calendar-today-btn {{ $styles['todayButton'] ?? 'bg-indigo-600 hover:bg-indigo-700 text-white' }}"
                        >
                                {{ $trans('today') }}
                        </button>
                    @endif
                </div>
            @endif
        </div>

        <!-- Calendar Body -->
        <div class="nepali-calendar-body p-3">
            @if($view === 'month')
                <!-- Month View -->
                <div class="nepali-calendar-grid grid grid-cols-7 gap-px bg-gray-200">
                    <!-- Day Headers -->
                    @foreach($nepaliDays as $day)
                        <div class="nepali-calendar-day-header p-2 text-center text-sm font-medium {{ $styles['dayHeader'] }} {{ $loop->last ? 'saturday' : '' }}">
                            {{ $day }}
                        </div>
                    @endforeach

                    <!-- Calendar Days -->
                    @foreach($weeks as $week)
                        @foreach($week as $dayIndex => $day)
                            <div 
                                class="nepali-calendar-day p-1 min-h-[100px] flex flex-col {{ $styles['day'] }} {{ !$day ? 'bg-gray-50' : '' }}  {{ $loop->last ? 'saturday' : '' }}"
                                @if($day) wire:click="selectDate({{ $day }})" @endif
                            >
                                @if($day)
                                    <div class="nepali-calendar-day-number flex justify-between items-start mb-1">
                                        <span class="{{ $this->isToday($day) ? 'today' : '' }} text-sm font-medium p-1 rounded-full w-6 h-6 flex items-center justify-center">
                                            {{ $this->formatDayNumber($day) }}
                                        </span>
                                    </div>
                                    
                                    <!-- Events for this day -->
                                    <div class="nepali-calendar-events mt-auto space-y-1 overflow-hidden">
                                        @foreach($this->getEventsForDate($day) as $event)
                                            <div 
                                                class="nepali-calendar-event text-xs p-1 rounded flex justify-between items-center {{ $styles['event'] }} {{ $event['color'] ?? '' }}"
                                                title="{{ $event['title'] }}{{ $event['description'] ? ' - ' . $event['description'] : '' }}"
                                            >
                                                <div class="event-title truncate">{{ $event['title'] }}</div>
                                                <button 
                                                    wire:click.stop="deleteEvent('{{ $event['id'] }}')" 
                                                    class="nepali-calendar-event-delete opacity-0 hover:opacity-100 transition-opacity"
                                                    title="{{ __('nepali-calendar::nepali.events.delete_event') }}"
                                                >
                                                    @include('nepali-calendar::icons.x-mark')
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                </div>
            @elseif($view === 'week')
                <!-- Week View -->
                <div class="text-center py-8 text-gray-500">
                    {{ __('nepali-calendar::nepali.messages.week_view_unavailable') }}
                </div>
            @endif
        </div>

        <!-- Event Modal -->
        @if($showEventModal)
            <div 
                class="nepali-calendar-modal-overlay fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                x-show="showEventModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                wire:click="closeModal"
            >
                <div 
                    class="nepali-calendar-modal bg-white rounded-lg shadow-xl w-full max-w-md mx-4"
                    x-show="showEventModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    @click.stop
                >
                    <div class="nepali-calendar-modal-header flex justify-between items-center border-b p-4">
                        <h3 class="nepali-calendar-modal-title text-lg font-medium text-gray-900">
                            {{ __('nepali-calendar::nepali.events.add_event') }}
                        </h3>
                        <button 
                            wire:click="closeModal"
                            class="nepali-calendar-modal-close text-gray-400 hover:text-gray-500"
                            title="{{ __('nepali-calendar::nepali.events.close') }}"
                        >
                            @include('nepali-calendar::icons.x-mark')
                        </button>
                    </div>
                    
                    <form wire:submit.prevent="addEvent" class="p-4">
                        <div class="nepali-calendar-form-group mb-4">
                            <label class="nepali-calendar-form-label block text-sm font-medium text-gray-700 mb-1">
                                {{ __('nepali-calendar::nepali.events.event_title') }}
                            </label>
                            <input 
                                type="text" 
                                wire:model="eventTitle" 
                                class="nepali-calendar-form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="{{ __('nepali-calendar::nepali.events.event_title_placeholder') }}"
                                required
                            >
                        </div>
                        
                        <div class="nepali-calendar-form-group mb-4">
                            <label class="nepali-calendar-form-label block text-sm font-medium text-gray-700 mb-1">
                                {{ __('nepali-calendar::nepali.events.event_description') }}
                            </label>
                            <textarea 
                                wire:model="eventDescription" 
                                class="nepali-calendar-form-textarea block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                rows="3"
                                placeholder="{{ __('nepali-calendar::nepali.events.event_description_placeholder') }}"
                            ></textarea>
                        </div>
                        
                        <div class="nepali-calendar-form-group mb-4">
                            <label class="nepali-calendar-form-label block text-sm font-medium text-gray-700 mb-1">
                                {{ __('nepali-calendar::nepali.events.date') }}
                            </label>
                            <input 
                                type="text" 
                                wire:model="eventDate" 
                                class="nepali-calendar-form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-100"
                                readonly
                            >
                        </div>
                        
                        <div class="nepali-calendar-form-group mb-4">
                            <label class="nepali-calendar-form-label block text-sm font-medium text-gray-700 mb-1">
                                {{ __('nepali-calendar::nepali.events.color') }}
                            </label>
                            <div class="color-options flex gap-2">
                                @foreach($colors as $color)
                                    <label class="color-option relative cursor-pointer">
                                        <input 
                                            type="radio" 
                                            class="sr-only" 
                                            wire:model="eventColor" 
                                            value="{{ $color }}"
                                        >
                                        <span class="color-checkmark block w-7 h-7 rounded-full bg-{{ $color }}-500 border-2 border-white shadow"></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="nepali-calendar-modal-actions flex justify-end gap-2 pt-4 border-t">
                            <button 
                                type="button" 
                                wire:click="closeModal" 
                                class="nepali-calendar-btn-secondary px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                {{ __('nepali-calendar::nepali.events.cancel') }}
                            </button>
                            <button 
                                type="submit" 
                                class="nepali-calendar-btn-primary px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                {{ __('nepali-calendar::nepali.events.save_event') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <!-- Legend -->
        <div class="nepali-calendar-legend p-4 border-t flex flex-wrap gap-4 text-sm">
            <div class="legend-item flex items-center gap-2">
                <span class="legend-color w-3 h-3 rounded-full {{ $styles['today'] }}"></span>
                <span class="legend-label text-gray-500">{{ __('nepali-calendar::nepali.today') }}</span>
            </div>
            <div class="legend-item flex items-center gap-2">
                <span class="legend-color w-3 h-3 rounded-full bg-green-500"></span>
                <span class="legend-label text-gray-500">{{ __('nepali-calendar::nepali.selected') }}</span>
            </div>
            <div class="legend-item flex items-center gap-2">
                <span class="legend-color w-3 h-3 rounded-full {{ $styles['event'] }}"></span>
                <span class="legend-label text-gray-500">{{ __('nepali-calendar::nepali.has_events') }}</span>
            </div>
        </div>
    </div>
</div>

@push('styles')
    @if(config('nepali-calendar.load_css', true))
        <link rel="stylesheet" href="{{ asset('vendor/nepali-calendar/css/nepali-calendar.css') }}">
    @endif
@endpush

@push('scripts')
    @if(config('nepali-calendar.load_js', true))
        <script src="{{ asset('vendor/nepali-calendar/js/nepali-calendar.js') }}" defer></script>
    @endif
@endpush