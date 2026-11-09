<?php

namespace App\Http\Controllers;

use App\Http\Services\ProductService;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService,
    ) {}

    /**
     * Display a listing of products (admin panel).
     */
    public function adminIndex(Request $request): View|JsonResponse
    {
        $products = $this->productService->getProducts(
            $request->all(),
            $request->integer('per_page', 20)
        );

        $categories = Category::where('type', 'product')->orderBy('name')->get();

        return view('admin.pages.products', compact('products', 'categories'));
    }

    /**
     * Display a listing of products.
     */
    public function index(Request $request): View|JsonResponse
    {
        $products = $this->productService->getProducts(
            $request->all(),
            $request->integer('per_page', 12)
        );

        if ($request->wantsJson()) {
            return response()->json($products);
        }

        return view('user.pages.katalog', compact('products'));
    }

    /**
     * Display the specified product.
     */
    public function show(Request $request, Product|int|string $product): View|JsonResponse
    {
        $productData = $this->productService->getDataProduct($product);

        if ($request->wantsJson()) {
            return response()->json($productData);
        }

        return view('user.pages.katalog', ['product' => $productData]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable'],
            'source' => ['nullable', 'in:karya,preloved'],
            'condition' => ['nullable', 'in:baru,mulus,layak'],
            'artisan_name' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => ['file', 'image', 'max:5120'],
            ]);
            $file = $request->file('image');
            $mime = $file->getClientMimeType() ?: $file->getMimeType() ?: 'image/jpeg';
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $validated['image'] = "data:{$mime};base64,{$base64}";
        } elseif (! is_string($validated['image'] ?? null)) {
            $validated['image'] = null;
        }

        $product = $this->productService->createDataProduct($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Produk berhasil ditambahkan.',
                'data' => $product,
            ], 201);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product|int|string $product): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable'],
            'source' => ['nullable', 'in:karya,preloved'],
            'condition' => ['nullable', 'in:baru,mulus,layak'],
            'artisan_name' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['nullable', 'in:ready,minimal_stock,empty'],
        ]);

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => ['file', 'image', 'max:5120'],
            ]);
            $file = $request->file('image');
            $mime = $file->getClientMimeType() ?: $file->getMimeType() ?: 'image/jpeg';
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $validated['image'] = "data:{$mime};base64,{$base64}";
        } elseif (! is_string($validated['image'] ?? null) || empty($validated['image'])) {
            unset($validated['image']);
        }

        $updated = $this->productService->updateDataProduct($product, $validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Produk berhasil diperbarui.',
                'data' => $updated,
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Request $request, Product|int|string $product): JsonResponse|RedirectResponse
    {
        $this->productService->deleteDataProduct($product);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Produk berhasil dihapus.',
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}
