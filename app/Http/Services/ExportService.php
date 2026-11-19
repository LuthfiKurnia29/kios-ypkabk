<?php

namespace App\Http\Services;

use App\Models\Donation;
use App\Models\Expense;
use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ExportService extends BaseService
{
    private const MONTH_NAMES = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    /**
     * Resolve period into start date, end date, and human-readable Indonesian label.
     *
     * @return array{start: Carbon, end: Carbon, label: string, slug: string}
     */
    public function resolveDateRange(string $periodType, string $period): array
    {
        if ($periodType === 'weekly') {
            // e.g. 2026-W46
            if (preg_match('/^(\d{4})-W(\d{1,2})$/', $period, $matches)) {
                $year = (int) $matches[1];
                $week = (int) $matches[2];
                $start = Carbon::now()->setISODate($year, $week)->startOfWeek(Carbon::MONDAY)->startOfDay();
                $end = $start->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();
            } else {
                $start = Carbon::now()->startOfWeek(Carbon::MONDAY)->startOfDay();
                $end = Carbon::now()->endOfWeek(Carbon::SUNDAY)->endOfDay();
            }

            if ($start->year === $end->year && $start->month === $end->month) {
                $monthName = self::MONTH_NAMES[$start->month];
                $label = "{$start->day} sampai {$end->day} {$monthName} {$start->year}";
            } elseif ($start->year === $end->year) {
                $m1 = self::MONTH_NAMES[$start->month];
                $m2 = self::MONTH_NAMES[$end->month];
                $label = "{$start->day} {$m1} sampai {$end->day} {$m2} {$start->year}";
            } else {
                $m1 = self::MONTH_NAMES[$start->month];
                $m2 = self::MONTH_NAMES[$end->month];
                $label = "{$start->day} {$m1} {$start->year} sampai {$end->day} {$m2} {$end->year}";
            }
        } else {
            // Monthly e.g. 2026-11
            if (preg_match('/^(\d{4})-(\d{1,2})$/', $period, $matches)) {
                $year = (int) $matches[1];
                $month = (int) $matches[2];
                $start = Carbon::create($year, $month, 1)->startOfDay();
                $end = $start->copy()->endOfMonth()->endOfDay();
            } else {
                $start = Carbon::now()->startOfMonth()->startOfDay();
                $end = Carbon::now()->endOfMonth()->endOfDay();
            }

            $monthName = self::MONTH_NAMES[$start->month];
            $label = "{$start->day} sampai {$end->day} {$monthName} {$start->year}";
        }

        $slug = Str::slug($label);

        return [
            'start' => $start,
            'end' => $end,
            'label' => $label,
            'slug' => $slug,
        ];
    }

    /**
     * Get Purchases data for export.
     */
    public function getPurchasesData(Carbon $start, Carbon $end): array
    {
        $purchases = Purchase::whereBetween('created_at', [$start, $end])
            ->orderByDesc('created_at')
            ->get();

        $totalRupiah = (int) $purchases->sum('total_price');

        return [
            'purchases' => $purchases,
            'total' => $totalRupiah,
        ];
    }

    /**
     * Get Donations data for export.
     */
    public function getDonationsData(Carbon $start, Carbon $end): array
    {
        $donations = Donation::with('category')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('received_at', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->whereNull('received_at')
                            ->whereBetween('created_at', [$start, $end]);
                    });
            })
            ->orderByDesc('created_at')
            ->get();

        $totalItems = (int) $donations->sum('item_count');

        return [
            'donations' => $donations,
            'total_items' => $totalItems,
        ];
    }

    /**
     * Get Reports (Income, Expense, Balance) data for export.
     */
    public function getReportsData(Carbon $start, Carbon $end): array
    {
        // Pemasukan dari pesanan paid / completed
        $purchases = Purchase::whereIn('status', ['paid', 'completed'])
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('created_at')
            ->get();

        $totalIncome = (int) $purchases->sum('total_price');

        // Pengeluaran
        $expenses = Expense::whereBetween('spent_at', [$start->toDateString(), $end->toDateString()])
            ->orderByDesc('spent_at')
            ->orderByDesc('id')
            ->get();

        $totalExpense = (int) $expenses->sum('amount');
        $balance = $totalIncome - $totalExpense;

        // Barang donasi diterima (terpisah, tidak dihitung di rupiah)
        $donations = Donation::with('category')
            ->where('status', 'received')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('received_at', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->whereNull('received_at')
                            ->whereBetween('created_at', [$start, $end]);
                    });
            })
            ->orderByDesc('created_at')
            ->get();

        $totalDonationItems = (int) $donations->sum('item_count');

        return [
            'income' => $totalIncome,
            'expense' => $totalExpense,
            'balance' => $balance,
            'purchases' => $purchases,
            'expenses' => $expenses,
            'donations' => $donations,
            'total_donation_items' => $totalDonationItems,
        ];
    }

    /**
     * Export view to PDF or Excel response.
     */
    public function export(string $type, string $format, array $range, array $data): SymfonyResponse
    {
        $filename = "kios-mandiri-{$type}-{$range['slug']}.{$format}";
        $viewData = array_merge($data, [
            'type' => $type,
            'format' => $format,
            'rangeLabel' => $range['label'],
            'generatedAt' => Carbon::now()->format('d F Y, H:i').' WIB',
        ]);

        if ($format === 'pdf') {
            return $this->renderPdf($type, $viewData, $filename);
        }

        return $this->renderExcel($type, $viewData, $filename);
    }

    private function renderPdf(string $type, array $viewData, string $filename): SymfonyResponse
    {
        $viewName = "admin.exports.{$type}-pdf";

        if (class_exists(Pdf::class)) {
            $pdf = Pdf::loadView($viewName, $viewData)
                ->setPaper('a4', 'portrait');

            return $pdf->download($filename);
        }

        // Fallback if barryvdh/laravel-dompdf is not yet installed in vendor
        $html = view($viewName, $viewData)->render();

        return response($html, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    private function renderExcel(string $type, array $viewData, string $filename): SymfonyResponse
    {
        $viewName = "admin.exports.{$type}-excel";

        if (class_exists(Excel::class)) {
            $export = new class($viewName, $viewData) implements FromView, ShouldAutoSize
            {
                public function __construct(protected string $view, protected array $data) {}

                public function view(): View
                {
                    return view($this->view, $this->data);
                }
            };

            return Excel::download($export, $filename);
        }

        // Fallback HTML-based table readable by Excel if maatwebsite/excel is not yet installed
        $html = view($viewName, $viewData)->render();

        return response($html, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
