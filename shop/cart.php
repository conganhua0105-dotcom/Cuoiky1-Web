<?php
session_start();
// require_once 'db.php'; // Code này không cần gọi db cũng chạy được, rất an toàn

// Hàm xử lý đường dẫn ảnh
function get_product_image_url($url)
{
    if (strpos($url, 'uploads/') !== false) return $url;
    if (strpos($url, 'http') !== false) return $url;
    return 'https://via.placeholder.com/50x50';
}

// 1. XỬ LÝ THÊM VÀO GIỎ
if (isset($_POST['add_to_cart'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $img = get_product_image_url($_POST['img']);

    $item = [
        'id' => $id,
        'name' => $name,
        'price' => $price,
        'img' => $img,
        'qty' => 1
    ];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Kiểm tra trùng sản phẩm thì tăng số lượng
    $found = false;
    foreach ($_SESSION['cart'] as &$cart_item) {
        if ($cart_item['id'] == $id) {
            $cart_item['qty']++;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = $item;
    }

    header("Location: cart.php");
    exit();
}

// 2. XÓA 1 SẢN PHẨM
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    foreach ($_SESSION['cart'] as $k => $v) {
        if ($v['id'] == $id) {
            unset($_SESSION['cart'][$k]);
            break;
        }
    }
    // Sắp xếp lại mảng
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}

// 3. CẬP NHẬT SỐ LƯỢNG (Tăng/Giảm - Code thêm cho xịn)
if (isset($_GET['update']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $type = $_GET['update']; // 'inc' hoặc 'dec'

    foreach ($_SESSION['cart'] as $k => &$v) {
        if ($v['id'] == $id) {
            if ($type == 'inc') $v['qty']++;
            if ($type == 'dec') $v['qty']--;
            // Nếu giảm về 0 thì xóa luôn
            if ($v['qty'] <= 0) unset($_SESSION['cart'][$k]);
            break;
        }
    }
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}

// 4. XÓA HẾT
if (isset($_GET['empty'])) {
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .cart-wrapper {
            max-width: 900px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .cart-table th,
        .cart-table td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .btn-checkout {
            background: #cd1818;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }

        .btn-checkout:hover {
            background: #a00;
        }

        .btn-home {
            background: #333;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-qty {
            border: 1px solid #ddd;
            padding: 2px 8px;
            text-decoration: none;
            color: #333;
            margin: 0 5px;
        }
    </style>
</head>

<body>
    <header>
        <div class="container header-content">
            <div class="logo"><a href="index.php" style="color:white; text-decoration:none;">LAPTOP SHOP</a></div>
            <div class="header-icons">
                <a href="index.php" class="icon-item"><i class="fa-solid fa-house"></i> Trang chủ</a>
            </div>
        </div>
    </header>

    <div class="cart-wrapper">
        <h2 style="border-bottom: 1px solid #eee; padding-bottom: 10px;">Giỏ hàng của bạn</h2>

        <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
            <table class="cart-table">
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Xóa</th>
                </tr>
                <?php
                $total = 0;
                foreach ($_SESSION['cart'] as $item):
                    $subtotal = $item['price'] * $item['qty'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td style="display:flex; align-items:center; gap:10px;">
                            <img src="<?php echo $item['img']; ?>" width="50" style="object-fit:cover;">
                            <?php echo $item['name']; ?>
                        </td>
                        <td><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td>
                        <td>
                            <a href="cart.php?update=dec&id=<?php echo $item['id']; ?>" class="btn-qty">-</a>
                            <?php echo $item['qty']; ?>
                            <a href="cart.php?update=inc&id=<?php echo $item['id']; ?>" class="btn-qty">+</a>
                        </td>
                        <td style="color:#cd1818; font-weight:bold;"><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</td>
                        <td><a href="cart.php?del=<?php echo $item['id']; ?>" style="color:red;"><i class="fa-solid fa-trash"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div style="margin-top: 30px; display:flex; justify-content:space-between; align-items:center;">
                <a href="cart.php?empty=1" style="color:#666;" onclick="return confirm('Xóa hết?')">Xóa tất cả</a>
                <div style="text-align:right;">
                    <h3>Tổng: <span style="color:#cd1818;"><?php echo number_format($total, 0, ',', '.'); ?>đ</span></h3>
                    <br>
                    <a href="index.php" class="btn-home">Mua tiếp</a>

                    <a href="checkout.php" class="btn-checkout">Thanh toán <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>

        <?php else: ?>
            <div style="text-align:center; padding:50px;">
                <p>Giỏ hàng trống!</p>
                <br>
                <a href="index.php" class="btn-checkout">Quay lại mua hàng</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>