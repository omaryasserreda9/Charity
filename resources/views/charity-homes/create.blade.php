@extends('layouts.app')

@section('title', 'إضافة بيت جمعية')
@section('page_title', 'إضافة بيت جمعية')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>بيت جمعية جديد</h2>
                <p>أضف بيت جمعية جديدة لإدارة البيانات الخاصة بها لاحقًا.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('charity-homes.store') }}">
            @include('charity-homes._form')
        </form>
    </section>
@endsection
