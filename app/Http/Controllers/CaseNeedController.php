<?php

namespace App\Http\Controllers;

use App\Models\CaseNeed;
use App\Models\HumanitarianCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CaseNeedController extends Controller
{
    public function index(HumanitarianCase $humanitarianCase): RedirectResponse
    {
        return redirect()->route('humanitarian-cases.show', $humanitarianCase);
    }

    public function create(HumanitarianCase $humanitarianCase): RedirectResponse
    {
        return redirect()->route('humanitarian-cases.show', $humanitarianCase);
    }

    public function store(Request $request, HumanitarianCase $humanitarianCase): RedirectResponse
    {
        if ($humanitarianCase->caseNeed()->exists()) {
            return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('error', 'يوجد بالفعل احتياجات مرتبطة بهذه الحالة.');
        }

        $humanitarianCase->caseNeed()->create($this->validatedAttributes($request));

        return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('success', 'تم حفظ احتياجات الحالة بنجاح.');
    }

    public function show(HumanitarianCase $humanitarianCase, CaseNeed $caseNeed): RedirectResponse
    {
        return redirect()->route('humanitarian-cases.show', $humanitarianCase);
    }

    public function edit(HumanitarianCase $humanitarianCase, CaseNeed $caseNeed): RedirectResponse
    {
        return redirect()->route('humanitarian-cases.show', $humanitarianCase);
    }

    public function update(Request $request, HumanitarianCase $humanitarianCase, CaseNeed $caseNeed): RedirectResponse
    {
        $caseNeed->update($this->validatedAttributes($request));

        return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('success', 'تم تحديث احتياجات الحالة بنجاح.');
    }

    public function destroy(HumanitarianCase $humanitarianCase, CaseNeed $caseNeed): RedirectResponse
    {
        $caseNeed->delete();

        return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('success', 'تم حذف احتياجات الحالة بنجاح.');
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate([
            'requested_needs' => ['nullable', 'string'],
            'recommended_needs' => ['nullable', 'string'],
        ]);
    }
}
