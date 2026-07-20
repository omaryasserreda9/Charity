@extends('layouts.app')

@section('title', 'تعديل متبرع')
@section('page_title', 'تعديل متبرع')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>{{ $donor->name }}</h2>
                <p>تعديل بيانات المتبرع.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('donors.update', $donor) }}">
            @method('PUT')
            @include('donors._form')
        </form>
    </section>
@endsection
