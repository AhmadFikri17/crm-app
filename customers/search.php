<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized');
}

$search = isset($_GET['q']) ? trim($_GET['q']) : '';

if (empty($search)) {
    // Load all customers
    $stmt = $pdo->query("SELECT * FROM customers ORDER BY created_at DESC");
} else {
    $searchParam = "%$search%";
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE nama LIKE ? OR email LIKE ? OR telepon LIKE ? ORDER BY created_at DESC");
    $stmt->execute([$searchParam, $searchParam, $searchParam]);
}

$customers = $stmt->fetchAll();

if (count($customers) > 0) {
    foreach ($customers as $customer) {
        $statusClass = $customer['status'] == 'aktif' ? 'status-aktif' : 'status-tidak_aktif';
        $statusText = $customer['status'] == 'aktif' ? 'Aktif' : 'Tidak Aktif';
        ?>
        <tr class="border-b border-[#D1FAE5] hover:bg-[#F0FDF4] transition">
            <td class="px-6 py-4 text-sm text-gray-900 font-mono"><?php echo htmlspecialchars($customer['customer_code']); ?></td>
            <td class="px-6 py-4 text-sm text-gray-900 font-medium"><?php echo htmlspecialchars($customer['nama']); ?></td>
            <td class="px-6 py-4 text-sm text-gray-500 hidden md:table-cell"><?php echo htmlspecialchars($customer['email']); ?></td>
            <td class="px-6 py-4 text-sm text-gray-500 hidden lg:table-cell"><?php echo htmlspecialchars($customer['telepon']); ?></td>
            <td class="px-6 py-4">
                <span class="px-2 py-1 text-xs font-semibold rounded-full <?php echo $statusClass; ?>">
                    <?php echo $statusText; ?>
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
        <?php
    }
} else {
    ?>
    <tr>
        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
            <i class="fas fa-inbox text-4xl mb-3 block"></i>
            Tidak ada pelanggan yang ditemukan
        </td>
    </tr>
    <?php
}
?>