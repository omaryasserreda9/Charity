@extends('layouts.app')

@section('title', 'تفاصيل عملية ميزانية')
@section('page_title', 'تفاصيل عملية ميزانية')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>عملية #{{ $budgetOperation->id }}</h2>
                <p>{{ $budgetOperation->type === 'in' ? 'وارد' : 'صادر' }}</p>
            </div>
            @can('budget_operations.edit')
            <a href="{{ route('budget-operations.edit', $budgetOperation) }}" class="btn btn-primary btn-sm">تعديل</a>
            @endcan
        </div>

        <dl class="details-list">
            <dt>البند</dt>
            <dd>{{ optional($budgetOperation->category)->title ?? 'بدون بند' }}</dd>
            <dt>اسم المتبرع / الجهة</dt>
            <dd>{{ $budgetOperation->donor_name }}</dd>
            @if($budgetOperation->type === 'in')
                <dt>رقم الإيصال</dt>
                <dd>{{ $budgetOperation->receipt_number ?? '—' }}</dd>
            @endif
            <dt>القيمة</dt>
            <dd>{{ number_format((float) $budgetOperation->quantity, 2) }}</dd>
            <dt>تاريخ العملية</dt>
            <dd>{{ optional($budgetOperation->operation_date)->format('Y-m-d') }}</dd>
        </dl>
    </section>
@endsection
