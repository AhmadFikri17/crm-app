<?php
// index.php - Landing Page
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard/index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM System - Solusi Manajemen Pelanggan Modern</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/dashboard.js"></script>
</head>
<body>
    <!-- Navbar -->
    <nav class="bg-white shadow-sm fixed w-full z-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-[#86EFAC] rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-[#22C55E] text-xl"></i>
                    </div>
                    <span class="text-xl font-bold text-[#1F2937]">CRM<span class="text-[#22C55E]">System</span></span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="nav-link text-[#1F2937] hover:text-[#22C55E]">Fitur</a>
                    <a href="#benefits" class="nav-link text-[#1F2937] hover:text-[#22C55E]">Manfaat</a>
                    <a href="#testimonials" class="nav-link text-[#1F2937] hover:text-[#22C55E]">Testimoni</a>
                    <a href="#pricing" class="nav-link text-[#1F2937] hover:text-[#22C55E]">Harga</a>
                </div>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="auth/login.php" class="text-[#1F2937] hover:text-[#22C55E] font-medium transition">
                        Masuk
                    </a>
                    <a href="auth/register.php" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        Daftar Gratis
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button class="md:hidden text-[#1F2937] focus:outline-none" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="md:hidden hidden py-4 border-t border-[#D1FAE5]">
                <div class="flex flex-col space-y-3">
                    <a href="#features" class="text-[#1F2937] hover:text-[#22C55E] px-4 py-2 rounded-lg hover:bg-[#F0FDF4]">Fitur</a>
                    <a href="#benefits" class="text-[#1F2937] hover:text-[#22C55E] px-4 py-2 rounded-lg hover:bg-[#F0FDF4]">Manfaat</a>
                    <a href="#testimonials" class="text-[#1F2937] hover:text-[#22C55E] px-4 py-2 rounded-lg hover:bg-[#F0FDF4]">Testimoni</a>
                    <a href="#pricing" class="text-[#1F2937] hover:text-[#22C55E] px-4 py-2 rounded-lg hover:bg-[#F0FDF4]">Harga</a>
                    <div class="pt-4 border-t border-[#D1FAE5] flex flex-col space-y-3">
                        <a href="auth/login.php" class="text-center text-[#1F2937] hover:text-[#22C55E] font-medium">Masuk</a>
                        <a href="auth/register.php" class="btn-primary text-white text-center px-6 py-2 rounded-lg font-medium">
                            Daftar Gratis
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="gradient-bg pt-24 pb-16 md:pt-32 md:pb-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-block bg-white/90 px-4 py-2 rounded-full text-sm font-medium text-[#22C55E] mb-6 shadow-sm">
                        <i class="fas fa-rocket mr-2"></i>
                        Solusi Manajemen Pelanggan Terbaik
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[#1F2937] leading-tight mb-6">
                        Kelola Pelanggan dengan
                        <span class="text-[#22C55E]">Mudah & Efisien</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Platform CRM modern yang membantu bisnis Anda mengelola data pelanggan, 
                        riwayat interaksi, dan meningkatkan hubungan dengan pelanggan secara profesional.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="auth/register.php" class="btn-primary text-white px-8 py-4 rounded-lg font-semibold text-center inline-flex items-center justify-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            Mulai Sekarang
                        </a>
                        <a href="#features" class="btn-outline px-8 py-4 rounded-lg font-semibold text-center inline-flex items-center justify-center">
                            <i class="fas fa-play-circle mr-2"></i>
                            Lihat Fitur
                        </a>
                    </div>
                    <div class="mt-8 flex items-center space-x-6">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <i class="fas fa-star text-yellow-400"></i>
                            <span class="text-sm text-gray-600 ml-2">4.9/5 (200+ ulasan)</span>
                        </div>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="bg-white rounded-2xl shadow-2xl p-8 relative">
                        <div class="absolute -top-4 -right-4 w-20 h-20 bg-[#22C55E] rounded-full opacity-10"></div>
                        <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-[#22C55E] rounded-full opacity-10"></div>
                        <div class="relative">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="w-12 h-12 bg-[#86EFAC] rounded-full flex items-center justify-center">
                                    <i class="fas fa-chart-line text-[#22C55E] text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-[#1F2937]">Dashboard Interaktif</h3>
                                    <p class="text-sm text-gray-500">Pantau performa bisnis Anda</p>
                                </div>
                            </div>
                            <!-- Mock Chart -->
                            <div class="bg-[#F0FDF4] rounded-xl p-4 mb-4">
                                <div class="flex items-end h-32 space-x-2">
                                    <div class="w-full bg-[#22C55E] rounded-t-lg h-20" style="height: 60%"></div>
                                    <div class="w-full bg-[#22C55E] rounded-t-lg h-24" style="height: 75%"></div>
                                    <div class="w-full bg-[#22C55E] rounded-t-lg h-16" style="height: 45%"></div>
                                    <div class="w-full bg-[#22C55E] rounded-t-lg h-28" style="height: 85%"></div>
                                    <div class="w-full bg-[#22C55E] rounded-t-lg h-20" style="height: 55%"></div>
                                    <div class="w-full bg-[#22C55E] rounded-t-lg h-32" style="height: 95%"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div class="bg-[#F0FDF4] rounded-lg p-3 text-center">
                                    <p class="text-sm text-gray-500">Pelanggan</p>
                                    <p class="text-xl font-bold text-[#22C55E]">1,234</p>
                                </div>
                                <div class="bg-[#F0FDF4] rounded-lg p-3 text-center">
                                    <p class="text-sm text-gray-500">Interaksi</p>
                                    <p class="text-xl font-bold text-[#22C55E]">856</p>
                                </div>
                                <div class="bg-[#F0FDF4] rounded-lg p-3 text-center">
                                    <p class="text-sm text-gray-500">Aktif</p>
                                    <p class="text-xl font-bold text-[#22C55E]">92%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-[#22C55E] font-semibold text-sm uppercase tracking-wider">Fitur Unggulan</span>
                <h2 class="text-3xl md:text-4xl font-bold text-[#1F2937] mt-2 mb-4">
                    Semua yang Anda Butuhkan untuk Mengelola Pelanggan
                </h2>
                <p class="text-gray-600 text-lg">
                    CRM System dilengkapi dengan fitur lengkap untuk membantu bisnis Anda berkembang
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card bg-white rounded-2xl p-8">
                    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-users text-2xl text-[#22C55E]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#1F2937] mb-3">Manajemen Pelanggan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kelola data pelanggan dengan mudah. Tambah, edit, hapus, dan cari pelanggan secara realtime.
                    </p>
                    <ul class="mt-4 space-y-2">
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            Auto-generate ID pelanggan
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            Pencarian realtime
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            Status pelanggan aktif/tidak aktif
                        </li>
                    </ul>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card bg-white rounded-2xl p-8">
                    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-comments text-2xl text-[#22C55E]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#1F2937] mb-3">Riwayat Interaksi</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Catat semua interaksi dengan pelanggan melalui berbagai kanal komunikasi.
                    </p>
                    <ul className="mt-4 space-y-2">
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            Telepon, Email, WhatsApp, Meeting
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            Filter berdasarkan jenis & tanggal
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            Catatan lengkap setiap interaksi
                        </li>
                    </ul>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card bg-white rounded-2xl p-8">
                    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-pie text-2xl text-[#22C55E]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#1F2937] mb-3">Dashboard & Analitik</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pantau performa bisnis dengan dashboard interaktif dan grafik visual.
                    </p>
                    <ul className="mt-4 space-y-2">
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            Statistik realtime
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            Grafik pelanggan per bulan
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            Aktivitas terbaru
                        </li>
                    </ul>
                </div>

                <!-- Feature 4 -->
                <div class="feature-card bg-white rounded-2xl p-8">
                    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-shield-alt text-2xl text-[#22C55E]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#1F2937] mb-3">Keamanan Terjamin</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sistem keamanan berlapis untuk melindungi data bisnis Anda.
                    </p>
                    <ul className="mt-4 space-y-2">
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            Password hashing
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            CSRF protection
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            SQL injection prevention
                        </li>
                    </ul>
                </div>

                <!-- Feature 5 -->
                <div class="feature-card bg-white rounded-2xl p-8">
                    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-users-cog text-2xl text-[#22C55E]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#1F2937] mb-3">Manajemen Role</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Atur akses pengguna dengan sistem role yang fleksibel.
                    </p>
                    <ul className="mt-4 space-y-2">
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            Admin & Staff roles
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            Hak akses berbeda
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            Mudah dikelola
                        </li>
                    </ul>
                </div>

                <!-- Feature 6 -->
                <div class="feature-card bg-white rounded-2xl p-8">
                    <div class="feature-icon w-16 h-16 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-mobile-alt text-2xl text-[#22C55E]"></i>
                    </div>
                    <h3 class="text-xl font-bold text-[#1F2937] mb-3">Responsive Design</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Akses CRM dari mana saja dan kapan saja dengan tampilan yang optimal.
                    </p>
                    <ul className="mt-4 space-y-2">
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            Mobile friendly
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            Tampilan responsif
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check-circle text-[#22C55E] mr-2"></i>
                            Akses dari semua perangkat
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-[#F0FDF4]">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="stat-number">500+</div>
                    <p class="text-gray-600 mt-2">Perusahaan Percaya</p>
                </div>
                <div class="text-center">
                    <div class="stat-number">10K+</div>
                    <p class="text-gray-600 mt-2">Pelanggan Terkelola</p>
                </div>
                <div class="text-center">
                    <div class="stat-number">50K+</div>
                    <p class="text-gray-600 mt-2">Interaksi Tercatat</p>
                </div>
                <div class="text-center">
                    <div class="stat-number">98%</div>
                    <p class="text-gray-600 mt-2">Kepuasan Pengguna</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-[#22C55E] font-semibold text-sm uppercase tracking-wider">Manfaat</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-[#1F2937] mt-2 mb-6">
                        Mengapa Memilih CRM System?
                    </h2>
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-[#F0FDF4] rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-[#22C55E] text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#1F2937] mb-1">Efisiensi Waktu</h4>
                                <p class="text-gray-600">Kelola semua data pelanggan dalam satu platform terintegrasi.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-[#F0FDF4] rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-chart-line text-[#22C55E] text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#1F2937] mb-1">Peningkatan Bisnis</h4>
                                <p class="text-gray-600">Pantau performa dan ambil keputusan berdasarkan data akurat.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-[#F0FDF4] rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-handshake text-[#22C55E] text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#1F2937] mb-1">Hubungan Pelanggan</h4>
                                <p class="text-gray-600">Bangun hubungan lebih baik dengan riwayat interaksi lengkap.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-[#F0FDF4] rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-lock text-[#22C55E] text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-[#1F2937] mb-1">Keamanan Data</h4>
                                <p class="text-gray-600">Data bisnis Anda aman dengan sistem keamanan berlapis.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="bg-gradient-to-br from-[#F0FDF4] to-[#86EFAC] rounded-3xl p-8">
                        <div class="bg-white rounded-2xl shadow-xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-[#1F2937]">Statistik Pelanggan</h4>
                                <span class="text-sm text-[#22C55E] font-semibold">+12%</span>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Pelanggan Aktif</span>
                                        <span class="font-medium">85%</span>
                                    </div>
                                    <div class="w-full bg-[#F0FDF4] rounded-full h-2">
                                        <div class="bg-[#22C55E] h-2 rounded-full" style="width: 85%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Interaksi Terjawab</span>
                                        <span class="font-medium">92%</span>
                                    </div>
                                    <div class="w-full bg-[#F0FDF4] rounded-full h-2">
                                        <div class="bg-[#22C55E] h-2 rounded-full" style="width: 92%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600">Kepuasan Pelanggan</span>
                                        <span class="font-medium">78%</span>
                                    </div>
                                    <div class="w-full bg-[#F0FDF4] rounded-full h-2">
                                        <div class="bg-[#22C55E] h-2 rounded-full" style="width: 78%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-16 md:py-24 bg-[#F0FDF4]">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-[#22C55E] font-semibold text-sm uppercase tracking-wider">Testimoni</span>
                <h2 class="text-3xl md:text-4xl font-bold text-[#1F2937] mt-2 mb-4">
                    Apa Kata Pengguna Kami
                </h2>
                <p class="text-gray-600 text-lg">
                    Ribuan bisnis telah mempercayai CRM System untuk mengelola pelanggan mereka
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="testimonial-card bg-white rounded-2xl p-8">
                    <div class="flex items-center space-x-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        "CRM System sangat membantu kami dalam mengelola ribuan pelanggan. Fitur interaksinya memudahkan tim untuk melacak komunikasi."
                    </p>
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-[#86EFAC] rounded-full flex items-center justify-center text-[#22C55E] font-bold">
                            AB
                        </div>
                        <div>
                            <h4 class="font-bold text-[#1F2937]">Ahmad Budi</h4>
                            <p class="text-sm text-gray-500">CEO, PT Maju Jaya</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card bg-white rounded-2xl p-8">
                    <div class="flex items-center space-x-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        "Dashboard yang informatif dan mudah dipahami. Kami bisa memantau pertumbuhan bisnis secara realtime."
                    </p>
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-[#86EFAC] rounded-full flex items-center justify-center text-[#22C55E] font-bold">
                            SR
                        </div>
                        <div>
                            <h4 class="font-bold text-[#1F2937]">Siti Rahayu</h4>
                            <p class="text-sm text-gray-500">Marketing Manager, CV Kreatif</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card bg-white rounded-2xl p-8">
                    <div class="flex items-center space-x-1 mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-600 leading-relaxed mb-6">
                        "Sistem yang stabil dan aman. Tim kami sangat terbantu dengan fitur pencarian dan filter yang cepat."
                    </p>
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-[#86EFAC] rounded-full flex items-center justify-center text-[#22C55E] font-bold">
                            BW
                        </div>
                        <div>
                            <h4 class="font-bold text-[#1F2937]">Budi Wibowo</h4>
                            <p class="text-sm text-gray-500">Owner, UD Berkah</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-16 md:py-24 bg-white">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-[#22C55E] font-semibold text-sm uppercase tracking-wider">Harga</span>
                <h2 class="text-3xl md:text-4xl font-bold text-[#1F2937] mt-2 mb-4">
                    Pilih Paket Sesuai Kebutuhan
                </h2>
                <p class="text-gray-600 text-lg">
                    Mulai dari gratis hingga paket profesional untuk bisnis Anda
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Free Plan -->
                <div class="pricing-card bg-white rounded-2xl p-8">
                    <h3 class="text-xl font-bold text-[#1F2937] mb-2">Gratis</h3>
                    <p class="text-gray-500 text-sm mb-4">Untuk pemula</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-[#1F2937]">Rp0</span>
                        <span class="text-gray-500">/bulan</span>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-[#22C55E] mr-3"></i>
                            10 Pelanggan
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-[#22C55E] mr-3"></i>
                            Interaksi dasar
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-[#22C55E] mr-3"></i>
                            Dashboard
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-[#22C55E] mr-3"></i>
                            1 User
                        </li>
                    </ul>
                    <a href="auth/register.php" class="btn-outline w-full block text-center py-3 rounded-lg font-medium">
                        Mulai Gratis
                    </a>
                </div>

                <!-- Pro Plan -->
                <div class="pricing-card popular bg-white rounded-2xl p-8 relative">
                    <div class="popular-badge absolute -top-3 left-1/2 transform -translate-x-1/2 px-4 py-1 rounded-full text-xs font-semibold">
                        <i class="fas fa-star mr-1"></i>
                        POPULER
                    </div>
                    <h3 class="text-xl font-bold text-[#1F2937] mb-2">Pro</h3>
                    <p class="text-gray-500 text-sm mb-4">Untuk bisnis berkembang</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-[#1F2937]">Rp99K</span>
                        <span class="text-gray-500">/bulan</span>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-[#22C55E] mr-3"></i>
                            Unlimited Pelanggan
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-[#22C55E] mr-3"></i>
                            Interaksi lengkap
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-[#22C55E] mr-3"></i>
                            Dashboard & Analitik
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-[#22C55E] mr-3"></i>
                            5 Users
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-[#22C55E] mr-3"></i>
                            Support prioritas
                        </li>
                    </ul>
                    <a href="auth/register.php" class="btn-primary w-full block text-center text-white py-3 rounded-lg font-medium">
                        Mulai Sekarang
                    </a>
                </div>

                <!-- Enterprise Plan -->
                <div class="pricing-card bg-white rounded-2xl p-8">
                    <h3 class="text-xl font-bold text-[#1F2937] mb-2">Enterprise</h3>
                    <p class="text-gray-500 text-sm mb-4">Untuk korporasi</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-[#1F2937]">Rp299K</span>
                        <span class="text-gray-500">/bulan</span>
                    </div>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-[#22C55E] mr-3"></i>
                            Unlimited Pelanggan
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-[#22C55E] mr-3"></i>
                            Interaksi lengkap
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-[#22C55E] mr-3"></i>
                            Dashboard & Analitik
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-[#22C55E] mr-3"></i>
                            Unlimited Users
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-[#22C55E] mr-3"></i>
                            Support 24/7
                        </li>
                        <li class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-check text-[#22C55E] mr-3"></i>
                            Custom fitur
                        </li>
                    </ul>
                    <a href="auth/register.php" class="btn-outline w-full block text-center py-3 rounded-lg font-medium">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 md:py-20 gradient-bg">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-[#1F2937] mb-4">
                Siap Mengelola Pelanggan Lebih Baik?
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
                Bergabunglah dengan ribuan bisnis yang telah menggunakan CRM System
                untuk meningkatkan hubungan dengan pelanggan.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="auth/register.php" class="btn-primary text-white px-8 py-4 rounded-lg font-semibold inline-flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Sekarang
                </a>
                <a href="#features" class="bg-white text-[#1F2937] px-8 py-4 rounded-lg font-semibold inline-flex items-center justify-center hover:shadow-lg transition">
                    <i class="fas fa-info-circle mr-2"></i>
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#1F2937] text-white py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-[#86EFAC] rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-[#22C55E] text-xl"></i>
                        </div>
                        <span class="text-xl font-bold">CRM<span class="text-[#22C55E]">System</span></span>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Solusi manajemen pelanggan modern untuk bisnis Anda.
                    </p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-[#22C55E] transition">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-[#22C55E] transition">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-[#22C55E] transition">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-[#22C55E] transition">
                            <i class="fab fa-youtube text-xl"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Fitur</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="footer-link">Manajemen Pelanggan</a></li>
                        <li><a href="#" class="footer-link">Riwayat Interaksi</a></li>
                        <li><a href="#" class="footer-link">Dashboard</a></li>
                        <li><a href="#" class="footer-link">Analitik</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Perusahaan</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="footer-link">Tentang Kami</a></li>
                        <li><a href="#" class="footer-link">Karir</a></li>
                        <li><a href="#" class="footer-link">Blog</a></li>
                        <li><a href="#" class="footer-link">Kontak</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Dukungan</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="footer-link">Pusat Bantuan</a></li>
                        <li><a href="#" class="footer-link">Dokumentasi</a></li>
                        <li><a href="#" class="footer-link">API</a></li>
                        <li><a href="#" class="footer-link">Status</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; 2026 CRM System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>