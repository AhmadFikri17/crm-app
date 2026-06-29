-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Jun 2026 pada 14.19
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crm_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `customer_code` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kota` varchar(50) DEFAULT NULL,
  `status` enum('aktif','tidak_aktif') DEFAULT 'aktif',
  `join_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id`, `customer_code`, `nama`, `email`, `telepon`, `alamat`, `kota`, `status`, `join_date`, `created_at`) VALUES
(1, 'CUST001', 'PT Maju Jaya', 'info@majujaya.com', '0215551234', 'Jl. Sudirman No. 123', 'Lamongan', 'aktif', '2024-01-15', '2026-06-21 07:42:23'),
(2, 'CUST002', 'CV Kreatif Abadi', 'contact@kreatif.com', '022-5556789', 'Jl. Merdeka No. 45', 'Bandung', 'aktif', '2024-02-20', '2026-06-21 07:42:23'),
(3, 'CUST003', 'UD Berkah Sentosa', 'berkah@sentosa.com', '031-5559012', 'Jl. Pemuda No. 67', 'Surabaya', 'tidak_aktif', '2024-03-10', '2026-06-21 07:42:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `interactions`
--

CREATE TABLE `interactions` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `interaction_type` enum('telepon','email','whatsapp','meeting') NOT NULL,
  `notes` text DEFAULT NULL,
  `interaction_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `interactions`
--

INSERT INTO `interactions` (`id`, `customer_id`, `user_id`, `interaction_type`, `notes`, `interaction_date`, `created_at`) VALUES
(1, 1, 1, 'telepon', 'Diskusi tentang proyek baru', '2024-04-01 10:00:00', '2026-06-21 07:42:37'),
(2, 1, 2, 'email', 'Mengirim proposal kerjasama', '2024-04-03 14:30:00', '2026-06-21 07:42:37'),
(3, 2, 2, 'whatsapp', 'Konfirmasi jadwal meeting', '2024-04-05 09:15:00', '2026-06-21 07:42:37'),
(4, 3, 1, 'meeting', 'Meeting evaluasi kinerja', '2024-04-07 13:00:00', '2026-06-21 07:42:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') DEFAULT 'staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin Utama', 'admin@crm.com', 'admin123', 'admin', '2026-06-21 07:42:07'),
(2, 'Staff Marketing', 'staff@crm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff', '2026-06-21 07:42:07'),
(3, 'Ahmad Fikri', 'ahmad@gmail.com', '$2y$10$B.BD8aKInY7yAoSr1Er4rezpnIBPUY77Ycg5b9dUfgZrx.KZNnP3a', 'admin', '2026-06-21 07:45:24');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_code` (`customer_code`);

--
-- Indeks untuk tabel `interactions`
--
ALTER TABLE `interactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `interactions`
--
ALTER TABLE `interactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `interactions`
--
ALTER TABLE `interactions`
  ADD CONSTRAINT `interactions_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `interactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
