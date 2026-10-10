<?php
session_start();
require_once 'db.php';

// 1. Kiểm tra mã đơn hàng
if (!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit();
}

$order_id = $_GET['order_id'];

// 2. Lấy thông tin đơn hàng từ Database
$sql = "SELECT * FROM orders WHERE id = '$order_id'";
$result = $conn->query($sql);
$order = $result->fetch_assoc();

if (!$order) {
    echo "Đơn hàng không tồn tại!";
    exit();
}

// --- CẤU HÌNH TÀI KHOẢN NGÂN HÀNG ---
$MY_BANK_ID = "MB";           // Ngân hàng MB Bank
$MY_ACCOUNT_NO = "0345848749"; // Số tài khoản
$MY_ACCOUNT_NAME = "HUA CONG AN"; // Tên chủ tài khoản

// Tạo nội dung chuyển khoản
$transfer_content = "DH" . $order_id;

// XỬ LÝ DỮ LIỆU AN TOÀN CHO URL
// 1. Loại bỏ khoảng trắng thừa ở STK và Bank ID
$bank_id_clean = trim($MY_BANK_ID);
$account_no_clean = trim($MY_ACCOUNT_NO);

// 2. Ép kiểu số tiền về số nguyên (xóa .00 nếu có)
$amount = (int)$order['total_money'];

// 3. Mã hóa tên người nhận đúng chuẩn URL (thay dấu cách bằng %20)
$accountNameEncoded = rawurlencode($MY_ACCOUNT_NAME);

// 4. Tạo Link QR
$qr_url = "https://img.vietqr.io/image/{$bank_id_clean}-{$account_no_clean}-compact2.png?amount={$amount}&addInfo={$transfer_content}&accountName={$accountNameEncoded}";
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán đơn hàng #<?php echo $order_id; ?></title>
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .payment-card {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 420px;
            width: 90%;
        }

        .logo-bank {
            font-weight: bold;
            color: #120c6e;
            /* Màu xanh MB */
            font-size: 20px;
            margin-bottom: 10px;
            display: block;
        }

        .qr-img {
            width: 100%;
            max-width: 320px;
            border-radius: 12px;
            border: 2px solid #e1e1e1;
            margin: 15px 0;
        }

        .amount {
            color: #cd1818;
            font-size: 28px;
            font-weight: 800;
            margin: 10px 0;
        }

        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            text-align: left;
            margin-top: 20px;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dashed #ddd;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .label {
            color: #666;
        }

        .value {
            font-weight: bold;
            color: #000;
        }

        .btn-home {
            display: block;
            text-decoration: none;
            background: #120c6e;
            /* Màu MB */
            color: white;
            padding: 15px;
            border-radius: 8px;
            font-weight: bold;
            margin-top: 25px;
            transition: 0.3s;
        }

        .btn-home:hover {
            background: #0b074a;
        }
    </style>
</head>

<body>

    <div class="payment-card">
        <span class="logo-bank">MB BANK QR PAYMENT</span>
        <h3 style="margin: 0; color: #333;">Thanh toán đơn hàng</h3>
        <p style="color: #666; margin-top: 5px;">Mã đơn: <strong>#<?php echo $order_id; ?></strong></p>

        <img src="<?php echo $qr_url; ?>" alt="QR Code Thanh Toán" class="qr-img">

        <div class="amount"><?php echo number_format($amount, 0, ',', '.'); ?> đ</div>

        <div class="info-box">
            <div class="info-row">
                <span class="label">Chủ tài khoản:</span>
                <span class="value"><?php echo $MY_ACCOUNT_NAME; ?></span>
            </div>
            <div class="info-row">
                <span class="label">Số tài khoản:</span>
                <span class="value"><?php echo $MY_ACCOUNT_NO; ?></span>
            </div>
            <div class="info-row">
                <span class="label">Ngân hàng:</span>
                <span class="value"><?php echo $MY_BANK_ID; ?></span>
            </div>
            <div class="info-row">
                <span class="label">Nội dung CK:</span>
                <span class="value" style="color: #cd1818;"><?php echo $transfer_content; ?></span>
            </div>
        </div>

        <p style="font-size: 13px; color: #888; margin-top: 15px;">
            <i>Vui lòng giữ nguyên nội dung chuyển khoản để hệ thống tự động xác nhận.</i>
        </p>

        <a href="index.php" class="btn-home">HOÀN TẤT ĐẶT HÀNG</a>
    </div>

</body>

</html>