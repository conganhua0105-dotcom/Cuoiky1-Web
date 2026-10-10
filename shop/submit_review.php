<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để đánh giá.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'submit') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $comment = isset($_POST['comment']) ? $conn->real_escape_string(trim($_POST['comment'])) : '';
    $user_id = $_SESSION['user']['id'];
    $full_name = $_SESSION['user']['full_name'];

    if ($product_id <= 0 || $rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Dữ liệu đánh giá không hợp lệ.']);
        exit();
    }

    // 1. Kiểm tra xem người dùng đã đánh giá sản phẩm này chưa
    $check_sql = "SELECT id FROM reviews WHERE product_id = $product_id AND user_id = $user_id";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Bạn đã đánh giá sản phẩm này rồi.']);
        exit();
    }

    // 2. Chèn đánh giá mới vào database
    $sql = "INSERT INTO reviews (product_id, user_id, rating, comment) VALUES ($product_id, $user_id, $rating, '$comment')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode([
            'success' => true, 
            'message' => 'Đánh giá của bạn đã được gửi thành công!',
            'review' => [
                'rating' => $rating,
                'comment' => $comment,
                'full_name' => $full_name,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Lỗi hệ thống khi lưu đánh giá.']);
    }

} else if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action']) && $_GET['action'] == 'load') {
    $product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
    
    $sql = "SELECT r.*, u.full_name FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.product_id = $product_id 
            ORDER BY r.created_at DESC";
    $result = $conn->query($sql);

    $reviews = [];
    $total_ratings = 0;
    $count = 0;
    
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
        $total_ratings += $row['rating'];
        $count++;
    }

    $average_rating = $count > 0 ? round($total_ratings / $count, 1) : 0;
    
    echo json_encode(['success' => true, 'reviews' => $reviews, 'average_rating' => $average_rating]);

} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
}

$conn->close();
?>