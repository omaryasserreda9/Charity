@extends('layouts.app')

@section('title', 'إضافة بند')
@section('page_title', 'إضافة بند')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>بند جديد</h2>
                <p>أدخل اسم البند المستخدم في عمليات المخزون.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('inventory-categories.store') }}">
            @include('inventory.categories._form')
        </form>
    </section>
@endsection
