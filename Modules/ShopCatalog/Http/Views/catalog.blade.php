@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{\Modules\ZSupport\App\Helpers\H::mtimeFix('/css/shop.css')}}"/>
@endpush

@section('content')

    <div class="container-fluid shop-catalog">
        <h1>Каталог</h1>

        <div class="shop-catalog__layout">
            <aside class="shop-catalog__categories">
                <ul>
                    <li><a href="{{ route('shop.catalog.page') }}" class="{{ $category_slug === null ? 'active' : '' }}">Все товары</a></li>
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('shop.catalog.category', $category->slug) }}"
                               class="{{ $category_slug === $category->slug ? 'active' : '' }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </aside>

            <div class="shop-catalog__products">
                @forelse($products as $product)
                    <div class="shop-product-card">
                        <a href="{{ route('shop.catalog.product', $product->slug) }}">
                            @if($product->image)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($product->image) }}" alt="{{ $product->name }}">
                            @endif
                            <div class="shop-product-card__name">{{ $product->name }}</div>
                        </a>
                        <div class="shop-product-card__price">
                            {{ \Modules\Shop\Services\ProductService::formatPrice($product->price) }}
                        </div>
                        <form action="{{ route('shop.cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit">В корзину</button>
                        </form>
                    </div>
                @empty
                    <p>Товары не найдены</p>
                @endforelse
            </div>
        </div>

        {{ $products->links() }}
    </div>

@endsection

@push('scripts')
    <script></script>
@endpush
