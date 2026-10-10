<?php
session_start();
require_once 'db.php';

// 1. KIỂM TRA QUYỀN ADMIN
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Xác định đang ở trang nào (Mặc định là sản phẩm)
$page = isset($_GET['page']) ? $_GET['page'] : 'products';

// =================================================================
// XỬ LÝ LOGIC: SẢN PHẨM (Create - Update - Delete)
// =================================================================
if ($page == 'products') {
    // Xử lý XÓA
    if (isset($_GET['delete_id'])) {
        $id = $_GET['delete_id'];
        $conn->query("DELETE FROM products WHERE id = $id");
        header("Location: admin.php?page=products");
        exit();
    }

    // Xử lý THÊM MỚI
    if (isset($_POST['add_product'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $old_price = $_POST['old_price'];
        $brand_id = $_POST['brand_id'];
        $badge = $_POST['badge'];
        $cpu = $_POST['cpu'];
        $ram = $_POST['ram'];
        $desc = $_POST['description'];

        // Xử lý ảnh BLOB (Lưu trực tiếp vào DB)
        $image_data = "NULL";
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
            $data = file_get_contents($_FILES['image_file']['tmp_name']);
            $image_data = "'" . $conn->real_escape_string($data) . "'";
        }
        // Link ảnh dự phòng (nếu không up file)
        $image_url = !empty($_POST['image_url']) ? $_POST['image_url'] : 'https://via.placeholder.com/200x150';

        $sql = "INSERT INTO products (name, price, old_price, brand_id, image_url, image_data, badge, cpu, ram, description) 
                VALUES ('$name', '$price', '$old_price', '$brand_id', '$image_url', $image_data, '$badge', '$cpu', '$ram', '$desc')";
        $conn->query($sql);
        header("Location: admin.php?page=products");
        exit();
    }

    // Xử lý CẬP NHẬT
    if (isset($_POST['update_product'])) {
        $id = $_POST['edit_id'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $old_price = $_POST['old_price'];
        $brand_id = $_POST['brand_id'];
        $badge = $_POST['badge'];
        $cpu = $_POST['cpu'];
        $ram = $_POST['ram'];
        $desc = $_POST['description'];
        $image_url = $_POST['image_url'];

        // Cập nhật ảnh BLOB nếu có chọn file mới
        $sql_img = "";
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
            $data = file_get_contents($_FILES['image_file']['tmp_name']);
            $escaped_data = $conn->real_escape_string($data);
            $sql_img = ", image_data = '$escaped_data'";
        }

        $sql = "UPDATE products SET name='$name', price='$price', old_price='$old_price', brand_id='$brand_id', 
                image_url='$image_url', badge='$badge', cpu='$cpu', ram='$ram', description='$desc' $sql_img WHERE id=$id";
        $conn->query($sql);
        header("Location: admin.php?page=products");
        exit();
    }
}

// =================================================================
// XỬ LÝ LOGIC: ĐƠN HÀNG (Cập nhật trạng thái)
// =================================================================
if ($page == 'order_detail' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    $conn->query("UPDATE orders SET status = '$status' WHERE id = $order_id");
    echo "<script>alert('Cập nhật trạng thái thành công!'); window.location.href='admin.php?page=order_detail&id=$order_id';</script>";
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Admin Control Panel</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .admin-wrapper {
            display: flex;
        }

        /* Sidebar */
        .admin-sidebar {
            width: 250px;
            background: #343a40;
            color: white;
            min-height: 100vh;
            padding: 20px;
            position: fixed;
        }

        .admin-sidebar h3 {
            color: #cd1818;
            margin-bottom: 30px;
            text-align: center;
            border-bottom: 1px solid #555;
            padding-bottom: 10px;
        }

        .admin-menu a {
            display: block;
            color: #c2c7d0;
            padding: 12px;
            text-decoration: none;
            border-bottom: 1px solid #4b545c;
            transition: 0.2s;
        }

        .admin-menu a:hover,
        .admin-menu a.active {
            background: #cd1818;
            color: white;
        }

        /* Content */
        .admin-content {
            flex-grow: 1;
            padding: 20px;
            margin-left: 250px;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background: white;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Table Styles */
        .box {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            font-size: 14px;
        }

        th {
            background: #f8f9fa;
            font-weight: bold;
        }

        /* Status Badge */
        .badge-status {
            padding: 5px 10px;
            border-radius: 4px;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .st-Pending {
            background: #ffc107;
            color: #333;
        }

        /* Chờ xử lý */
        .st-Shipping {
            background: #17a2b8;
        }

        /* Đang giao */
        .st-Completed {
            background: #28a745;
        }

        /* Hoàn thành */
        .st-Cancelled {
            background: #dc3545;
        }

        /* Đã hủy */

        /* Form elements */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-submit {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 15px;
        }

        .btn-view {
            background: #17a2b8;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <div class="admin-wrapper">
        <div class="admin-sidebar">
            <h3>ADMIN PANEL</h3>
            <div class="admin-menu">
                <a href="admin.php?page=products" class="<?php echo $page == 'products' ? 'active' : ''; ?>">
                    <i class="fa-solid fa-box"></i> Quản lý Sản phẩm
                </a>
                <a href="admin.php?page=orders" class="<?php echo ($page == 'orders' || $page == 'order_detail') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-file-invoice-dollar"></i> Quản lý Đơn hàng
                </a>
                <a href="index.php" target="_blank"><i class="fa-solid fa-earth-americas"></i> Xem Website</a>
                <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</a>
            </div>
        </div>

        <div class="admin-content">
            <div class="admin-header">
                <h2>
                    <?php
                    if ($page == 'products') echo 'Quản lý Sản phẩm';
                    if ($page == 'orders') echo 'Danh sách Đơn hàng';
                    if ($page == 'order_detail') echo 'Chi tiết Đơn hàng';
                    ?>
                </h2>
                <span>Xin chào, <strong><?php echo $_SESSION['user']['full_name']; ?></strong></span>
            </div>

            <?php if ($page == 'products'):
                // Lấy dữ liệu sản phẩm
                $brands = $conn->query("SELECT * FROM brands");
                $products = $conn->query("SELECT p.*, b.name as brand_name FROM products p JOIN brands b ON p.brand_id = b.id ORDER BY p.id DESC");

                $edit_product = null;
                if (isset($_GET['edit_id'])) {
                    $edit_id = $_GET['edit_id'];
                    $edit_product = $conn->query("SELECT * FROM products WHERE id = $edit_id")->fetch_assoc();
                }
            ?>
                <div class="box">
                    <h3 style="margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                        <?php echo $edit_product ? 'Sửa sản phẩm' : 'Thêm sản phẩm mới'; ?>
                    </h3>
                    <form method="POST" action="" enctype="multipart/form-data">
                        <?php if ($edit_product): ?>
                            <input type="hidden" name="edit_id" value="<?php echo $edit_product['id']; ?>">
                        <?php endif; ?>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Tên sản phẩm:</label>
                                <input type="text" name="name" required value="<?php echo $edit_product['name'] ?? ''; ?>">
                            </div>
                            <div class="form-group">
                                <label>Hãng:</label>
                                <select name="brand_id">
                                    <?php $brands->data_seek(0);
                                    while ($b = $brands->fetch_assoc()): ?>
                                        <option value="<?php echo $b['id']; ?>" <?php echo ($edit_product && $edit_product['brand_id'] == $b['id']) ? 'selected' : ''; ?>><?php echo $b['name']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group"><label>Giá bán:</label><input type="number" name="price" required value="<?php echo $edit_product['price'] ?? ''; ?>"></div>
                            <div class="form-group"><label>Giá cũ:</label><input type="number" name="old_price" value="<?php echo $edit_product['old_price'] ?? ''; ?>"></div>
                            <div class="form-group">
                                <label>Ảnh (Chọn file từ máy):</label>
                                <input type="file" name="image_file" accept="image/*">
                            </div>
                            <div class="form-group"><label>Link ảnh (URL - Tùy chọn):</label><input type="text" name="image_url" value="<?php echo $edit_product['image_url'] ?? ''; ?>"></div>
                            <div class="form-group"><label>Nhãn (Badge):</label><input type="text" name="badge" value="<?php echo $edit_product['badge'] ?? ''; ?>"></div>
                            <div class="form-group"><label>CPU:</label><input type="text" name="cpu" value="<?php echo $edit_product['cpu'] ?? ''; ?>"></div>
                            <div class="form-group"><label>RAM:</label><input type="text" name="ram" value="<?php echo $edit_product['ram'] ?? ''; ?>"></div>
                        </div>
                        <div class="form-group" style="margin-top:15px;"><label>Mô tả:</label><textarea name="description" rows="3"><?php echo $edit_product['description'] ?? ''; ?></textarea></div>
                        <button type="submit" name="<?php echo $edit_product ? 'update_product' : 'add_product'; ?>" class="btn-submit"><?php echo $edit_product ? 'Cập nhật' : 'Thêm mới'; ?></button>
                        <?php if ($edit_product): ?><a href="admin.php?page=products" style="margin-left:10px; color:#666;">Hủy</a><?php endif; ?>
                    </form>
                </div>

                <div class="box">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ảnh</th>
                                <th>Tên</th>
                                <th>Giá</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $products->fetch_assoc()):
                                $img_src = !empty($row['image_data']) ? 'data:image/jpeg;base64,' . base64_encode($row['image_data']) : $row['image_url'];
                            ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><img src="<?php echo $img_src; ?>" width="40" height="40" style="object-fit:cover;"></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td style="color:#cd1818;"><?php echo number_format($row['price'], 0, ',', '.'); ?>đ</td>
                                    <td>
                                        <a href="admin.php?page=products&edit_id=<?php echo $row['id']; ?>" style="color:blue;"><i class="fa-solid fa-pen"></i></a>
                                        <a href="admin.php?page=products&delete_id=<?php echo $row['id']; ?>" style="color:red; margin-left:10px;" onclick="return confirm('Xóa?');"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>


            <?php if ($page == 'orders'):
                $orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
            ?>
                <div class="box">
                    <table>
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Chi tiết</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($ord = $orders->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $ord['id']; ?></td>
                                    <td>
                                        <strong><?php echo $ord['full_name']; ?></strong><br>
                                        <span style="font-size:12px; color:#666;"><?php echo $ord['phone']; ?></span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($ord['created_at'])); ?></td>
                                    <td style="color:#cd1818; font-weight:bold;"><?php echo number_format($ord['total_money'], 0, ',', '.'); ?>đ</td>
                                    <td>
                                        <span class="badge-status st-<?php echo $ord['status']; ?>">
                                            <?php
                                            if ($ord['status'] == 'Pending') echo 'Chờ xử lý';
                                            elseif ($ord['status'] == 'Shipping') echo 'Đang giao';
                                            elseif ($ord['status'] == 'Completed') echo 'Hoàn thành';
                                            elseif ($ord['status'] == 'Cancelled') echo 'Đã hủy';
                                            else echo $ord['status'];
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="admin.php?page=order_detail&id=<?php echo $ord['id']; ?>" class="btn-view"><i class="fa-solid fa-eye"></i> Xem</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>


            <?php if ($page == 'order_detail' && isset($_GET['id'])):
                $order_id = $_GET['id'];
                $order = $conn->query("SELECT * FROM orders WHERE id = $order_id")->fetch_assoc();
                $details = $conn->query("SELECT * FROM order_details WHERE order_id = $order_id");
            ?>
                <div style="margin-bottom: 15px;">
                    <a href="admin.php?page=orders" style="text-decoration:none; color:#333;"><i class="fa-solid fa-arrow-left"></i> Quay lại danh sách</a>
                </div>

                <div class="form-grid">
                    <div class="box">
                        <h3>Thông tin khách hàng</h3>
                        <p><strong>Họ tên:</strong> <?php echo $order['full_name']; ?></p>
                        <p><strong>SĐT:</strong> <?php echo $order['phone']; ?></p>
                        <p><strong>Địa chỉ:</strong> <?php echo $order['address']; ?></p>
                        <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>

                        <hr style="margin: 15px 0; border: 0; border-top: 1px solid #eee;">

                        <form method="POST" action="">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <label><strong>Cập nhật trạng thái:</strong></label>
                            <div style="display:flex; gap:10px; margin-top:5px;">
                                <select name="status" style="width:auto;">
                                    <option value="Pending" <?php echo $order['status'] == 'Pending' ? 'selected' : ''; ?>>Chờ xử lý</option>
                                    <option value="Shipping" <?php echo $order['status'] == 'Shipping' ? 'selected' : ''; ?>>Đang giao hàng</option>
                                    <option value="Completed" <?php echo $order['status'] == 'Completed' ? 'selected' : ''; ?>>Hoàn thành</option>
                                    <option value="Cancelled" <?php echo $order['status'] == 'Cancelled' ? 'selected' : ''; ?>>Hủy đơn</option>
                                </select>
                                <button type="submit" name="update_status" class="btn-submit" style="margin:0; padding: 8px 15px;">Lưu</button>
                            </div>
                        </form>
                    </div>

                    <div class="box">
                        <h3>Sản phẩm đã mua</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>SL</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($item = $details->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $item['product_name']; ?></td>
                                        <td><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td><strong><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>đ</strong></td>
                                    </tr>
                                <?php endwhile; ?>
                                <tr>
                                    <td colspan="3" style="text-align:right;"><strong>TỔNG TIỀN:</strong></td>
                                    <td style="color:#cd1818; font-size:18px; font-weight:bold;">
                                        <?php echo number_format($order['total_money'], 0, ',', '.'); ?>đ
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>

</body>

</html>