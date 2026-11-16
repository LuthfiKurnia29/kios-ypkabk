<?php

namespace App\Http\Services;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CategoryService extends BaseService
{
    /**
     * Get paginated categories with filters.
     *
     * @param  array<string, mixed>  $filters
     */
    public function getCategories(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = Category::withCount(['products', 'donations']);

        $search = trim((string) ($filters['search'] ?? ''));
        if ($search !== '') {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', filter_var($filters['is_active'], FILTER_VALIDATE_BOOLEAN));
        }

        return $query->orderBy('name')->paginate($perPage);
    }

    /**
     * Create a new category.
     *
     * @param  array<string, mixed>  $data
     */
    public function createCategory(array $data): Category
    {
        $data['type'] = $data['type'] ?? 'product';
        $data['is_active'] = isset($data['is_active']) ? (bool) $data['is_active'] : true;

        if (empty($data['slug'])) {
            $data['slug'] = $this->generateUniqueSlug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['slug']);
        }

        return Category::create($data);
    }

    /**
     * Update an existing category.
     *
     * @param  array<string, mixed>  $data
     */
    public function updateCategory(Category|int|string $category, array $data): Category
    {
        $model = $category instanceof Category ? $category : Category::findOrFail($category);

        if (isset($data['is_active'])) {
            $data['is_active'] = (bool) $data['is_active'];
        }

        if (empty($data['slug']) && ! empty($data['name']) && $data['name'] !== $model->name) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $model->id);
        } elseif (! empty($data['slug'])) {
            $data['slug'] = Str::slug($data['slug']);
        }

        $model->update($data);

        return $model->fresh();
    }

    /**
     * Delete an existing category.
     */
    public function deleteCategory(Category|int|string $category): bool
    {
        $model = $category instanceof Category ? $category : Category::findOrFail($category);

        return (bool) $model->delete();
    }

    /**
     * Generate a unique slug for category.
     */
    protected function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        if ($baseSlug === '') {
            $baseSlug = 'kategori';
        }

        $slug = $baseSlug;
        $counter = 1;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn (Builder $q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
