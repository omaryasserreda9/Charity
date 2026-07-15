@csrf

<div class="alert alert-info">
    الرصيد الحالي: <strong>{{ number_format($currentBalance, 2) }}</strong>
</div>

<div class="row g-3">
    <div class="col-12 col-md-6">
        <label class="form-label" for="type">نوع العملية</label>
        <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
            <option value="in" {{ old('type', $budgetOperation->type ?? '') === 'in' ? 'selected' : '' }}>
                وارد
            </option>

            <option value="out" {{ old('type', $budgetOperation->type ?? '') === 'out' ? 'selected' : '' }}>
                صادر
            </option>
        </select>
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="budget_category_id">البند</label>
        <select id="budget_category_id" name="budget_category_id" class="form-select @error('budget_category_id') is-invalid @enderror">
            <option value="">بدون بند</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ (string) old('budget_category_id', $budgetOperation->budget_category_id ?? '') === (string) $category->id ? 'selected' : '' }}>
                {{ $category->title }}
            </option>
            @endforeach
        </select>
        @error('budget_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="donor_name">اسم المتبرع / الجهة</label>
        <input id="donor_name" type="text" name="donor_name" value="{{ old('donor_name', $budgetOperation->donor_name ?? '') }}" class="form-control @error('donor_name') is-invalid @enderror" required>
        @error('donor_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6" id="receipt_number_group" style="display: {{ old('type', $budgetOperation->type ?? '') === 'in' ? 'block' : 'none' }};">
        <label class="form-label" for="receipt_number">رقم الإيصال</label>
        <input id="receipt_number" type="text" name="receipt_number" value="{{ old('receipt_number', $budgetOperation->receipt_number ?? '') }}" class="form-control @error('receipt_number') is-invalid @enderror">
        @error('receipt_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="quantity">القيمة</label>
        <input id="quantity" type="number" name="quantity" value="{{ old('quantity', $budgetOperation->quantity ?? '') }}" step="0.01" min="0.01" class="form-control @error('quantity') is-invalid @enderror" required>
        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="operation_date">تاريخ العملية</label>
        <input id="operation_date" type="date" name="operation_date" value="{{ old('operation_date', isset($budgetOperation) ? optional($budgetOperation->operation_date)->format('Y-m-d') : now()->format('Y-m-d')) }}" class="form-control @error('operation_date') is-invalid @enderror" required>
        @error('operation_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary">حفظ</button>
    <a href="{{ route('budget-operations.index') }}" class="btn btn-light">إلغاء</a>
</div>

<script>
    (function(){
        const typeEl = document.getElementById('type');
        const receiptGroup = document.getElementById('receipt_number_group');

        if (! typeEl) return;

        typeEl.addEventListener('change', function(){
            if (this.value === 'in') {
                receiptGroup.style.display = 'block';
            } else {
                receiptGroup.style.display = 'none';
            }
        });
    })();
</script>