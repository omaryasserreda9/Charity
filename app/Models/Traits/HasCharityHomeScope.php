<?php

namespace App\Models\Traits;

use App\Models\Scopes\CharityHomeScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasCharityHomeScope
{
    public static function bootHasCharityHomeScope(): void
    {
        static::addGlobalScope(new CharityHomeScope());

        static::creating(function (Model $model): void {
            if (app()->runningInConsole() || ! auth()->check()) {
                return;
            }

            if (method_exists(auth()->user(), 'isSuperAdmin') && auth()->user()->isSuperAdmin()) {
                return;
            }

            // Force the charity_home_id to the authenticated user's home to prevent forged values
            $model->charity_home_id = auth()->user()->charity_home_id;
        });

        // Prevent non-super-admins from changing the charity_home_id on update
        static::updating(function (Model $model): void {
            if (app()->runningInConsole() || ! auth()->check()) {
                return;
            }

            if (method_exists(auth()->user(), 'isSuperAdmin') && auth()->user()->isSuperAdmin()) {
                return;
            }

            // Restore original charity_home_id if present, otherwise set to user's home
            $original = $model->getOriginal('charity_home_id');
            $model->charity_home_id = $original ?? auth()->user()->charity_home_id;
        });
    }

    public function scopeForCharityHome(Builder $query): Builder
    {
        if (app()->runningInConsole() || ! auth()->check()) {
            return $query;
        }

        if (method_exists(auth()->user(), 'isSuperAdmin') && auth()->user()->isSuperAdmin()) {
            return $query;
        }

        return $query->where($this->getTable() . '.charity_home_id', auth()->user()->charity_home_id);
    }
}
