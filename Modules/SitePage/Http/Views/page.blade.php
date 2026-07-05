@extends('layouts.main')

@section('content')

    <div class="container-fluid">
        <h1>{{ $page['title'] }}</h1>
        {!! $page['content'] !!}
    </div>

@endsection

@push('scripts')
    <script>

    </script>
@endpush
