<?php

// NAMESPACE SESUAI DENGAN LOKASI FOLDER ADMIN
namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Controller; 
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// GANTI NAMA CLASS MENJADI AdminOrderController UNTUK MENCEGAH KONFLIK
class AdminOrderController extends Controller 
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    // ===========================================
    // 4. PESANAN PELANGGAN (RESOURCE: INDEX, SHOW, UPDATE STATUS)
    // ===========================================

    /**
     * Menampilkan daftar semua pesanan yang perlu diproses.
     */
    public function index()
    {
        try {
            // Memanggil method yang benar di ApiService: getAllOrders()
            $ordersResponse = $this->apiService->getAllOrders(); 
            $orders = $ordersResponse['data'] ?? [];
            
            // Urutkan pesanan berdasarkan ID (terbaru di atas)
            $orders = collect($orders)->sortByDesc('id')->values()->all();

            return view('admin.orders.index', compact('orders'));
        } catch (\Exception $e) {
            Log::error('Admin orders index error: ' . $e->getMessage());
            return view('admin.orders.index')->with('error', 'Gagal memuat daftar pesanan');
        }
    }

    /**
     * Menampilkan detail pesanan tertentu.
     */
    public function show($orderId)
    {
        try {
            // ✅ PERBAIKAN: Mengganti getOrderById() menjadi getOrderDetail()
            $orderResponse = $this->apiService->getOrderDetail($orderId); 
            $order = $orderResponse['data'] ?? null;

            if (!$order) {
                return redirect()->route('admin.orders.index')->with('error', 'Pesanan tidak ditemukan.');
            }

            return view('admin.orders.show', compact('order'));
        } catch (\Exception $e) {
            Log::error('Admin order show error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat detail pesanan');
        }
    }
    
    /**
     * Memperbarui status pesanan.
     */
    public function updateStatus(Request $request, $orderId)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,ready,completed,cancelled', 
        ]);

        try {
            $response = $this->apiService->updateOrderStatus($orderId, $validated['status']);

            if (isset($response['success']) && $response['success']) {
                return back()->with('success', '✅ Status pesanan berhasil diperbarui!');
            }

            return back()->with('error', '❌ Gagal memperbarui status: ' . ($response['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Update order status error: ' . $e->getMessage());
            return back()->with('error', '❌ Terjadi kesalahan sistem saat memperbarui status.');
        }
    }

    // Pastikan rute di web.php sudah menggunakan AdminOrderController::class
}
