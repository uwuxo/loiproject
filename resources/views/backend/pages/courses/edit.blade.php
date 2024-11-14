@extends('backend.layouts.master')

@section('title')
Course Edit - Admin Panel
@endsection

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .form-check-label {
            text-transform: capitalize;
        }

        .datepicker-wrapper {
            position: relative;
        }

        .datepicker-popup {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            display: none;
            width: 300px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .datepicker-popup.show {
            display: block;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
            text-align: center;
        }

        .calendar-day {
            padding: 8px;
            cursor: pointer;
            border-radius: 4px;
        }

        .calendar-day:hover {
            background-color: #f0f0f0;
        }

        .calendar-day.today {
            background-color: #e9ecef;
        }

        .calendar-day.selected {
            background-color: #0d6efd;
            color: white;
        }

        .calendar-day.disabled {
            color: #aaa;
            cursor: not-allowed;
        }

        .weekday-header {
            font-weight: bold;
            padding: 8px;
            background-color: #f8f9fa;
        }
    </style>
@endsection


@section('admin-content')
    <!-- page title area start -->
    <div class="page-title-area">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="breadcrumbs-area clearfix">
                    <h4 class="page-title pull-left">Course Edit</h4>
                    <ul class="breadcrumbs pull-left">
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('group.index') }}">All Courses</a></li>
                        <li><span>Edit Course</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-6 clearfix">
                @include('backend.layouts.partials.logout')
            </div>
        </div>
    </div>
    <!-- page title area end -->

    <div class="main-content-inner">
        <div class="row">
            <!-- data table start -->
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Edit Course</h4>
                        @include('backend.layouts.partials.messages')

                        <form action="{{ route('group.update', $group->id) }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $group->name) }}" required>

                                    <div class="mt-5">
                                        <label for="name">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $group->description) }}</textarea>
                                    </div>
                                    <div class="mt-5">
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="password">Users</label>
                                                <select name="users[]" id="users" class="form-control select2" multiple>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}"
                                                            {{ in_array($user->id, $group->users->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                            {{ $user->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
            
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="password">Rooms</label>
                                                <select name="rooms[]" id="rooms" class="form-control select2" multiple>
                                                    @foreach ($rooms as $room)
                                                        <option value="{{ $room->id }}"
                                                            {{ in_array($room->id, $group->rooms->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                            {{ $room->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 col-sm-12">
                                    <h3 class="header-title">Schedule</h3>
                                    <div class="mt-5">
                                        <!-- Datepicker 1 -->
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <div class="datepicker-wrapper">
                                                    <label class="form-label">Start Date</label>
                                                    <input type="text" id="start_date" name="start_date"
                                                        class="form-control datepicker" readonly placeholder="DD/MM/YYYY"
                                                        value="{{ Carbon\Carbon::parse($group->start_date)->format('d-m-Y') }}"
                                                        required>
                                                    <div class="datepicker-popup">
                                                        <div class="calendar-header">
                                                            <button
                                                                class="btn btn-sm btn-outline-secondary prev-month">&lt;</button>
                                                            <div class="d-flex align-items-center">
                                                                <select class="form-select form-select-sm me-2 month-select"
                                                                    style="width: auto;"></select>
                                                                <select class="form-select form-select-sm year-select"
                                                                    style="width: auto;"></select>
                                                            </div>
                                                            <button
                                                                class="btn btn-sm btn-outline-secondary next-month">&gt;</button>
                                                        </div>
                                                        <div class="calendar-grid"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Datepicker 2 -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="datepicker-wrapper">
                                                    <label class="form-label">End Date</label>
                                                    <input type="text" id="end_date" name="end_date"
                                                        class="form-control datepicker" readonly placeholder="DD/MM/YYYY"
                                                        value="{{ Carbon\Carbon::parse($group->end_date)->format('d-m-Y') }}"
                                                        required>
                                                    <div class="datepicker-popup">
                                                        <div class="calendar-header">
                                                            <button
                                                                class="btn btn-sm btn-outline-secondary prev-month">&lt;</button>
                                                            <div class="d-flex align-items-center">
                                                                <select class="form-select form-select-sm me-2 month-select"
                                                                    style="width: auto;"></select>
                                                                <select class="form-select form-select-sm year-select"
                                                                    style="width: auto;"></select>
                                                            </div>
                                                            <button
                                                                class="btn btn-sm btn-outline-secondary next-month">&gt;</button>
                                                        </div>
                                                        <div class="calendar-grid"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-5">
                                        @props(['name' => 'schedule', 'values' => $group->schedule])

                                        @php
                                            $days = [
                                                'monday' => 'Monday',
                                                'tuesday' => 'Tuesday',
                                                'wednesday' => 'Wednesday',
                                                'thursday' => 'Thursday',
                                                'friday' => 'Friday',
                                                'saturday' => 'Saturday',
                                                'sunday' => 'Sunday',
                                            ];

                                            // Xử lý values từ old input hoặc model
                                            $oldValues = old($name, $values ?? []);
                                        @endphp

                                        <div class="weekly-schedule">
                                            @foreach ($days as $dayKey => $dayLabel)
                                                <div class="card mb-3">
                                                    <div class="card-body">
                                                        <div class="row align-items-center">
                                                            <div class="col-md-2">
                                                                <div class="form-check">
                                                                    <input type="checkbox"
                                                                        class="form-check-input day-toggle"
                                                                        id="day_{{ $dayKey }}"
                                                                        @checked(!empty($oldValues[$dayKey]['start_time']))>
                                                                    <label class="form-check-label"
                                                                        for="day_{{ $dayKey }}">
                                                                        {{ $dayLabel }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-10">
                                                                <div class="row time-inputs"
                                                                    @if (empty($oldValues[$dayKey]['start_time'])) style="display: none;" @endif>
                                                                    <div class="col-md-5">
                                                                        <label class="form-label">Start Time</label>
                                                                        <input type="time"
                                                                            name="{{ $name }}[{{ $dayKey }}][start_time]"
                                                                            class="form-control start-time"
                                                                            value="{{ $oldValues[$dayKey]['start_time'] ?? '' }}"
                                                                            @error("$name.$dayKey.start_time") is-invalid @enderror>
                                                                        @error("$name.$dayKey.start_time")
                                                                            <div class="invalid-feedback">
                                                                                {{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                    <div class="col-md-5">
                                                                        <label class="form-label">End Time</label>
                                                                        <input type="time"
                                                                            name="{{ $name }}[{{ $dayKey }}][end_time]"
                                                                            class="form-control end-time"
                                                                            value="{{ $oldValues[$dayKey]['end_time'] ?? '' }}"
                                                                            @error("$name.$dayKey.end_time") is-invalid @enderror>
                                                                        @error("$name.$dayKey.end_time")
                                                                            <div class="invalid-feedback">
                                                                                {{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="mt-5">
                                        <label for="allowed_days" class="form-label">Teacher First</label><br>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="teacher_first"
                                                {{ $group->teacher_first ? 'checked' : '' }} value="1">
                                            <label class="form-check-label" for="">On</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="teacher_first"
                                                {{ !$group->teacher_first ? 'checked' : '' }} value="0">
                                            <label class="form-check-label" for="">Off</label>
                                        </div>
                                    </div>
                                    <div class="mt-5">
                                        <label for="allowed_days" class="form-label">Status</label><br>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status"
                                                {{ $group->status ? 'checked' : '' }} value="1">
                                            <label class="form-check-label" for="">Active</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status"
                                                {{ !$group->status ? 'checked' : '' }} value="0">
                                            <label class="form-check-label" for="">Inactive</label>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            

                            <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save Course</button>
                            <a href="{{ route('group.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
            <!-- data table end -->

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        })
        class Datepicker {
            constructor(wrapper) {
                this.wrapper = wrapper;
                this.currentDate = new Date();
                this.selectedDate = null;
                this.displayedMonth = this.currentDate.getMonth();
                this.displayedYear = this.currentDate.getFullYear();

                // Get elements within this wrapper
                this.input = wrapper.querySelector('.datepicker');
                this.popup = wrapper.querySelector('.datepicker-popup');
                this.monthSelect = wrapper.querySelector('.month-select');
                this.yearSelect = wrapper.querySelector('.year-select');
                this.calendarGrid = wrapper.querySelector('.calendar-grid');
                this.prevButton = wrapper.querySelector('.prev-month');
                this.nextButton = wrapper.querySelector('.next-month');

                this.initializeControls();
                this.setupEventListeners();
                this.renderCalendar();
            }

            initializeControls() {
                // Populate months
                const months = ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6',
                    'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
                ];
                months.forEach((month, index) => {
                    const option = document.createElement('option');
                    option.value = index;
                    option.textContent = month;
                    this.monthSelect.appendChild(option);
                });
                this.monthSelect.value = this.displayedMonth;

                // Populate years
                const currentYear = this.currentDate.getFullYear();
                for (let year = currentYear - 10; year <= currentYear + 10; year++) {
                    const option = document.createElement('option');
                    option.value = year;
                    option.textContent = year;
                    this.yearSelect.appendChild(option);
                }
                this.yearSelect.value = this.displayedYear;
            }

            setupEventListeners() {
                this.input.addEventListener('click', () => this.toggleDatepicker());
                this.prevButton.addEventListener('click', () => this.changeMonth(-1));
                this.nextButton.addEventListener('click', () => this.changeMonth(1));
                this.monthSelect.addEventListener('change', () => this.updateCalendar());
                this.yearSelect.addEventListener('change', () => this.updateCalendar());

                document.addEventListener('click', (e) => {
                    if (!this.wrapper.contains(e.target)) {
                        this.popup.classList.remove('show');
                    }
                });
            }

            toggleDatepicker() {
                // Close all other datepickers first
                document.querySelectorAll('.datepicker-popup.show').forEach(popup => {
                    if (popup !== this.popup) {
                        popup.classList.remove('show');
                    }
                });
                this.popup.classList.toggle('show');
            }

            changeMonth(delta) {
                this.displayedMonth += delta;
                if (this.displayedMonth > 11) {
                    this.displayedMonth = 0;
                    this.displayedYear++;
                } else if (this.displayedMonth < 0) {
                    this.displayedMonth = 11;
                    this.displayedYear--;
                }
                this.monthSelect.value = this.displayedMonth;
                this.yearSelect.value = this.displayedYear;
                this.renderCalendar();
            }

            updateCalendar() {
                this.displayedMonth = parseInt(this.monthSelect.value);
                this.displayedYear = parseInt(this.yearSelect.value);
                this.renderCalendar();
            }

            renderCalendar() {
                this.calendarGrid.innerHTML = '';

                // Add weekday headers
                const weekdays = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
                weekdays.forEach(day => {
                    const dayElement = document.createElement('div');
                    dayElement.className = 'weekday-header';
                    dayElement.textContent = day;
                    this.calendarGrid.appendChild(dayElement);
                });

                const firstDay = new Date(this.displayedYear, this.displayedMonth, 1);
                const lastDay = new Date(this.displayedYear, this.displayedMonth + 1, 0);
                const startingDay = firstDay.getDay();

                // Add empty cells for days before the first day of the month
                for (let i = 0; i < startingDay; i++) {
                    const emptyDay = document.createElement('div');
                    emptyDay.className = 'calendar-day disabled';
                    this.calendarGrid.appendChild(emptyDay);
                }

                // Add days of the month
                for (let day = 1; day <= lastDay.getDate(); day++) {
                    const dayElement = document.createElement('div');
                    dayElement.className = 'calendar-day';
                    dayElement.textContent = day;

                    const currentDate = new Date(this.displayedYear, this.displayedMonth, day);

                    if (this.isToday(currentDate)) {
                        dayElement.classList.add('today');
                    }

                    if (this.isSelected(currentDate)) {
                        dayElement.classList.add('selected');
                    }

                    dayElement.addEventListener('click', () => this.selectDate(day));
                    this.calendarGrid.appendChild(dayElement);
                }
            }

            isToday(date) {
                const today = new Date();
                return date.getDate() === today.getDate() &&
                    date.getMonth() === today.getMonth() &&
                    date.getFullYear() === today.getFullYear();
            }

            isSelected(date) {
                if (!this.selectedDate) return false;
                return date.getDate() === this.selectedDate.getDate() &&
                    date.getMonth() === this.selectedDate.getMonth() &&
                    date.getFullYear() === this.selectedDate.getFullYear();
            }

            selectDate(day) {
                this.selectedDate = new Date(this.displayedYear, this.displayedMonth, day);
                this.input.value = this.formatDate(this.selectedDate);
                this.renderCalendar();
                this.popup.classList.remove('show');
            }

            formatDate(date) {
                const day = date.getDate().toString().padStart(2, '0');
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const year = date.getFullYear();
                return `${day}-${month}-${year}`;
            }
        }

        // Initialize all datepickers
        document.querySelectorAll('.datepicker-wrapper').forEach(wrapper => {
            new Datepicker(wrapper);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const schedule = document.querySelector('.weekly-schedule');

            // Xử lý toggle ngày
            schedule.querySelectorAll('.day-toggle').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const timeInputs = this.closest('.card-body').querySelector('.time-inputs');
                    timeInputs.style.display = this.checked ? 'flex' : 'none';

                    if (!this.checked) {
                        timeInputs.querySelectorAll('input[type="time"]').forEach(input => {
                            input.value = '';
                            input.classList.remove('is-invalid');
                            input.nextElementSibling.textContent = '';
                        });
                    }
                });
            });

            // Ngăn chặn form submit khi có lỗi
            const form = schedule.closest('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    let hasError = false;

                    // Kiểm tra tất cả các ngày được chọn
                    schedule.querySelectorAll('.day-toggle:checked').forEach(checkbox => {
                        const cardBody = checkbox.closest('.card-body');
                        const startTime = cardBodyx.querySelector('.start-time');
                        const endTime = cardBodyx.querySelector('.end-time');

                        if (!validateTimeRange(startTime, endTime)) {
                            hasError = true;
                        }
                    });

                    if (hasError) {
                        e.preventDefault(); // Ngăn form submit nếu có lỗi
                        // Scroll đến lỗi đầu tiên
                        const firstError = schedule.querySelector('.is-invalid');
                        if (firstError) {
                            firstError.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        }
                    }
                });
            }

            // Validate khi thay đổi giá trị
            schedule.querySelectorAll('.time-inputs input[type="time"]').forEach(input => {
                input.addEventListener('change', function() {
                    const cardBody = this.closest('.card-body');
                    const startTime = cardBody.querySelector('.start-time');
                    const endTime = cardBody.querySelector('.end-time');
                    validateTimeRange(startTime, endTime);
                });
            });
        });

        function validateTimeRange(startInput, endInput) {
            // Reset validation state
            [startInput, endInput].forEach(input => {
                input.classList.remove('is-invalid');
                const feedback = input.nextElementSibling;
                if (feedback) {
                    feedback.textContent = '';
                }
            });

            // Kiểm tra nếu có start time thì phải có end time
            if (startInput.value && !endInput.value) {
                endInput.classList.add('is-invalid');
                if(endInput.nextElementSibling)
                endInput.nextElementSibling.textContent = 'The field is required for the end time';
                return false;
            }

            // Kiểm tra nếu có end time thì phải có start time
            if (!startInput.value && endInput.value) {
                startInput.classList.add('is-invalid');
                if(startInput.nextElementSibling)
                startInput.nextElementSibling.textContent = 'The field is required for the start time';
                return false;
            }

            // Kiểm tra end time phải sau start time
            if (startInput.value && endInput.value && endInput.value <= startInput.value) {
                endInput.classList.add('is-invalid');
                if(endInput.nextElementSibling)
                endInput.nextElementSibling.textContent = 'The end time must be after the start time';
                return false;
            }

            return true;
        }
    </script>
@endsection
