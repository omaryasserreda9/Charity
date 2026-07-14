@extends('layouts.app')

@section('title', 'تعديل الدليل')
@section('page_title', 'تعديل الدليل')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>{{ $caseReferrer->name }}</h2>
                <p>تعديل بيانات الدليل.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('case-referrers.update', $caseReferrer) }}">
            @method('PUT')
            @include('case-referrers._form')
        </form>
    </section>
@endsection
