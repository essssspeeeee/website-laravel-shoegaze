<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shoegaze - Dashboard')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #ffffff; }
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="antialiased text-gray-900">

    @include('components.header')

    @yield('content')

    @include('components.footer')

    @stack('scripts')

    <script>
        // Global function for adding to cart
        function addToCart(productId, size = null, qty = 1) {
            console.log('addToCart called with:', { productId, size, qty });

            const formData = new FormData();
            formData.append('quantity', qty);
            if (size) {
                formData.append('size', size);
            }

            const csrfToken = document.querySelector('meta[name=csrf-token]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                alert('Error: CSRF token missing');
                return;
            }

            fetch(`/cart/add/${productId}`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text().then(text => {
                    console.log('Raw response text:', text);
                    const contentType = response.headers.get('content-type') || '';
                    if (response.ok && contentType.includes('application/json')) {
                        return JSON.parse(text);
                    }
                    throw new Error(`Unexpected response type or status ${response.status}`);
                });
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success || data.message) {
                    // Dispatch event for notifications
                    window.dispatchEvent(new CustomEvent('add-to-cart', {
                        detail: { product: data.product_name || 'Produk berhasil ditambahkan ke keranjang' }
                    }));

                    // Refresh cart count
                    refreshCartCount();
                } else {
                    throw new Error('Invalid response data');
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                alert('Gagal menambahkan ke keranjang: ' + error.message);
            });
        }

        // Function to refresh cart count
        function refreshCartCount() {
            console.log('Refreshing cart count...');
            const csrfToken = document.querySelector('meta[name=csrf-token]');
            if (!csrfToken) {
                console.error('CSRF token not found for cart count');
                return;
            }

            fetch('/cart/count', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.content,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error(`Failed to fetch cart count: ${response.status}`);
                }
            })
            .then(data => {
                console.log('Cart count data:', data);
                const badge = document.getElementById('cart-count');
                if (badge) {
                    if (data.count && data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                } else {
                    console.error('Cart count element not found');
                }
            })
            .catch(error => {
                console.error('Error refreshing cart count:', error);
            });
        }

        // Initialize cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            refreshCartCount();
        });

        // Listen for add-to-cart event to refresh counter
        window.addEventListener('add-to-cart', function() {
            refreshCartCount();
        });
    </script>
</body>
</html>
