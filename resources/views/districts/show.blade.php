@extends('layouts.app')

@section('title', 'تفاصيل منطقة')
@section('page_title', 'تفاصيل منطقة')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>{{ $district->title }}</h2>
                <p>عدد الحملات المرتبطة: {{ $district->campaigns_count }}</p>
            </div>
            <a href="{{ route('districts.edit', $district) }}" class="btn btn-primary btn-sm">تعديل</a>
        </div>

        <dl class="details-list">
            <dt>الرقم</dt>
            <dd>{{ $district->id }}</dd>
            <dt>تاريخ الإنشاء</dt>
            <dd>{{ optional($district->created_at)->format('Y-m-d') }}</dd>
        </dl>
    </section>
@endsection
