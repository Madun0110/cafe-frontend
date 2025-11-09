@extends('layouts.app')

@section('title', 'Menu - KopiKu Café')

@section('content')
    <div class="max-w-7xl mx-auto px-6 py-10 bg-[#FBF6F0] rounded-lg shadow-md">
        <div class="text-center mb-12 fade-in">
            <h1 class="text-5xl font-serif font-extrabold text-[#6B3E1A] mb-4 flex justify-center items-center gap-3">
                <i class="fas fa-coffee text-4xl text-[#A0522D]"></i>
                Menu Kopi Kami
            </h1>
            <p class="text-xl text-[#7A5A38] max-w-3xl mx-auto italic font-light">
                Nikmati berbagai pilihan kopi spesial dan makanan lezat dengan kualitas terbaik
            </p>
        </div>

        <!-- Tombol Filter Kategori -->
        <div class="flex flex-wrap gap-3 mb-10 justify-center fade-in" id="category-buttons">
            <button
                class="category-btn px-6 py-3 bg-[#A0522D] text-cream rounded-full font-semibold shadow-lg hover:shadow-xl transition transform hover:scale-105 active"
                data-category="all">
                <i class="fas fa-star mr-2"></i> Semua Menu
            </button>

            @foreach ($categories as $category)
                @php $catNameLower = strtolower($category['name']); @endphp
                <button
                    class="category-btn px-6 py-3 bg-cream text-[#6B3E1A] rounded-full font-medium shadow-md hover:shadow-xl hover:bg-[#A0522D] hover:text-cream transition transform hover:scale-105 flex items-center gap-2"
                    data-category="{{ $category['id'] }}">
                    @if (str_contains($catNameLower, 'kopi') || str_contains($catNameLower, 'minuman'))
                        <i class="fas fa-coffee text-[#A0522D]"></i>
                    @elseif(str_contains($catNameLower, 'makanan'))
                        <i class="fas fa-utensils text-[#C68642]"></i>
                    @else
                        <i class="fas fa-tag text-[#967255]"></i>
                    @endif
                    {{ $category['name'] }}
                </button>
            @endforeach
        </div>

        <!-- Grid Produk -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12" id="products-grid">
            @forelse($products as $product)
                <div class="product-card bg-cream rounded-3xl shadow-md hover:shadow-2xl transition-transform duration-300 overflow-hidden fade-in transform hover:-translate-y-2 border border-[#C4A46A]"
                    data-category="{{ $product['category_id'] }}"
                    data-available="{{ $product['is_available'] ? 'true' : 'false' }}">

                    <div
                        class="h-48 bg-gradient-to-br from-[#F0DFB0] to-[#D7B56A] flex items-center justify-center rounded-t-3xl">
                        @php $nameLower = strtolower($product['name']); @endphp

                        @if (str_contains($nameLower, 'kopi') || str_contains($nameLower, 'coffee') || str_contains($nameLower, 'espresso'))
                            <i class="fas fa-coffee text-6xl text-[#6B3E1A] opacity-90"></i>
                        @elseif(str_contains($nameLower, 'teh') || str_contains($nameLower, 'tea') || str_contains($nameLower, 'jus'))
                            <i class="fas fa-glass-whiskey text-6xl text-[#519872] opacity-90"></i>
                        @elseif(str_contains($nameLower, 'nasi') || str_contains($nameLower, 'mie') || str_contains($nameLower, 'makanan'))
                            <i class="fas fa-utensils text-6xl text-[#C68642] opacity-90"></i>
                        @else
                            <i class="fas fa-concierge-bell text-6xl text-[#967255] opacity-80"></i>
                        @endif
                    </div>

                    <div class="p-6">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-xl font-semibold text-[#6B3E1A] flex-1 truncate">{{ $product['name'] }}</h3>
                            <span
                                class="ml-2 px-3 py-1 rounded-full text-xs font-semibold {{ $product['is_available'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product['is_available'] ? 'Tersedia' : 'Habis' }}
                            </span>
                        </div>

                        <p class="text-[#7A5A38] text-sm mb-4 leading-relaxed line-clamp-2">{{ $product['description'] }}
                        </p>

                        <div class="flex items-center justify-between">
                            <p class="text-2xl font-bold text-[#A0522D]">
                                Rp {{ number_format($product['price'], 0, ',', '.') }}
                            </p>
                            <button
                                class="add-to-cart-btn bg-[#A0522D] text-cream px-6 py-3 rounded-full font-semibold hover:bg-[#6B3E1A] disabled:bg-gray-400 disabled:cursor-not-allowed transition transform hover:scale-105 shadow-md hover:shadow-lg"
                                data-product-id="{{ $product['id'] }}" data-product='@json($product)'
                                {{ !$product['is_available'] ? 'disabled' : '' }}>
                                <i class="fas fa-plus mr-2"></i>Tambah
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Pesan jika tidak ada produk -->
                <div class="col-span-full text-center py-16 fade-in bg-cream rounded-xl shadow-md text-[#967255]"
                    id="no-products-message">
                    <i class="fas fa-coffee text-6xl mb-4"></i>
                    <h3 class="text-2xl font-bold mb-2">Menu Belum Tersedia</h3>
                    <p>Silakan hubungi administrator untuk menambahkan menu</p>
                </div>
            @endforelse
        </div>

        <!-- Section Keunggulan -->
        <div class="bg-white rounded-3xl shadow-xl p-8 mb-8 fade-in max-w-5xl mx-auto border-t-4 border-[#A0522D]">
            <h2 class="text-3xl font-serif font-bold text-center text-[#6B3E1A] mb-8">
                <i class="fas fa-leaf text-[#519872] mr-3"></i> Mengapa Memilih KopiKu?
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">

                <div class="p-4 transform hover:scale-105 transition duration-300">
                    <i class="fas fa-award text-5xl text-[#D7B56A] mb-3"></i>
                    <h3 class="text-xl font-semibold text-[#A0522D] mb-2">Biji Kopi Premium</h3>
                    <p class="text-[#7A5A38] text-sm">Dipilih langsung dari petani terbaik untuk rasa otentik di setiap
                        tegukan.</p>
                </div>

                <div class="p-4 transform hover:scale-105 transition duration-300">
                    <i class="fas fa-truck-moving text-5xl text-[#C68642] mb-3"></i>
                    <h3 class="text-xl font-semibold text-[#A0522D] mb-2">Pesan Cepat, Ambil Instan</h3>
                    <p class="text-[#7A5A38] text-sm">Proses pemesanan dan pengiriman yang efisien tanpa mengurangi
                        kualitas.</p>
                </div>

                <div class="p-4 transform hover:scale-105 transition duration-300">
                    <i class="fas fa-hand-holding-heart text-5xl text-[#A0522D] mb-3"></i>
                    <h3 class="text-xl font-semibold text-[#A0522D] mb-2">Dibuat Dengan Cinta</h3>
                    <p class="text-[#7A5A38] text-sm">Semua makanan dan kue dibuat harian dengan bahan segar, resep rahasia
                        kami.</p>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="#"
                    class="inline-block bg-[#6B3E1A] text-cream px-8 py-3 rounded-full font-bold text-lg hover:bg-[#A0522D] transition shadow-lg">
                    <i class="fas fa-mug-hot mr-2"></i> Coba Kopi Terbaik Kami!
                </a>
            </div>
        </div>
    </div>

    <style>
        .bg-primary {
            background-color: #A0522D;
        }

        .text-primary {
            color: #A0522D;
        }

        .text-cream {
            color: #FBF6F0;
        }

        .bg-cream {
            background-color: #FBF6F0;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-clamp: 2;
        }
    </style>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productCards = document.querySelectorAll('.product-card');
            const categoryBtns = document.querySelectorAll('.category-btn');
            const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
            const noProductsMessage = document.getElementById('no-products-message');

            // === FUNGSI KERANJANG MENGGUNAKAN LOCALSTORAGE ===
            const CART_KEY = 'shopping_cart';

            // Ambil keranjang dari localStorage
            const getCart = () => {
                const cart = localStorage.getItem(CART_KEY);
                return cart ? JSON.parse(cart) : [];
            };

            // Simpan keranjang ke localStorage
            const saveCart = (cart) => {
                localStorage.setItem(CART_KEY, JSON.stringify(cart));
            };

            // Tambahkan produk ke keranjang
            const addToCart = (product) => {
                const cart = getCart();
                const existingProduct = cart.find(item => item.id === product.id);

                if (existingProduct) {
                    existingProduct.quantity += 1;
                } else {
                    cart.push({
                        ...product,
                        quantity: 1
                    });
                }

                saveCart(cart);
                alert(`${product.name} ditambahkan ke keranjang!`);
                // Optional: dispatch event agar UI lain (misal badge keranjang) bisa update
                window.dispatchEvent(new CustomEvent('cart-updated'));
            };

            // === 1. Tambahkan ke keranjang ===
            addToCartBtns.forEach(button => {
                button.addEventListener('click', () => {
                    const productData = JSON.parse(button.dataset.product);
                    productData.price = parseFloat(productData.price);
                    addToCart(productData);
                });
            });

            // === 2. Filter produk berdasarkan kategori ===
            const filterProducts = (selectedCategory) => {
                let visibleCount = 0;

                productCards.forEach(card => {
                    const productCategory = card.dataset.category;
                    const show = selectedCategory === 'all' || productCategory === selectedCategory;

                    card.style.display = show ? 'block' : 'none';
                    if (show) visibleCount++;
                });

                if (noProductsMessage) {
                    const productsGrid = document.getElementById('products-grid');
                    const hasActualProducts = productsGrid.children.length > 0 &&
                        Array.from(productsGrid.children).some(child => child !== noProductsMessage);

                    if (visibleCount === 0 && hasActualProducts) {
                        noProductsMessage.style.display = 'block';
                        noProductsMessage.querySelector('h3').textContent = 'Menu Tidak Ditemukan';
                        noProductsMessage.querySelector('p').textContent =
                            'Tidak ada produk yang tersedia di kategori ini.';
                    } else if (!hasActualProducts && visibleCount === 0) {
                        noProductsMessage.style.display = 'block';
                        noProductsMessage.querySelector('h3').textContent = 'Menu Belum Tersedia';
                        noProductsMessage.querySelector('p').textContent =
                            'Silakan hubungi administrator untuk menambahkan menu';
                    } else {
                        noProductsMessage.style.display = 'none';
                    }
                }
            };

            // === Event listener tombol kategori ===
            categoryBtns.forEach(button => {
                button.addEventListener('click', () => {
                    const selectedCategory = button.dataset.category;

                    // Reset semua tombol
                    categoryBtns.forEach(btn => {
                        btn.classList.remove('active', 'bg-[#A0522D]', 'text-cream',
                            'shadow-lg');
                        btn.classList.add('bg-cream', 'text-[#6B3E1A]', 'shadow-md');
                    });

                    // Aktifkan tombol yang diklik
                    button.classList.remove('bg-cream', 'text-[#6B3E1A]', 'shadow-md');
                    button.classList.add('active', 'bg-[#A0522D]', 'text-cream', 'shadow-lg');

                    filterProducts(selectedCategory);
                });
            });

            // === Set default kategori "all" ===
            const initialCategoryBtn = document.querySelector('.category-btn[data-category="all"]');
            if (initialCategoryBtn) {
                initialCategoryBtn.classList.add('active', 'bg-[#A0522D]', 'text-cream', 'shadow-lg');
                initialCategoryBtn.classList.remove('bg-cream', 'text-[#6B3E1A]', 'shadow-md');
                filterProducts('all');
            }

            // === Optional: Update badge keranjang jika ada elemen .cart-count ===
            const updateCartCount = () => {
                const cartCountElement = document.querySelector('.cart-count');
                if (cartCountElement) {
                    const cart = getCart();
                    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                    cartCountElement.textContent = totalItems;
                    cartCountElement.style.display = totalItems > 0 ? 'flex' : 'none';
                }
            };

            // Jalankan saat load dan saat cart berubah
            updateCartCount();
            window.addEventListener('cart-updated', updateCartCount);
        });
    </script>
@endsection
