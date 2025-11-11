<?php
require_once 'config.php';

// Xác định trang hiện tại
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page);
$offset = ($page - 1) * ITEMS_PER_PAGE;

// Đếm tổng số sản phẩm
$count_sql = "SELECT COUNT(*) as total FROM products";
$count_result = mysqli_query($conn, $count_sql);
$total_products = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_products / ITEMS_PER_PAGE);

// Lấy sản phẩm cho trang hiện tại
$sql = "SELECT * FROM products ORDER BY created_at DESC LIMIT $offset, " . ITEMS_PER_PAGE;
$result = mysqli_query($conn, $sql);
?>

<?php include 'menu.php'; ?>

<div class="container">
    <h1 class="page-title">Sản Phẩm Mới Nhất</h1>
    <p class="page-subtitle">Khám phá những sản phẩm Apple tuyệt vời</p>

    <div class="product-grid">
        <?php while($product = mysqli_fetch_assoc($result)): ?>
        <div class="product-card">
            <div class="product-image">
                <a href="detail.php?id=<?php echo $product['id']; ?>">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>"
                        alt="<?php echo htmlspecialchars($product['name']); ?>">
                </a>
            </div>
            <div class="product-info">
                <a id=<?php echo $product['id']; ?>" class="product-name-link">
                    <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                </a>
                <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                <p class="product-price"><?php echo number_format($product['price'], 0, ',', '.'); ?> ₫</p>
                <div class="product-actions">
                    <a href="detail.php?id=<?php echo $product['id']; ?>" class="btn-add-cart">Xem chi tiết</a>
                    <a href="addcart.php?add=<?php echo $product['id']; ?>" class="btn-add-cart">🛒 Thêm vào giỏ</a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <?php if($total_pages > 1): ?>
    <div class="pagination">
        <?php if($page > 1): ?>
        <a href="?page=<?php echo $page - 1; ?>" class="pagination-btn">← Trước</a>
        <?php endif; ?>

        <?php for($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?page=<?php echo $i; ?>" class="pagination-number <?php echo $i == $page ? 'active' : ''; ?>">
            <?php echo $i; ?>
        </a>
        <?php endfor; ?>

        <?php if($page < $total_pages): ?>
        <a href="?page=<?php echo $page + 1; ?>" class="pagination-btn">Sau →</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<style>
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 60px;
}

.product-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.product-image {
    width: 100%;
    height: 300px;
    overflow: hidden;
    background: #fafafa;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-info {
    padding: 25px;
}

.product-name {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #1d1d1f;
}

.product-description {
    font-size: 14px;
    color: #6e6e73;
    margin-bottom: 15px;
    line-height: 1.5;
}

.product-price {
    font-size: 20px;
    font-weight: 700;
    color: #000;
    margin-bottom: 15px;
}

.btn-add-cart {
    display: inline-block;
    padding: 12px 24px;
    background: #000;
    color: #fff;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
}

.btn-add-cart:hover {
    background: #333;
    transform: translateY(-2px);
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin: 50px 0;
}

.pagination-btn,
.pagination-number {
    padding: 12px 20px;
    background: #fff;
    color: #000;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid #e5e5e7;
}

.pagination-btn:hover,
.pagination-number:hover {
    background: #000;
    color: #fff;
    border-color: #000;
}

.pagination-number.active {
    background: #000;
    color: #fff;
    border-color: #000;
}
</style>

</body>

</html>