<?php

namespace App\Http\Controllers;

use App\Models\FamilyMember;
use App\Models\HumanitarianCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FamilyMemberController extends Controller
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
        $humanitarianCase->familyMembers()->create($this->validatedAttributes($request));

        return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('success', 'تم إضافة فرد العائلة بنجاح.');
    }

    public function show(HumanitarianCase $humanitarianCase, FamilyMember $familyMember): RedirectResponse
    {
        return redirect()->route('humanitarian-cases.show', $humanitarianCase);
    }

    public function edit(HumanitarianCase $humanitarianCase, FamilyMember $familyMember): RedirectResponse
    {
        return redirect()->route('humanitarian-cases.show', $humanitarianCase);
    }

    public function update(Request $request, HumanitarianCase $humanitarianCase, FamilyMember $familyMember): RedirectResponse
    {
        $familyMember->update($this->validatedAttributes($request));

        return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('success', 'تم تحديث فرد العائلة بنجاح.');
    }

    public function destroy(HumanitarianCase $humanitarianCase, FamilyMember $familyMember): RedirectResponse
    {
        $familyMember->delete();

        return redirect()->route('humanitarian-cases.show', $humanitarianCase)->with('success', 'تم حذف فرد العائلة بنجاح.');
    }

    private function validatedAttributes(Request $request): array
    {
        return $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'relation' => ['nullable', 'string', 'max:255'],
            'age' => ['nullable', 'integer', 'min:0', 'max:150'],
            'education' => ['nullable', 'string', 'max:255'],
            'health_status' => ['nullable', 'string', 'max:255'],
            'marital_status' => ['nullable', 'string', 'max:255'],
            'average_income' => ['nullable', 'string', 'max:255'],
            'job' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
