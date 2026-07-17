@extends('layouts.app')

@section('title', 'إضافة مستخدم')
@section('page_title', 'إضافة مستخدم')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>مستخدم جديد</h2>
                <p>إنشاء مستخدم جديد وتعيينه إلى بيت جمعية وأدوار.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('users.store') }}">
            @include('users._form')
        </form>
    </section>
@endsection
