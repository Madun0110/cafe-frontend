@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('page-title', 'Kelola Kategori') 
@section('page-description', 'Tambah dan kelola kategori produk')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Add Category Form -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-plus-circle mr-2 text-green-600"></i>Tambah Kategori Baru
            </h3>
            
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                        <input type="text" name="name" required 
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                               placeholder="Contoh: Espresso, Makanan">
                    </div>
                </div>
                
                <button type="submit" 
                        class="w-full bg-primary text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition mt-6">
                    <i class="fas fa-save mr-2"></i>Simpan Kategori
                </button>
            </form>
        </div>
    </div>

    <!-- Categories List -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-tags mr-2 text-blue-600"></i>Daftar Kategori
                    <span class="ml-2 bg-gray-100 text-gray-800 text-sm px-2 py-1 rounded-full">
                        {{ count($categories) }} kategori
                    </span>
                </h3>
            </div>
            
            @if(count($categories) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($categories as $category)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-gray-800 text-lg">{{ $category['name'] }}</h4>
                                <p class="text-sm text-gray-500 mt-1">
                                    Dibuat: {{ \Carbon\Carbon::parse($category['created_at'])->format('d M Y') }}
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 transition" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-tags text-4xl text-gray-300 mb-4"></i>
                    <h4 class="text-lg font-medium text-gray-500 mb-2">Belum ada kategori</h4>
                    <p class="text-gray-400">Tambahkan kategori pertama Anda</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection