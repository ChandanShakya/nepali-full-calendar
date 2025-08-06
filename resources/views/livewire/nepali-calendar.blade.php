{{-- Include CSS if not already included --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/nepali-calendar/nepali-calendar.css') }}">
@endpush

<div class="nepali-calendar">
    <!-- Calendar Header -->
    <div class="nepali-calendar-header">
        <div class="nepali-calendar-nav">
            <button wire:click="previousMonth" class="nepali-calendar-nav-btn" title="{{ $trans('previous_month') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
            
            <h2 class="nepali-calendar-title">{{ $monthName }} {{ $formattedYear }}</h2>
            
            <button wire:click="nextMonth" class="nepali-calendar-nav-btn" title="{{ $trans('next_month') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
        
        <!-- Language and View Controls -->
      <div class="nepali-calendar-controls">
    <!-- Language Switcher Dropdown -->
      <!-- Language and View Controls -->
            <div class="nepali-calendar-controls">
                <div class="nepali-calendar-language-dropdown">
                    <select wire:change="changeLanguage($event.target.value)" 
                            class="nepali-calendar-lang-select">
                        <option value="nepali" {{ $language === 'nepali' ? 'selected' : '' }}>नेपाली</option>
                        <option value="english" {{ $language === 'english' ? 'selected' : '' }}>English</option>
                    </select>
                    <div class="dropdown-arrow">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </div>
                </div>
                <button wire:click="goToToday" class="nepali-calendar-today-btn">
                    {{ $trans('today') }}
                </button>
            </div>
        </div>

    </div>

    <!-- Calendar Body -->
    <div class="nepali-calendar-body">
        @if($view === 'month')
            <!-- Month View -->
            <div class="nepali-calendar-grid">
                <!-- Day Headers -->
                @foreach($nepaliDays as $day)
                        <div class="nepali-calendar-day-header {{ $loop->last ? 'saturday' : '' }}">
                            {{ $day }}
                        </div>
                    @endforeach

                <!-- Calendar Days -->
               @foreach($weeks as $week)
                        @foreach($week as $dayIndex => $day)
                            <div class="nepali-calendar-day 
                                {{ !$day ? 'empty' : '' }} 
                                {{ $loop->parent->first && $loop->iteration <= 7 ? 'first-week' : '' }}
                                {{ $loop->last ? 'saturday' : '' }}"
                                 @if($day) wire:click="selectDate({{ $day }})" @endif>
                                
                                @if($day)
                                    <div class="nepali-calendar-day-number">
                                          <span class="{{ $this->isToday($day) ? 'today' : '' }}">
                                        {{ $this->formatDayNumber($day) }}
                                    </span>
                                      
                                    </div>
                                    
                                    <!-- Events for this day -->
                                    <div class="nepali-calendar-events">
                                        @foreach($this->getEventsForDate($day) as $event)
                                            <div class="nepali-calendar-event {{ str_replace('bg-', '', $event['color']) }}"
                                                 title="{{ $event['title'] }}{{ $event['description'] ? ' - ' . $event['description'] : '' }}">
                                                <div class="event-title">{{ $event['title'] }}</div>
                                                <button wire:click.stop="deleteEvent('{{ $event['id'] }}')" 
                                                        class="nepali-calendar-event-delete"
                                                        title="{{ $trans('events.delete_event') }}">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
            </div>
        @endif

        @if($view === 'week')
            <!-- Week View (You can implement this later) -->
            <div class="text-center py-8 text-gray-500">
                {{ $trans('messages.week_view_unavailable') }}
            </div>
        @endif
    </div>

    <!-- Event Modal -->
    @if($showEventModal)
        <div class="nepali-calendar-modal-overlay" wire:click="closeModal">
            <div class="nepali-calendar-modal" wire:click.stop>
                <div class="nepali-calendar-modal-header">
                    <h3 class="nepali-calendar-modal-title">{{ $trans('events.add_event') }}</h3>
                    <button wire:click="closeModal" class="nepali-calendar-modal-close" title="{{ $trans('events.close') }}">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="addEvent">
                    <div class="nepali-calendar-form-group">
                        <label class="nepali-calendar-form-label">{{ $trans('events.event_title') }}</label>
                        <input type="text" 
                               wire:model="eventTitle" 
                               class="nepali-calendar-form-input"
                               placeholder="घटनाको नाम लेख्नुहोस्"
                               required>
                    </div>
                    
                    <div class="nepali-calendar-form-group">
                        <label class="nepali-calendar-form-label">{{ $trans('events.event_description') }}</label>
                        <textarea wire:model="eventDescription" 
                                  class="nepali-calendar-form-textarea"
                                  placeholder="घटनाको विवरण लेख्नुहोस्"></textarea>
                    </div>
                    
                    <div class="nepali-calendar-form-group">
                        <label class="nepali-calendar-form-label">{{ $trans('events.date') }}</label>
                        <input type="text" 
                               wire:model="eventDate" 
                               class="nepali-calendar-form-input"
                               readonly>
                    </div>
                    
                    <div class="nepali-calendar-modal-actions">
                        <button type="button" 
                                wire:click="closeModal" 
                                class="nepali-calendar-btn nepali-calendar-btn-secondary">
                           {{ $trans('events.cancel') }}
                        </button>
                        <button type="submit" 
                                class="nepali-calendar-btn nepali-calendar-btn-primary">
                            {{ $trans('events.save_event') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Legend -->
   <div class="nepali-calendar-legend">
            <div class="legend-item">
                <span class="legend-color today"></span>
                <span class="legend-label">{{ $trans('today') }}</span>
            </div>
            <div class="legend-item">
                <span class="legend-color selected"></span>
                <span class="legend-label">{{ $trans('selected') }}</span>
            </div>
            <div class="legend-item">
                <span class="legend-color has-events"></span>
                <span class="legend-label">{{ $trans('has_events') }}</span>
            </div>
        </div>
</div>