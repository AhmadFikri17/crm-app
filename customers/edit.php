<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Cek role
if ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'staff') {
    header('Location: index.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

// Get customer data
try {
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE id = ?");
    $stmt->execute([$id]);
    $customer = $stmt->fetch();
    if (!$customer) {
        header('Location: index.php');
        exit();
    }
} catch(PDOException $e) {
    $error = 'Gagal mengambil data pelanggan';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Token keamanan tidak valid!';
    } else {
        $nama = htmlspecialchars(trim($_POST['nama']));
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $telepon = preg_replace('/[^0-9]/', '', $_POST['telepon']);
        $alamat = htmlspecialchars(trim($_POST['alamat']));
        $kota = htmlspecialchars(trim($_POST['kota']));
        $status = $_POST['status'] ?? 'aktif';
        $join_date = $_POST['join_date'];
        
        if (empty($nama) || empty($join_date)) {
            $error = 'Nama dan tanggal bergabung wajib diisi!';
        } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Format email tidak valid!';
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE customers SET nama = ?, email = ?, telepon = ?, alamat = ?, kota = ?, status = ?, join_date = ? WHERE id = ?");
                $stmt->execute([$nama, $email, $telepon, $alamat, $kota, $status, $join_date, $id]);
                $success = 'Data pelanggan berhasil diperbarui!';
                // Refresh data
                $stmt = $pdo->prepare("SELECT * FROM customers WHERE id = ?");
                $stmt->execute([$id]);
                $customer = $stmt->fetch();
            } catch(PDOException $e) {
                $error = 'Gagal memperbarui pelanggan';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelanggan - CRM System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .sidebar { background: #FFFFFF; border-right: 1px solid #D1FAE5; }
        .sidebar-link { color: #1F2937; transition: all 0.3s; }
        .sidebar-link:hover, .sidebar-link.active { background: #F0FDF4; color: #22C55E; }
        .sidebar-link.active { border-right: 4px solid #22C55E; }
        .btn-primary { background: #22C55E; }
        .btn-primary:hover { background: #16A34A; }
        .btn-secondary { background: #9CA3AF; }
        .btn-secondary:hover { background: #6B7280; }
    </style>
</head>
<body class="bg-[#F0FDF4]">
    <div class="flex h-screen">
        <aside class="sidebar w-64 flex-shrink-0 hidden md:block overflow-y-auto">
            <div class="p-6 border-b border-[#D1FAE5]">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-[#86EFAC] rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-[#22C55E] text-lg"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-[#1F2937]">CRM System</h2>
                        <p class="text-xs text-gray-500">v1.0</p>
                    </div>
                </div>
            </div>
            <nav class="p-4 space-y-1">
                <a href="../dashboard/index.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-chart-pie w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="index.php" class="sidebar-link active flex items-center space-x-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-users w-5"></i>
                    <span>Pelanggan</span>
                </a>
                <a href="../interactions/index.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-comments w-5"></i>
                    <span>Interaksi</span>
                </a>
                <div class="pt-4 mt-4 border-t border-[#D1FAE5]">
                    <a href="../auth/logout.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg text-red-500 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </nav>
        </aside>

        <div class="flex-1 overflow-y-auto">
            <header class="bg-white border-b border-[#D1FAE5] px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button class="md:hidden text-[#1F2937]" onclick="toggleSidebar()">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-[#1F2937]">Edit Pelanggan</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-3">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-medium text-[#1F2937]"><?php echo htmlspecialchars($_SESSION['user_nama']); ?></p>
                                <p class="text-xs text-gray-500"><?php echo htmlspecialchars($_SESSION['user_role']); ?></p>
                            </div>
                            <div class="w-10 h-10 bg-[#86EFAC] rounded-full flex items-center justify-center text-[#22C55E] font-bold">
                                <?php echo strtoupper(substr($_SESSION['user_nama'], 0, 2)); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6">
                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-xl shadow-sm border border-[#D1FAE5] p-6">
                        <?php if ($error): ?>
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="" id="customerForm" class="space-y-5">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[#1F2937] font-medium mb-2">ID Pelanggan</label>
                                    <input type="text" value="<?php echo htmlspecialchars($customer['customer_code']); ?>" 
                                           class="w-full px-4 py-2 border border-[#D1FAE5] rounded-lg bg-gray-50 text-gray-500" disabled>
                                </div>
                                <div>
                                    <label class="block text-[#1F2937] font-medium mb-2">Tanggal Bergabung <span class="text-red-500">*</span></label>
                                    <input type="date" name="join_date" id="join_date" required
                                           class="w-full px-4 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]"
                                           value="<?php echo htmlspecialchars($customer['join_date']); ?>">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[#1F2937] font-medium mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="nama" id="nama" required
                                       class="w-full px-4 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]"
                                       placeholder="Masukkan nama lengkap" value="<?php echo htmlspecialchars($customer['nama']); ?>">
                            </div>

                            <div>
                                <label class="block text-[#1F2937] font-medium mb-2">Email</label>
                                <input type="email" name="email" id="email"
                                       class="w-full px-4 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]"
                                       placeholder="Masukkan email" value="<?php echo htmlspecialchars($customer['email']); ?>">
                                <span class="text-red-500 text-sm hidden" id="emailError">Format email tidak valid</span>
                            </div>

                            <div>
                                <label class="block text-[#1F2937] font-medium mb-2">Nomor Telepon</label>
                                <input type="tel" name="telepon" id="telepon"
                                       class="w-full px-4 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]"
                                       placeholder="Contoh: 08123456789" value="<?php echo htmlspecialchars($customer['telepon']); ?>">
                                <span class="text-red-500 text-sm hidden" id="phoneError">Hanya angka yang diperbolehkan</span>
                            </div>

                            <div>
                                <label class="block text-[#1F2937] font-medium mb-2">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="3"
                                          class="w-full px-4 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]"
                                          placeholder="Masukkan alamat lengkap"><?php echo htmlspecialchars($customer['alamat']); ?></textarea>
                            </div>

                            <div>
                                <label class="block text-[#1F2937] font-medium mb-2">Kota</label>
                                <input type="text" name="kota" id="kota"
                                       class="w-full px-4 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]"
                                       placeholder="Masukkan kota" value="<?php echo htmlspecialchars($customer['kota']); ?>">
                            </div>

                            <div>
                                <label class="block text-[#1F2937] font-medium mb-2">Status Pelanggan</label>
                                <select name="status" class="w-full px-4 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                                    <option value="aktif" <?php echo $customer['status'] == 'aktif' ? 'selected' : ''; ?>>Aktif</option>
                                    <option value="tidak_aktif" <?php echo $customer['status'] == 'tidak_aktif' ? 'selected' : ''; ?>>Tidak Aktif</option>
                                </select>
                            </div>

                            <div class="flex space-x-3 pt-4">
                                <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg transition flex-1">
                                    <i class="fas fa-save mr-2"></i> Update
                                </button>
                                <a href="index.php" class="btn-secondary text-white px-6 py-2 rounded-lg transition text-center flex-1">
                                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('hidden');
        }

        document.getElementById('customerForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            const telepon = document.getElementById('telepon');
            const emailError = document.getElementById('emailError');
            const phoneError = document.getElementById('phoneError');
            let isValid = true;
            
            if (email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                emailError.classList.remove('hidden');
                email.classList.add('border-red-500');
                isValid = false;
            } else {
                emailError.classList.add('hidden');
                email.classList.remove('border-red-500');
            }
            
            if (telepon.value && !/^[0-9]+$/.test(telepon.value)) {
                phoneError.classList.remove('hidden');
                telepon.classList.add('border-red-500');
                isValid = false;
            } else {
                phoneError.classList.add('hidden');
                telepon.classList.remove('border-red-500');
            }
            
            if (!isValid) e.preventDefault();
        });

        document.getElementById('telepon').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>