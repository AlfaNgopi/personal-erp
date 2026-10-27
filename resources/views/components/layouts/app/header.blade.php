<!-- Header -->
<header class="bg-white dark:bg-gray-800 shadow-sm z-20 border-b border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between h-16 px-4">
        <!-- Left side: Logo and toggle -->
        <div class="flex items-center">
            <button @click="toggleSidebar"
                class="p-2 rounded-md text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="ml-4 font-semibold text-xl text-blue-600 dark:text-blue-400">{{ config('app.name') }}</div>
        </div>

        <!-- Right side: Theme toggle, Search, notifications, profile -->
        <div class="flex items-center space-x-4">

            <!-- Workspace Switcher -->
            @php
            $user = auth()->user();

            // Sesuaikan relasi workspaces() dengan model User kamu.
            $workspaces = $user->workspaces()->orderBy('name')->get();

            

            $currentWorkspaceId = session('active_workspace_id');

            $currentWorkspace = app(\App\Models\WorkSpace::class)->find($currentWorkspaceId);
            @endphp

            <div x-data="{ open: false }" class="relative">
                <button
                    type="button"
                    @click="open = !open"
                    @keydown.escape.window="open = false"
                    class="flex items-center gap-2 rounded-lg border border-gray-200
               bg-white px-3 py-2 text-sm shadow-sm transition
               hover:bg-gray-50 focus:outline-none
               dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"
                    :aria-expanded="open.toString()"
                    aria-haspopup="true">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg
                     bg-blue-100 text-blue-700 dark:bg-blue-900
                     dark:text-blue-300">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4
                         M9 9v.01M9 12v.01M9 15v.01M9 18v.01" />
                        </svg>
                    </span>

                    <span class="hidden max-w-32 text-left sm:block">
                        <span class="block text-xs text-gray-500 dark:text-gray-400">
                            Workspace
                        </span>
                        <span class="block truncate font-medium text-gray-800 dark:text-gray-100">
                            {{ $currentWorkspace?->name ?? 'Pilih workspace' }}
                        </span>
                    </span>

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-4 w-4 text-gray-500 transition-transform"
                        :class="{ 'rotate-180': open }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div
                    x-cloak
                    x-show="open"
                    @click.outside="open = false"
                    x-transition
                    class="absolute right-0 z-50 mt-2 w-64 overflow-hidden
               rounded-xl border border-gray-200 bg-white shadow-lg
               dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-100 px-4 py-3
                    dark:border-gray-700">
                        <p class="text-xs font-medium uppercase tracking-wide
                      text-gray-500 dark:text-gray-400">
                            Workspace kamu
                        </p>
                    </div>

                    <div class="max-h-72 overflow-y-auto p-2">
                        @forelse ($workspaces as $workspace)
                        <a
                            href="{{ route('switch-workspace', $workspace->id) }}"
                            @click="open = false"
                            class="flex items-center gap-3 rounded-lg px-3 py-2
                           transition hover:bg-gray-100
                           dark:hover:bg-gray-700
                           {{ $currentWorkspace?->is($workspace)
                                ? 'bg-blue-50 text-blue-700 dark:bg-blue-950 dark:text-blue-300'
                                : 'text-gray-700 dark:text-gray-200' }}">
                            <span class="flex h-9 w-9 shrink-0 items-center
                                 justify-center rounded-lg bg-gray-100
                                 text-gray-600 dark:bg-gray-700
                                 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4" />
                                </svg>
                            </span>

                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-medium">
                                    {{ $workspace->name }}
                                </span>
                                <span class="block truncate text-xs text-gray-500
                                     dark:text-gray-400">
                                    {{ $workspace->slug }}
                                </span>
                            </span>

                            @if ($currentWorkspace?->is($workspace))
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            @endif
                        </a>
                        @empty
                        <p class="px-3 py-4 text-sm text-gray-500
                          dark:text-gray-400">
                            Belum ada workspace.
                        </p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Theme Toggle -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="p-2 rounded-md text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none transition-colors duration-200">
                    <!-- Sun icon for light mode -->
                    <svg x-show="localStorage.theme !== 'dark'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z" />
                    </svg>
                    <!-- Moon icon for dark mode -->
                    <svg x-show="localStorage.theme === 'dark'" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" x-transition
                    class="absolute border border-gray-200 dark:border-gray-700 right-0 mt-2 w-36 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50">
                    <form id="header-appearance-form" action="{{ route('settings.appearance.update') }}" method="POST"
                        class="hidden">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="theme_preference" id="header_theme_preference">
                    </form>

                    <button type="button" onclick="persistTheme('light')"
                        class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center {{ (auth()->user()->theme_preference ?? 'system') === 'light' ? 'bg-gray-100 text-blue-700 dark:bg-gray-700 dark:text-blue-400 font-medium' : 'text-gray-700 dark:text-gray-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 9H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 5a7 7 0 100 14 7 7 0 000-14z" />
                        </svg>
                        Light
                    </button>
                    <button type="button" onclick="persistTheme('dark')"
                        class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center {{ (auth()->user()->theme_preference ?? 'system') === 'dark' ? 'bg-gray-100 text-blue-700 dark:bg-gray-700 dark:text-blue-400 font-medium' : 'text-gray-700 dark:text-gray-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        Dark
                    </button>
                    <button type="button" onclick="persistTheme('system')"
                        class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center {{ (auth()->user()->theme_preference ?? 'system') === 'system' ? 'bg-gray-100 text-blue-700 dark:bg-gray-700 dark:text-blue-400 font-medium' : 'text-gray-700 dark:text-gray-300' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        System
                    </button>
                </div>

                <script>
                    window.persistTheme = function(theme) {
                        // Update UI immediately (client-side)
                        if (typeof window.setAppearance === 'function') {
                            window.setAppearance(theme);
                        }

                        // Set and submit form for persistence
                        const form = document.getElementById('header-appearance-form');
                        const input = document.getElementById('header_theme_preference');
                        if (form && input) {
                            input.value = theme;
                            form.submit();
                        }
                    }
                </script>
            </div>
            <!-- Profile -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center focus:outline-none">
                    <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                        <span
                            class="flex h-full w-full items-center justify-center rounded-lg bg-gray-200 text-black dark:bg-gray-700 dark:text-white">
                            {{ Auth::user()->initials() }}
                        </span>
                    </span>
                    <span class="ml-2 hidden md:block">{{ Auth::user()->name }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" :class="{ 'block': open, 'hidden': !open }"
                    class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700">
                    <a href="{{ route('settings.profile.edit') }}"
                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Settings
                        </div>
                    </a>
                    <div class="border-t border-gray-200 dark:border-gray-700"></div>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit"
                            class="block w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </div>
                        </button>
                    </form>
                    <a href="{{ route('api-token') }}"
                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            API Token
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>