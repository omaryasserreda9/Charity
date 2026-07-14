<?php

namespace App\Http\Controllers;

use App\Models\CaseExpense;
use App\Models\HumanitarianCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CaseExpenseController extends Controller
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
        if ($humanitarianCase->caseExpense()->exists()) {
            return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('error', 'يوجد بالفعل مصاريف مرتبطة بهذه الحالة.');
        }

        $humanitarianCase->caseExpense()->create($this->validatedAttributes($request));

        return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('success', 'تم حفظ بيانات المصاريف بنجاح.');
    }

    public function show(HumanitarianCase $humanitarianCase, CaseExpense $caseExpense): RedirectResponse
    {
        return redirect()->route('humanitarian-cases.show', $humanitarianCase);
    }

    public function edit(HumanitarianCase $humanitarianCase, CaseExpense $caseExpense): RedirectResponse
    {
        return redirect()->route('humanitarian-cases.show', $humanitarianCase);
    }

    public function update(Request $request, HumanitarianCase $humanitarianCase, CaseExpense $caseExpense): RedirectResponse
    {
        $caseExpense->update($this->validatedAttributes($request));

        return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('success', 'تم تحديث بيانات المصاريف بنجاح.');
    }

    public function destroy(HumanitarianCase $humanitarianCase, CaseExpense $caseExpense): RedirectResponse
    {
        $caseExpense->delete();

        return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('success', 'تم حذف بيانات المصاريف بنجاح.');
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate([
            'home_rent' => ['nullable', 'numeric', 'min:0'],
            'school_expenses' => ['nullable', 'numeric', 'min:0'],
            'utilities' => ['nullable', 'numeric', 'min:0'],
            'medicine' => ['nullable', 'numeric', 'min:0'],
            'nutrition' => ['nullable', 'numeric', 'min:0'],
            'other_expenses' => ['nullable', 'numeric', 'min:0'],
            'total_expenses' => ['nullable', 'numeric', 'min:0'],
        ]);
    }
}
