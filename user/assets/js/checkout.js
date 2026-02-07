// checkout.js
document.addEventListener('DOMContentLoaded', function () {
    const checkoutButton = document.getElementById('checkout-button');
    const loginMessage = document.getElementById('login-message');

    checkoutButton.addEventListener('click', function () {
        if (checkoutButton.disabled) {
            alert('Bạn cần đăng nhập để thực hiện thanh toán.');
        } else {
            // Thực hiện hành động thanh toán
        }
    });
});