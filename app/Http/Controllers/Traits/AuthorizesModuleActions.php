<?php

namespace App\Http\Controllers\Traits;

trait AuthorizesModuleActions
{
    protected string $permissionPrefix = '';

    protected function initializeModulePermissions(): void
    {
        if (empty($this->permissionPrefix)) {
            return;
        }

        $this->middleware("can:{$this->permissionPrefix}.view")->only(['index', 'show']);
        $this->middleware("can:{$this->permissionPrefix}.add")->only(['create', 'store']);
        $this->middleware("can:{$this->permissionPrefix}.edit")->only(['edit', 'update']);
        $this->middleware("can:{$this->permissionPrefix}.delete")->only(['destroy']);
    }

    protected function authorizeModuleAction(string $action): void
    {
        if (empty($this->permissionPrefix)) {
            return;
        }

        $this->authorize("{$this->permissionPrefix}.{$action}");
    }
}