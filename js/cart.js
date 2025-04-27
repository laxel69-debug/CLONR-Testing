document.addEventListener('DOMContentLoaded', function() {
    const addToCartButton = document.getElementById('add-to-cart');
    if (addToCartButton) {
        addToCartButton.addEventListener('click', function() {
            const size = document.getElementById('size').value;
            const quantity = document.getElementById('quantity').value;

            // Assuming these elements exist on your product page
            const productName = document.querySelector('.product-details h1').textContent;
            const productPriceText = document.querySelector('.product-details p').textContent;
            // Extract the numeric price (remove '₱' and any commas)
            const productPrice = parseFloat(productPriceText.replace('₱', '').replace(',', ''));
            const productImage = document.getElementById('slider-image').src;

            // Assuming you have a way to identify the product ID (e.g., from the filename)
            const productId = window.location.pathname.split('/').pop().split('.')[0];

            // Updated and CORRECTED path to cart_functions.php
            fetch('../../products/payment/cart_functions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'add_to_cart': '1',
                    'pid': productId,
                    'name': productName,
                    'price': productPrice,
                    'image': productImage,
                    'size': size,
                    'quantity': quantity
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Optionally update the cart count in the header here
                    updateCartCount();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to connect to server. Please try again.');
            });
        });
    }

    function updateCartCount() {
        // Updated and CORRECTED path to cart_count.php
        fetch('../../products/payment/cart_count.php')
        .then(response => response.text())
        .then(count => {
            const cartCountSpan = document.querySelector('.cart-count');
            if (cartCountSpan) {
                cartCountSpan.textContent = count;
            }
        })
        .catch(error => {
            console.error('Error fetching cart count:', error);
        });
    }

    // Call updateCartCount on page load to show initial count
    updateCartCount();
});
