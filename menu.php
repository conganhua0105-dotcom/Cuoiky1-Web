<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        background: #f5f5f7;
    }

    .header {
        background: #000;
        color: #fff;
        padding: 0;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .nav-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 20px;
    }

    .logo {
        font-size: 24px;
        font-weight: 600;
        padding: 15px 0;
    }

    .nav-menu {
        display: flex;
        list-style: none;
        gap: 40px;
    }

    .nav-menu a {
        color: #fff;
        text-decoration: none;
        padding: 20px 0;
        display: block;
        font-size: 14px;
        transition: all 0.3s ease;
        opacity: 0.8;
    }

    .nav-menu a:hover,
    .nav-menu a.active {
        opacity: 1;
    }

    .cart-badge {
        background: #ff3b30;
        color: #fff;
        font-size: 11px;
        padding: 2px 6px;
        border-radius: 10px;
        margin-left: 5px;
        font-weight: 700;
    }

    .container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .page-title {
        font-size: 48px;
        font-weight: 700;
        text-align: center;
        margin-bottom: 20px;
        color: #1d1d1f;
    }

    .page-subtitle {
        text-align: center;
        font-size: 21px;
        color: #6e6e73;
        margin-bottom: 50px;
    }
    </style>
</head>

<body>
    <header class="header">
        <nav class="nav-container">
            <div class="logo">Apple Store</div>
            <ul class="nav-menu">
                <li><a href="index.php"
                        class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Trang
                        Chủ</a></li>
                <li><a href="category.php"
                        class="<?php echo basename($_SERVER['PHP_SELF']) == 'category.php' ? 'active' : ''; ?>">Danh
                        Mục</a></li>
                <li><a href="addcart.php"
                        class="<?php echo basename($_SERVER['PHP_SELF']) == 'addcart.php' ? 'active' : ''; ?>">🛒 Giỏ
                        Hàng <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?><span
                            class="cart-badge"><?php echo count($_SESSION['cart']); ?></span><?php endif; ?></a></li>
                <li><a href="admin.php"
                        class="<?php echo basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : ''; ?>">Quản Lý</a>
                </li>
            </ul>
        </nav>
    </header>
</body>

</html>