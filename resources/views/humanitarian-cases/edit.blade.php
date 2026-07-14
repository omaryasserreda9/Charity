@extends('layouts.app')

@section('title', 'تعديل حالة')
@section('page_title', 'تعديل حالة')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>{{ $humanitarianCase->name }}</h2>
                <p>تعديل بيانات الحالة أو إضافة مرفقات جديدة.</p>
            </div>
        </div>

        @if($humanitarianCase->files->isNotEmpty())
            <div class="mb-4">
                <h3 class="h6 mb-3">المرفقات الحالية</h3>
                @include('humanitarian-cases._attachments', ['files' => $humanitarianCase->files])
            </div>
        @endif

        <form method="POST" action="{{ route('humanitarian-cases.update', $humanitarianCase) }}" enctype="multipart/form-data">
            @method('PUT')
            @include('humanitarian-cases._form')
        </form>
    </section>
@endsection
