@extends('layouts.app')

@section('title', 'تعديل منطقة')
@section('page_title', 'تعديل منطقة')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>{{ $district->title }}</h2>
                <p>تعديل بيانات المنطقة.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('districts.update', $district) }}">
            @method('PUT')
            @include('districts._form')
        </form>
    </section>
@endsection
