<?php
session_start();
require_once 'config.php';

// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Xử lý AJAX thêm đánh giá
if(isset($_POST['ajax_submit_review'])) {
    header('Content-Type: application/json');
    
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $rating = (int)$_POST['rating'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);
    
    if(!empty($customer_name) && !empty($comment) && $rating >= 1 && $rating <= 5) {
        $sql = "INSERT INTO reviews (product_id, customer_name, rating, comment) VALUES ($product_id, '$customer_name', $rating, '$comment')";
        if(mysqli_query($conn, $sql)) {
            // Lấy review vừa thêm
            $review_id = mysqli_insert_id($conn);
            $get_review = "SELECT * FROM reviews WHERE id = $review_id";
            $review_result = mysqli_query($conn, $get_review);
            $new_review = mysqli_fetch_assoc($review_result);
            
            echo json_encode([
                'success' => true,
                'message' => 'Cảm ơn bạn đã đánh giá!',
                'review' => $new_review
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra!']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin!']);
    }
    exit();
}

// Lấy thông tin sản phẩm
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

// Nếu không tìm thấy sản phẩm
if(!$product) {
    header("Location: index.php");
    exit();
}

// Lấy tất cả đánh giá của sản phẩm
$reviews_sql = "SELECT * FROM reviews WHERE product_id = $product_id ORDER BY created_at DESC";
$reviews_result = mysqli_query($conn, $reviews_sql);

// Tính điểm trung bình
$avg_sql = "SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews FROM reviews WHERE product_id = $product_id";
$avg_result = mysqli_query($conn, $avg_sql);
$avg_data = mysqli_fetch_assoc($avg_result);
$avg_rating = $avg_data['avg_rating'] ? round($avg_data['avg_rating'], 1) : 0;
$total_reviews = $avg_data['total_reviews'];

// Lấy sản phẩm liên quan (cùng danh mục)
$related_sql = "SELECT * FROM products WHERE category = '{$product['category']}' AND id != $product_id LIMIT 4";
$related_result = mysqli_query($conn, $related_sql);
?>

<?php include 'menu.php'; ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<div class="container">
    <div id="alertMessage"></div>

    <div class="product-detail">
        <div class="product-image-section">
            <img src="<?php echo htmlspecialchars($product['image']); ?>"
                alt="<?php echo htmlspecialchars($product['name']); ?>" class="main-product-image">
        </div>

        <div class="product-info-section">
            <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>

            <div class="rating-summary">
                <div class="stars">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                    <span class="star <?php echo $i <= $avg_rating ? 'filled' : ''; ?>">★</span>
                    <?php endfor; ?>
                </div>
                <span class="rating-text"><?php echo $avg_rating; ?>/5</span>
                <span class="review-count">(<?php echo $total_reviews; ?> đánh giá)</span>
            </div>

            <p class="product-description-detail"><?php echo htmlspecialchars($product['description']); ?></p>

            <div class="price-section">
                <span class="product-price-large"><?php echo number_format($product['price'], 0, ',', '.'); ?> ₫</span>
            </div>

            <div class="action-buttons">
                <a href="addcart.php?add=<?php echo $product['id']; ?>" class="btn btn-add-cart-large">🛒 Thêm vào giỏ
                    hàng</a>
                <a href="javascript:history.back()" class="btn btn-back">← Quay lại</a>
            </div>

            <div class="product-specs">
                <h3>📋 Thông tin sản phẩm</h3>
                <ul>
                    <li><strong>Mã sản phẩm:</strong> #<?php echo $product['id']; ?></li>
                    <li><strong>Danh mục:</strong>
                        <?php 
                        $categories = [
                            'dien-thoai' => '📱 Điện Thoại',
                            'may-tinh' => '💻 Máy Tính',
                            'tai-nghe' => '🎧 Tai Nghe'
                        ];
                        echo $categories[$product['category']];
                        ?>
                    </li>
                    <li><strong>Ngày thêm:</strong> <?php echo date('d/m/Y', strtotime($product['created_at'])); ?></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Sản phẩm liên quan -->
    <?php if(mysqli_num_rows($related_result) > 0): ?>
    <div class="related-products">
        <h2>Sản phẩm liên quan</h2>
        <div class="product-grid-small">
            <?php while($related = mysqli_fetch_assoc($related_result)): ?>
            <a href="detail.php?id=<?php echo $related['id']; ?>" class="related-product-card">
                <img src="<?php echo htmlspecialchars($related['image']); ?>" alt="">
                <h4><?php echo htmlspecialchars($related['name']); ?></h4>
                <p class="related-price"><?php echo number_format($related['price'], 0, ',', '.'); ?> ₫</p>
            </a>
            <?php endwhile; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Phần đánh giá -->
    <div class="reviews-section">
        <h2>⭐ Đánh giá sản phẩm (<?php echo $total_reviews; ?>)</h2>

        <!-- Form thêm đánh giá -->
        <div class="review-form-container">
            <h3>Viết đánh giá của bạn</h3>
            <form id="reviewForm" class="review-form">
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">

                <div class="form-group">
                    <label>Họ tên</label>
                    <input type="text" name="customer_name" id="customer_name" required
                        placeholder="Nhập họ tên của bạn">
                </div>

                <div class="form-group">
                    <label>Đánh giá</label>
                    <div class="rating-input">
                        <input type="radio" name="rating" value="5" id="star5" required>
                        <label for="star5">★</label>
                        <input type="radio" name="rating" value="4" id="star4">
                        <label for="star4">★</label>
                        <input type="radio" name="rating" value="3" id="star3">
                        <label for="star3">★</label>
                        <input type="radio" name="rating" value="2" id="star2">
                        <label for="star2">★</label>
                        <input type="radio" name="rating" value="1" id="star1">
                        <label for="star1">★</label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Nhận xét</label>
                    <textarea name="comment" id="comment" rows="4" required
                        placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..."></textarea>
                </div>

                <button type="submit" id="submitReview" class="btn btn-primary">Gửi đánh giá</button>
            </form>
        </div>

        <!-- Danh sách đánh giá -->
        <div class="reviews-list" id="reviewsList">
            <?php if(mysqli_num_rows($reviews_result) > 0): ?>
            <?php while($review = mysqli_fetch_assoc($reviews_result)): ?>
            <div class="review-item">
                <div class="review-header">
                    <div class="reviewer-info">
                        <div class="reviewer-avatar"><?php echo mb_substr($review['customer_name'], 0, 1); ?></div>
                        <div>
                            <h4><?php echo htmlspecialchars($review['customer_name']); ?></h4>
                            <span
                                class="review-date"><?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?></span>
                        </div>
                    </div>
                    <div class="review-rating">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                        <span class="star <?php echo $i <= $review['rating'] ? 'filled' : ''; ?>">★</span>
                        <?php endfor; ?>
                    </div>
                </div>
                <p class="review-comment"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
            </div>
            <?php endwhile; ?>
            <?php else: ?>
            <div class="no-reviews">
                <p>Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá sản phẩm này!</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
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

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.product-detail {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    margin-bottom: 60px;
    background: #fff;
    padding: 40px;
    border-radius: 18px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
}

.product-image-section {
    position: sticky;
    top: 100px;
    height: fit-content;
}

.main-product-image {
    width: 100%;
    border-radius: 18px;
    background: #fafafa;
}

.product-info-section {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.product-title {
    font-size: 42px;
    font-weight: 700;
    color: #1d1d1f;
    line-height: 1.2;
}

.rating-summary {
    display: flex;
    align-items: center;
    gap: 15px;
}

.stars {
    display: flex;
    gap: 3px;
}

.star {
    font-size: 24px;
    color: #e5e5e7;
}

.star.filled {
    color: #ffb800;
}

.rating-text {
    font-size: 20px;
    font-weight: 600;
    color: #1d1d1f;
}

.review-count {
    font-size: 16px;
    color: #6e6e73;
}

.product-description-detail {
    font-size: 18px;
    color: #1d1d1f;
    line-height: 1.6;
}

.price-section {
    padding: 20px 0;
    border-top: 2px solid #e5e5e7;
    border-bottom: 2px solid #e5e5e7;
}

.product-price-large {
    font-size: 36px;
    font-weight: 700;
    color: #000;
}

.action-buttons {
    display: flex;
    gap: 15px;
}

.btn-add-cart-large {
    flex: 1;
    padding: 18px 32px;
    background: #000;
    color: #fff;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    text-align: center;
    transition: all 0.3s ease;
}

.btn-add-cart-large:hover {
    background: #333;
    transform: translateY(-2px);
}

.btn-back {
    padding: 18px 32px;
    background: #fff;
    color: #000;
    text-decoration: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    border: 2px solid #e5e5e7;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: #f5f5f7;
}

.product-specs {
    background: #f5f5f7;
    padding: 25px;
    border-radius: 12px;
}

.product-specs h3 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #1d1d1f;
}

.product-specs ul {
    list-style: none;
}

.product-specs li {
    padding: 10px 0;
    border-bottom: 1px solid #e5e5e7;
    font-size: 16px;
    color: #1d1d1f;
}

.product-specs li:last-child {
    border-bottom: none;
}

/* Sản phẩm liên quan */
.related-products {
    margin-bottom: 60px;
}

.related-products h2 {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 30px;
    color: #1d1d1f;
}

.product-grid-small {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.related-product-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
}

.related-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
}

.related-product-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 15px;
    background: #fafafa;
}

.related-product-card h4 {
    font-size: 16px;
    font-weight: 600;
    color: #1d1d1f;
    margin-bottom: 8px;
}

.related-price {
    font-size: 18px;
    font-weight: 700;
    color: #000;
}

/* Phần đánh giá */
.reviews-section {
    background: #fff;
    padding: 40px;
    border-radius: 18px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
    margin-bottom: 60px;
}

.reviews-section h2 {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 30px;
    color: #1d1d1f;
}

.review-form-container {
    background: #f5f5f7;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 40px;
}

.review-form-container h3 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #1d1d1f;
}

.review-form {
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
.form-group textarea {
    padding: 12px 16px;
    border: 2px solid #e5e5e7;
    border-radius: 10px;
    font-size: 14px;
    font-family: inherit;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #000;
}

/* Rating input */
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 5px;
}

.rating-input input {
    display: none;
}

.rating-input label {
    font-size: 36px;
    color: #e5e5e7;
    cursor: pointer;
    transition: all 0.2s ease;
}

.rating-input label:hover,
.rating-input label:hover~label,
.rating-input input:checked~label {
    color: #ffb800;
}

.btn-primary {
    padding: 14px 28px;
    background: #000;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    width: fit-content;
}

.btn-primary:hover {
    background: #333;
    transform: translateY(-2px);
}

/* Danh sách đánh giá */
.reviews-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.review-item {
    padding: 25px;
    background: #fafafa;
    border-radius: 12px;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.reviewer-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.reviewer-avatar {
    width: 50px;
    height: 50px;
    background: #000;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 700;
    text-transform: uppercase;
}

.reviewer-info h4 {
    font-size: 16px;
    font-weight: 600;
    color: #1d1d1f;
    margin-bottom: 5px;
}

.review-date {
    font-size: 13px;
    color: #6e6e73;
}

.review-rating {
    display: flex;
    gap: 3px;
}

.review-rating .star {
    font-size: 18px;
}

.review-comment {
    font-size: 15px;
    line-height: 1.6;
    color: #1d1d1f;
}

.no-reviews {
    text-align: center;
    padding: 60px 20px;
    color: #6e6e73;
}

@media (max-width: 968px) {
    .product-detail {
        grid-template-columns: 1fr;
        gap: 30px;
        padding: 20px;
    }

    .product-image-section {
        position: relative;
        top: 0;
    }

    .product-title {
        font-size: 28px;
    }

    .product-grid-small {
        grid-template-columns: repeat(2, 1fr);
    }

    .action-buttons {
        flex-direction: column;
    }

    .reviews-section {
        padding: 20px;
    }
}
</style>

<script>
$(document).ready(function() {
    loadReviews();

    // Xử lý submit form đánh giá
    $('#reviewForm').on('submit', function(e) {
        e.preventDefault();

        var formData = {
            ajax_submit_review: true,
            customer_name: $('#customer_name').val(),
            rating: $('input[name="rating"]:checked').val(),
            comment: $('#comment').val()
        };

        // Disable button submit
        $('#submitReview').prop('disabled', true).text('Đang gửi...');

        $.ajax({
            url: 'detail.php?id=<?php echo $product_id; ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Hiển thị thông báo thành công
                    $('#alertMessage').html('<div class="alert alert-success">' + response
                        .message + '</div>');

                    // Reset form
                    $('#reviewForm')[0].reset();

                    // Load lại danh sách reviews
                    loadReviews();

                    // Ẩn thông báo sau 3 giây
                    setTimeout(function() {
                        $('#alertMessage').fadeOut(function() {
                            $(this).html('').show();
                        });
                    }, 3000);
                } else {
                    $('#alertMessage').html('<div class="alert alert-error">' + response
                        .message + '</div>');
                }

                // Enable lại button
                $('#submitReview').prop('disabled', false).text('Gửi đánh giá');
            },
            error: function() {
                $('#alertMessage').html(
                    '<div class="alert alert-error">Có lỗi xảy ra. Vui lòng thử lại!</div>'
                );
                $('#submitReview').prop('disabled', false).text('Gửi đánh giá');
            }
        });
    });

    // Hàm load danh sách reviews
    function loadReviews() {
        $.ajax({
            url: 'load_reviews.php',
            type: 'GET',
            data: {
                product_id: <?php echo $product_id; ?>
            },
            success: function(html) {
                $('#reviewsList').html(html);

                // Cập nhật số lượng đánh giá
                var reviewCount = $('.review-item').length;
                $('.reviews-section h2').html('⭐ Đánh giá sản phẩm (' + reviewCount + ')');
            }
        });
    }
});
</script>

</body>

</html>