<x-layouts.app>
    @php
    $showColumns = collect($columns)->filter(fn($c) => ($c['inShowPage'] ?? false) && ($c['type'] ?? 'string') !== 'password');
    @endphp

    {{-- Breadcrumb --}}
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        @if (!empty($parent))
        <span class="text-gray-500 dark:text-gray-400">{{ $parent }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        @endif
        <a href="{{ route($table . '.index') }}"
            class="text-blue-600 dark:text-blue-400 hover:underline">{{ $title }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Detail') }}</span>
    </div>

    {{-- Header --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $title }} {{ __('Detail') }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('View') }} {{ $title }} {{ __('details') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route($table . '.index') }}">
                <x-button type="secondary">{{ __('Back') }}</x-button>
            </a>

            @if (auth()->user()->hasPermission('edit-' . $table) && ($canEdit ?? true) !== false)
            <a href="{{ route($table . '.edit', $data) }}">
                <x-button type="primary">{{ __('Edit') }}</x-button>
            </a>
            @endif
        </div>
    </div>

    {{-- Detail --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <dl class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach ($showColumns as $column)
            @php
            $name = $column['name'];
            $label = __($column['label'] ?? ucfirst(str_replace('_', ' ', $name)));
            $type = $column['type'] ?? 'string';
            $value = $data->{$name} ?? null;
            @endphp

            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $label }}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100 sm:col-span-2">
                    @switch($type)
                    @case('select')
                    @php
                    $option = collect($column['options'] ?? [])->firstWhere('value', $value);
                    @endphp
                    {{ $option['label'] ?? ($value ?? '-') }}
                    @break

                    @case('checkbox')
                    @case('boolean')
                    @if ($value)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">{{ __('Yes') }}</span>
                    @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">{{ __('No') }}</span>
                    @endif
                    @break

                    @case('image')
                    @if (!empty($value))
                    <img src="{{ asset('storage/' . $value) }}" alt="{{ $label }}"
                        class="h-32 w-auto rounded border border-gray-200 dark:border-gray-700">
                    @else
                    -
                    @endif
                    @break

                    @case('file')
                    @if (!empty($value))
                    <a href="{{ asset('storage/' . $value) }}" target="_blank"
                        class="text-blue-600 dark:text-blue-400 hover:underline">{{ basename($value) }}</a>
                    @else
                    -
                    @endif
                    @break

                    @case('date')
                    {{ $value ? \Carbon\Carbon::parse($value)->format('d M Y') : '-' }}
                    @break

                    @case('number')
                    @case('integer')
                    @case('decimal')
                    {{ $value !== null ? $value : '-' }}
                    @break

                    @case('textarea')
                    {!! $value ? nl2br(e($value)) : '-' !!}
                    @break

                    @default
                    {{ $value !== null && $value !== '' ? $value : '-' }}
                    @endswitch
                </dd>
            </div>
            @endforeach

            @if ($table_has_timestamps ?? false)
            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Created At') }}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100 sm:col-span-2">
                    {{ optional($data->created_at)->format('d M Y H:i') ?? '-' }}
                </dd>
            </div>
            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-3 gap-1 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Updated At') }}</dt>
                <dd class="text-sm text-gray-900 dark:text-gray-100 sm:col-span-2">
                    {{ optional($data->updated_at)->format('d M Y H:i') ?? '-' }}
                </dd>
            </div>
            @endif
        </dl>
    </div>
</x-layouts.app>