@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{\Modules\ZSupport\App\Helpers\H::mtimeFix('/css/user_profile.css')}}"/>
@endpush

@section('content')

    <div class="container-fluid user-profile">
        <h1>Профиль</h1>

        @if(request()->has('saved'))
            <div class="user-profile__notice">Изменения сохранены</div>
        @endif

        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="user-profile__avatar">
                <img src="{{ $profile['avatar'] }}" alt="Аватар">
                <input type="file" name="avatar" accept="image/*">
                @error('avatar') <span class="text-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Имя: <span class="form-required">*</span></label>
                <input type="text" name="name" value="{{ old('name', $profile['name']) }}" required
                       class="form-control {{ $errors->has('name') ? 'input-error' : '' }}">
                @error('name') <span class="text-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Логин: <span class="form-required">*</span></label>
                <input type="text" name="login" value="{{ old('login', $profile['login']) }}" required
                       class="form-control {{ $errors->has('login') ? 'input-error' : '' }}">
                @error('login') <span class="text-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Email: <span class="form-required">*</span></label>
                <input type="text" name="email" value="{{ old('email', $profile['email']) }}" required
                       class="form-control {{ $errors->has('email') ? 'input-error' : '' }}">
                @error('email') <span class="text-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Телефон:</label>
                <input type="text" name="profile_phone" value="{{ old('profile_phone', $profile['profile_phone']) }}"
                       class="form-control {{ $errors->has('profile_phone') ? 'input-error' : '' }}">
                @error('profile_phone') <span class="text-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>

@endsection

@push('scripts')
    <script></script>
@endpush
