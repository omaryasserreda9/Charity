<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CharityHomeScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (app()->runningInConsole() || ! auth()->check()) {
            return;
        }

        $user = auth()->user();
        if (! method_exists($user, 'isSuperAdmin') || $user->isSuperAdmin()) {
            return;
        }

        $charityHomeId = $user->charity_home_id;
        if ($charityHomeId === null) {
            $builder->whereNull($model->getTable() . '.charity_home_id');
        } else {
            $builder->where($model->getTable() . '.charity_home_id', $charityHomeId);
        }
    }
}
