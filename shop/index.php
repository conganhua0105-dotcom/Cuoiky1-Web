<?php
session_start();
require_once 'db.php'; // Gọi file kết nối

// 1. ĐẾM GIỎ HÀNG
$total_qty = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_qty += $item['qty'];
    }
}

// 2. XỬ LÝ BỘ LỌC (SERVER SIDE)
$where_clauses = [];

// A. Lọc Tìm kiếm (Từ thanh header)
if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
    $keyword = $conn->real_escape_string($_GET['keyword']);
    $where_clauses[] = "p.name LIKE '%$keyword%'";
}

// B. Lọc Hãng
$current_brand = isset($_GET['brand']) ? $_GET['brand'] : 'all';
if ($current_brand != 'all') {
    $safe_brand = $conn->real_escape_string($current_brand);
    $where_clauses[] = "b.slug = '$safe_brand'";
}

// C. Lọc Trạng thái
if (isset($_GET['status']) && !empty($_GET['status'])) {
    $status_arr = $_GET['status'];
    $status_conditions = [];
    foreach ($status_arr as $st) {
        if ($st == 'sale') $status_conditions[] = "(p.badge LIKE '%-%' OR p.badge LIKE '%SALE%' OR p.badge LIKE '%GIẢM%')";
        if ($st == 'new') $status_conditions[] = "(p.badge LIKE '%NEW%' OR p.badge LIKE '%MỚI%' OR p.badge LIKE '%HOT%')";
    }
    if (!empty($status_conditions)) $where_clauses[] = "(" . implode(' OR ', $status_conditions) . ")";
}

// D. Lọc Giá
if (isset($_GET['price']) && !empty($_GET['price'])) {
    $price_arr = $_GET['price'];
    $price_conditions = [];
    foreach ($price_arr as $range) {
        $parts = explode('-', $range);
        if (count($parts) == 2) {
            $min = (int)$parts[0];
            $max = (int)$parts[1];
            $price_conditions[] = "(p.price >= $min AND p.price <= $max)";
        }
    }
    if (!empty($price_conditions)) $where_clauses[] = "(" . implode(' OR ', $price_conditions) . ")";
}

// Tạo câu lệnh SQL WHERE
$sql_where = !empty($where_clauses) ? "WHERE " . implode(' AND ', $where_clauses) : "";

// 3. XỬ LÝ PHÂN TRANG
$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$start = ($page - 1) * $limit;

// Đếm tổng số
$sql_count = "SELECT count(p.id) as total FROM products p JOIN brands b ON p.brand_id = b.id $sql_where";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_records = $row_count['total'];
$total_pages = ceil($total_records / $limit);

// =================================================================
// CẬP NHẬT 1: ORDER BY RAND() ĐỂ SẢN PHẨM HIỆN NGẪU NHIÊN
// =================================================================
$sql = "SELECT p.*, b.slug FROM products p JOIN brands b ON p.brand_id = b.id $sql_where ORDER BY RAND() LIMIT $start, $limit";
$result = $conn->query($sql);


// Hàm giữ tham số URL
function get_url_param($exclude_param = '')
{
    $params = $_GET;
    if ($exclude_param && isset($params[$exclude_param])) unset($params[$exclude_param]);
    return http_build_query($params);
}

// Hàm xử lý đường dẫn ảnh (Đảm bảo an toàn đường dẫn)
function get_product_image_url($url)
{
    if (strpos($url, 'uploads/') !== false) return $url;
    if (strpos($url, 'http') !== false || strpos($url, 'https') !== false) return $url;
    return 'https://via.placeholder.com/200x150';
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Laptop Shop - Trang chủ</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .btn-filter-submit {
            margin-top: 15px;
            padding: 10px 25px;
            background: #cd1818;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
        }

        .btn-filter-submit:hover {
            background-color: #a00;
        }

        .btn-add-cart {
            width: 100%;
            padding: 10px;
            background-color: #cd1818;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 8px;
        }

        .page-item {
            text-decoration: none;
            padding: 8px 14px;
            border: 1px solid #ddd;
            color: #333;
            border-radius: 4px;
            background: white;
            font-weight: 600;
        }

        .page-item.active {
            background-color: #cd1818;
            color: white;
            pointer-events: none;
        }

        .brand-tag {
            text-decoration: none;
            display: inline-block;
            color: #333;
        }

        .brand-tag.active {
            background-color: #cd1818;
            color: white;
            border-color: #cd1818;
        }

        .product-link {
            text-decoration: none;
            color: inherit;
        }

        .product-link:hover .product-name {
            color: #cd1818;
        }

        .cart-count {
            background: yellow;
            color: red;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            position: absolute;
            top: -5px;
            right: -10px;
            font-weight: bold;
        }

        .icon-link {
            text-decoration: none;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
        }

        .icon-link:hover {
            opacity: 0.8;
        }

        .icon-link span {
            font-size: 11px;
        }
    </style>
</head>

<body>

    <header>
        <div class="container header-content">
            <div class="logo"><a href="index.php" style="color:white; text-decoration:none;">LAPTOP SHOP</a></div>

            <form action="index.php" method="GET" class="search-bar">
                <input type="text" name="keyword" placeholder="Nhập tên máy tính..." value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>

            <div class="header-icons">
                <a href="mailto:hotro@laptop.com" class="icon-link">
                    <i class="fa-solid fa-headset" style="font-size: 18px;"></i><span>Hỗ trợ</span>
                </a>

                <a href="cart.php" class="icon-item" style="position: relative; text-decoration: none; color: white;">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span>Giỏ hàng</span>
                    <?php if ($total_qty > 0): ?>
                        <span class="cart-count"><?php echo $total_qty; ?></span>
                    <?php endif; ?>
                </a>

                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['user']['role'] == 'admin'): ?>
                        <a href="admin.php" class="icon-link">
                            <div class="user-icon"><i class="fa-solid fa-user-shield"></i></div><span>Admin</span>
                        </a>
                    <?php else: ?>
                        <div class="icon-item" style="cursor: default;">
                            <div class="user-icon"><i class="fa-solid fa-user"></i></div>
                            <span style="font-size: 11px;">Hi, <?php echo $_SESSION['user']['full_name']; ?></span>
                        </div>
                    <?php endif; ?>
                    <a href="logout.php" class="icon-link" style="margin-left: 10px;">
                        <div class="user-icon" style="background: #555;"><i class="fa-solid fa-right-from-bracket"></i></div><span>Thoát</span>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="icon-link">
                        <div class="user-icon"><i class="fa-solid fa-user"></i></div><span>Đăng nhập</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="container main-wrapper">
        <section class="brands-section">
            <h3>Thương hiệu nổi bật</h3>
            <div class="brand-list">
                <?php
                // =================================================================
                // CẬP NHẬT 2: ĐÃ THÊM SURFACE VÀ RAZER VÀO LIST
                // =================================================================
                $brands_list = [
                    'all' => 'Tất cả',
                    'apple' => 'MacBook',
                    'asus' => 'ASUS',
                    'msi' => 'MSI',
                    'dell' => 'Dell',
                    'hp' => 'HP',
                    'acer' => 'Acer',
                    'lenovo' => 'Lenovo',
                    'surface' => 'Surface',
                    'razer' => 'Razer',
                    'lg' => 'LG',
                    'samsung' => 'Samsung',
                    'gigabyte' => 'Gigabyte'
                ];
                foreach ($brands_list as $slug => $name):
                ?>
                    <a href="index.php?brand=<?php echo $slug; ?>" class="brand-tag <?php echo ($current_brand == $slug) ? 'active' : ''; ?>"><?php echo $name; ?></a>
                <?php endforeach; ?>
            </div>
        </section>

        <div class="content-layout">
            <aside class="sidebar">
                <form action="index.php" method="GET">
                    <input type="hidden" name="brand" value="<?php echo $current_brand; ?>">
                    <?php if (isset($_GET['keyword'])): ?>
                        <input type="hidden" name="keyword" value="<?php echo $_GET['keyword']; ?>">
                    <?php endif; ?>

                    <div class="filter-group">
                        <h4>Trạng thái</h4>
                        <div class="filter-options">
                            <?php $st_arr = isset($_GET['status']) ? $_GET['status'] : []; ?>
                            <label><input type="checkbox" name="status[]" value="new" <?php echo in_array('new', $st_arr) ? 'checked' : ''; ?>> Hàng mới về</label>
                            <label><input type="checkbox" name="status[]" value="sale" <?php echo in_array('sale', $st_arr) ? 'checked' : ''; ?>> Khuyến mãi sốc</label>
                        </div>
                    </div>
                    <div class="filter-group">
                        <h4>Mức giá</h4>
                        <div class="filter-options">
                            <?php $pr_arr = isset($_GET['price']) ? $_GET['price'] : []; ?>
                            <label><input type="checkbox" name="price[]" value="0-10000000" <?php echo in_array('0-10000000', $pr_arr) ? 'checked' : ''; ?>> Dưới 10 triệu</label>
                            <label><input type="checkbox" name="price[]" value="10000000-20000000" <?php echo in_array('10000000-20000000', $pr_arr) ? 'checked' : ''; ?>> 10 - 20 triệu</label>
                            <label><input type="checkbox" name="price[]" value="20000000-999999999" <?php echo in_array('20000000-999999999', $pr_arr) ? 'checked' : ''; ?>> Trên 20 triệu</label>
                        </div>
                    </div>
                    <button type="submit" class="btn-filter-submit">Lọc sản phẩm</button>
                </form>
                <div style="margin-top: 20px;">
                    <img src="https://images.unsplash.com/photo-1603302576837-37561b2e2302?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" alt="Ads" style="width:100%; border-radius: 8px;">
                </div>
            </aside>

            <div style="flex-grow: 1;">
                <main class="product-grid">
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $price_fmt = number_format($row['price'], 0, ',', '.') . 'đ';
                            $old_price_fmt = number_format($row['old_price'], 0, ',', '.') . 'đ';
                            $img = get_product_image_url($row['image_url']);
                    ?>
                            <div class="product-card">
                                <div class="badge"><?php echo $row['badge']; ?></div>
                                <a href="detail.php?id=<?php echo $row['id']; ?>" class="product-link">
                                    <div class="product-img"><img src="<?php echo $img; ?>" alt="<?php echo $row['name']; ?>"></div>
                                    <h3 class="product-name"><?php echo $row['name']; ?></h3>
                                </a>
                                <div class="price-box">
                                    <span class="current-price"><?php echo $price_fmt; ?></span>
                                    <span class="old-price"><?php echo $old_price_fmt; ?></span>
                                </div>
                                <div class="specs">
                                    <?php if ($row['cpu']) { ?><span><i class="fa-solid fa-microchip"></i> <?php echo $row['cpu']; ?></span><?php } ?>
                                    <?php if ($row['ram']) { ?><span><i class="fa-solid fa-memory"></i> <?php echo $row['ram']; ?></span><?php } ?>
                                </div>
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="name" value="<?php echo $row['name']; ?>">
                                    <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                                    <input type="hidden" name="img" value="<?php echo $img; ?>">
                                    <button type="submit" name="add_to_cart" class="btn-add-cart">
                                        <i class="fa-solid fa-cart-plus"></i> Thêm vào giỏ
                                    </button>
                                </form>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<p style='width:100%; text-align:center; padding:50px;'>Không tìm thấy sản phẩm nào!</p>";
                    }
                    ?>
                </main>

                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php
                        $query_string = get_url_param('page');
                        $link_base = "index.php?" . ($query_string ? $query_string . "&" : "");
                        ?>
                        <?php if ($page > 1): ?>
                            <a href="<?php echo $link_base . "page=" . ($page - 1); ?>" class="page-item"><i class="fa-solid fa-chevron-left"></i></a>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="<?php echo $link_base . "page=" . $i; ?>" class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                        <?php endfor; ?>
                        <?php if ($page < $total_pages): ?>
                            <a href="<?php echo $link_base . "page=" . ($page + 1); ?>" class="page-item"><i class="fa-solid fa-chevron-right"></i></a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-system">
                <div class="footer-text">
                    <h4>Hệ thống Laptop Shop trên toàn quốc</h4>
                    <p>Bao gồm Cửa hàng Laptop Shop, Trung tâm Điện máy, Trung tâm Laptop, Bảo hành ủy quyền</p>
                </div>
                <div class="footer-btn">
                    <button>Xem danh sách cửa hàng</button>
                </div>
            </div>

            <div class="footer-payment">
                <h4>Hỗ trợ thanh toán</h4>
                <div class="payment-grid">
                    <div class="pay-item"><img src="https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png" alt="Visa"></div>
                    <div class="pay-item"><img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard"></div>
                    <div class="pay-item"><span>JCB</span></div>
                    <div class="pay-item"><span>Amex</span></div>
                    <div class="pay-item"><span>VNPAY</span></div>
                    <div class="pay-item"><span style="color: #0088ff; font-weight:bold;">ZaloPay</span></div>
                    <div class="pay-item"><span>Napas</span></div>
                    <div class="pay-item"><span>Kredivo</span></div>
                    <div class="pay-item"><span style="color: #a50064; font-weight:bold;">MoMo</span></div>
                    <div class="pay-item"><span>Foxpay</span></div>
                    <div class="pay-item"><span>AlePay</span></div>
                    <div class="pay-item"><span>Muadee</span></div>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
<?php $conn->close(); ?>