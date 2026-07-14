@extends('layouts.app')

@section('title', 'تعديل عملية مخزون')
@section('page_title', 'تعديل عملية مخزون')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>تعديل العملية #{{ $inventoryOperation->id }}</h2>
                <p>تحديث بيانات العملية مع الحفاظ على سلامة المخزون.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('inventory-operations.update', $inventoryOperation) }}">
            @method('PUT')
            @include('inventory.operations._form')
        </form>
    </section>
@endsection
