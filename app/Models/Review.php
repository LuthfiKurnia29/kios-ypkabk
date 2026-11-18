<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'purchase_id' => 'integer',
            'donation_id' => 'integer',
            'is_active' => 'boolean',
            'order' => 'integer',
        ];
    }

    /**
     * Scope a query to only include active reviews ordered.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('order')->latest();
    }

    /**
     * Associated purchase for buyer reviews.
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Associated donation for donor reviews.
     */
    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

    /**
     * Get initials from the reviewer's name.
     */
    public function getInitialsAttribute(): string
    {
        $words = preg_split('/\s+/', trim($this->name));
        if (! $words || empty($words[0])) {
            return 'Y';
        }

        if (count($words) === 1) {
            return strtoupper(substr($words[0], 0, 2));
        }

        return strtoupper(substr($words[0], 0, 1).substr($words[count($words) - 1], 0, 1));
    }

    /**
     * Get short display name with first name and initial (e.g. "Rina W.").
     */
    public function getDisplayNameAttribute(): string
    {
        $clean = trim($this->name);
        $stripped = preg_replace('/^(ibu|bapak|bpk|mas|mbak|pak|dr\.|dr)\s+/i', '', $clean);
        $words = preg_split('/\s+/', trim($stripped ?: $clean));

        if (! $words || empty($words[0])) {
            return $clean;
        }

        if (count($words) === 1) {
            return ucfirst($words[0]);
        }

        return ucfirst($words[0]).' '.strtoupper(substr(end($words), 0, 1)).'.';
    }

    /**
     * Get verified badge label.
     */
    public function getVerifiedLabelAttribute(): string
    {
        if ($this->source === 'donor' || $this->donation_id || str_contains(strtolower($this->role ?? ''), 'donatur')) {
            return 'Donatur terverifikasi';
        }

        return 'Pembeli terverifikasi';
    }

    /**
     * Get readable transaction identifier.
     */
    public function getTransactionCodeAttribute(): string
    {
        if ($this->purchase) {
            return $this->purchase->order_code ?: ('Pesanan #'.$this->purchase_id);
        }

        if ($this->donation_id) {
            return 'Donasi #'.$this->donation_id;
        }

        return '—';
    }
}
