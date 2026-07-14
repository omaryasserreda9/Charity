@extends('layouts.app')

@section('title', 'إضافة حالة')
@section('page_title', 'إضافة حالة')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>حالة جديدة</h2>
                <p>أدخل بيانات الحالة الإنسانية والمرفقات.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('humanitarian-cases.store') }}" enctype="multipart/form-data">
            @include('humanitarian-cases._form')
        </form>
    </section>
@endsection
