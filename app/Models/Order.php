<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    public const SHIPPING_FEE = 40;

    public const STATUSES = [
        'pending' => 'รอชำระเงิน',
        'paid' => 'ชำระเงินแล้ว',
        'shipping' => 'กำลังจัดส่ง',
        'delivered' => 'จัดส่งสำเร็จ',
    ];

    public const PAYMENT_METHODS = [
        'transfer' => 'โอนเงิน / Mobile Banking',
        'cod' => 'เก็บเงินปลายทาง',
    ];

    protected $fillable = [
        'user_id', 'recipient_name', 'recipient_phone', 'shipping_address', 'province', 'postal_code',
        'payment_method', 'subtotal', 'shipping_fee', 'total', 'status',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Order M:N Product through order_items
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot('quantity', 'unit_price');
    }

    // #PP-1024
    public function number(): string
    {
        return '#PP-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
