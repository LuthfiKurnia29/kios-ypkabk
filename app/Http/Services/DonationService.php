<?php

namespace App\Http\Services;

use App\Models\Category;
use App\Models\Donation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DonationService extends BaseService
{
    /**
     * Get donations with optional filters and pagination.
     *
     * @param  array{
     *     status?: ?string,
     *     category_id?: ?int,
     *     search?: ?string,
     * }  $filters
     * @return LengthAwarePaginator<Donation>|Collection<int, Donation>
     */
    public function getDonations(array $filters = [], ?int $perPage = 15): LengthAwarePaginator|Collection
    {
        $query = Donation::with('category');

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['search'])) {
            $search = trim($filters['search']);
            $query->where(function (Builder $q) use ($search) {
                $q->where('donor_name', 'like', "%{$search}%")
                    ->orWhere('donor_phone', 'like', "%{$search}%")
                    ->orWhere('donor_city', 'like', "%{$search}%")
                    ->orWhere('category_name', 'like', "%{$search}%");
            });
        }

        $query->latest();

        return $perPage !== null ? $query->paginate($perPage) : $query->get();
    }

    /**
     * Retrieve a single donation by ID or instance.
     */
    public function getDonation(Donation|int $donation): Donation
    {
        if ($donation instanceof Donation) {
            return $donation->loadMissing('category');
        }

        return Donation::with('category')->findOrFail($donation);
    }

    /**
     * Store a new donation record.
     *
     * @param  array{
     *     donor_name: string,
     *     donor_phone: string,
     *     donor_city: string,
     *     category_id?: ?int,
     *     category_name?: ?string,
     *     description?: ?string,
     *     status?: ?string,
     * }  $data
     */
    public function createDonation(array $data): Donation
    {
        $categoryName = $data['category_name'] ?? null;

        if (! empty($data['category_id']) && empty($categoryName)) {
            $category = Category::find($data['category_id']);
            $categoryName = $category?->name;
        }

        return Donation::create([
            'donor_name' => trim($data['donor_name']),
            'donor_phone' => trim($data['donor_phone']),
            'donor_city' => trim($data['donor_city']),
            'category_id' => $data['category_id'] ?? null,
            'category_name' => $categoryName,
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'received_at' => ($data['status'] ?? null) === 'received' ? now() : null,
        ]);
    }

    /**
     * Update the donation status.
     */
    public function updateDonationStatus(Donation|int $donation, string $status): Donation
    {
        $targetDonation = $this->getDonation($donation);

        $attributes = ['status' => $status];

        if ($status === 'received' && $targetDonation->received_at === null) {
            $attributes['received_at'] = now();
        }

        $targetDonation->update($attributes);

        return $targetDonation->fresh('category');
    }

    /**
     * Delete a donation record.
     */
    public function deleteDonation(Donation|int $donation): bool
    {
        $targetDonation = $this->getDonation($donation);

        return (bool) $targetDonation->delete();
    }
}
