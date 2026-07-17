<?php

namespace App\Http\Controllers;

use App\Models\CampaignCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignCategoryController extends Controller
{
    protected string $permissionPrefix = 'campaign_categories';
    public function index(Request $request): View
    {
        $search = (string) $request->input('search', '');

        $categories = CampaignCategory::query()
            ->withCount('campaigns')
            ->when($search, fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $breadcrumbs = ['بنود الحملات' => route('campaign-categories.index')];

        return view('campaign.categories.index', compact('categories', 'search', 'breadcrumbs'));
    }

    public function create(): View
    {
        $breadcrumbs = [
            'بنود الحملات' => route('campaign-categories.index'),
            'إضافة بند' => route('campaign-categories.create'),
        ];

        return view('campaign.categories.create', compact('breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        CampaignCategory::create($this->validatedAttributes($request));

        return redirect()->route('campaign-categories.index')->with('success', 'تم إنشاء البند بنجاح.');
    }

    public function show(CampaignCategory $campaignCategory): View
    {
        $campaignCategory->loadCount('campaigns');

        $breadcrumbs = [
            'بنود الحملات' => route('campaign-categories.index'),
            $campaignCategory->title => route('campaign-categories.show', $campaignCategory),
        ];

        return view('campaign.categories.show', compact('campaignCategory', 'breadcrumbs'));
    }

    public function edit(CampaignCategory $campaignCategory): View
    {
        $breadcrumbs = [
            'بنود الحملات' => route('campaign-categories.index'),
            'تعديل البند' => route('campaign-categories.edit', $campaignCategory),
        ];

        return view('campaign.categories.edit', compact('campaignCategory', 'breadcrumbs'));
    }

    public function update(Request $request, CampaignCategory $campaignCategory): RedirectResponse
    {
        $campaignCategory->update($this->validatedAttributes($request));

        return redirect()->route('campaign-categories.index')->with('success', 'تم تحديث البند بنجاح.');
    }

    public function destroy(CampaignCategory $campaignCategory): RedirectResponse
    {
        if ($campaignCategory->campaigns()->exists()) {
            return redirect()->route('campaign-categories.index')->with('error', 'لا يمكن حذف البند لوجود حملات مرتبطة به.');
        }

        $campaignCategory->delete();

        return redirect()->route('campaign-categories.index')->with('success', 'تم حذف البند بنجاح.');
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);
    }
}
