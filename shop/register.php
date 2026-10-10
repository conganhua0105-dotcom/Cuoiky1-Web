<?php
session_start();
require_once 'db.php';

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = $_POST['full_name'];

    // 1. Kiểm tra xác nhận mật khẩu
    if ($password != $confirm_password) {
        $error = "Mật khẩu xác nhận không khớp!";
    } else {
        // 2. Kiểm tra tên đăng nhập đã tồn tại chưa
        $check_sql = "SELECT * FROM users WHERE username = '$username'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            $error = "Tên đăng nhập đã tồn tại, vui lòng chọn tên khác!";
        } else {
            // 3. Thêm vào Database (Mặc định role là 'user')
            // Lưu ý: Thực tế nên dùng password_hash() để mã hóa, ở đây làm cơ bản dùng text thường
            $sql = "INSERT INTO users (username, password, full_name, role) VALUES ('$username', '$password', '$full_name', 'user')";

            if ($conn->query($sql) === TRUE) {
                $success = "Đăng ký thành công! Đang chuyển hướng đăng nhập...";
                echo "<script>setTimeout(function(){ window.location.href = 'login.php'; }, 2000);</script>";
            } else {
                $error = "Lỗi hệ thống: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: sans-serif;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
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
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #cd1818;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-login:hover {
            background: #a00;
        }

        .error-msg {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            font-size: 13px;
            background: #fff5f5;
            padding: 10px;
            border: 1px solid #ffcccc;
            border-radius: 4px;
        }

        .success-msg {
            color: green;
            text-align: center;
            margin-bottom: 15px;
            font-size: 13px;
            background: #f0fff4;
            padding: 10px;
            border: 1px solid #c6f6d5;
            border-radius: 4px;
        }

        .link-register {
            display: block;
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .link-register a {
            color: #cd1818;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h2>ĐĂNG KÝ THÀNH VIÊN</h2>

        <?php if ($error): ?><div class="error-msg"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="success-msg"><?php echo $success; ?></div><?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Họ và tên:</label>
                <input type="text" name="full_name" required placeholder="Ví dụ: Nguyễn Văn A">
            </div>
            <div class="form-group">
                <label>Tên đăng nhập:</label>
                <input type="text" name="username" required placeholder="Viết liền không dấu">
            </div>
            <div class="form-group">
                <label>Mật khẩu:</label>
                <input type="password" name="password" required placeholder="Nhập mật khẩu">
            </div>
            <div class="form-group">
                <label>Nhập lại mật khẩu:</label>
                <input type="password" name="confirm_password" required placeholder="Nhập lại mật khẩu">
            </div>
            <button type="submit" name="register" class="btn-login">Đăng Ký</button>
        </form>

        <div class="link-register">
            Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a>
        </div>
        <div class="link-register" style="margin-top: 10px;">
            <a href="index.php" style="color: #555;">← Về trang chủ</a>
        </div>
    </div>
</body>

</html>