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
                        <div class="card card-body mb-0 bg-body auth-login m-auto w-100 mt-4 mb-4">
                            <div class="row gx-0">
                                <div class="col-xl-12 text-dark">
                                    <div class="text-center">
                                        <a href="/" class="d-inline-block">
                                            <img src="/images/logo.svg" alt="Newecs"
                                                 class="img-fluid d-block mx-auto mb-4">
                                        </a>
                                    </div>
                                    <h3 class="text-center">Восстановление пароля</h3>

                                    @if(request()->has('sended'))
                                        <div style="padding: 60px; text-align: center; color: green">
                                            Дальнейшие инструкции отправлены на адрес вашей электронной почты.
                                        </div>
                                    @else
                                        <form action="{{ route('user.password.recovery') }}" method="POST" novalidate
                                              autocomplete="off">
                                            @csrf


                                            <div class="form-group">
                                                <label>Логин или адрес электронной почты: <span class="form-required"
                                                                                                title="Обязательное поле">*</span></label>
                                                <input type="text" name="login" value="{{ old('login') }}" required
                                                       class="form-control {{ $errors->has('login') ? 'input-error' : '' }}">
                                                @error('login') <span class="text-error">{{ $message }}</span> @enderror
                                            </div>


                                            <input type="submit" name="op" id="edit-submit"
                                                   value="Отправить новый пароль на почту"
                                                   class="btn btn-primary mb-4 form-submit">
                                        </form>
                                    @endif

                                    <div class="d-flex justify-content-between">
                                        <a href="{{\Modules\SitePage\Services\PageService::byModule(Modules\SitePage\Enums\PageModule::LOGIN)->url ?? '/login'}}"
                                           class="">
                                            Вход
                                        </a>
                                        <a href="{{\Modules\SitePage\Services\PageService::byModule(Modules\SitePage\Enums\PageModule::REGISTRATION)->url ?? '/registration'}}"
                                           class="">
                                            Регистрация
                                        </a>
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
    <script>
    </script>
@endpush
