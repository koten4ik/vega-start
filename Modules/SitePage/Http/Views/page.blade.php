@extends('layouts.main')

@push('styles')
    <style></style>
@endpush

@section('content')

    <div class="container-fluid">
        <h1>{{$page->name}}</h1>
        {!! $page->text !!}
    </div>

@endsection

@push('scripts')
    <script>

    </script>
@endpush
