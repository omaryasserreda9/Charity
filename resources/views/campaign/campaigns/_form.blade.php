@csrf

<div class="row g-3">
    <div class="col-12 col-md-6">
        <label class="form-label" for="title">عنوان الحملة</label>
        <input id="title" type="text" name="title" value="{{ old('title', $campaign->title ?? '') }}" class="form-control @error('title') is-invalid @enderror" required autofocus>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="area">المنطقة</label>
        <input id="area" type="text" name="area" value="{{ old('area', $campaign->area ?? '') }}" class="form-control @error('area') is-invalid @enderror" required>
        @error('area')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="campaign_category_id">التصنيف</label>
        <select id="campaign_category_id" name="campaign_category_id" class="form-select @error('campaign_category_id') is-invalid @enderror" required>
            <option value="">اختر التصنيف</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ (string) old('campaign_category_id', $campaign->campaign_category_id ?? '') === (string) $category->id ? 'selected' : '' }}>
                {{ $category->title }}
            </option>
            @endforeach
        </select>
        @error('campaign_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
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