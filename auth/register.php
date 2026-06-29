<?php
require_once '../config/database.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard/index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = htmlspecialchars(trim($_POST['nama']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Validasi
    if (empty($nama) || empty($email) || empty($password) || empty($password_confirm)) {
        $error = 'Semua field wajib diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter!';
    } elseif ($password !== $password_confirm) {
        $error = 'Password dan konfirmasi password tidak cocok!';
    } else {
        try {
            // Cek email sudah terdaftar
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error = 'Email sudah terdaftar!';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, 'staff')");
                $stmt->execute([$nama, $email, $hashedPassword]);
                $success = 'Registrasi berhasil! Silahkan login.';
                // Redirect setelah 2 detik
                header("refresh:2;url=login.php");
            }
        } catch(PDOException $e) {
            $error = 'Terjadi kesalahan sistem!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CRM System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background: #F0FDF4; }
        .register-card { background: #FFFFFF; }
        .btn-primary { background: #22C55E; }
        .btn-primary:hover { background: #16A34A; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="register-card rounded-2xl shadow-xl p-8 max-w-md w-full border border-[#D1FAE5]">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-[#1F2937]">Daftar Akun</h1>
            <p class="text-gray-500 mt-2">Buat akun baru untuk mengakses CRM</p>
        </div>

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

        <form method="POST" action="" id="registerForm" class="space-y-4">
            <div>
                <label class="block text-[#1F2937] font-medium mb-2">Nama Lengkap</label>
                <div class="relative">
                    <i class="fas fa-user absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="nama" id="nama" required
                           class="w-full pl-10 pr-4 py-3 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]"
                           placeholder="Masukkan nama lengkap">
                </div>
            </div>

            <div>
                <label class="block text-[#1F2937] font-medium mb-2">Email</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="email" name="email" id="email" required
                           class="w-full pl-10 pr-4 py-3 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]"
                           placeholder="Masukkan email">
                </div>
                <span class="text-red-500 text-sm hidden" id="emailError">Email tidak valid</span>
            </div>

            <div>
                <label class="block text-[#1F2937] font-medium mb-2">Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="password" name="password" id="password" required
                           class="w-full pl-10 pr-12 py-3 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]"
                           placeholder="Minimal 8 karakter">
                    <button type="button" onclick="togglePassword('password', 'toggleIcon1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye-slash" id="toggleIcon1"></i>
                    </button>
                </div>
                <span class="text-red-500 text-sm hidden" id="passwordError">Password minimal 8 karakter</span>
            </div>

            <div>
                <label class="block text-[#1F2937] font-medium mb-2">Konfirmasi Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="password" name="password_confirm" id="password_confirm" required
                           class="w-full pl-10 pr-12 py-3 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]"
                           placeholder="Ulangi password">
                    <button type="button" onclick="togglePassword('password_confirm', 'toggleIcon2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye-slash" id="toggleIcon2"></i>
                    </button>
                </div>
                <span class="text-red-500 text-sm hidden" id="confirmError">Password tidak cocok</span>
            </div>

            <button type="submit" class="btn-primary w-full text-white font-semibold py-3 rounded-lg transition duration-200">
                <i class="fas fa-user-plus mr-2"></i> Daftar
            </button>
        </form>

        <p class="text-center mt-6 text-gray-600">
            Sudah punya akun? <a href="login.php" class="text-[#22C55E] font-medium hover:underline">Masuk Sekarang</a>
        </p>
    </div>

    <script>
        function togglePassword(fieldId, iconId) {
            const password = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const confirm = document.getElementById('password_confirm');
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');
            const confirmError = document.getElementById('confirmError');
            
            let isValid = true;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email.value)) {
                emailError.classList.remove('hidden');
                email.classList.add('border-red-500');
                isValid = false;
            } else {
                emailError.classList.add('hidden');
                email.classList.remove('border-red-500');
            }
            
            if (password.value.length < 8) {
                passwordError.classList.remove('hidden');
                password.classList.add('border-red-500');
                isValid = false;
            } else {
                passwordError.classList.add('hidden');
                password.classList.remove('border-red-500');
            }
            
            if (password.value !== confirm.value) {
                confirmError.classList.remove('hidden');
                confirm.classList.add('border-red-500');
                isValid = false;
            } else {
                confirmError.classList.add('hidden');
                confirm.classList.remove('border-red-500');
            }
            
            if (!isValid) e.preventDefault();
        });
    </script>
</body>
</html>