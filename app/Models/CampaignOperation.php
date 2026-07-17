<?php

namespace App\Models;

use App\Models\Traits\HasCharityHomeScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignOperation extends Model
{
    use HasFactory, HasCharityHomeScope;

    protected $fillable = [
        'campaign_id',
        'humanitarian_case_id',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function humanitarianCase(): BelongsTo
    {
        return $this->belongsTo(HumanitarianCase::class);
    }
}
