@extends('layouts.main')

@push('styles')
    <style></style>
@endpush

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
