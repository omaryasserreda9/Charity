<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HumanitarianCaseFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'humanitarian_case_id',
        'original_name',
        'path',
        'mime_type',
        'size',
    ];

    public function humanitarianCase(): BelongsTo
    {
        return $this->belongsTo(HumanitarianCase::class);
    }

    public function url(): string
    {
        return route('humanitarian-case-files.preview', $this);
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    public function formattedSize(): string
    {
        if ($this->size === null) {
            return '';
        }

        if ($this->size >= 1048576) {
            return number_format($this->size / 1048576, 2).' MB';
        }

        if ($this->size >= 1024) {
            return number_format($this->size / 1024, 2).' KB';
        }

        return $this->size.' B';
    }
}
