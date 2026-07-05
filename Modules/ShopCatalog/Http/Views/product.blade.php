@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{\Modules\ZSupport\App\Helpers\H::mtimeFix('/css/shop.css')}}"/>
@endpush

@section('content')

    <div class="container-fluid shop-product">
        <h1>{{ $product['name'] }}</h1>

        @if($product['image'])
            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}">
        @endif

        <div class="shop-product__price">
            {{ $product['price'] }}
        </div>

        <div class="shop-product__description">
            {!! $product['description'] !!}
        </div>

        @if($product['in_stock'])
            <form action="{{ route('shop.cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product['id'] }}">
                <input type="number" name="quantity" value="1" min="1" max="{{ $product['quantity'] }}">
                <button type="submit">В корзину</button>
            </form>
        @else
            <p>Нет в наличии</p>
        @endif
    </div>

@endsection

@push('scripts')
    <script src="{{ \Modules\ZSupport\App\Helpers\H::mtimeFix('/js/shop.js') }}" defer></script>
@endpush
