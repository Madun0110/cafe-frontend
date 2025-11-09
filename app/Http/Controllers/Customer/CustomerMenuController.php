<?php

namespace App\Http\Controllers\Customer;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller; // Wajib di-import
use App\Models\Cart; // Wajib di-import untuk fungsionalitas keranjang
use Illuminate\Support\Facades\Log; // Wajib di-import untuk logging error

class CustomerMenuController extends Controller
{
    protected $apiService;
    protected $cart; // Properti untuk menampung instance Cart

    public function __construct(ApiService $apiService, Cart $cart) // Inject Cart di sini
    {
        $this->apiService = $apiService;
        $this->cart = $cart;
    }

    /**
     * Menampilkan Tampilan Menu Pelanggan (Akses via QR Code).
     * View: resources/views/customer/menu.blade.php
     */
    public function index()
    {
        try {
            $productsResponse = $this->apiService->getProducts();
            $categoriesResponse = $this->apiService->getCategories();

            $products = $productsResponse['data'] ?? [];
            $categories = $categoriesResponse['data'] ?? [];

            // return $productsResponse;
            $cartItemsCount = $this->cart->getTotalItems();

            return view('customer.menu', compact('products', 'categories', 'cartItemsCount'));
        } catch (\Exception $e) {
            Log::error('Customer MenuController error: ' . $e->getMessage());
            return view('customer.menu', [
                'products' => [],
                'categories' => [],
                'cartItemsCount' => 0,
                'error' => 'Gagal memuat menu pemesanan. Silakan coba scan QR lagi.'
            ]);
        }
    }

    /**
     * Endpoint API untuk menambahkan produk ke keranjang belanja.
     */
    public function addToCart(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            $productData = $request->input('product_data');
            $quantity = $request->input('quantity', 1);

            $this->cart->addItem($productId, $productData, $quantity);

            return response()->json([
                'success' => true,
                'message' => 'Produk ditambahkan ke keranjang',
                'cart_total' => $this->cart->getTotalItems(),
                'cart_total_price' => $this->cart->getTotal()
            ]);
        } catch (\Exception $e) {
            Log::error('Add to cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk ke keranjang'
            ], 500);
        }
    }

    /**
     * Endpoint API untuk mendapatkan isi keranjang saat ini.
     */
    public function getCart()
    {
        try {
            $cartItems = $this->cart->getItems();
            $total = $this->cart->getTotal();
            $totalItems = $this->cart->getTotalItems();

            return response()->json([
                'success' => true,
                'cart_items' => $cartItems,
                'total' => $total,
                'total_items' => $totalItems
            ]);
        } catch (\Exception $e) {
            Log::error('Get cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat keranjang'
            ], 500);
        }
    }

    /**
     * Endpoint API untuk menghapus produk dari keranjang.
     */
    public function removeFromCart(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            $this->cart->removeItem($productId);

            return response()->json([
                'success' => true,
                'message' => 'Produk dihapus dari keranjang',
                'cart_total' => $this->cart->getTotalItems(),
                'cart_total_price' => $this->cart->getTotal()
            ]);
        } catch (\Exception $e) {
            Log::error('Remove from cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus produk dari keranjang'
            ], 500);
        }
    }

    /**
     * Endpoint API untuk mengosongkan keranjang.
     */
    public function clearCart()
    {
        try {
            $this->cart->clear();

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan'
            ]);
        } catch (\Exception $e) {
            Log::error('Clear cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengosongkan keranjang'
            ], 500);
        }
    }

    /**
     * FUNGSI KRUSIAL: Menyimpan pesanan dan memicu notifikasi Dapur.
     */
    public function storeOrder(Request $request)
    {
        // PENTING: Ini adalah tempat di mana Anda akan mengambil data final dari keranjang ($this->cart->getItems())
        // dan mengirimkannya ke API Service untuk disimpan sebagai pesanan baru.
        // Setelah berhasil, Anda akan memicu mekanisme real-time/pencetakan untuk Dapur.

        try {
            $cartData = $this->cart->getItems();
            if (empty($cartData)) {
                return response()->json(['success' => false, 'message' => 'Keranjang kosong.'], 400);
            }

            // 1. Simpan pesanan via API Service
            $order = $this->apiService->createOrder([
                'total_amount' => $this->cart->getTotal(),
                'items' => $cartData,
                // Tambahkan detail pelanggan/meja jika ada
            ]);

            // 2. Kosongkan keranjang setelah pesanan berhasil
            $this->cart->clear();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dikirim ke dapur!',
                'order_id' => $order['id'] ?? null
            ]);
        } catch (\Exception $e) {
            Log::error('Store order error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}
