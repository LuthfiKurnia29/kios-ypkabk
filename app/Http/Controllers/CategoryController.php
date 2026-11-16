<?php

namespace App\Http\Controllers;

use App\Http\Services\CategoryService;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
    ) {}

    /**
     * Display a listing of categories in admin panel.
     */
    public function index(Request $request): View|JsonResponse
    {
        $categories = $this->categoryService->getCategories(
            $request->all(),
            $request->integer('per_page', 20)
        );

        $totalCount = Category::count();
        $productCategoryCount = Category::where('type', 'product')->count();
        $donationCategoryCount = Category::where('type', 'donation')->count();
        $activeCount = Category::where('is_active', true)->count();

        if ($request->wantsJson()) {
            return response()->json([
                'categories' => $categories,
                'stats' => [
                    'total' => $totalCount,
                    'product' => $productCategoryCount,
                    'donation' => $donationCategoryCount,
                    'active' => $activeCount,
                ],
            ]);
        }

        return view('admin.pages.categories', compact(
            'categories',
            'totalCount',
            'productCategoryCount',
            'donationCategoryCount',
            'activeCount'
        ));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug'],
            'type' => ['nullable', 'string', 'in:product,donation'],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category = $this->categoryService->createCategory($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Kategori berhasil ditambahkan.',
                'data' => $category,
            ], 201);
        }

        return redirect()->back()->with('success', "Kategori '{$category->name}' berhasil ditambahkan.");
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category->id)],
            'type' => ['nullable', 'string', 'in:product,donation'],
            'icon' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $updated = $this->categoryService->updateCategory($category, $validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Kategori berhasil diperbarui.',
                'data' => $updated,
            ]);
        }

        return redirect()->back()->with('success', "Kategori '{$updated->name}' berhasil diperbarui.");
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Request $request, Category $category): JsonResponse|RedirectResponse
    {
        $categoryName = $category->name;
        $this->categoryService->deleteCategory($category);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Kategori berhasil dihapus.',
            ]);
        }

        return redirect()->back()->with('success', "Kategori '{$categoryName}' berhasil dihapus.");
    }
}
