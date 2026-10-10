<?php
// Thông tin kết nối Database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laptop_shop";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thiết lập font chữ tiếng Việt không bị lỗi
$conn->set_charset("utf8");
