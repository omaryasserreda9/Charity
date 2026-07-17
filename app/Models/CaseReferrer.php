<?php

namespace App\Models;

use App\Models\Traits\HasCharityHomeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\District;
use App\Models\HumanitarianCase;
use App\Models\Campaign;

class CaseReferrer extends Model
{
    use HasFactory, HasCharityHomeScope;

    protected $fillable = [
        'name',
        'district_id',
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function humanitarianCases(): HasMany
    {
        return $this->hasMany(HumanitarianCase::class, 'referrer_id');
    }

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_case_referrer')
            ->withTimestamps();
    }
}
