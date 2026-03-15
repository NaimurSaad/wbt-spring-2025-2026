const taxRate = .05;
const originalPriceInput = document.getElementById('original-price');
const discountInput = document.getElementById('discount');
const finalPriceInput = document.getElementById('final-price')
const priceErrorMessage = document.getElementById('price-error-message');
const discountErrorMessage = document.getElementById('discount-error-message');

let budgetDealAlertShown = false;

function updateFinalPrice() {
    let originalPrice = Number(originalPriceInput.value);
    let discount = Number(discountInput.value);

    if (Number.isNaN(originalPrice)) {
        originalPrice = 0;
    }

    if (Number.isNaN(discount)) {
        discount = 0;
    }

    if (originalPrice < 0) {
        originalPrice = 0;
        originalPriceInput.value = 0;
        priceErrorMessage.textContent = 'Price cannot be less than 0.';
    } else {
        priceErrorMessage.textContent = '';
    }

    if (discount < 0) {
        discount = 0;
        discountInput.value = 0;
        discountErrorMessage.textContent = 'Discount percentage cannot be less than 0.';
    } else if (discount > 100) {
        discount = 100;
        discountInput.value = 100;
        discountErrorMessage.textContent = 'Discount percentage cannot exceed 100';
    } else {
        discountErrorMessage.textContent = '';
    }

    const discountAmount = originalPrice * (discount / 100);
    const tax = (originalPrice - discountAmount) * taxRate;
    const finalPrice = (originalPrice - discountAmount) + tax;
    finalPriceInput.value = `৳${finalPrice}`;

    if (finalPrice > 0 && finalPrice < 500 && !budgetDealAlertShown) {
        alert('You unlocked a budget deal!');
        budgetDealAlertShown = true;
    }
    if (finalPrice >= 500) {
        budgetDealAlertShown = false;
    }

}
originalPriceInput.addEventListener('input', updateFinalPrice);
discountInput.addEventListener('input', updateFinalPrice);
updateFinalPrice();


