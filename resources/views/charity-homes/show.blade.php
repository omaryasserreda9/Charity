@extends('layouts.app')

@section('title', $charityHome->title)
@section('page_title', $charityHome->title)

@section('content')
    <section class="panel">
        <div class="panel-header d-flex justify-content-between align-items-center">
            <div>
                <h2>{{ $charityHome->title }}</h2>
                <p>عرض تفاصيل بيت الجمعية والمستخدمين المرتبطين به.</p>
            </div>
            <div>
                @can('charity_homes.edit')
                <a href="{{ route('charity-homes.edit', $charityHome) }}" class="btn btn-outline-primary">تعديل</a>
                @endcan
                @can('charity_homes.delete')
                <form action="{{ route('charity-homes.destroy', $charityHome) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل متأكد من حذف بيت الجمعية؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>

        <div class="mb-4">
            <strong>عدد المستخدمين:</strong> {{ $charityHome->users->count() }}
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الأدوار</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($charityHome->users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">لا يوجد مستخدمين مرتبطين بهذا البيت.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
