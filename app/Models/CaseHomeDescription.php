<?php

namespace App\Models;

use App\Models\Traits\HasCharityHomeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseHomeDescription extends Model
{
    use HasFactory, HasCharityHomeScope;

    protected $fillable = [
        'humanitarian_case_id',
        'rooms_count',
        'clean_water',
        'roof_condition',
        'flooring_type',
        'has_tv',
        'has_washing_machine',
        'has_gas_stove',
        'has_fan',
        'has_phone',
        'has_fridge',
    ];

    protected $casts = [
        'clean_water' => 'boolean',
        'has_tv' => 'boolean',
        'has_washing_machine' => 'boolean',
        'has_gas_stove' => 'boolean',
        'has_fan' => 'boolean',
        'has_phone' => 'boolean',
        'has_fridge' => 'boolean',
    ];

    public function humanitarianCase(): BelongsTo
    {
        return $this->belongsTo(HumanitarianCase::class);
    }
}
