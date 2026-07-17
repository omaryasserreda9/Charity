<?php

namespace App\Http\Controllers;

use App\Models\CaseReferrer;
use App\Models\District;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaseReferrerController extends Controller
{
    protected string $permissionPrefix = 'case_referrers';
    public function index(Request $request): View
    {
        $search = (string) $request->input('search', '');
        $districtId = $request->input('district_id');
        $districts = District::orderBy('title')->get();

        $referrers = CaseReferrer::query()
            ->with('district')
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->when($districtId, fn ($query, $districtId) => $query->where('district_id', $districtId))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $breadcrumbs = ['الدليل' => route('case-referrers.index')];

        return view('case-referrers.index', compact('referrers', 'districts', 'search', 'districtId', 'breadcrumbs'));
    }

    public function create(): View
    {
        $districts = District::orderBy('title')->get();

        $breadcrumbs = [
            'الدليل' => route('case-referrers.index'),
            'إضافة دليل' => route('case-referrers.create'),
        ];

        return view('case-referrers.create', compact('districts', 'breadcrumbs'));
    }

    public function store(Request $request): RedirectResponse
    {
        CaseReferrer::create($this->validatedAttributes($request));

        return redirect()->route('case-referrers.index')->with('success', 'تم إنشاء الدليل بنجاح.');
    }

    public function show(CaseReferrer $caseReferrer): View
    {
        $caseReferrer->load('district');

        $breadcrumbs = [
            'الدليل' => route('case-referrers.index'),
            $caseReferrer->name => route('case-referrers.show', $caseReferrer),
        ];

        return view('case-referrers.show', compact('caseReferrer', 'breadcrumbs'));
    }

    public function edit(CaseReferrer $caseReferrer): View
    {
        $districts = District::orderBy('title')->get();

        $breadcrumbs = [
            'الدليل' => route('case-referrers.index'),
            'تعديل الدليل' => route('case-referrers.edit', $caseReferrer),
        ];

        return view('case-referrers.edit', compact('caseReferrer', 'districts', 'breadcrumbs'));
    }

    public function update(Request $request, CaseReferrer $caseReferrer): RedirectResponse
    {
        $caseReferrer->update($this->validatedAttributes($request));

        return redirect()->route('case-referrers.index')->with('success', 'تم تحديث الدليل بنجاح.');
    }

    public function destroy(CaseReferrer $caseReferrer): RedirectResponse
    {
        $caseReferrer->delete();

        return redirect()->route('case-referrers.index')->with('success', 'تم حذف الدليل بنجاح.');
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'district_id' => ['required', 'exists:districts,id'],
        ]);
    }
}
