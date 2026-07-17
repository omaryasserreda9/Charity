@extends('layouts.app')

@section('title', 'تفاصيل بند')
@section('page_title', 'تفاصيل بند')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>{{ $campaignCategory->title }}</h2>
                <p>عدد الحملات المرتبطة: {{ $campaignCategory->campaigns_count }}</p>
            </div>
            @can('campaign_categories.edit')
            <a href="{{ route('campaign-categories.edit', $campaignCategory) }}" class="btn btn-primary btn-sm">تعديل</a>
            @endcan
        </div>

        <dl class="details-list">
            <dt>الرقم</dt>
            <dd>{{ $campaignCategory->id }}</dd>
            <dt>تاريخ الإنشاء</dt>
            <dd>{{ optional($campaignCategory->created_at)->format('Y-m-d') }}</dd>
        </dl>
    </section>
@endsection
