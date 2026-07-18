@php
    $user = $user ?? null;
@endphp
@csrf

<div class="mb-3">
    <label class="form-label" for="name">الاسم</label>
    <input id="name" type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label" for="email">البريد الإلكتروني</label>
    <input id="email" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control @error('email') is-invalid @enderror" required>
    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label" for="password">كلمة المرور</label>
    <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ isset($user) ? '' : 'required' }}>
    <small class="form-text text-muted">{{ isset($user) ? 'اترك الحقل فارغًا إذا كنت لا تريد تغيير كلمة المرور.' : '' }}</small>
    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label" for="roles">الأدوار</label>
    <select id="roles" name="roles[]" class="form-select @error('roles') is-invalid @enderror" multiple>
        @foreach($roles as $role)
            <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>{{ $role->name }}</option>
        @endforeach
    </select>
    @error('roles')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label" for="charity_home_id">بيت الجمعية</label>
    @php
        $assigned = isset($user) && $user->charity_home_id;
        $disableHome = isset($user) && $assigned && $user->id !== 1;
    @endphp

    @if($disableHome)
        <input type="hidden" name="charity_home_id" value="{{ $user->charity_home_id }}">
    @endif

    <select id="charity_home_id" name="charity_home_id" class="form-select @error('charity_home_id') is-invalid @enderror" {{ $disableHome ? 'disabled' : '' }}>
        <option value="">بدون بيت جمعية</option>
        @foreach($charityHomes as $home)
            <option value="{{ $home->id }}" {{ (string) old('charity_home_id', $user->charity_home_id ?? '') === (string) $home->id ? 'selected' : '' }}>{{ $home->title }}</option>
        @endforeach
    </select>

    @if($disableHome)
        <div class="form-text">تم تعيين هذا المستخدم لبيت جمعية، ولا يمكن تغيير الاختيار.</div>
    @endif

    @error('charity_home_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="form-check mb-3">
    <input id="active" type="checkbox" name="active" value="1" class="form-check-input" {{ old('active', $user->active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="active">مفعل</label>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary">حفظ</button>
    <a href="{{ route('users.index') }}" class="btn btn-light">إلغاء</a>
</div>
