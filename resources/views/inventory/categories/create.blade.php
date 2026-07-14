@extends('layouts.app')

@section('title', 'إضافة تصنيف')
@section('page_title', 'إضافة تصنيف')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>تصنيف جديد</h2>
                <p>أدخل اسم التصنيف المستخدم في عمليات المخزون.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('inventory-categories.store') }}">
            @include('inventory.categories._form')
        </form>
    </section>
@endsection
