<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'humanitarian_case_id',
        'home_rent',
        'school_expenses',
        'utilities',
        'medicine',
        'nutrition',
        'other_expenses',
        'total_expenses',
    ];

    public function humanitarianCase(): BelongsTo
    {
        return $this->belongsTo(HumanitarianCase::class);
    }
}
