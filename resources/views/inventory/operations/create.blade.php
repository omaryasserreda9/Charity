@extends('layouts.app')

@section('title', 'إضافة عملية مخزون')
@section('page_title', 'إضافة عملية مخزون')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>عملية جديدة</h2>
                <p>سجل واردًا أو صادرًا في مخزون الجمعية.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('inventory-operations.store') }}">
            @include('inventory.operations._form')
        </form>
    </section>
@endsection
