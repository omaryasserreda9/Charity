@extends('layouts.app')

@section('title', 'لوحة التحكم')
@section('page_title', 'لوحة التحكم')

@section('content')
<div class="row g-3 mb-4">
    @foreach($stats as $stat)
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <span class="stat-icon text-bg-{{ $stat['color'] }}">
                <i class="fa-solid {{ $stat['icon'] }}"></i>
            </span>
            <div>
                <p>{{ $stat['label'] }}</p>
                <strong>{{ $stat['value'] }}</strong>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-12 col-xl-8">
        <section class="panel">
            <div class="panel-header">
                <div>
                    <h2>آخر التبرعات</h2>
                    <p>آخر عمليات الوارد في الميزانية.</p>
                </div>
                <a href="{{ route('budget-operations.create') }}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-plus ms-1"></i>
                    إضافة عملية
                </a>
            </div>

            <div class="table-responsive">
                <table id="recentDonationsTable" class="table table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th>المتبرع</th>
                            <th>البند</th>
                            <th>المبلغ</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentDonations as $donation)
                        <tr>
                            <td>{{ $donation->donor_name }}</td>
                            <td>{{ optional($donation->category)->title ?? 'بدون بند' }}</td>
                            <td>{{ number_format((float) $donation->quantity, 2) }}</td>
                            <td>{{ optional($donation->operation_date)->format('Y-m-d') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <div class="col-12 col-xl-4">
        <section class="panel h-100">
            <div class="panel-header">
                <div>
                    <h2>حملات قيد التنفيذ</h2>
                    <p>حملات تحتاج متابعة.</p>
                </div>
                <a href="{{ route('campaigns.index') }}" class="btn btn-light btn-sm">عرض الكل</a>
            </div>

            @if($pendingCampaigns->isNotEmpty())
            <ul class="dashboard-list">
                @foreach($pendingCampaigns as $campaign)
                <li>
                    <a href="{{ route('campaigns.show', $campaign) }}">
                        <strong>{{ $campaign->title }}</strong>
                        <small>{{ $campaign->district->title }} · {{ optional($campaign->campaign_date)->format('Y-m-d') }}</small>
                    </a>
                </li>
                @endforeach
            </ul>
            @else
            <p class="text-muted mb-0">لا توجد حملات قيد التنفيذ حالياً.</p>
            @endif
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
    new DataTable('#recentDonationsTable', {
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/ar.json',
            emptyTable: 'لا توجد تبرعات مسجلة.'
        },
        pageLength: 5,
        lengthMenu: [5, 10, 25],
        responsive: true,
        order: [
            [3, 'desc']
        ]
    });
</script>
@endpush