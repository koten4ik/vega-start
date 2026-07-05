<?php

namespace Modules\Shop\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Modules\Shop\Models\ProductModel;

class ProductQuantityRule implements ValidationRule, DataAwareRule
{
    protected array $data = [];

    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $product = ProductModel::find($this->data['product_id'] ?? null);

        if ($product === null) {
            $fail('Товар не найден');
            return;
        }

        if ($product->quantity < (int)$value) {
            $fail('Недостаточно товара на складе. В наличии: ' . $product->quantity);
        }
    }
}
