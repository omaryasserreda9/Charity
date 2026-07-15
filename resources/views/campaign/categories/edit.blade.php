@extends('layouts.app')

@section('title', 'تعديل بند')
@section('page_title', 'تعديل بند')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>{{ $campaignCategory->title }}</h2>
                <p>تعديل بيانات البند.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('campaign-categories.update', $campaignCategory) }}">
            @method('PUT')
            @include('campaign.categories._form')
        </form>
    </section>
@endsection
