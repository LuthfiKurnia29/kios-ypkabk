<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnboxingRequest;
use App\Http\Requests\UpdateUnboxingRequest;
use App\Models\Unboxing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnboxingController extends Controller
{
    /**
     * Display a listing of unboxing gallery entries in the admin back-office.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Unboxing::query();

        if ($request->filled('search')) {
            $search = (string) $request->input('search');
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('platform') && in_array($request->input('platform'), ['youtube', 'instagram'], true)) {
            $query->where('platform', $request->input('platform'));
        }

        if ($request->filled('is_visible')) {
            $query->where('is_visible', (bool) $request->input('is_visible'));
        }

        $unboxings = $query->orderByDesc('recorded_at')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $totalCount = Unboxing::count();
        $youtubeCount = Unboxing::where('platform', 'youtube')->count();
        $instagramCount = Unboxing::where('platform', 'instagram')->count();
        $visibleCount = Unboxing::where('is_visible', true)->count();

        if ($request->wantsJson()) {
            return response()->json([
                'unboxings' => $unboxings,
                'stats' => [
                    'total' => $totalCount,
                    'youtube' => $youtubeCount,
                    'instagram' => $instagramCount,
                    'visible' => $visibleCount,
                ],
            ]);
        }

        return view('admin.pages.unboxing', compact(
            'unboxings',
            'totalCount',
            'youtubeCount',
            'instagramCount',
            'visibleCount'
        ));
    }

    /**
     * Store a newly created unboxing video entry in storage.
     */
    public function store(StoreUnboxingRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        if (empty($validated['platform'])) {
            $validated['platform'] = Unboxing::determinePlatform($validated['url']);
        }

        $validated['is_visible'] = $request->has('is_visible')
            ? $request->boolean('is_visible')
            : true;

        if (empty($validated['recorded_at'])) {
            $validated['recorded_at'] = now()->toDateString();
        }

        $unboxing = Unboxing::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Video dokumentasi unboxing berhasil ditambahkan.',
                'data' => $unboxing,
            ], 201);
        }

        return redirect()->route('admin.unboxing.index')
            ->with('success', "Video unboxing '{$unboxing->title}' berhasil ditambahkan ke galeri.");
    }

    /**
     * Update the specified unboxing video entry in storage.
     */
    public function update(UpdateUnboxingRequest $request, Unboxing $unboxing): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        if (empty($validated['platform'])) {
            $validated['platform'] = Unboxing::determinePlatform($validated['url']);
        }

        $validated['is_visible'] = $request->has('is_visible')
            ? $request->boolean('is_visible')
            : false;

        $unboxing->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Video dokumentasi unboxing berhasil diperbarui.',
                'data' => $unboxing,
            ]);
        }

        return redirect()->route('admin.unboxing.index')
            ->with('success', "Video unboxing '{$unboxing->title}' berhasil diperbarui.");
    }

    /**
     * Remove the specified unboxing video entry from storage.
     */
    public function destroy(Request $request, Unboxing $unboxing): JsonResponse|RedirectResponse
    {
        $title = $unboxing->title;
        $unboxing->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Video dokumentasi unboxing berhasil dihapus.',
            ]);
        }

        return redirect()->route('admin.unboxing.index')
            ->with('success', "Video unboxing '{$title}' berhasil dihapus dari galeri.");
    }

    /**
     * Toggle the visibility status of the specified unboxing video entry.
     */
    public function toggleVisibility(Request $request, Unboxing $unboxing): JsonResponse|RedirectResponse
    {
        $unboxing->update(['is_visible' => ! $unboxing->is_visible]);

        $statusText = $unboxing->is_visible ? 'ditampilkan di galeri publik' : 'disembunyikan dari galeri publik';

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Status video unboxing berhasil {$statusText}.",
                'is_visible' => $unboxing->is_visible,
            ]);
        }

        return redirect()->back()
            ->with('success', "Video '{$unboxing->title}' kini {$statusText}.");
    }
}
