@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{\Modules\ZSupport\App\Helpers\H::mtimeFix('/css/shop.css')}}"/>
@endpush

@section('content')

    <div class="container-fluid shop-order-success">
        <h1>Заказ №{{ $order->id }} принят</h1>
        <p>Статус: {{ $order->status->label() }}</p>
        <p>Сумма: {{ \Modules\Shop\Services\ProductService::formatPrice($order->total) }}</p>
    </div>

@endsection

@push('scripts')
    <script></script>
@endpush
