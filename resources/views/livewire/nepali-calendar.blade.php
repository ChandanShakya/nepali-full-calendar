{{-- Include CSS if not already included --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/nepali-calendar/nepali-calendar.css') }}">
@endpush

<div class="nepali-calendar">
    <!-- Calendar Header -->
    <div class="nepali-calendar-header">
        <div class="nepali-calendar-nav">
            <button wire:click="previousMonth" class="nepali-calendar-nav-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
            
            <h2 class="nepali-calendar-title">{{ $monthName }} {{ $year }}</h2>
            
            <button wire:click="nextMonth" class="nepali-calendar-nav-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>  
    </div>

    <!-- Calendar Body -->
    <div class="nepali-calendar-body">
        @if($view === 'month')
            <!-- Month View -->
            <div class="nepali-calendar-grid">
                <!-- Day Headers -->
                @foreach($nepaliDays as $day)
                    <div class="nepali-calendar-day-header">
                        {{ $day }}
                    </div>
                @endforeach

                <!-- Calendar Days -->
                @foreach($weeks as $week)
                    @foreach($week as $dayIndex => $day)
                        <div class="nepali-calendar-day {{ !$day ? 'empty' : '' }}"
                             @if($day) wire:click="selectDate({{ $day }})" @endif>
                            
                            @if($day)
                                <div class="nepali-calendar-day-number">
                                    <span class="{{ $this->isToday($day) ? 'today' : '' }}">
                                        {{ $day }}
                                    </span>
                                </div>
                                
                                <!-- Events for this day -->
                                <div class="nepali-calendar-events">
                                    @foreach($this->getEventsForDate($day) as $event)
                                        <div class="nepali-calendar-event {{ str_replace('bg-', '', $event['color']) }}"
                                             title="{{ $event['title'] }}{{ $event['description'] ? ' - ' . $event['description'] : '' }}">
                                            {{ $event['title'] }}
                                            <button wire:click.stop="deleteEvent('{{ $event['id'] }}')" 
                                                    class="nepali-calendar-event-delete"
                                                    title="घटना मेटाउनुहोस्">
                                                ×
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
                हप्ता दृश्य अझै उपलब्ध छैन
            </div>
        @endif
    </div>

    <!-- Event Modal -->
    @if($showEventModal)
        <div class="nepali-calendar-modal-overlay" wire:click="closeModal">
            <div class="nepali-calendar-modal" wire:click.stop>
                <div class="nepali-calendar-modal-header">
                    <h3 class="nepali-calendar-modal-title">नयाँ घटना थप्नुहोस्</h3>
                    <button wire:click="closeModal" class="nepali-calendar-modal-close">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form wire:submit.prevent="addEvent">
                    <div class="nepali-calendar-form-group">
                        <label class="nepali-calendar-form-label">घटनाको नाम *</label>
                        <input type="text" 
                               wire:model="eventTitle" 
                               class="nepali-calendar-form-input"
                               placeholder="घटनाको नाम लेख्नुहोस्"
                               required>
                    </div>
                    
                    <div class="nepali-calendar-form-group">
                        <label class="nepali-calendar-form-label">विवरण</label>
                        <textarea wire:model="eventDescription" 
                                  class="nepali-calendar-form-textarea"
                                  placeholder="घटनाको विवरण लेख्नुहोस्"></textarea>
                    </div>
                    
                    <div class="nepali-calendar-form-group">
                        <label class="nepali-calendar-form-label">मिति</label>
                        <input type="text" 
                               wire:model="eventDate" 
                               class="nepali-calendar-form-input"
                               readonly>
                    </div>
                    
                    <div class="nepali-calendar-modal-actions">
                        <button type="button" 
                                wire:click="closeModal" 
                                class="nepali-calendar-btn nepali-calendar-btn-secondary">
                            रद्द गर्नुहोस्
                        </button>
                        <button type="submit" 
                                class="nepali-calendar-btn nepali-calendar-btn-primary">
                            थप्नुहोस्
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Legend -->
    <div class="nepali-calendar-legend">
        
    </div>
</div>