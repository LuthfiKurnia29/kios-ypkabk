<?php

namespace App\Http\Controllers;

use App\Http\Services\DonationService;
use App\Models\Donation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonationController extends Controller
{
    public function __construct(
        protected DonationService $donationService,
    ) {}

    /**
     * Display a listing of donations (admin).
     */
    public function index(Request $request): JsonResponse|View
    {
        $donations = $this->donationService->getDonations(
            $request->all(),
            $request->integer('per_page', 15)
        );

        if ($request->wantsJson()) {
            return response()->json($donations);
        }

        return view('admin.pages.donations', compact('donations'));
    }

    /**
     * Store a newly created donation form submission.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'donor_name' => ['required', 'string', 'max:255'],
            'donor_phone' => ['required', 'string', 'max:50'],
            'donor_city' => ['required', 'string', 'max:100'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'category_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $donation = $this->donationService->createDonation($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Terima kasih banyak! Tim Kios Mandiri YPKABK akan segera menghubungi nomor WhatsApp Anda untuk koordinasi donasi.',
                'data' => $donation,
            ], 201);
        }

        return redirect()->back()->with('success', 'Formulir donasi berhasil dikirim. Tim kami akan segera menghubungi Anda.');
    }

    /**
     * Update the donation status (admin).
     */
    public function updateStatus(Request $request, Donation|int $donation): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,picked_up,received,rejected'],
        ]);

        $updated = $this->donationService->updateDonationStatus($donation, $validated['status']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status donasi berhasil diperbarui.',
                'data' => $updated,
            ]);
        }

        return redirect()->back()->with('success', 'Status donasi berhasil diperbarui.');
    }
}
