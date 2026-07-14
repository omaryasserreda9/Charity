@extends('layouts.app')

@section('title', 'تعديل حملة')
@section('page_title', 'تعديل حملة')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>{{ $campaign->title }}</h2>
                <p>تعديل بيانات الحملة.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('campaigns.update', $campaign) }}">
            @method('PUT')
            @include('campaign.campaigns._form')
        </form>
    </section>
@endsection
