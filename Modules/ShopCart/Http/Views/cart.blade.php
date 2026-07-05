@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{\Modules\ZSupport\App\Helpers\H::mtimeFix('/css/shop.css')}}"/>
@endpush

@section('content')

    <div class="container-fluid shop-cart">
        <h1>Корзина</h1>

        @if($cart['is_empty'])
            <p>Корзина пуста</p>
        @else
            <table class="shop-cart__table">
                <thead>
                <tr>
                    <th>Товар</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($cart['items'] as $item)
                    <tr>
                        <td>{{ $item['product']['name'] }}</td>
                        <td>{{ $item['price'] }}</td>
                        <td>
                            <form action="{{ route('shop.cart.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="cart_item_id" value="{{ $item['id'] }}">
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0">
                                <button type="submit">Обновить</button>
                            </form>
                        </td>
                        <td>{{ $item['subtotal'] }}</td>
                        <td>
                            <form action="{{ route('shop.cart.remove') }}" method="POST">
                                @csrf
                                <input type="hidden" name="cart_item_id" value="{{ $item['id'] }}">
                                <button type="submit">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="shop-cart__total">
                Итого: {{ $cart['total'] }}
            </div>

            <a href="{{ route('shop.order.checkoutPage') }}" class="btn btn-primary">Оформить заказ</a>
        @endif
    </div>

@endsection

@push('scripts')
    <script src="{{ \Modules\ZSupport\App\Helpers\H::mtimeFix('/js/shop.js') }}" defer></script>
@endpush
