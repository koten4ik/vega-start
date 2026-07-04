@extends('layouts.reg')

@push('styles')
    <style></style>
@endpush

@section('content')

    <div id="main-wrapper">
        <div
                class="position-relative overflow-hidden min-vh-100 w-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100 my-5 my-xl-0">
                    <div class="col-md-4 d-flex flex-column justify-content-center">
                        <span class="card card-body mb-0 bg-body auth-login m-auto w-100 mt-4 mb-4">
                            <span class="row gx-0">
                                <span class="col-xl-12 text-dark">
                                    <div class="text-center">
                                        <a href="/" class="d-inline-block">
                                            <img src="/images/logo.svg" alt="Newecs"
                                                 class="img-fluid d-block mx-auto mb-4">
                                        </a>
                                    </div>
                                    <h3 class="text-center">Регистрация</h3>

                                    <form action="{{ route('user.register') }}" method="POST" novalidate
                                          autocomplete="off">
                                        @csrf

                                        <div class="form-group">
                                            <label>Логин вашего спонсора: <span class="form-required" title="Обязательное поле">*</span></label>
                                            <input type="text" name="sponsor_login"
                                                   value="{{ old('sponsor_login', session()->get('referrer_login')) }}"
                                                   class="form-control {{ $errors->has('sponsor_login') ? 'input-error' : '' }}"
                                                   {{ session()->get('referrer_login') ? 'readonly' : '' }} required>
                                            @error('sponsor_login') <span class="text-error">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Ваш логин: <span class="form-required" title="Обязательное поле">*</span></label>
                                            <input type="text" name="login" value="{{ old('login') }}" required
                                                   class="form-control {{ $errors->has('login') ? 'input-error' : '' }}">
                                            @error('login') <span class="text-error">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Адрес электронной почты: <span class="form-required" title="Обязательное поле">*</span></label>
                                            <input type="email" name="email" value="{{ old('email') }}" required
                                                   class="form-control {{ $errors->has('email') ? 'input-error' : '' }}">
                                            @error('email') <span class="text-error">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Пароль: <span class="form-required" title="Обязательное поле">*</span></label>
                                            <input type="password" name="password" required
                                                   class="form-control {{ $errors->has('password') ? 'input-error' : '' }}">
                                            @error('password') <span class="text-error">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Подтвердите пароль: <span class="form-required" title="Обязательное поле">*</span></label>
                                            <input type="password" name="password_repeat" required
                                                   class="form-control {{ $errors->has('password_repeat') ? 'input-error' : '' }}">
                                            @error('password_repeat') <span
                                                    class="text-error">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Пол: <span class="form-required" title="Обязательное поле">*</span></label>
                                            <select name="profile_gender"
                                                    class="form-control required {{ $errors->has('profile_gender') ? 'input-error' : '' }}">
                                                <option value="">-- Выберите --</option>
                                                @foreach(\Modules\User\Enums\UserGender::cases() as $gender)
                                                    <option
                                                            value="{{ $gender->value }}"
                                                            {{  old('profile_gender') == $gender->value ? 'selected' : '' }}
                                                    >
                                                        {{ $gender->label() }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('profile_gender') <span class="text-error">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Фамилия: <span class="form-required" title="Обязательное поле">*</span></label>
                                            <input type="text" name="profile_last_name" id="profile_last_name"
                                                   value="{{ old('profile_last_name') }}" required
                                                   class="form-control {{ $errors->has('profile_last_name') ? 'input-error' : '' }}">
                                            @error('profile_last_name') <span class="text-error">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Имя: <span class="form-required" title="Обязательное поле">*</span></label>
                                            <input type="text" name="profile_first_name" id="profile_first_name"
                                                   value="{{ old('profile_first_name') }}" required
                                                   class="form-control {{ $errors->has('profile_first_name') ? 'input-error' : '' }}">
                                            @error('profile_first_name') <span class="text-error">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Отчество:</label>
                                            <input type="text" name="profile_middle_name" id="profile_middle_name"
                                                   value="{{ old('profile_middle_name') }}"
                                                   class="form-control {{ $errors->has('profile_middle_name') ? 'input-error' : '' }}">
                                            @error('profile_middle_name') <span class="text-error">{{ $message }}</span> @enderror
                                        </div>

                                         <div class="form-group">
                                            <label for="edit-profile-birthday">
                                                Дата рождения:
                                                <span class="form-required" title="Обязательное поле">*</span>
                                            </label>

                                            <div class="container-inline">

                                                {{-- Год --}}
                                                <div class="form-group">
                                                    <select
                                                        name="profile_birthday[year]"
                                                        id="edit-profile-birthday-year"
                                                        class="form-select form-control @error('profile_birthday.year') is-invalid @enderror"
                                                        required
                                                    >
                                                        <option value="">Год</option>

                                                        @for($year = now()->year; $year >= 1900; $year--)
                                                            <option
                                                                value="{{ $year }}"
                                                                {{ old('profile_birthday.year') == $year ? 'selected' : '' }}
                                                            >
                                                                {{ $year }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>

                                                {{-- Месяц --}}
                                                <div class="form-group">
                                                    <select
                                                        name="profile_birthday[month]"
                                                        id="edit-profile-birthday-month"
                                                        class="form-select form-control @error('profile_birthday.month') is-invalid @enderror"
                                                        required
                                                    >
                                                        <option value="">Месяц</option>

                                                        @foreach(config('variables.months') as $number => $name)
                                                            <option
                                                                value="{{ $number }}"
                                                                {{ old('profile_birthday.month') == $number ? 'selected' : '' }}
                                                            >
                                                                {{ $name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- День --}}
                                                <div class="form-group">
                                                    <select
                                                        name="profile_birthday[day]"
                                                        id="edit-profile-birthday-day"
                                                        class="form-select form-control @error('profile_birthday.day') is-invalid @enderror"
                                                        required
                                                    >
                                                        <option value="">День</option>

                                                        @for($day = 1; $day <= 31; $day++)
                                                            <option
                                                                value="{{ $day }}"
                                                                {{ old('profile_birthday.day') == $day ? 'selected' : '' }}
                                                            >
                                                                {{ $day }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>

                                            </div>

                                            @error('profile_birthday.year')
                                            <span class="text-error">{{ $message }}</span>
                                            @enderror

                                             @error('profile_birthday.month')
                                            <span class="text-error">{{ $message }}</span>
                                            @enderror

                                             @error('profile_birthday.day')
                                            <span class="text-error">{{ $message }}</span>
                                            @enderror

                                             @error('profile_birthday')
                                            <span class="text-error">{{ $message }}</span>
                                            @enderror

                                            <div class="description">
                                                Содержимое этого поля является приватным и не будет отображаться публично.
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Страна: <span class="form-required" title="Обязательное поле">*</span></label>
                                            <select id="country_id" name="country_id" required class="form-control {{ $errors->has('country_id') ? 'input-error' : '' }}">
                                                @foreach(\Modules\ZSupport\App\Models\Country::where('display',true)->orderBy('rank','DESC')->get() as $country)
                                                    <option
                                                            value="{{ $country->id }}"
                                                            data-cyrillic-only="{{ $country->cyrillic_only ? 1 : 0 }}"
                                                            {{ old('country_id') == $country->id ? 'selected' : '' }}
                                                    >
                                                        {{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('country_id') <span class="text-error">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="form-group">
                                            <label>Номер телефона: <span class="form-required"
                                                                         title="Обязательное поле">*</span></label>
                                            <input type="text" name="profile_phone" id="profile_phone"
                                                   value="{{ old('profile_phone') }}"
                                                   class="form-control {{ $errors->has('profile_phone') ? 'input-error' : '' }}"
                                                   required>
                                            @error('profile_phone') <span
                                                    class="text-error">{{ $message }}</span> @enderror
                                        </div>

                                        <fieldset>
                                            <div class="checkbox">
                                                <label class="option" for="edit-profile-conditions1">
                                                    <input type="checkbox" name="profile_conditions1" required
                                                           id="edit-profile-conditions1" class="form-checkbox required">
                                                    Я ознакомился и принимаю условия <a href="/user-agreement"
                                                                                        target="-_blank">"Пользовательского
                                                        соглашения"</a> <span class="form-required"
                                                                              title="Обязательное поле">*</span>
                                                </label>
                                                @error('profile_conditions1') <span
                                                        class="text-error">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="checkbox"><label class="option" for="edit-profile-conditions2">
                                                    <input type="checkbox" name="profile_conditions2" required
                                                           id="edit-profile-conditions2" class="form-checkbox required">
                                                    Я ознакомился и принимаю условия <a href="/agreement"
                                                                                        target="-_blank">"Согласие
                                                        на обработку персональных данных"</a> <span
                                                            class="form-required"
                                                            title="Обязательное поле">*</span></label>
                                                @error('profile_conditions2') <span
                                                        class="text-error">{{ $message }}</span> @enderror
                                            </div>
                                        </fieldset>

                                        <input type="submit" name="op" id="edit-submit" value="Регистрация"
                                               class="btn btn-primary mb-4 form-submit">
                                    </form>


                                    <div class="d-flex justify-content-between">
                                        <a href="{{\Modules\SitePage\Services\PageService::byModule(Modules\SitePage\Enums\PageModule::LOGIN)->url ?? '/login'}}"
                                           class="">
                                            Вход
                                        </a>
                                        <a href="/password" class="">Восстановление пароля</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/inputmask.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var selector = document.getElementById("profile_phone");
            var im = new Inputmask("+7 (999) 999-99-99");
            im.mask(selector);
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const countrySelect = document.getElementById('country_id');

            const fioFields = [
                document.getElementById('profile_last_name'),
                document.getElementById('profile_first_name'),
                document.getElementById('profile_middle_name'),
            ];

            function isCyrillicOnlyCountry() {
                const selectedOption = countrySelect.options[countrySelect.selectedIndex];

                return selectedOption.dataset.cyrillicOnly === '1';
            }

            function sanitizeField(field) {

                if (!isCyrillicOnlyCountry()) {
                    return;
                }

                // оставляем только кириллицу, пробел и дефис
                field.value = field.value.replace(/[^А-Яа-яЁёІіЇїЄєҐґ\s-]/g, '');
            }

            fioFields.forEach(field => {

                field.addEventListener('input', function () {
                    sanitizeField(field);
                });

            });

            // При смене страны сразу чистим поля
            countrySelect.addEventListener('change', function () {

                fioFields.forEach(field => {
                    sanitizeField(field);
                });

            });

        });
    </script>
@endpush
