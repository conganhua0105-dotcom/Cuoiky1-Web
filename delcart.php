<?php
session_start();

// Xóa một sản phẩm cụ thể
if(isset($_GET['delete'])) {
    $product_id = (int)$_GET['delete'];
    
    if(isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    
    header("Location: addcart.php");
    exit();
}

// Xóa toàn bộ giỏ hàng
if(isset($_GET['clear']) && $_GET['clear'] == 'all') {
    $_SESSION['cart'] = [];
    
    header("Location: addcart.php");
    exit();
}
?>