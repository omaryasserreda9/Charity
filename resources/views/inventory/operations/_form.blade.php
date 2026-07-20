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

    <div class="col-12 col-md-6" id="donor_in_group" style="display: {{ old('type', $inventoryOperation->type ?? 'in') === 'in' ? 'block' : 'none' }};">
        <label class="form-label" for="donor_selector">اسم المتبرع / الجهة</label>
        <select id="donor_selector" name="donor_selector" class="form-select @error('donor_id') is-invalid @enderror @error('donor_name') is-invalid @enderror">
            @if(old('donor_selector'))
                <option value="{{ old('donor_selector') }}" selected>
                    {{ old('donor_name', old('donor_selector')) }}
                </option>
            @elseif(isset($inventoryOperation) && $inventoryOperation->donor_id)
                <option value="{{ $inventoryOperation->donor_id }}" selected>
                    {{ $inventoryOperation->donor->name }}
                </option>
            @elseif(isset($inventoryOperation) && $inventoryOperation->donor_name)
                <option value="{{ $inventoryOperation->donor_name }}" selected>
                    {{ $inventoryOperation->donor_name }}
                </option>
            @else
                <option value="" selected>اختر متبرعاً...</option>
            @endif
        </select>
        <input type="hidden" id="donor_name_hidden" name="donor_name" value="{{ old('donor_name', $inventoryOperation->donor_name ?? '') }}">
        @error('donor_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        @error('donor_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    <div class="col-12 col-md-6" id="donor_out_group" style="display: {{ old('type', $inventoryOperation->type ?? 'in') === 'out' ? 'block' : 'none' }};">
        <label class="form-label" for="donor_name_text">اسم المتبرع / الجهة</label>
        <input id="donor_name_text" type="text" name="donor_name_text" value="{{ old('donor_name_text', (old('type', $inventoryOperation->type ?? 'in') === 'out') ? old('donor_name', $inventoryOperation->donor_name ?? '') : '') }}" class="form-control @error('donor_name') is-invalid @enderror">
        @error('donor_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    </div>

    @php
        $initialPhone = '';
        $showPhone = false;
        if (old('donor_selector') && is_numeric(old('donor_selector'))) {
            $donor = \App\Models\Donor::find(old('donor_selector'));
            if ($donor && $donor->phone) {
                $initialPhone = $donor->phone;
                $showPhone = true;
            }
        } elseif (isset($inventoryOperation) && $inventoryOperation->donor && $inventoryOperation->donor->phone) {
            $initialPhone = $inventoryOperation->donor->phone;
            $showPhone = true;
        }
    @endphp
    <div class="col-12 col-md-6" id="donor_phone_group" style="display: {{ $showPhone ? 'block' : 'none' }};">
        <label class="form-label" for="donor_phone">رقم الهاتف (للقراءة فقط)</label>
        <input id="donor_phone" type="text" value="{{ $initialPhone }}" class="form-control" readonly>
    </div>

    <div class="col-12 col-md-6" id="receipt_number_group" style="display: {{ (string) old('type', $inventoryOperation->type ?? 'in') === 'in' ? 'block' : 'none' }};">
        <label class="form-label" for="receipt_number">رقم الإيصال</label>
        <input id="receipt_number" type="text" name="receipt_number" value="{{ old('receipt_number', $inventoryOperation->receipt_number ?? '') }}" class="form-control @error('receipt_number') is-invalid @enderror">
        @error('receipt_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
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

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single {
            height: 38px !important;
            padding: 5px 12px;
            font-size: 0.9rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
            left: 10px !important; /* RTL support */
            right: auto !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px !important;
            text-align: right;
            padding-right: 0px !important;
        }
        .select2-container {
            width: 100% !important;
        }
        .is-invalid + .select2-container .select2-selection {
            border-color: #dc3545 !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            const donorSelector = $('#donor_selector');
            const typeEl = document.getElementById('type');
            const receiptGroup = document.getElementById('receipt_number_group');
            const donorInGroup = document.getElementById('donor_in_group');
            const donorOutGroup = document.getElementById('donor_out_group');
            const donorPhoneGroup = document.getElementById('donor_phone_group');

            // Initialize Select2
            donorSelector.select2({
                tags: true,
                ajax: {
                    url: '{{ route("donors.search") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (donor) {
                                return {
                                    id: donor.id,
                                    text: donor.name,
                                    phone: donor.phone
                                };
                            })
                        };
                    },
                    cache: true
                },
                placeholder: 'اختر متبرعاً أو اكتب اسماً جديداً',
                minimumInputLength: 0,
                language: "ar",
                dir: "rtl"
            });

            // Update phone and hidden name on selection
            donorSelector.on('select2:select', function (e) {
                const data = e.params.data;
                if (data.phone) {
                    $('#donor_phone').val(data.phone);
                    $(donorPhoneGroup).show();
                } else {
                    $('#donor_phone').val('');
                    $(donorPhoneGroup).hide();
                }
                $('#donor_name_hidden').val(data.text);
            });

            donorSelector.on('select2:unselect', function () {
                $('#donor_phone').val('');
                $(donorPhoneGroup).hide();
                $('#donor_name_hidden').val('');
            });

            function toggleFields() {
                if (typeEl.value === 'in') {
                    if (receiptGroup) receiptGroup.style.display = 'block';
                    if (donorInGroup) donorInGroup.style.display = 'block';
                    if (donorOutGroup) donorOutGroup.style.display = 'none';
                    
                    const currentPhone = $('#donor_phone').val();
                    if (currentPhone) {
                        $(donorPhoneGroup).show();
                    } else {
                        $(donorPhoneGroup).hide();
                    }
                    
                    $('#donor_name_text').val('');
                } else {
                    if (receiptGroup) receiptGroup.style.display = 'none';
                    if (donorInGroup) donorInGroup.style.display = 'none';
                    if (donorOutGroup) donorOutGroup.style.display = 'block';
                    $(donorPhoneGroup).hide();

                    const hiddenName = $('#donor_name_hidden').val();
                    if (hiddenName && !$('#donor_name_text').val()) {
                        $('#donor_name_text').val(hiddenName);
                    }
                }
            }

            if (typeEl) {
                typeEl.addEventListener('change', toggleFields);
                toggleFields();
            }
        });
    </script>
@endpush
