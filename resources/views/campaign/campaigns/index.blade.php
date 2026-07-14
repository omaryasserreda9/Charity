@extends('layouts.app')

@section('title', 'الحملات')
@section('page_title', 'الحملات')

@section('content')
    <section class="panel">
        <div class="panel-header">
            <div>
                <h2>سجل الحملات</h2>
                <p>بحث وتصفية حسب التصنيف.</p>
            </div>
            <a href="{{ route('campaigns.create') }}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-plus ms-1"></i>
                إضافة حملة
            </a>
        </div>

        <form method="GET" action="{{ route('campaigns.index') }}" class="filter-bar mb-3">
            <input type="search" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control" placeholder="بحث بالعنوان أو المنطقة">
            <select name="district_id" class="form-select">
                <option value="">كل المناطق</option>
                @foreach($districts as $district)
                    <option value="{{ $district->id }}" {{ (string) ($filters['district_id'] ?? '') === (string) $district->id ? 'selected' : '' }}>
                        {{ $district->title }}
                    </option>
                @endforeach
            </select>
            <select name="campaign_category_id" class="form-select">
                <option value="">كل التصنيفات</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ (string) ($filters['campaign_category_id'] ?? '') === (string) $category->id ? 'selected' : '' }}>
                        {{ $category->title }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">تصفية</button>
            <a href="{{ route('campaigns.index') }}" class="btn btn-light">إعادة ضبط</a>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>المنطقة</th>
                        <th>التصنيف</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>الحالات</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                        <tr>
                            <td>{{ $campaign->id }}</td>
                            <td>{{ $campaign->title }}</td>
                            <td>{{ $campaign->district->title }}</td>
                            <td>{{ $campaign->category->title }}</td>
                            <td>
                                <span class="badge rounded-pill {{ $campaign->status === 'done' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $campaign->statusLabel() }}
                                </span>
                            </td>
                            <td>{{ optional($campaign->campaign_date)->format('Y-m-d') }}</td>
                            <td>{{ $campaign->humanitarian_cases_count }}</td>
                            <td>
                                <div class="table-actions">
                                    <a class="btn btn-sm btn-light" href="{{ route('campaigns.show', $campaign) }}">عرض</a>
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('campaigns.cases', $campaign) }}">الحالات</a>
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('campaigns.edit', $campaign) }}">تعديل</a>
                                    <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteCampaign{{ $campaign->id }}">حذف</button>
                                </div>

                                <div class="modal fade" id="deleteCampaign{{ $campaign->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">حذف الحملة</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                            </div>
                                            <div class="modal-body">هل تريد حذف هذه الحملة؟</div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                                                <form method="POST" action="{{ route('campaigns.destroy', $campaign) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">تأكيد الحذف</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">لا توجد حملات مطابقة.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $campaigns->links() }}
    </section>
@endsection
