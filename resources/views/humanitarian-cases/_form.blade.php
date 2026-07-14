@csrf

<div class="row g-3">
    <div class="col-12 col-md-6">
        <label class="form-label" for="name">الاسم</label>
        <input id="name" type="text" name="name" value="{{ old('name', $humanitarianCase->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="phone">رقم الجوال</label>
        <input id="phone" type="text" name="phone" value="{{ old('phone', $humanitarianCase->phone ?? '') }}" class="form-control @error('phone') is-invalid @enderror" required>
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="national_id">رقم الهوية</label>
        <input id="national_id" type="text" name="national_id" value="{{ old('national_id', $humanitarianCase->national_id ?? '') }}" class="form-control @error('national_id') is-invalid @enderror" required>
        @error('national_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="type">نوع الحالة</label>
        <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
            @foreach(\App\Models\HumanitarianCase::typeOptions() as $value => $label)
                <option value="{{ $value }}" {{ (string) old('type', $humanitarianCase->type ?? '') === (string) $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label" for="notes">ملاحظات</label>
        <textarea id="notes" name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $humanitarianCase->notes ?? '') }}</textarea>
        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label" for="attachments">المرفقات</label>
        <input id="attachments" type="file" name="attachments[]" class="form-control @error('attachments') is-invalid @enderror @error('attachments.*') is-invalid @enderror" multiple accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx,image/*,application/pdf">
        <div class="form-text">يمكنك اختيار عدة ملفات (PDF، صور، Word). الحد الأقصى 10 ميجابايت لكل ملف.</div>
        @error('attachments')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        @foreach ($errors->get('attachments.*') as $message)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @endforeach
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary">حفظ</button>
    <a href="{{ route('humanitarian-cases.index') }}" class="btn btn-light">إلغاء</a>
</div>
