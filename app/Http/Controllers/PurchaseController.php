<?php

namespace App\Http\Controllers;

use App\Http\Services\PurchaseService;
use App\Models\Purchase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseController extends Controller
{
    public function __construct(
        protected PurchaseService $purchaseService,
    ) {}

    /**
     * Display a listing of purchases.
     */
    public function index(Request $request): JsonResponse|View
    {
        $purchases = $this->purchaseService->getPurchases(
            $request->all(),
            $request->integer('per_page', 15)
        );

        if ($request->wantsJson()) {
            return response()->json($purchases);
        }

        return view('admin.pages.purchases', compact('purchases'));
    }

    /**
     * Display the specified purchase order.
     */
    public function show(Request $request, Purchase|int|string $purchase): JsonResponse|View
    {
        $order = $this->purchaseService->getPurchase($purchase);

        if ($request->wantsJson()) {
            return response()->json($order);
        }

        return view('admin.pages.dashboard', ['purchase' => $order]);
    }

    /**
     * Store a newly created purchase order.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_address' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $customerData = [
            'user_id' => $request->user()?->id,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ];

        $purchase = $this->purchaseService->createPurchase($customerData, $validated['items']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Pesanan berhasil dibuat.',
                'data' => $purchase,
            ], 201);
        }

        return redirect()->back()->with('success', "Pesanan {$purchase->order_code} berhasil dibuat.");
    }

    /**
     * Update the status of the specified purchase order.
     */
    public function updateStatus(Request $request, Purchase|int|string $purchase): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,paid,cancelled,completed'],
            'notes' => ['nullable', 'string'],
        ]);

        $order = $this->purchaseService->updateStatus($purchase, $validated['status'], $validated['notes'] ?? null);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Status pesanan berhasil diperbarui.',
                'data' => $order,
            ]);
        }

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
