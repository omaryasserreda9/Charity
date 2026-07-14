<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetOperation extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_category_id',
        'type',
        'donor_name',
        'quantity',
        'operation_date',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'operation_date' => 'date',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BudgetCategory::class, 'budget_category_id');
    }
}
