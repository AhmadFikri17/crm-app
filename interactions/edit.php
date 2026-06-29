<?php
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'staff')) {
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

try {
    // Get interaction data
    $stmt = $pdo->prepare("SELECT * FROM interactions WHERE id = ?");
    $stmt->execute([$id]);
    $interaction = $stmt->fetch();
    if (!$interaction) {
        header('Location: index.php');
        exit();
    }
    
    // Get customers
    $customers = $pdo->query("SELECT id, nama FROM customers ORDER BY nama")->fetchAll();
    
} catch(PDOException $e) {
    $error = 'Gagal mengambil data interaksi';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Token keamanan tidak valid!';
    } else {
        $customer_id = (int)$_POST['customer_id'];
        $interaction_type = $_POST['interaction_type'] ?? '';
        $notes = htmlspecialchars(trim($_POST['notes']));
        $interaction_date = $_POST['interaction_date'] ?: date('Y-m-d H:i:s');
        
        if (empty($customer_id) || empty($interaction_type)) {
            $error = 'Pelanggan dan jenis interaksi wajib dipilih!';
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE interactions SET customer_id = ?, interaction_type = ?, notes = ?, interaction_date = ? WHERE id = ?");
                $stmt->execute([$customer_id, $interaction_type, $notes, $interaction_date, $id]);
                $success = 'Interaksi berhasil diperbarui!';
                // Refresh data
                $stmt = $pdo->prepare("SELECT * FROM interactions WHERE id = ?");
                $stmt->execute([$id]);
                $interaction = $stmt->fetch();
            } catch(PDOException $e) {
                $error = 'Gagal memperbarui interaksi';
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
    <title>Edit Interaksi - CRM System</title>
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
                        <h1 class="text-xl font-semibold text-[#1F2937]">Edit Interaksi</h1>
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

                        <form method="POST" action="" id="interactionForm" class="space-y-5">
                            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                            
                            <div>
                                <label class="block text-[#1F2937] font-medium mb-2">Pelanggan <span class="text-red-500">*</span></label>
                                <select name="customer_id" id="customer_id" required
                                        class="w-full px-4 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                                    <option value="">Pilih Pelanggan</option>
                                    <?php foreach ($customers as $c): ?>
                                    <option value="<?php echo $c['id']; ?>" <?php echo ($interaction['customer_id'] == $c['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($c['nama']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[#1F2937] font-medium mb-2">Tanggal Interaksi</label>
                                <input type="datetime-local" name="interaction_date" 
                                       class="w-full px-4 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]"
                                       value="<?php echo date('Y-m-d\TH:i', strtotime($interaction['interaction_date'])); ?>">
                            </div>

                            <div>
                                <label class="block text-[#1F2937] font-medium mb-2">Jenis Interaksi <span class="text-red-500">*</span></label>
                                <select name="interaction_type" id="interaction_type" required
                                        class="w-full px-4 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]">
                                    <option value="">Pilih Jenis</option>
                                    <option value="telepon" <?php echo $interaction['interaction_type'] == 'telepon' ? 'selected' : ''; ?>>📞 Telepon</option>
                                    <option value="email" <?php echo $interaction['interaction_type'] == 'email' ? 'selected' : ''; ?>>✉️ Email</option>
                                    <option value="whatsapp" <?php echo $interaction['interaction_type'] == 'whatsapp' ? 'selected' : ''; ?>>💬 WhatsApp</option>
                                    <option value="meeting" <?php echo $interaction['interaction_type'] == 'meeting' ? 'selected' : ''; ?>>🤝 Meeting</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[#1F2937] font-medium mb-2">Catatan Interaksi</label>
                                <textarea name="notes" rows="4"
                                          class="w-full px-4 py-2 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]"
                                          placeholder="Masukkan catatan interaksi"><?php echo htmlspecialchars($interaction['notes'] ?? ''); ?></textarea>
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
    </script>
</body>
</html>