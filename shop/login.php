<?php
session_start();
require_once 'db.php';

$error = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra trong database (Lưu ý: Bài tập cơ bản nên so sánh password thường, thực tế nên dùng mã hóa)
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Lưu thông tin vào Session
        $_SESSION['user'] = $user;

        // Nếu là admin thì chuyển sang trang admin, ngược lại về trang chủ
        if ($user['role'] == 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "Sai tên đăng nhập hoặc mật khẩu!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập hệ thống</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        .login-box h2 {
            text-align: center;
            color: #cd1818;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .btn-login {
            width: 100%;
            padding: 10px;
            background: #cd1818;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-login:hover {
            background: #a00;
        }

        .error-msg {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .back-home {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h2>ĐĂNG NHẬP</h2>
        <?php if ($error): ?>
            <p class="error-msg"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Tài khoản:</label>
                <input type="text" name="username" required placeholder="Name">
            </div>
            <div class="form-group">
                <label>Mật khẩu:</label>
                <input type="password" name="password" required placeholder="Pass">
            </div>
            <button type="submit" name="login" class="btn-login">Đăng nhập</button>
            <div style="text-align: center; margin-top: 15px; font-size: 14px;">
                Chưa có tài khoản? <a href="register.php" style="color: #cd1818; font-weight: bold; text-decoration: none;">Đăng ký ngay</a>
            </div>
        </form>
        <a href="index.php" class="back-home">← Quay lại trang chủ</a>
    </div>
</body>

</html>