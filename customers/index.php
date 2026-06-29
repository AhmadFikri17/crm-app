<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchCondition = '';
$params = [];

if (!empty($search)) {
    $searchCondition = " WHERE nama LIKE ? OR email LIKE ? OR telepon LIKE ? ";
    $searchParam = "%$search%";
    $params = [$searchParam, $searchParam, $searchParam];
}

try {
    // Get total for pagination
    $countSql = "SELECT COUNT(*) as total FROM customers $searchCondition";
    $stmt = $pdo->prepare($countSql);
    $stmt->execute($params);
    $totalCustomers = $stmt->fetch()['total'];
    $totalPages = ceil($totalCustomers / $limit);
    
    // Get customers
    $sql = "SELECT * FROM customers $searchCondition ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $customers = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $error = 'Gagal mengambil data pelanggan';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pelanggan - CRM System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .sidebar { background: #FFFFFF; border-right: 1px solid #D1FAE5; }
        .sidebar-link { color: #1F2937; transition: all 0.3s; }
        .sidebar-link:hover, .sidebar-link.active { background: #F0FDF4; color: #22C55E; }
        .sidebar-link.active { border-right: 4px solid #22C55E; }
        .card-stat { background: #FFFFFF; border: 1px solid #D1FAE5; }
        .btn-primary { background: #22C55E; }
        .btn-primary:hover { background: #16A34A; }
        .btn-danger { background: #EF4444; }
        .btn-danger:hover { background: #DC2626; }
        .btn-warning { background: #F59E0B; }
        .btn-warning:hover { background: #D97706; }
        .status-aktif { background: #D1FAE5; color: #16A34A; }
        .status-tidak_aktif { background: #FEE2E2; color: #DC2626; }
    </style>
</head>
<body class="bg-[#F0FDF4]">
    <div class="flex h-screen">
        <!-- Sidebar -->
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

        <!-- Main Content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Navbar -->
            <header class="bg-white border-b border-[#D1FAE5] px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button class="md:hidden text-[#1F2937]" onclick="toggleSidebar()">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-xl font-semibold text-[#1F2937]">Data Pelanggan</h1>
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

            <!-- Content -->
            <div class="p-6">
                <!-- Header Actions -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div class="flex items-center space-x-2">
                        <a href="create.php" class="btn-primary text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Pelanggan</span>
                        </a>
                    </div>
                    <div class="w-full sm:w-64 relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Cari pelanggan..." 
                               class="w-full pl-10 pr-4 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]"
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-xl shadow-sm border border-[#D1FAE5] overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-[#F0FDF4] border-b border-[#D1FAE5]">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Telepon</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Bergabung</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="customerTableBody">
                                <?php if (isset($customers) && count($customers) > 0): ?>
                                    <?php foreach ($customers as $customer): ?>
                                    <tr class="border-b border-[#D1FAE5] hover:bg-[#F0FDF4] transition">
                                        <td class="px-6 py-4 text-sm text-gray-900 font-mono"><?php echo htmlspecialchars($customer['customer_code']); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium"><?php echo htmlspecialchars($customer['nama']); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-500 hidden md:table-cell"><?php echo htmlspecialchars($customer['email']); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-500 hidden lg:table-cell"><?php echo htmlspecialchars($customer['telepon']); ?></td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full status-<?php echo $customer['status']; ?>">
                                                <?php echo $customer['status'] == 'aktif' ? 'Aktif' : 'Tidak Aktif'; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 hidden sm:table-cell">
                                            <?php echo date('d M Y', strtotime($customer['join_date'])); ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <a href="detail.php?id=<?php echo $customer['id']; ?>" 
                                                   class="text-[#22C55E] hover:text-[#16A34A]" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'staff'): ?>
                                                <a href="edit.php?id=<?php echo $customer['id']; ?>" 
                                                   class="text-blue-500 hover:text-blue-600" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php endif; ?>
                                                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                                <button onclick="confirmDelete(<?php echo $customer['id']; ?>)" 
                                                        class="text-red-500 hover:text-red-600" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3 block"></i>
                                            <?php echo !empty($search) ? 'Tidak ada pelanggan yang ditemukan' : 'Belum ada data pelanggan'; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (isset($totalPages) && $totalPages > 1): ?>
                    <div class="px-6 py-4 border-t border-[#D1FAE5] flex items-center justify-between">
                        <p class="text-sm text-gray-500">
                            Menampilkan <?php echo $offset + 1; ?> - <?php echo min($offset + $limit, $totalCustomers); ?> dari <?php echo $totalCustomers; ?>
                        </p>
                        <div class="flex space-x-1">
                            <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" 
                               class="px-3 py-1 border border-[#D1FAE5] rounded-lg hover:bg-[#F0FDF4] text-sm">←</a>
                            <?php endif; ?>
                            <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $totalPages); $i++): ?>
                            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" 
                               class="px-3 py-1 border border-[#D1FAE5] rounded-lg hover:bg-[#F0FDF4] text-sm <?php echo $i == $page ? 'bg-[#22C55E] text-white border-[#22C55E]' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                            <?php endfor; ?>
                            <?php if ($page < $totalPages): ?>
                            <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" 
                               class="px-3 py-1 border border-[#D1FAE5] rounded-lg hover:bg-[#F0FDF4] text-sm">→</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full">
            <h3 class="text-lg font-semibold text-[#1F2937] mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-500 text-sm mb-4">Apakah Anda yakin ingin menghapus pelanggan ini? Data yang dihapus tidak dapat dikembalikan.</p>
            <div class="flex space-x-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 border border-[#D1FAE5] rounded-lg hover:bg-gray-50 transition">Batal</button>
                <a href="#" id="deleteConfirmBtn" class="flex-1 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-center">Hapus</a>
            </div>
        </div>
    </div>

    <script>
        // Sidebar toggle
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('hidden');
        }

        // Search dengan AJAX
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const search = this.value;
            searchTimeout = setTimeout(function() {
                fetch(`search.php?q=${encodeURIComponent(search)}`)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('customerTableBody').innerHTML = html;
                    })
                    .catch(err => console.error('Error:', err));
            }, 300);
        });

        // Delete confirmation
        function confirmDelete(id) {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteConfirmBtn').href = `delete.php?id=${id}`;
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }
    </script>
</body>
</html>