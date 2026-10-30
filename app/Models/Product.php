<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    // Thai labels for pet_type, used in filters and on product cards
    public const PET_TYPES = [
        'dog' => 'สุนัข',
        'cat' => 'แมว',
        'small_pet' => 'นกและสัตว์เล็ก',
        'fish' => 'ปลา',
    ];

    // สีพื้นหลังการ์ดสินค้า โทนเขียว/ครีม วนตามลำดับสินค้า
    public const CARD_COLORS = ['#ebf2e0', '#f6efdf', '#fdeee4', '#e5f1ea'];

    // อาหารแต่ละสัตว์ใช้ emoji ต่างกัน
    public const FOOD_EMOJI = [
        'dog' => '🦴',
        'cat' => '🐟',
        'small_pet' => '🌾',
        'fish' => '🦐',
    ];

    // ไม่มีรูปสินค้า ใช้ emoji ตามหมวดแทน (แบบใน Figma)
    public const CATEGORY_EMOJI = [
        'อาหาร' => '🦴',
        'ขนม' => '🍪',
        'ของเล่น' => '🧶',
        'ทราย/ห้องน้ำ' => '🪣',
        'ที่นอน/บ้าน' => '🛏️',
        'ปลอกคอ/สายจูง' => '🦮',
        'อาบน้ำ/ดูแลขน' => '🛁',
        'อุปกรณ์เลี้ยง' => '🏠',
    ];

    public const PRODUCT_TYPES = [
        'food' => 'อาหารและขนม',
        'accessory' => 'อุปกรณ์',
    ];

    protected $fillable = [
        'name', 'short_description', 'description', 'brand', 'size',
        'price', 'original_price', 'stock', 'badge', 'image',
        'pet_type', 'product_type', 'flavor', 'material', 'category_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'original_price' => 'decimal:2',
            'stock' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Product M:N Order through order_items
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot('quantity', 'unit_price');
    }

    // users who saved this product to their favorites
    public function wishedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    /**
     * ดึงตัวเลขที่ต้องโชว์บนการ์ดมาพร้อมกันในคำสั่งเดียว
     * reviews_avg_rating = ดาวเฉลี่ย, reviews_count = จำนวนรีวิว, sold_count = ขายแล้วกี่ชิ้น
     * ใช้: Product::withStats()->get()
     */
    public function scopeWithStats(Builder $query): Builder
    {
        return $query->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->withSum('orders as sold_count', 'order_items.quantity');
    }

    public function averageRating(): float
    {
        $avg = $this->reviews_avg_rating ?? $this->reviews()->avg('rating');

        return round((float) $avg, 1);
    }

    public function hasDiscount(): bool
    {
        return $this->original_price !== null && (float) $this->original_price > (float) $this->price;
    }

    public function emoji(): string
    {
        $category = $this->category?->name;

        if ($category === 'อาหาร') {
            return self::FOOD_EMOJI[$this->pet_type] ?? '🦴';
        }

        return self::CATEGORY_EMOJI[$category] ?? '🐾';
    }

    public function cardColor(): string
    {
        return self::CARD_COLORS[($this->id - 1) % count(self::CARD_COLORS)];
    }

    public function petTypeLabel(): string
    {
        return self::PET_TYPES[$this->pet_type] ?? $this->pet_type;
    }

    public function isFood(): bool
    {
        return $this->product_type === 'food';
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }

    // "ขายแล้ว 1,250 ชิ้น" - computed from order_items, not stored
    public function soldCount(): int
    {
        if (array_key_exists('sold_count', $this->attributes)) {
            return (int) $this->sold_count;
        }

        return (int) $this->orders()->sum('order_items.quantity');
    }
}
