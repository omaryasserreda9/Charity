@csrf

@php
$familyMembers = old('family_members', isset($humanitarianCase) ? $humanitarianCase->familyMembers->toArray() : []);
$caseIncome = old('case_income', isset($humanitarianCase) ? optional($humanitarianCase->caseIncome)->toArray() : []);
$caseExpense = old('case_expense', isset($humanitarianCase) ? optional($humanitarianCase->caseExpense)->toArray() : []);
$caseHomeDescription = old('case_home_description', isset($humanitarianCase) ? optional($humanitarianCase->caseHomeDescription)->toArray() : []);
$caseNeed = old('case_need', isset($humanitarianCase) ? optional($humanitarianCase->caseNeed)->toArray() : []);
@endphp

<div class="row g-3">
    <div class="col-12 col-md-6">
        <label class="form-label" for="name">الاسم</label>
        <input id="name" type="text" name="name" value="{{ old('name', $humanitarianCase->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="phone">رقم الجوال</label>
        <input id="phone" type="text" name="phone" value="{{ old('phone', $humanitarianCase->phone ?? '') }}" class="form-control @error('phone') is-invalid @enderror" required maxlength="11" inputmode="numeric" pattern="0[0-9]{10}" title="يجب أن يكون 11 رقمًا ويبدأ بـ 0">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="national_id">رقم الهوية</label>
        <input id="national_id" type="text" name="national_id" value="{{ old('national_id', $humanitarianCase->national_id ?? '') }}" class="form-control @error('national_id') is-invalid @enderror" required maxlength="14" inputmode="numeric" pattern="[2-9][0-9]{13}" title="يجب أن يكون 14 رقمًا ويبدأ بـ 2 أو أعلى">
        @error('national_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="district_id">المنطقة</label>
        <select id="district_id" name="district_id" class="form-select @error('district_id') is-invalid @enderror" required>
            <option value="">اختر المنطقة</option>
            @foreach($districts as $district)
            <option value="{{ $district->id }}" {{ (string) old('district_id', $humanitarianCase->district_id ?? '') === (string) $district->id ? 'selected' : '' }}>{{ $district->title }}</option>
            @endforeach
        </select>
        @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="referrer_id">الدليل</label>
        <select id="referrer_id" name="referrer_id" class="form-select @error('referrer_id') is-invalid @enderror">
            <option value="">اختر المنطقة أولاً</option>
        </select>
        @error('referrer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="type">نوع الحالة</label>
        <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
            @foreach(\App\Models\HumanitarianCase::typeOptions() as $value => $label)
            <option value="{{ $value }}" {{ (string) old('type', $humanitarianCase->type ?? '') === (string) $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label" for="research_team">فريق البحث</label>
        <input id="research_team" type="text" name="research_team" value="{{ old('research_team', $humanitarianCase->research_team ?? '') }}" class="form-control @error('research_team') is-invalid @enderror">
        <div class="form-text">اكتب أسماء أفراد فريق البحث (اختياري).</div>
        @error('research_team')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label" for="notes">ملاحظات</label>
        <textarea id="notes" name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $humanitarianCase->notes ?? '') }}</textarea>
        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label" for="attachments">المرفقات</label>
        <input id="attachments" type="file" name="attachments[]" class="form-control @error('attachments') is-invalid @enderror @error('attachments.*') is-invalid @enderror" multiple accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx,image/*,application/pdf">
        <div class="form-text">يمكنك اختيار عدة ملفات (PDF، صور، Word). الحد الأقصى 10 ميجابايت لكل ملف.</div>
        @error('attachments')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        @foreach ($errors->get('attachments.*') as $message)
        <div class="invalid-feedback d-block">{{ $message }}</div>
        @endforeach
    </div>
</div>

<div class="accordion mt-4" id="caseDetailsAccordion">
    <div class="accordion-item">
        <h2 class="accordion-header" id="familyMembersHeading">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#familyMembersCollapse" aria-expanded="false" aria-controls="familyMembersCollapse">
                أفراد الأسرة
            </button>
        </h2>
        <div id="familyMembersCollapse" class="accordion-collapse collapse" aria-labelledby="familyMembersHeading" data-bs-parent="#caseDetailsAccordion">
            <div class="accordion-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <tbody id="familyMembersTableBody">
                            @if(count($familyMembers) > 0)

                            @foreach($familyMembers as $member)
                            <!-- First Row -->
                            <tr>
                                <td>
                                    <label class="form-label">الاسم</label>
                                    <input type="text"
                                        name="family_members[{{ $loop->index }}][name]"
                                        value="{{ $member['name'] ?? '' }}"
                                        class="form-control">
                                </td>

                                <td>
                                    <label class="form-label">العلاقة</label>
                                    <input type="text"
                                        name="family_members[{{ $loop->index }}][relation]"
                                        value="{{ $member['relation'] ?? '' }}"
                                        class="form-control">
                                </td>

                                <td>
                                    <label class="form-label">العمر</label>
                                    <input type="number"
                                        min="0"
                                        max="150"
                                        name="family_members[{{ $loop->index }}][age]"
                                        value="{{ $member['age'] ?? '' }}"
                                        class="form-control">
                                </td>

                                <td>
                                    <label class="form-label">التعليم</label>
                                    <input type="text"
                                        name="family_members[{{ $loop->index }}][education]"
                                        value="{{ $member['education'] ?? '' }}"
                                        class="form-control">
                                </td>
                            </tr>

                            <!-- Second Row -->
                            <tr>
                                <td>
                                    <label class="form-label">الحالة الصحية</label>
                                    <input type="text"
                                        name="family_members[{{ $loop->index }}][health_status]"
                                        value="{{ $member['health_status'] ?? '' }}"
                                        class="form-control">
                                </td>

                                <td>
                                    <label class="form-label">الحالة الاجتماعية</label>
                                    <input type="text"
                                        name="family_members[{{ $loop->index }}][marital_status]"
                                        value="{{ $member['marital_status'] ?? '' }}"
                                        class="form-control">
                                </td>

                                <td>
                                    <label class="form-label">متوسط الدخل</label>
                                    <input type="text"
                                        name="family_members[{{ $loop->index }}][average_income]"
                                        value="{{ $member['average_income'] ?? '' }}"
                                        class="form-control">
                                </td>

                                <td>
                                    <label class="form-label">الوظيفة</label>
                                    <div class="d-flex gap-2">
                                        <input type="text"
                                            name="family_members[{{ $loop->index }}][job]"
                                            value="{{ $member['job'] ?? '' }}"
                                            class="form-control">

                                        <button type="button"
                                            class="btn btn-outline-danger remove-family-member">
                                            إزالة
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="family-separator">
                                <td colspan="4">
                                    <hr class="my-4">
                                </td>
                            </tr>
                            @endforeach

                            @else

                            <!-- First Row -->
                            <tr>
                                <td><label>الاسم</label><input type="text" name="family_members[0][name]" class="form-control"></td>
                                <td><label>العلاقة</label><input type="text" name="family_members[0][relation]" class="form-control"></td>
                                <td><label>العمر</label><input type="number" min="0" max="150" name="family_members[0][age]" class="form-control"></td>
                                <td><label>التعليم</label><input type="text" name="family_members[0][education]" class="form-control"></td>
                            </tr>

                            <!-- Second Row -->
                            <tr>
                                <td><label>الحالة الصحية</label><input type="text" name="family_members[0][health_status]" class="form-control"></td>
                                <td><label>الحالة الاجتماعية</label><input type="text" name="family_members[0][marital_status]" class="form-control"></td>
                                <td><label>متوسط الدخل</label><input type="text" name="family_members[0][average_income]" class="form-control"></td>
                                <td>
                                    <label>الوظيفة</label>
                                    <div class="d-flex gap-2">
                                        <input type="text" name="family_members[0][job]" class="form-control">
                                        <button type="button" class="btn btn-outline-danger remove-family-member">
                                            إزالة
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr class="family-separator">
                                <td colspan="4">
                                    <hr class="my-4">
                                </td>
                            </tr>

                            @endif
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" id="addFamilyMember">أضف فردًا</button>
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="incomeHeading">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#incomeCollapse" aria-expanded="false" aria-controls="incomeCollapse">
                الدخل
            </button>
        </h2>
        <div id="incomeCollapse" class="accordion-collapse collapse" aria-labelledby="incomeHeading" data-bs-parent="#caseDetailsAccordion">
            <div class="accordion-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="job_income">دخل العمل</label>
                        <input id="job_income" type="number" step="0.01" min="0" name="case_income[job_income]" value="{{ old('case_income.job_income', $caseIncome['job_income'] ?? '') }}" class="form-control">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="pension_income">دخل المعاش</label>
                        <input id="pension_income" type="number" step="0.01" min="0" name="case_income[pension_income]" value="{{ old('case_income.pension_income', $caseIncome['pension_income'] ?? '') }}" class="form-control">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="charity_income">دخل المساعدات</label>
                        <input id="charity_income" type="number" step="0.01" min="0" name="case_income[charity_income]" value="{{ old('case_income.charity_income', $caseIncome['charity_income'] ?? '') }}" class="form-control">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="other_income">دخل آخر</label>
                        <input id="other_income" type="number" step="0.01" min="0" name="case_income[other_income]" value="{{ old('case_income.other_income', $caseIncome['other_income'] ?? '') }}" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="expensesHeading">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#expensesCollapse" aria-expanded="false" aria-controls="expensesCollapse">
                المصاريف
            </button>
        </h2>
        <div id="expensesCollapse" class="accordion-collapse collapse" aria-labelledby="expensesHeading" data-bs-parent="#caseDetailsAccordion">
            <div class="accordion-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="home_rent">إيجار المنزل</label>
                        <input id="home_rent" type="number" step="0.01" min="0" name="case_expense[home_rent]" value="{{ old('case_expense.home_rent', $caseExpense['home_rent'] ?? '') }}" class="form-control">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="school_expenses">مصاريف المدرسة</label>
                        <input id="school_expenses" type="number" step="0.01" min="0" name="case_expense[school_expenses]" value="{{ old('case_expense.school_expenses', $caseExpense['school_expenses'] ?? '') }}" class="form-control">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="utilities">فواتير</label>
                        <input id="utilities" type="number" step="0.01" min="0" name="case_expense[utilities]" value="{{ old('case_expense.utilities', $caseExpense['utilities'] ?? '') }}" class="form-control">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="medicine">الدواء</label>
                        <input id="medicine" type="number" step="0.01" min="0" name="case_expense[medicine]" value="{{ old('case_expense.medicine', $caseExpense['medicine'] ?? '') }}" class="form-control">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="nutrition">التغذية</label>
                        <input id="nutrition" type="number" step="0.01" min="0" name="case_expense[nutrition]" value="{{ old('case_expense.nutrition', $caseExpense['nutrition'] ?? '') }}" class="form-control">
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="other_expenses">مصروفات أخرى</label>
                        <input id="other_expenses" type="number" step="0.01" min="0" name="case_expense[other_expenses]" value="{{ old('case_expense.other_expenses', $caseExpense['other_expenses'] ?? '') }}" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="homeDescriptionHeading">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#homeDescriptionCollapse" aria-expanded="false" aria-controls="homeDescriptionCollapse">
                وصف المنزل
            </button>
        </h2>
        <div id="homeDescriptionCollapse" class="accordion-collapse collapse" aria-labelledby="homeDescriptionHeading" data-bs-parent="#caseDetailsAccordion">
            <div class="accordion-body">
                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="rooms_count">عدد الغرف</label>
                        <input id="rooms_count" type="number" min="0" name="case_home_description[rooms_count]" value="{{ old('case_home_description.rooms_count', $caseHomeDescription['rooms_count'] ?? '') }}" class="form-control">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="clean_water">ماء نظيف</label>
                        <div class="form-check">
                            <input id="clean_water" type="checkbox" name="case_home_description[clean_water]" value="1" class="form-check-input" {{ old('case_home_description.clean_water', $caseHomeDescription['clean_water'] ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="clean_water">نعم</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="roof_condition">حالة السقف</label>
                        <input id="roof_condition" type="text" name="case_home_description[roof_condition]" value="{{ old('case_home_description.roof_condition', $caseHomeDescription['roof_condition'] ?? '') }}" class="form-control">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="flooring_type">نوع الأرضية</label>
                        <input id="flooring_type" type="text" name="case_home_description[flooring_type]" value="{{ old('case_home_description.flooring_type', $caseHomeDescription['flooring_type'] ?? '') }}" class="form-control">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="has_tv">تلفاز</label>
                        <div class="form-check">
                            <input id="has_tv" type="checkbox" name="case_home_description[has_tv]" value="1" class="form-check-input" {{ old('case_home_description.has_tv', $caseHomeDescription['has_tv'] ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_tv">نعم</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="has_washing_machine">غسالة</label>
                        <div class="form-check">
                            <input id="has_washing_machine" type="checkbox" name="case_home_description[has_washing_machine]" value="1" class="form-check-input" {{ old('case_home_description.has_washing_machine', $caseHomeDescription['has_washing_machine'] ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_washing_machine">نعم</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="has_gas_stove">موقد غاز</label>
                        <div class="form-check">
                            <input id="has_gas_stove" type="checkbox" name="case_home_description[has_gas_stove]" value="1" class="form-check-input" {{ old('case_home_description.has_gas_stove', $caseHomeDescription['has_gas_stove'] ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_gas_stove">نعم</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="has_fan">مروحة</label>
                        <div class="form-check">
                            <input id="has_fan" type="checkbox" name="case_home_description[has_fan]" value="1" class="form-check-input" {{ old('case_home_description.has_fan', $caseHomeDescription['has_fan'] ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_fan">نعم</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="has_phone">هاتف</label>
                        <div class="form-check">
                            <input id="has_phone" type="checkbox" name="case_home_description[has_phone]" value="1" class="form-check-input" {{ old('case_home_description.has_phone', $caseHomeDescription['has_phone'] ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_phone">نعم</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="has_fridge">ثلاجة</label>
                        <div class="form-check">
                            <input id="has_fridge" type="checkbox" name="case_home_description[has_fridge]" value="1" class="form-check-input" {{ old('case_home_description.has_fridge', $caseHomeDescription['has_fridge'] ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="has_fridge">نعم</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="accordion-item">
        <h2 class="accordion-header" id="needsHeading">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#needsCollapse" aria-expanded="false" aria-controls="needsCollapse">
                الاحتياجات
            </button>
        </h2>
        <div id="needsCollapse" class="accordion-collapse collapse" aria-labelledby="needsHeading" data-bs-parent="#caseDetailsAccordion">
            <div class="accordion-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="requested_needs">الاحتياجات المطلوبة</label>
                        <textarea id="requested_needs" name="case_need[requested_needs]" rows="3" class="form-control">{{ old('case_need.requested_needs', $caseNeed['requested_needs'] ?? '') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="recommended_needs">الاحتياجات الموصى بها</label>
                        <textarea id="recommended_needs" name="case_need[recommended_needs]" rows="3" class="form-control">{{ old('case_need.recommended_needs', $caseNeed['recommended_needs'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="familyMemberRowTemplate">

    <!-- First Row -->
    <tr>
        <td>
            <label class="form-label">الاسم</label>
            <input type="text"
                name="family_members[__INDEX__][name]"
                class="form-control">
        </td>

        <td>
            <label class="form-label">العلاقة</label>
            <input type="text"
                name="family_members[__INDEX__][relation]"
                class="form-control">
        </td>

        <td>
            <label class="form-label">العمر</label>
            <input type="number"
                min="0"
                max="150"
                name="family_members[__INDEX__][age]"
                class="form-control">
        </td>

        <td>
            <label class="form-label">التعليم</label>
            <input type="text"
                name="family_members[__INDEX__][education]"
                class="form-control">
        </td>
    </tr>

    <!-- Second Row -->
    <tr>
        <td>
            <label class="form-label">الحالة الصحية</label>
            <input type="text"
                name="family_members[__INDEX__][health_status]"
                class="form-control">
        </td>

        <td>
            <label class="form-label">الحالة الاجتماعية</label>
            <input type="text"
                name="family_members[__INDEX__][marital_status]"
                class="form-control">
        </td>

        <td>
            <label class="form-label">متوسط الدخل</label>
            <input type="text"
                name="family_members[__INDEX__][average_income]"
                class="form-control">
        </td>

        <td>
            <label class="form-label">الوظيفة</label>

            <div class="d-flex gap-2">
                <input type="text"
                    name="family_members[__INDEX__][job]"
                    class="form-control">

                <button type="button"
                    class="btn btn-outline-danger remove-family-member">
                    إزالة
                </button>
            </div>
        </td>
    </tr>

    <tr class="family-separator">
        <td colspan="4">
            <hr class="my-4">
        </td>
    </tr>
</template>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary">حفظ</button>
    <a href="{{ route('humanitarian-cases.index') }}" class="btn btn-light">إلغاء</a>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const familyMembersBody = document.getElementById('familyMembersTableBody');
        const addButton = document.getElementById('addFamilyMember');
        const template = document.getElementById('familyMemberRowTemplate');
        const referrers = @json($referrersJson);
        const districtSelect = document.getElementById('district_id');
        const referrerSelect = document.getElementById('referrer_id');
        const initialReferrer = @json(old('referrer_id', isset($humanitarianCase) ? $humanitarianCase -> referrer_id : ''));

        function updateReferrerOptions() {
            if (!districtSelect || !referrerSelect) return;

            const selectedDistrict = districtSelect.value;
            const selectedReferrer = String(referrerSelect.value || initialReferrer);
            referrerSelect.innerHTML = '';

            if (!selectedDistrict) {
                referrerSelect.disabled = true;
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'اختر المنطقة أولاً';
                referrerSelect.appendChild(option);
                return;
            }

            referrerSelect.disabled = false;
            const blankOption = document.createElement('option');
            blankOption.value = '';
            blankOption.textContent = 'بدون دليل';
            referrerSelect.appendChild(blankOption);

            const filtered = referrers.filter(item => String(item.district_id) === String(selectedDistrict));
            if (filtered.length === 0) {
                const option = document.createElement('option');
                option.value = '';
                option.disabled = true;
                option.textContent = 'لا يوجد دليل متاح';
                referrerSelect.appendChild(option);
                return;
            }

            filtered.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                if (String(item.id) === String(selectedReferrer)) {
                    option.selected = true;
                }
                referrerSelect.appendChild(option);
            });
        }

        if (districtSelect && referrerSelect) {
            districtSelect.addEventListener('change', updateReferrerOptions);
            updateReferrerOptions();
        }

        if (!familyMembersBody || !addButton || !template) return;

        // Count existing members (2 rows per member)
        let index = familyMembersBody.querySelectorAll('tr.family-separator').length;
        addButton.addEventListener('click', function() {

            let html = template.innerHTML.replace(/__INDEX__/g, index);

            familyMembersBody.insertAdjacentHTML('beforeend', html);

            index++;
        });

        familyMembersBody.addEventListener('click', function(e) {

            const btn = e.target.closest('.remove-family-member');
            if (!btn) return;

            const secondRow = btn.closest('tr');
            if (!secondRow) return;

            const firstRow = secondRow.previousElementSibling;
            const separatorRow = secondRow.nextElementSibling;

            if (firstRow) firstRow.remove();
            secondRow.remove();

            if (separatorRow && separatorRow.classList.contains('family-separator')) {
                separatorRow.remove();
            }
        });

    });
</script>
@endpush