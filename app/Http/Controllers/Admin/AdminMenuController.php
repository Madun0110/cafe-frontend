<?php
// NAMESPACE SESUAI DENGAN LOKASI FOLDER ADMIN
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // Wajib: Import Controller dasar
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// GANTI NAMA CLASS MENJADI AdminMenuController UNTUK MENCEGAH KONFLIK
class AdminMenuController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    // ===========================================
    // KELOLA KATEGORI (CRUD)
    // ===========================================

    /**
     * Menampilkan daftar semua Kategori.
     */
    public function categories()
    {
        try {
            // ✅ PERBAIKAN: Mengganti getAllCategories() menjadi getCategories()
            $categoriesResponse = $this->apiService->getCategories();
            $categories = $categoriesResponse['data'] ?? [];

            
            return view('admin.categories', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Admin categories index error: ' . $e->getMessage());
            return view('admin.categories')->with('error', 'Gagal memuat daftar kategori');
        }
    }

    /**
     * Menyimpan kategori baru.
     */
    public function createCategory(Request $request)
    {
        // Catatan: Validasi unique:categories,name seharusnya dilakukan di backend API
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $response = $this->apiService->createCategory($validated);

            if (isset($response['success']) && $response['success']) {
                return redirect()->route('admin.categories')->with('success', '✅ Kategori berhasil ditambahkan!');
            }

            return back()->with('error', '❌ Gagal menambah kategori: ' . ($response['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Create category error: ' . $e->getMessage());
            return back()->with('error', '❌ Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui kategori yang ada.
     */
    public function updateCategory(Request $request, $id)
    {
        // Catatan: Validasi unique:categories,name seharusnya dilakukan di backend API
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $response = $this->apiService->updateCategory($id, $validated);

            if (isset($response['success']) && $response['success']) {
                return redirect()->route('admin.categories')->with('success', '✅ Kategori berhasil diupdate!');
            }

            return back()->with('error', '❌ Gagal mengupdate kategori: ' . ($response['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Update category error: ' . $e->getMessage());
            return back()->with('error', '❌ Terjadi kesalahan sistem');
        }
    }

    /**
     * Menghapus kategori.
     */
    public function deleteCategory($id)
    {
        try {
            $response = $this->apiService->deleteCategory($id);

            if (isset($response['success']) && $response['success']) {
                return redirect()->route('admin.categories')->with('success', '✅ Kategori berhasil dihapus!');
            }

            return back()->with('error', '❌ Gagal menghapus kategori: ' . ($response['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            Log::error('Delete category error: ' . $e->getMessage());
            return back()->with('error', '❌ Terjadi kesalahan sistem');
        }
    }
}
