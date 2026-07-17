<?php

namespace App\Models;

use App\Models\Traits\HasCharityHomeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseNeed extends Model
{
    use HasFactory, HasCharityHomeScope;

    protected $fillable = [
        'humanitarian_case_id',
        'requested_needs',
        'recommended_needs',
    ];

    public function humanitarianCase(): BelongsTo
    {
        return $this->belongsTo(HumanitarianCase::class);
    }
}
