<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of active reviews.
     */
    public function index(): JsonResponse
    {
        $reviews = Review::active()->get();

        return response()->json($reviews);
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'quote' => ['required', 'string'],
        ]);

        $review = Review::create([
            'name' => trim($validated['name']),
            'role' => $validated['role'] ?? 'Donatur & Sahabat ABK',
            'quote' => trim($validated['quote']),
            'is_active' => true,
            'order' => Review::max('order') + 1,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Ulasan berhasil dikirim.',
                'data' => $review,
            ], 201);
        }

        return redirect()->back()->with('success', 'Ulasan berhasil dikirim.');
    }
}
