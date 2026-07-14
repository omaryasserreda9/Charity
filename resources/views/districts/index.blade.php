@extends('layouts.app')

@section('title', 'المناطق')
@section('page_title', 'المناطق')

@section('content')
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2>المناطق</h2>
                <p>إدارة المناطق المستخدمة في الحملات.</p>
            </div>
            <a href="{{ route('districts.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus ms-1"></i>
                إضافة منطقة
            </a>
        </div>

        <form method="GET" action="{{ route('districts.index') }}" class="filter-bar mb-3">
            <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="بحث باسم المنطقة">
            <button type="submit" class="btn btn-primary">بحث</button>
            <a href="{{ route('districts.index') }}" class="btn btn-light">إعادة ضبط</a>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>عدد الحملات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($districts as $district)
                        <tr>
                            <td>{{ $district->id }}</td>
                            <td>{{ $district->title }}</td>
                            <td>{{ $district->campaigns_count }}</td>
                            <td>
                                <div class="table-actions">
                                    <a class="btn btn-sm btn-light" href="{{ route('districts.show', $district) }}">عرض</a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('districts.edit', $district) }}">تعديل</a>
                                    <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteDistrict{{ $district->id }}">حذف</button>
                                </div>

                                <div class="modal fade" id="deleteDistrict{{ $district->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">حذف المنطقة</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                            </div>
                                            <div class="modal-body">لا يمكن حذف المنطقة إذا كانت هناك حملات مرتبطة بها.</div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                                                <form method="POST" action="{{ route('districts.destroy', $district) }}">
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
                            <td colspan="4" class="text-center text-muted py-4">لا توجد مناطق حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $districts->links() }}
    </section>
@endsection
