@extends('layouts.app')

@section('title', 'إضافة عملية ميزانية')
@section('page_title', 'إضافة عملية ميزانية')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>عملية جديدة</h2>
                <p>سجل واردًا أو صادرًا في ميزانية الجمعية.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('budget-operations.store') }}">
            @include('budget.operations._form')
        </form>
    </section>
@endsection
