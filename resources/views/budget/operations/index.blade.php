@extends('layouts.app')

@section('title', 'عمليات الميزانية')
@section('page_title', 'عمليات الميزانية')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <span class="stat-icon text-bg-success"><i class="fa-solid fa-wallet"></i></span>
            <div>
                <p>الرصيد الحالي</p>
                <strong>{{ number_format($currentBalance, 2) }}</strong>
            </div>
        </div>
    </div>
</div>

<section class="panel">
    <div class="panel-header">
        <div>
            <h2>سجل العمليات</h2>
            <p>بحث وتصفية حسب البند والتاريخ.</p>
        </div>
        @can('budget_operations.add')
        <a href="{{ route('budget-operations.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-plus ms-1"></i>
            إضافة عملية
        </a>
        @endcan
    </div>

    <form method="GET" action="{{ route('budget-operations.index') }}" class="filter-bar mb-3">
        <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="بحث باسم المتبرع أو البند">
        <select name="budget_category_id" class="form-select">
            <option value="">كل البنود</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ (string) ($filters['budget_category_id'] ?? '') === (string) $category->id ? 'selected' : '' }}>
                {{ $category->title }}
            </option>
            @endforeach
        </select>
        <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="form-control" aria-label="من تاريخ">
        <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="form-control" aria-label="إلى تاريخ">
        <button type="submit" class="btn btn-primary">تصفية</button>
        <a href="{{ route('budget-operations.index') }}" class="btn btn-light">إعادة ضبط</a>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>النوع</th>
                    <th>البند</th>
                    <th>اسم المتبرع / الجهة</th>
                    <th>القيمة</th>
                    <th>التاريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($operations as $operation)
                <tr>
                    <td>{{ $operation->id }}</td>
                    <td>
                        <span class="badge rounded-pill {{ $operation->type === 'in' ? 'text-bg-success' : 'text-bg-danger' }}">
                            {{ $operation->type === 'in' ? 'وارد' : 'صادر' }}
                        </span>
                    </td>
                    <td>{{ optional($operation->category)->title ?? 'بدون بند' }}</td>
                    <td>{{ $operation->donor_name }}</td>
                    <td>{{ number_format((float) $operation->quantity, 2) }}</td>
                    <td>{{ optional($operation->operation_date)->format('Y-m-d') }}</td>
                    <td>
                        <div class="table-actions">
                            <a class="btn btn-sm btn-light" href="{{ route('budget-operations.show', $operation) }}">عرض</a>
                            @can('budget_operations.edit')
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('budget-operations.edit', $operation) }}">تعديل</a>
                            @endcan
                            @can('budget_operations.delete')
                            <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteOperation{{ $operation->id }}">حذف</button>
                            @endcan
                        </div>

                        <div class="modal fade" id="deleteOperation{{ $operation->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">حذف العملية</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                    </div>
                                    <div class="modal-body">هل تريد حذف هذه العملية من سجل الميزانية؟</div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                                        @can('budget_operations.delete')
                                        <form method="POST" action="{{ route('budget-operations.destroy', $operation) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">تأكيد الحذف</button>
                                        </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">لا توجد عمليات مطابقة.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $operations->links() }}
</section>
@endsection