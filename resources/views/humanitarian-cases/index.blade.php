@extends('layouts.app')

@section('title', 'الحالات الإنسانية')
@section('page_title', 'الحالات الإنسانية')

@section('content')
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2>سجل الحالات</h2>
                <p>بحث وتصفية حسب نوع الحالة.</p>
            </div>
            <a href="{{ route('humanitarian-cases.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus ms-1"></i>
                إضافة حالة
            </a>
        </div>

        <form method="GET" action="{{ route('humanitarian-cases.index') }}" class="filter-bar mb-3">
            <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="بحث بالاسم أو الجوال أو رقم الهوية أو المنطقة">
            <select name="type" class="form-select">
                <option value="">كل الأنواع</option>
                @foreach(\App\Models\HumanitarianCase::typeOptions() as $value => $label)
                    <option value="{{ $value }}" {{ (string) ($filters['type'] ?? '') === (string) $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">تصفية</button>
            <a href="{{ route('humanitarian-cases.index') }}" class="btn btn-light">إعادة ضبط</a>
        </form>

        <div class="table-responsive">
            <table id="humanitarianCasesTable" class="table table-hover align-middle w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>الجوال</th>
                        <th>رقم الهوية</th>
                        <th>المنطقة</th>
                        <th>النوع</th>
                        <th>المرفقات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cases as $case)
                        <tr>
                            <td>{{ $case->id }}</td>
                            <td>{{ $case->name }}</td>
                            <td>{{ $case->phone }}</td>
                            <td>{{ $case->national_id }}</td>
                            <td>{{ $case->area ?: '—' }}</td>
                            <td>
                                <span class="badge rounded-pill {{ $case->type === 'mine' ? 'text-bg-warning' : 'text-bg-info' }}">
                                    {{ $case->typeLabel() }}
                                </span>
                            </td>
                            <td>{{ $case->files_count }}</td>
                            <td>
                                <div class="table-actions">
                                    <a class="btn btn-sm btn-light" href="{{ route('humanitarian-cases.show', $case) }}">عرض</a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('humanitarian-cases.edit', $case) }}">تعديل</a>
                                    <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteCase{{ $case->id }}">حذف</button>
                                </div>

                                <div class="modal fade" id="deleteCase{{ $case->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">حذف الحالة</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                            </div>
                                            <div class="modal-body">هل تريد حذف هذه الحالة وجميع مرفقاتها؟</div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                                                <form method="POST" action="{{ route('humanitarian-cases.destroy', $case) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">تأكيد الحذف</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    new DataTable('#humanitarianCasesTable', {
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/ar.json',
            emptyTable: 'لا توجد حالات مطابقة.'
        },
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        columnDefs: [{ orderable: false, targets: -1 }]
    });
</script>
@endpush
