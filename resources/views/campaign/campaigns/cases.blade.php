@extends('layouts.app')

@section('title', 'الحالات المرتبطة')
@section('page_title', 'الحالات المرتبطة')

@section('content')
<section class="panel">
    <div class="panel-header">
        <div>
            <h2>{{ $campaign->title }}</h2>
            <p>اختر الحالات الإنسانية المرتبطة بهذه الحملة.</p>
        </div>
            <div class="d-flex gap-2">
            <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-light btn-sm">عودة للحملة</a>
            @can('campaigns.view')
            <a href="{{ route('campaigns.cases.export', $campaign) }}?{{ http_build_query($filters) }}" class="btn btn-outline-secondary btn-sm">تنزيل Excel</a>
            @endcan
        </div>
    </div>

    <form method="GET" action="{{ route('campaigns.cases', $campaign) }}" class="filter-bar mb-3">
        <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="بحث بالاسم أو الجوال أو رقم الهوية أو المنطقة">
        <select name="district_id" class="form-select">
            <option value="">كل المناطق</option>
            @foreach($districts as $district)
            <option value="{{ $district->id }}" {{ (string) ($filters['district_id'] ?? '') === (string) $district->id ? 'selected' : '' }}>{{ $district->title }}</option>
            @endforeach
        </select>

        <select name="referrer_id" class="form-select">
            <option value="">كل الدلائل</option>
            @foreach($referrers as $referrer)
            <option value="{{ $referrer->id }}"
                {{ (string) ($filters['referrer_id'] ?? '') === (string) $referrer->id ? 'selected' : '' }}>
                {{ $referrer->name }}
            </option>
            @endforeach
        </select>

        <select name="type" class="form-select">
            <option value="">كل الأنواع</option>
            @foreach(\App\Models\HumanitarianCase::typeOptions() as $value => $label)
            <option value="{{ $value }}"
                {{ (string) ($filters['type'] ?? '') === (string) $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">تصفية</button>
        <a href="{{ route('campaigns.cases', $campaign) }}" class="btn btn-light">إعادة ضبط</a>
    </form>

    <form method="POST" action="{{ route('campaigns.cases.sync', $campaign) }}">
        @csrf
        @method('PUT')

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th style="width: 48px;">
                            <input type="checkbox" class="form-check-input" id="selectAllCases" aria-label="تحديد الكل">
                        </th>
                        <th>الاسم</th>
                        <th>رقم الجوال</th>
                        <th>رقم الهوية</th>
                        <th>المنطقة</th>
                        <th>الدليل</th>
                        <th>عدد أفراد العائلة</th>
                        <th>النوع</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($humanitarianCases as $case)
                    <tr>
                        <td>
                            <input
                                type="checkbox"
                                class="form-check-input case-checkbox"
                                name="humanitarian_case_ids[]"
                                value="{{ $case->id }}"
                                {{ in_array($case->id, old('humanitarian_case_ids', $selectedCaseIds), true) ? 'checked' : '' }}
                                </td>
                        <td>{{ $case->name }}</td>
                        <td>{{ $case->phone }}</td>
                        <td>{{ $case->national_id }}</td>
                        <td>{{ $case->district->title ?? $case->area ?: '—' }}</td>
                        <td>{{ $case->referrer->name ?? '—' }}</td>
                        <td>{{ $case->family_members_count + 1 }}</td>

                        <td>{{ $case->typeLabel() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">لا توجد حالات إنسانية مسجلة.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($humanitarianCases->isNotEmpty())
        <div class="d-flex gap-2 mt-3">
            @can('campaigns.edit')
            <button type="submit" class="btn btn-primary">حفظ</button>
            @endcan
            <a href="{{ route('campaigns.index') }}" class="btn btn-light">إلغاء</a>
        </div>
        @endif
    </form>
</section>

<div class="modal fade" id="markDoneModal" tabindex="-1" aria-labelledby="markDoneModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="markDoneModalLabel">تحديث حالة الحملة</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body">تم حفظ الحالات بنجاح. هل تريد تغيير حالة الحملة إلى <strong>منجزة</strong>؟</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">لا، إبقاء قيد التنفيذ</button>
                @can('campaigns.edit')
                <form method="POST" action="{{ route('campaigns.mark-done', $campaign) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">نعم، تحديد كمنجزة</button>
                </form>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function() {
        const selectAll = document.getElementById('selectAllCases');
        const checkboxes = document.querySelectorAll('.case-checkbox');

        if (selectAll && checkboxes.length) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach((checkbox) => {
                    checkbox.checked = selectAll.checked;
                });
            });

            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', function() {
                    selectAll.checked = Array.from(checkboxes).every((item) => item.checked);
                });
            });

            selectAll.checked = Array.from(checkboxes).every((item) => item.checked);
        }

        @if(session('prompt_mark_done'))
        const markDoneModal = document.getElementById('markDoneModal');
        if (markDoneModal) {
            new bootstrap.Modal(markDoneModal).show();
        }
        @endif
    })();
</script>
@endpush