<?php

namespace App\Http\Services;

use App\Models\Expense;
use App\Models\Purchase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FinancialReportService extends BaseService
{
    public const CACHE_KEY_PREFIX = 'financial_report_';

    public const CACHE_TTL = 60;

    /**
     * Indonesian month names for labels.
     *
     * @var array<int, string>
     */
    private const MONTH_LABELS = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
        7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
    ];

    /**
     * Indonesian full month names.
     *
     * @var array<int, string>
     */
    private const MONTH_FULL = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    /**
     * Get all periods that have data plus current month.
     *
     * @return array<int, array{key: string, label: string}>
     */
    public function getAvailablePeriods(): array
    {
        $now = Carbon::now();
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $purchaseYr = "cast(strftime('%Y', created_at) as integer)";
            $purchaseMo = "cast(strftime('%m', created_at) as integer)";
            $expenseYr = "cast(strftime('%Y', spent_at) as integer)";
            $expenseMo = "cast(strftime('%m', spent_at) as integer)";
        } elseif ($driver === 'pgsql') {
            $purchaseYr = 'CAST(EXTRACT(YEAR FROM created_at) AS INTEGER)';
            $purchaseMo = 'CAST(EXTRACT(MONTH FROM created_at) AS INTEGER)';
            $expenseYr = 'CAST(EXTRACT(YEAR FROM spent_at) AS INTEGER)';
            $expenseMo = 'CAST(EXTRACT(MONTH FROM spent_at) AS INTEGER)';
        } else {
            $purchaseYr = 'YEAR(created_at)';
            $purchaseMo = 'MONTH(created_at)';
            $expenseYr = 'YEAR(spent_at)';
            $expenseMo = 'MONTH(spent_at)';
        }

        // Months with purchase data
        $purchaseMonths = Purchase::whereIn('status', ['paid', 'completed'])
            ->selectRaw("{$purchaseYr} as yr, {$purchaseMo} as mo")
            ->distinct()
            ->orderByDesc('yr')
            ->orderByDesc('mo')
            ->get()
            ->toBase()
            ->map(fn ($r) => ['year' => (int) $r->yr, 'month' => (int) $r->mo]);

        // Months with expense data
        if (Schema::hasTable('expenses')) {
            $expenseMonths = Expense::selectRaw("{$expenseYr} as yr, {$expenseMo} as mo")
                ->distinct()
                ->orderByDesc('yr')
                ->orderByDesc('mo')
                ->get()
                ->toBase()
                ->map(fn ($r) => ['year' => (int) $r->yr, 'month' => (int) $r->mo]);
        } else {
            $expenseMonths = collect();
        }

        // Merge and deduplicate
        $allMonths = $purchaseMonths->merge($expenseMonths)
            ->map(fn ($m) => $m['year'] * 100 + $m['month'])
            ->unique()
            ->sort()
            ->reverse()
            ->values();

        // Always include current month
        $currentKey = $now->year * 100 + $now->month;
        if (! $allMonths->contains($currentKey)) {
            $allMonths = $allMonths->prepend($currentKey);
        }

        return $allMonths->map(function (int $ym) {
            $year = (int) floor($ym / 100);
            $month = $ym % 100;

            return [
                'key' => sprintf('%04d-%02d', $year, $month),
                'label' => self::MONTH_FULL[$month].' '.$year,
            ];
        })->values()->all();
    }

    /**
     * Get income for a given year/month.
     */
    public function getIncome(int $year, int $month): int
    {
        return (int) Purchase::whereIn('status', ['paid', 'completed'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->sum('total_price');
    }

    /**
     * Get expense sum for a given year/month.
     */
    public function getExpense(int $year, int $month): int
    {
        if (! Schema::hasTable('expenses')) {
            return 0;
        }

        return (int) Expense::whereYear('spent_at', $year)
            ->whereMonth('spent_at', $month)
            ->sum('amount');
    }

    /**
     * Get 6 months of bar chart data ending at the selected month.
     *
     * @return array<int, array{month: string, income: int, expense: int}>
     */
    public function getChartData(int $year, int $month): array
    {
        $result = [];
        $date = Carbon::create($year, $month, 1);

        for ($i = 5; $i >= 0; $i--) {
            $d = $date->copy()->subMonths($i);
            $result[] = [
                'month' => self::MONTH_LABELS[$d->month],
                'income' => $this->getIncome($d->year, $d->month),
                'expense' => $this->getExpense($d->year, $d->month),
            ];
        }

        return $result;
    }

    /**
     * Full financial report data for selected period key (YYYY-MM).
     *
     * @return array{income: int, expense: int, balance: int, monthly: array<int, array{month: string, income: int, expense: int}>, periods: array<int, array{key: string, label: string}>, selected_period: string, all_zero: bool}
     */
    public function getReportData(?string $selectedPeriod = null): array
    {
        return Cache::remember(
            self::CACHE_KEY_PREFIX.($selectedPeriod ?? 'current'),
            self::CACHE_TTL,
            function () use ($selectedPeriod) {
                $periods = $this->getAvailablePeriods();

                // Default: first in list (most recent or current month)
                if (empty($periods)) {
                    $now = Carbon::now();
                    $periods = [[
                        'key' => sprintf('%04d-%02d', $now->year, $now->month),
                        'label' => self::MONTH_FULL[$now->month].' '.$now->year,
                    ]];
                }

                // Find selected or default to first
                $activePeriod = collect($periods)->firstWhere('key', $selectedPeriod) ?? $periods[0];
                [$year, $month] = array_map('intval', explode('-', $activePeriod['key']));

                $income = $this->getIncome($year, $month);
                $expense = $this->getExpense($year, $month);
                $balance = $income - $expense;
                $monthly = $this->getChartData($year, $month);
                $allZero = collect($monthly)->every(fn ($m) => $m['income'] === 0 && $m['expense'] === 0);

                return [
                    'income' => $income,
                    'expense' => $expense,
                    'balance' => $balance,
                    'monthly' => $monthly,
                    'periods' => $periods,
                    'selected_period' => $activePeriod['key'],
                    'all_zero' => $allZero,
                ];
            }
        );
    }

    /**
     * Get the latest data date (most recent purchase or expense).
     */
    public function getLatestDataDate(): ?string
    {
        $latestPurchase = Purchase::whereIn('status', ['paid', 'completed'])->max('updated_at');
        $latestExpense = Schema::hasTable('expenses') ? Expense::max('updated_at') : null;

        $latest = match (true) {
            $latestPurchase && $latestExpense => max($latestPurchase, $latestExpense),
            (bool) $latestPurchase => $latestPurchase,
            (bool) $latestExpense => $latestExpense,
            default => null,
        };

        if (! $latest) {
            return null;
        }

        $carbon = Carbon::parse($latest);
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $carbon->day.' '.$months[$carbon->month].' '.$carbon->year;
    }

    /**
     * Clear all financial report caches.
     */
    public static function clearCache(): void
    {
        // Clear tagged or prefix-based cache entries
        // Using array cache in tests, so we clear known keys
        $periods = (new self)->getAvailablePeriods();
        Cache::forget(self::CACHE_KEY_PREFIX.'current');
        foreach ($periods as $p) {
            Cache::forget(self::CACHE_KEY_PREFIX.$p['key']);
        }
    }
}
