@extends('layouts.app')

@section('title', 'إضافة متبرع')
@section('page_title', 'إضافة متبرع')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>متبرع جديد</h2>
                <p>أدخل بيانات المتبرع أو الجهة المانحة.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('donors.store') }}">
            @include('donors._form')
        </form>
    </section>
@endsection
