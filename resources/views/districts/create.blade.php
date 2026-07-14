@extends('layouts.app')

@section('title', 'إضافة منطقة')
@section('page_title', 'إضافة منطقة')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>منطقة جديدة</h2>
                <p>أدخل اسم المنطقة المستخدم في الحملات.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('districts.store') }}">
            @include('districts._form')
        </form>
    </section>
@endsection
