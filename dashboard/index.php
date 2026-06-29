<?php
require_once '../config/database.php';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Get stats
try {
    // Total customers
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM customers");
    $totalCustomers = $stmt->fetch()['total'];
    
    // New customers this month
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM customers WHERE MONTH(join_date) = MONTH(CURRENT_DATE()) AND YEAR(join_date) = YEAR(CURRENT_DATE())");
    $newCustomers = $stmt->fetch()['total'];
    
    // Total interactions
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM interactions");
    $totalInteractions = $stmt->fetch()['total'];
    
    // Interactions today
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM interactions WHERE DATE(interaction_date) = CURRENT_DATE()");
    $interactionsToday = $stmt->fetch()['total'];
    
    // Chart data: Customers per month
    $stmt = $pdo->query("SELECT DATE_FORMAT(join_date, '%Y-%m') as month, COUNT(*) as total FROM customers WHERE join_date IS NOT NULL GROUP BY DATE_FORMAT(join_date, '%Y-%m') ORDER BY month DESC LIMIT 6");
    $customersChart = $stmt->fetchAll();
    
    // Chart data: Interactions per month
    $stmt = $pdo->query("SELECT DATE_FORMAT(interaction_date, '%Y-%m') as month, COUNT(*) as total FROM interactions WHERE interaction_date IS NOT NULL GROUP BY DATE_FORMAT(interaction_date, '%Y-%m') ORDER BY month DESC LIMIT 6");
    $interactionsChart = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $error = 'Gagal mengambil data statistik';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CRM System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar { background: #FFFFFF; border-right: 1px solid #D1FAE5; }
        .sidebar-link { color: #1F2937; transition: all 0.3s; }
        .sidebar-link:hover, .sidebar-link.active { background: #F0FDF4; color: #22C55E; }
        .sidebar-link.active { border-right: 4px solid #22C55E; }
        .card-stat { background: #FFFFFF; border: 1px solid #D1FAE5; }
        .btn-primary { background: #22C55E; }
        .btn-primary:hover { background: #16A34A; }
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
                <a href="../dashboard/index.php" class="sidebar-link active flex items-center space-x-3 px-4 py-3 rounded-lg">
                    <i class="fas fa-chart-pie w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="../customers/index.php" class="sidebar-link flex items-center space-x-3 px-4 py-3 rounded-lg">
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
                        <h1 class="text-xl font-semibold text-[#1F2937]">Dashboard</h1>
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
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="card-stat rounded-xl p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Total Pelanggan</p>
                                <p class="text-2xl font-bold text-[#1F2937]"><?php echo $totalCustomers; ?></p>
                            </div>
                            <div class="w-12 h-12 bg-[#F0FDF4] rounded-full flex items-center justify-center text-[#22C55E]">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-stat rounded-xl p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Pelanggan Baru (Bulan Ini)</p>
                                <p class="text-2xl font-bold text-[#1F2937]"><?php echo $newCustomers; ?></p>
                            </div>
                            <div class="w-12 h-12 bg-[#F0FDF4] rounded-full flex items-center justify-center text-[#22C55E]">
                                <i class="fas fa-user-plus text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-stat rounded-xl p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Total Interaksi</p>
                                <p class="text-2xl font-bold text-[#1F2937]"><?php echo $totalInteractions; ?></p>
                            </div>
                            <div class="w-12 h-12 bg-[#F0FDF4] rounded-full flex items-center justify-center text-[#22C55E]">
                                <i class="fas fa-comments text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-stat rounded-xl p-6 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Interaksi Hari Ini</p>
                                <p class="text-2xl font-bold text-[#1F2937]"><?php echo $interactionsToday; ?></p>
                            </div>
                            <div class="w-12 h-12 bg-[#F0FDF4] rounded-full flex items-center justify-center text-[#22C55E]">
                                <i class="fas fa-calendar-day text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="card-stat rounded-xl p-6 shadow-sm">
                        <h3 class="font-semibold text-[#1F2937] mb-4">Pelanggan Per Bulan</h3>
                        <canvas id="customersChart" height="250"></canvas>
                    </div>
                    <div class="card-stat rounded-xl p-6 shadow-sm">
                        <h3 class="font-semibold text-[#1F2937] mb-4">Interaksi Per Bulan</h3>
                        <canvas id="interactionsChart" height="250"></canvas>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card-stat rounded-xl p-6 shadow-sm mt-6">
                    <h3 class="font-semibold text-[#1F2937] mb-4">Aktivitas Terbaru</h3>
                    <div class="space-y-3">
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT i.*, c.nama as customer_name, u.nama as staff_name 
                                                  FROM interactions i 
                                                  JOIN customers c ON i.customer_id = c.id 
                                                  JOIN users u ON i.user_id = u.id 
                                                  ORDER BY i.interaction_date DESC LIMIT 5");
                            $recentActivities = $stmt->fetchAll();
                            
                            if (count($recentActivities) > 0) {
                                foreach ($recentActivities as $activity) {
                                    $icons = [
                                        'telepon' => 'fa-phone',
                                        'email' => 'fa-envelope',
                                        'whatsapp' => 'fa-whatsapp',
                                        'meeting' => 'fa-handshake'
                                    ];
                                    $icon = isset($icons[$activity['interaction_type']]) ? $icons[$activity['interaction_type']] : 'fa-comment';
                        ?>
                        <div class="flex items-center justify-between py-2 border-b border-[#D1FAE5] last:border-0">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-[#F0FDF4] rounded-full flex items-center justify-center text-[#22C55E]">
                                    <i class="fas <?php echo $icon; ?>"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-[#1F2937]">
                                        <?php echo htmlspecialchars($activity['customer_name']); ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        <?php echo htmlspecialchars($activity['interaction_type']); ?> • 
                                        <?php echo htmlspecialchars($activity['staff_name']); ?>
                                    </p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-500">
                                <?php echo date('d M Y H:i', strtotime($activity['interaction_date'])); ?>
                            </span>
                        </div>
                        <?php 
                                }
                            } else {
                                echo '<p class="text-gray-500 text-sm">Belum ada aktivitas</p>';
                            }
                        } catch(PDOException $e) {
                            echo '<p class="text-gray-500 text-sm">Gagal memuat aktivitas</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sidebar toggle untuk mobile
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('hidden');
        }

        // Charts
        <?php
        $months = array_column(array_reverse($customersChart), 'month');
        $values = array_column(array_reverse($customersChart), 'total');
        $monthsJson = json_encode($months);
        $valuesJson = json_encode($values);
        
        $monthsI = array_column(array_reverse($interactionsChart), 'month');
        $valuesI = array_column(array_reverse($interactionsChart), 'total');
        $monthsIJson = json_encode($monthsI);
        $valuesIJson = json_encode($valuesI);
        ?>

        // Customer Chart
        const ctx1 = document.getElementById('customersChart').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: <?php echo $monthsJson; ?>,
                datasets: [{
                    label: 'Pelanggan Baru',
                    data: <?php echo $valuesJson; ?>,
                    backgroundColor: '#86EFAC',
                    borderColor: '#22C55E',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Interaction Chart
        const ctx2 = document.getElementById('interactionsChart').getContext('2d');
        new Chart(ctx2, {
            type: 'line',
            data: {
                labels: <?php echo $monthsIJson; ?>,
                datasets: [{
                    label: 'Interaksi',
                    data: <?php echo $valuesIJson; ?>,
                    borderColor: '#22C55E',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>
</html>