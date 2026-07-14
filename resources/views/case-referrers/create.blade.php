@extends('layouts.app')

@section('title', 'إضافة دليل')
@section('page_title', 'إضافة دليل')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>دليل جديد</h2>
                <p>أضف دليلًا مرتبطًا بمنطقة.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('case-referrers.store') }}">
            @include('case-referrers._form')
        </form>
    </section>
@endsection
