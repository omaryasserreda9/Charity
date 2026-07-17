<?php

namespace App\Http\Controllers;

use App\Models\BudgetOperation;
use App\Models\Campaign;
use App\Models\CampaignOperation;
use App\Models\HumanitarianCase;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke()
    {
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            return redirect()->route('charity-homes.index');
        }

        $totalDonations = (float) BudgetOperation::where('type', 'in')->sum('quantity');
        $humanitarianCasesCount = HumanitarianCase::count();
        $activeCampaignsCount = Campaign::where('status', 'pending')->count();

        $stats = [
            [
                'label' => 'إجمالي التبرعات',
                'value' => number_format($totalDonations, 2),
                'icon' => 'fa-hand-holding-heart',
                'color' => 'success',
            ],
            [
                'label' => 'الحالات الإنسانية',
                'value' => number_format($humanitarianCasesCount),
                'icon' => 'fa-people-roof',
                'color' => 'primary',
            ],
            [
                'label' => 'الحملات قيد التنفيذ',
                'value' => number_format($activeCampaignsCount),
                'icon' => 'fa-bullhorn',
                'color' => 'warning',
            ],
            [
                'label' => 'عمليات الحملات',
                'value' => number_format(CampaignOperation::count()),
                'icon' => 'fa-clipboard-check',
                'color' => 'danger',
            ],
        ];

        $recentDonations = BudgetOperation::query()
            ->with('category')
            ->where('type', 'in')
            ->latest('operation_date')
            ->latest()
            ->limit(10)
            ->get();

        $pendingCampaigns = Campaign::query()
            ->with(['category', 'district'])
            ->where('status', 'pending')
            ->latest('campaign_date')
            ->limit(5)
            ->get();

        $breadcrumbs = [
            'لوحة التحكم' => route('dashboard'),
        ];

        return view('dashboard.index', compact('stats', 'recentDonations', 'pendingCampaigns', 'breadcrumbs'));
    }
}
