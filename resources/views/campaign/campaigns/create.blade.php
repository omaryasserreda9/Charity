@extends('layouts.app')

@section('title', 'إضافة حملة')
@section('page_title', 'إضافة حملة')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>حملة جديدة</h2>
                <p>أدخل بيانات الحملة.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('campaigns.store') }}">
            @include('campaign.campaigns._form')
        </form>
    </section>
@endsection
