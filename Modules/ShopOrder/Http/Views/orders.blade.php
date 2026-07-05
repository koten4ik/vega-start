@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{\Modules\ZSupport\App\Helpers\H::mtimeFix('/css/shop.css')}}"/>
@endpush

@section('content')

    <div class="container-fluid shop-orders">
        <h1>Мои заказы</h1>

        @forelse($orders as $order)
            <div class="shop-orders__item">
                <a href="{{ route('shop.order.success', $order->id) }}">
                    Заказ №{{ $order->id }} от {{ $order->created_at->format('d.m.Y') }}
                </a>
                — {{ $order->status->label() }}
                — {{ \Modules\Shop\Services\ProductService::formatPrice($order->total) }}
            </div>
        @empty
            <p>Заказов пока нет</p>
        @endforelse
    </div>

@endsection

@push('scripts')
    <script></script>
@endpush
