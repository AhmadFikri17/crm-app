<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit();
}

try {
    // Get customer detail
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE id = ?");
    $stmt->execute([$id]);
    $customer = $stmt->fetch();
    if (!$customer) {
        header('Location: index.php');
        exit();
    }
    
    // Get interactions
    $stmt = $pdo->prepare("SELECT i.*, u.nama as staff_name FROM interactions i 
                           JOIN users u ON i.user_id = u.id 
                           WHERE i.customer_id = ? 
                           ORDER BY i.interaction_date DESC");
    $stmt->execute([$id]);
    $interactions = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $error = 'Gagal mengambil data pelanggan';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelanggan - CRM System</title>
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
        .status-aktif { background: #D1FAE5; color: #16A34A; }
        .status-tidak_aktif { background: #FEE2E2; color: #DC2626; }
        .interaction-icon { background: #F0FDF4; color: #22C55E; }
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
                        <h1 class="text-xl font-semibold text-[#1F2937]">Detail Pelanggan</h1>
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
                <div class="max-w-4xl mx-auto">
                    <?php if (isset($error)): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Customer Info Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-[#D1FAE5] p-6 mb-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-2xl font-bold text-[#1F2937]"><?php echo htmlspecialchars($customer['nama']); ?></h2>
                                <p class="text-sm text-gray-500">ID: <?php echo htmlspecialchars($customer['customer_code']); ?></p>
                            </div>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full status-<?php echo $customer['status']; ?>">
                                <?php echo $customer['status'] == 'aktif' ? 'Aktif' : 'Tidak Aktif'; ?>
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="text-[#1F2937]"><?php echo htmlspecialchars($customer['email'] ?: '-'); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Telepon</p>
                                <p class="text-[#1F2937]"><?php echo htmlspecialchars($customer['telepon'] ?: '-'); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Alamat</p>
                                <p class="text-[#1F2937]"><?php echo htmlspecialchars($customer['alamat'] ?: '-'); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Kota</p>
                                <p class="text-[#1F2937]"><?php echo htmlspecialchars($customer['kota'] ?: '-'); ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Bergabung</p>
                                <p class="text-[#1F2937]"><?php echo date('d F Y', strtotime($customer['join_date'])); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Interactions -->
                    <div class="bg-white rounded-xl shadow-sm border border-[#D1FAE5] p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-[#1F2937]">
                                <i class="fas fa-comments text-[#22C55E] mr-2"></i>
                                Riwayat Interaksi
                            </h3>
                            <a href="../interactions/create.php?customer_id=<?php echo $customer['id']; ?>" 
                               class="btn-primary text-white px-4 py-2 rounded-lg text-sm flex items-center space-x-2 transition">
                                <i class="fas fa-plus"></i>
                                <span>Tambah Interaksi</span>
                            </a>
                        </div>

                        <?php if (count($interactions) > 0): ?>
                        <div class="space-y-3">
                            <?php foreach ($interactions as $interaction): 
                                $icons = [
                                    'telepon' => 'fa-phone',
                                    'email' => 'fa-envelope',
                                    'whatsapp' => 'fa-whatsapp',
                                    'meeting' => 'fa-handshake'
                                ];
                                $icon = isset($icons[$interaction['interaction_type']]) ? $icons[$interaction['interaction_type']] : 'fa-comment';
                                $colors = [
                                    'telepon' => 'text-blue-500 bg-blue-50',
                                    'email' => 'text-purple-500 bg-purple-50',
                                    'whatsapp' => 'text-green-500 bg-green-50',
                                    'meeting' => 'text-orange-500 bg-orange-50'
                                ];
                                $color = isset($colors[$interaction['interaction_type']]) ? $colors[$interaction['interaction_type']] : 'text-gray-500 bg-gray-50';
                            ?>
                            <div class="flex items-start space-x-3 p-3 bg-[#F0FDF4] rounded-lg">
                                <div class="w-10 h-10 rounded-full <?php echo $color; ?> flex items-center justify-center flex-shrink-0">
                                    <i class="fas <?php echo $icon; ?>"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <span class="font-medium text-[#1F2937]"><?php echo ucfirst($interaction['interaction_type']); ?></span>
                                            <span class="text-xs text-gray-500 ml-2">oleh <?php echo htmlspecialchars($interaction['staff_name']); ?></span>
                                        </div>
                                        <span class="text-xs text-gray-500">
                                            <?php echo date('d M Y H:i', strtotime($interaction['interaction_date'])); ?>
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($interaction['notes'] ?: 'Tidak ada catatan'); ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p class="text-center text-gray-500 py-8">
                            <i class="fas fa-inbox text-4xl block mb-3"></i>
                            Belum ada interaksi untuk pelanggan ini
                        </p>
                        <?php endif; ?>
                    </div>

                    <div class="mt-6">
                        <a href="index.php" class="btn-secondary text-white px-6 py-2 rounded-lg transition inline-flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('hidden');
        }
    </script>
</body>
</html>