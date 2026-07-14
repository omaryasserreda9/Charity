<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\CaseReferrer;

class District extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function referrers(): HasMany
    {
        return $this->hasMany(CaseReferrer::class);
    }
}
