<?php

namespace App\Http\Controllers;

use App\Models\CaseHomeDescription;
use App\Models\HumanitarianCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CaseHomeDescriptionController extends Controller
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
        if ($humanitarianCase->caseHomeDescription()->exists()) {
            return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('error', 'يوجد بالفعل وصف المنزل مرتبط بهذه الحالة.');
        }

        $humanitarianCase->caseHomeDescription()->create($this->validatedAttributes($request));

        return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('success', 'تم حفظ وصف المنزل بنجاح.');
    }

    public function show(HumanitarianCase $humanitarianCase, CaseHomeDescription $caseHomeDescription): RedirectResponse
    {
        return redirect()->route('humanitarian-cases.show', $humanitarianCase);
    }

    public function edit(HumanitarianCase $humanitarianCase, CaseHomeDescription $caseHomeDescription): RedirectResponse
    {
        return redirect()->route('humanitarian-cases.show', $humanitarianCase);
    }

    public function update(Request $request, HumanitarianCase $humanitarianCase, CaseHomeDescription $caseHomeDescription): RedirectResponse
    {
        $caseHomeDescription->update($this->validatedAttributes($request));

        return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('success', 'تم تحديث وصف المنزل بنجاح.');
    }

    public function destroy(HumanitarianCase $humanitarianCase, CaseHomeDescription $caseHomeDescription): RedirectResponse
    {
        $caseHomeDescription->delete();

        return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('success', 'تم حذف وصف المنزل بنجاح.');
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate([
            'rooms_count' => ['nullable', 'integer', 'min:0'],
            'clean_water' => ['nullable', 'boolean'],
            'roof_condition' => ['nullable', 'string', 'max:255'],
            'flooring_type' => ['nullable', 'string', 'max:255'],
            'has_tv' => ['nullable', 'boolean'],
            'has_washing_machine' => ['nullable', 'boolean'],
            'has_gas_stove' => ['nullable', 'boolean'],
            'has_fan' => ['nullable', 'boolean'],
            'has_phone' => ['nullable', 'boolean'],
            'has_fridge' => ['nullable', 'boolean'],
        ]);
    }
}
