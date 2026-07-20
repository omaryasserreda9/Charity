<?php

namespace App\Models;

use App\Models\Traits\HasCharityHomeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donor extends Model
{
    use HasFactory, HasCharityHomeScope;

    protected $fillable = [
        'name',
        'phone',
    ];

    /**
     * Validation rules for Donor model properties
     */
    public static array $rules = [
        'name' => ['required', 'string', 'max:255'],
        'phone' => ['nullable', 'string', 'regex:/^01[0-9]{9}$/'],
    ];

    public function budgetOperations(): HasMany
    {
        return $this->hasMany(BudgetOperation::class);
    }

    public function inventoryOperations(): HasMany
    {
        return $this->hasMany(InventoryOperation::class);
    }
}
