<?php

namespace App\Http\Controllers;

use App\Models\CaseReferrer;
use App\Models\District;
use App\Models\HumanitarianCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HumanitarianCaseController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'type', 'district_id', 'referrer_id']);
        $districts = District::orderBy('title')->get();
        $referrers = CaseReferrer::query()
            ->when($filters['district_id'] ?? null, fn($query, $districtId) => $query->where('district_id', $districtId))
            ->orderBy('name')
            ->get();

        $cases = HumanitarianCase::query()
            ->withCount(['files', 'familyMembers'])
            ->with(['district', 'referrer'])
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('national_id', 'like', "%{$search}%")
                        ->orWhereHas('district', fn($districtQuery) => $districtQuery->where('title', 'like', "%{$search}%"))
                        ->orWhereHas('referrer', fn($referrerQuery) => $referrerQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($filters['type'] ?? null, fn($query, string $type) => $query->where('type', $type))
            ->when($filters['district_id'] ?? null, fn($query, $districtId) => $query->where('district_id', $districtId))
            ->when($filters['referrer_id'] ?? null, fn($query, $referrerId) => $query->where('referrer_id', $referrerId))
            ->latest()
            ->get();

        $breadcrumbs = ['الحالات الإنسانية' => route('humanitarian-cases.index')];

        return view('humanitarian-cases.index', compact('cases', 'districts', 'referrers', 'filters', 'breadcrumbs'));
    }

    public function create(): View
    {
        $districts = District::orderBy('title')->get();
        $referrers = CaseReferrer::orderBy('name')->get();

        $referrersJson = $referrers->map(function ($referrer) {
            return [
                'id' => $referrer->id,
                'name' => $referrer->name,
                'district_id' => $referrer->district_id,
            ];
        })->values()->toArray();

        $breadcrumbs = [
            'الحالات الإنسانية' => route('humanitarian-cases.index'),
            'إضافة حالة' => route('humanitarian-cases.create'),
        ];

        return view('humanitarian-cases.create', compact(
            'districts',
            'referrers',
            'referrersJson',
            'breadcrumbs'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $case = HumanitarianCase::create($this->validatedAttributes($request));
        $this->saveRelatedData($request, $case);
        $this->storeAttachments($request, $case);

        return redirect()->route('humanitarian-cases.index')->with('success', 'تم إنشاء الحالة بنجاح.');
    }

    public function show(HumanitarianCase $humanitarianCase): View
    {
        $humanitarianCase->load(['files', 'district', 'referrer', 'familyMembers', 'caseIncome', 'caseExpense', 'caseHomeDescription', 'caseNeed']);

        $breadcrumbs = [
            'الحالات الإنسانية' => route('humanitarian-cases.index'),
            $humanitarianCase->name => route('humanitarian-cases.show', $humanitarianCase),
        ];

        return view('humanitarian-cases.show', compact('humanitarianCase', 'breadcrumbs'));
    }

    public function edit(HumanitarianCase $humanitarianCase): View
    {
        $humanitarianCase->load([
            'files',
            'familyMembers',
            'caseIncome',
            'caseExpense',
            'caseHomeDescription',
            'caseNeed',
            'referrer',
        ]);

        $districts = District::orderBy('title')->get();
        $referrers = CaseReferrer::orderBy('name')->get();

        $referrersJson = $referrers->map(function ($referrer) {
            return [
                'id' => $referrer->id,
                'name' => $referrer->name,
                'district_id' => $referrer->district_id,
            ];
        })->values()->toArray();

        $breadcrumbs = [
            'الحالات الإنسانية' => route('humanitarian-cases.index'),
            'تعديل الحالة' => route('humanitarian-cases.edit', $humanitarianCase),
        ];

        return view('humanitarian-cases.edit', compact(
            'humanitarianCase',
            'districts',
            'referrers',
            'referrersJson',
            'breadcrumbs'
        ));
    }

    public function update(Request $request, HumanitarianCase $humanitarianCase): RedirectResponse
    {
        $humanitarianCase->update($this->validatedAttributes($request, $humanitarianCase));
        $this->saveRelatedData($request, $humanitarianCase);
        $this->storeAttachments($request, $humanitarianCase);

        return redirect()->route('humanitarian-cases.index')->with('success', 'تم تحديث الحالة بنجاح.');
    }

    public function destroy(HumanitarianCase $humanitarianCase): RedirectResponse
    {
        $humanitarianCase->delete();

        return redirect()->route('humanitarian-cases.index')->with('success', 'تم حذف الحالة بنجاح.');
    }

    private function validatedAttributes(Request $request, ?HumanitarianCase $case = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^0[0-9]{10}$/'],
            'national_id' => [
                'required',
                'string',
                'regex:/^[2-9][0-9]{13}$/',
                Rule::unique('humanitarian_cases')->ignore(optional($case)->id),
            ],
            'district_id' => ['required', 'exists:districts,id'],
            'referrer_id' => [
                'nullable',
                Rule::exists('case_referrers', 'id')->where('district_id', $request->input('district_id')),
            ],
            'research_team' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'type' => ['required', Rule::in(['mine', 'seasonal'])],
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,webp,doc,docx'],
        ], [
            'phone.regex' => 'رقم الجوال يجب أن يكون 11 رقمًا ويبدأ بـ 0.',
            'national_id.regex' => 'رقم الهوية يجب أن يكون 14 رقمًا ويبدأ بـ 2 أو أعلى (لا يبدأ بـ 0 أو 1).',
        ]);
    }

    private function storeAttachments(Request $request, HumanitarianCase $case): void
    {
        if (! $request->hasFile('attachments')) {
            return;
        }

        /** @var UploadedFile $file */
        foreach ($request->file('attachments') as $file) {
            $path = $file->store("humanitarian-cases/{$case->id}", 'public');

            $case->files()->create([
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }
    }

    private function saveRelatedData(Request $request, HumanitarianCase $case): void
    {
        $validated = $this->validatedRelatedAttributes($request);

        $this->saveFamilyMembers($validated['family_members'] ?? [], $case);

        $caseIncome = $validated['case_income'] ?? [];
        $caseIncome['total_income'] = $this->computeDecimalTotal([
            $caseIncome['job_income'] ?? null,
            $caseIncome['pension_income'] ?? null,
            $caseIncome['charity_income'] ?? null,
            $caseIncome['other_income'] ?? null,
        ]);
        $this->saveSingleRelatedRecord($case, 'caseIncome', $caseIncome);

        $caseExpense = $validated['case_expense'] ?? [];
        $caseExpense['total_expenses'] = $this->computeDecimalTotal([
            $caseExpense['home_rent'] ?? null,
            $caseExpense['school_expenses'] ?? null,
            $caseExpense['utilities'] ?? null,
            $caseExpense['medicine'] ?? null,
            $caseExpense['nutrition'] ?? null,
            $caseExpense['other_expenses'] ?? null,
        ]);
        $this->saveSingleRelatedRecord($case, 'caseExpense', $caseExpense);

        $this->saveSingleRelatedRecord($case, 'caseHomeDescription', $validated['case_home_description'] ?? []);
        $this->saveSingleRelatedRecord($case, 'caseNeed', $validated['case_need'] ?? []);
    }

    private function validatedRelatedAttributes(Request $request): array
    {
        return $request->validate([
            'family_members' => ['nullable', 'array'],
            'family_members.*.name' => ['nullable', 'string', 'max:255'],
            'family_members.*.relation' => ['nullable', 'string', 'max:255'],
            'family_members.*.age' => ['nullable', 'integer', 'min:0', 'max:150'],
            'family_members.*.education' => ['nullable', 'string', 'max:255'],
            'family_members.*.health_status' => ['nullable', 'string', 'max:255'],
            'family_members.*.marital_status' => ['nullable', 'string', 'max:255'],
            'family_members.*.average_income' => ['nullable', 'string', 'max:255'],
            'family_members.*.job' => ['nullable', 'string', 'max:255'],

            'case_income' => ['nullable', 'array'],
            'case_income.job_income' => ['nullable', 'numeric', 'min:0'],
            'case_income.pension_income' => ['nullable', 'numeric', 'min:0'],
            'case_income.charity_income' => ['nullable', 'numeric', 'min:0'],
            'case_income.other_income' => ['nullable', 'numeric', 'min:0'],

            'case_expense' => ['nullable', 'array'],
            'case_expense.home_rent' => ['nullable', 'numeric', 'min:0'],
            'case_expense.school_expenses' => ['nullable', 'numeric', 'min:0'],
            'case_expense.utilities' => ['nullable', 'numeric', 'min:0'],
            'case_expense.medicine' => ['nullable', 'numeric', 'min:0'],
            'case_expense.nutrition' => ['nullable', 'numeric', 'min:0'],
            'case_expense.other_expenses' => ['nullable', 'numeric', 'min:0'],

            'case_home_description' => ['nullable', 'array'],
            'case_home_description.rooms_count' => ['nullable', 'integer', 'min:0'],
            'case_home_description.clean_water' => ['nullable', 'boolean'],
            'case_home_description.roof_condition' => ['nullable', 'string', 'max:255'],
            'case_home_description.flooring_type' => ['nullable', 'string', 'max:255'],
            'case_home_description.has_tv' => ['nullable', 'boolean'],
            'case_home_description.has_washing_machine' => ['nullable', 'boolean'],
            'case_home_description.has_gas_stove' => ['nullable', 'boolean'],
            'case_home_description.has_fan' => ['nullable', 'boolean'],
            'case_home_description.has_phone' => ['nullable', 'boolean'],
            'case_home_description.has_fridge' => ['nullable', 'boolean'],

            'case_need' => ['nullable', 'array'],
            'case_need.requested_needs' => ['nullable', 'string'],
            'case_need.recommended_needs' => ['nullable', 'string'],
        ]);
    }

    private function saveFamilyMembers(array $members, HumanitarianCase $case): void
    {
        $case->familyMembers()->delete();

        foreach ($members as $member) {
            if (! $this->hasValues($member)) {
                continue;
            }

            $case->familyMembers()->create([
                'name' => $member['name'] ?? null,
                'relation' => $member['relation'] ?? null,
                'age' => $member['age'] ?? null,
                'education' => $member['education'] ?? null,
                'health_status' => $member['health_status'] ?? null,
                'marital_status' => $member['marital_status'] ?? null,
                'average_income' => $member['average_income'] ?? null,
                'job' => $member['job'] ?? null,
            ]);
        }
    }

    private function saveSingleRelatedRecord(HumanitarianCase $case, string $relation, array $data): void
    {
        if (! $this->hasValues($data)) {
            $case->$relation()->delete();
            return;
        }

        $case->$relation()->updateOrCreate(
            ['humanitarian_case_id' => $case->id],
            $data
        );
    }

    private function computeDecimalTotal(array $values): ?string
    {
        $total = 0;
        $hasValue = false;

        foreach ($values as $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $hasValue = true;
            $total += (float) $value;
        }

        return $hasValue ? number_format($total, 2, '.', '') : null;
    }

    private function hasValues(array $data): bool
    {
        foreach ($data as $value) {
            if ($value === null || $value === '') {
                continue;
            }

            return true;
        }

        return false;
    }
}
