<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_category_id',
        'type',
        'donor_name',
        'receipt_number',
        'item_name',
        'quantity',
        'operation_date',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'operation_date' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(InventoryCategory::class, 'inventory_category_id');
    }
}
