<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_id',
        'title',
        'campaign_category_id',
        'status',
        'campaign_date',
    ];

    protected $casts = [
        'campaign_date' => 'date',
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CampaignCategory::class, 'campaign_category_id');
    }

    public function operations(): HasMany
    {
        return $this->hasMany(CampaignOperation::class);
    }

    public function humanitarianCases(): BelongsToMany
    {
        return $this->belongsToMany(HumanitarianCase::class, 'campaign_operations')
            ->withTimestamps();
    }

    public function caseReferrers(): BelongsToMany
    {
        return $this->belongsToMany(CaseReferrer::class, 'campaign_case_referrer')
            ->withTimestamps();
    }

    public function statusLabel(): string
    {
        switch ($this->status) {
            case 'pending':
                return 'قيد التنفيذ';

            case 'done':
                return 'منجزة';

            default:
                return $this->status;
        }
    }

    public static function statusOptions(): array
    {
        return [
            'pending' => 'قيد التنفيذ',
            'done' => 'منجزة',
        ];
    }
}
