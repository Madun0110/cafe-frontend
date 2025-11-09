@extends('layouts.admin')

@section('title', 'Riwayat Pesanan Admin')
@section('page-title', 'Riwayat Pesanan')
@section('page-description', 'Daftar semua pesanan yang masuk ke sistem.')

@section('content')
<div class="container mx-auto p-0">
    <h1 class="text-3xl font-bold mb-6 text-kopi-dark">Riwayat Pesanan</h1>
    
    {{-- Notifikasi (Dibuat lebih general karena layout sudah menangani notifikasi utama) --}}
    {{-- Kita biarkan notifikasi di sini sebagai fallback/untuk pesan spesifik halaman --}}
    @if(session('warning'))
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4 rounded shadow" role="alert">
            {{ session('warning') }}
        </div>
    @endif

    <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-kopi-light-bg">
        @if(count($orders) > 0)
        <table class="min-w-full leading-normal">
            <thead>
                <tr class="bg-kopi-light-bg text-left text-xs font-semibold text-kopi-dark uppercase tracking-wider">
                    <th class="px-5 py-3 border-b-2 border-kopi-primary">ID Pesanan</th>
                    <th class="px-5 py-3 border-b-2 border-kopi-primary">Nama Pelanggan</th>
                    <th class="px-5 py-3 border-b-2 border-kopi-primary">Meja</th>
                    <th class="px-5 py-3 border-b-2 border-kopi-primary">Total</th>
                    <th class="px-5 py-3 border-b-2 border-kopi-primary">Status</th>
                    <th class="px-5 py-3 border-b-2 border-kopi-primary">Waktu Pesan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr class="hover:bg-kopi-cream transition duration-150">
                    <td class="px-5 py-4 border-b border-gray-100 bg-white text-sm">
                        {{-- Menggunakan warna primary untuk link --}}
                        <a href="{{ route('admin.orders.show', $order['id']) }}" class="text-kopi-primary hover:text-kopi-dark font-semibold">
                            #{{ $order['id'] }}
                        </a>
                    </td>
                    <td class="px-5 py-4 border-b border-gray-100 bg-white text-sm text-gray-700">
                        {{ $order['customer_name'] ?? 'Anonim' }}
                    </td>
                    <td class="px-5 py-4 border-b border-gray-100 bg-white text-sm text-gray-700">
                        {{ $order['table_number'] ?? '-' }}
                    </td>
                    <td class="px-5 py-4 border-b border-gray-100 bg-white text-sm font-bold text-kopi-dark">
                        Rp {{ number_format($order['total_amount'] ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 border-b border-gray-100 bg-white text-sm">
                        @php
                            $status = strtolower($order['status'] ?? 'pending');
                            // Menggunakan warna kustom KopiKu untuk Selesai
                            $color = [
                                'pending' => 'bg-yellow-100 text-yellow-800', // Tetap kuning untuk perhatian
                                'selesai' => 'bg-kopi-green/20 text-kopi-green', // Menggunakan warna hijau KopiKu
                                'dibatalkan' => 'bg-red-100 text-red-800',
                            ][$status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight">
                            <span aria-hidden="true" class="absolute inset-0 {{ $color }} opacity-70 rounded-full"></span>
                            <span class="relative">{{ ucfirst($order['status'] ?? 'pending') }}</span>
                        </span>
                    </td>
                    <td class="px-5 py-4 border-b border-gray-100 bg-white text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($order['created_at'] ?? now())->format('d M y, H:i') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="p-8 text-center text-gray-500">
            <svg class="mx-auto h-16 w-16 text-kopi-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            <p class="mt-4 text-kopi-dark font-medium">Belum ada data pesanan yang tersedia.</p>
            <p class="text-sm text-gray-600">Pastikan API berjalan dan terhubung dengan benar untuk menampilkan riwayat pesanan.</p>
        </div>
        @endif
    </div>
</div>
@endsection
