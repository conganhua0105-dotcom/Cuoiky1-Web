<?php
require_once 'config.php';

// Lấy danh mục được chọn
$selected_category = isset($_GET['cat']) ? $_GET['cat'] : 'dien-thoai';

// Danh sách các danh mục
$categories = [
    'dien-thoai' => ['name' => 'Điện Thoại', 'icon' => '📱'],
    'may-tinh' => ['name' => 'Máy Tính', 'icon' => '💻'],
    'tai-nghe' => ['name' => 'Tai Nghe', 'icon' => '🎧']
];

// Lấy sản phẩm theo danh mục
$sql = "SELECT * FROM products WHERE category = '$selected_category' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<?php include 'menu.php'; ?>

<div class="container">
    <h1 class="page-title">Danh Mục Sản Phẩm</h1>
    <p class="page-subtitle">Chọn danh mục để xem sản phẩm</p>

    <div class="category-tabs">
        <?php foreach($categories as $key => $cat): ?>
        <a href="?cat=<?php echo $key; ?>"
            class="category-tab <?php echo $selected_category == $key ? 'active' : ''; ?>">
            <span class="category-icon"><?php echo $cat['icon']; ?></span>
            <span class="category-name"><?php echo $cat['name']; ?></span>
        </a>
        <?php endforeach; ?>
    </div>

    <div class="category-header">
        <h2><?php echo $categories[$selected_category]['icon'] . ' ' . $categories[$selected_category]['name']; ?></h2>
    </div>

    <div class="product-grid">
        <?php if(mysqli_num_rows($result) > 0): ?>
        <?php while($product = mysqli_fetch_assoc($result)): ?>
        <div class="product-card">
            <div class="product-image">
                <img src="<?php echo htmlspecialchars($product['image']); ?>"
                    alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="product-info">
                <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                <p class="product-price"><?php echo number_format($product['price'], 0, ',', '.'); ?> ₫</p>
                <a href="addcart.php?add=<?php echo $product['id']; ?>" class="btn-add-cart">🛒 Thêm vào giỏ</a>
            </div>
        </div>
        <?php endwhile; ?>
        <?php else: ?>
        <div class="no-products">
            <p>Không có sản phẩm nào trong danh mục này.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
.category-tabs {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-bottom: 50px;
    flex-wrap: wrap;
}

.category-tab {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    padding: 30px 50px;
    background: #fff;
    border-radius: 18px;
    text-decoration: none;
    color: #1d1d1f;
    transition: all 0.3s ease;
    border: 3px solid transparent;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
}

.category-tab:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.category-tab.active {
    border-color: #000;
    background: #000;
    color: #fff;
}

.category-icon {
    font-size: 48px;
}

.category-name {
    font-size: 18px;
    font-weight: 600;
}

.category-header {
    text-align: center;
    margin-bottom: 40px;
}

.category-header h2 {
    font-size: 36px;
    font-weight: 700;
    color: #1d1d1f;
}

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

.no-products {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
    font-size: 18px;
    color: #6e6e73;
}
</style>

</body>

</html>