<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized');
}

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$customer_filter = isset($_GET['customer_id']) ? (int)$_GET['customer_id'] : 0;
$type_filter = isset($_GET['type']) ? $_GET['type'] : '';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';

$where = [];
$params = [];

if (!empty($search)) {
    $where[] = "(c.nama LIKE ? OR i.interaction_type LIKE ? OR i.notes LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

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

$whereClause = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

$sql = "SELECT i.*, c.nama as customer_name, u.nama as staff_name 
        FROM interactions i 
        JOIN customers c ON i.customer_id = c.id 
        JOIN users u ON i.user_id = u.id 
        $whereClause 
        ORDER BY i.interaction_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$interactions = $stmt->fetchAll();

if (count($interactions) > 0) {
    foreach ($interactions as $interaction) {
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
        <?php
    }
} else {
    ?>
    <tr>
        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
            <i class="fas fa-inbox text-4xl mb-3 block"></i>
            Tidak ada interaksi yang ditemukan
        </td>
    </tr>
    <?php
}
?>