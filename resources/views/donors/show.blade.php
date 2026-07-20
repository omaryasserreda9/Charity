@extends('layouts.app')

@section('title', $donor->name)
@section('page_title', $donor->name)

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>{{ $donor->name }}</h2>
                <p>تفاصيل المتبرع والعمليات المرتبطة به.</p>
            </div>
            @can('donors.edit')
            <a href="{{ route('donors.edit', $donor) }}" class="btn btn-primary btn-sm">تعديل</a>
            @endcan
        </div>

        <dl class="details-list">
            <dt>الاسم</dt>
            <dd>{{ $donor->name }}</dd>
            <dt>رقم الهاتف</dt>
            <dd>{{ $donor->phone ?? '—' }}</dd>
            <dt>عمليات الميزانية</dt>
            <dd>{{ $donor->budget_operations_count }}</dd>
            <dt>عمليات المخزون</dt>
            <dd>{{ $donor->inventory_operations_count }}</dd>
            <dt>تاريخ الإنشاء</dt>
            <dd>{{ optional($donor->created_at)->format('Y-m-d') }}</dd>
            <dt>آخر تحديث</dt>
            <dd>{{ optional($donor->updated_at)->format('Y-m-d') }}</dd>
        </dl>

        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('donors.index') }}" class="btn btn-light">رجوع</a>
        </div>
    </section>
@endsection
