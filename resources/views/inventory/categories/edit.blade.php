@extends('layouts.app')

@section('title', 'تعديل تصنيف')
@section('page_title', 'تعديل تصنيف')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>{{ $inventoryCategory->title }}</h2>
                <p>تعديل بيانات التصنيف.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('inventory-categories.update', $inventoryCategory) }}">
            @method('PUT')
            @include('inventory.categories._form')
        </form>
    </section>
@endsection
