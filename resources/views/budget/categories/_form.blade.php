@csrf

<div class="mb-3">
    <label class="form-label" for="title">عنوان البند</label>
    <input id="title" type="text" name="title" value="{{ old('title', $budgetCategory->title ?? '') }}" class="form-control @error('title') is-invalid @enderror" required autofocus>
    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">حفظ</button>
    <a href="{{ route('budget-categories.index') }}" class="btn btn-light">إلغاء</a>
</div>
