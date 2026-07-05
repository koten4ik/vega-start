@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{\Modules\ZSupport\App\Helpers\H::mtimeFix('/css/shop.css')}}"/>
@endpush

@section('content')

    <div class="container-fluid shop-checkout">
        <h1>Оформление заказа</h1>

        @if($cart->items->isEmpty())
            <p>Корзина пуста</p>
        @else
            <ul class="shop-checkout__items">
                @foreach($cart->items as $item)
                    <li>
                        {{ $item->product->name }} × {{ $item->quantity }}
                        — {{ \Modules\Shop\Services\ProductService::formatPrice($item->subtotal()) }}
                    </li>
                @endforeach
            </ul>

            <div class="shop-checkout__total">
                Итого: {{ \Modules\Shop\Services\ProductService::formatPrice($cart->total()) }}
            </div>

            <form action="{{ route('shop.order.create') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Имя: <span class="form-required">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="form-control {{ $errors->has('name') ? 'input-error' : '' }}">
                    @error('name') <span class="text-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Телефон: <span class="form-required">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                           class="form-control {{ $errors->has('phone') ? 'input-error' : '' }}">
                    @error('phone') <span class="text-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Email:</label>
                    <input type="text" name="email" value="{{ old('email') }}"
                           class="form-control {{ $errors->has('email') ? 'input-error' : '' }}">
                    @error('email') <span class="text-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Адрес доставки: <span class="form-required">*</span></label>
                    <input type="text" name="address" value="{{ old('address') }}" required
                           class="form-control {{ $errors->has('address') ? 'input-error' : '' }}">
                    @error('address') <span class="text-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Комментарий:</label>
                    <textarea name="comment" class="form-control">{{ old('comment') }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Оформить заказ</button>
            </form>
        @endif
    </div>

@endsection

@push('scripts')
    <script></script>
@endpush
