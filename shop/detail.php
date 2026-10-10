<?php
session_start();
require_once 'db.php';

// Hàm xử lý đường dẫn ảnh (Đảm bảo an toàn đường dẫn)
function get_product_image_url($url) {
    // Nếu image_url là đường dẫn uploads/ thì dùng luôn
    if (strpos($url, 'uploads/') !== false) {
        return $url;
    }
    // Nếu là URL bên ngoài hoặc placeholder
    if (strpos($url, 'http') !== false || strpos($url, 'https') !== false) {
        return $url;
    }
    // Mặc định nếu không rõ
    return 'https://via.placeholder.com/400x300';
}

// Kiểm tra xem có ID trên đường dẫn không (VD: detail.php?id=10)
if (!isset($_GET['id'])) {
    die("Không tìm thấy sản phẩm!");
}

$id = $_GET['id'];

// Lấy thông tin chi tiết sản phẩm từ Database
$sql = "SELECT * FROM products WHERE id = $id";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

if (!$product) {
    die("Sản phẩm không tồn tại!");
}

// Lấy đường dẫn ảnh an toàn
$img = get_product_image_url($product['image_url']);

// Đếm giỏ hàng để hiện lên header
$total_qty = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total_qty += $item['qty'];
    }
}

// Lấy đánh giá trung bình (sử dụng Ajax sau để tối ưu, nhưng đây là phương án dự phòng)
$rating_sql = "SELECT AVG(rating) as avg_rating FROM reviews WHERE product_id = $id";
$rating_result = $conn->query($rating_sql);
$rating_row = $rating_result->fetch_assoc();
$average_rating = round($rating_row['avg_rating'] ?? 0, 1);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title><?php echo $product['name']; ?> - Chi tiết</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        /* CSS Riêng cho trang chi tiết */
        .detail-container {
            max-width: 1100px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
        }

        .detail-wrapper {
            display: flex;
            gap: 30px;
        }

        .detail-left {
            width: 40%;
        }

        .detail-left img {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #eee;
        }

        .detail-right {
            width: 60%;
        }

        .detail-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .detail-price {
            color: #cd1818;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .old-price {
            color: #888;
            text-decoration: line-through;
            font-size: 18px;
            margin-left: 10px;
        }

        .detail-specs {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .detail-specs p {
            margin-bottom: 8px;
        }

        .detail-specs i {
            width: 25px;
            color: #555;
        }

        .detail-desc {
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .detail-desc h3 {
            margin-bottom: 10px;
            color: #cd1818;
        }

        .detail-desc p,
        .detail-desc li {
            line-height: 1.6;
            color: #333;
        }

        .btn-buy-now {
            background: #cd1818;
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .btn-buy-now:hover {
            background: #a00;
        }

        /* Cập nhật CSS cho Rating */
        .product-rating {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .stars-display, .rating-input .star {
            color: orange;
            font-size: 1.5em;
        }
        .rating-avg {
            font-size: 1.1em;
            font-weight: bold;
            margin-left: 10px;
        }

        /* Vùng nhập Rating */
        .rating-input {
            display: inline-block;
            direction: rtl; /* Đảo ngược để sao hoạt động đúng */
            unicode-bidi: bidi-override;
            margin-bottom: 15px;
        }
        .rating-input .star {
            cursor: pointer;
            transition: color 0.2s;
            color: #ccc;
        }
        .rating-input .star:hover,
        .rating-input .star:hover ~ .star {
            color: orange;
        }
        /* Style cho sao đã chọn */
        .rating-input .star.selected,
        .rating-input .star.selected ~ .star {
            color: orange;
        }

        /* Khu vực Đánh giá */
        .review-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .review-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 10px;
        }
        .review-form button {
            background: #cd1818;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        
        /* Hiển thị danh sách reviews */
        .review-list {
            margin-top: 20px;
        }
        .review-item {
            border: 1px solid #f0f0f0;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 6px;
        }
        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .review-user-name {
            font-weight: bold;
            color: #333;
        }
        .review-date {
            font-size: 0.8em;
            color: #999;
        }

    </style>
</head>

<body>

    <header>
        <div class="container header-content">
            <div class="logo"><a href="index.php" style="color:white; text-decoration:none;">LAPTOP SHOP</a></div>
            <div class="header-icons">
                <a href="index.php" class="icon-item"><i class="fa-solid fa-house"></i> Trang chủ</a>
                <a href="cart.php" class="icon-item" style="position: relative;">
                    <i class="fa-solid fa-cart-shopping"></i> Giỏ hàng
                    <?php if ($total_qty > 0): ?>
                        <span style="background:yellow; color:red; border-radius:50%; padding:2px 6px; font-size:10px; position:absolute; top:-5px; right:-10px; font-weight:bold;"><?php echo $total_qty; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </header>

    <div class="detail-container">
        <div class="detail-wrapper">
            <div class="detail-left">
                <img src="<?php echo $img; ?>" alt="<?php echo $product['name']; ?>">
            </div>

            <div class="detail-right">
                <h1 class="detail-name"><?php echo $product['name']; ?></h1>
                <div class="detail-price">
                    <?php echo number_format($product['price'], 0, ',', '.'); ?>đ
                    <span class="old-price"><?php echo number_format($product['old_price'], 0, ',', '.'); ?>đ</span>
                </div>

                <div class="detail-specs">
                    <h4><i class="fa-solid fa-gear"></i> Thông số kỹ thuật:</h4>
                    <br>
                    <p><i class="fa-solid fa-microchip"></i> <strong>CPU:</strong> <?php echo $product['cpu']; ?></p>
                    <p><i class="fa-solid fa-memory"></i> <strong>RAM:</strong> <?php echo $product['ram']; ?></p>
                    <p><i class="fa-solid fa-hard-drive"></i> <strong>Ổ cứng:</strong> <?php echo $product['disk']; ?></p>
                    <p><i class="fa-solid fa-gamepad"></i> <strong>VGA:</strong> <?php echo $product['vga'] ? $product['vga'] : 'Onboard'; ?></p>
                    <p><i class="fa-solid fa-desktop"></i> <strong>Màn hình:</strong> <?php echo $product['screen'] ? $product['screen'] : 'Full HD'; ?></p>
                </div>

                <form method="POST" action="cart.php">
                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="name" value="<?php echo $product['name']; ?>">
                    <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                    <input type="hidden" name="img" value="<?php echo $img; ?>">

                    <button type="submit" name="add_to_cart" class="btn-buy-now">
                        <i class="fa-solid fa-cart-plus"></i> THÊM VÀO GIỎ NGAY
                    </button>
                </form>
            </div>
        </div>

        <div class="detail-desc">
            <h3>Đặc điểm nổi bật & Tính năng</h3>
            <div class="desc-content">
                <?php echo $product['description']; ?>
            </div>
        </div>
        <div class="review-section">
            <h2>Đánh giá & Bình luận</h2>
            
            <?php if (isset($_SESSION['user'])): ?>
                <form id="review-form" class="review-form">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="action" value="submit">
                    <input type="hidden" id="rating-value" name="rating" value="0" required>
                    
                    <p>Chọn số sao bạn muốn đánh giá:</p>
                    <div class="rating-input">
                        <i class="fa-solid fa-star star" data-rating="5"></i>
                        <i class="fa-solid fa-star star" data-rating="4"></i>
                        <i class="fa-solid fa-star star" data-rating="3"></i>
                        <i class="fa-solid fa-star star" data-rating="2"></i>
                        <i class="fa-solid fa-star star" data-rating="1"></i>
                    </div>
                    
                    <textarea name="comment" rows="3" placeholder="Viết nhận xét của bạn về sản phẩm này..."></textarea>
                    <button type="submit" id="submit-review-btn">Gửi Đánh Giá</button>
                    <p id="review-message" style="margin-top: 10px;"></p>
                </form>
            <?php else: ?>
                <p>Vui lòng <a href="login.php">Đăng nhập</a> để gửi đánh giá.</p>
            <?php endif; ?>

            <div class="review-list" id="review-list-container">
                <p>Đang tải bình luận...</p>
            </div>
        </div>
    </div>

    <footer>
        <div class="container" style="text-align: center;">
            <p>Hệ thống Laptop Shop - Uy tín số 1</p>
        </div>
    </footer>

    <script>
        $(document).ready(function() {
            const PRODUCT_ID = <?php echo $product['id']; ?>;
            const $ratingInput = $('.rating-input .star');
            let currentRating = 0; // Giá trị sao cuối cùng được chọn
            
            // ---------------------------------------------
            // A. LOGIC HIỂN THỊ SAO (HOVER & CLICK)
            // ---------------------------------------------

            $ratingInput.on('mouseover', function() {
                const rating = $(this).data('rating');
                $ratingInput.each(function() {
                    $(this).removeClass('selected');
                });
                // Tô màu cho sao hiện tại và các sao nhỏ hơn (vì đã dùng direction: rtl)
                $(this).addClass('hover').prevAll().addClass('hover');
                $(this).removeClass('hover').addClass('selected').prevAll().addClass('selected');
            }).on('mouseout', function() {
                // Xóa hiệu ứng hover, trở về trạng thái selected đã lưu
                $ratingInput.removeClass('hover');
                $ratingInput.each(function() {
                    const dataRating = $(this).data('rating');
                    if (dataRating <= currentRating) {
                        $(this).addClass('selected');
                    } else {
                        $(this).removeClass('selected');
                    }
                });
            }).on('click', function() {
                currentRating = $(this).data('rating');
                $('#rating-value').val(currentRating); // Lưu giá trị sao vào hidden input
                
                // Cập nhật trạng thái selected vĩnh viễn
                $ratingInput.removeClass('selected');
                $ratingInput.each(function() {
                    if ($(this).data('rating') <= currentRating) {
                        $(this).addClass('selected');
                    }
                });
            });

            // ---------------------------------------------
            // B. LOGIC GỬI ĐÁNH GIÁ (AJAX)
            // ---------------------------------------------

            $('#review-form').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);
                const $message = $('#review-message');
                const $button = $('#submit-review-btn');
                
                if (currentRating === 0) {
                    $message.html('<span style="color: red;">Vui lòng chọn số sao để đánh giá!</span>');
                    return;
                }
                
                $button.prop('disabled', true).text('Đang gửi...');

                $.ajax({
                    url: 'submit_review.php',
                    type: 'POST',
                    data: $form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $message.html('<span style="color: green;">' + response.message + '</span>');
                            // Tải lại danh sách bình luận sau khi gửi thành công
                            loadReviews();
                            // Xóa form và reset sao
                            $form[0].reset();
                            currentRating = 0;
                            $ratingInput.removeClass('selected');
                            // Ẩn form đánh giá để người dùng không đánh giá lần 2
                            $form.hide(); 
                        } else {
                            $message.html('<span style="color: red;">' + response.message + '</span>');
                        }
                        $button.prop('disabled', false).text('Gửi Đánh Giá');
                    },
                    error: function() {
                        $message.html('<span style="color: red;">Đã xảy ra lỗi khi gửi đánh giá. Vui lòng thử lại.</span>');
                        $button.prop('disabled', false).text('Gửi Đánh Giá');
                    }
                });
            });

            // ---------------------------------------------
            // C. LOGIC TẢI BÌNH LUẬN (AJAX)
            // ---------------------------------------------
            
            function renderStars(rating) {
                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    const color = i <= rating ? 'orange' : '#ccc';
                    starsHtml += '<i class="fa-solid fa-star" style="color: ' + color + ';"></i>';
                }
                return starsHtml;
            }
            
            function loadReviews() {
                $.ajax({
                    url: 'submit_review.php',
                    type: 'GET',
                    data: { action: 'load', product_id: PRODUCT_ID },
                    dataType: 'json',
                    success: function(response) {
                        const $listContainer = $('#review-list-container');
                        $listContainer.empty();
                        
                        if (response.success && response.reviews.length > 0) {
                            // Cập nhật danh sách bình luận
                            response.reviews.forEach(review => {
                                const reviewHtml = `
                                    <div class="review-item">
                                        <div class="review-header">
                                            <div>
                                                <span class="review-user-name">${review.full_name}</span>
                                                <div class="stars-display">${renderStars(review.rating)}</div>
                                            </div>
                                            <span class="review-date">${review.created_at}</span>
                                        </div>
                                        <p>${review.comment}</p>
                                    </div>
                                `;
                                $listContainer.append(reviewHtml);
                            });
                            
                            // Cập nhật Rating trung bình
                            const avgRating = response.average_rating;
                            $('#avg-rating-value').text(avgRating);
                            $('#average-stars').html(renderStars(Math.round(avgRating)));
                            
                        } else {
                            $listContainer.html('<p>Chưa có đánh giá nào cho sản phẩm này.</p>');
                        }
                    },
                    error: function() {
                        $('#review-list-container').html('<p style="color: red;">Không thể tải danh sách đánh giá.</p>');
                    }
                });
            }
            
            // Tải bình luận ngay khi trang được load
            loadReviews();
        });
    </script>

</body>

</html>