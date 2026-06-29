<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Filter
$customer_filter = isset($_GET['customer_id']) ? (int)$_GET['customer_id'] : 0;
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query
$where = [];
$params = [];

if ($customer_filter > 0) {
    $where[] = "i.customer_id = ?";
    $params[] = $customer_filter;
}

if (!empty($type_filter)) {
    $where[] = "i.interaction_type = ?";
    $params[] = $type_filter;
}

if (!empty($date_from)) {
    $where[] = "DATE(i.interaction_date) >= ?";
    $params[] = $date_from;
}

if (!empty($date_to)) {
    $where[] = "DATE(i.interaction_date) <= ?";
    $params[] = $date_to;
}

if (!empty($search)) {
    $where[] = "(c.nama LIKE ? OR i.interaction_type LIKE ? OR i.notes LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

$whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

try {
    // Get total
    $countSql = "SELECT COUNT(*) as total FROM interactions i 
                 JOIN customers c ON i.customer_id = c.id 
                 $whereClause";
    $stmt = $pdo->prepare($countSql);
    $stmt->execute($params);
    $totalInteractions = $stmt->fetch()['total'];
    $totalPages = ceil($totalInteractions / $limit);
    
    // Get interactions
    $sql = "SELECT i.*, c.nama as customer_name, u.nama as staff_name 
            FROM interactions i 
            JOIN customers c ON i.customer_id = c.id 
            JOIN users u ON i.user_id = u.id 
            $whereClause 
            ORDER BY i.interaction_date DESC 
            LIMIT $limit OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $interactions = $stmt->fetchAll();
    
    // Get customers for filter
    $customers = $pdo->query("SELECT id, nama FROM customers ORDER BY nama")->fetchAll();
    
} catch(PDOException $e) {
    $error = 'Gagal mengambil data interaksi';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Interaksi - CRM System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .sidebar { background: #FFFFFF; border-right: 1px solid #D1FAE5; }
        .sidebar-link { color: #1F2937; transition: all 0.3s; }
        .sidebar-link:hover, .sidebar-link.active { background: #F0FDF4; color: #22C55E; }
        .sidebar-link.active { border-right: 4px solid #22C55E; }
        .btn-primary { background: #22C55E; }
        .btn-primary:hover { background: #16A34A; }
        .btn-danger { background: #EF4444; }
        .btn-danger:hover { background: #DC2626; }
        .btn-warning { background: #F59E0B; }
        .btn-warning:hover { background: #D97706; }
        .btn-secondary { background: #9CA3AF; }
        .btn-secondary:hover { background: #6B7280; }
        .badge-telepon { background: #DBEAFE; color: #2563EB; }
        .badge-email { background: #F3E8FF; color: #7C3AED; }
        .badge-whatsapp { background: #D1FAE5; color: #16A34A; }
        .badge-meeting { background: #FEF3C7; color: #D97706; }
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
                <a href="../customers/index.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-users w-5"></i>
                    <span>Pelanggan</span>
                </a>
                <a href="index.php" class="sidebar-link active flex items-center space-x-3 px-4 py-3 rounded-lg">
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
                        <h1 class="text-xl font-semibold text-[#1F2937]">Riwayat Interaksi</h1>
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
                <!-- Header Actions -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <a href="create.php" class="btn-primary text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition">
                        <i class="fas fa-plus"></i>
                        <span>Tambah Interaksi</span>
                    </a>
                    <div class="w-full sm:w-64 relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="searchInput" placeholder="Cari interaksi..." 
                               class="w-full pl-10 pr-4 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]"
                               value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </div>

                <!-- Filter -->
                <div class="bg-white rounded-xl shadow-sm border border-[#D1FAE5] p-4 mb-6">
                    <form method="GET" action="" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pelanggan</label>
                            <select name="customer_id" class="w-full px-3 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                                <option value="">Semua</option>
                                <?php foreach ($customers as $c): ?>
                                <option value="<?php echo $c['id']; ?>" <?php echo $customer_filter == $c['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($c['nama']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Interaksi</label>
                            <select name="type" class="w-full px-3 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                                <option value="">Semua</option>
                                <option value="telepon" <?php echo $type_filter == 'telepon' ? 'selected' : ''; ?>>Telepon</option>
                                <option value="email" <?php echo $type_filter == 'email' ? 'selected' : ''; ?>>Email</option>
                                <option value="whatsapp" <?php echo $type_filter == 'whatsapp' ? 'selected' : ''; ?>>WhatsApp</option>
                                <option value="meeting" <?php echo $type_filter == 'meeting' ? 'selected' : ''; ?>>Meeting</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                            <input type="date" name="date_from" value="<?php echo htmlspecialchars($date_from); ?>"
                                   class="w-full px-3 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                            <input type="date" name="date_to" value="<?php echo htmlspecialchars($date_to); ?>"
                                   class="w-full px-3 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                        </div>
                        <div class="sm:col-span-2 lg:col-span-4 flex space-x-2">
                            <button type="submit" class="btn-primary text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-filter mr-2"></i> Filter
                            </button>
                            <a href="index.php" class="btn-secondary text-white px-4 py-2 rounded-lg transition">
                                <i class="fas fa-undo mr-2"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-xl shadow-sm border border-[#D1FAE5] overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-[#F0FDF4] border-b border-[#D1FAE5]">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Catatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Staff</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="interactionTableBody">
                                <?php if (isset($interactions) && count($interactions) > 0): ?>
                                    <?php foreach ($interactions as $interaction): 
                                        $badgeClass = 'badge-' . $interaction['interaction_type'];
                                    ?>
                                    <tr class="border-b border-[#D1FAE5] hover:bg-[#F0FDF4] transition">
                                        <td class="px-6 py-4 text-sm font-medium text-[#1F2937]"><?php echo htmlspecialchars($interaction['customer_name']); ?></td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $badgeClass; ?>">
                                                <?php echo ucfirst($interaction['interaction_type']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 hidden md:table-cell">
                                            <?php echo date('d M Y H:i', strtotime($interaction['interaction_date'])); ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 hidden lg:table-cell max-w-xs truncate">
                                            <?php echo htmlspecialchars(substr($interaction['notes'] ?? '', 0, 50)); ?>
                                            <?php echo strlen($interaction['notes'] ?? '') > 50 ? '...' : ''; ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 hidden sm:table-cell">
                                            <?php echo htmlspecialchars($interaction['staff_name']); ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-2">
                                                <?php if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'staff'): ?>
                                                <a href="edit.php?id=<?php echo $interaction['id']; ?>" 
                                                   class="text-blue-500 hover:text-blue-600" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php endif; ?>
                                                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                                                <button onclick="confirmDelete(<?php echo $interaction['id']; ?>)" 
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
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                            <i class="fas fa-inbox text-4xl mb-3 block"></i>
                                            <?php echo !empty($search) ? 'Tidak ada interaksi yang ditemukan' : 'Belum ada data interaksi'; ?>
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
                            Menampilkan <?php echo $offset + 1; ?> - <?php echo min($offset + $limit, $totalInteractions); ?> dari <?php echo $totalInteractions; ?>
                        </p>
                        <div class="flex space-x-1">
                            <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?>&<?php echo http_build_query(['customer_id' => $customer_filter, 'type' => $type_filter, 'date_from' => $date_from, 'date_to' => $date_to, 'search' => $search]); ?>" 
                               class="px-3 py-1 border border-[#D1FAE5] rounded-lg hover:bg-[#F0FDF4] text-sm">←</a>
                            <?php endif; ?>
                            <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $totalPages); $i++): ?>
                            <a href="?page=<?php echo $i; ?>&<?php echo http_build_query(['customer_id' => $customer_filter, 'type' => $type_filter, 'date_from' => $date_from, 'date_to' => $date_to, 'search' => $search]); ?>" 
                               class="px-3 py-1 border border-[#D1FAE5] rounded-lg hover:bg-[#F0FDF4] text-sm <?php echo $i == $page ? 'bg-[#22C55E] text-white border-[#22C55E]' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                            <?php endfor; ?>
                            <?php if ($page < $totalPages): ?>
                            <a href="?page=<?php echo $page + 1; ?>&<?php echo http_build_query(['customer_id' => $customer_filter, 'type' => $type_filter, 'date_from' => $date_from, 'date_to' => $date_to, 'search' => $search]); ?>" 
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
            <p class="text-gray-500 text-sm mb-4">Apakah Anda yakin ingin menghapus interaksi ini?</p>
            <div class="flex space-x-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 border border-[#D1FAE5] rounded-lg hover:bg-gray-50 transition">Batal</button>
                <a href="#" id="deleteConfirmBtn" class="flex-1 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-center">Hapus</a>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('hidden');
        }

        // Search dengan AJAX
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const search = this.value;
            const params = new URLSearchParams(window.location.search);
            params.set('search', search);
            
            searchTimeout = setTimeout(function() {
                fetch(`search.php?${params.toString()}`)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('interactionTableBody').innerHTML = html;
                    })
                    .catch(err => console.error('Error:', err));
            }, 300);
        });

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