<!-- resources/views/menu/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-8">Menu Kopi Kami</h1>
    
    <!-- Categories Filter -->
    <div class="flex flex-wrap gap-2 mb-6 justify-center">
        <button class="category-btn px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 active" data-category="all">
            Semua
        </button>
        @foreach($categories as $category)
            <button class="category-btn px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300" 
                    data-category="{{ $category['id'] }}">
                {{ $category['name'] }}
            </button>
        @endforeach
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($products as $product)
            <div class="product-card bg-white rounded-lg shadow-md p-6" 
                 data-category="{{ $product['category_id'] }}">
                <h3 class="text-xl font-semibold mb-2">{{ $product['name'] }}</h3>
                <p class="text-gray-600 mb-3">{{ $product['description'] }}</p>
                <p class="text-2xl font-bold text-green-600 mb-4">
                    Rp {{ number_format($product['price'], 0, ',', '.') }}
                </p>
                <div class="flex items-center justify-between">
                    <span class="text-sm {{ $product['is_available'] ? 'text-green-600' : 'text-red-600' }}">
                        {{ $product['is_available'] ? 'Tersedia' : 'Habis' }}
                    </span>
                    <button class="add-to-cart bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 disabled:bg-gray-400"
                            data-product='@json($product)'
                            {{ !$product['is_available'] ? 'disabled' : '' }}>
                        Tambah
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Shopping Cart -->
    <div class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg p-6 w-80">
        <h3 class="text-lg font-semibold mb-4">Keranjang</h3>
        <div id="cart-items" class="space-y-2 max-h-60 overflow-y-auto mb-4">
            <!-- Cart items will appear here -->
        </div>
        <form id="order-form" action="{{ route('order.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="table" class="block text-sm font-medium text-gray-700">Nomor Meja</label>
                <input type="number" name="table" id="table" 
                       class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2" 
                       min="1" required>
                <input type="hidden" name="items" id="items-input">
            </div>
            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                Pesan Sekarang
            </button>
        </form>
    </div>
</div>

<script>
// JavaScript untuk cart functionality
class ShoppingCart {
    constructor() {
        this.items = [];
        this.updateCartDisplay();
    }

    addItem(product) {
        const existingItem = this.items.find(item => item.product_id === product.id);
        
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            this.items.push({
                product_id: product.id,
                name: product.name,
                price: product.price,
                quantity: 1,
                type: this.determineProductType(product)
            });
        }
        
        this.updateCartDisplay();
    }

    determineProductType(product) {
        // Logic untuk menentukan food/drink berdasarkan nama atau kategori
        const drinkKeywords = ['kopi', 'coffee', 'tea', 'espresso', 'latte', 'cappuccino'];
        const productName = product.name.toLowerCase();
        
        return drinkKeywords.some(keyword => productName.includes(keyword)) ? 'drink' : 'food';
    }

    updateCartDisplay() {
        const cartItems = document.getElementById('cart-items');
        const itemsInput = document.getElementById('items-input');
        
        cartItems.innerHTML = '';
        
        if (this.items.length === 0) {
            cartItems.innerHTML = '<p class="text-gray-500">Keranjang kosong</p>';
            itemsInput.value = '';
            return;
        }

        this.items.forEach((item, index) => {
            const itemElement = document.createElement('div');
            itemElement.className = 'flex justify-between items-center border-b pb-2';
            itemElement.innerHTML = `
                <div>
                    <p class="font-medium">${item.name}</p>
                    <p class="text-sm text-gray-600">Rp ${item.price.toLocaleString()} x ${item.quantity}</p>
                </div>
                <button type="button" onclick="cart.removeItem(${index})" class="text-red-600 hover:text-red-800">
                    Hapus
                </button>
            `;
            cartItems.appendChild(itemElement);
        });

        itemsInput.value = JSON.stringify(this.items);
    }

    removeItem(index) {
        this.items.splice(index, 1);
        this.updateCartDisplay();
    }
}

const cart = new ShoppingCart();

// Event Listeners
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const product = JSON.parse(this.dataset.product);
        cart.addItem(product);
    });
});

// Category Filter
document.querySelectorAll('.category-btn').forEach(button => {
    button.addEventListener('click', function() {
        const category = this.dataset.category;
        
        // Update active state
        document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active', 'bg-blue-600', 'text-white'));
        this.classList.add('active', 'bg-blue-600', 'text-white');
        
        // Filter products
        document.querySelectorAll('.product-card').forEach(card => {
            if (category === 'all' || card.dataset.category === category) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>

<style>
.category-btn.active {
    background-color: #2563eb;
    color: white;
}

.product-card {
    transition: transform 0.2s;
}

.product-card:hover {
    transform: translateY(-2px);
}
</style>
@endsection