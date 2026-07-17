@extends('layouts.app')

@section('title', $user->name)
@section('page_title', $user->name)

@section('content')
    <section class="panel">
        <div class="panel-header d-flex justify-content-between align-items-center">
            <div>
                <h2>عرض المستخدم</h2>
                <p>تفاصيل المستخدم وأدواره وبيته.</p>
            </div>
            @can('users.edit')
            <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary">تعديل</a>
            @endcan
        </div>

        <div class="mb-3">
            <strong>الاسم:</strong>
            <p>{{ $user->name }}</p>
        </div>

        <div class="mb-3">
            <strong>البريد الإلكتروني:</strong>
            <p>{{ $user->email }}</p>
        </div>

        <div class="mb-3">
            <strong>الحالة:</strong>
            <p>{{ $user->active ? 'مفعل' : 'معطل' }}</p>
        </div>

        <div class="mb-3">
            <strong>بيت الجمعية:</strong>
            <p>{{ $user->charityHome->title ?? '—' }}</p>
        </div>

        <div class="mb-3">
            <strong>الأدوار:</strong>
            <p>{{ $user->roles->pluck('name')->join(', ') }}</p>
        </div>
    </section>
@endsection
