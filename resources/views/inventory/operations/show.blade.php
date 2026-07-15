@extends('layouts.app')

@section('title', 'تفاصيل عملية مخزون')
@section('page_title', 'تفاصيل عملية مخزون')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>عملية #{{ $inventoryOperation->id }}</h2>
                <p>{{ $inventoryOperation->type === 'in' ? 'وارد' : 'صادر' }}</p>
            </div>
            <a href="{{ route('inventory-operations.edit', $inventoryOperation) }}" class="btn btn-primary btn-sm">تعديل</a>
        </div>

        <dl class="details-list">
            <dt>البند</dt>
            <dd>{{ optional($inventoryOperation->category)->title ?? 'بدون بند' }}</dd>
            <dt>اسم المتبرع / الجهة</dt>
            <dd>{{ $inventoryOperation->donor_name }}</dd>
            <dt>اسم الصنف</dt>
            <dd>{{ $inventoryOperation->item_name }}</dd>
            <dt>الكمية</dt>
            <dd>{{ number_format((float) $inventoryOperation->quantity, 2) }}</dd>
            <dt>تاريخ العملية</dt>
            <dd>{{ optional($inventoryOperation->operation_date)->format('Y-m-d') }}</dd>
        </dl>
    </section>
@endsection
