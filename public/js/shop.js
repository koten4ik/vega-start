document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('input[type="number"][max]').forEach(function (input) {
        input.addEventListener('change', function () {
            var max = parseInt(input.getAttribute('max'), 10);
            var min = parseInt(input.getAttribute('min') || '0', 10);
            var value = parseInt(input.value, 10) || min;

            if (value > max) value = max;
            if (value < min) value = min;

            input.value = value;
        });
    });

    document.querySelectorAll('.shop-cart form[action*="/cart/remove"]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            if (!confirm('Удалить товар из корзины?')) {
                e.preventDefault();
            }
        });
    });

});
