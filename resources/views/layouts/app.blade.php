<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'KopiKu Café')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />

    <!-- FIREBASE CDN IMPORTS -->
    <!-- END FIREBASE CDN IMPORTS -->

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#A0522D',
                        /* coklat kopi */
                        cream: '#FBF6F0',
                        /* cream hangat */
                        accent: '#6B3E1A',
                        /* coklat gelap */
                    },
                    fontFamily: {
                        serif: ['Georgia', 'Cambria', 'Times New Roman', 'serif'],
                        sans: ['Helvetica', 'Arial', 'sans-serif'],
                    }
                },
            },
        }
    </script>

    <style>
        body {
            background-color: #FBF6F0;
            font-family: 'Helvetica', Arial, sans-serif;
        }

        .fade-in {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cart-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            background-color: #ef4444;
            color: white;
            font-size: 12px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            z-index: 10;
        }

        /* Custom styles for category buttons */
        .category-btn.active {
            background-color: var(--primary, #A0522D);
            color: var(--cream, #FBF6F0);
            box-shadow: 0 10px 15px -3px rgba(160, 82, 45, 0.4), 0 4px 6px -2px rgba(160, 82, 45, 0.2);
        }

        .category-btn {
            white-space: nowrap;
            /* Mencegah wrap */
        }
    </style>

    @yield('styles')
</head>

<body class="relative">

    <!-- Header Nav Bar -->
    <nav class="bg-primary text-cream shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center py-4">
                <a href="{{ url('/') }}"
                    class="flex items-center space-x-2 font-serif text-2xl font-bold hover:text-yellow-300 transition">
                    <i class="fas fa-coffee text-3xl"></i>
                    <span>KopiKu Café</span>
                </a>
                <div class="flex items-center space-x-8 text-cream font-semibold text-lg">
                    <a href="{{ route('menu') }}"
                        class="hover:text-yellow-300 flex items-center gap-2 {{ request()->is('menu*') || request()->is('/') ?: '' }}">
                        <i class="fas fa-home"></i> Menu
                    </a>
                    <button id="cart-toggle" class="relative hover:text-yellow-300 focus:outline-none"
                        aria-label="Keranjang Belanja">
                        <i class="fas fa-shopping-cart text-2xl"></i>
                        <span id="cart-badge" class="cart-badge hidden">0</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen max-w-7xl mx-auto px-6 py-10">
        @yield('content')
    </main>

    <!-- Sidebar Keranjang -->
    <div id="cart-sidebar"
        class="fixed inset-y-0 right-0 w-full max-w-sm bg-cream shadow-xl transform translate-x-full transition-transform duration-300 z-50 flex flex-col rounded-l-3xl overflow-hidden">

        <div class="p-6 flex justify-between items-center border-b border-accent">
            <h3 class="text-xl font-bold text-accent flex items-center gap-2">
                <i class="fas fa-shopping-cart"></i> Keranjang Belanja
            </h3>
            <button id="cart-close" class="text-accent hover:text-primary focus:outline-none"
                aria-label="Tutup Keranjang">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <div id="cart-items" class="flex-grow overflow-y-auto p-6 space-y-4 text-accent">
            <p class="italic text-center mt-20 text-gray-500">
                Keranjang kosong, silakan tambah produk.
            </p>
        </div>

        <div class="p-6 border-t border-accent bg-cream">
            <div class="flex justify-between items-center mb-5">
                <span class="font-semibold text-accent text-lg">Total:</span>
                <span id="cart-total" class="text-primary font-extrabold text-2xl">Rp 0</span>
            </div>
            <button id="checkout-btn"
                class="w-full bg-primary hover:bg-accent text-cream font-bold py-3 rounded-lg transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                <i class="fas fa-paper-plane mr-2"></i> Pesan Sekarang
            </button>
        </div>
    </div>

    <!-- Overlay -->
    <div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-40 hidden z-40"></div>

    @if (session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 fade-in">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 fade-in">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartSidebar = document.getElementById('cart-sidebar');
            const cartToggle = document.getElementById('cart-toggle');
            const cartOverlay = document.getElementById('cart-overlay');
            const cartClose = document.getElementById('cart-close');
            const cartItemsContainer = document.getElementById('cart-items');
            const cartTotalElement = document.getElementById('cart-total');
            const cartCountElement = document.querySelector('.cart-count');
            const checkoutBtn = document.getElementById('checkout-btn');

            const CART_KEY = 'shopping_cart';

            const getCart = () => {
                const cart = localStorage.getItem(CART_KEY);
                return cart ? JSON.parse(cart) : [];
            };

            const saveCart = (cart) => {
                localStorage.setItem(CART_KEY, JSON.stringify(cart));
                window.dispatchEvent(new CustomEvent('cart-updated'));
            };

            const addToCart = (product) => {
                const cart = getCart();
                const existing = cart.find(item => item.id === product.id);

                if (existing) {
                    existing.quantity += 1;
                } else {
                    cart.push({
                        ...product,
                        quantity: 1
                    });
                }

                saveCart(cart);
                alert(`${product.name} ditambahkan!`);
            };

            const renderCart = () => {
                const cart = getCart();
                cartItemsContainer.innerHTML = '';

                if (cart.length === 0) {
                    cartItemsContainer.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <p>Keranjang kosong</p>
                </div>
            `;
                    cartTotalElement.textContent = 'Rp 0';
                    return;
                }

                let total = 0;

                cart.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    total += itemTotal;

                    const itemEl = document.createElement('div');
                    itemEl.className = 'flex items-center space-x-3 bg-gray-50 p-3 rounded-lg';
                    itemEl.innerHTML = `
                <img src="${item.image || '/images/default-product.jpg'}" alt="${item.name}" class="w-16 h-16 object-cover rounded">
                <div class="flex-1">
                    <h4 class="font-medium text-sm">${item.name}</h4>
                    <p class="text-xs text-gray-600">Rp ${item.price.toLocaleString('id-ID')}</p>
                </div>
                <div class="flex items-center space-x-1">
                    <button class="decrement-btn w-6 h-6 bg-gray-200 rounded-full hover:bg-gray-300 flex items-center justify-center text-sm" data-id="${item.id}">-</button>
                    <span class="quantity w-8 text-center text-sm font-medium">${item.quantity}</span>
                    <button class="increment-btn w-6 h-6 bg-gray-200 rounded-full hover:bg-gray-300 flex items-center justify-center text-sm" data-id="${item.id}">+</button>
                </div>
                <button class="remove-btn text-red-500 hover:text-red-700 text-sm" data-id="${item.id}">Hapus</button>
            `;

                    cartItemsContainer.appendChild(itemEl);
                });

                cartTotalElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;
            };

            const updateCartCount = () => {
                const cart = getCart();
                const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                if (cartCountElement) {
                    cartCountElement.textContent = totalItems;
                    cartCountElement.style.display = totalItems > 0 ? 'flex' : 'none';
                }
            };

            cartItemsContainer.addEventListener('click', (e) => {
                const id = parseInt(e.target.dataset.id);
                if (!id) return;

                const cart = getCart();

                if (e.target.classList.contains('increment-btn')) {
                    const item = cart.find(i => i.id === id);
                    if (item) item.quantity += 1;
                }

                if (e.target.classList.contains('decrement-btn')) {
                    const item = cart.find(i => i.id === id);
                    if (item && item.quantity > 1) {
                        item.quantity -= 1;
                    } else {
                        const index = cart.findIndex(i => i.id === id);
                        if (index > -1) cart.splice(index, 1);
                    }
                }

                if (e.target.classList.contains('remove-btn')) {
                    const index = cart.findIndex(i => i.id === id);
                    if (index > -1) cart.splice(index, 1);
                }

                saveCart(cart);
                renderCart();
            });

            const openCart = () => {
                cartSidebar.classList.remove('translate-x-full');
                cartOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                renderCart();
            };

            const closeCart = () => {
                cartSidebar.classList.add('translate-x-full');
                cartOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            };

            cartToggle.addEventListener('click', openCart);
            cartClose.addEventListener('click', closeCart);
            cartOverlay.addEventListener('click', closeCart);

            window.addEventListener('cart-updated', () => {
                updateCartCount();
                if (!cartSidebar.classList.contains('translate-x-full')) {
                    renderCart();
                }
            });

            updateCartCount();

            window.addToCart = addToCart;

            const performCheckout = async () => {
                const cart = getCart();

                if (cart.length === 0) {
                    alert('Keranjang kosong!');
                    return;
                }

                checkoutBtn.disabled = true;
                checkoutBtn.textContent = 'Memproses...';

                try {
                    const response = await fetch('/order', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        },
                        body: JSON.stringify({
                            table: 1,
                            products: cart,
                            total: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0),
                            payment_method: 'cash'
                        })
                    });

                    const result = await response.json();
                    console.log(result)
                    if (response.ok) {
                        localStorage.removeItem(CART_KEY);
                        window.dispatchEvent(new CustomEvent('cart-updated'));

                        closeCart();

                        alert(result.message || 'Pesanan berhasil dikirim!');

                        // Optional: redirect ke halaman konfirmasi
                        // window.location.href = '/order/confirmation';

                    } else {
                        throw new Error(result.message || 'Gagal mengirim pesanan');
                    }
                } catch (error) {
                    console.error('Checkout error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                } finally {
                    checkoutBtn.disabled = cart.length === 0;
                    checkoutBtn.textContent = 'Checkout';
                }
            };

            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', performCheckout);
            }

            window.addEventListener('cart-updated', () => {
                updateCartCount();
                const cart = getCart();
                checkoutBtn.disabled = cart.length === 0;
                if (!cartSidebar.classList.contains('translate-x-full')) {
                    renderCart();
                }
            });
        });
    </script>

    @yield('scripts')
</body>

</html>
