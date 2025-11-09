@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('page-description', 'Ringkasan sistem pemesanan kopi')

@section('content')

{{-- Grid Utama Dashboard (Menggunakan warna kustom KopiKu) --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    
    {{-- Card Produk --}}
    <div class="bg-kopi-cream rounded-xl shadow-lg p-6 border-l-4 border-kopi-green transform hover:scale-[1.02] transition duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-kopi-green/20 rounded-lg">
                <i class="fas fa-box text-2xl text-kopi-green"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Produk</p>
                <p class="text-3xl font-serif font-bold text-kopi-dark">{{ $products_count ?? 0 }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.products') }}" class="text-kopi-primary hover:text-kopi-dark text-sm font-semibold flex items-center">
                Kelola Produk <i class="fas fa-chevron-right ml-2 text-xs"></i>
            </a>
        </div>
    </div>

    {{-- Card Pesanan Makanan --}}
    <div class="bg-kopi-cream rounded-xl shadow-lg p-6 border-l-4 border-kopi-accent transform hover:scale-[1.02] transition duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-kopi-accent/20 rounded-lg">
                <i class="fas fa-utensils text-2xl text-kopi-accent"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pesanan Makanan Baru</p>
                <p class="text-3xl font-serif font-bold text-kopi-dark">{{ $food_orders_count ?? 0 }}</p>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-kopi-accent text-sm font-semibold">
                {{ $food_orders_count > 0 ? 'Perlu diproses segera' : 'Semua pesanan selesai' }}
            </span>
        </div>
    </div>

    {{-- Card Pesanan Minuman --}}
    <div class="bg-kopi-cream rounded-xl shadow-lg p-6 border-l-4 border-kopi-primary transform hover:scale-[1.02] transition duration-300">
        <div class="flex items-center">
            <div class="p-3 bg-kopi-primary/20 rounded-lg">
                <i class="fas fa-coffee text-2xl text-kopi-primary"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pesanan Minuman Baru</p>
                <p class="text-3xl font-serif font-bold text-kopi-dark">{{ $drink_orders_count ?? 0 }}</p>
            </div>
        </div>
        <div class="mt-4">
            <span class="text-kopi-primary text-sm font-semibold">
                {{ $drink_orders_count > 0 ? 'Sedang dibuat barista' : 'Sedang tidak ada antrean' }}
            </span>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<h2 class="text-2xl font-serif font-semibold text-kopi-dark mb-4 border-b border-gray-300 pb-2">Aksi Cepat</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    
    {{-- Tambah Produk --}}
    <a href="{{ route('admin.products') }}" 
       class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition cursor-pointer border border-kopi-light-bg hover:border-kopi-primary flex items-center space-x-4">
        <div class="w-16 h-16 bg-kopi-light-bg rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-plus text-3xl text-kopi-primary"></i>
        </div>
        <div>
            <h3 class="font-serif font-bold text-kopi-dark text-xl mb-1">Tambah Produk Baru</h3>
            <p class="text-sm text-gray-600">Langsung tambahkan item menu ke katalog.</p>
        </div>
    </a>

    {{-- Monitor Kitchen --}}
    <a href="{{ route('admin.kitchen') }}" 
       class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition cursor-pointer border border-kopi-light-bg hover:border-kopi-primary flex items-center space-x-4">
        <div class="w-16 h-16 bg-kopi-light-bg rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-fire-alt text-3xl text-kopi-accent"></i>
        </div>
        <div>
            <h3 class="font-serif font-bold text-kopi-dark text-xl mb-1">Monitor Kitchen Live</h3>
            <p class="text-sm text-gray-600">Lihat status pesanan yang sedang diproses.</p>
        </div>
    </a>
</div>

{{-- Informasi Sistem --}}
<div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-kopi-dark">
    <h3 class="text-lg font-serif font-bold text-kopi-dark mb-4 flex items-center">
        <i class="fas fa-cog mr-2 text-kopi-primary"></i>Detail & Status Sistem
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h4 class="font-semibold text-kopi-dark mb-2">Status Aplikasi</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Environment:</span>
                    <span class="font-medium {{ app()->environment('production') ? 'text-red-600' : 'text-kopi-green' }}">
                        {{ strtoupper(app()->environment()) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Debug Mode:</span>
                    <span class="font-medium {{ app()->hasDebugModeEnabled() ? 'text-orange-600' : 'text-kopi-green' }}">
                        {{ app()->hasDebugModeEnabled() ? 'ON' : 'OFF' }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Waktu Server:</span>
                    <span class="font-medium text-kopi-dark">{{ now()->format('d M Y H:i:s') }}</span>
                </div>
            </div>
        </div>
        <div>
            <h4 class="font-semibold text-kopi-dark mb-2">Koneksi API</h4>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">API Base URL:</span>
                    <span class="font-medium text-blue-600 truncate">{{ env('API_BASE_URL') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Frontend URL:</span>
                    <span class="font-medium text-kopi-dark truncate">{{ env('APP_URL') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    <span id="api-status" class="font-medium text-sm">Checking...</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Skrip untuk mengecek status API --}}
<script>
    // Check API status
    fetch('/api-status')
        .then(response => response.json())
        .then(data => {
            const statusElement = document.getElementById('api-status');
            if (data.products) { 
                statusElement.textContent = 'Connected ✓';
                statusElement.className = 'font-semibold text-kopi-green'; 
            } else {
                statusElement.textContent = 'Disconnected ✗';
                statusElement.className = 'font-semibold text-red-600';
            }
        })
        .catch(error => {
            document.getElementById('api-status').textContent = 'Error';
            document.getElementById('api-status').className = 'font-semibold text-red-600';
        });
</script>
@endsection