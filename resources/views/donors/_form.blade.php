@csrf

<div class="mb-3">
    <label class="form-label" for="name">اسم المتبرع / الجهة</label>
    <input id="name" type="text" name="name" value="{{ old('name', $donor->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label" for="phone">رقم الهاتف</label>
    <input id="phone" type="text" name="phone" value="{{ old('phone', $donor->phone ?? '') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="01xxxxxxxxx">
    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">حفظ</button>
    <a href="{{ route('donors.index') }}" class="btn btn-light">إلغاء</a>
</div>
