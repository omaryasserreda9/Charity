<?php

namespace App\Models;

use App\Models\Traits\HasCharityHomeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetOperation extends Model
{
    use HasFactory, HasCharityHomeScope;

    protected $fillable = [
        'budget_category_id',
        'type',
        'donor_name',
        'receipt_number',
        'quantity',
        'operation_date',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'operation_date' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BudgetCategory::class, 'budget_category_id');
    }
}
