@extends('layouts.app')

@section('title', 'تفاصيل حملة')
@section('page_title', 'تفاصيل حملة')

@section('content')
<section class="panel form-panel">
    <div class="panel-header">
        <div>
            <h2>{{ $campaign->title }}</h2>
            <p>
                <span class="badge rounded-pill {{ $campaign->status === 'done' ? 'text-bg-success' : 'text-bg-secondary' }}">
                    {{ $campaign->statusLabel() }}
                </span>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('campaigns.cases', $campaign) }}" class="btn btn-outline-secondary btn-sm">إدارة الحالات</a>
            <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-primary btn-sm">تعديل</a>
        </div>
    </div>

    <dl class="details-list">
        <dt>المنطقة</dt>
        <dd>{{ $campaign->district->title }}</dd>
        <dt>البند</dt>
        <dd>{{ $campaign->category->title }}</dd>
        <dt>تاريخ الحملة</dt>
        <dd>{{ optional($campaign->campaign_date)->format('Y-m-d') }}</dd>
        <dt>عدد الحالات المرتبطة</dt>
        <dd>{{ $campaign->humanitarianCases->count() }}</dd>
        <dt>الدلائل المرتبطة</dt>
        <dd>
            @if($campaign->caseReferrers->isNotEmpty())
            @foreach($campaign->caseReferrers as $referrer)
            <span class="badge text-bg-info me-1 mb-1">
                {{ $referrer->name }}
                @if($referrer->district)
                ({{ $referrer->district->title }})
                @endif
            </span>
            @endforeach
            @else
            —
            @endif
        </dd>
    </dl>
</section>



@if($campaign->humanitarianCases->isNotEmpty())
<section class="panel mt-4">
    <div class="panel-header">
        <div>
            <h2>الحالات المرتبطة</h2>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>رقم الجوال</th>
                    <th>رقم الهوية</th>
                    <th>المنطقة</th>
                    <th>النوع</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campaign->humanitarianCases as $case)
                <tr>
                    <td>
                        <a href="{{ route('humanitarian-cases.show', $case) }}">{{ $case->name }}</a>
                    </td>
                    <td>{{ $case->phone }}</td>
                    <td>{{ $case->national_id }}</td>
                    <td>{{ $case->district->title ?? $case->area ?: '—' }}</td>
                    <td>{{ $case->typeLabel() }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


</section>
@endif
@endsection