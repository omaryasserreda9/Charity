@extends('layouts.app')

@section('title', 'المستخدمون')
@section('page_title', 'المستخدمون')

@section('content')
    <section class="panel">
        <div class="panel-header d-flex justify-content-between align-items-center">
            <div>
                <h2>قائمة المستخدمين</h2>
                <p>عرض جميع المستخدمين وإدارة حالاتهم وأدوارهم.</p>
            </div>
            @can('users.add')
            <a href="{{ route('users.create') }}" class="btn btn-primary">إضافة مستخدم</a>
            @endcan
        </div>

        <form method="GET" action="{{ route('users.index') }}" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="بحث بالاسم أو البريد الإلكتروني" value="{{ $search }}">
                <button type="submit" class="btn btn-outline-secondary">بحث</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>بيت الجمعية</th>
                        <th>الحالة</th>
                        <th>الأدوار</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->charityHome->title ?? '—' }}</td>
                            <td>{{ $user->active ? 'مفعل' : 'معطل' }}</td>
                            <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                            <td>
                                <div class="table-actions">
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-light">عرض</a>
                                    @can('users.edit')
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                                    @endcan
                                    @can('users.delete')
                                    @if($user->id !== 1)
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل متأكد من حذف المستخدم؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                        </form>
                                    @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">لا يوجد مستخدمين حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </section>
@endsection
