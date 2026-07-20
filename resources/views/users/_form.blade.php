@php
    $user = $user ?? null;
    $isSelf = $isSelf ?? false;
@endphp
@csrf

<div class="mb-3">
    <label class="form-label" for="name">الاسم</label>
    <input id="name" type="text" name="name"
        value="{{ old('name', $user->name ?? '') }}"
        class="form-control @error('name') is-invalid @enderror"
        required autofocus>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="email">البريد الإلكتروني</label>
    <input id="email" type="email" name="email"
        value="{{ old('email', $user->email ?? '') }}"
        class="form-control @error('email') is-invalid @enderror"
        required>
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label" for="password">كلمة المرور</label>
    <input id="password" type="password" name="password"
        class="form-control @error('password') is-invalid @enderror"
        {{ isset($user) ? '' : 'required' }}>

    @if(isset($user))
        <small class="form-text text-muted">
            اترك الحقل فارغًا إذا كنت لا تريد تغيير كلمة المرور.
        </small>
    @endif

    @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

@php
    $moduleTranslations = [
        'dashboard' => 'لوحة التحكم',
        'budget_categories' => 'تصنيفات الميزانية',
        'budget_operations' => 'عمليات الميزانية',
        'inventory_categories' => 'تصنيفات المخزون',
        'inventory_operations' => 'عمليات المخزون',
        'humanitarian_cases' => 'الحالات الإنسانية',
        'case_referrers' => 'جهات إحالة الحالات',
        'family_members' => 'أفراد العائلة',
        'case_incomes' => 'دخل الحالات',
        'case_expenses' => 'مصاريف الحالات',
        'case_home_descriptions' => 'وصف السكن للحالات',
        'case_needs' => 'احتياجات الحالات',
        'districts' => 'المناطق والمديريات',
        'campaign_categories' => 'تصنيفات الحملات',
        'campaigns' => 'الحملات',
        'humanitarian_case_files' => 'ملفات الحالات',
        'users' => 'المسؤولين والمستخدمين',
    ];

    $actionTranslations = [
        'view' => 'عرض',
        'add' => 'إضافة',
        'edit' => 'تعديل',
        'delete' => 'حذف',
    ];
@endphp

@if(!$isSelf)
<div class="mb-4">
    <label class="form-label d-block mb-3 fw-bold border-bottom pb-2">
        صلاحيات المستخدم
    </label>

    <div class="row row-cols-1 row-cols-md-2 g-3">
        @foreach($permissionGroups as $module => $groupPermissions)
            <div class="col">
                <div class="card h-100 border-0 shadow-sm"
                    style="border-radius:12px;background:#fdfdfd;box-shadow:0 4px 12px rgba(0,0,0,.03)!important;border:1px solid #eaeaea!important;">

                    <div class="card-header bg-white border-0 pt-3 pb-1">
                        <h6 class="card-title mb-0 fw-bold"
                            style="font-size:.95rem;border-bottom:2px solid #0056b3;display:inline-block;padding-bottom:4px;">
                            {{ $moduleTranslations[$module] ?? ucfirst(str_replace('_',' ',$module)) }}
                        </h6>
                    </div>

                    <div class="card-body pt-2">
                        <div class="d-flex flex-wrap gap-2">

                            @foreach($groupPermissions as $permission)

                                @php
                                    $parts = explode('.', $permission->name);
                                    $action = $parts[1] ?? '';
                                    $translatedAction = $actionTranslations[$action] ?? ucfirst($action);
                                    $isChecked = isset($user) && $user->permissions->contains($permission->id);
                                @endphp

                                <div class="form-check form-switch bg-light border rounded px-3 py-2 d-flex align-items-center gap-2">
                                    <input
                                        class="form-check-input ms-0"
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->id }}"
                                        id="perm_{{ $permission->id }}"
                                        {{ $isChecked ? 'checked' : '' }}>

                                    <label
                                        class="form-check-label fw-medium text-secondary"
                                        for="perm_{{ $permission->id }}"
                                        style="cursor:pointer;font-size:.85rem;">
                                        {{ $translatedAction }}
                                    </label>
                                </div>

                            @endforeach

                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    @error('permissions')
        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
    @enderror
</div>
@endif

@if(auth()->user()->isSuperAdmin())
    <div class="mb-3">
        <label class="form-label" for="charity_home_id">بيت الجمعية</label>

        @php
            $assigned = isset($user) && $user->charity_home_id;
            $disableHome = isset($user) && $assigned && $user->id !== 1;
        @endphp

        @if($disableHome)
            <input type="hidden" name="charity_home_id" value="{{ $user->charity_home_id }}">
        @endif

        <select
            id="charity_home_id"
            name="charity_home_id"
            class="form-select @error('charity_home_id') is-invalid @enderror"
            {{ $disableHome ? 'disabled' : '' }}>

            <option value="">بدون بيت جمعية</option>

            @foreach($charityHomes as $home)
                <option
                    value="{{ $home->id }}"
                    {{ (string) old('charity_home_id', $user->charity_home_id ?? '') === (string) $home->id ? 'selected' : '' }}>
                    {{ $home->title }}
                </option>
            @endforeach

        </select>

        @if($disableHome)
            <div class="form-text">
                تم تعيين هذا المستخدم لبيت جمعية، ولا يمكن تغيير الاختيار.
            </div>
        @endif

        @error('charity_home_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
@endif

<div class="form-check mb-3">
    <input
        id="active"
        type="checkbox"
        name="active"
        value="1"
        class="form-check-input"
        {{ old('active', $user->active ?? true) ? 'checked' : '' }}>

    <label class="form-check-label" for="active">
        مفعل
    </label>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">
        حفظ
    </button>

    <a href="{{ route('users.index') }}" class="btn btn-light">
        إلغاء
    </a>
</div>