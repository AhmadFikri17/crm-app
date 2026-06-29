-- Database: crm_db

CREATE DATABASE IF NOT EXISTS crm_db;
USE crm_db;

-- Tabel Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff') DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Customers
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_code VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    telepon VARCHAR(20),
    alamat TEXT,
    kota VARCHAR(50),
    status ENUM('aktif', 'tidak_aktif') DEFAULT 'aktif',
    join_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Interactions
CREATE TABLE interactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    user_id INT NOT NULL,
    interaction_type ENUM('telepon', 'email', 'whatsapp', 'meeting') NOT NULL,
    notes TEXT,
    interaction_date DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert data dummy untuk user
INSERT INTO users (nama, email, password, role) VALUES
('Admin Utama', 'admin@crm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Staff Marketing', 'staff@crm.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff');

-- Insert data dummy untuk customers
INSERT INTO customers (customer_code, nama, email, telepon, alamat, kota, status, join_date) VALUES
('CUST001', 'PT Maju Jaya', 'info@majujaya.com', '021-5551234', 'Jl. Sudirman No. 123', 'Jakarta', 'aktif', '2024-01-15'),
('CUST002', 'CV Kreatif Abadi', 'contact@kreatif.com', '022-5556789', 'Jl. Merdeka No. 45', 'Bandung', 'aktif', '2024-02-20'),
('CUST003', 'UD Berkah Sentosa', 'berkah@sentosa.com', '031-5559012', 'Jl. Pemuda No. 67', 'Surabaya', 'tidak_aktif', '2024-03-10');

-- Insert data dummy untuk interactions
INSERT INTO interactions (customer_id, user_id, interaction_type, notes, interaction_date) VALUES
(1, 1, 'telepon', 'Diskusi tentang proyek baru', '2024-04-01 10:00:00'),
(1, 2, 'email', 'Mengirim proposal kerjasama', '2024-04-03 14:30:00'),
(2, 2, 'whatsapp', 'Konfirmasi jadwal meeting', '2024-04-05 09:15:00'),
(3, 1, 'meeting', 'Meeting evaluasi kinerja', '2024-04-07 13:00:00');