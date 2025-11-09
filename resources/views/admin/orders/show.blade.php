@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order['id'])
@section('page-title', 'Detail Pesanan')
@section('page-description', 'Mengelola dan memperbarui status pesanan.')

@section('content')
<div class="container mx-auto p-0">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-kopi-dark">Detail Pesanan #{{ $order['id'] }}</h1>
        {{-- Tombol Kembali --}}
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-4 py-2 border border-kopi-primary rounded-lg shadow-sm text-sm font-medium text-kopi-dark bg-white hover:bg-kopi-cream focus:outline-none focus:ring-2 focus:ring-kopi-accent transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
    </div>

    {{-- Notifikasi (Layout sudah menangani notifikasi utama, tapi ini untuk fallback) --}}
    @if(session('warning'))
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded shadow" role="alert">
            {{ session('warning') }}
        </div>
    @endif

    {{-- Kartu Detail Pesanan --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Kolom Kiri: Informasi Dasar & Aksi --}}
        <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-xl border border-kopi-light-bg space-y-4">
            <h2 class="text-xl font-semibold border-b-2 border-kopi-primary pb-2 mb-4 text-kopi-dark">Informasi Pelanggan & Status</h2>

            {{-- Status Saat Ini --}}
            <div class="flex items-center justify-between border-b pb-2">
                <span class="text-sm font-medium text-gray-500">Status:</span>
                @php
                    $status = strtolower($order['status'] ?? 'pending');
                    // Menggunakan warna kustom KopiKu untuk Selesai
                    $color = [
                        'pending' => 'bg-yellow-500',
                        'selesai' => 'bg-kopi-green', // Solid color for emphasis
                        'dibatalkan' => 'bg-red-500',
                    ][$status] ?? 'bg-gray-500';
                @endphp
                <span class="px-3 py-1 text-sm font-bold text-white {{ $color }} rounded-full shadow-md">
                    {{ ucfirst($order['status'] ?? 'Pending') }}
                </span>
            </div>

            {{-- Detail Lain --}}
            <p class="text-sm text-kopi-dark">
                <span class="font-medium text-gray-500">Pelanggan:</span> {{ $order['customer_name'] ?? 'Anonim' }}
            </p>
            <p class="text-sm text-kopi-dark">
                <span class="font-medium text-gray-500">Nomor Meja:</span> {{ $order['table_number'] ?? '-' }}
            </p>
            <p class="text-sm text-kopi-dark">
                <span class="font-medium text-gray-500">Metode Bayar:</span> {{ $order['payment_method'] ?? 'N/A' }}
            </p>
            <p class="text-sm text-gray-600">
                <span class="font-medium text-gray-500">Waktu Pesan:</span> {{ \Carbon\Carbon::parse($order['created_at'] ?? now())->format('d M Y H:i:s') }}
            </p>

            {{-- Formulir Update Status --}}
            @if(strtolower($status) == 'pending')
            <div class="pt-4 border-t border-kopi-light-bg mt-4 space-y-3">
                <h3 class="text-lg font-semibold text-kopi-dark">Perbarui Status</h3>
                
                {{-- Tombol Tandai Selesai (Warna Hijau KopiKu) --}}
                <form action="{{ route('admin.orders.update_status', $order['id']) }}" method="POST" class="w-full">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Selesai">
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyelesaikan pesanan ini?')"
                        class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent rounded-lg shadow-md text-sm font-medium text-white bg-kopi-green hover:bg-kopi-green/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-kopi-green transition duration-150">
                        <i class="fas fa-check-circle mr-2"></i>
                        Tandai Selesai
                    </button>
                </form>

                {{-- Tombol Batalkan Pesanan (Tetap Merah untuk aksi destruktif) --}}
                <form action="{{ route('admin.orders.update_status', $order['id']) }}" method="POST" class="w-full">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Dibatalkan">
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Aksi ini tidak dapat dibatalkan.')"
                        class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent rounded-lg shadow-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150">
                        <i class="fas fa-times-circle mr-2"></i>
                        Batalkan Pesanan
                    </button>
                </form>
            </div>
            @else
            <div class="pt-4 border-t border-kopi-light-bg mt-4 text-center">
                <p class="text-sm text-kopi-primary italic">Pesanan sudah {{ ucfirst($status) }} dan tidak dapat diubah.</p>
            </div>
            @endif
        </div>
        
        {{-- Kolom Kanan: Detail Item & Total --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-xl border border-kopi-light-bg">
            <h2 class="text-xl font-semibold border-b-2 border-kopi-primary pb-2 mb-4 text-kopi-dark">Detail Item Pesanan</h2>

            @if(!empty($order['items']))
            <ul class="divide-y divide-kopi-light-bg">
                @php $subtotal = 0; @endphp
                @foreach ($order['items'] as $item)
                    <li class="py-3 flex justify-between items-center">
                        <div>
                            <p class="text-md font-medium text-kopi-dark">{{ $item['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ $item['quantity'] }} x Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <span class="text-md font-semibold text-kopi-primary">
                            Rp {{ number_format(($item['quantity'] ?? 0) * ($item['price'] ?? 0), 0, ',', '.') }}
                        </span>
                        @php $subtotal += ($item['quantity'] ?? 0) * ($item['price'] ?? 0); @endphp
                    </li>
                @endforeach
            </ul>
            
            <div class="mt-6 pt-4 border-t-2 border-kopi-primary space-y-2">
                <div class="flex justify-between text-lg font-medium text-gray-700">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                {{-- Total Akhir menggunakan warna accent yang lebih menonjol --}}
                <div class="flex justify-between text-2xl font-extrabold text-kopi-dark">
                    <span>TOTAL AKHIR:</span>
                    <span class="text-kopi-accent">Rp {{ number_format($order['total_amount'] ?? $subtotal, 0, ',', '.') }}</span>
                </div>
            </div>

            @else
            <p class="text-kopi-primary italic">Tidak ada item dalam pesanan ini.</p>
            @endif
        </div>
    </div>

</div>
@endsection
