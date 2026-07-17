@extends('layouts.app')

@section('title', 'تعديل مستخدم')
@section('page_title', 'تعديل مستخدم')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>تعديل المستخدم</h2>
                <p>تحديث بيانات المستخدم والأدوار وبيت الجمعية.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('users.update', $user) }}">
            @method('PUT')
            @include('users._form')
        </form>
    </section>
@endsection
