<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function operations(): HasMany
    {
        return $this->hasMany(InventoryOperation::class);
    }
}
