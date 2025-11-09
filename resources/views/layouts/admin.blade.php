<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'KopiKu Café')</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        // WARNA KUSTOM KOPIKU
                        'kopi-dark': '#6B3E1A',      // Cokelat gelap untuk sidebar/teks utama
                        'kopi-primary': '#A0522D',   // Cokelat sedang untuk hover/aktif
                        'kopi-accent': '#C68642',    // Cokelat muda/oranye untuk aksen
                        'kopi-cream': '#FBF6F0',     // Krem untuk background body
                        'kopi-light-bg': '#F0DFB0',  // Krem cokelat muda (WARNA BARU UNTUK TEKS NAVIGASI)
                        'kopi-green': '#519872',     // Hijau untuk status
                    },
                    fontFamily: {
                        serif: ['Georgia', 'Times New Roman', 'serif'], 
                        sans: ['Arial', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    
    <link href="https://fonts.googleapis.com/css2?family=Georgia&display=swap" rel="stylesheet">
    
    <style>
        /* Mengaplikasikan font serif ke body dan judul */
        body, h1, h2, h3, h4 {
            font-family: 'Georgia', 'Times New Roman', serif;
        }
        .sidebar {
            transition: all 0.3s ease;
        }
        .main-content {
            transition: all 0.3s ease;
        }
        /* Style Mobile */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.active {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="bg-kopi-cream">

    <div class="lg:hidden fixed top-4 left-4 z-50">
        <button id="mobile-menu-btn" class="bg-kopi-primary text-white p-3 rounded-lg shadow-lg">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <div class="sidebar fixed inset-y-0 left-0 z-40 w-64 bg-kopi-dark text-kopi-cream shadow-2xl">
        
        <div class="p-6 border-b border-kopi-primary">
            <div class="flex items-center space-x-3">
                <i class="fas fa-coffee text-3xl text-kopi-accent"></i>
                <div>
                    <h1 class="text-xl font-serif font-bold text-kopi-cream">KopiKu Café</h1>
                    <p class="text-sm text-kopi-light-bg opacity-70">Admin Panel</p>
                </div>
            </div>
        </div>

        <nav class="p-4 space-y-2">
            
            {{-- 1. Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" 
               class="flex items-center space-x-3 p-3 rounded-lg hover:bg-kopi-primary transition 
               {{ request()->routeIs('admin.dashboard') ? 'bg-kopi-primary text-white shadow-inner' : 'text-kopi-light-bg' }}">
                <i class="fas fa-tachometer-alt w-6"></i>
                <span>Dashboard</span>
            </a>

            {{-- 2. Pesanan Pelanggan (BARU DITAMBAHKAN) --}}
            <a href="{{ route('admin.orders.index') }}" 
               class="flex items-center space-x-3 p-3 rounded-lg hover:bg-kopi-primary transition 
               {{ request()->routeIs('admin.orders.index') ? 'bg-kopi-primary text-white shadow-inner' : 'text-kopi-light-bg' }}">
                <i class="fas fa-receipt w-6"></i>
                <span>Pesanan Pelanggan</span>
            </a>
            
            {{-- 3. Kelola Produk --}}
            <a href="{{ route('admin.products') }}" 
               class="flex items-center space-x-3 p-3 rounded-lg hover:bg-kopi-primary transition 
               {{ request()->routeIs('admin.products*') ? 'bg-kopi-primary text-white shadow-inner' : 'text-kopi-light-bg' }}">
                <i class="fas fa-box w-6"></i>
                <span>Kelola Produk</span>
            </a>
            
            {{-- 4. Monitor Kitchen --}}
            <a href="{{ route('admin.kitchen') }}" 
               class="flex items-center space-x-3 p-3 rounded-lg hover:bg-kopi-primary transition 
               {{ request()->routeIs('admin.kitchen*') ? 'bg-kopi-primary text-white shadow-inner' : 'text-kopi-light-bg' }}">
                <i class="fas fa-utensils w-6"></i>
                <span>Monitor Kitchen</span>
            </a>
            
            <div class="mt-8 border-t border-kopi-primary pt-4">
                {{-- Asumsi route('menu') mengarah ke tampilan pelanggan --}}
                <a href="{{ route('menu') }}" target="_blank"
                    class="flex items-center space-x-3 p-3 rounded-lg bg-kopi-accent text-white font-semibold shadow-md hover:bg-kopi-primary hover:shadow-lg transition">
                    <i class="fas fa-store w-6"></i>
                    <span>Lihat Menu</span>
                </a>
            </div>
        </nav>
    </div>

    <div class="main-content lg:ml-64 min-h-screen flex flex-col">
        
        <header class="bg-kopi-cream shadow-sm border-b border-kopi-light-bg text-kopi-dark">
            <div class="px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-serif font-bold text-kopi-dark">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-kopi-primary text-sm">@yield('page-description', 'Sistem Pemesanan Kopi')</p>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        {{-- Menggunakan Laravel helper untuk waktu --}}
                        <span class="text-sm text-kopi-primary">{{ now()->format('d F Y') }}</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="p-6 flex-grow">
            @if(session('success'))
                <div class="flash-message mb-6 bg-kopi-green/10 border border-kopi-green text-kopi-dark px-4 py-3 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-3 text-kopi-green"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="flash-message mb-6 bg-red-100 border border-red-500 text-red-700 px-4 py-3 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-3"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
        
        <footer class="bg-kopi-dark text-kopi-light-bg text-center py-2 text-xs mt-auto">
            &copy; {{ date('Y') }} KopiKu Café.
        </footer>
    </div>

    <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.getElementById('mobile-overlay').classList.toggle('hidden');
        });

        document.getElementById('mobile-overlay').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.remove('active');
            this.classList.add('hidden');
        });

        // Auto-hide flash messages
        setTimeout(() => {
            document.querySelectorAll('.flash-message').forEach(msg => {
                msg.style.opacity = '0';
                msg.style.transform = 'translateY(-10px)';
                setTimeout(() => msg.remove(), 300);
            });
        }, 5000);
    </script>

    @yield('scripts')
</body>
</html>
