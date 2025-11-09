@extends('layouts.admin')

@section('title', 'Monitor Kitchen')
@section('page-title', 'Monitor Kitchen')
@section('page-description', 'Pantau pesanan makanan dan minuman')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-kopi-accent">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-serif font-bold text-kopi-dark flex items-center">
                    <i class="fas fa-utensils mr-3 text-kopi-accent"></i>Pesanan Makanan
                    <span class="ml-3 bg-kopi-accent/20 text-kopi-dark text-sm px-3 py-1 rounded-full font-sans">
                        {{ count($foodOrders) }} pesanan
                    </span>
                </h3>
                {{-- Tombol Refresh Makanan --}}
                <button id="refresh-food"
                    class="bg-kopi-accent text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-kopi-primary transition">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh
                </button>
            </div>

            @if (count($foodOrders) > 0)
                <div class="space-y-4">
                    @foreach ($foodOrders as $order)
                        {{-- Card Pesanan Makanan --}}
                        <div class="border border-kopi-accent/50 rounded-lg p-4 bg-kopi-light-bg/50 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-bold text-kopi-dark text-lg font-serif">Meja
                                        #{{ $order['table'] ?? 'N/A' }}</h4>
                                    <p class="text-sm text-gray-600">Order ID: {{ $order['id'] }}</p>
                                    <p class="text-xs text-kopi-dark/70 mt-1">
                                        {{ \Carbon\Carbon::parse($order['created_at'])->format('H:i') }}
                                    </p>
                                </div>
                                {{-- Status Pesanan --}}
                                <span
                                    class="bg-kopi-accent text-kopi-dark text-xs px-3 py-1 rounded-full font-semibold shadow-inner">
                                    Menunggu
                                </span>
                            </div>

                            <div class="bg-white rounded p-3 mb-3 border border-kopi-light-bg">
                                <p class="text-sm font-semibold text-kopi-dark mb-2">Items:</p>
                                <ul class="text-sm text-gray-700 space-y-1">
                                    <li class="flex justify-between">
                                        <span>Product Name</span>
                                        <span class="font-bold text-kopi-dark">x1</span>
                                    </li>
                                    @foreach ($order['food'] as $item)
                                        <li class="flex justify-between">
                                            <span>{{ $item['product']['name'] }}</span>
                                            <span class="font-bold text-kopi-dark">x{{ $item['quantity'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="flex space-x-2">
                                {{-- Tombol Selesai --}}
                                <button
                                    class="flex-1 bg-kopi-green text-white py-2 rounded text-sm font-medium hover:bg-kopi-green/90 transition shadow-md">
                                    <i class="fas fa-check mr-1"></i>Selesai
                                </button>
                                {{-- Tombol Batal --}}
                                <button
                                    class="flex-1 bg-kopi-primary text-white py-2 rounded text-sm font-medium hover:bg-kopi-dark transition shadow-md">
                                    <i class="fas fa-times mr-1"></i>Batal
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 border-2 border-dashed border-kopi-light-bg rounded-lg">
                    <i class="fas fa-utensils text-5xl text-kopi-accent/50 mb-4"></i>
                    <h4 class="text-lg font-serif font-medium text-kopi-dark mb-2">Tidak ada pesanan makanan</h4>
                    <p class="text-gray-500">Semua pesanan telah diproses</p>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-kopi-primary">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-serif font-bold text-kopi-dark flex items-center">
                    <i class="fas fa-coffee mr-3 text-kopi-primary"></i>Pesanan Minuman
                    <span class="ml-3 bg-kopi-primary/20 text-kopi-dark text-sm px-3 py-1 rounded-full font-sans">
                        {{ count($drinkOrders) }} pesanan
                    </span>
                </h3>
                {{-- Tombol Refresh Minuman --}}
                <button id="refresh-drink"
                    class="bg-kopi-primary text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-kopi-dark transition">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh
                </button>
            </div>

            @if (count($drinkOrders) > 0)
                <div class="space-y-4">
                    @foreach ($drinkOrders as $order)
                        {{-- Card Pesanan Minuman --}}
                        <div class="border border-kopi-primary/50 rounded-lg p-4 bg-kopi-light-bg/50 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-bold text-kopi-dark text-lg font-serif">Meja
                                        #{{ $order['table'] ?? 'N/A' }}</h4>
                                    <p class="text-sm text-gray-600">Order ID: {{ $order['id'] }}</p>
                                    <p class="text-xs text-kopi-dark/70 mt-1">
                                        {{ \Carbon\Carbon::parse($order['created_at'])->format('H:i') }}
                                    </p>
                                </div>
                                {{-- Status Pesanan --}}
                                <span
                                    class="bg-kopi-primary text-white text-xs px-3 py-1 rounded-full font-semibold shadow-inner">
                                    Menunggu
                                </span>
                            </div>

                            <div class="bg-white rounded p-3 mb-3 border border-kopi-light-bg">
                                <p class="text-sm font-semibold text-kopi-dark mb-2">Items:</p>
                                <ul class="text-sm text-gray-700 space-y-1">
                                    <li class="flex justify-between">
                                        <span>Product Name</span>
                                        {{-- <span class="font-bold text-kopi-dark">{{ $order['quantity'] }}</span> --}}
                                    </li>
                                    @foreach ($order['drink'] as $item)
                                        <li class="flex justify-between">
                                            <span>{{ $item['product']['name'] }}</span>
                                            <span class="font-bold text-kopi-dark">x{{ $item['quantity'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="flex space-x-2">
                                {{-- Tombol Selesai --}}
                                <button
                                    class="flex-1 bg-kopi-green text-white py-2 rounded text-sm font-medium hover:bg-kopi-green/90 transition shadow-md">
                                    <i class="fas fa-check mr-1"></i>Selesai
                                </button>
                                {{-- Tombol Batal --}}
                                <button
                                    class="flex-1 bg-kopi-dark text-white py-2 rounded text-sm font-medium hover:bg-kopi-primary transition shadow-md">
                                    <i class="fas fa-times mr-1"></i>Batal
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 border-2 border-dashed border-kopi-light-bg rounded-lg">
                    <i class="fas fa-coffee text-5xl text-kopi-primary/50 mb-4"></i>
                    <h4 class="text-lg font-serif font-medium text-kopi-dark mb-2">Tidak ada pesanan minuman</h4>
                    <p class="text-gray-500">Semua pesanan telah diproses</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Auto refresh every 30 seconds
        setInterval(() => {
            window.location.reload();
        }, 30000);

        // Manual refresh buttons
        document.getElementById('refresh-food').addEventListener('click', () => {
            window.location.reload();
        });

        document.getElementById('refresh-drink').addEventListener('click', () => {
            window.location.reload();
        });

        // Catatan: Pastikan array $order['items'] sudah benar-benar ada di Controller Anda.
    </script>
@endsection
