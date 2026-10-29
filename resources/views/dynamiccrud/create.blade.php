<x-layouts.app>
    @php
    $formColumns = collect($columns)->filter(fn($c) => $c['inCreateForm'] ?? false);
    $hasFile = $formColumns->contains(fn($c) => in_array($c['type'], ['file', 'image']));
    @endphp

    {{-- Breadcrumb --}}
    <div class="mb-6 flex items-center text-sm">
        <a href="{{ route('dashboard') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Dashboard') }}</a>
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
        <a href="{{ route($table . '.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $title }}</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2 text-gray-400" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-500 dark:text-gray-400">{{ __('Create') }}</span>
    </div>

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Create') }} {{ $title }}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $subheading ?? __('Fill in the details below') }}</p>
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
            <form action="{{ route($table . '.store') }}" method="POST" class="max-w-2xl"
                @if ($hasFile) enctype="multipart/form-data" @endif>
                @csrf

                @foreach ($formColumns as $column)
                @php
                $name = $column['name'];
                $label = __($column['label'] ?? ucfirst(str_replace('_', ' ', $name)));
                $required = $column['required'] ?? false;
                $type = $column['type'] ?? 'string';
                @endphp

                @switch($type)
                @case('email')
                <div class="mb-4">
                    <x-forms.input :label="$label" :name="$name" type="email"
                        :value="old($name)" :required="$required" />
                </div>
                @break

                @case('password')
                <div class="mb-4">
                    <x-forms.input :label="$label" :name="$name" type="password"
                        :required="$required" />
                </div>
                <div class="mb-4">
                    <x-forms.input :label="__('Confirm') . ' ' . $label" :name="$name . '_confirmation'"
                        type="password" :required="$required" />
                </div>
                @break

                @case('number')
                @case('integer')
                @case('decimal')
                <div class="mb-4">
                    <x-forms.input :label="$label" :name="$name" type="number"
                        step="{{ $type === 'integer' ? '1' : '0.01' }}"
                        :value="old($name)" :required="$required" />
                </div>
                @break

                @case('date')
                <div class="mb-4">
                    <x-forms.input :label="$label" :name="$name" type="date"
                        :value="old($name)" :required="$required" />
                </div>
                @break

                @case('textarea')
                <div class="mb-4">
                    <x-forms.textarea :label="$label" :name="$name"
                        :value="old($name)" :required="$required" />
                </div>
                @break

                @case('select')
                <div class="mb-4">
                    <x-forms.select :label="$label" :name="$name"
                        :options="$column['options'] ?? []"
                        :value="old($name)" :required="$required" />
                </div>
                @break

                @case('enum')
                <div class="mb-4">
                    <x-forms.enum :label="$label" :name="$name"
                        :options="$column['options'] ?? []"
                        :value="old($name)" :required="$required" />
                </div>
                @break

                @case('file')
                @case('image')
                <div class="mb-4">
                    <x-forms.file :label="$label" :name="$name"
                        :accept="$type === 'image' ? 'image/*' : null"
                        :required="$required" />
                </div>
                @break

                @case('checkbox')
                @case('boolean')
                <div class="mb-4">
                    <x-forms.checkbox :label="$label" :name="$name"
                        :checked="old($name, false)" />
                </div>
                @break

                @default
                {{-- string / text --}}
                <div class="mb-4">
                    <x-forms.input :label="$label" :name="$name" type="text"
                        :value="old($name)" :required="$required" />
                </div>
                @endswitch
                @endforeach

                <div class="flex gap-3 mt-6">
                    <x-button type="primary">{{ __('Create') }}</x-button>
                    <a href="{{ route($table . '.index') }}"
                        class="text-white font-medium py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors flex items-center justify-center cursor-pointer bg-gray-600 hover:bg-gray-700 focus:ring-gray-500 dark:bg-gray-500 dark:hover:bg-gray-600">
                        {{ __('Batal') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>