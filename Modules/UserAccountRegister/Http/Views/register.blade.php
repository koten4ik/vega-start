@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{\Modules\ZSupport\App\Helpers\H::mtimeFix('/css/auth.css')}}"/>
@endpush

@section('content')

    <div class="auth-page">
        <div class="auth-card">
            <h1>Регистрация</h1>

            <form action="{{ route('user.register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Имя: <span class="form-required">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="form-control {{ $errors->has('name') ? 'input-error' : '' }}">
                    @error('name') <span class="text-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Логин: <span class="form-required">*</span></label>
                    <input type="text" name="login" value="{{ old('login') }}" required
                           class="form-control {{ $errors->has('login') ? 'input-error' : '' }}">
                    @error('login') <span class="text-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Email: <span class="form-required">*</span></label>
                    <input type="text" name="email" value="{{ old('email') }}" required
                           class="form-control {{ $errors->has('email') ? 'input-error' : '' }}">
                    @error('email') <span class="text-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Пароль: <span class="form-required">*</span></label>
                    <input type="password" name="password" required
                           class="form-control {{ $errors->has('password') ? 'input-error' : '' }}">
                    @error('password') <span class="text-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Повторите пароль: <span class="form-required">*</span></label>
                    <input type="password" name="password_repeat" required
                           class="form-control {{ $errors->has('password_repeat') ? 'input-error' : '' }}">
                    @error('password_repeat') <span class="text-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Телефон: <span class="form-required">*</span></label>
                    <input type="text" name="profile_phone" id="profile_phone" value="{{ old('profile_phone') }}"
                           placeholder="+7 (___) ___-__-__" required
                           class="form-control {{ $errors->has('profile_phone') ? 'input-error' : '' }}">
                    @error('profile_phone') <span class="text-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group form-group--checkbox">
                    <label>
                        <input type="checkbox" name="agreement" value="1" required>
                        Согласен с обработкой персональных данных
                    </label>
                    @error('agreement') <span class="text-error">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
            </form>

            <div class="auth-card__links">
                <a href="{{ route('user.auth.loginPage') }}">Вход</a>
                <a href="{{ route('user.password.recoveryPage') }}">Восстановление пароля</a>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ \Modules\ZSupport\App\Helpers\H::mtimeFix('/js/auth.js') }}" defer></script>
@endpush
