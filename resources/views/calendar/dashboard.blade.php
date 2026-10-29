<x-layouts.app>
    <div class="space-y-6">
        {{-- Page Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    {{ __('Calendar Dashboard') }}
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Manage your events, tasks, and daily reviews in one place.') }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button type="button" id="todayButton"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    {{ __('Today') }}
                </button>
                <a href="{{ route('calendar-events.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('Add Event') }}
                </a>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
            @php
                $summaryCards = [
                    ['label' => __('Events This Month'), 'value' => $eventCount, 'hint' => __('Scheduled activities'), 'color' => 'blue', 'bgClass' => 'bg-blue-100 dark:bg-blue-900/40', 'textClass' => 'text-blue-600 dark:text-blue-400', 'icon' => 'M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13H4V6a1 1 0 011-1z'],
                    ['label' => __('Pending Tasks'), 'value' => $pendingTaskCount, 'hint' => __('Tasks to follow up'), 'color' => 'amber', 'bgClass' => 'bg-amber-100 dark:bg-amber-900/40', 'textClass' => 'text-amber-600 dark:text-amber-400', 'icon' => 'M9 5h6m-7 4h8m-8 4h5m-8 7h14a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['label' => __('Completed Tasks'), 'value' => $completedTaskCount, 'hint' => __('Tasks completed'), 'color' => 'green', 'bgClass' => 'bg-green-100 dark:bg-green-900/40', 'textClass' => 'text-green-600 dark:text-green-400', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => __('Reviews This Month'), 'value' => $reviewCount, 'hint' => __('Daily reviews recorded'), 'color' => 'purple', 'bgClass' => 'bg-purple-100 dark:bg-purple-900/40', 'textClass' => 'text-purple-600 dark:text-purple-400', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.96a1 1 0 00.95.69h4.164c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.96c.299.921-.755 1.688-1.539 1.118l-3.37-2.448a1 1 0 00-1.176 0l-3.37 2.448c-.784.57-1.838-.197-1.539-1.118l1.287-3.96a1 1 0 00-.364-1.118L4.1 9.387c-.783-.57-.38-1.81.588-1.81h4.164a1 1 0 00.951-.69l1.246-3.96z'],
                ];
            @endphp

            @foreach ($summaryCards as $card)
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $card['label'] }}</p>
                            <p class="mt-2 text-3xl font-bold text-gray-800 dark:text-gray-100">{{ $card['value'] }}</p>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ $card['hint'] }}</p>
                        </div>
                        <div class="rounded-full p-3 {{ $card['bgClass'] }}">
                            <svg class="h-6 w-6 {{ $card['textClass'] }}"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $card['icon'] }}"/>
                            </svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Calendar + Selected Day --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
            <section class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 xl:col-span-2">
                <div class="flex flex-col gap-4 border-b border-gray-200 p-5 dark:border-gray-700 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 id="monthTitle" class="text-lg font-semibold text-gray-800 dark:text-gray-100"></h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Select a date to view its activities.') }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" id="prevMonth" aria-label="{{ __('Previous month') }}"
                            class="rounded-lg border border-gray-300 p-2 text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button type="button" id="nextMonth" aria-label="{{ __('Next month') }}"
                            class="rounded-lg border border-gray-300 p-2 text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                <div class="p-3 sm:p-5">
                    <div class="mb-2 grid grid-cols-7">
                        @foreach ([__('Mon'), __('Tue'), __('Wed'), __('Thu'), __('Fri'), __('Sat'), __('Sun')] as $weekday)
                            <div class="py-2 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $weekday }}</div>
                        @endforeach
                    </div>
                    <div id="calendarGrid" class="grid grid-cols-7 gap-1.5 sm:gap-2" aria-live="polite"></div>

                    <div class="mt-5 flex flex-wrap gap-x-5 gap-y-2 border-t border-gray-100 pt-4 dark:border-gray-700">
                        <span class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300"><span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>{{ __('Events') }}</span>
                        <span class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>{{ __('Tasks') }}</span>
                        <span class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-300"><span class="h-2.5 w-2.5 rounded-full bg-purple-500"></span>{{ __('Reviews') }}</span>
                    </div>
                </div>
            </section>

            {{-- Selected Date Details --}}
            <aside class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-200 p-5 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Selected date') }}</p>
                    <h2 id="selectedDateTitle" class="mt-1 text-lg font-semibold text-gray-800 dark:text-gray-100"></h2>
                    <p id="selectedDateSubtitle" class="mt-1 text-sm text-gray-500 dark:text-gray-400"></p>
                </div>
                <div id="dayDetails" class="max-h-[620px] space-y-5 overflow-y-auto p-5">
                    <div class="py-8 text-center text-sm text-gray-500 dark:text-gray-400">{{ __('Loading activities...') }}</div>
                </div>
            </aside>
        </div>
    </div>

    <script>
        (() => {
            const dataUrl = @json(route('calendar.data'));
            const dayUrl = @json(route('calendar.day'));
            const locale = @json(app()->getLocale());
            const grid = document.getElementById('calendarGrid');
            const monthTitle = document.getElementById('monthTitle');
            const selectedDateTitle = document.getElementById('selectedDateTitle');
            const selectedDateSubtitle = document.getElementById('selectedDateSubtitle');
            const dayDetails = document.getElementById('dayDetails');

            let visibleMonth = new Date();
            visibleMonth = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth(), 1);
            let selectedDate = toDateKey(new Date());
            let monthData = {};

            const dateFormatter = new Intl.DateTimeFormat(locale, { day: 'numeric', month: 'long', year: 'numeric' });
            const monthFormatter = new Intl.DateTimeFormat(locale, { month: 'long', year: 'numeric' });

            function toDateKey(date) {
                const y = date.getFullYear();
                const m = String(date.getMonth() + 1).padStart(2, '0');
                const d = String(date.getDate()).padStart(2, '0');
                return `${y}-${m}-${d}`;
            }

            function escapeHtml(value) {
                return String(value ?? '').replace(/[&<>"']/g, char => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
                })[char]);
            }

            function activityCount(dateKey) {
                const day = monthData[dateKey] || {};
                return (day.events?.length || 0) + (day.tasks?.length || 0) + (day.reviews?.length || 0);
            }

            async function loadMonth() {
                monthTitle.textContent = monthFormatter.format(visibleMonth);
                grid.innerHTML = '<div class="col-span-7 py-12 text-center text-sm text-gray-500">Loading calendar...</div>';

                const month = `${visibleMonth.getFullYear()}-${String(visibleMonth.getMonth() + 1).padStart(2, '0')}`;
                try {
                    const response = await fetch(`${dataUrl}?month=${encodeURIComponent(month)}`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!response.ok) throw new Error('Unable to load calendar data');
                    monthData = await response.json();
                    renderCalendar();
                    loadDay(selectedDate);
                } catch (error) {
                    grid.innerHTML = '<div class="col-span-7 rounded-lg bg-red-50 p-4 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-300">Calendar data could not be loaded.</div>';
                }
            }

            function renderCalendar() {
                grid.innerHTML = '';
                const year = visibleMonth.getFullYear();
                const month = visibleMonth.getMonth();
                const firstDay = new Date(year, month, 1);
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const mondayOffset = (firstDay.getDay() + 6) % 7;
                const totalCells = Math.ceil((mondayOffset + daysInMonth) / 7) * 7;
                const todayKey = toDateKey(new Date());

                for (let i = 0; i < totalCells; i++) {
                    const dayNumber = i - mondayOffset + 1;
                    const inMonth = dayNumber >= 1 && dayNumber <= daysInMonth;
                    const date = new Date(year, month, dayNumber);
                    const key = toDateKey(date);
                    const count = inMonth ? activityCount(key) : 0;
                    const isSelected = key === selectedDate;
                    const isToday = key === todayKey;
                    const day = monthData[key] || {};
                    const dots = [
                        ...(day.events?.length ? ['bg-blue-500'] : []),
                        ...(day.tasks?.length ? ['bg-amber-500'] : []),
                        ...(day.reviews?.length ? ['bg-purple-500'] : [])
                    ];

                    const button = document.createElement('button');
                    button.type = 'button';
                    button.disabled = !inMonth;
                    button.setAttribute('aria-label', dateFormatter.format(date));
                    button.setAttribute('aria-pressed', String(isSelected));
                    button.className = [
                        'min-h-[76px] rounded-lg border p-1.5 text-left transition sm:min-h-[94px] sm:p-2',
                        inMonth ? 'hover:border-blue-300 hover:bg-blue-50/60 dark:hover:bg-gray-700/60' : 'cursor-default border-transparent',
                        inMonth ? 'border-gray-200 dark:border-gray-700' : '',
                        isSelected ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500 dark:bg-blue-900/20' : 'bg-white dark:bg-gray-800',
                        isToday && !isSelected ? 'border-blue-300 dark:border-blue-700' : ''
                    ].join(' ');

                    button.innerHTML = `
                        <div class="flex items-center justify-between gap-1">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full text-sm font-medium ${inMonth ? 'text-gray-700 dark:text-gray-200' : 'text-transparent'} ${isToday ? 'bg-blue-600 text-white dark:text-white' : ''}">${inMonth ? dayNumber : ''}</span>
                            ${count ? `<span class="text-[10px] font-medium text-gray-500 dark:text-gray-400">${count}</span>` : ''}
                        </div>
                        <div class="mt-2 flex min-h-2 flex-wrap gap-1">${dots.map(dot => `<span class="h-1.5 w-1.5 rounded-full ${dot}"></span>`).join('')}</div>
                        <div class="mt-1 hidden space-y-1 sm:block">
                            ${(day.events || []).slice(0, 1).map(item => `<p class="truncate rounded bg-blue-50 px-1 py-0.5 text-[10px] text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">${escapeHtml(item.title)}</p>`).join('')}
                            ${(day.tasks || []).slice(0, 1).map(item => `<p class="truncate rounded bg-amber-50 px-1 py-0.5 text-[10px] text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">${escapeHtml(item.title)}</p>`).join('')}
                        </div>`;
                    if (inMonth) button.addEventListener('click', () => {
                        selectedDate = key;
                        renderCalendar();
                        loadDay(key);
                    });
                    grid.appendChild(button);
                }
            }

            function section(title, color, items, type) {
                const heading = `<div class="mb-3 flex items-center justify-between"><h3 class="text-sm font-semibold text-gray-800 dark:text-gray-100">${title}</h3><span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600 dark:bg-gray-700 dark:text-gray-300">${items.length}</span></div>`;
                if (!items.length) return heading + `<p class="rounded-lg border border-dashed border-gray-200 px-3 py-4 text-xs text-gray-500 dark:border-gray-700 dark:text-gray-400">No ${title.toLowerCase()} for this date.</p>`;

                return heading + `<div class="space-y-2">${items.map(item => {
                    const meta = type === 'events'
                        ? [item.time, item.type].filter(Boolean).join(' · ')
                        : type === 'tasks'
                            ? [item.status, item.priority ? `Priority: ${item.priority}` : ''].filter(Boolean).join(' · ')
                            : (item.rating !== null && item.rating !== undefined ? `Rating: ${escapeHtml(item.rating)}/5` : '');
                    return `<article class="rounded-lg border border-gray-200 p-3 dark:border-gray-700">
                        <div class="flex gap-3">
                            <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full ${color}"></span>
                            <div class="min-w-0 flex-1">
                                <p class="break-words text-sm font-medium text-gray-800 dark:text-gray-100">${escapeHtml(item.title || item.notes || 'Daily review')}</p>
                                ${meta ? `<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">${escapeHtml(meta)}</p>` : ''}
                                ${item.description ? `<p class="mt-2 whitespace-pre-line text-xs leading-5 text-gray-600 dark:text-gray-300">${escapeHtml(item.description)}</p>` : ''}
                                ${item.notes && item.title ? `<p class="mt-2 whitespace-pre-line text-xs leading-5 text-gray-600 dark:text-gray-300">${escapeHtml(item.notes)}</p>` : ''}
                            </div>
                        </div>
                    </article>`;
                }).join('')}</div>`;
            }

            async function loadDay(dateKey) {
                selectedDateTitle.textContent = dateFormatter.format(new Date(`${dateKey}T12:00:00`));
                selectedDateSubtitle.textContent = 'Loading daily activities...';
                dayDetails.innerHTML = '<div class="py-8 text-center text-sm text-gray-500 dark:text-gray-400">Loading...</div>';

                try {
                    const response = await fetch(`${dayUrl}?date=${encodeURIComponent(dateKey)}`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!response.ok) throw new Error('Unable to load day');
                    const data = await response.json();
                    selectedDateSubtitle.textContent = `${(data.events?.length || 0) + (data.tasks?.length || 0) + (data.reviews?.length || 0)} activities`;
                    dayDetails.innerHTML =
                        section('Events', 'bg-blue-500', data.events || [], 'events') +
                        '<div class="border-t border-gray-100 pt-4 dark:border-gray-700"></div>' +
                        section('Tasks', 'bg-amber-500', data.tasks || [], 'tasks') +
                        '<div class="border-t border-gray-100 pt-4 dark:border-gray-700"></div>' +
                        section('Reviews', 'bg-purple-500', data.reviews || [], 'reviews');
                } catch (error) {
                    selectedDateSubtitle.textContent = '';
                    dayDetails.innerHTML = '<p class="rounded-lg bg-red-50 p-4 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-300">Could not load activities for this date.</p>';
                }
            }

            document.getElementById('prevMonth').addEventListener('click', () => {
                visibleMonth = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth() - 1, 1);
                loadMonth();
            });
            document.getElementById('nextMonth').addEventListener('click', () => {
                visibleMonth = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth() + 1, 1);
                loadMonth();
            });
            document.getElementById('todayButton').addEventListener('click', () => {
                const today = new Date();
                visibleMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                selectedDate = toDateKey(today);
                loadMonth();
            });

            loadMonth();
        })();
    </script>
</x-layouts.app>
