@extends('layouts.app')

@section('title', 'الدليل')
@section('page_title', 'الدليل')

@section('content')
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2>الدليل</h2>
                <p>إدارة الدلائل المرتبطة بالمناطق.</p>
            </div>
            <a href="{{ route('case-referrers.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus ms-1"></i>
                إضافة دليل
            </a>
        </div>

        <form method="GET" action="{{ route('case-referrers.index') }}" class="filter-bar mb-3">
            <input type="search" name="search" value="{{ $search }}" class="form-control" placeholder="بحث باسم الدليل">
            <select name="district_id" class="form-select">
                <option value="">كل المناطق</option>
                @foreach($districts as $district)
                    <option value="{{ $district->id }}" {{ (string) $districtId === (string) $district->id ? 'selected' : '' }}>{{ $district->title }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">بحث</button>
            <a href="{{ route('case-referrers.index') }}" class="btn btn-light">إعادة ضبط</a>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>المنطقة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referrers as $referrer)
                        <tr>
                            <td>{{ $referrer->id }}</td>
                            <td>{{ $referrer->name }}</td>
                            <td>{{ $referrer->district->title ?? '—' }}</td>
                            <td>
                                <div class="table-actions">
                                    <a class="btn btn-sm btn-light" href="{{ route('case-referrers.show', $referrer) }}">عرض</a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('case-referrers.edit', $referrer) }}">تعديل</a>
                                    <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteReferrer{{ $referrer->id }}">حذف</button>
                                </div>

                                <div class="modal fade" id="deleteReferrer{{ $referrer->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">حذف الدليل</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                            </div>
                                            <div class="modal-body">هل تريد حذف هذا الدليل؟ سيصبح الحقل فارغًا في الحالات الإنسانية المرتبطة به.</div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                                                <form method="POST" action="{{ route('case-referrers.destroy', $referrer) }}">
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
                            <td colspan="4" class="text-center text-muted py-4">لا توجد دلائل حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $referrers->links() }}
    </section>
@endsection
