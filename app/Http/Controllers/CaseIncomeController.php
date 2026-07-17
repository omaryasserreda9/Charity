<?php

namespace App\Http\Controllers;

use App\Models\CaseIncome;
use App\Models\HumanitarianCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CaseIncomeController extends Controller
{
    protected string $permissionPrefix = 'humanitarian_cases';
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
        if ($humanitarianCase->caseIncome()->exists()) {
            return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('error', 'يوجد بالفعل دخل مرتبط بهذه الحالة.');
        }

        $humanitarianCase->caseIncome()->create($this->validatedAttributes($request));

        return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('success', 'تم حفظ بيانات الدخل بنجاح.');
    }

    public function show(HumanitarianCase $humanitarianCase, CaseIncome $caseIncome): RedirectResponse
    {
        return redirect()->route('humanitarian-cases.show', $humanitarianCase);
    }

    public function edit(HumanitarianCase $humanitarianCase, CaseIncome $caseIncome): RedirectResponse
    {
        return redirect()->route('humanitarian-cases.show', $humanitarianCase);
    }

    public function update(Request $request, HumanitarianCase $humanitarianCase, CaseIncome $caseIncome): RedirectResponse
    {
        $caseIncome->update($this->validatedAttributes($request));

        return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('success', 'تم تحديث بيانات الدخل بنجاح.');
    }

    public function destroy(HumanitarianCase $humanitarianCase, CaseIncome $caseIncome): RedirectResponse
    {
        $caseIncome->delete();

        return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('success', 'تم حذف بيانات الدخل بنجاح.');
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate([
            'job_income' => ['nullable', 'numeric', 'min:0'],
            'pension_income' => ['nullable', 'numeric', 'min:0'],
            'charity_income' => ['nullable', 'numeric', 'min:0'],
            'other_income' => ['nullable', 'numeric', 'min:0'],
            'total_income' => ['nullable', 'numeric', 'min:0'],
        ]);
    }
}
