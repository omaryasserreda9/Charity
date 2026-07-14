<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class HumanitarianCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'national_id',
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

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class, 'campaign_operations')
            ->withTimestamps();
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
