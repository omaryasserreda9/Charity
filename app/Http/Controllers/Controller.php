<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\AuthorizesModuleActions;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
    use AuthorizesModuleActions;

    public function __construct()
    {
        $this->initializeModulePermissions();
    }
}
