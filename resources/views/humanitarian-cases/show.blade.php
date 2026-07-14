@extends('layouts.app')

@section('title', 'تفاصيل حالة')
@section('page_title', 'تفاصيل حالة')

@section('content')
    <section class="panel form-panel">
        <div class="panel-header">
            <div>
                <h2>{{ $humanitarianCase->name }}</h2>
                <p>
                    <span class="badge rounded-pill {{ $humanitarianCase->type === 'mine' ? 'text-bg-warning' : 'text-bg-info' }}">
                        {{ $humanitarianCase->typeLabel() }}
                    </span>
                </p>
            </div>
            <a href="{{ route('humanitarian-cases.edit', $humanitarianCase) }}" class="btn btn-primary btn-sm">تعديل</a>
        </div>

        <dl class="details-list">
            <dt>الاسم</dt>
            <dd>{{ $humanitarianCase->name }}</dd>
            <dt>رقم الجوال</dt>
            <dd>{{ $humanitarianCase->phone }}</dd>
            <dt>رقم الهوية</dt>
            <dd>{{ $humanitarianCase->national_id }}</dd>
            <dt>المنطقة</dt>
            <dd>{{ $humanitarianCase->area ?: '—' }}</dd>
            <dt>نوع الحالة</dt>
            <dd>{{ $humanitarianCase->typeLabel() }}</dd>
            <dt>ملاحظات</dt>
            <dd>{{ $humanitarianCase->notes ?: '—' }}</dd>
            <dt>تاريخ الإنشاء</dt>
            <dd>{{ optional($humanitarianCase->created_at)->format('Y-m-d') }}</dd>
        </dl>
    </section>

    <section class="panel mt-4">
        <div class="panel-header">
            <div>
                <h2>المرفقات</h2>
                <p>تنزيل ومعاينة وحذف الملفات المرتبطة بالحالة.</p>
            </div>
        </div>

        @include('humanitarian-cases._attachments', ['files' => $humanitarianCase->files])
    </section>
@endsection
