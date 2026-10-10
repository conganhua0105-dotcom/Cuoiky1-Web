<?php
session_start();
require_once 'db.php';

// --- CẤU HÌNH CƠ BẢN ---
mysqli_set_charset($conn, 'utf8mb4'); // Sửa lỗi font tiếng Việt
date_default_timezone_set('Asia/Ho_Chi_Minh');

// 1. KIỂM TRA ĐĂNG NHẬP
if (!isset($_SESSION['user'])) {
    echo "<script>alert('Bạn cần đăng nhập để thanh toán!'); window.location.href='login.php';</script>";
    exit();
}

// 2. KIỂM TRA GIỎ HÀNG
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];

// 3. TÍNH TỔNG TIỀN
$total_money = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_money += $item['price'] * $item['qty'];
}

// 4. XỬ LÝ KHI BẤM NÚT "ĐẶT HÀNG"
if (isset($_POST['btn_order'])) {
    // Lấy dữ liệu và xử lý bảo mật (mysqli_real_escape_string)
    $user_id = $user['id'];
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $note = isset($_POST['note']) ? mysqli_real_escape_string($conn, $_POST['note']) : '';
    $order_date = date('Y-m-d H:i:s');

    // A. Lưu vào bảng ORDERS
    $sql_order = "INSERT INTO orders (user_id, full_name, phone, address, note, total_money, created_at) 
                  VALUES ('$user_id', '$full_name', '$phone', '$address', '$note', '$total_money', '$order_date')";

    if ($conn->query($sql_order)) {
        $order_id = $conn->insert_id; // Lấy ID đơn hàng vừa tạo

        // B. Lưu chi tiết vào bảng ORDER_DETAILS
        foreach ($_SESSION['cart'] as $item) {
            $p_id = $item['id'];
            $p_name = mysqli_real_escape_string($conn, $item['name']); // Chống lỗi tên sản phẩm có ký tự lạ
            $p_price = $item['price'];
            $qty = $item['qty'];

            $sql_detail = "INSERT INTO order_details (order_id, product_id, product_name, price, quantity) 
                           VALUES ('$order_id', '$p_id', '$p_name', '$p_price', '$qty')";
            $conn->query($sql_detail);
        }

        // C. Xóa giỏ hàng
        unset($_SESSION['cart']);

        // D. CHUYỂN HƯỚNG SANG TRANG THANH TOÁN QR CODE
        header("Location: payment.php?order_id=" . $order_id);
        exit();
    } else {
        echo "<script>alert('Lỗi hệ thống: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán | Laptop Shop</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Layout chia cột */
        .checkout-wrapper {
            display: flex;
            gap: 20px;
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 15px;
            flex-wrap: wrap;
            /* Cho mobile */
        }

        .col-left {
            flex: 6;
            min-width: 300px;
        }

        .col-right {
            flex: 4;
            min-width: 300px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        h3.title {
            margin-top: 0;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
            margin-bottom: 20px;
            font-size: 18px;
            color: #333;
            text-transform: uppercase;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: #cd1818;
            outline: none;
        }

        /* Order Summary Styles */
        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed #eee;
            font-size: 14px;
        }

        .order-item b {
            color: #333;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
            color: #cd1818;
            padding-top: 15px;
            border-top: 2px solid #eee;
        }

        /* Payment Method Styles */
        .payment-option {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            background: #fdfdfd;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: 0.2s;
        }

        .payment-option:hover {
            border-color: #cd1818;
            background: #fff5f5;
        }

        .payment-option input {
            transform: scale(1.2);
        }

        .btn-confirm {
            width: 100%;
            background: #cd1818;
            color: white;
            border: none;
            padding: 16px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
            text-transform: uppercase;
            transition: 0.2s;
        }

        .btn-confirm:hover {
            background: #a51212;
        }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .checkout-wrapper {
                flex-direction: column;
            }

            .col-right {
                order: -1;
            }

            /* Đẩy đơn hàng lên trên khi xem mobile */
        }
    </style>
</head>

<body>

    <header style="background: #cd1818; padding: 15px 0;">
        <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">
            <a href="index.php" style="color:white; text-decoration:none; font-weight:bold; font-size: 24px;">LAPTOP SHOP</a>
            <span style="float: right; color: white; margin-top: 5px;">Thanh toán an toàn</span>
        </div>
    </header>

    <form method="POST" action="">
        <div class="checkout-wrapper">

            <div class="col-left">
                <div class="card">
                    <h3 class="title">Thông tin giao hàng</h3>

                    <div class="form-group">
                        <label>Họ và tên</label>
                        <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>" required placeholder="Ví dụ: 0912345678">
                    </div>

                    <div class="form-group">
                        <label>Địa chỉ nhận hàng</label>
                        <textarea name="address" rows="3" class="form-control" required placeholder="Số nhà, đường, phường, quận..."><?php echo htmlspecialchars($user['address']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>Ghi chú đơn hàng (Tùy chọn)</label>
                        <textarea name="note" rows="2" class="form-control" placeholder="Ví dụ: Giao hàng giờ hành chính, gọi trước khi giao..."></textarea>
                    </div>
                </div>
            </div>

            <div class="col-right">
                <div class="card">
                    <h3 class="title">Đơn hàng của bạn</h3>

                    <div class="order-list">
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <div class="order-item">
                                <div>
                                    <b><?php echo $item['qty']; ?>x</b> <?php echo htmlspecialchars($item['name']); ?>
                                </div>
                                <div><?php echo number_format($item['price'] * $item['qty'], 0, ',', '.'); ?>đ</div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="total-row">
                        <span>Tổng cộng:</span>
                        <span><?php echo number_format($total_money, 0, ',', '.'); ?> VNĐ</span>
                    </div>
                </div>

                <div class="card">
                    <h3 class="title" style="font-size: 16px; margin-bottom: 15px;">Phương thức thanh toán</h3>

                    <label class="payment-option">
                        <input type="radio" name="payment_method" value="cod" checked>
                        <div>
                            <strong>Thanh toán khi nhận hàng (COD)</strong><br>
                            <span style="font-size: 13px; color: #666;">Kiểm tra hàng rồi thanh toán tiền mặt</span>
                        </div>
                    </label>

                    <label class="payment-option" style="margin-top: 10px;">
                        <input type="radio" name="payment_method" value="qr">
                        <div>
                            <strong>Chuyển khoản QR Code</strong><br>
                            <span style="font-size: 13px; color: #666;">Quét mã VietQR (MB, VCB, Techcom...)</span>
                        </div>
                    </label>

                    <button type="submit" name="btn_order" class="btn-confirm">ĐẶT HÀNG NGAY</button>
                </div>
            </div>
        </div>
    </form>

</body>

</html>