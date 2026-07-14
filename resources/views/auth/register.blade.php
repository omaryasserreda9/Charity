@extends('layouts.guest')

@section('title', 'إنشاء حساب')

@section('content')
    <div class="auth-heading">
        <h1>إنشاء حساب</h1>
        <p>أضف حسابًا جديدًا للوصول إلى لوحة إدارة الجمعية.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="vstack gap-3">
        @csrf

        <div>
            <label class="form-label" for="name">الاسم</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" autocomplete="name" autofocus required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="form-label" for="email">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="email" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="form-label" for="password">كلمة المرور</label>
            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="form-label" for="password_confirmation">تأكيد كلمة المرور</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" autocomplete="new-password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">إنشاء الحساب</button>
    </form>

    <p class="auth-link">لديك حساب بالفعل؟ <a href="{{ route('login') }}">تسجيل الدخول</a></p>
@endsection
