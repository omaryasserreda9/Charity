<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BudgetCategoryController;
use App\Http\Controllers\BudgetOperationController;
use App\Http\Controllers\CampaignCategoryController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HumanitarianCaseController;
use App\Http\Controllers\HumanitarianCaseFileController;
use App\Http\Controllers\InventoryCategoryController;
use App\Http\Controllers\InventoryOperationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
});

Route::middleware('guest')->group(function (): void {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function (): void {
    Route::get('dashboard', DashboardController::class)->name('dashboard');
    Route::resource('budget-categories', BudgetCategoryController::class);
    Route::resource('budget-operations', BudgetOperationController::class);
    Route::resource('inventory-categories', InventoryCategoryController::class);
    Route::resource('inventory-operations', InventoryOperationController::class);
    Route::resource('humanitarian-cases', HumanitarianCaseController::class);
    Route::get('campaigns/{campaign}/cases', [CampaignController::class, 'cases'])->name('campaigns.cases');
    Route::put('campaigns/{campaign}/cases', [CampaignController::class, 'syncCases'])->name('campaigns.cases.sync');
    Route::patch('campaigns/{campaign}/mark-done', [CampaignController::class, 'markDone'])->name('campaigns.mark-done');
    Route::resource('campaign-categories', CampaignCategoryController::class);
    Route::resource('campaigns', CampaignController::class);
    Route::get('humanitarian-case-files/{humanitarianCaseFile}/preview', [HumanitarianCaseFileController::class, 'preview'])
        ->name('humanitarian-case-files.preview');
    Route::get('humanitarian-case-files/{humanitarianCaseFile}/download', [HumanitarianCaseFileController::class, 'download'])
        ->name('humanitarian-case-files.download');
    Route::delete('humanitarian-case-files/{humanitarianCaseFile}', [HumanitarianCaseFileController::class, 'destroy'])
        ->name('humanitarian-case-files.destroy');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
