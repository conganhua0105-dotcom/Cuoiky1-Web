<?php
require_once 'config.php';

// Xử lý thêm sản phẩm
if(isset($_POST['add_product'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $image = mysqli_real_escape_string($conn, $_POST['image']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $sql = "INSERT INTO products (name, category, price, image, description) VALUES ('$name', '$category', '$price', '$image', '$description')";
    if(mysqli_query($conn, $sql)) {
        $success_message = "Thêm sản phẩm thành công!";
    } else {
        $error_message = "Lỗi: " . mysqli_error($conn);
    }
}

// Xử lý xóa sản phẩm
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $sql = "DELETE FROM products WHERE id = $id";
    if(mysqli_query($conn, $sql)) {
        $success_message = "Xóa sản phẩm thành công!";
    }
}

// Xử lý cập nhật sản phẩm
if(isset($_POST['update_product'])) {
    $id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $image = mysqli_real_escape_string($conn, $_POST['image']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $sql = "UPDATE products SET name='$name', category='$category', price='$price', image='$image', description='$description' WHERE id=$id";
    if(mysqli_query($conn, $sql)) {
        $success_message = "Cập nhật sản phẩm thành công!";
    }
}

// Lấy sản phẩm cần sửa
$edit_product = null;
if(isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_sql = "SELECT * FROM products WHERE id = $edit_id";
    $edit_result = mysqli_query($conn, $edit_sql);
    $edit_product = mysqli_fetch_assoc($edit_result);
}

// Lấy tất cả sản phẩm
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<?php include 'menu.php'; ?>

<div class="container">
    <h1 class="page-title">Quản Lý Sản Phẩm</h1>
    <p class="page-subtitle">Thêm, sửa, xóa sản phẩm</p>

    <?php if(isset($success_message)): ?>
    <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if(isset($error_message)): ?>
    <div class="alert alert-error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <div class="admin-content">
        <div class="form-section">
            <h2><?php echo $edit_product ? '✏️ Sửa Sản Phẩm' : '➕ Thêm Sản Phẩm Mới'; ?></h2>
            <form method="POST" class="product-form">
                <?php if($edit_product): ?>
                <input type="hidden" name="id" value="<?php echo $edit_product['id']; ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label>Tên sản phẩm</label>
                    <input type="text" name="name" required
                        value="<?php echo $edit_product ? htmlspecialchars($edit_product['name']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Danh mục</label>
                    <select name="category" required>
                        <option value="dien-thoai"
                            <?php echo ($edit_product && $edit_product['category'] == 'dien-thoai') ? 'selected' : ''; ?>>
                            📱 Điện Thoại</option>
                        <option value="may-tinh"
                            <?php echo ($edit_product && $edit_product['category'] == 'may-tinh') ? 'selected' : ''; ?>>
                            💻 Máy Tính</option>
                        <option value="tai-nghe"
                            <?php echo ($edit_product && $edit_product['category'] == 'tai-nghe') ? 'selected' : ''; ?>>
                            🎧 Tai Nghe</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Giá (VNĐ)</label>
                    <input type="number" name="price" required
                        value="<?php echo $edit_product ? $edit_product['price'] : ''; ?>">
                </div>

                <div class="form-group">
                    <label>URL Ảnh</label>
                    <input type="text" name="image" required
                        value="<?php echo $edit_product ? htmlspecialchars($edit_product['image']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label>Mô tả</label>
                    <textarea name="description" rows="4"
                        required><?php echo $edit_product ? htmlspecialchars($edit_product['description']) : ''; ?></textarea>
                </div>

                <div class="form-actions">
                    <?php if($edit_product): ?>
                    <button type="submit" name="update_product" class="btn btn-primary">Cập Nhật</button>
                    <a href="admin.php" class="btn btn-secondary">Hủy</a>
                    <?php else: ?>
                    <button type="submit" name="add_product" class="btn btn-primary">Thêm Sản Phẩm</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <div class="table-section">
            <h2>📦 Danh Sách Sản Phẩm</h2>
            <div class="table-container">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Tên</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($product = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td>
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt=""
                                    class="table-image">
                            </td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td>
                                <?php 
                                $cat_icons = ['dien-thoai' => '📱', 'may-tinh' => '💻', 'tai-nghe' => '🎧'];
                                echo $cat_icons[$product['category']] ?? '';
                                ?>
                            </td>
                            <td><?php echo number_format($product['price'], 0, ',', '.'); ?> ₫</td>
                            <td class="action-buttons">
                                <a href="?edit=<?php echo $product['id']; ?>" class="btn-edit">✏️ Sửa</a>
                                <a href="?delete=<?php echo $product['id']; ?>" class="btn-delete"
                                    onclick="return confirm('Bạn có chắc muốn xóa?')">🗑️ Xóa</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.alert {
    padding: 15px 20px;
    border-radius: 10px;
    margin-bottom: 30px;
    font-weight: 500;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.admin-content {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 30px;
    margin-bottom: 60px;
}

.form-section,
.table-section {
    background: #fff;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
}

.form-section h2,
.table-section h2 {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 25px;
    color: #1d1d1f;
}

.product-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-size: 14px;
    font-weight: 600;
    color: #1d1d1f;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 12px 16px;
    border: 2px solid #e5e5e7;
    border-radius: 10px;
    font-size: 14px;
    font-family: inherit;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #000;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
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
}

.btn-secondary {
    background: #e5e5e7;
    color: #1d1d1f;
}

.btn-secondary:hover {
    background: #d1d1d6;
}

.table-container {
    overflow-x: auto;
}

.product-table {
    width: 100%;
    border-collapse: collapse;
}

.product-table th {
    background: #f5f5f7;
    padding: 15px;
    text-align: left;
    font-weight: 600;
    font-size: 14px;
    color: #1d1d1f;
    border-bottom: 2px solid #e5e5e7;
}

.product-table td {
    padding: 15px;
    border-bottom: 1px solid #e5e5e7;
    font-size: 14px;
    color: #1d1d1f;
}

.product-table tbody tr:hover {
    background: #fafafa;
}

.table-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}

.action-buttons {
    display: flex;
    gap: 10px;
}

.btn-edit,
.btn-delete {
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-edit {
    background: #007aff;
    color: #fff;
}

.btn-edit:hover {
    background: #0051d5;
}

.btn-delete {
    background: #ff3b30;
    color: #fff;
}

.btn-delete:hover {
    background: #d70015;
}

@media (max-width: 968px) {
    .admin-content {
        grid-template-columns: 1fr;
    }

    .action-buttons {
        flex-direction: column;
    }
}
</style>

</body>

</html>