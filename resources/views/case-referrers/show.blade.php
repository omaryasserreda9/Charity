@extends('layouts.app')

@section('title', $caseReferrer->name)
@section('page_title', $caseReferrer->name)

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>{{ $caseReferrer->name }}</h2>
                <p>تفاصيل الدليل المرتبط بالمنطقة.</p>
            </div>
        </div>

        <dl class="details-list">
            <dt>الاسم</dt>
            <dd>{{ $caseReferrer->name }}</dd>
            <dt>المنطقة</dt>
            <dd>{{ $caseReferrer->district->title ?? '—' }}</dd>
            <dt>الإنشاء</dt>
            <dd>{{ optional($caseReferrer->created_at)->format('Y-m-d') }}</dd>
            <dt>آخر تحديث</dt>
            <dd>{{ optional($caseReferrer->updated_at)->format('Y-m-d') }}</dd>
        </dl>

        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('case-referrers.edit', $caseReferrer) }}" class="btn btn-primary">تعديل</a>
            <a href="{{ route('case-referrers.index') }}" class="btn btn-light">رجوع</a>
        </div>
    </section>
@endsection
