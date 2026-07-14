@extends('layouts.guest')

@section('title', 'تسجيل الدخول')

@section('content')
    <div class="auth-heading">
        <h1>تسجيل الدخول</h1>
        <p>ادخل إلى لوحة إدارة الجمعية لمتابعة التبرعات والحملات.</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="vstack gap-3">
        @csrf

        <div>
            <label class="form-label" for="email">البريد الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="email" autofocus required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="form-label" for="password">كلمة المرور</label>
            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="current-password" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember">تذكرني</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">دخول</button>
    </form>

    <p class="auth-link">ليس لديك حساب؟ <a href="{{ route('register') }}">إنشاء حساب جديد</a></p>
@endsection
