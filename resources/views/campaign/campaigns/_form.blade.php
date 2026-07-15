@csrf

<div class="row g-3">
    <div class="col-12 col-md-6">
        <label class="form-label" for="title">عنوان الحملة</label>
        <input id="title" type="text" name="title" value="{{ old('title', $campaign->title ?? '') }}" class="form-control @error('title') is-invalid @enderror" required autofocus>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="district_id">المنطقة</label>
        <select id="district_id" name="district_id" class="form-select @error('district_id') is-invalid @enderror" required>
            <option value="">اختر المنطقة</option>
            @foreach($districts as $district)
            <option value="{{ $district->id }}" {{ (string) old('district_id', $campaign->district_id ?? '') === (string) $district->id ? 'selected' : '' }}>
                {{ $district->title }}
            </option>
            @endforeach
        </select>
        @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="campaign_category_id">البند</label>
        <select id="campaign_category_id" name="campaign_category_id" class="form-select @error('campaign_category_id') is-invalid @enderror" required>
            <option value="">اختر البند</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ (string) old('campaign_category_id', $campaign->campaign_category_id ?? '') === (string) $category->id ? 'selected' : '' }}>
                {{ $category->title }}
            </option>
            @endforeach
        </select>
        @error('campaign_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    @php
    $selectedReferrerIds = old(
    'case_referrer_ids',
    isset($campaign)
    ? $campaign->caseReferrers->pluck('id')->map(function ($id) {
    return (string) $id;
    })->all()
    : []
    );
    @endphp

    <div class="col-12">
        <label class="form-label">الدلائل المرتبطة</label>

        <div class="border rounded p-3" style="max-height: 250px; overflow-y: auto;">
            @forelse($caseReferrers as $caseReferrer)
            <div class="form-check mb-2">
                <input
                    class="form-check-input"
                    type="checkbox"
                    id="referrer{{ $caseReferrer->id }}"
                    name="case_referrer_ids[]"
                    value="{{ $caseReferrer->id }}"
                    {{ in_array((string) $caseReferrer->id, $selectedReferrerIds, true) ? 'checked' : '' }}>

                <label class="form-check-label" for="referrer{{ $caseReferrer->id }}">
                    <strong>{{ $caseReferrer->name }}</strong>

                    @if($caseReferrer->district)
                    <span class="text-muted">
                        ({{ $caseReferrer->district->title }})
                    </span>
                    @endif
                </label>
            </div>
            @empty
            <p class="text-muted mb-0">لا توجد دلائل.</p>
            @endforelse
        </div>

        @error('case_referrer_ids')
        <div class="invalid-feedback d-block">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="status">الحالة</label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach(\App\Models\Campaign::statusOptions() as $value => $label)
            <option value="{{ $value }}" {{ (string) old('status', $campaign->status ?? 'pending') === (string) $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="campaign_date">تاريخ الحملة</label>
        <input id="campaign_date" type="date" name="campaign_date" value="{{ old('campaign_date', isset($campaign) ? optional($campaign->campaign_date)->format('Y-m-d') : now()->format('Y-m-d')) }}" class="form-control @error('campaign_date') is-invalid @enderror" required>
        @error('campaign_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary">حفظ</button>
    <a href="{{ route('campaigns.index') }}" class="btn btn-light">إلغاء</a>
</div>