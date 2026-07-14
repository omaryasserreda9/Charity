@extends('layouts.app')

@section('title', 'تعديل تصنيف')
@section('page_title', 'تعديل تصنيف')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>{{ $campaignCategory->title }}</h2>
                <p>تعديل بيانات التصنيف.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('campaign-categories.update', $campaignCategory) }}">
            @method('PUT')
            @include('campaign.categories._form')
        </form>
    </section>
@endsection
