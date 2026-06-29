<?php
require_once '../config/database.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ../dashboard/index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    if (empty($email) || empty($password)) {
        $error = 'Email dan password wajib diisi!';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_nama'] = $user['nama'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                
                if ($remember) {
                    setcookie('user_email', $email, time() + (86400 * 30), "/");
                    setcookie('user_password', $password, time() + (86400 * 30), "/");
                }
                
                header('Location: ../dashboard/index.php');
                exit();
            } else {
                $error = 'Email atau password salah!';
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
    <title>Login - CRM System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background: #F0FDF4; }
        .login-card { background: #FFFFFF; }
        .btn-primary { background: #22C55E; }
        .btn-primary:hover { background: #16A34A; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="login-card rounded-2xl shadow-xl p-8 max-w-md w-full border border-[#D1FAE5]">
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-[#86EFAC] rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-users text-3xl text-[#22C55E]"></i>
            </div>
            <h1 class="text-3xl font-bold text-[#1F2937]">CRM System</h1>
            <p class="text-gray-500 mt-2">Sistem Manajemen Pelanggan</p>
        </div>

        <?php if ($error): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="" id="loginForm" class="space-y-5">
            <div>
                <label class="block text-[#1F2937] font-medium mb-2">Email</label>
                <div class="relative">
                    <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="email" name="email" id="email" required
                           class="w-full pl-10 pr-4 py-3 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]"
                           placeholder="Masukkan email" value="<?php echo isset($_COOKIE['user_email']) ? htmlspecialchars($_COOKIE['user_email']) : ''; ?>">
                </div>
                <span class="text-red-500 text-sm hidden" id="emailError">Email tidak valid</span>
            </div>

            <div>
                <label class="block text-[#1F2937] font-medium mb-2">Password</label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="password" name="password" id="password" required
                           class="w-full pl-10 pr-12 py-3 border border-[#D1FAE5] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#22C55E]"
                           placeholder="Masukkan password">
                    <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <i class="fas fa-eye-slash" id="toggleIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full text-white font-semibold py-3 rounded-lg transition duration-200">
                <i class="fas fa-sign-in-alt mr-2"></i> Masuk
            </button>
        </form>

        <p class="text-center mt-6 text-gray-600">
            Belum punya akun? <a href="register.php" class="text-[#22C55E] font-medium hover:underline">Daftar Sekarang</a>
        </p>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
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

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            const emailError = document.getElementById('emailError');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email.value)) {
                e.preventDefault();
                emailError.classList.remove('hidden');
                email.classList.add('border-red-500');
            } else {
                emailError.classList.add('hidden');
                email.classList.remove('border-red-500');
            }
        });
    </script>
</body>
</html>