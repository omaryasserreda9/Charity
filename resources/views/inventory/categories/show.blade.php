@extends('layouts.app')

@section('title', 'تفاصيل تصنيف')
@section('page_title', 'تفاصيل تصنيف')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>{{ $inventoryCategory->title }}</h2>
                <p>عدد العمليات المرتبطة: {{ $inventoryCategory->operations_count }}</p>
            </div>
            <a href="{{ route('inventory-categories.edit', $inventoryCategory) }}" class="btn btn-primary btn-sm">تعديل</a>
        </div>

        <dl class="details-list">
            <dt>الرقم</dt>
            <dd>{{ $inventoryCategory->id }}</dd>
            <dt>تاريخ الإنشاء</dt>
            <dd>{{ optional($inventoryCategory->created_at)->format('Y-m-d') }}</dd>
        </dl>
    </section>
@endsection
