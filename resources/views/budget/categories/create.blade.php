@extends('layouts.app')

@section('title', 'إضافة تصنيف')
@section('page_title', 'إضافة تصنيف')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>تصنيف جديد</h2>
                <p>أدخل اسم التصنيف المستخدم في عمليات الميزانية.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('budget-categories.store') }}">
            @include('budget.categories._form')
        </form>
    </section>
@endsection
