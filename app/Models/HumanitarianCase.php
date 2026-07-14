<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Campaign;
use App\Models\CaseExpense;
use App\Models\CaseHomeDescription;
use App\Models\CaseIncome;
use App\Models\CaseNeed;
use App\Models\CaseReferrer;
use App\Models\District;
use App\Models\FamilyMember;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class HumanitarianCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'national_id',
        'district_id',
        'referrer_id',
        'research_team',
        'notes',
        'type',
    ];

    protected static function booted(): void
    {
        static::deleting(function (HumanitarianCase $case): void {
            $case->load('files');

            foreach ($case->files as $file) {
                Storage::disk('public')->delete($file->path);
            }
        });
    }

    public function files(): HasMany
    {
        return $this->hasMany(HumanitarianCaseFile::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_operations')
            ->withTimestamps();
    }

    public function familyMembers(): HasMany
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function caseIncome(): HasOne
    {
        return $this->hasOne(CaseIncome::class);
    }

    public function caseExpense(): HasOne
    {
        return $this->hasOne(CaseExpense::class);
    }

    public function caseHomeDescription(): HasOne
    {
        return $this->hasOne(CaseHomeDescription::class);
    }

    public function caseNeed(): HasOne
    {
        return $this->hasOne(CaseNeed::class);
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(CaseReferrer::class, 'referrer_id');
    }

    public function typeLabel(): string
    {
        switch ($this->type) {
            case 'mine':
                return 'خاصة بالجمعية';

            case 'seasonal':
                return 'موسمية';

            default:
                return $this->type;
        }
    }

    public static function typeOptions(): array
    {
        return [
            'mine' => 'خاصة بالجمعية',
            'seasonal' => 'موسمية',
        ];
    }
}
