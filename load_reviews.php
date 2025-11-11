<?php
require_once 'config.php';

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;

$reviews_sql = "SELECT * FROM reviews WHERE product_id = $product_id ORDER BY created_at DESC";
$reviews_result = mysqli_query($conn, $reviews_sql);

if(mysqli_num_rows($reviews_result) > 0):
    while($review = mysqli_fetch_assoc($reviews_result)):
?>
<div class="review-item">
    <div class="review-header">
        <div class="reviewer-info">
            <div class="reviewer-avatar"><?php echo mb_substr($review['customer_name'], 0, 1); ?></div>
            <div>
                <h4><?php echo htmlspecialchars($review['customer_name']); ?></h4>
                <span class="review-date"><?php echo date('d/m/Y H:i', strtotime($review['created_at'])); ?></span>
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
<?php 
    endwhile;
else:
?>
<div class="no-reviews">
    <p>Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá sản phẩm này!</p>
</div>
<?php endif; ?>

<style>
.review-item {
    padding: 25px;
    background: #fafafa;
    border-radius: 12px;
    margin-bottom: 20px;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
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
    color: #e5e5e7;
}

.review-rating .star.filled {
    color: #ffb800;
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
</style>