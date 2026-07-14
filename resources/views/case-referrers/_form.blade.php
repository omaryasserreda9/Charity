@csrf

<div class="mb-3">
    <label class="form-label" for="name">اسم الدليل</label>
    <input id="name" type="text" name="name" value="{{ old('name', $caseReferrer->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label" for="district_id">المنطقة</label>
    <select id="district_id" name="district_id" class="form-select @error('district_id') is-invalid @enderror" required>
        <option value="">اختر المنطقة</option>
        @foreach($districts as $district)
            <option value="{{ $district->id }}" {{ (string) old('district_id', $caseReferrer->district_id ?? '') === (string) $district->id ? 'selected' : '' }}>{{ $district->title }}</option>
        @endforeach
    </select>
    @error('district_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">حفظ</button>
    <a href="{{ route('case-referrers.index') }}" class="btn btn-light">إلغاء</a>
</div>
