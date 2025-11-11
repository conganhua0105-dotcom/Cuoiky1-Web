<?php
session_start();
require_once 'config.php';

// Khởi tạo giỏ hàng nếu chưa có
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xử lý thêm sản phẩm vào giỏ hàng
if(isset($_GET['add'])) {
    $product_id = (int)$_GET['add'];
    
    // Lấy thông tin sản phẩm từ database
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);
    
    if($product) {
        // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
        if(isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity']++;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        }
        $success_message = "Đã thêm {$product['name']} vào giỏ hàng!";
    }
}

// Xử lý cập nhật số lượng
if(isset($_POST['update_cart'])) {
    foreach($_POST['quantity'] as $id => $qty) {
        $qty = (int)$qty;
        if($qty > 0) {
            $_SESSION['cart'][$id]['quantity'] = $qty;
        } else {
            unset($_SESSION['cart'][$id]);
        }
    }
    $success_message = "Cập nhật giỏ hàng thành công!";
}

// Xử lý xóa một sản phẩm từ delcart.php
if(isset($_GET['deleted'])) {
    $success_message = "Đã xóa sản phẩm khỏi giỏ hàng!";
}

// Xử lý xóa toàn bộ từ delcart.php
if(isset($_GET['cleared'])) {
    $success_message = "Đã xóa toàn bộ giỏ hàng!";
}

// Tính tổng tiền
$total = 0;
foreach($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<?php include 'menu.php'; ?>

<div class="container">
    <h1 class="page-title">🛒 Giỏ Hàng</h1>
    <p class="page-subtitle">Quản lý sản phẩm trong giỏ hàng của bạn</p>

    <?php if(isset($success_message)): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if(empty($_SESSION['cart'])): ?>
    <div class="empty-cart">
        <div class="empty-icon">🛒</div>
        <h2>Giỏ hàng trống</h2>
        <p>Bạn chưa có sản phẩm nào trong giỏ hàng</p>
        <a href="index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
    </div>
    <?php else: ?>
    <form method="POST" class="cart-form">
        <div class="cart-table">
            <table>
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                        <th>Xóa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($_SESSION['cart'] as $id => $item): ?>
                    <tr>
                        <td class="product-info">
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="">
                            <span><?php echo htmlspecialchars($item['name']); ?></span>
                        </td>
                        <td class="price"><?php echo number_format($item['price'], 0, ',', '.'); ?> ₫</td>
                        <td>
                            <input type="number" name="quantity[<?php echo $id; ?>]"
                                value="<?php echo $item['quantity']; ?>" min="0" class="quantity-input">
                        </td>
                        <td class="price"><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?>
                            ₫</td>
                        <td>
                            <a href="delcart.php?delete=<?php echo $id; ?>" class="btn-delete-item"
                                onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="cart-actions">
            <div class="cart-buttons">
                <button type="submit" name="update_cart" class="btn btn-secondary">♻️ Cập nhật giỏ hàng</button>
                <a href="delcart.php?clear=all" class="btn btn-danger"
                    onclick="return confirm('Bạn có chắc muốn xóa toàn bộ giỏ hàng?')">🗑️ Xóa toàn bộ</a>
            </div>

            <div class="cart-summary">
                <div class="summary-row">
                    <span>Tổng sản phẩm:</span>
                    <span class="total-items"><?php echo count($_SESSION['cart']); ?> món</span>
                </div>
                <div class="summary-row">
                    <span>Tổng cộng:</span>
                    <span class="total-price"><?php echo number_format($total, 0, ',', '.'); ?> ₫</span>
                </div>
                <button type="button" class="btn btn-checkout"
                    onclick="alert('Chức năng thanh toán đang phát triển!')">💳 Thanh toán</button>
                <a href="index.php" class="btn btn-continue">← Tiếp tục mua sắm</a>
            </div>
        </div>
    </form>
    <?php endif; ?>
</div>

<style>
.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 30px;
    font-weight: 500;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.empty-cart {
    text-align: center;
    padding: 80px 20px;
    background: #fff;
    border-radius: 18px;
    margin: 40px 0;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
}

.empty-icon {
    font-size: 80px;
    margin-bottom: 20px;
}

.empty-cart h2 {
    font-size: 28px;
    font-weight: 600;
    color: #1d1d1f;
    margin-bottom: 10px;
}

.empty-cart p {
    font-size: 16px;
    color: #6e6e73;
    margin-bottom: 30px;
}

.cart-form {
    background: #fff;
    border-radius: 18px;
    padding: 30px;
    margin-bottom: 60px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
}

.cart-table {
    overflow-x: auto;
    margin-bottom: 30px;
}

.cart-table table {
    width: 100%;
    border-collapse: collapse;
}

.cart-table th {
    background: #f5f5f7;
    padding: 15px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    color: #1d1d1f;
    border-bottom: 2px solid #e5e5e7;
}

.cart-table td {
    padding: 20px 15px;
    border-bottom: 1px solid #e5e5e7;
    vertical-align: middle;
}

.cart-table tbody tr:hover {
    background: #fafafa;
}

.product-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.product-info img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
}

.product-info span {
    font-size: 16px;
    font-weight: 500;
    color: #1d1d1f;
}

.price {
    font-size: 16px;
    font-weight: 600;
    color: #1d1d1f;
}

.quantity-input {
    width: 80px;
    padding: 8px 12px;
    border: 2px solid #e5e5e7;
    border-radius: 8px;
    font-size: 14px;
    text-align: center;
    font-weight: 600;
}

.quantity-input:focus {
    outline: none;
    border-color: #000;
}

.btn-delete-item {
    font-size: 24px;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-block;
}

.btn-delete-item:hover {
    transform: scale(1.3);
}

.cart-actions {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 30px;
    padding-top: 20px;
    border-top: 2px solid #e5e5e7;
}

.cart-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.cart-summary {
    min-width: 320px;
    background: #f5f5f7;
    padding: 25px;
    border-radius: 12px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    font-size: 16px;
}

.summary-row:last-of-type {
    margin-bottom: 25px;
    padding-top: 15px;
    border-top: 2px solid #e5e5e7;
}

.total-items {
    font-weight: 600;
    color: #1d1d1f;
}

.total-price {
    font-size: 28px;
    font-weight: 700;
    color: #000;
}

.btn {
    padding: 12px 24px;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-primary {
    background: #000;
    color: #fff;
}

.btn-primary:hover {
    background: #333;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #007aff;
    color: #fff;
}

.btn-secondary:hover {
    background: #0051d5;
    transform: translateY(-2px);
}

.btn-danger {
    background: #ff3b30;
    color: #fff;
}

.btn-danger:hover {
    background: #d70015;
    transform: translateY(-2px);
}

.btn-checkout {
    width: 100%;
    background: #000;
    color: #fff;
    margin-bottom: 10px;
    font-size: 16px;
    padding: 15px;
}

.btn-checkout:hover {
    background: #333;
    transform: translateY(-2px);
}

.btn-continue {
    width: 100%;
    background: #fff;
    color: #000;
    border: 2px solid #e5e5e7;
}

.btn-continue:hover {
    background: #f5f5f7;
}

@media (max-width: 968px) {
    .cart-actions {
        flex-direction: column;
    }

    .cart-summary {
        width: 100%;
    }

    .product-info img {
        width: 60px;
        height: 60px;
    }

    .product-info span {
        font-size: 14px;
    }
}
</style>

</body>

</html>