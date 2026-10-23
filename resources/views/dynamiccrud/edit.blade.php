<x-layouts.app>
    @php
    $formColumns = collect($columns)->filter(fn($c) => $c['inEditForm'] ?? false);
    $hasFile = $formColumns->contains(fn($c) => in_array($c['type'], ['file', 'image']));
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
        <span class="text-gray-500 dark:text-gray-400">{{ __('Edit') }}</span>
    </div>

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit') }} {{ $title }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Update') }} {{ $title }} {{ __('details') }}</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        @if ($errors->any())
        <div class="bg-red-50 dark:bg-red-900 text-red-700 dark:text-red-300 p-4">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="p-6">
            <form action="{{ route($table . '.update', $data) }}" method="POST" class="max-w-2xl"
                @if ($hasFile) enctype="multipart/form-data" @endif>
                @csrf
                @method('PUT')

                @foreach ($formColumns as $column)
                @php
                $name = $column['name'];
                $label = __($column['label'] ?? ucfirst(str_replace('_', ' ', $name)));
                $required = $column['required'] ?? false;
                $type = $column['type'] ?? 'string';
                $current = old($name, $data->{$name} ?? null);
                @endphp

                @switch($type)
                @case('email')
                <div class="mb-4">
                    <x-forms.input :label="$label" :name="$name" type="email"
                        :value="$current" :required="$required" />
                </div>
                @break

                @case('password')
                <div class="mb-4">
                    <x-forms.input :label="$label" :name="$name" type="password" />
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Leave blank to keep current password') }}
                    </p>
                </div>
                <div class="mb-4">
                    <x-forms.input :label="__('Confirm') . ' ' . $label" :name="$name . '_confirmation'"
                        type="password" />
                </div>
                @break

                @case('number')
                @case('integer')
                @case('decimal')
                <div class="mb-4">
                    <x-forms.input :label="$label" :name="$name" type="number"
                        step="{{ $type === 'integer' ? '1' : '0.01' }}"
                        :value="$current" :required="$required" />
                </div>
                @break

                @case('date')
                <div class="mb-4">
                    <x-forms.input :label="$label" :name="$name" type="date"
                        :value="$current instanceof \Carbon\CarbonInterface ? $current->format('Y-m-d') : $current"
                        :required="$required" />
                </div>
                @break

                @case('textarea')
                <div class="mb-4">
                    <x-forms.textarea :label="$label" :name="$name"
                        :value="$current" :required="$required" />
                </div>
                @break

                @case('select')
                <div class="mb-4">
                    <x-forms.select :label="$label" :name="$name"
                        :options="$column['options'] ?? []"
                        :value="$current" :required="$required" />
                </div>
                @break

                @case('file')
                @case('image')
                <div class="mb-4">
                    <x-forms.file :label="$label" :name="$name"
                        :accept="$type === 'image' ? 'image/*' : null"
                        :required="false" />
                    @if (!empty($data->{$name}))
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Current file') }}:
                        <a href="{{ asset('storage/' . $data->{$name}) }}" target="_blank"
                            class="text-blue-600 dark:text-blue-400 hover:underline">{{ basename($data->{$name}) }}</a>
                    </p>
                    @endif
                </div>
                @break

                @case('checkbox')
                @case('boolean')
                <div class="mb-4">
                    <x-forms.checkbox :label="$label" :name="$name"
                        :checked="(bool) $current" />
                </div>
                @break

                @default
                {{-- string / text --}}
                <div class="mb-4">
                    <x-forms.input :label="$label" :name="$name" type="text"
                        :value="$current" :required="$required" />
                </div>
                @endswitch
                @endforeach

                <div class="flex gap-3 mt-6">
                    <x-button type="primary">{{ __('Update') }}</x-button>
                    <a href="{{ route($table . '.index') }}"
                        class="text-white font-medium py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors flex items-center justify-center cursor-pointer bg-gray-600 hover:bg-gray-700 focus:ring-gray-500 dark:bg-gray-500 dark:hover:bg-gray-600">
                        {{ __('Batal') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>