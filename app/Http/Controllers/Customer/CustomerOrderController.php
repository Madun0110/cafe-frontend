<?php
// app/Http/Controllers/Customer/OrderController.php
// Controller ini menangani proses checkout dan pengiriman pesanan ke API.

// NAMESPACE DISESUAIKAN DENGAN FOLDER CUSTOMER
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller; // Wajib: Import Controller dasar
use App\Services\ApiService;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerOrderController extends Controller
{
    protected $apiService;
    protected $cart;

    // Dependency Injection: Menyuntikkan ApiService dan Cart Service
    public function __construct(ApiService $apiService, Cart $cart)
    {
        $this->apiService = $apiService;
        $this->cart = $cart;
    }

    /**
     * Menampilkan form untuk konfirmasi pesanan (halaman checkout).
     * Dipanggil saat pelanggan menekan tombol 'Pesan' dari keranjang.
     * Menggunakan view: resources/views/orders/create.blade.php
     */
    public function showOrderForm()
    {
        try {
            // Ambil item dan total dari service keranjang
            $cartItems = $this->cart->getItems();
            $total = $this->cart->getTotal();

            if (empty($cartItems)) {
                return redirect()->route('menu')->with('error', 'Keranjang kosong. Tambahkan item terlebih dahulu.');
            }

            // Mengirim data keranjang ke view checkout
            return view('orders.create', compact('cartItems', 'total'));

        } catch (\Exception $e) {
            Log::error('Show order form error: ' . $e->getMessage());
            return redirect()->route('menu')->with('error', 'Gagal memuat form order. Coba lagi.');
        }
    }

    /**
     * Menyimpan data pesanan ke API.
     * Dipanggil dari form konfirmasi pesanan (orders/create.blade.php).
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'table' => 'required|integer|min:1|max:50',
                'products' => 'required|array|min:1',
                'total' => 'required|min:0',
                'payment_method' => 'required',

            ]);
            $response = $this->apiService->createOrder($validated);
            return $response;
            Log::info('Order API response:', $response);

            if (isset($response['success']) && $response['success']) {
                return redirect()->route('menu')
                    ->with('success', '✅ Pesanan berhasil dibuat! Nomor meja Anda: ' . $validated['table'] . '. Mohon tunggu.');
            }

            $errorMessage = $response['message'] ?? 'Error tidak diketahui dari API';
            return back()->with('error', '❌ Gagal membuat pesanan: ' . $errorMessage);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Menangani error validasi
            $flatErrors = array_reduce($e->errors(), 'array_merge', []);

            return back()->withInput()->with('error', '❌ Data tidak valid: ' . implode(', ', $flatErrors));
        } catch (\Exception $e) {
            // Tangani error sistem atau koneksi API
            Log::error('Kesalahan saat membuat pesanan: ' . $e->getMessage());
            return back()->with('error', '❌ Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
