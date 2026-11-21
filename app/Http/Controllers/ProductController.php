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

        $categories = Category::get();

        return view('admin.pages.products', compact('products', 'categories'));
    }

    /**
     * Display the specified product in the admin back-office panel.
     */
    public function adminShow(Request $request, Product|int|string $product): View|JsonResponse
    {
        $productData = $this->productService->getDataProduct($product);
        $productData->loadMissing(['category', 'images', 'detailPurchases.purchase']);

        $totalSold = $productData->detailPurchases->sum('quantity');
        $totalRevenue = $productData->detailPurchases->sum(fn ($item) => $item->quantity * $item->price);

        if ($request->wantsJson()) {
            return response()->json([
                'product' => $productData,
                'total_sold' => $totalSold,
                'total_revenue' => $totalRevenue,
            ]);
        }

        return view('admin.pages.product-show', [
            'product' => $productData,
            'totalSold' => $totalSold,
            'totalRevenue' => $totalRevenue,
        ]);
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
        $productData->loadMissing(['category', 'images']);

        if ($request->wantsJson()) {
            return response()->json($productData);
        }

        $relatedProducts = Product::with('category')
            ->where('id', '!=', $productData->id)
            ->when($productData->category_id, fn ($q) => $q->where('category_id', $productData->category_id))
            ->available()
            ->limit(4)
            ->get();

        return view('user.pages.product-detail', [
            'product' => $productData,
            'relatedProducts' => $relatedProducts,
        ]);
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
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'image', 'max:5120'],
            'source' => ['nullable', 'in:karya,preloved'],
            'condition' => ['nullable', 'in:baru,mulus,layak'],
            'artisan_name' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $uploadedImages = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $mime = $file->getClientMimeType() ?: $file->getMimeType() ?: 'image/jpeg';
                    $base64 = base64_encode(file_get_contents($file->getRealPath()));
                    $uploadedImages[] = "data:{$mime};base64,{$base64}";
                }
            }
        }

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => ['file', 'image', 'max:5120'],
            ]);
            $file = $request->file('image');
            $mime = $file->getClientMimeType() ?: $file->getMimeType() ?: 'image/jpeg';
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $singleImage = "data:{$mime};base64,{$base64}";
            if (! in_array($singleImage, $uploadedImages, true)) {
                array_unshift($uploadedImages, $singleImage);
            }
        } elseif (is_string($validated['image'] ?? null) && ! empty($validated['image'])) {
            if (! in_array($validated['image'], $uploadedImages, true)) {
                array_unshift($uploadedImages, $validated['image']);
            }
        }

        if (! empty($uploadedImages)) {
            $validated['image'] = $uploadedImages[0];
        } else {
            $validated['image'] = null;
        }

        unset($validated['images']);

        $product = $this->productService->createDataProduct($validated);

        foreach ($uploadedImages as $imgData) {
            $product->images()->create([
                'image' => $imgData,
            ]);
        }

        $product->loadMissing('images');

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
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'image', 'max:5120'],
            'source' => ['nullable', 'in:karya,preloved'],
            'condition' => ['nullable', 'in:baru,mulus,layak'],
            'artisan_name' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['nullable', 'in:ready,minimal_stock,empty'],
        ]);

        $newUploadedImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $mime = $file->getClientMimeType() ?: $file->getMimeType() ?: 'image/jpeg';
                    $base64 = base64_encode(file_get_contents($file->getRealPath()));
                    $newUploadedImages[] = "data:{$mime};base64,{$base64}";
                }
            }
        }

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => ['file', 'image', 'max:5120'],
            ]);
            $file = $request->file('image');
            $mime = $file->getClientMimeType() ?: $file->getMimeType() ?: 'image/jpeg';
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $singleImage = "data:{$mime};base64,{$base64}";
            $validated['image'] = $singleImage;
            if (! in_array($singleImage, $newUploadedImages, true)) {
                $newUploadedImages[] = $singleImage;
            }
        } elseif (! is_string($validated['image'] ?? null) || empty($validated['image'])) {
            unset($validated['image']);
        }

        unset($validated['images']);

        $updated = $this->productService->updateDataProduct($product, $validated);

        foreach ($newUploadedImages as $imgData) {
            $updated->images()->create([
                'image' => $imgData,
            ]);
        }

        if (empty($updated->image) && $updated->images()->exists()) {
            $firstImg = $updated->images()->first();
            $updated->update(['image' => $firstImg->image]);
        }

        $updated->loadMissing('images');

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
