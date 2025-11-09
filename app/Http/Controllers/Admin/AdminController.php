<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
        // Kita tidak memanggil middleware di sini karena akan dipasang di routes/web.php
    }

    // ===========================================
    // AUTHENTICATION (Login & Logout)
    // ===========================================

    /**
     * Menampilkan form login admin.
     */
    public function showLoginForm()
    {
        // View 'admin.login' diasumsikan ada di resources/views/admin/login.blade.php
        return view('admin.login');
    }

    /**
     * Proses login admin.
     * Menggunakan Auth::guard('admin') untuk memproses login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            // Redirect ke dashboard admin setelah login sukses
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau Password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout(); // Logout hanya dari guard admin
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman login setelah logout
        return redirect()->route('admin.login');
    }

    // ===========================================
    // DASHBOARD & KITCHEN MONITORING
    // ===========================================

    /**
     * Menampilkan dashboard utama admin dengan ringkasan data.
     * Menggunakan method getSystemSummary() dari ApiService.
     */
    public function dashboard()
    {
        try {
            // Ambil data ringkasan sistem
            $summaryResponse = $this->apiService->getSystemSummary();
            $summaryData = $summaryResponse['data'] ?? [];

            // Ambil data produk untuk tabel atau ringkasan lain di dashboard
            $productsResponse = $this->apiService->getAllProducts();
            $products = $productsResponse['data'] ?? [];

            // FIX 1: Definisikan variabel count yang dibutuhkan oleh view
            $products_count = count($products);
            $food_orders_count = $summaryData['food_orders_count'] ?? 0;
            $drink_orders_count = $summaryData['drink_orders_count'] ?? 0;

            // Kumpulkan semua data yang akan dikirim ke view
            $data = [
                'products' => $products,
                'products_count' => $products_count,
                'food_orders_count' => $food_orders_count,
                'drink_orders_count' => $drink_orders_count,
                'summary' => $summaryData,
            ];

            return view('admin.dashboard', $data);

        } catch (\Exception $e) {
            Log::error('Admin Dashboard Error: ' . $e->getMessage());

            // FIX 1: Fallback lengkap untuk mencegah error Undefined variable
            return view('admin.dashboard', [
                'products' => [],
                'products_count' => 0,
                'food_orders_count' => 0,
                'drink_orders_count' => 0,
                'error' => 'Gagal memuat ringkasan data dari API.'
            ]);
        }
    }

    /**
     * Menampilkan tampilan monitoring dapur (Kitchen View).
     * Memanggil getFoodOrders() dan getDrinkOrders() untuk efisiensi dapur.
     */
    public function kitchen()
    {
        try {
            // Ambil pesanan makanan dan minuman secara terpisah untuk tampilan dapur
            $foodOrdersResponse = $this->apiService->getFoodOrders();
            $drinkOrdersResponse = $this->apiService->getDrinkOrders();
            // return $drinkOrdersResponse;
            $foodOrders = $foodOrdersResponse['data'];
            $drinkOrders = $drinkOrdersResponse['data'] ?? [];
            // return $drinkOrders;
            return view('admin.kitchen', compact('foodOrders', 'drinkOrders'));
        } catch (\Exception $e) {
            Log::error('Admin Kitchen Error: ' . $e->getMessage());
            return view('admin.kitchen')->with('error', 'Gagal memuat data pesanan dapur dari API.');
        }
    }

    // ===========================================
    // KELOLA PRODUK (CRUD)
    // ===========================================

    /**
     * Menampilkan daftar semua produk.
     * Menggunakan method getAllProducts() dari ApiService.
     */
    public function products()
    {
        try {
            $productsResponse = $this->apiService->getAllProducts();
            $products = $productsResponse['data'] ?? [];

            // FIX 2: Tambahkan pengambilan kategori karena dibutuhkan oleh admin.products view
            $categoriesResponse = $this->apiService->getAllCategories();
            $categories = $categoriesResponse['data'] ?? [];

            // FIX 2: Mengirim variabel $categories ke view
            return view('admin.products', compact('products', 'categories'));
        } catch (\Exception $e) {
            Log::error('Admin Products Index Error: ' . $e->getMessage());

            // FIX 2: Definisikan $categories dan $products di catch block agar View tidak error
            return view('admin.products', [
                'products' => [],
                'categories' => [],
                'error' => 'Gagal memuat daftar produk dan kategori.'
            ]);
        }
    }

    /**
     * Menyimpan produk baru.
     */
    public function createProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'is_available' => 'required',
            'category_id' => 'required|integer',
        ]);
        // dd($validated);
        try {
            $response = $this->apiService->createProduct([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'is_available' => $validated['is_available'] == 1 ? true : false,
                'category_id' => $validated['category_id'],
            ]);
            // return $response;
            if (isset($response['success']) && $response['success']) {
                return redirect()->route('admin.products')->with('success', '✅ Produk berhasil ditambahkan!');
            }

            return back()->with('error', '❌ Gagal menambah produk: ' . ($response['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Create product error: ' . $e->getMessage());
            return back()->with('error', '❌ Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui produk yang ada.
     */
    public function updateProduct(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|integer',
            'image' => 'nullable|url',
        ]);

        try {
            $response = $this->apiService->updateProduct($id, $validated);

            if (isset($response['success']) && $response['success']) {
                return redirect()->route('admin.products')->with('success', '✅ Produk berhasil diupdate!');
            }

            return back()->with('error', '❌ Gagal mengupdate produk: ' . ($response['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Update product error: ' . $e->getMessage());
            return back()->with('error', '❌ Terjadi kesalahan sistem');
        }
    }

    /**
     * Menghapus produk.
     */
    public function deleteProduct($id)
    {
        try {
            $response = $this->apiService->deleteProduct($id);

            if (isset($response['success']) && $response['success']) {
                return redirect()->route('admin.products')->with('success', '✅ Produk berhasil dihapus!');
            }

            return back()->with('error', '❌ Gagal menghapus produk: ' . ($response['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Delete product error: ' . $e->getMessage());
            return back()->with('error', '❌ Terjadi kesalahan sistem');
        }
    }
}
