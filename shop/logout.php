<?php
session_start();
session_destroy(); // Xóa sạch session
header("Location: index.php"); // Quay về trang chủ
exit();
