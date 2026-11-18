<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Purchase;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Display a listing of active reviews (public JSON API).
     */
    public function index(): JsonResponse
    {
        $reviews = Review::active()->take(6)->get();

        return response()->json($reviews);
    }

    /**
     * Display the public review form verified via WhatsApp token.
     */
    public function create(Request $request, string $token): View
    {
        $resolved = $this->resolveTransactionByToken($token);

        if ($resolved['status'] === 'invalid') {
            return view('user.pages.tulis-ulasan', [
                'state' => 'invalid',
                'token' => $token,
            ]);
        }

        if ($resolved['status'] === 'already_reviewed') {
            return view('user.pages.tulis-ulasan', [
                'state' => 'already_reviewed',
                'token' => $token,
            ]);
        }

        $target = $resolved['target'];
        $source = $resolved['source'];
        $defaultName = $source === 'buyer' ? $target->customer_name : $target->donor_name;

        return view('user.pages.tulis-ulasan', [
            'state' => 'form',
            'token' => $token,
            'target' => $target,
            'source' => $source,
            'defaultName' => $defaultName,
        ]);
    }

    /**
     * Store a newly created review using WhatsApp token verification.
     */
    public function store(Request $request, string $token): View|RedirectResponse
    {
        $resolved = $this->resolveTransactionByToken($token);

        if ($resolved['status'] === 'invalid') {
            return view('user.pages.tulis-ulasan', [
                'state' => 'invalid',
                'token' => $token,
            ]);
        }

        if ($resolved['status'] === 'already_reviewed') {
            return view('user.pages.tulis-ulasan', [
                'state' => 'already_reviewed',
                'token' => $token,
            ]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'quote' => ['required', 'string', 'max:300'],
            'agree_terms' => ['accepted'],
        ], [
            'name.required' => 'Nama tampilan wajib diisi.',
            'name.max' => 'Nama tampilan maksimal 100 karakter.',
            'quote.required' => 'Pengalaman atau ulasan wajib diisi.',
            'quote.max' => 'Pengalaman atau ulasan maksimal 300 karakter.',
            'agree_terms.accepted' => 'Anda harus menyetujui ulasan untuk ditampilkan.',
        ]);

        $target = $resolved['target'];
        $source = $resolved['source'];

        Review::create([
            'name' => trim($validated['name']),
            'role' => $source === 'buyer' ? 'Pembeli Karya ABK' : 'Donatur Peduli',
            'quote' => trim($validated['quote']),
            'purchase_id' => $source === 'buyer' ? $target->id : null,
            'donation_id' => $source === 'donor' ? $target->id : null,
            'source' => $source,
            'is_active' => false,
            'order' => (Review::max('order') ?? 0) + 1,
        ]);

        return view('user.pages.tulis-ulasan', [
            'state' => 'submitted',
            'token' => $token,
        ]);
    }

    /**
     * Resolve transaction by review_token.
     *
     * @return array{
     *     status: 'valid'|'invalid'|'already_reviewed',
     *     target: Purchase|Donation|null,
     *     source: 'buyer'|'donor'|null,
     * }
     */
    private function resolveTransactionByToken(string $token): array
    {
        $trimmed = trim($token);

        if ($trimmed === '') {
            return ['status' => 'invalid', 'target' => null, 'source' => null];
        }

        // 1. Cek Purchase
        $purchase = Purchase::where('review_token', $trimmed)->first();
        if ($purchase) {
            if (! in_array($purchase->status, ['paid', 'completed'], true)) {
                return ['status' => 'invalid', 'target' => null, 'source' => null];
            }

            if (Review::where('purchase_id', $purchase->id)->exists()) {
                return ['status' => 'already_reviewed', 'target' => $purchase, 'source' => 'buyer'];
            }

            return ['status' => 'valid', 'target' => $purchase, 'source' => 'buyer'];
        }

        // 2. Cek Donation
        $donation = Donation::where('review_token', $trimmed)->first();
        if ($donation) {
            if ($donation->status !== 'received') {
                return ['status' => 'invalid', 'target' => null, 'source' => null];
            }

            if (Review::where('donation_id', $donation->id)->exists()) {
                return ['status' => 'already_reviewed', 'target' => $donation, 'source' => 'donor'];
            }

            return ['status' => 'valid', 'target' => $donation, 'source' => 'donor'];
        }

        return ['status' => 'invalid', 'target' => null, 'source' => null];
    }
}
