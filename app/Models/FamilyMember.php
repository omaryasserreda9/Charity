<?php

namespace App\Models;

use App\Models\Traits\HasCharityHomeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyMember extends Model
{
    use HasFactory, HasCharityHomeScope;

    protected $fillable = [
        'humanitarian_case_id',
        'name',
        'relation',
        'age',
        'education',
        'health_status',
        'marital_status',
        'average_income',
        'job',
    ];

    public function humanitarianCase(): BelongsTo
    {
        return $this->belongsTo(HumanitarianCase::class);
    }
}
