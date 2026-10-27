<x-layouts.app>
    <div class="space-y-6">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    {{ __('Finance Dashboard') }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    {{ __('Overview of your financial performance') }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <select
                    id="financePeriod"
                    class="rounded-lg border border-gray-300 dark:border-gray-600
                    bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200
                    text-sm px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                    <option value="monthly">{{ __('This Year') }}</option>
                    <option value="quarterly">{{ __('Last 3 Months') }}</option>
                </select>

                <button
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg
                    bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium
                    transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Add Transaction') }}
                </button>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            {{-- Total Income --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6
                border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ __('Total Income') }}
                        </p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-2">
                            Rp {{ $totalIncome }}
                        </p>
                        <p class="text-xs text-green-600 dark:text-green-400 flex items-center mt-2">
                            <svg class="w-4 h-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                            +12.8% {{ __('from last month') }}
                        </p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2
                                3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402
                                2.599 1M12 8V7m0 1v8m0 0v1m0-1
                                c-1.11 0-2.08-.402-2.599-1" />
                            <circle cx="12" cy="12" r="9" stroke-width="2" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Expenses --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6
                border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ __('Total Expenses') }}
                        </p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-2">
                            Rp {{ $totalExpenses }}
                        </p>
                        <p class="text-xs text-red-600 dark:text-red-400 flex items-center mt-2">
                            <svg class="w-4 h-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 9l7 7 7-7" />
                            </svg>
                            +4.5% {{ __('from last month') }}
                        </p>
                    </div>
                    <div class="bg-red-100 dark:bg-red-900/50 p-3 rounded-full">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 9l-5 5-5-5m5 5V3" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M5 21h14" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Net Profit --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6
                border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ __('Net Profit') }}
                        </p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-2">
                            Rp {{ $netProfit }}
                        </p>
                        <p class="text-xs text-green-600 dark:text-green-400 flex items-center mt-2">
                            <svg class="w-4 h-4 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                            +18.2% {{ __('from last month') }}
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 3v18h18M18 17V9m-5 8V5m-5 12v-4" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Cash Balance --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6
                border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ __('Cash Balance') }}
                        </p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-2">
                            Rp {{ $cashBalance }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-2">
                            {{ __('Available funds') }}
                        </p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900/50 p-3 rounded-full">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <rect x="3" y="6" width="18" height="14" rx="2"
                                stroke-width="2" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M3 10h18M7 15h3" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Income vs Expenses --}}
            <div class="xl:col-span-2 bg-white dark:bg-gray-800 rounded-lg
                shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row sm:items-center
                    sm:justify-between gap-3 mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            {{ __('Income & Expenses') }}
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ __('Monthly financial comparison') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-4 text-sm">
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                            {{ __('Income') }}
                        </span>
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <span class="w-3 h-3 rounded-full bg-red-400"></span>
                            {{ __('Expenses') }}
                        </span>
                    </div>
                </div>

                <div class="relative h-72">
                    <canvas id="incomeExpenseChart"></canvas>
                </div>
            </div>

            {{-- Expense Categories --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6
                border border-gray-200 dark:border-gray-700">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                        {{ __('Expense Breakdown') }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ __('Expenses by category') }}
                    </p>
                </div>

                <div class="relative h-56">
                    <canvas id="expenseCategoryChart"></canvas>
                </div>

                <div class="mt-5 space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                            {{ __('Operations') }}
                        </span>
                        <span class="font-medium text-gray-800 dark:text-gray-100">35%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                            {{ __('Salaries') }}
                        </span>
                        <span class="font-medium text-gray-800 dark:text-gray-100">30%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                            {{ __('Marketing') }}
                        </span>
                        <span class="font-medium text-gray-800 dark:text-gray-100">20%</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                            <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                            {{ __('Other') }}
                        </span>
                        <span class="font-medium text-gray-800 dark:text-gray-100">15%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Section --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- Recent Transactions --}}
            <div class="xl:col-span-2 bg-white dark:bg-gray-800 rounded-lg
                shadow-sm border border-gray-200 dark:border-gray-700">

                <div class="p-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            {{ __('Recent Transactions') }}
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ __('Latest financial activities') }}
                        </p>
                    </div>
                    <a href="#"
                        class="text-sm font-medium text-blue-600 hover:text-blue-700
                        dark:text-blue-400">
                        {{ __('View all') }}
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700/50
                            text-gray-500 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3 font-medium">{{ __('Transaction') }}</th>
                                <th class="px-6 py-3 font-medium">{{ __('Date') }}</th>
                                <th class="px-6 py-3 font-medium">{{ __('Amount') }}</th>
                                <th class="px-6 py-3 font-medium">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-green-100
                                            dark:bg-green-900/40 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600"
                                                fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-gray-100">
                                                {{ __('Client Payment') }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                INV-2026-001
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                    25 Oct 2026
                                </td>
                                <td class="px-6 py-4 font-medium text-green-600 whitespace-nowrap">
                                    +Rp 15.000.000
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs
                                        font-medium bg-green-100 text-green-700
                                        dark:bg-green-900/40 dark:text-green-400">
                                        {{ __('Completed') }}
                                    </span>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-red-100
                                            dark:bg-red-900/40 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600"
                                                fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-gray-100">
                                                {{ __('Office Rent') }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                EXP-2026-018
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                    24 Oct 2026
                                </td>
                                <td class="px-6 py-4 font-medium text-red-500 whitespace-nowrap">
                                    -Rp 8.000.000
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs
                                        font-medium bg-green-100 text-green-700
                                        dark:bg-green-900/40 dark:text-green-400">
                                        {{ __('Completed') }}
                                    </span>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100
                                            dark:bg-blue-900/40 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600"
                                                fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-gray-100">
                                                {{ __('Project Revenue') }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                INV-2026-002
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                    23 Oct 2026
                                </td>
                                <td class="px-6 py-4 font-medium text-green-600 whitespace-nowrap">
                                    +Rp 12.500.000
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs
                                        font-medium bg-yellow-100 text-yellow-700
                                        dark:bg-yellow-900/40 dark:text-yellow-400">
                                        {{ __('Pending') }}
                                    </span>
                                </td>
                            </tr>

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-red-100
                                            dark:bg-red-900/40 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600"
                                                fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800 dark:text-gray-100">
                                                {{ __('Employee Salaries') }}
                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                EXP-2026-017
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                    22 Oct 2026
                                </td>
                                <td class="px-6 py-4 font-medium text-red-500 whitespace-nowrap">
                                    -Rp 25.000.000
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-xs
                                        font-medium bg-green-100 text-green-700
                                        dark:bg-green-900/40 dark:text-green-400">
                                        {{ __('Completed') }}
                                    </span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Financial Summary --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6
                border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                    {{ __('Financial Summary') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 mb-6">
                    {{ __('Current financial position') }}
                </p>

                <div class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Income Target') }}
                            </span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                                75%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 75%"></div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Rp 125.500.000 / Rp 167.000.000
                        </p>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Expense Budget') }}
                            </span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                                62%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 62%"></div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            Rp 78.250.000 / Rp 125.000.000
                        </p>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-5">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Profit Margin') }}
                        </p>
                        <div class="flex items-end justify-between mt-2">
                            <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">
                                {{ $profitMargin }}%
                            </p>
                            <span class="text-sm text-green-600 dark:text-green-400">
                                +3.2%
                            </span>
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>

    {{-- Chart.js --}}
    @once
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endonce

    <!-- hidden variable for js -->
    <input type="hidden" id="monthLabels" value="{{ json_encode($monthLabels) }}">
    <input type="hidden" id="chartIncome" value="{{ json_encode($chartIncome) }}">
    <input type="hidden" id="chartExpenses" value="{{ json_encode($chartExpenses) }}">

    <input type="hidden" id="expenseCategories" value="{{ json_encode($expenseCategories) }}">


    <script>
        const financeLabels = JSON.parse(document.getElementById('monthLabels').value);
        console.log(financeLabels);
        const financeIncome = JSON.parse(document.getElementById('chartIncome').value);
        console.log(financeIncome);
        const financeExpenses = JSON.parse(document.getElementById('chartExpenses').value);
        console.log(financeExpenses);
    </script>

    <script>
        const expenseCategories = JSON.parse(document.getElementById('expenseCategories').value);
        console.log(expenseCategories);

        const expenseCategoryLabels = expenseCategories.map(c => c.name);

        const expenseCategoryValues = expenseCategories.map(c => c.percentage);

        const expenseCategoryColors = expenseCategories.map(c => c.color);
    </script>

    <script>
        (() => {
            function initFinanceDashboard() {
                if (typeof Chart === 'undefined') return;

                const incomeCanvas = document.getElementById('incomeExpenseChart');
                const expenseCanvas = document.getElementById('expenseCategoryChart');

                if (!incomeCanvas || !expenseCanvas) return;

                // Avoid duplicate charts if the view is initialized again.
                if (incomeCanvas.chartInstance) incomeCanvas.chartInstance.destroy();
                if (expenseCanvas.chartInstance) expenseCanvas.chartInstance.destroy();

                const isDark = document.documentElement.classList.contains('dark');
                const textColor = isDark ? '#9CA3AF' : '#6B7280';
                const gridColor = isDark ?
                    'rgba(255,255,255,0.08)' :
                    'rgba(0,0,0,0.06)';

                const money = value =>
                    'Rp ' + new Intl.NumberFormat('id-ID').format(value);

                const monthlyData = {
                    labels: [
                        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                    ],
                    income: [
                        75, 82, 90, 78, 105, 98,
                        115, 108, 120, 125.5, 0, 0
                    ],
                    expenses: [
                        45, 52, 58, 49, 65, 61,
                        72, 68, 75, 78.25, 0, 0
                    ]
                };

                const quarterlyData = {
                    labels: ['Agu', 'Sep', 'Okt'],
                    income: [108, 120, 125.5],
                    expenses: [68, 75, 78.25]
                };

                function createIncomeExpenseChart() {
                    incomeCanvas.chartInstance = new Chart(incomeCanvas, {
                        type: 'bar',
                        data: {
                            labels: financeLabels,
                            datasets: [{
                                    data: financeIncome,
                                    backgroundColor: '#3B82F6',
                                    borderWidth: 0,
                                    hoverOffset: 6
                                },
                                {
                                    data: financeExpenses,
                                    backgroundColor: '#F87171',
                                    borderWidth: 0,
                                    hoverOffset: 6
                                },

                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: context =>
                                            context.dataset.label + ': ' +
                                            money(context.parsed.y)
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: textColor
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: gridColor
                                    },
                                    ticks: {
                                        color: textColor,
                                        callback: value => value / 1000000 + ' jt'
                                    }
                                }
                            }
                        }
                    });
                }

                createIncomeExpenseChart();

                expenseCanvas.chartInstance = new Chart(expenseCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: ['Operations', 'Salaries', 'Marketing', 'Other'],
                        datasets: [{
                            data: [35, 30, 20, 15],
                            backgroundColor: [
                                '#3B82F6',
                                '#22C55E',
                                '#EAB308',
                                '#A855F7'
                            ],
                            borderWidth: 0,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '72%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: context =>
                                        context.label + ': ' + context.parsed + '%'
                                }
                            }
                        }
                    }
                });

                const periodSelect = document.getElementById('financePeriod');

                if (periodSelect) {
                    periodSelect.onchange = () => {
                        const selectedData = periodSelect.value === 'quarterly' ?
                            quarterlyData :
                            monthlyData;

                        if (incomeCanvas.chartInstance) {
                            incomeCanvas.chartInstance.destroy();
                        }

                        createIncomeExpenseChart(selectedData);
                    };
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener(
                    'DOMContentLoaded',
                    initFinanceDashboard, {
                        once: true
                    }
                );
            } else {
                initFinanceDashboard();
            }
        })();
    </script>
</x-layouts.app>