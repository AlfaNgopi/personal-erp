<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\FinanceTransaction;
use App\Models\Finance\FinanceMerchant;
use App\Models\Finance\FinanceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceDashboardController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Workspace
        |--------------------------------------------------------------------------
        | Sesuaikan dengan mekanisme workspace di aplikasi kamu.
        */
        $workspaceId = $request->user()->work_space_id;

        /*
        |--------------------------------------------------------------------------
        | Period
        |--------------------------------------------------------------------------
        */
        $period = $request->input('period', 'yearly');

        $now = Carbon::now();

        if ($period === 'quarterly') {
            $startDate = $now->copy()->subMonths(2)->startOfMonth();
        } else {
            $period = 'yearly';
            $startDate = $now->copy()->startOfYear();
        }

        $endDate = $now->copy()->endOfDay();

        /*
        |--------------------------------------------------------------------------
        | Transaction Base Query
        |--------------------------------------------------------------------------
        */
        $transactions = FinanceTransaction::query();

        // dd($transactions->get());

        /*
        |--------------------------------------------------------------------------
        | Summary: Income & Expenses
        |--------------------------------------------------------------------------
        | Ubah nilai type sesuai isi finance_categories.type di database.
        */
        $incomeType = 'income';
        $expenseType = 'expense';

        $incomeQuery = (clone $transactions)
            ->where('amount', '>', 0);

        $expenseQuery = (clone $transactions)
            ->where('amount', '<', 0);

        $totalIncome = (clone $incomeQuery)
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $totalExpenses = (clone $expenseQuery)
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('amount');

        $totalExpenses = abs($totalExpenses);

        $netProfit = $totalIncome - $totalExpenses;


        /*
        |--------------------------------------------------------------------------
        | Previous Period Comparison
        |--------------------------------------------------------------------------
        */
        $periodDays = $startDate->diffInDays($endDate) + 1;

        $previousEndDate = $startDate->copy()->subSecond();
        $previousStartDate = $previousEndDate->copy()
            ->subDays($periodDays - 1)
            ->startOfDay();

        $previousIncome = (clone $incomeQuery)
            ->whereBetween('transaction_date', [
                $previousStartDate,
                $previousEndDate,
            ])
            ->sum('amount');

        $previousExpenses = (clone $expenseQuery)
            ->whereBetween('transaction_date', [
                $previousStartDate,
                $previousEndDate,
            ])
            ->sum('amount');

        $previousExpenses = abs($previousExpenses);

        $previousProfit = $previousIncome - $previousExpenses;

        $incomeGrowth = $this->percentageChange(
            $previousIncome,
            $totalIncome
        );

        $expenseGrowth = $this->percentageChange(
            $previousExpenses,
            $totalExpenses
        );

        $profitGrowth = $this->percentageChange(
            $previousProfit,
            $netProfit
        );

        /*
        |--------------------------------------------------------------------------
        | Cash Balance
        |--------------------------------------------------------------------------
        | Net transaksi sepanjang waktu, bukan saldo rekening aktual.
        | Jika ada saldo awal, tambahkan ke perhitungan ini.
        */
        $allTimeIncome = (clone $incomeQuery)->sum('amount');
        $allTimeExpenses = (clone $expenseQuery)->sum('amount');

        $allTimeExpenses = abs($allTimeExpenses);

        $cashBalance = $allTimeIncome - $allTimeExpenses;

        /*
        |--------------------------------------------------------------------------
        | Monthly Income & Expenses Chart
        |--------------------------------------------------------------------------
        */
        $monthlyIncome = (clone $incomeQuery)
            ->whereBetween('transaction_date', [
                $now->copy()->startOfYear(),
                $now->copy()->endOfYear(),
            ])
            ->selectRaw('MONTH(transaction_date) as month, SUM(amount) as total')
            ->groupByRaw('MONTH(transaction_date)')
            ->pluck('total', 'month');

        $monthlyExpenses = (clone $expenseQuery)
            ->whereBetween('transaction_date', [
                $now->copy()->startOfYear(),
                $now->copy()->endOfYear(),
            ])
            ->selectRaw('MONTH(transaction_date) as month, SUM(amount) as total')
            ->groupByRaw('MONTH(transaction_date)')
            ->pluck('total', 'month');


        $monthLabels = [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
        ];

        $chartIncome = [];
        $chartExpenses = [];

        for ($month = 1; $month <= 12; $month++) {
            $chartIncome[] = (float) ($monthlyIncome[$month] ?? 0);
            $chartExpenses[] = (float) (abs($monthlyExpenses[$month] ?? 0) );
        }

        /*
        |--------------------------------------------------------------------------
        | Expense Breakdown by Category
        |--------------------------------------------------------------------------
        */
        $expenseCategories = (clone $expenseQuery)
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->join(
                'finance_categories',
                'finance_transactions.finance_category_id',
                '=',
                'finance_categories.id'
            )
            ->whereNull('finance_categories.deleted_at')
            ->select(
                'finance_categories.id',
                'finance_categories.name',
                'finance_categories.color',
                DB::raw('SUM(finance_transactions.amount) as total')
            )
            ->groupBy(
                'finance_categories.id',
                'finance_categories.name',
                'finance_categories.color'
            )
            ->orderBy('total', 'asc')
            ->get();

        $otherExpenseCategories = FinanceTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->whereNull('finance_category_id')
            ->sum('amount');

        $expenseCategories->push((object)[
            'id' => 0,
            'name' => 'Other',
            'color' => '#ccc',
            'total' => $otherExpenseCategories
        ]);

        $expenseTotalForChart = abs($expenseCategories->sum('total'));
        $expenseTotalForChart += abs($otherExpenseCategories);

        $expenseCategories = $expenseCategories->map(function ($category) use ($expenseTotalForChart) {
            $category->percentage = $expenseTotalForChart > 0
                ? round((abs($category->total) / $expenseTotalForChart) * 100, 2)
                : 0;

            return $category;
        });

        // dd($expenseCategories);

        /*
        |--------------------------------------------------------------------------
        | Recent Transactions
        |--------------------------------------------------------------------------
        */
        $recentTransactions = FinanceTransaction::query()
            ->leftJoin(
                'finance_merchants',
                'finance_transactions.finance_merchant_id',
                '=',
                'finance_merchants.id'
            )
            ->leftJoin(
                'finance_categories',
                'finance_transactions.finance_category_id',
                '=',
                'finance_categories.id'
            )
            ->where(function ($query) {
                $query->whereNull('finance_merchants.id')
                    ->orWhereNull('finance_merchants.deleted_at');
            })
            ->where(function ($query) {
                $query->whereNull('finance_categories.id')
                    ->orWhereNull('finance_categories.deleted_at');
            })
            ->select([
                'finance_transactions.id',
                'finance_transactions.transactionid',
                'finance_transactions.amount',
                'finance_transactions.transaction_date',
                'finance_transactions.description',
                'finance_transactions.finance_merchant_id',
                'finance_transactions.finance_category_id',
                'finance_merchants.name as merchant_name',
                'finance_categories.name as category_name',
                'finance_categories.type as category_type',
            ])
            ->orderByDesc('finance_transactions.transaction_date')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Merchant & Category Counts
        |--------------------------------------------------------------------------
        */
        $totalMerchants = FinanceMerchant::query()
            ->count();

        $totalCategories = FinanceCategory::query()
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Financial Summary
        |--------------------------------------------------------------------------
        */
        $incomeTarget = 167000000;
        $expenseBudget = 125000000;

        $incomeTargetProgress = $incomeTarget > 0
            ? min(100, round(($totalIncome / $incomeTarget) * 100))
            : 0;

        $expenseBudgetProgress = $expenseBudget > 0
            ? round(($totalExpenses / $expenseBudget) * 100)
            : 0;

        $profitMargin = $totalIncome > 0
            ? round(($netProfit / $totalIncome) * 100, 2)
            : 0;


        // dd($expenseCategories);

        return view('financetransaction.dashboard', compact(
            'period',
            'startDate',
            'endDate',
            'totalIncome',
            'totalExpenses',
            'netProfit',
            'cashBalance',
            'previousIncome',
            'previousExpenses',
            'incomeGrowth',
            'expenseGrowth',
            'profitGrowth',
            'monthLabels',
            'chartIncome',
            'chartExpenses',
            'expenseCategories',
            'expenseTotalForChart',
            'recentTransactions',
            'totalMerchants',
            'totalCategories',
            'incomeTarget',
            'expenseBudget',
            'incomeTargetProgress',
            'expenseBudgetProgress',
            'profitMargin'
        ));
    }

    /**
     * Persentase perubahan dari periode sebelumnya.
     */
    private function percentageChange($previous, $current): float
    {
        if ((float) $previous === 0.0) {
            return (float) $current > 0 ? 100.0 : 0.0;
        }

        return round(
            (($current - $previous) / abs($previous)) * 100,
            2
        );
    }
}
