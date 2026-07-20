@extends('layouts.app')

@section('title', 'المتبرعون')
@section('page_title', 'المتبرعون')

@section('content')
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2>المتبرعون</h2>
                <p>إدارة المتبرعين والجهات المانحة.</p>
            </div>
            @can('donors.add')
            <a href="{{ route('donors.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus ms-1"></i>
                إضافة متبرع
            </a>
            @endcan
        </div>

        <form method="GET" action="{{ route('donors.index') }}" class="filter-bar mb-3">
            <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="بحث بالاسم أو رقم الهاتف">
            <button type="submit" class="btn btn-primary">بحث</button>
            <a href="{{ route('donors.index') }}" class="btn btn-light">إعادة ضبط</a>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>رقم الهاتف</th>
                        <th>عمليات الميزانية</th>
                        <th>عمليات المخزون</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donors as $donor)
                        <tr>
                            <td>{{ $donor->id }}</td>
                            <td>{{ $donor->name }}</td>
                            <td>{{ $donor->phone ?? '—' }}</td>
                            <td>{{ $donor->budget_operations_count }}</td>
                            <td>{{ $donor->inventory_operations_count }}</td>
                            <td>
                                <div class="table-actions">
                                    <a class="btn btn-sm btn-light" href="{{ route('donors.show', $donor) }}">عرض</a>
                                    @can('donors.edit')
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('donors.edit', $donor) }}">تعديل</a>
                                    @endcan
                                    @can('donors.delete')
                                    <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteDonor{{ $donor->id }}">حذف</button>
                                    @endcan
                                </div>

                                <div class="modal fade" id="deleteDonor{{ $donor->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">حذف المتبرع</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                            </div>
                                            <div class="modal-body">سيتم حذف المتبرع فقط، وستبقى العمليات المرتبطة باسمه دون ربط.</div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                                                @can('donors.delete')
                                                <form method="POST" action="{{ route('donors.destroy', $donor) }}">
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
                            <td colspan="6" class="text-center text-muted py-4">لا يوجد متبرعون حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $donors->links() }}
    </section>
@endsection
