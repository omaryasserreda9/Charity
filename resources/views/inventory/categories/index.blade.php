@extends('layouts.app')

@section('title', 'بنود المخزون')
@section('page_title', 'بنود المخزون')

@section('content')
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2>البنود</h2>
                <p>إدارة بنود عمليات المخزون.</p>
            </div>
            <a href="{{ route('inventory-categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus ms-1"></i>
                إضافة بند
            </a>
        </div>

        <form method="GET" action="{{ route('inventory-categories.index') }}" class="filter-bar mb-3">
            <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="بحث باسم البند">
            <button type="submit" class="btn btn-primary">بحث</button>
            <a href="{{ route('inventory-categories.index') }}" class="btn btn-light">إعادة ضبط</a>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>عدد العمليات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->title }}</td>
                            <td>{{ $category->operations_count }}</td>
                            <td>
                                <div class="table-actions">
                                    <a class="btn btn-sm btn-light" href="{{ route('inventory-categories.show', $category) }}">عرض</a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('inventory-categories.edit', $category) }}">تعديل</a>
                                    <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteCategory{{ $category->id }}">حذف</button>
                                </div>

                                <div class="modal fade" id="deleteCategory{{ $category->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">حذف البند</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                            </div>
                                            <div class="modal-body">سيتم حذف البند فقط، وستظهر عملياته باسم "بدون بند".</div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                                                <form method="POST" action="{{ route('inventory-categories.destroy', $category) }}">
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
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">لا توجد بنود حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $categories->links() }}
    </section>
@endsection
