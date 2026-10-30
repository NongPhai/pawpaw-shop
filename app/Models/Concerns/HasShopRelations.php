<?php

namespace App\Models\Concerns;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Shop relationships for the User model.
 * Add `use \App\Models\Concerns\HasShopRelations;` inside the User class.
 *
 * Note: `name` (from the starter kit) holds the first name, `last_name` the surname.
 */
trait HasShopRelations
{
    public function fullName(): string
    {
        return trim($this->name . ' ' . $this->last_name);
    }

    public function fullAddress(): string
    {
        return trim(implode(' ', array_filter([$this->address, $this->province, $this->postal_code])));
    }

    // User 1:N Order
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // User 1:N Review
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // User M:N Product through cart_items
    public function cartProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'cart_items')
            ->withPivot('quantity');
    }

    // User M:N Product through wishlists (รายการโปรด)
    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlists')
            ->withTimestamps();
    }
}
