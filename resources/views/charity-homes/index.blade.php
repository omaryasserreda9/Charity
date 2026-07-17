@extends('layouts.app')

@section('title', 'بيوت الجمعيات')
@section('page_title', 'بيوت الجمعيات')

@section('content')
    <section class="panel">
        <div class="panel-header d-flex justify-content-between align-items-center">
            <div>
                <h2>قائمة بيوت الجمعيات</h2>
                <p>إدارة بيوت الجمعيات والاطلاع على عدد المستخدمين لكل بيت.</p>
            </div>
            @can('charity_homes.add')
            <a href="{{ route('charity-homes.create') }}" class="btn btn-primary">إضافة بيت جمعية</a>
            @endcan
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>المستخدمون</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($charityHomes as $home)
                        <tr>
                            <td>{{ $home->id }}</td>
                            <td>{{ $home->title }}</td>
                            <td>{{ $home->users_count }}</td>
                            <td>
                                <a href="{{ route('charity-homes.show', $home) }}" class="btn btn-sm btn-light">عرض</a>
                                @can('charity_homes.edit')
                                <a href="{{ route('charity-homes.edit', $home) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                                @endcan
                                @can('charity_homes.delete')
                                <form action="{{ route('charity-homes.destroy', $home) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل متأكد من حذف بيت الجمعية؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">لا توجد بيوت جمعيات حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $charityHomes->links() }}
    </section>
@endsection
