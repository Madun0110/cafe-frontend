@extends('layouts.app')

@section('title', 'Konfirmasi Pesanan - Checkout')

@section('content')
<div class="max-w-xl mx-auto px-6 py-12 bg-white rounded-xl shadow-2xl mt-10 border-t-8 border-[#A0522D]">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-serif font-bold text-[#6B3E1A] mb-2"><i class="fas fa-receipt mr-2"></i> Konfirmasi Pesanan</h1>
        <p class="text-[#7A5A38]">Mohon masukkan nomor meja dan periksa kembali detail pesanan Anda.</p>
    </div>

    {{-- Tampilkan Error atau Success Message --}}
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
            <p class="font-bold">Gagal!</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    {{-- Form Konfirmasi Pesanan --}}
    <form action="{{ route('order.store') }}" method="POST">
        @csrf

        {{-- Nomor Meja --}}
        <div class="mb-6">
            <label for="table" class="block text-lg font-medium text-[#6B3E1A] mb-2">Nomor Meja Anda:</label>
            <input type="number" id="table" name="table" required min="1" max="50"
                   class="w-full px-4 py-3 border border-[#D7B56A] rounded-lg focus:ring-[#A0522D] focus:border-[#A0522D] text-[#6B3E1A] font-semibold text-xl"
                   placeholder="e.g., 5" value="{{ old('table') }}">
            @error('table')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Detail Item Pesanan --}}
        <h2 class="text-2xl font-semibold text-[#A0522D] mb-4 border-b pb-2">Detail Keranjang</h2>
        <div class="space-y-4 mb-6 max-h-80 overflow-y-auto pr-2">
            @forelse($cartItems as $item)
                <div class="flex justify-between items-center p-3 bg-[#FBF6F0] rounded-lg shadow-sm border border-[#D7B56A]">
                    <div class="flex-1">
                        <p class="font-bold text-[#6B3E1A]">{{ $item['name'] }}</p>
                        <p class="text-sm text-[#7A5A38]">
                            {{ $item['quantity'] }} x Rp {{ number_format($item['price'], 0, ',', '.') }}
                        </p>
                        @if(!empty($item['note']))
                            <p class="text-xs italic text-[#967255]">Catatan: {{ $item['note'] }}</p>
                        @endif
                    </div>
                    <p class="font-bold text-[#A0522D]">
                        Rp {{ number_format($item['quantity'] * $item['price'], 0, ',', '.') }}
                    </p>

                    {{-- Input tersembunyi untuk dikirim ke Controller (penting!) --}}
                    <input type="hidden" name="items[{{ $item['id'] }}][id]" value="{{ $item['id'] }}">
                    <input type="hidden" name="items[{{ $item['id'] }}][quantity]" value="{{ $item['quantity'] }}">
                    <input type="hidden" name="items[{{ $item['id'] }}][note]" value="{{ $item['note'] ?? '' }}">
                </div>
            @empty
                {{-- Ini seharusnya tidak tercapai jika OrderController@showOrderForm bekerja dengan benar,
                     tapi ini adalah pengamanan. --}}
                <div class="text-center py-5 text-[#7A5A38]">Keranjang Anda kosong. Silakan kembali ke menu.</div>
            @endforelse
        </div>

        {{-- Total Pembayaran --}}
        <div class="flex justify-between items-center border-t-2 border-[#6B3E1A] pt-4 mt-4">
            <span class="text-2xl font-bold text-[#6B3E1A]">TOTAL PEMBAYARAN:</span>
            <span class="text-3xl font-extrabold text-[#A0522D]">
                Rp {{ number_format($total, 0, ',', '.') }}
            </span>
        </div>

        {{-- Tombol Konfirmasi --}}
        <button type="submit"
                class="w-full mt-8 bg-[#A0522D] text-white px-6 py-4 rounded-xl font-bold text-xl hover:bg-[#6B3E1A] transition transform hover:scale-[1.01] shadow-lg disabled:bg-gray-400">
            <i class="fas fa-paper-plane mr-2"></i> Konfirmasi & Kirim Pesanan
        </button>
    </form>
    
    <div class="text-center mt-6">
        <a href="{{ route('menu') }}" class="text-[#7A5A38] hover:text-[#A0522D] transition text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Menu
        </a>
    </div>

</div>
@endsection

<style>
    .bg-cream { background-color: #FBF6F0; }
    .text-cream { color: #FBF6F0; }
</style>
