<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - KopiKu Café</title>
    <!-- Memuat Tailwind CSS dari CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Warna KopiKu */
        :root {
            --brown-dark: #6B3E1A;
            --brown-medium: #A0522D;
            --cream: #FBF6F0;
            --yellow-accent: #D7B56A;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--cream);
        }
        .login-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease-in-out;
            border: 1px solid var(--yellow-accent);
        }
        .login-card:hover {
            transform: translateY(-5px);
        }
        .btn-submit {
            background-color: var(--brown-medium);
            transition: background-color 0.3s;
        }
        .btn-submit:hover {
            background-color: var(--brown-dark);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
    
    <div class="login-card w-full max-w-md bg-white rounded-xl p-8 md:p-12 z-10 border-t-8 border-[var(--yellow-accent)]">
        
        <div class="text-center mb-10">
            <i class="fas fa-user-shield text-5xl text-[var(--yellow-accent)] mb-3"></i>
            <h2 class="text-3xl font-serif font-bold text-[var(--brown-dark)]">Login Area Admin</h2>
            <p class="text-gray-500 mt-2">Masuk untuk mengelola menu dan pesanan.</p>
        </div>

        {{-- Menampilkan pesan error validasi atau sesi --}}
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Mengarahkan ke rute 'admin.login.submit' --}}
        <form method="POST" action="{{ route('admin.login.submit') }}"> 
            @csrf

            <div class="mb-6">
                <label for="email" class="block text-gray-700 text-sm font-semibold mb-2">Email</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--brown-medium)] focus:border-[var(--brown-medium)] outline-none transition duration-150"
                           placeholder="Masukkan email admin">
                </div>
            </div>

            <div class="mb-8">
                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="password" id="password" name="password" required
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--brown-medium)] focus:border-[var(--brown-medium)] outline-none transition duration-150"
                           placeholder="Masukkan password">
                </div>
            </div>

            <button type="submit" class="btn-submit w-full text-white font-bold py-3 rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:scale-[1.01]">
                <i class="fas fa-sign-in-alt mr-2"></i> Masuk
            </button>
        </form>
    </div>
</body>
</html>
