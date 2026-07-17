<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\District;
use App\Models\HumanitarianCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use App\Models\CaseReferrer;

class CampaignController extends Controller
{
    protected string $permissionPrefix = 'campaigns';
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'campaign_category_id', 'district_id']);
        $categories = CampaignCategory::orderBy('title')->get();
        $districts = District::orderBy('title')->get();

        $campaigns = Campaign::query()
            ->with(['category', 'district'])
            ->withCount('humanitarianCases')
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhereHas('district', fn($districtQuery) => $districtQuery->where('title', 'like', "%{$search}%"));
                });
            })
            ->when($filters['campaign_category_id'] ?? null, fn($query, $categoryId) => $query->where('campaign_category_id', $categoryId))
            ->when($filters['district_id'] ?? null, fn($query, $districtId) => $query->where('district_id', $districtId))
            ->latest('campaign_date')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $breadcrumbs = ['الحملات' => route('campaigns.index')];

        return view('campaign.campaigns.index', compact('campaigns', 'categories', 'districts', 'filters', 'breadcrumbs'));
    }

    public function create(): View
    {
        $categories = CampaignCategory::orderBy('title')->get();
        $districts = District::orderBy('title')->get();
        $caseReferrers = CaseReferrer::orderBy('name')->get();
        $breadcrumbs = [
            'الحملات' => route('campaigns.index'),
            'إضافة حملة' => route('campaigns.create'),
        ];

        return view('campaign.campaigns.create', compact('categories', 'districts', 'caseReferrers', 'breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        $campaign = Campaign::create($this->validatedAttributes($request));

        $referrerIds = $this->validatedReferrerIds($request);
        if (! auth()->user()->isSuperAdmin() && ! empty($referrerIds)) {
            $allowed = \App\Models\CaseReferrer::whereIn('id', $referrerIds)
                ->where('charity_home_id', auth()->user()->charity_home_id)
                ->pluck('id')
                ->all();

            if (count($allowed) !== count($referrerIds)) {
                abort(403);
            }
        }

        $campaign->caseReferrers()->sync($referrerIds);

        return redirect()->route('campaigns.index')->with('success', 'تم إنشاء الحملة بنجاح.');
    }

    public function show(Campaign $campaign): View
    {
        $campaign->load(['category', 'district', 'humanitarianCases', 'caseReferrers']);

        $breadcrumbs = [
            'الحملات' => route('campaigns.index'),
            $campaign->title => route('campaigns.show', $campaign),
        ];

        return view('campaign.campaigns.show', compact('campaign', 'breadcrumbs'));
    }

    public function edit(Campaign $campaign): View
    {
        $categories = CampaignCategory::orderBy('title')->get();
        $districts = District::orderBy('title')->get();
        $caseReferrers = CaseReferrer::orderBy('name')->get();
        $breadcrumbs = [
            'الحملات' => route('campaigns.index'),
            'تعديل الحملة' => route('campaigns.edit', $campaign),
        ];

        return view('campaign.campaigns.edit', compact('campaign', 'categories', 'districts', 'caseReferrers', 'breadcrumbs'));
    }

    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $campaign->update($this->validatedAttributes($request));

        $referrerIds = $this->validatedReferrerIds($request);
        if (! auth()->user()->isSuperAdmin() && ! empty($referrerIds)) {
            $allowed = \App\Models\CaseReferrer::whereIn('id', $referrerIds)
                ->where('charity_home_id', auth()->user()->charity_home_id)
                ->pluck('id')
                ->all();

            if (count($allowed) !== count($referrerIds)) {
                abort(403);
            }
        }

        $campaign->caseReferrers()->sync($referrerIds);

        return redirect()->route('campaigns.index')->with('success', 'تم تحديث الحملة بنجاح.');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        $campaign->delete();

        return redirect()->route('campaigns.index')->with('success', 'تم حذف الحملة بنجاح.');
    }

    public function cases(Request $request, Campaign $campaign): View
    {
        $this->authorize('campaigns.view');
        $filters = $request->only([
            'search',
            'district_id',
            'referrer_id',
            'type',
        ]);
        $campaign->load('category');
        $selectedCaseIds = $campaign->humanitarianCases()->pluck('humanitarian_cases.id')->all();
        $districts = District::orderBy('title')->get();
        $referrers = CaseReferrer::orderBy('name')->get();

        $humanitarianCases = HumanitarianCase::query()
            ->with(['district', 'referrer'])
            ->withCount('familyMembers')
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('national_id', 'like', "%{$search}%")
                        ->orWhereHas('district', fn($districtQuery) => $districtQuery->where('title', 'like', "%{$search}%"));
                });
            })
            ->when($filters['district_id'] ?? null, fn($query, $districtId) => $query->where('district_id', $districtId))
            ->when($filters['referrer_id'] ?? null, fn($query, $referrerId) => $query->where('referrer_id', $referrerId))
            ->when($filters['type'] ?? null, fn($query, $type) => $query->where('type', $type))
            ->orderBy('name')
            ->get();

        $breadcrumbs = [
            'الحملات' => route('campaigns.index'),
            $campaign->title => route('campaigns.show', $campaign),
            'الحالات المرتبطة' => route('campaigns.cases', $campaign),
        ];

        return view('campaign.campaigns.cases', compact(
            'campaign',
            'humanitarianCases',
            'selectedCaseIds',
            'districts',
            'referrers',
            'filters',
            'breadcrumbs'
        ));
    }

    public function syncCases(Request $request, Campaign $campaign): RedirectResponse
    {
        $this->authorize('campaigns.edit');
        $validated = $request->validate([
            'humanitarian_case_ids' => ['nullable', 'array'],
            'humanitarian_case_ids.*' => ['integer', 'exists:humanitarian_cases,id'],
        ]);

        $caseIds = $validated['humanitarian_case_ids'] ?? [];
        if (! auth()->user()->isSuperAdmin() && ! empty($caseIds)) {
            $allowed = \App\Models\HumanitarianCase::whereIn('id', $caseIds)
                ->where('charity_home_id', auth()->user()->charity_home_id)
                ->pluck('id')
                ->all();

            if (count($allowed) !== count($caseIds)) {
                abort(403);
            }
        }

        $campaign->humanitarianCases()->sync($caseIds);

        return redirect()
            ->route('campaigns.cases', $campaign)
            ->with('success', 'تم حفظ الحالات المرتبطة بالحملة بنجاح.')
            ->with('prompt_mark_done', $campaign->status === 'pending');
    }

    public function markDone(Campaign $campaign): RedirectResponse
    {
        $this->authorize('campaigns.edit');
        $campaign->update(['status' => 'done']);

        return redirect()
            ->route('campaigns.cases', $campaign)
            ->with('success', 'تم تحديث حالة الحملة إلى منجزة.');
    }

    public function exportCases(Request $request, Campaign $campaign)
    {
        $this->authorize('campaigns.view');
        $filters = $request->only([
            'search',
            'district_id',
            'referrer_id',
            'type',
        ]);

        $campaign->load('category');

        $humanitarianCases = HumanitarianCase::query()
            ->with(['district', 'referrer'])
            ->withCount('familyMembers')
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('national_id', 'like', "%{$search}%")
                        ->orWhereHas('district', fn($districtQuery) => $districtQuery->where('title', 'like', "%{$search}%"));
                });
            })
            ->when($filters['district_id'] ?? null, fn($query, $districtId) => $query->where('district_id', $districtId))
            ->when($filters['referrer_id'] ?? null, fn($query, $referrerId) => $query->where('referrer_id', $referrerId))
            ->when($filters['type'] ?? null, fn($query, $type) => $query->where('type', $type))
            ->orderBy('name')
            ->get();

        $view = view('campaign.campaigns.export', compact('campaign', 'humanitarianCases', 'filters'))->render();

        $filename = sprintf('campaign_%s_cases_%s.xls', $campaign->id, now()->format('Ymd_His'));

        // Excel-friendly HTML with UTF-8 BOM for Arabic
        $content = "\xEF\xBB\xBF" . $view;

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate([
            'district_id' => ['required', 'exists:districts,id'],
            'title' => ['required', 'string', 'max:255'],
            'campaign_category_id' => ['required', 'exists:campaign_categories,id'],
            'status' => ['required', Rule::in(['pending', 'done'])],
            'campaign_date' => ['required', 'date'],
        ]);
    }

    private function validatedReferrerIds(Request $request): array
    {
        $validated = $request->validate([
            'case_referrer_ids' => ['nullable', 'array'],
            'case_referrer_ids.*' => ['integer', 'exists:case_referrers,id'],
        ]);

        return $validated['case_referrer_ids'] ?? [];
    }
}
