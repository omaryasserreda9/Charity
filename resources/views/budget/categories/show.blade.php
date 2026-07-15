@extends('layouts.app')

@section('title', 'تفاصيل بند')
@section('page_title', 'تفاصيل بند')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>{{ $budgetCategory->title }}</h2>
                <p>عدد العمليات المرتبطة: {{ $budgetCategory->operations_count }}</p>
            </div>
            <a href="{{ route('budget-categories.edit', $budgetCategory) }}" class="btn btn-primary btn-sm">تعديل</a>
        </div>

        <dl class="details-list">
            <dt>الرقم</dt>
            <dd>{{ $budgetCategory->id }}</dd>
            <dt>تاريخ الإنشاء</dt>
            <dd>{{ optional($budgetCategory->created_at)->format('Y-m-d') }}</dd>
        </dl>
    </section>
@endsection
