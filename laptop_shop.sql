
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 15, 2025 lúc 07:01 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `laptop_shop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`id`, `name`, `slug`) VALUES
(1, 'Apple', 'apple'),
(2, 'ASUS', 'asus'),
(3, 'MSI', 'msi'),
(4, 'Dell', 'dell'),
(5, 'HP', 'hp'),
(6, 'Acer', 'acer'),
(7, 'Lenovo', 'lenovo'),
(8, 'LG', 'lg'),
(9, 'Samsung', 'samsung'),
(10, 'Gigabyte', 'gigabyte'),
(11, 'Surface', 'surface'),
(12, 'Razer', 'razer');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `total_money` decimal(15,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `full_name`, `phone`, `address`, `note`, `total_money`, `status`, `created_at`) VALUES
(1, 2, 'Phan Văn Nam', '0987654321', '27 Trần Ngọc Sương', NULL, 28990000.00, 'Pending', '2025-12-15 17:37:59'),
(2, 2, 'Phan Văn Nam', '0987654321', '123 Đường ABC, Hà Nội', '', 44980000.00, 'Pending', '2025-12-15 17:51:47'),
(3, 2, 'Hứa Công An', '0345848749', 'Thôn Phong Nam, phường Hòa Xuân, TP Đà Nẵng', '', 65990000.00, 'Pending', '2025-12-15 17:53:13'),
(4, 2, 'Đỗ Trọng Chiến', '0905283151', '40 Phạm Vinh', '', 15490000.00, 'Pending', '2025-12-15 17:59:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `product_name`, `price`, `quantity`) VALUES
(1, 1, 39, 'Laptop Gigabyte Aorus 15 9KF-E3VN583SH', 28990000.00, 1),
(2, 2, 26, 'Laptop Surface Laptop Go 2 (i5/8GB/128GB)', 14990000.00, 1),
(3, 2, 17, 'Laptop Samsung Galaxy Book3 Pro 14 inch', 29990000.00, 1),
(4, 3, 20, 'Laptop Razer Blade 15 Advanced Model', 65990000.00, 1),
(5, 4, 24, 'Laptop Lenovo Ideapad Slim 5 Light 14ABR8', 15490000.00, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(15,0) NOT NULL,
  `old_price` decimal(15,0) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `badge` varchar(50) DEFAULT NULL,
  `cpu` varchar(100) DEFAULT NULL,
  `ram` varchar(50) DEFAULT NULL,
  `disk` varchar(50) DEFAULT NULL,
  `vga` varchar(100) DEFAULT NULL,
  `screen` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `brand_id`, `name`, `price`, `old_price`, `image_url`, `badge`, `cpu`, `ram`, `disk`, `vga`, `screen`, `status`, `created_at`, `description`) VALUES
(1, 2, 'Laptop Asus Vivobook 15 OLED A1505VA', 17990000, 19990000, 'uploads/1765285720_69381f58b561c.png', '-10%', 'i5-13500H', '16GB', '512GB', NULL, 'OLED', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(2, 1, 'MacBook Air 13 inch M1 2020 Gray', 18490000, 19990000, 'uploads/1765285678_69381f2eceea2.jpg', '-5%', 'Apple M1', '8GB', '256GB', NULL, 'Retina', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(3, 3, 'Laptop Gaming MSI Thin GF63 12UC', 15990000, 18990000, 'uploads/1765285490_69381e7286f96.jpg', '-15%', 'i5-12450H', '8GB', '512GB', 'RTX 3050', '144Hz', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(4, 6, 'Laptop Acer Aspire 7 Gaming A715', 14990000, 16500000, 'uploads/1765285447_69381e47bad79.png', '-8%', 'Ryzen 5', '8GB', '512GB', NULL, NULL, 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(5, 4, 'Laptop Dell Inspiron 15 3520 (i5/8GB/SSD)', 12490000, 15990000, 'uploads/1765285421_69381e2d591c0.jpg', '-20%', 'i5-1135G7', '8GB', '256GB', NULL, NULL, 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(6, 7, 'Laptop Lenovo Legion 5 15IAH7H', 29990000, 32990000, 'uploads/1765285362_69381df2d46eb.jpg', 'NEW', 'i7-12700H', '16GB', '512GB', 'RTX 3060', '165Hz', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(7, 5, 'Laptop HP Pavilion 15-eg2035TU', 16290000, 17490000, 'uploads/1765285341_69381ddd198e1.jpeg', '-5%', 'i5-1235U', '8GB', '512GB', NULL, NULL, 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(8, 8, 'Laptop LG Gram 2023 14Z90R Siêu nhẹ', 24990000, 28990000, 'uploads/1765285296_69381db0e6835.png', '-12%', 'i7-1360P', '16GB', '512GB', NULL, '14 inch', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(9, 1, 'MacBook Pro 14 inch M3 2023', 39990000, 42000000, 'uploads/1765285267_69381d93a8f17.jpg', 'NEW', 'Apple M3', '16GB', '512GB', NULL, 'Liquid Retina XDR', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(10, 2, 'Laptop Asus TUF Gaming F15 FX506HF', 16490000, 19990000, 'uploads/1765285240_69381d786f0b0.jpg', '-10%', 'i5-11400H', '8GB', '512GB', 'RTX 2050', '144Hz', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(11, 4, 'Laptop Dell XPS 13 Plus 9320', 54990000, 59000000, 'uploads/1765285186_69381d42f2e9a.jpg', 'NEW', 'i7-1360P', '32GB', '1TB', NULL, 'OLED 3.5K', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(12, 5, 'Laptop HP Victus 16-e1106AX Gaming', 19590000, 23000000, 'uploads/1765285157_69381d259af88.jpg', '-15%', 'Ryzen 7', '8GB', '512GB', 'RTX 3050Ti', '144Hz', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(13, 7, 'Laptop Lenovo ThinkBook 14 G3 ACL', 13990000, 15500000, 'uploads/1765285132_69381d0c3becf.jpg', '-10%', 'Ryzen 5', '8GB', '512GB', NULL, NULL, 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(14, 6, 'Laptop Acer Nitro 5 Tiger AN515', 21990000, 24990000, 'uploads/1765285107_69381cf305134.jpg', 'NEW', 'i5-12500H', '8GB', '512GB', 'RTX 3050Ti', '144Hz', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(15, 3, 'Laptop MSI Modern 14 C13M', 11490000, 12990000, 'uploads/1765285060_69381cc429b8a.png', '-5%', 'i3-1315U', '8GB', '512GB', NULL, NULL, 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(16, 2, 'Laptop Asus Zenbook 14 OLED UX3402', 23990000, 26990000, 'uploads/1765285026_69381ca22a7be.jpg', 'NEW', 'i5-1240P', '16GB', '512GB', NULL, '2.8K OLED', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(17, 9, 'Laptop Samsung Galaxy Book3 Pro 14 inch', 29990000, 34990000, 'uploads/1765284996_69381c849375c.png', 'NEW', 'i7-1360P', '16GB', '512GB', NULL, '3K AMOLED', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(18, 10, 'Laptop Gaming Gigabyte G5 MF-F2VN333SH', 20990000, 22990000, 'uploads/1765284974_69381c6eacc01.jpg', '-10%', 'i5-12450H', '8GB', '512GB', 'RTX 4050', '144Hz', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(19, 11, 'Surface Pro 9 Wifi (i5/8GB/256GB) kèm phím', 25590000, 28000000, 'uploads/1765284947_69381c53e52f9.jpg', 'NEW', 'Intel Evo i5', '8GB', '256GB', NULL, 'Touchscreen', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(20, 12, 'Laptop Razer Blade 15 Advanced Model', 65990000, 79000000, 'uploads/1765284917_69381c35bac2f.jpg', 'NEW', 'i7-12800H', '16GB', '1TB', 'RTX 3070 Ti', '240Hz', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(21, 4, 'Laptop Dell Vostro 3520 (V5I3614W1)', 9990000, 11290000, 'uploads/1765284893_69381c1d88125.png', '-8%', 'i3-1215U', '8GB', '256GB', NULL, NULL, 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(22, 6, 'Laptop Acer Aspire 3 A315-59-314F', 8490000, 10990000, 'uploads/1765284858_69381bfaeb499.jpg', 'NEW', 'i3-1215U', '4GB', '512GB SSD', NULL, NULL, 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(23, 3, 'Laptop MSI Katana 15 B13VEK-252VN', 26490000, 29990000, 'uploads/1765284771_69381ba36e272.png', '-15%', 'i7-13620H', '16GB', '512GB', 'RTX 4050', '144Hz', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(24, 7, 'Laptop Lenovo Ideapad Slim 5 Light 14ABR8', 15490000, 17990000, 'uploads/1765284724_69381b7469713.jpg', '-5%', 'Ryzen 5 7530U', '16GB', '512GB', NULL, NULL, 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(25, 5, 'Laptop HP Envy X360 13-bf0090TU', 22990000, 25490000, 'uploads/1765284663_69381b37e7ec9.jpg', 'NEW', 'i7-1250U', '16GB', '512GB', NULL, 'Cảm ứng Xoay 360', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(26, 11, 'Laptop Surface Laptop Go 2 (i5/8GB/128GB)', 14990000, 16990000, 'uploads/1765284624_69381b1037088.jpg', '-10%', 'i5-1135G7', '8GB', '128GB', NULL, '12.4 inch', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(27, 9, 'Samsung Galaxy Book2 Pro 360', 28500000, 32000000, 'uploads/1765284581_69381ae5431ed.jpg', 'NEW', 'i7-1260P', '16GB', '512GB', NULL, 'Cảm ứng S-Pen', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(28, 2, 'Laptop Asus ExpertBook B1400 (Doanh nghiệp)', 11990000, 16990000, 'uploads/1765284538_69381aba03a35.jpg', '-25%', 'i5-1135G7', '8GB', '512GB', NULL, 'Độ bền quân đội', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(29, 7, 'Laptop Lenovo LOQ 15IRH8 Gaming (i5-13420H)', 19990000, 22990000, 'uploads/1765284297_693819c925341.jpg', 'NEW', 'i5-13420H', '16GB', '512GB', 'RTX 3050 6GB', '144Hz', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(30, 6, 'Laptop Acer Nitro V ANV15-51-530H', 24490000, 28990000, 'uploads/1765284263_693819a7dbc9a.png', '-15%', 'i5-13420H', '16GB', '512GB', 'RTX 4050', '144Hz', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(31, 4, 'Laptop Dell Gaming G15 5530 (i7/16GB/512GB)', 29990000, 34500000, 'uploads/1765283998_6938189e0cf16.jpg', 'NEW', 'i7-13650HX', '16GB', '512GB', 'RTX 4050', '165Hz', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(32, 2, 'Laptop Asus ROG Strix G15 G513IE', 32990000, 35990000, 'uploads/1765283865_6938181990069.jpg', 'NEW', 'Ryzen 7', '16GB', '512GB', 'RTX 3050Ti', '144Hz', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(33, 5, 'Laptop HP Omen 16-xf0071AX Gaming', 39990000, 44990000, 'uploads/1765283713_6938178191b50.jpg', 'NEW', 'Ryzen 7-7840HS', '16GB', '1TB', 'RTX 4060', '165Hz QHD', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(34, 6, 'Laptop Acer Swift Go 14 SFG14-71-51CQ', 18990000, 23990000, 'uploads/1765283474_69381692a117a.jpg', '-20%', 'i5-13500H', '16GB', '512GB', NULL, 'OLED 2.8K', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(35, 7, 'Laptop Lenovo Yoga Slim 7 Pro 14IHU5', 21500000, 24000000, 'uploads/1765283372_6938162c0915a.jpeg', 'NEW', 'i5-11300H', '16GB', '512GB', NULL, '90Hz 2.8K', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(36, 4, 'Laptop Dell Latitude 3540 (71021487)', 13990000, 15290000, 'uploads/1765283280_693815d0516d3.jpg', 'NEW', 'i5-1335U', '8GB', '256GB', NULL, NULL, 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n');
INSERT INTO `products` (`id`, `brand_id`, `name`, `price`, `old_price`, `image_url`, `badge`, `cpu`, `ram`, `disk`, `vga`, `screen`, `status`, `created_at`, `description`) VALUES
(37, 3, 'Laptop MSI Raider GE78 HX 13VH', 89990000, 99000000, 'uploads/1765282876_6938143c847fc.png', 'VIP', 'i9-13980HX', '64GB', '2TB', 'RTX 4080', '240Hz QHD+', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(38, 1, 'MacBook Air 13.6 inch M2 2022', 26500000, 28990000, 'uploads/1765282513_693812d133736.jpg', 'NEW', 'Apple M2', '8GB', '256GB', NULL, 'Liquid Retina', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(39, 10, 'Laptop Gigabyte Aorus 15 9KF-E3VN583SH', 28990000, 31990000, 'uploads/1765282462_6938129eafc0c.jpg', '-10%', 'i5-12500H', '16GB', '512GB', 'RTX 4060', '360Hz', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n'),
(40, 7, 'Laptop Lenovo LOQ 15IAX9 (i5-12450HX)', 18490000, 21000000, 'uploads/1765282371_6938124348a4f.jpg', 'NEW', 'i5-12450HX', '12GB', '512GB', 'RTX 3050', '144Hz', 1, '2025-12-02 17:23:47', '<h3>1. Thiết kế hiện đại, sang trọng</h3>\r\n<p>Máy sở hữu ngôn ngữ thiết kế tối giản nhưng đầy tinh tế. Vỏ máy được hoàn thiện từ chất liệu cao cấp, mang lại cảm giác chắc chắn khi cầm nắm nhưng vẫn đảm bảo trọng lượng nhẹ, giúp bạn dễ dàng mang theo bên mình đến bất cứ đâu.</p>\r\n\r\n<h3>2. Hiệu năng mạnh mẽ</h3>\r\n<p>Được trang bị bộ vi xử lý thế hệ mới nhất cùng dung lượng RAM lớn, chiếc laptop này xử lý mượt mà mọi tác vụ từ văn phòng cơ bản (Word, Excel) đến thiết kế đồ họa 2D nhẹ nhàng hay giải trí với các tựa game online phổ biến.</p>\r\n\r\n<h3>3. Màn hình sắc nét</h3>\r\n<ul>\r\n    <li>Kích thước màn hình lớn với viền siêu mỏng.</li>\r\n    <li>Độ phân giải cao mang lại hình ảnh chi tiết, sống động.</li>\r\n    <li>Công nghệ chống chói giúp bảo vệ mắt khi làm việc lâu.</li>\r\n</ul>\r\n\r\n<h3>4. Bàn phím và Touchpad</h3>\r\n<p>Hành trình phím sâu, độ nảy tốt mang lại trải nghiệm gõ phím thoải mái. Touchpad rộng rãi, hỗ trợ cảm ứng đa điểm nhạy bén.</p>\r\n\r\n<h3>5. Kết nối đa dạng</h3>\r\n<p>Trang bị đầy đủ các cổng kết nối thông dụng như USB Type-C, USB 3.0, HDMI... giúp bạn dễ dàng kết nối với các thiết bị ngoại vi.</p>\r\n');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL COMMENT 'Số sao từ 1 đến 5',
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `full_name`, `email`, `phone`, `address`, `role`, `created_at`) VALUES
(1, 'admin', '12345', 'Quản Trị Viên', 'antam105206@gmail.com', NULL, NULL, 'admin', '2025-12-09 08:07:57'),
(2, 'nam', '123456', 'Phan Văn Nam', 'namphan@gmail.com', '0987654321', '123 Đường ABC, Hà Nội', 'user', '2025-12-09 08:07:57');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
