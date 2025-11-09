@extends('layouts.admin')

@section('title', 'Kelola Produk')
@section('page-title', 'Kelola Produk')
@section('page-description', 'Tambah dan kelola menu produk')

@section('content')
    @if (session('success'))
        {{-- Diubah ke warna hijau KopiKu --}}
        <div class="bg-kopi-green/10 border border-kopi-green text-kopi-dark px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        {{-- Diubah ke warna merah yang lebih lembut --}}
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-kopi-primary">
                <h3 class="text-xl font-serif font-bold text-kopi-dark mb-4 flex items-center">
                    <i class="fas fa-plus-circle mr-3 text-kopi-primary"></i>Tambah Produk Baru
                </h3>

                {{-- Form ini akan dikirim ke Backend menggunakan endpoint POST/store --}}
                <form action="{{ route('admin.products.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-kopi-dark mb-2">Nama Produk</label>
                            <input type="text" name="name" required
                                class="w-full border border-kopi-light-bg rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-kopi-accent focus:border-kopi-accent"
                                placeholder="Contoh: Espresso, Nasi Goreng" value="{{ old('name') }}">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-kopi-dark mb-2">Kategori</label>
                            <select name="category_id" required
                                class="w-full border border-kopi-light-bg rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-kopi-accent focus:border-kopi-accent">
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category['id'] }}" data-name="{{ $category['name'] }}"
                                        {{ old('category_id') == $category['id'] ? 'selected' : '' }}>
                                        {{ $category['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-kopi-dark mb-2">Harga (Rp)</label>
                            <input type="number" name="price" step="0.01" min="0" required
                                class="w-full border border-kopi-light-bg rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-kopi-accent focus:border-kopi-accent"
                                placeholder="25000" value="{{ old('price') }}">
                            @error('price')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-kopi-dark mb-2">Status</label>
                            <select name="is_available"
                                class="w-full border border-kopi-light-bg rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-kopi-accent focus:border-kopi-accent">
                                <option value="1" {{ old('is_available', 1) == 1 ? 'selected' : '' }}>Tersedia
                                </option>
                                <option value="0" {{ old('is_available') == 0 ? 'selected' : '' }}>Habis</option>
                            </select>
                            @error('is_available')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-kopi-dark mb-2">Deskripsi</label>
                            <textarea name="description" required rows="3"
                                class="w-full border border-kopi-light-bg rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-kopi-accent focus:border-kopi-accent"
                                placeholder="Deskripsi produk...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-kopi-green text-white py-3 rounded-lg font-semibold hover:bg-kopi-green/90 transition mt-6 shadow-md">
                        <i class="fas fa-save mr-2"></i>Simpan Produk
                    </button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-6 border-t-4 border-kopi-accent">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-serif font-bold text-kopi-dark flex items-center">
                        <i class="fas fa-list mr-3 text-kopi-accent"></i>Daftar Produk
                        <span class="ml-3 bg-kopi-light-bg/50 text-kopi-dark text-sm px-2 py-1 rounded-full font-sans">
                            {{ count($products) }} produk
                        </span>
                    </h3>

                    <a href="{{ route('admin.products') }}"
                        class="bg-kopi-light-bg/50 text-kopi-dark px-4 py-2 rounded-lg hover:bg-kopi-light-bg transition shadow-sm">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </a>
                </div>

                @if (count($products) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-full">
                            <thead>
                                {{-- Header Table menggunakan warna abu-abu yang lebih lembut --}}
                                <tr class="bg-kopi-light-bg/50 border-b border-kopi-light-bg">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-kopi-dark/70 uppercase w-2/5">
                                        Produk</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-kopi-dark/70 uppercase w-1/5">
                                        Kategori</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-kopi-dark/70 uppercase w-1/5">
                                        Harga</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-kopi-dark/70 uppercase w-1/5">
                                        Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-kopi-dark/70 uppercase w-1/10">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="productsTableBody" class="divide-y divide-kopi-light-bg">
                                @foreach ($products as $product)
                                    <tr class="hover:bg-kopi-light-bg/30 transition"
                                        data-product-id="{{ $product['id'] }}">
                                        <td class="px-4 py-4">
                                            <div class="font-bold text-kopi-dark">{{ $product['name'] }}</div>
                                            <div class="text-sm text-gray-600 mt-1">{{ $product['description'] }}</div>
                                        </td>
                                        <td class="px-4 py-4">
                                            @php
                                                $categoryName = 'N/A';
                                                $categoryColor = 'bg-kopi-light-bg text-kopi-dark';
                                                foreach ($categories as $cat) {
                                                    if ($cat['id'] == $product['category_id']) {
                                                        $categoryName = $cat['name'];
                                                        // Simulasi penentuan warna berdasarkan ID/Nama Kategori (Jika Kategori sudah ditentukan warnanya di Backend, ini bisa diganti dengan class warna yang sesuai)
                                                        if ($cat['id'] % 2 == 0) {
                                                            $categoryColor = 'bg-kopi-primary/20 text-kopi-primary';
                                                        }
                                                        // Contoh warna: Primary
                                                        else {
                                                            $categoryColor = 'bg-kopi-accent/20 text-kopi-accent';
                                                        } // Contoh warna: Accent
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $categoryColor }}">
                                                {{ $categoryName }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="font-bold text-kopi-primary price-display">
                                                Rp {{ number_format($product['price'], 0, ',', '.') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            @php
                                                $isAvailable = $product['is_available'] ?? 1; // Default Tersedia
                                                $statusClass = $isAvailable
                                                    ? 'bg-kopi-green/20 text-kopi-green'
                                                    : 'bg-red-100 text-red-700';
                                                $dotClass = $isAvailable ? 'bg-kopi-green' : 'bg-red-500';
                                                $statusText = $isAvailable ? 'Tersedia' : 'Habis';
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                                <span class="w-2 h-2 rounded-full mr-2 {{ $dotClass }}"></span>
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex space-x-2">
                                                <button
                                                    class="text-blue-600 hover:text-kopi-dark transition btn-edit p-1 rounded hover:bg-kopi-light-bg"
                                                    title="Edit" data-id="{{ $product['id'] }}"
                                                    data-name="{{ $product['name'] }}"
                                                    data-desc="{{ $product['description'] }}"
                                                    data-price="{{ $product['price'] }}"
                                                    data-category="{{ $product['category_id'] }}"
                                                    data-available="{{ $isAvailable }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <button
                                                    class="text-red-600 hover:text-kopi-dark transition btn-delete p-1 rounded hover:bg-kopi-light-bg"
                                                    title="Hapus" data-id="{{ $product['id'] }}"
                                                    data-name="{{ $product['name'] }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12 border-2 border-dashed border-kopi-light-bg/50 rounded-lg">
                        <i class="fas fa-box-open text-5xl text-kopi-accent/50 mb-4"></i>
                        <h4 class="text-xl font-serif font-medium text-kopi-dark mb-2">Belum ada produk</h4>
                        <p class="text-gray-500">Tambahkan produk pertama Anda di panel kiri.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="editModal"
        class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto hidden z-50 justify-center items-start pt-10">
        <div class="relative mx-auto p-5 border w-full max-w-md shadow-2xl rounded-xl bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-2xl font-serif font-bold text-kopi-dark mb-4 flex items-center justify-center">
                    Edit Produk <span id="editProductName" class="ml-2 text-kopi-primary"></span>
                </h3>
                <div class="mt-2 px-7 py-3">

                    {{-- Form Edit --}}
                    <form id="editForm" method="POST" action="">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4 text-left">

                            <div>
                                <label class="block text-sm font-medium text-kopi-dark mb-2" for="edit_name">Nama
                                    Produk</label>
                                <input type="text" id="edit_name" name="name" required
                                    class="w-full border border-kopi-light-bg rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-kopi-accent focus:border-kopi-accent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-kopi-dark mb-2"
                                    for="edit_category_id">Kategori</label>
                                <select id="edit_category_id" name="category_id" required
                                    class="w-full border border-kopi-light-bg rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-kopi-accent focus:border-kopi-accent">
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category['id'] }}" data-name="{{ $category['name'] }}">
                                            {{ $category['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-kopi-dark mb-2" for="edit_price">Harga
                                    (Rp)</label>
                                <input type="number" id="edit_price" name="price" step="0.01" min="0"
                                    required
                                    class="w-full border border-kopi-light-bg rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-kopi-accent focus:border-kopi-accent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-kopi-dark mb-2"
                                    for="edit_is_available">Status</label>
                                <select id="edit_is_available" name="is_available"
                                    class="w-full border border-kopi-light-bg rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-kopi-accent focus:border-kopi-accent">
                                    <option value="1">Tersedia</option>
                                    <option value="0">Habis</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-kopi-dark mb-2"
                                    for="edit_description">Deskripsi</label>
                                <textarea id="edit_description" name="description" required rows="3"
                                    class="w-full border border-kopi-light-bg rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-kopi-accent focus:border-kopi-accent"></textarea>
                            </div>
                        </div>

                        <div class="items-center px-4 py-3 mt-6 flex justify-between">
                            <button type="button" id="closeEditModal"
                                class="px-4 py-2 bg-kopi-primary/20 text-kopi-primary text-base font-medium rounded-lg w-full mr-2 shadow-sm hover:bg-kopi-primary/40 transition">
                                Batal
                            </button>
                            <button type="submit" id="saveEditBtn"
                                class="px-4 py-2 bg-kopi-green text-white text-base font-medium rounded-lg w-full ml-2 shadow-md hover:bg-kopi-green/90 transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div id="deleteModal"
        class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto hidden z-50 justify-center items-start pt-10">
        <div class="relative mx-auto p-6 border w-full max-w-sm shadow-2xl rounded-xl bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-xl font-bold text-kopi-dark mb-4">Konfirmasi Hapus</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-600">
                        Apakah Anda yakin ingin menghapus produk
                        <span id="deleteProductName" class="font-bold text-red-600"></span>?
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="items-center px-4 py-3 mt-4 flex justify-between">
                    <button type="button" id="closeDeleteModal"
                        class="px-4 py-2 bg-kopi-primary/20 text-kopi-primary text-base font-medium rounded-lg w-full mr-2 shadow-sm hover:bg-kopi-primary/40 transition">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-lg w-full ml-2 shadow-md hover:bg-red-700 transition">
                            Hapus Permanen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productTableBody = document.getElementById('productsTableBody');
            const editModal = document.getElementById('editModal');
            const editForm = document.getElementById('editForm');
            const deleteModal = document.getElementById('deleteModal');
            const deleteForm = document.getElementById('deleteForm');
            const closeEditModalBtn = document.getElementById('closeEditModal');
            const closeDeleteModalBtn = document.getElementById('closeDeleteModal');

            // --- Event Delegation untuk tombol Edit & Delete ---
            if (productTableBody) {
                productTableBody.addEventListener('click', function(e) {
                    const editButton = e.target.closest('.btn-edit');
                    const deleteButton = e.target.closest('.btn-delete');

                    // 1. Logika untuk membuka Modal Edit
                    if (editButton) {
                        const id = editButton.getAttribute('data-id');
                        const name = editButton.getAttribute('data-name');
                        const desc = editButton.getAttribute('data-desc');
                        const price = editButton.getAttribute('data-price');
                        const category = editButton.getAttribute('data-category');
                        const available = editButton.getAttribute('data-available');

                        // Mengisi nilai form di modal
                        document.getElementById('editProductName').textContent = name;
                        document.getElementById('edit_name').value = name;
                        document.getElementById('edit_description').value = desc;
                        document.getElementById('edit_price').value = price;
                        document.getElementById('edit_category_id').value = category;
                        document.getElementById('edit_is_available').value = available;

                        // Mengatur Action URL form (PENTING untuk backend)
                        // ASUMSI: Route update Anda bernama 'admin.products.update' dan menerima ID di URL
                        const updateUrl = "{{ route('admin.products.update', 'REPLACE_ID') }}";
                        editForm.setAttribute('action', updateUrl.replace('REPLACE_ID', id));

                        // Menampilkan modal
                        editModal.classList.remove('hidden');
                        editModal.classList.add('flex');
                    }

                    // 2. Logika untuk membuka Modal Hapus
                    else if (deleteButton) {
                        const id = deleteButton.getAttribute('data-id');
                        const name = deleteButton.getAttribute('data-name');

                        // Mengisi nama produk yang akan dihapus
                        document.getElementById('deleteProductName').textContent = name;

                        // Mengatur Action URL form (PENTING untuk backend)
                        // ASUMSI: Route delete Anda bernama 'admin.products.delete' dan menerima ID di URL
                        const deleteUrl = "{{ route('admin.products.delete', 'REPLACE_ID') }}";
                        deleteForm.setAttribute('action', deleteUrl.replace('REPLACE_ID', id));

                        // Menampilkan modal
                        deleteModal.classList.remove('hidden');
                        deleteModal.classList.add('flex');
                    }
                });
            }

            // --- HANDLER TOMBOL BATAL & Klik di luar modal ---

            // Tutup Modal Edit (Tombol Batal)
            if (closeEditModalBtn) {
                closeEditModalBtn.addEventListener('click', () => {
                    editModal.classList.add('hidden');
                    editModal.classList.remove('flex');
                });
            }

            // Tutup Modal Hapus (Tombol Batal)
            if (closeDeleteModalBtn) {
                closeDeleteModalBtn.addEventListener('click', () => {
                    deleteModal.classList.add('hidden');
                    deleteModal.classList.remove('flex');
                });
            }

            // Tutup Modal Edit (Klik di luar modal)
            if (editModal) {
                editModal.addEventListener('click', (e) => {
                    if (e.target.id === 'editModal') {
                        editModal.classList.add('hidden');
                        editModal.classList.remove('flex');
                    }
                });
            }

            // Tutup Modal Hapus (Klik di luar modal)
            if (deleteModal) {
                deleteModal.addEventListener('click', (e) => {
                    if (e.target.id === 'deleteModal') {
                        deleteModal.classList.add('hidden');
                        deleteModal.classList.remove('flex');
                    }
                });
            }

            // Tambahkan tombol refresh manual ke script auto-refresh jika diperlukan
            document.getElementById('refresh-food')?.addEventListener('click', () => window.location.reload());
            document.getElementById('refresh-drink')?.addEventListener('click', () => window.location.reload());

        });
    </script>
@endsection
