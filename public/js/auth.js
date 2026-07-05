document.addEventListener('DOMContentLoaded', function () {

    var phoneInput = document.getElementById('profile_phone');
    if (!phoneInput) return;

    phoneInput.addEventListener('input', function () {
        var digits = phoneInput.value.replace(/\D/g, '');

        if (digits.charAt(0) === '8') digits = '7' + digits.slice(1);
        if (digits.charAt(0) !== '7') digits = '7' + digits;
        digits = digits.slice(0, 11);

        var formatted = '+7';
        if (digits.length > 1) formatted += ' (' + digits.slice(1, 4);
        if (digits.length >= 4) formatted += ') ' + digits.slice(4, 7);
        if (digits.length >= 7) formatted += '-' + digits.slice(7, 9);
        if (digits.length >= 9) formatted += '-' + digits.slice(9, 11);

        phoneInput.value = formatted;
    });

});
