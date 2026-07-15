@csrf

<div class="row g-3">
    <div class="col-12 col-md-6">
        <label class="form-label" for="type">نوع العملية</label>
        <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required>
            <option value="in" {{ (string) old('type', $inventoryOperation->type ?? '') === (string) 'in' ? 'selected' : '' }}>وارد</option>
            <option value="out" {{ (string) old('type', $inventoryOperation->type ?? '') === (string) 'out' ? 'selected' : '' }}>صادر</option>
        </select>
        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="inventory_category_id">البند</label>
        <select id="inventory_category_id" name="inventory_category_id" class="form-select @error('inventory_category_id') is-invalid @enderror">
            <option value="">بدون بند</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ (string) old('inventory_category_id', $inventoryOperation->inventory_category_id ?? '') === (string) $category->id ? 'selected' : '' }}>
                    {{ $category->title }}
                </option>
            @endforeach
        </select>
        @error('inventory_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="donor_name">اسم المتبرع / الجهة</label>
        <input id="donor_name" type="text" name="donor_name" value="{{ old('donor_name', $inventoryOperation->donor_name ?? '') }}" class="form-control @error('donor_name') is-invalid @enderror" required>
        @error('donor_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="item_name">اسم الصنف</label>
        <input id="item_name" type="text" name="item_name" value="{{ old('item_name', $inventoryOperation->item_name ?? '') }}" class="form-control @error('item_name') is-invalid @enderror" required>
        @error('item_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="quantity">الكمية</label>
        <input id="quantity" type="number" name="quantity" value="{{ old('quantity', $inventoryOperation->quantity ?? '') }}" step="0.01" min="0.01" class="form-control @error('quantity') is-invalid @enderror" required>
        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6">
        <label class="form-label" for="operation_date">تاريخ العملية</label>
        <input id="operation_date" type="date" name="operation_date" value="{{ old('operation_date', isset($inventoryOperation) ? optional($inventoryOperation->operation_date)->format('Y-m-d') : now()->format('Y-m-d')) }}" class="form-control @error('operation_date') is-invalid @enderror" required>
        @error('operation_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-primary">حفظ</button>
    <a href="{{ route('inventory-operations.index') }}" class="btn btn-light">إلغاء</a>
</div>
