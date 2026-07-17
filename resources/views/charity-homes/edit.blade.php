@extends('layouts.app')

@section('title', 'تعديل بيت جمعية')
@section('page_title', 'تعديل بيت جمعية')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>تعديل بيت الجمعية</h2>
                <p>قم بتحديث بيانات بيت الجمعية.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('charity-homes.update', $charityHome) }}">
            @method('PUT')
            @include('charity-homes._form')
        </form>
    </section>
@endsection
