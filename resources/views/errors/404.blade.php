@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{\Modules\ZSupport\App\Helpers\H::mtimeFix('/css/errors.css')}}"/>
@endpush

@section('content')
    <div class="container-fluid page-404">

        <div class="page-404__content">
            <div class="page-404__code">
                404
            </div>

            <div class="page-404__text">
                Страница не найдена
            </div>
        </div>

    </div>
@endsection
