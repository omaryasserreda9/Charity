@extends('layouts.app')

@section('title', 'تعديل عملية ميزانية')
@section('page_title', 'تعديل عملية ميزانية')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>تعديل العملية #{{ $budgetOperation->id }}</h2>
                <p>تحديث بيانات العملية مع الحفاظ على سلامة الرصيد.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('budget-operations.update', $budgetOperation) }}">
            @method('PUT')
            @include('budget.operations._form')
        </form>
    </section>
@endsection
