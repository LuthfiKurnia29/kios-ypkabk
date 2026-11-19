<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminExportRequest;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Http\Services\ExportService;
use App\Http\Services\FinancialReportService;
use App\Models\Donation;
use App\Models\Expense;
use App\Models\Purchase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ReportController extends Controller
{
    public function __construct(
        protected FinancialReportService $financialReportService,
    ) {}

    public function index(Request $request): View
    {
        $selectedPeriod = $request->query('periode');
        $financialData = $this->financialReportService->getReportData($selectedPeriod);

        $activePeriod = $financialData['selected_period'];
        [$year, $month] = array_map('intval', explode('-', $activePeriod));

        $expenses = Expense::whereYear('spent_at', $year)
            ->whereMonth('spent_at', $month)
            ->orderByDesc('spent_at')
            ->orderByDesc('id')
            ->get();

        return view('admin.pages.reports', compact('financialData', 'expenses', 'selectedPeriod'));
    }

    public function income(Request $request): View
    {
        $selectedPeriod = $request->query('periode');
        $financialData = $this->financialReportService->getReportData($selectedPeriod);

        $activePeriod = $financialData['selected_period'];
        [$year, $month] = array_map('intval', explode('-', $activePeriod));

        $purchases = Purchase::whereIn('status', ['paid', 'completed'])
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        $donations = Donation::with('category')
            ->where('status', 'received')
            ->where(function ($query) use ($year, $month) {
                $query->where(function ($q) use ($year, $month) {
                    $q->whereNotNull('received_at')
                        ->whereYear('received_at', $year)
                        ->whereMonth('received_at', $month);
                })->orWhere(function ($q) use ($year, $month) {
                    $q->whereNull('received_at')
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $month);
                });
            })
            ->orderByDesc('received_at')
            ->orderByDesc('created_at')
            ->get();

        $totalDonationItems = (int) $donations->sum('item_count');

        return view('admin.pages.reports-income', compact(
            'financialData',
            'purchases',
            'donations',
            'totalDonationItems',
            'selectedPeriod',
            'activePeriod'
        ));
    }

    public function store(StoreReportRequest $request): RedirectResponse
    {
        Expense::create($request->validated());

        FinancialReportService::clearCache();

        return redirect()->route('admin.reports.index', ['periode' => $request->input('periode_redirect')])
            ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function update(UpdateReportRequest $request, Expense $expense): RedirectResponse
    {
        $expense->update($request->validated());

        FinancialReportService::clearCache();

        return redirect()->route('admin.reports.index', ['periode' => $request->input('periode_redirect')])
            ->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        FinancialReportService::clearCache();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Pengeluaran berhasil dihapus.');
    }

    /**
     * Export financial report (income & expense) to Excel or PDF.
     */
    public function export(AdminExportRequest $request, ExportService $exportService): SymfonyResponse
    {
        $validated = $request->validated();
        $range = $exportService->resolveDateRange($validated['period_type'], $validated['period']);
        $data = $exportService->getReportsData($range['start'], $range['end']);

        return $exportService->export('reports', $validated['format'], $range, $data);
    }
}
