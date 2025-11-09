<?php
// app/Services/ApiService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = env('API_BASE_URL');
    }

    // ==================== CATEGORIES ====================

    /**
     * Metode asli untuk mengambil daftar kategori. Mengembalikan respons mentah dari API.
     */
    public function getCategories()
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/api/categories");

            Log::info('Categories API Response: ' . $response->status());

            // Perbaiki format: Pastikan selalu mengembalikan array dengan kunci 'data'
            $data = $response->json();
            return [
                'data' => $data['data'] ?? $data, // Ambil data dari key 'data' atau array mentah
                'success' => $response->successful()
            ];

        } catch (\Exception $e) {
            Log::error('API Categories Error: ' . $e->getMessage());
            return ['data' => []]; // Fallback untuk mencegah error di Controller
        }
    }

    /**
     * ALIAS METHOD: getAllCategories()
     * Dibuat agar sesuai dengan panggilan di AdminController (FIX ERROR Undefined Method)
     */
    public function getAllCategories()
    {
        return $this->getCategories();
    }

    public function getCategory($id)
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/api/categories/{$id}");

            $data = $response->json();
            return [
                'data' => $data['data'] ?? $data,
                'success' => $response->successful()
            ];

        } catch (\Exception $e) {
            Log::error('API Single Category Error: ' . $e->getMessage());
            return ['data' => null];
        }
    }

    public function createCategory($data)
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->post("{$this->baseUrl}/api/categories", $data);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('API Create Category Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateCategory($id, $data)
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->put("{$this->baseUrl}/api/categories/{$id}", $data);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('API Update Category Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function deleteCategory($id)
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->delete("{$this->baseUrl}/api/categories/{$id}");
            return $response->json();
        } catch (\Exception $e) {
            Log::error('API Delete Category Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ==================== PRODUCTS ====================
    public function getProducts()
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/api/guest/products");
            // return $response
            Log::info('Products API Response: ' . $response->status());

            $data = $response->json();
            return [
                'data' => $data['data'] ?? $data,
                'success' => $response->successful()
            ];

        } catch (\Exception $e) {
            Log::error('API Products Error: ' . $e->getMessage());
            return ['data' => []];
        }
    }

    public function getAllProducts()
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/api/products");

            $data = $response->json();
            return [
                'data' => $data['data'] ?? $data,
                'success' => $response->successful()
            ];
        } catch (\Exception $e) {
            Log::error('API All Products Error: ' . $e->getMessage());
            return ['data' => []];
        }
    }

    public function getProduct($id)
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/api/products/{$id}");

            $data = $response->json();
            return [
                'data' => $data['data'] ?? $data,
                'success' => $response->successful()
            ];
        } catch (\Exception $e) {
            Log::error('API Single Product Error: ' . $e->getMessage());
            return ['data' => null];
        }
    }

    public function createProduct($data)
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->post("{$this->baseUrl}/api/products", $data);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('API Create Product Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateProduct($id, $data)
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->put("{$this->baseUrl}/api/products/{$id}", $data);
            return $response->json();
        } catch (\Exception $e) {
            Log::error('API Update Product Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function deleteProduct($id)
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->delete("{$this->baseUrl}/api/products/{$id}");
            return $response->json();
        } catch (\Exception $e) {
            Log::error('API Delete Product Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ==================== ORDERS ====================
    public function createOrder($data)
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->post("{$this->baseUrl}/api/guest/order", $data);

            Log::info('Create Order Response: ' . $response->status());
            return $response->json();
        } catch (\Exception $e) {
            Log::error('API Create Order Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Mengambil daftar semua pesanan (Riwayat) dari API.
     */
    public function getAllOrders()
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/api/orders");

            Log::info('Get All Orders Response: ' . $response->status());

            if ($response->successful()) {
                $apiData = $response->json();
                // Mengembalikan format yang diharapkan oleh OrderController: ['success' => true, 'data' => [...]]
                return [
                    'success' => true,
                    // Mengambil data dari key 'data' API atau array mentah
                    'data' => $apiData['data'] ?? $apiData
                ];
            }

            return ['success' => false, 'message' => 'Gagal mengambil daftar pesanan dari API.', 'data' => []];

        } catch (\Exception $e) {
            Log::error('API Get All Orders Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage(), 'data' => []];
        }
    }

    /**
     * Mengambil detail satu pesanan berdasarkan ID dari API.
     */
    public function getOrderDetail($id)
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/api/orders/{$id}");

            Log::info('Get Order Detail Response: ' . $response->status());

            if ($response->successful()) {
                $apiData = $response->json();

                // Mengembalikan format yang diharapkan oleh OrderController: ['success' => true, 'data' => {...}]
                return [
                    'success' => true,
                    'data' => $apiData['data'] ?? $apiData
                ];
            }

            return ['success' => false, 'message' => 'Gagal mengambil detail pesanan dari API.', 'data' => null];

        } catch (\Exception $e) {
            Log::error('API Get Order Detail Error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage(), 'data' => null];
        }
    }

    /**
     * Memperbarui status pesanan (diubah oleh Admin) ke API.
     */
    public function updateOrderStatus($id, $status)
    {
        try {
            // Mengirim permintaan PATCH dengan payload 'status'
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->patch("{$this->baseUrl}/api/orders/{$id}/status", [
                    'status' => $status,
                ]);

            Log::info('Update Order Status Response: ' . $response->status());

            // Pastikan respons berhasil (kode 2xx)
            if ($response->successful()) {
                return ['success' => true, 'message' => 'Status berhasil diperbarui.'];
            }

            // Tangani error spesifik dari API (jika ada)
            $apiData = $response->json();
            $message = $apiData['message'] ?? 'Gagal memperbarui status. Kode: ' . $response->status();

            return ['success' => false, 'message' => $message];

        } catch (\Exception $e) {
            Log::error('API Update Order Status Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Gagal koneksi ke API: ' . $e->getMessage()];
        }
    }


    // ==================== KITCHEN ORDERS ====================
    public function getFoodOrders()
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/api/kitchen/food");

            $data = $response->json();
            return [
                'data' => $data['data'] ?? $data,
                'success' => $response->successful()
            ];

        } catch (\Exception $e) {
            Log::error('API Food Orders Error: ' . $e->getMessage());
            return ['data' => []];
        }
    }

    public function getDrinkOrders()
    {
        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/api/kitchen/drink");

            $data = $response->json();
            return [
                'data' => $data['data'] ?? $data,
                'success' => $response->successful()
            ];
        } catch (\Exception $e) {
            Log::error('API Drink Orders Error: ' . $e->getMessage());
            return ['data' => []];
        }
    }

    // ==================== UTILITY METHODS ====================

    /**
     * Metode ini perlu diubah untuk menggunakan method getCategories() yang sudah diperbaiki
     * agar return valuenya konsisten (array dengan kunci 'data').
     */
    public function getSystemSummary()
    {
        try {
            // Memastikan method yang dipanggil mengembalikan format array ['data' => ...]
            $categoriesResponse = $this->getCategories();
            $productsResponse = $this->getAllProducts();
            $foodOrdersResponse = $this->getFoodOrders();
            $drinkOrdersResponse = $this->getDrinkOrders();

            return [
                'success' => true,
                'data' => [
                    'categories_count' => count($categoriesResponse['data'] ?? []),
                    'products_count' => count($productsResponse['data'] ?? []),
                    'food_orders_count' => count($foodOrdersResponse['data'] ?? []),
                    'drink_orders_count' => count($drinkOrdersResponse['data'] ?? []),
                    'timestamp' => now()->toDateTimeString()
                ]
            ];
        } catch (\Exception $e) {
            Log::error('System Summary Error: ' . $e->getMessage());
            return ['success' => false, 'data' => [], 'message' => $e->getMessage()];
        }
    }

    // Method utility lainnya tidak diubah agar fokus pada perbaikan error Controller

    public function testConnection()
    {
        try {
            $response = Http::timeout(10)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/api/categories");

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $response->json(),
                'url' => $this->baseUrl
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'url' => $this->baseUrl
            ];
        }
    }

    public function getApiStatus()
    {
        try {
            $categories = Http::timeout(10)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/api/categories");

            $products = Http::timeout(10)
                ->withOptions(['verify' => false])
                ->get("{$this->baseUrl}/api/products");

            // NOTE: Di sini asumsi API mengembalikan data di luar format standar.
            return [
                'categories' => $categories->successful(),
                'products' => $products->successful(),
                'categories_count' => count($categories->json()['data'] ?? []),
                'products_count' => count($products->json()['data'] ?? [])
            ];
        } catch (\Exception $e) {
            return [
                'categories' => false,
                'products' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
