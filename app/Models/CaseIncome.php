<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseIncome extends Model
{
    use HasFactory;

    protected $fillable = [
        'humanitarian_case_id',
        'job_income',
        'pension_income',
        'charity_income',
        'other_income',
        'total_income',
    ];

    public function humanitarianCase(): BelongsTo
    {
        return $this->belongsTo(HumanitarianCase::class);
    }
}
