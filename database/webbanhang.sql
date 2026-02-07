-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th2 07, 2026 lúc 08:09 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `webbanhang`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `type` enum('Admin','Staff') DEFAULT 'Staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `phone_number`, `address`, `status`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Nguyen Van A', 'admin@example.com', '$2y$10$uGO4a6i77l73XKvM9tXVtujkUzqCxkgcPBqP8UTvnPltI43M6r7Ay', '0909123456', '123 Đường ABC, Hà Nội', 'Active', 'Admin', '2025-09-11 14:57:55', '2025-09-11 15:23:53'),
(2, 'Hoàng Vinh', 'vinhy115@gmail.com', '$2y$10$cBdNrcdInk/noZQ499UT0OXzAUvuQHq4jnmBh8N.jOoNu3HtcFTXS', '0123456789', 'Cần Thơ', 'Active', 'Staff', '2025-10-02 12:24:21', '2025-10-02 12:24:21'),
(3, 'Văn B', 'vanb@gmail.com', '$2y$10$rK23/2QjnjSV8u16bWxeT.HSg34/hUN9775mkc0HVHpu05MyzVyKm', '0123456789', 'Cần Thơ', 'Active', 'Staff', '2025-12-11 15:54:03', '2025-12-11 15:54:03');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brand`
--

CREATE TABLE `brand` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `brand`
--

INSERT INTO `brand` (`id`, `name`, `slug`, `status`) VALUES
(1, 'ADIDAS', 'adidas', ''),
(2, 'SNEAKERS', 'sneakers', ''),
(3, 'MT', 'mt', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `gender_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT INTO `category` (`id`, `name`, `slug`, `status`, `gender_id`) VALUES
(1, 'Giày tây', 'gi-y-t-y', '', 1),
(10, 'Giày cao gót', 'giay-cao-got', '1', 2),
(11, 'Sneakers nữ', 'sneakers-n-', '1', 2),
(12, 'Giày boot nam', 'gi-y-boot-nam', '1', 1),
(13, 'Sneakers nam', 'sneakers-nam', '1', 1),
(14, 'Giày boot nữ', 'gi-y-boot-n-', '1', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(250) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `response` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `phone_number`, `note`, `response`, `created_at`, `updated_at`) VALUES
(1, 'vinh', 'vinhy115@gmail.com', '0123456789', 'Chất lượng sản phẩm qua đã', 'jjj', '2025-11-11 10:36:43', '2025-12-11 16:26:29'),
(2, 'Nguyễn Văn A', 'user@gmail.com', '0123456789', 'abc', NULL, '2025-12-06 07:40:48', '2025-12-06 07:40:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `gender`
--

CREATE TABLE `gender` (
  `id` int(11) NOT NULL,
  `gt` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `gender`
--

INSERT INTO `gender` (`id`, `gt`) VALUES
(1, 'Nam'),
(2, 'Nữ');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `thumbnails` varchar(500) DEFAULT NULL,
  `summary` longtext DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `newscategory_id` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `news`
--

INSERT INTO `news` (`id`, `title`, `slug`, `thumbnails`, `summary`, `description`, `newscategory_id`, `status`, `created_at`, `updated_at`) VALUES
(3, 'Gợi ý chọn giày diện Tết: Từ giày tây lịch lãm đến boot, sneaker cho cả nhà', 'g-i-ch-n-gi-y-di-n-t-t-t-gi-y-t-y-l-ch-l-m-n-boot-sneaker-cho-c-nh-', 'uploads/news/6931534e5dd5f79cffcce-7b52-47cb-8a78-75af08ab3b00.png', 'Tết là dịp làm mới bản thân từ trang phục đến đôi giày trên chân. Bộ sưu tập giày tây HO103, HO2216, MTUY6634, LTG4768, THUS851-20, các mẫu boot AK90010, AK9981, AK83-17, AK129-1 cùng giày thể thao AKSK09-8, MTY73572 mang đến nhiều lựa chọn cho cả nam và nữ. Hãy cùng khám phá để chọn cho mình đôi giày đẹp, êm chân, tự tin du xuân và chúc Tết người thân, bạn bè.', 'Tết luôn là thời điểm lý tưởng để “làm mới” phong cách, chuẩn bị những bộ trang phục chỉn chu nhất cho những ngày đầu năm. Bên cạnh áo quần, một đôi giày đẹp – êm chân sẽ giúp bạn tự tin hơn trong từng bước đi chúc Tết, đi chơi, du xuân hay gặp gỡ đối tác đầu năm. Dưới đây là gợi ý những mẫu giày nổi bật đang có tại cửa hàng, phù hợp cho nhiều nhu cầu khác nhau.\r\n1. Giày tây – Lịch lãm ngày đầu năm cho quý ông\r\nGiày tây HO103 & HO2216\r\nHai mẫu giày tây màu nâu với thiết kế tối giản, sang trọng, dễ phối với quần tây, sơ mi hoặc vest. Chất liệu da bền đẹp, đế êm, thích hợp cho các buổi họp mặt gia đình, gặp gỡ đối tác hay dự tiệc cưới đầu năm.\r\nGiày tây MTUY6634 & LTG4768\r\nThiết kế buộc dây cổ điển, form thon gọn giúp tôn dáng bàn chân. Tông màu trung tính, phù hợp với phong cách công sở hoặc smart-casual. Đây là lựa chọn lý tưởng cho anh em cần một đôi giày có thể dùng xuyên suốt từ Tết đến đi làm hằng ngày.\r\nGiày tây THUS851-20\r\nMẫu giày đen lịch lãm, dễ phối đồ nhất trong tủ. Đế êm, bám tốt, phù hợp cho những ai phải di chuyển nhiều nhưng vẫn cần vẻ ngoài chuyên nghiệp trong các buổi gặp gỡ đầu năm.\r\n2. Boot nam – Ấm áp, phong trần cho chuyến du xuân\r\nGiày boot nam AK90010 & AK9981\r\nDáng boot cổ lửng, chất liệu bền chắc, đế rãnh sâu chống trượt tốt, phù hợp cho các chuyến đi chơi, du lịch, leo núi nhẹ hoặc chụp hình ngoài trời. Tông nâu vàng, nâu đất dễ phối với quần jean, kaki và áo khoác.\r\nGiày boot nam AK83-17\r\nThiết kế bụi bặm hơn, thích hợp cho anh em thích phong cách streetwear. Kết hợp cùng quần jean rách, áo thun, áo khoác là đã có ngay outfit du xuân cực chất.\r\n3. Boot nữ – Cá tính, “hack” dáng cho nàng\r\nGiày boot nữ AK83-17 & AK129-1\r\nDáng boot cổ lửng, đế cao chunky giúp tôn dáng và “hack” chiều cao hiệu quả. Tông đen dễ phối với chân váy, đầm hoặc quần skinny. Đây là lựa chọn hoàn hảo cho các buổi đi chơi, dạo phố, cafe hay chụp ảnh Tết với bạn bè.\r\nVới các nàng yêu sự cá tính nhưng vẫn muốn mang êm chân cả ngày, hai mẫu boot này là gợi ý không nên bỏ qua.\r\n4. Giày thể thao – Thoải mái cho những ngày Tết năng động\r\nGiày thể thao nam AKSK09-8\r\nThiết kế trẻ trung, năng động, đế êm nhẹ, phù hợp đi chơi, dạo phố, lái xe hoặc du lịch dài ngày. Tông màu trầm dễ phối với nhiều kiểu trang phục, từ quần short đến quần jean.\r\nGiày thể thao nữ MTY73572\r\nMẫu slip-on vải dệt màu đen đế trắng, mang vào là êm, cực kỳ thoải mái cho những ngày Tết phải di chuyển nhiều. Thiết kế đơn giản nhưng hiện đại, có thể phối với jean, legging hay váy năng động đều đẹp.\r\n5. Gợi ý chọn giày diện Tết\r\nƯu tiên giày êm, nhẹ, phù hợp với hoạt động di chuyển nhiều.\r\nChọn màu sắc dễ phối đồ: đen, nâu, nâu đất… để dùng được cả sau Tết.\r\nVới các buổi tiệc, gặp đối tác: chọn giày tây HO103, HO2216, LTG4768, THUS851-20.\r\nVới du lịch, đi chơi ngoài trời: ưu tiên boot AK90010, AK9981, AK83-17 hoặc giày thể thao AKSK09-8, MTY73572.\r\nCác nàng thích phong cách cá tính, lên hình đẹp: đừng bỏ qua boot nữ AK83-17, AK129-1.\r\n                                 ', 4, NULL, '2025-12-04 09:24:30', '2025-12-04 09:24:30'),
(4, 'Ra mắt bộ sưu tập giày mới – Nâng cấp phong cách từ công sở đến đường phố', 'ra-m-t-b-s-u-t-p-gi-y-m-i-n-ng-c-p-phong-c-ch-t-c-ng-s-n-ng-ph-', 'uploads/news/693154c4127ffbe8f83fd-b3c6-4af5-a410-a0510d447b6f.png', 'Bộ sưu tập giày mới vừa cập bến với nhiều mẫu giày tây, boot và giày thể thao dành cho cả nam lẫn nữ. Từ giày tây HO2216, MTUY6634 lịch lãm, đến các mẫu boot cá tính AK83-17, AK129-1 và sneaker AKSK09-8, MTY73572, tất cả đều chú trọng độ êm, form đẹp và dễ phối đồ. Đây là thời điểm lý tưởng để bạn “refresh” tủ giày, sẵn sàng cho những ngày đi làm, đi chơi và du lịch sắp tới.', 'Nhằm mang đến nhiều lựa chọn hơn cho khách hàng, cửa hàng vừa cập nhật loạt sản phẩm giày mới với thiết kế hiện đại, dễ đi và phù hợp nhiều phong cách khác nhau. Bộ sưu tập lần này tập trung vào 3 nhóm chính: giày tây, boot thời trang và giày thể thao.\r\n\r\n1. Giày tây mới – Chỉn chu hơn mỗi ngày\r\n\r\nHO2216, MTUY6634, LTG4768, THUS851-20\r\nCác mẫu giày tây mới sở hữu form thon gọn, đường may tinh tế, tông màu đen – nâu sang trọng. Chất liệu da bền đẹp, đế êm hỗ trợ di chuyển nhiều giờ liền mà vẫn thoải mái.\r\n\r\nPhù hợp cho:\r\n\r\nĐi làm công sở, gặp gỡ khách hàng\r\n\r\nDự tiệc, họp quan trọng, phỏng vấn\r\n\r\nNhững anh em cần một đôi giày có thể mang từ ngày thường đến dịp trang trọng sẽ rất hợp với nhóm sản phẩm này.\r\n\r\n2. Boot nam & nữ – Cá tính, dễ phối outfit\r\n\r\nBoot nam AK90010, AK9981\r\nThiết kế cổ lửng khỏe khoắn, đế rãnh sâu chống trượt, thích hợp cho những buổi đi chơi, du lịch, chụp hình ngoài trời.\r\n\r\nBoot nữ AK83-17, AK129-1\r\nDáng boot cổ lửng, đế chunky cao vừa phải, giúp tôn dáng và “hack” chiều cao hiệu quả. Tông đen dễ phối với jean, chân váy hay đầm, rất phù hợp style cá tính, street style.\r\n\r\nNếu bạn đang tìm một đôi giày vừa thời trang, vừa đủ ấm cho những ngày mưa hoặc đi chơi tối, các mẫu boot mới chắc chắn là lựa chọn đáng thử.\r\n\r\n3. Giày thể thao – Thoải mái cho mọi hoạt động\r\n\r\nAKSK09-8 (nam)\r\nSneaker đế êm, chống trượt, tông màu nam tính, dễ phối với quần jean, kaki hay jogger. Phù hợp đi học, đi làm casual, dạo phố cuối tuần.\r\n\r\nMTY73572 (nữ)\r\nMẫu slip-on vải dệt đế trắng siêu nhẹ, mang vào là êm, cực tiện cho những bạn gái thích sự gọn gàng, không tốn thời gian buộc dây. Rất hợp cho đi bộ, đi chơi, shopping cả ngày.\r\n\r\n4. Vì sao bạn không nên bỏ lỡ bộ sưu tập mới?\r\n\r\nThiết kế hiện đại, dễ phối với đồ sẵn có trong tủ quần áo\r\n\r\nChú trọng độ êm, độ bền, phù hợp người phải di chuyển nhiều\r\n\r\nNhiều lựa chọn cho cả nam – nữ, từ công sở đến dạo phố\r\n\r\nGiá vẫn giữ ở mức dễ tiếp cận, ưu đãi đặc biệt cho đợt ra mắt đầu tiên\r\n                                    ', 3, NULL, '2025-12-04 09:30:44', '2025-12-04 09:30:44'),
(5, 'Xu hướng giày 2025: Ưu tiên êm chân, đa dụng và dễ phối đồ', 'xu-h-ng-gi-y-2025-u-ti-n-m-ch-n-a-d-ng-v-d-ph-i-', 'uploads/news/693157a22bb5962af1aed-ae29-4cdb-b4c3-ffd0a9be3bc4.png', '  Năm 2025, xu hướng giày tập trung vào sự thoải mái và tính đa dụng: giày tây tối giản, boot đế chunky cá tính và sneaker nhẹ, êm chân. Các mẫu như giày tây HO2216, LTG4768, THUS851-20, boot AK83-17, AK129-1 hay giày thể thao AKSK09-8, MTY73572 đang được nhiều khách hàng lựa chọn vì vừa đẹp, vừa dễ phối với nhiều phong cách từ công sở đến dạo phố.\r\n                                    \"\r\n                                   \r\n                                            ', ' Trong vài mùa gần đây, thời trang giày chuyển dần từ “chỉ đẹp” sang đẹp nhưng phải êm và dễ đi. Người dùng ngày càng ưu tiên những đôi giày có thể mang từ sáng đến tối mà vẫn thoải mái, đồng thời phối được với nhiều kiểu trang phục khác nhau. Dưới đây là 3 xu hướng giày nổi bật mà bạn dễ dàng bắt gặp trong năm 2025.\r\n\r\n1. Giày tây tối giản – Lịch lãm nhưng không cứng nhắc\r\n\r\nNhững đôi giày tây với thiết kế đơn giản, ít chi tiết rườm rà đang chiếm ưu thế. Form thon gọn, tông màu đen – nâu cơ bản giúp dễ phối với quần tây, sơ mi, vest nhưng cũng có thể mix cùng quần jean tối màu cho phong cách smart-casual.\r\n\r\nTại cửa hàng, các mẫu như HO2216, MTUY6634, LTG4768, THUS851-20 được yêu thích nhờ:\r\n\r\nThiết kế gọn, hiện đại, không quá “già”.\r\n\r\nĐế êm, mang đi làm cả ngày vẫn thoải mái.\r\n\r\nPhối được nhiều outfit: dự tiệc, đi làm, gặp khách hàng…\r\n\r\nXu hướng chung: một đôi giày tây nhưng “đa nhiệm”, dùng được cho nhiều dịp khác nhau.\r\n\r\n2. Boot đế chunky – “Vũ khí” hack dáng cho phái nữ, cá tính cho phái nam\r\n\r\nBoot đế dày, phom cứng cáp tiếp tục là item hot trong tủ đồ của các bạn trẻ. Không chỉ lên hình đẹp, boot còn dễ phối từ quần jean, kaki đến chân váy, đầm.\r\n\r\nBoot nữ AK83-17, AK129-1:\r\n\r\nĐế chunky cao vừa phải, giúp tôn dáng, kéo dài chân mà vẫn chắc chắn.\r\n\r\nTông đen basic, phối với đồ nào cũng hợp – từ váy ôm đến chân váy xòe, jean rách…\r\n\r\nBoot nam AK90010, AK9981:\r\n\r\nDáng khỏe khoắn, phù hợp phong cách streetwear, đi du lịch, chụp hình ngoài trời.\r\n\r\nĐế rãnh sâu, bám tốt, mang vừa thời trang vừa thực dụng.\r\n\r\nXu hướng boot năm nay thiên về cá tính nhưng không quá “hầm hố”, vẫn giữ được sự gọn gàng để mang hằng ngày.\r\n\r\n3. Sneaker nhẹ – Linh hoạt từ đi học, đi làm đến dạo phố\r\n\r\nKhi lịch trình ngày càng bận rộn, những đôi sneaker nhẹ, êm, dễ mang trở thành lựa chọn số 1 cho cả nam và nữ.\r\n\r\nAKSK09-8 (nam):\r\n\r\nThiết kế thể thao năng động, đế êm, hợp đi học, đi làm casual và dạo phố cuối tuần.\r\n\r\nMTY73572 (nữ):\r\n\r\nMẫu slip-on vải dệt đế trắng siêu nhẹ, chỉ cần xỏ chân là xong, không mất thời gian buộc dây.\r\n\r\nPhù hợp với jean, legging, váy thể thao – rất hợp style “năng động nhưng đơn giản”.\r\n\r\nĐiểm chung của xu hướng sneaker là tối giản chi tiết, tập trung vào độ êm và sự tiện lợi.\r\n\r\n4. Cách bắt trend nhưng vẫn “hợp mình”\r\n\r\nChọn giày phù hợp môi trường sử dụng chính: công sở → giày tây; đi học/đi chơi → sneaker; thích cá tính → boot.\r\n\r\nƯu tiên màu trung tính (đen, nâu, kem…) để dễ phối với đồ sẵn có.\r\n\r\nThử giày kỹ để đảm bảo form vừa chân, đế đủ êm, vì xu hướng lớn nhất bây giờ là… không hy sinh sự thoải mái chỉ để đẹp.\r\n                                    \"\r\n                                        \r\n                                            ', 5, NULL, '2025-12-04 09:38:43', '2025-12-04 09:42:58'),
(6, '🔥 KHUYẾN MÃI SỐC – GIÀY BOOT NỮ AK1908 GIẢM NGAY 22%! 🔥 ', '-khuy-n-m-i-s-c-gi-y-boot-n-ak1908-gi-m-ngay-22-', 'uploads/news/6933e8d6bd4ef0005b095-ea91-4b51-9e5c-df3de91739ce.png', ' Nhằm tri ân khách hàng và chào đón mùa thời trang mới, hệ thống MT Shoes chính thức triển khai chương trình khuyến mãi đặc biệt cho mẫu giày boot nữ AK1908 – một trong những thiết kế bán chạy và được yêu thích nhất.', '✨ Giảm giá trực tiếp 22% – Duy nhất trong tuần này!\r\n\r\nGiày boot AK1908 gây ấn tượng với:\r\n\r\nThiết kế thời thượng, phong cách thanh lịch phù hợp nhiều trang phục.\r\n\r\nChất liệu da mềm mịn, ôm chân thoải mái.\r\n\r\nĐế block vững chắc, giúp di chuyển êm ái cả ngày.\r\n\r\nForm dáng tôn chân, sang trọng, cực hợp cho outfit mùa lạnh.\r\n\r\n🎁 Áp dụng khi nào?\r\n\r\nThời gian: từ hôm nay đến hết tuần\r\n\r\nÁp dụng tại: tất cả chi nhánh và website MT Shoes\r\n\r\n💥 Đừng bỏ lỡ!\r\n\r\nSố lượng có hạn. Đây là cơ hội tuyệt vời để sở hữu đôi boot xịn – đẹp – sang với giá cực ưu đãi. Nhấn “Mua ngay” để đặt hàng và nhận ưu đãi trước khi kết thúc!\r\n                                    ', 6, NULL, '2025-12-06 08:27:02', '2025-12-06 08:27:02'),
(7, 'KHUYẾN MÃI HẤP DẪN – GIÀY BOOT AK83-17 GIẢM NGAY 18%', 'khuy-n-m-i-h-p-d-n-gi-y-boot-ak83-17-gi-m-ngay-18-', 'uploads/news/6941151cbe899demo.png', '\r\n                         Nhằm mang đến cho khách hàng cơ hội sở hữu những mẫu giày thời trang với mức giá tốt nhất, cửa hàng chính thức triển khai chương trình khuyến mãi đặc biệt dành cho giày boot AK83-17.           ', '\r\n                              Ưu đãi nổi bật:\r\n•	Giảm ngay 18% cho mẫu giày boot AK83-17\r\n•	Thiết kế hiện đại, mạnh mẽ, dễ phối đồ\r\n•	Chất liệu bền đẹp, form chuẩn, mang êm chân\r\n•	Phù hợp đi làm, đi chơi và thời tiết thu – đông\r\nThời gian áp dụng: Có hạn – số lượng có giới hạn\r\nĐừng bỏ lỡ cơ hội sở hữu giày boot AK83-17 với mức giá ưu đãi cực tốt ngay hôm nay!\r\n      ', 6, NULL, '2025-12-16 08:15:24', '2025-12-16 08:15:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `newscategory`
--

CREATE TABLE `newscategory` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `newscategory`
--

INSERT INTO `newscategory` (`id`, `name`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Giày Nữ', 'gi-y-n-', '', '2025-09-11 15:29:23', '2025-12-14 11:19:27'),
(2, 'Giày nam ', 'gi-y-nam-', '', '2025-12-04 09:10:51', '2025-12-04 09:10:51'),
(3, 'Giày mới ra mắt', 'gi-y-m-i-ra-m-t', '', '2025-12-04 09:11:15', '2025-12-04 09:11:15'),
(4, 'Tết này mang gì???', 't-t-n-y-mang-g-', '', '2025-12-04 09:11:40', '2025-12-04 09:11:40'),
(5, 'Xu hướng thời trang', 'xu-h-ng-th-i-trang', '', '2025-12-04 09:32:21', '2025-12-04 09:32:21'),
(6, 'Sale sập sàn', 'sale-s-p-s-n', '', '2025-12-06 08:19:51', '2025-12-06 08:19:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `note` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(50) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT 'Pending',
  `shipping_status` varchar(50) DEFAULT 'Pending',
  `estimated_delivery` varchar(255) DEFAULT 'Chưa xác định'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `firstname`, `lastname`, `email`, `phone_number`, `address`, `note`, `created_at`, `updated_at`, `status`, `payment_status`, `shipping_status`, `estimated_delivery`) VALUES
(1, 1, 'JK,JK', 'HJ, G ', 'vinhy115@gmail.com', '0367070318', 'UYGM GM', '', '2025-09-11 15:31:22', '2025-10-02 19:20:01', 'Delivered', 'Paid', 'Pending', ''),
(2, 1, 'Hoàng', 'Vinh', 'vinhy115@gmail.com', '0123456789', 'Cần Thơ', 'ja', '2025-11-11 09:26:35', '2025-11-11 16:26:35', 'Processing', 'Pending', 'Pending', 'Chưa xác định'),
(3, 1, 'Hoàng', 'Vinh', 'vinhy115@gmail.com', '0123456789', 'Cần Thơ', '', '2025-11-11 09:43:41', '2025-12-11 19:13:43', 'Processing', 'Pending', 'Pending', NULL),
(4, 1, 'Hoàng', 'Vinh', 'admin@example.com', '0123456789', 'Cần Thơ', '', '2025-11-11 10:02:53', '2025-12-11 19:13:30', 'Cancelled', 'Pending', 'Pending', NULL),
(5, 1, 'Nguyễn Văn', 'A', 'user@gmail.com', '0123456789', '345y Cần Thơ', '', '2025-12-06 07:35:51', '2025-12-14 18:05:00', 'Delivered', 'Paid', 'Pending', NULL),
(6, 1, 'Hoàng', 'Vinh', 'user@gmail.com', '0123456789', 'Cần Thơ', 'ja', '2025-12-14 10:47:54', '2025-12-14 17:47:54', 'Processing', 'Pending', 'Pending', 'Chưa xác định'),
(7, 1, 'Hoàng', 'Vinh', 'vinhy115@gmail.com', '0123456789', 'Cần Thơ', 'ja', '2025-12-16 08:13:38', '2025-12-16 15:13:38', 'Processing', 'Pending', 'Pending', 'Chưa xác định');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `size_id` int(11) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `product_name` varchar(200) DEFAULT NULL,
  `product_image` varchar(255) DEFAULT NULL,
  `price` int(11) NOT NULL CHECK (`price` >= 0),
  `num` int(11) NOT NULL CHECK (`num` >= 0),
  `total` int(11) NOT NULL CHECK (`total` >= 0),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `size_id`, `size`, `product_name`, `product_image`, `price`, `num`, `total`, `created_at`, `updated_at`) VALUES
(2, 2, 6, NULL, NULL, NULL, NULL, 229000, 1, 229000, '2025-11-11 09:26:35', '2025-11-11 09:26:35'),
(3, 3, 9, NULL, NULL, NULL, NULL, 1190000, 1, 1190000, '2025-11-11 09:43:41', '2025-11-11 09:43:41'),
(4, 4, 4, 11, '29', NULL, NULL, 890000, 2, 1780000, '2025-11-11 10:02:53', '2025-11-11 10:02:53'),
(5, 5, 14, 68, '42', NULL, NULL, 1890000, 1, 1890000, '2025-12-06 07:35:51', '2025-12-06 07:35:51'),
(6, 6, 13, 58, '39', NULL, NULL, 1360000, 1, 1360000, '2025-12-14 10:47:54', '2025-12-14 10:47:54'),
(7, 1, 6, NULL, NULL, NULL, NULL, 229000, 1, 229000, '2025-12-14 11:11:36', '2025-12-14 11:11:36'),
(8, 7, 20, 275, '41', NULL, NULL, 890000, 1, 890000, '2025-12-16 08:13:39', '2025-12-16 08:13:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `price` int(11) NOT NULL CHECK (`price` >= 0),
  `discount` int(11) DEFAULT NULL,
  `thumbnail` varchar(500) DEFAULT NULL,
  `summary` longtext DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`id`, `category_id`, `brand_id`, `name`, `slug`, `price`, `discount`, `thumbnail`, `summary`, `description`, `created_at`, `updated_at`) VALUES
(4, 1, 3, 'Giày tây HO2216', 'gi-y-t-y-ho2216', 800000, 750000, 'uploads/HO2216-1--1763559695-0.png;uploads/HO2216-2--1763559695-1.png;uploads/HO2216-1763559695-2.png', 'Giày tây HO2216 thiết kế lịch lãm, mũi giày thon gọn, chất liệu da bóng sang trọng. Đế êm, chống trượt tốt, phù hợp đi làm, gặp đối tác và dự tiệc.', 'Giày tây HO2216 là lựa chọn dành cho quý ông yêu phong cách chỉn chu, hiện đại. Thiết kế tối giản nhưng tinh tế, phù hợp với nhiều kiểu trang phục công sở và trang trọng.\r\nĐặc điểm nổi bật:\r\nChất liệu:\r\n-Thân giày làm từ da tổng hợp/da bò (bạn sửa lại cho đúng loại), bề mặt bóng nhẹ, khó bám bẩn.\r\n-Lót trong mềm, thoáng khí, hạn chế hầm bí khi mang lâu.\r\nThiết kế:\r\n-Dáng giày ôm vừa chân, mũi tròn thon tạo cảm giác thanh lịch.\r\n-Đường may gọn gàng, form cứng cáp giữ dáng tốt sau thời gian sử dụng.\r\n-Thiết kế xỏ chân (slip-on)/buộc dây (bạn chỉnh lại), giúp mang vào và tháo ra nhanh chóng.\r\nĐế giày:\r\n-Đế cao su/TPC chống trơn trượt (chỉnh lại nếu cần), tăng độ bám trên nhiều bề mặt.\r\n-Lớp lót đế êm, hỗ trợ giảm lực tác động khi di chuyển, phù hợp người phải đứng hoặc đi lại nhiều.\r\nỨng dụng:\r\n-Dễ phối với quần tây, sơ mi, vest, quần kaki.\r\n-Thích hợp đi làm văn phòng, dự họp, thuyết trình, phỏng vấn, tiệc cưới và các sự kiện trang trọng.\r\nHướng dẫn bảo quản:\r\n-Tránh ngâm nước, hạn chế đi mưa lâu.\r\n-Vệ sinh bằng khăn mềm, dùng xi/kem dưỡng da giày để giữ được độ bóng và màu sắc ban đầu.', '2025-09-30 14:11:06', '2025-12-04 09:07:46'),
(5, 1, 3, 'Giày tây MTUY6634', 'gi-y-t-y-mtuy6634', 560000, 530000, 'uploads/MTUY6634-1--1763562922-0.png;uploads/MTUY6634-2--1763562922-1.png;uploads/MTUY6634-1763562922-2.png', 'Giày tây MTUY6634 kiểu loafer xỏ chân tiện lợi, dáng thon gọn lịch lãm. Chất liệu da tổng hợp cao cấp, bề mặt bóng nhẹ sang trọng, đế êm chống trượt, phù hợp môi trường công sở và các dịp trang trọng.', 'Giày tây MTUY6634 là mẫu giày loafer hiện đại dành cho quý ông yêu thích sự gọn gàng, chỉn chu nhưng vẫn thoải mái khi di chuyển cả ngày. Thiết kế tối giản, tông màu trầm dễ phối đồ, phù hợp từ đi làm văn phòng đến gặp đối tác hay dự tiệc.\r\nĐặc điểm nổi bật:\r\nChất liệu:\r\n-Thân giày làm từ da tổng hợp cao cấp, bề mặt bóng nhẹ, hạn chế bám bẩn và dễ lau chùi.\r\n-Lót trong mềm, thoáng khí, giúp chân luôn thoải mái, giảm cảm giác hầm bí khi mang lâu.\r\nThiết kế:\r\n-Dáng loafer xỏ chân (slip-on) tiện lợi, mang vào và tháo ra nhanh chóng.\r\n-Mũi giày tròn thon, tạo cảm giác thanh lịch và kéo dài dáng chân.\r\n-Đường may tỉ mỉ, form giày cứng cáp, giữ dáng tốt sau thời gian sử dụng.\r\nĐế giày:\r\n-Đế cao su bám tốt, chống trơn trượt trên nhiều bề mặt.\r\n-Đế êm, có độ đàn hồi, hỗ trợ giảm lực tác động lên bàn chân khi đứng hoặc đi lại nhiều.\r\nỨng dụng:\r\n-Dễ dàng phối cùng quần tây, sơ mi, vest, quần kaki…\r\n-Phù hợp đi làm văn phòng, dự họp, gặp khách hàng, dự tiệc cưới và các sự kiện trang trọng.\r\nHướng dẫn bảo quản:\r\n-Tránh để giày tiếp xúc nước quá lâu hoặc ngâm nước.\r\n-Vệ sinh giày bằng khăn mềm, để nơi khô thoáng; có thể dùng xi/kem dưỡng da giày định kỳ để giữ độ bóng đẹp và bền màu.', '2025-09-30 14:14:28', '2025-12-04 09:06:03'),
(6, 1, 3, 'Giày tây LTG4768', 'gi-y-t-y-ltg4768', 570000, 530000, 'uploads/LTG4768-1--1763564421-0.png;uploads/LTG4768-2--1763564421-1.png;uploads/LTG4768-1763564421-2.png', 'Giày tây LTG4768 mang phong cách lịch lãm, tối giản nhưng sang trọng. Thiết kế dáng thon gọn, màu trung tính dễ phối đồ, phù hợp cho quý ông công sở, gặp gỡ đối tác hay dự các sự kiện trang trọng.', 'Giày tây LTG4768 là lựa chọn lý tưởng cho những ai yêu thích sự chỉn chu và tinh tế trong từng bước chân. Form giày ôm vừa vặn, tạo cảm giác gọn gàng và tôn dáng, đồng thời vẫn đảm bảo êm ái khi mang cả ngày.\r\nĐặc điểm nổi bật:\r\nChất liệu:\r\n-Thân giày làm từ da tổng hợp cao cấp, bề mặt mịn, khó bám bẩn, dễ lau chùi.\r\n-Lót trong êm, thoáng khí, giảm hầm bí và hạn chế ma sát cho bàn chân.\r\nThiết kế:\r\n-Phong cách giày tây cổ điển, phù hợp môi trường văn phòng và trang trọng.\r\n-Mũi giày thon nhẹ, giúp bàn chân trông dài và thanh lịch hơn.\r\n-Đường may tinh gọn, chắc chắn, giữ form giày ổn định sau thời gian sử dụng.\r\nĐế giày:\r\n-Đế cao su bám tốt, chống trơn trượt trên nhiều bề mặt.\r\n-Đệm đế êm, hỗ trợ giảm áp lực lên gót và lòng bàn chân khi đứng hoặc di chuyển nhiều.\r\nỨng dụng:\r\n-Dễ dàng phối với quần tây, sơ mi, vest, hoặc quần kaki cho phong cách smart-casual.\r\n-Phù hợp đi làm, họp hành, gặp khách hàng, dự tiệc cưới và các buổi họp mặt quan trọng.\r\nHướng dẫn bảo quản:\r\n-Tránh ngâm nước hoặc để giày trong môi trường ẩm ướt quá lâu.\r\n-Vệ sinh giày bằng khăn mềm, cất nơi khô thoáng; có thể dùng xi/kem dưỡng giày định kỳ để giữ độ bóng đẹp và tăng độ bền.', '2025-09-30 14:15:45', '2025-12-04 09:08:11'),
(7, 11, 2, 'GIÀY SNEAKERS NỮ CHERRYLOVE', 'gi-y-sneakers-n-cherrylove', 1250000, 1190000, 'uploads/68e3b6d9027f6_nu1.jpg', 'Giày Đế Đúc , Mũi tròn, Đế cao su nhựa Thermos', 'Đặc điểm\r\nGiày Đế Đúc , Mũi tròn, Đế cao su nhựa Thermos\r\nCông nghệ: WEARABILITY - PILLOW WALK\r\nChất liệu\r\nChất Liệu: Da Tổng Hợp\r\nKích thước\r\nChiều cao đế: 1.25 IN (3.18 CM)', '2025-10-06 12:32:25', '2025-11-19 15:30:30'),
(8, 11, 2, 'GIÀY THỂ THAO NỮ CHICSNEAKER', 'gi-y-th-thao-n-chicsneaker', 1420000, 1390000, 'uploads/68e3b7940ed0c_NU2.jpg', 'GIÀY THỂ THAO NỮ CHICSNEAKER', 'GIÀY THỂ THAO NỮ CHICSNEAKER\r\n\r\nĐặc điểm\r\nGiày thể thao, Mũi tròn, Đế cao su\r\nCông nghệ: WEARABILITY - PILLOW WALK\r\nChất liệu\r\nChất liệu: Da lộn\r\nKích thước\r\nChiều cao đế: 1.00 IN (2.54 CM)', '2025-10-06 12:35:32', '2025-12-04 06:55:38'),
(9, 11, 2, 'GIÀY SNEAKERS NỮ RAYES', 'gi-y-sneakers-n-rayes', 1290000, 1190000, 'uploads/68e3b861c1391_nu3.jpg', 'GIÀY SNEAKERS NỮ RAYES', 'GIÀY SNEAKERS NỮ RAYES\r\n\r\nĐặc điểm\r\nGiày thể thao, Mũi tròn, Đế cao su nhựa Thermos\r\nCông nghệ: WEARABILITY-PILLOWWALK+LIGHTWEIGHT\r\nChất liệu\r\nChất liệu: Da Tổng Hợp\r\nKích thước\r\nChiều cao đế: 1.75 IN (4.45 CM)', '2025-10-06 12:38:57', '2025-11-19 15:30:46'),
(10, 1, 3, 'Giày tây THUS851-20', 'gi-y-t-y-thus851-20', 2200000, 1990000, 'uploads/THUS851-1--1763565751-0.png;uploads/THUS851-1763565751-1.png', 'Giày tây THUS851-20 kiểu buộc dây cổ điển, màu đen sang trọng, dáng thon gọn lịch lãm. Chất liệu da tổng hợp cao cấp, đế êm chống trượt, phù hợp môi trường công sở và các dịp cần trang phục chỉnh tề.', 'Giày tây THUS851-20 được thiết kế dành cho quý ông yêu phong cách tối giản nhưng vẫn nổi bật nhờ những chi tiết tinh tế. Tông đen dễ phối đồ, form giày gọn chân, thích hợp từ đi làm, gặp đối tác đến dự tiệc.\r\n\r\nĐặc điểm nổi bật:\r\nChất liệu:\r\n-Thân giày làm từ da tổng hợp cao cấp, bề mặt mịn, khó bám bẩn và dễ lau chùi.\r\n-Lót trong mềm, thoáng khí, giúp chân thoải mái, hạn chế hầm bí khi mang lâu.\r\nThiết kế:\r\n-Kiểu giày buộc dây (Derby/Oxford), tạo cảm giác lịch sự, chuyên nghiệp.\r\n-Mũi giày thon nhẹ, tôn dáng bàn chân, phù hợp nhiều dáng người.\r\n-Đường may sắc nét, form giày cứng cáp, giữ dáng tốt.\r\n-Điểm nhấn chi tiết kim loại nhỏ ở gót giày tạo nét hiện đại và khác biệt.\r\nĐế giày:\r\n-Đế cao su bám tốt, chống trơn trượt trên nhiều bề mặt.\r\n-Đệm đế êm, có độ đàn hồi, hỗ trợ giảm áp lực lên gót và lòng bàn chân.\r\nỨng dụng:\r\n-Dễ dàng phối với quần tây, sơ mi, vest, hoặc quần kaki tối màu.\r\n-Phù hợp đi làm văn phòng, họp hành, gặp khách hàng, phỏng vấn, dự tiệc cưới và các sự kiện trang trọng.\r\nHướng dẫn bảo quản:\r\n-Tránh ngâm nước hoặc để giày trong môi trường ẩm ướt quá lâu.\r\n-Vệ sinh bằng khăn mềm, để khô tự nhiên; dùng xi/kem dưỡng giày màu đen định kỳ để giữ độ bóng đẹp và bền màu.', '2025-11-11 10:49:03', '2025-11-19 15:22:31'),
(11, 12, 3, 'Giày boot nam AK9981', 'gi-y-boot-nam-ak9981', 1000000, 879000, 'uploads/Gi--y-boot-nam-AK9981-1--1764831283-0.png;uploads/Gi--y-boot-nam-AK9981-1764831283-1.png', 'Giày boot nam AK9981 thiết kế cổ lửng khỏe khoắn, phong cách năng động, nam tính. Chất liệu da tổng hợp bền đẹp, đế cao su rãnh sâu chống trượt tốt, phù hợp đi làm, đi chơi, dạo phố và du lịch.', 'Giày boot nam AK9981 là lựa chọn lý tưởng cho anh em thích phong cách trẻ trung, bụi bặm nhưng vẫn gọn gàng. Form boot ôm vừa cổ chân, tạo điểm nhấn cho outfit mà vẫn mang lại cảm giác thoải mái khi di chuyển.\r\nChất liệu:\r\n-Thân giày làm từ da tổng hợp cao cấp (có thể chỉnh thành da thật nếu đúng), bề mặt mịn, khó bám bẩn, dễ lau chùi.\r\n-Lót trong mềm, thoáng, hạn chế hầm bí, êm chân khi mang lâu.\r\nThiết kế:\r\n-Dáng boot cổ lửng, đường may nổi tạo cảm giác chắc chắn, nam tính.\r\n-Kiểu buộc dây chắc chắn, dễ tùy chỉnh độ ôm chân.\r\n-Cổ giày có thể được lót đệm (nếu có) giúp ôm cổ chân và giảm cọ xát.\r\n-Logo/chi tiết trang trí bên hông (nếu có) tạo điểm nhấn nổi bật.\r\nĐế giày:\r\n-Đế cao su đúc với rãnh sâu, tăng độ bám, chống trơn trượt tốt trên nhiều bề mặt.\r\n-Đế dày vừa phải, có độ đàn hồi, hỗ trợ giảm lực tác động lên gót và lòng bàn chân.\r\nỨng dụng:\r\n-Dễ phối với quần jean, kaki, jogger, áo thun, sơ mi, áo khoác…\r\n-Phù hợp đi chơi, dạo phố, du lịch, đi làm phong cách casual hoặc các hoạt động ngoài trời nhẹ.\r\nHướng dẫn bảo quản:\r\n-Tránh để giày ngâm nước hoặc tiếp xúc bùn bẩn quá lâu; nên phơi nơi khô thoáng, tránh nắng gắt.\r\n-Vệ sinh bằng khăn mềm hoặc bàn chải lông mịn; có thể dùng xịt/kem dưỡng da giày để tăng độ bền và giữ màu đẹp.', '2025-11-11 11:05:52', '2025-12-04 06:54:43'),
(12, 1, 3, 'Giày tây HO103', 'gi-y-t-y-ho103', 890000, 780000, 'uploads/HO103-1--1763558164-0.png;uploads/HO103-2--1763558164-1.png;uploads/HO103-1763558164-2.png', 'Giày tây HO103 với thiết kế đơn giản, sang trọng, chất liệu da bền đẹp, phù hợp đi làm, dự tiệc và các sự kiện quan trọng. Form ôm chân, đế êm hỗ trợ di chuyển cả ngày dài.', 'Giày tây HO103 là mẫu giày dành cho quý ông yêu thích sự lịch lãm nhưng vẫn đề cao sự thoải mái khi sử dụng hằng ngày. Thiết kế tối giản, tinh tế, dễ phối với quần tây, sơ mi, vest cho nhiều hoàn cảnh: đi làm, họp quan trọng, gặp đối tác hay tham gia tiệc cưới.\r\nĐặc điểm nổi bật:\r\n  -Chất liệu:\r\n      +Thân giày làm từ da (simili/da tổng hợp/da bò – bạn chỉnh lại cho đúng) bề mặt mịn, hạn chế bong tróc.\r\n      +Lót trong mềm, thấm hút mồ hôi tốt, giúp chân luôn khô thoáng.\r\n  -Thiết kế:\r\n      +Form giày chuẩn phong cách công sở, mũi hơi nhọn nhưng không gây đau chân.\r\n      +Đường may chắc chắn, đường nét gọn gàng tạo cảm giác lịch sự, chuyên nghiệp.\r\n  -Đế giày:\r\n      +Đế cao su/tpr chống trơn trượt (bạn chỉnh lại nếu khác), bám tốt trên nhiều bề mặt.\r\n      +Đế êm, hỗ trợ giảm lực khi di chuyển, phù hợp người phải đứng hoặc đi nhiều.\r\n  -Ứng dụng:\r\n      +Phối hợp dễ dàng với quần tây, áo sơ mi, vest, quần kaki…\r\n      +Thích hợp đi làm văn phòng, phỏng vấn, họp đối tác, dự tiệc, sự kiện. \r\n  -Bảo quản:\r\n      +Hạn chế tiếp xúc nước mưa lâu, lau khô ngay khi bị ướt.\r\n      +Thường xuyên lau sạch bụi và dùng xi/kem dưỡng da giày để giữ độ bóng đẹp.', '2025-11-11 11:25:42', '2025-11-19 14:35:42'),
(13, 14, 3, 'Giày boot AK83-17', 'gi-y-boot-ak83-17', 1650000, 1360000, 'uploads/Gi--y-boot-AK83-17-1--1764832157-0.png;uploads/Gi--y-boot-AK83-17-1764832157-1.png', 'Giày boot nữ AK83-17 thiết kế cổ lửng cá tính, đế cao chunky thời trang, giúp tôn dáng và “hack” chiều cao hiệu quả. Tông đen dễ phối đồ, phù hợp đi chơi, dạo phố, chụp hình, dự tiệc hoặc đi làm phong cách trẻ trung.', 'Giày boot nữ AK83-17 là lựa chọn hoàn hảo cho những nàng yêu phong cách cá tính, hiện đại và nổi bật. Form boot ôm gọn cổ chân, kết hợp cùng đế cao dày dặn tạo hiệu ứng chân dài hơn mà vẫn chắc chắn khi di chuyển. \r\nChất liệu:\r\n -Thân giày làm từ da tổng hợp cao cấp, bề mặt bóng nhẹ, hạn chế bám bẩn, dễ lau chùi.\r\n-Lót trong mềm, êm chân, giúp giảm ma sát và hạn chế hầm bí khi mang lâu.\r\nThiết kế:\r\n-Dáng boot cổ lửng, đường cắt góc cạnh tạo vẻ mạnh mẽ, thời trang.\r\n-Thân giày có logo/chi tiết “M FASHION” dập nổi, tăng điểm nhấn cá tính.\r\n-Buộc dây phía trước giúp điều chỉnh độ ôm chân, đồng thời tạo điểm nhấn trang trí.\r\n-Phần lưỡi gà và cổ giày thiết kế cao hơn, ôm chân, phù hợp mix với quần skinny, legging hoặc váy ngắn.\r\nĐế giày:\r\n-Đế cao chunky dày, rãnh sâu chống trơn trượt tốt, tạo cảm giác vững chãi khi bước đi.\r\n-Độ cao gót/đế giúp tôn dáng nhưng vẫn giữ được sự ổn định, phù hợp mang cả ngày.\r\nỨng dụng:\r\n-Dễ phối với jean, skinny, short, chân váy, đầm… cho nhiều phong cách từ cá tính, năng động đến street style.\r\n-Thích hợp đi chơi, dạo phố, đi cafe, xem phim, du lịch, lên hình chụp ảnh OOTD, hoặc đi làm môi trường trẻ trung.\r\nHướng dẫn bảo quản:\r\n-Tránh ngâm nước lâu, nếu giày bị ướt nên lau khô và để nơi thoáng mát.\r\n-Vệ sinh bằng khăn mềm ẩm; có thể dùng kem/xịt dưỡng da giày màu đen để giữ độ bóng đẹp và tăng độ bền.', '2025-11-11 12:00:00', '2025-12-04 07:09:17'),
(14, 12, 3, 'Giày boot nam AK90010', 'gi-y-boot-nam-ak90010', 2200000, 1890000, 'uploads/Gi--y-boot-nam-AK90010-1--1764830662-0.png;uploads/Gi--y-boot-nam-AK90010-1764830662-1.png', 'Giày boot nam AK90010 với thiết kế cổ lửng khỏe khoắn, chất liệu da tổng hợp cao cấp, đế cao su bám tốt. Phù hợp đi làm, đi chơi, dạo phố hay du lịch, dễ phối với quần jean, kaki và áo khoác.', 'Giày boot nam AK90010 là lựa chọn lý tưởng cho phái mạnh yêu phong cách nam tính, hiện đại và năng động. Thiết kế cổ lửng ôm vừa cổ chân, tạo điểm nhấn cho set đồ nhưng vẫn thoải mái khi di chuyển.\r\nĐặc điểm nổi bật:\r\nChất liệu:\r\n-Thân giày làm từ da tổng hợp cao cấp, bề mặt mịn, hạn chế bám bẩn, dễ vệ sinh.\r\n- Lót trong mềm, thoáng khí, hỗ trợ giảm ma sát và hầm bí cho bàn chân.\r\nThiết kế:\r\n-Dáng boot cổ lửng, đường cắt gọn gàng, tôn dáng chân và tăng vẻ nam tính.\r\n-Kiểu buộc dây chắc chắn, dễ điều chỉnh độ ôm chân.\r\n-Đường may tỉ mỉ, form giày cứng cáp, giữ dáng tốt sau thời gian sử dụng.\r\nĐế giày:\r\n-Đế cao su đúc nguyên khối, có rãnh chống trượt, bám tốt trên nhiều bề mặt.\r\n-Đế êm, có độ đàn hồi, hỗ trợ bước đi vững chắc, phù hợp di chuyển nhiều.\r\nỨng dụng:\r\n-Dễ phối với quần jean, quần kaki, áo sơ mi, áo thun, áo khoác…\r\n-Thích hợp đi làm phong cách casual, đi chơi, dạo phố, du lịch hoặc các hoạt động ngoài trời nhẹ.\r\nHướng dẫn bảo quản:\r\n-Tránh ngâm nước lâu hoặc để giày trong môi trường ẩm ướt.\r\n-Lau sạch bụi bẩn bằng khăn mềm, để khô tự nhiên; có thể dùng thêm dung dịch/kem dưỡng da giày để tăng độ bền và giữ màu đẹp.', '2025-11-11 12:09:38', '2025-12-04 06:44:22'),
(15, 14, 3, 'Giày boot AK129-1', 'gi-y-boot-ak129-1', 950000, 900000, 'uploads/Gi--y-boot-AK129-1-1--1764832381-0.png;uploads/Gi--y-boot-AK129-1-1764832381-1.png', 'Giày boot nữ AK129-1 thiết kế cổ lửng thời trang, đế cao vừa phải giúp tôn dáng và “hack” chiều cao. Tông màu trung tính, dễ phối với nhiều kiểu trang phục, phù hợp đi chơi, dạo phố, đi làm phong cách trẻ trung, cá tính.', 'Giày boot nữ AK129-1 là lựa chọn lý tưởng cho những cô nàng yêu phong cách năng động, hiện đại. Form boot ôm gọn cổ chân, kết hợp cùng đế dày chắc chắn mang lại cảm giác vững chãi, vừa đẹp vừa dễ đi.\r\nChất liệu:\r\n-Thân giày làm từ da tổng hợp cao cấp (hoặc da lộn – bạn chỉnh lại cho đúng), bề mặt bền màu, hạn chế bám bẩn, dễ vệ sinh.\r\n-Lót trong mềm, êm, giúp giảm ma sát và hạn chế hầm bí khi mang cả ngày.\r\nThiết kế:\r\n-Dáng boot cổ lửng, ôm chân gọn gàng, thích hợp phối cùng quần skinny, jean, chân váy, đầm…\r\n-Thiết kế buộc dây/kéo khóa (tùy mẫu thực tế), vừa tiện mang vào – tháo ra, vừa tạo điểm nhấn cá tính.\r\n-Các đường cắt, may và chi tiết trang trí (logo/miếng da/đường chỉ nổi…) làm tổng thể trông hiện đại và nổi bật hơn.\r\nĐế giày:\r\n-Đế cao dày dặn, có rãnh chống trơn trượt, giúp bước đi chắc chắn trên nhiều bề mặt.\r\n-Độ cao gót/đế hợp lý, giúp tôn dáng mà vẫn giữ được sự thoải mái khi di chuyển.\r\nỨng dụng:\r\n-Phù hợp đi học, đi làm, đi cafe, dạo phố, xem phim, du lịch, chụp hình OOTD…\r\n-Dễ phối đồ theo nhiều phong cách: từ nữ tính với váy/đầm đến cá tính với jean rách, áo khoác da, hoodie…\r\nHướng dẫn bảo quản:\r\n-Tránh ngâm nước lâu hoặc để giày nơi ẩm mốc; nếu bị ướt nên lau khô và để nơi thoáng mát.\r\n-Vệ sinh bề mặt bằng khăn mềm/bàn chải lông mịn; có thể dùng xịt/kem dưỡng da giày để giữ form đẹp và tăng độ bền.', '2025-12-04 07:13:01', '2025-12-04 07:13:01'),
(16, 13, 2, 'Thể thao nam AKSK09-8', 'th-thao-nam-aksk09-8', 1650000, 1430000, 'uploads/Th----thao-nam-AKSK09-8-1--1764832718-0.png;uploads/Th----thao-nam-AKSK09-8-1764832718-1.png', 'Giày thể thao nam AKSK09-8 thiết kế trẻ trung, năng động, form ôm chân thoải mái. Đế nhẹ, êm, bám tốt, phù hợp đi học, đi làm casual, tập luyện nhẹ và dạo phố hằng ngày.', 'Giày thể thao nam AKSK09-8 là lựa chọn lý tưởng cho các chàng trai yêu thích phong cách thoải mái, linh hoạt nhưng vẫn gọn gàng. Thiết kế đơn giản, dễ phối đồ, mang được trong nhiều hoàn cảnh từ đi chơi đến vận động nhẹ.\r\nChất liệu:\r\n-Thân giày làm từ vải lưới/da tổng hợp (bạn chỉnh lại đúng chất liệu), thoáng khí, giúp chân luôn dễ chịu.\r\n-Lót trong mềm, hút ẩm tốt, hạn chế mùi và giảm ma sát khi di chuyển.\r\nThiết kế:\r\n-Form giày thể thao ôm chân, hỗ trợ bước chạy/bước đi chắc chắn.\r\n-Kiểu buộc dây giúp điều chỉnh độ ôm tùy theo bàn chân.\r\n-Đường may gọn gàng, phối màu trẻ trung, dễ mix với nhiều kiểu outfit.\r\nĐế giày:\r\n-Đế phylon/cao su nhẹ (chỉnh theo thực tế), đàn hồi tốt, hỗ trợ giảm chấn khi chạy/đi bộ.\r\n-Mặt đế có rãnh chống trượt, bám tốt trên nhiều bề mặt.\r\nỨng dụng:\r\n-Phù hợp đi học, đi làm phong cách casual, đi chơi, dạo phố.\r\n-Thích hợp cho các hoạt động vận động nhẹ như đi bộ, tập gym cơ bản, thể dục ngoài trời.\r\nHướng dẫn bảo quản:\r\n-Hạn chế ngâm giày trong nước quá lâu; nếu bị ướt nên để khô tự nhiên nơi thoáng mát.\r\n-Vệ sinh bằng bàn chải lông mềm/khăn ẩm; có thể dùng xịt khử mùi giày định kỳ để giữ giày luôn sạch và thơm.', '2025-12-04 07:18:38', '2025-12-04 07:18:38'),
(17, 11, 2, 'Thể thao nữ MTY73572', 'th-thao-n-mty73572', 590000, 519000, 'uploads/Th----thao-n----MTY73572-1--1764833032-0.png;uploads/Th----thao-n----MTY73572-1764833032-1.png', 'Giày thể thao nữ MTY73572 thiết kế trẻ trung, năng động, form ôm chân gọn gàng. Đế nhẹ, êm, bám tốt, phù hợp đi học, đi làm, đi chơi, dạo phố và tập luyện nhẹ hằng ngày.', 'Giày thể thao nữ MTY73572 là lựa chọn lý tưởng cho các nàng yêu phong cách thoải mái nhưng vẫn thời trang. Thiết kế đơn giản, dễ phối với nhiều kiểu trang phục từ jean, legging đến váy năng động.\r\nChất liệu:\r\n-Thân giày làm từ vải lưới/da tổng hợp (bạn chỉnh lại cho đúng), thoáng khí, giúp chân luôn khô thoáng.\r\n-Lót trong mềm, thấm hút tốt, giảm ma sát và hạn chế mùi khi mang lâu.\r\nThiết kế:\r\n-Form giày thể thao ôm chân, đường cắt mềm mại, tôn dáng bàn chân nữ.\r\n-Kiểu buộc dây/đệm thun (tùy mẫu thực tế) giúp điều chỉnh độ ôm, dễ mang vào – tháo ra.\r\n-Phối màu nữ tính, trẻ trung, dễ mix với outfit đi học, đi chơi hay tập luyện.\r\nĐế giày:\r\n-Đế phylon/cao su nhẹ, đàn hồi tốt, hỗ trợ giảm chấn khi đi bộ hoặc vận động.\r\n-Mặt đế có rãnh chống trượt, bám tốt trên nhiều bề mặt.\r\nỨng dụng:\r\n-Phù hợp đi học, đi làm phong cách casual, đi chơi, dạo phố, du lịch.\r\n-Thích hợp cho các hoạt động thể thao nhẹ như đi bộ, chạy bộ nhẹ, tập gym cơ bản, aerobic…\r\nHướng dẫn bảo quản:\r\n-Hạn chế ngâm giày trong nước quá lâu; nếu giày bị ướt nên để khô tự nhiên ở nơi thoáng mát.\r\n-Vệ sinh bằng bàn chải lông mềm/khăn ẩm; có thể dùng xịt khử mùi giày định kỳ để giữ giày luôn sạch và thơm.', '2025-12-04 07:23:52', '2025-12-04 07:23:52'),
(18, 12, 3, 'Giày boot nam AK7262', 'gi-y-boot-nam-ak7262', 1850000, 1690000, 'uploads/AK7262-1--1765007847-0.png;uploads/AK7262-1765007847-1.png', 'Giày boot nam AK7262 thiết kế mạnh mẽ, form đứng dáng, chất liệu da tổng hợp bền đẹp. Đế cao su chống trượt, cổ boot ôm chân chắc chắn, phù hợp phong cách nam tính và sang trọng.', 'Giày boot nam AK7262 mang phong cách hiện đại, chất liệu da tổng hợp cao cấp, đế cao su bám đường tốt, phù hợp đi làm, đi chơi và phối cùng nhiều trang phục. Sản phẩm nổi bật với độ bền cao, thiết kế nam tính và khả năng bảo vệ mắt cá chân. Lựa chọn lý tưởng cho những ai yêu thích boot thời trang, mạnh mẽ và sang trọng.', '2025-12-06 07:57:27', '2025-12-06 07:57:27'),
(19, 14, 3, 'Giày boot AK1908', 'gi-y-boot-ak1908', 980000, 760000, 'uploads/AK1908-1765008244-0.png;uploads/Screenshot-2025-12-06-150312-1765008244-1.png', 'Giày boot nữ AK1908 mang phong cách trẻ trung – hiện đại, thiết kế cổ lửng ôm chân giúp tôn dáng và dễ phối đồ. Chất liệu da mềm, đế chắc chắn, thích hợp đi học, đi làm, đi chơi.', 'Giày boot nữ AK1908 là mẫu boot thời trang được yêu thích nhờ thiết kế cổ lửng thanh lịch, chất da mềm mại và kiểu dáng tôn dáng. Phù hợp phong cách công sở, dạo phố hoặc dự tiệc. Sản phẩm bền, êm chân, dễ mix với váy, quần jean hoặc skinny. Lựa chọn hoàn hảo cho nàng yêu phong cách hiện đại.', '2025-12-06 08:04:04', '2025-12-06 08:04:04'),
(20, 13, 2, 'Giày Sneaker Nam Lacoste Men White L001 47SMA0054-2B7 Leather Màu Trắng Be Size 39.5', 'gi-y-sneaker-nam-lacoste-men-white-l001-47sma0054-2b7-leather-m-u-tr-ng-be-size-39-5', 1000000, 890000, 'uploads/Gi--y-Sneaker-Nam-Lacoste-Men-1--1765872710-0.png;uploads/Gi--y-Sneaker-Nam-Lacoste-Men-1765872710-1.png', 'Giày Sneaker Nam Lacoste Men White L001 47SMA0054-2B7 Leather Màu Trắng Be sở hữu kiểu dáng thời trang, hiện đại đến từ thương hiệu Lacoste nổi tiếng. Với đôi giày này bạn có thể kết hợp với nhiêu trang phục khác nhau để có set đồ năng động, trẻ trung.', 'Giày Sneaker Lacoste Men White L001 47SMA0054-2B7 Leather được làm từ chất liệu da cao cấp, với phong cách trẻ trung, khỏe khoắn và lịch lãm mang đậm phong phong cách đặc trưng của thương hiệu Lacoste. Phần đế giày được làm bằng cao su nên đi êm và ma sát tốt. \r\nLót giày thông thoáng, dày dặn, êm ái giúp chân luôn thoải mái dù mang giày suốt cả ngày.\r\nCác đường chỉ khâu thẳng hàng rất tinh tế và chắc chắn.\r\nLogo đặt tinh tế và tạo điểm nhấn riêng cho đôi giày.', '2025-12-16 08:11:50', '2025-12-16 08:11:50');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_size`
--

CREATE TABLE `product_size` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(50) DEFAULT NULL,
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_size`
--

INSERT INTO `product_size` (`id`, `product_id`, `size`, `stock`) VALUES
(8, 4, NULL, 110),
(9, 4, '27', 110),
(10, 4, '28', 12),
(11, 4, '29', 8),
(12, 4, '30', 5),
(13, 5, NULL, 100),
(14, 5, '27', 12),
(15, 6, NULL, 20),
(16, 6, '27', 5),
(17, 6, '28', 1),
(18, 6, '29', 11),
(19, 7, NULL, 20),
(20, 7, '35', 14),
(21, 7, '36', 13),
(22, 7, '37', 17),
(23, 7, '38', 13),
(24, 7, '39', 12),
(25, 7, '40', 11),
(26, 8, NULL, 60),
(27, 8, '36', 20),
(28, 8, '37', 20),
(29, 8, '38', 20),
(30, 8, '39', 20),
(31, 8, '40', 20),
(32, 9, NULL, 50),
(33, 9, '36', 20),
(34, 9, '37', 20),
(35, 9, '38', 19),
(36, 9, '39', 20),
(37, 9, '40', 20),
(38, 4, 'FS', 0),
(39, 5, 'FS', 0),
(40, 7, 'FS', 0),
(41, 6, 'FS', 0),
(50, 12, '35', 20),
(51, 12, '36', 17),
(52, 12, '37', 10),
(53, 12, '38', 10),
(54, 12, '39', 10),
(55, 12, '40', 10),
(56, 12, '41', 10),
(57, 12, '42', 10),
(58, 13, '39', 11),
(59, 13, '40', 10),
(60, 13, '41', 10),
(61, 13, '42', 10),
(62, 13, '43', 10),
(63, 13, '44', 0),
(64, 13, '45', 0),
(65, 14, '39', 10),
(66, 14, '40', 10),
(67, 14, '41', 10),
(68, 14, '42', 10),
(69, 14, '43', 10),
(70, 14, '44', 0),
(71, 14, '45', 0),
(80, 12, '43', 10),
(81, 12, '44', 10),
(82, 12, '45', 10),
(83, 4, '35', 0),
(84, 4, '36', 0),
(85, 4, '37', 0),
(86, 4, '38', 0),
(87, 4, '39', 10),
(88, 4, '40', 10),
(89, 4, '41', 10),
(90, 4, '42', 10),
(91, 4, '43', 10),
(92, 4, '44', 10),
(93, 4, '45', 10),
(94, 5, '35', 0),
(95, 5, '36', 0),
(96, 5, '37', 0),
(97, 5, '38', 0),
(98, 5, '39', 10),
(99, 5, '40', 10),
(100, 5, '41', 10),
(101, 5, '42', 10),
(102, 5, '43', 10),
(103, 5, '44', 10),
(104, 5, '45', 10),
(105, 6, '35', 0),
(106, 6, '36', 0),
(107, 6, '37', 0),
(108, 6, '38', 0),
(109, 6, '39', 10),
(110, 6, '40', 10),
(111, 6, '41', 10),
(112, 6, '42', 10),
(113, 6, '43', 10),
(114, 6, '44', 10),
(115, 6, '45', 10),
(116, 10, '35', 10),
(117, 10, '36', 10),
(118, 10, '37', 10),
(119, 10, '38', 10),
(120, 10, '39', 10),
(121, 10, '40', 10),
(122, 10, '41', 10),
(123, 10, '42', 10),
(124, 10, '43', 10),
(125, 10, '44', 10),
(126, 10, '45', 10),
(137, 14, '35', 0),
(138, 14, '36', 0),
(139, 14, '37', 0),
(140, 14, '38', 0),
(148, 11, '35', 0),
(149, 11, '36', 0),
(150, 11, '37', 0),
(151, 11, '38', 0),
(152, 11, '39', 10),
(153, 11, '40', 10),
(154, 11, '41', 10),
(155, 11, '42', 10),
(156, 11, '43', 10),
(157, 11, '44', 0),
(158, 11, '45', 0),
(164, 13, '36', 0),
(165, 13, '37', 0),
(166, 13, '38', 0),
(169, 15, '35', 30),
(170, 15, '36', 30),
(171, 15, '37', 30),
(172, 15, '38', 30),
(173, 15, '39', 30),
(174, 15, '40', 10),
(175, 15, '41', 10),
(176, 15, '42', 10),
(177, 16, '39', 10),
(178, 16, '40', 10),
(179, 16, '41', 10),
(180, 16, '42', 10),
(181, 16, '43', 10),
(182, 16, '44', 10),
(183, 16, '45', 10),
(184, 17, '35', 10),
(185, 17, '36', 10),
(186, 17, '37', 10),
(187, 17, '38', 10),
(188, 17, '39', 10),
(189, 17, '40', 10),
(190, 17, '41', 10),
(191, 17, '42', 10),
(258, 18, '39', 0),
(259, 18, '40', 0),
(260, 18, '41', 0),
(261, 18, '42', 0),
(262, 18, '43', 0),
(263, 18, '44', 0),
(264, 18, '45', 0),
(265, 19, '35', 0),
(266, 19, '36', 0),
(267, 19, '37', 0),
(268, 19, '38', 0),
(269, 19, '39', 0),
(270, 19, '40', 0),
(271, 19, '41', 0),
(272, 19, '42', 0),
(273, 20, '39', 10),
(274, 20, '40', 10),
(275, 20, '41', 10),
(276, 20, '42', 10),
(277, 20, '43', 10),
(278, 20, '44', 10),
(279, 20, '45', 10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `purchase_receipts`
--

CREATE TABLE `purchase_receipts` (
  `receipt_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `receipt_date` datetime NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `note` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `purchase_receipts`
--

INSERT INTO `purchase_receipts` (`receipt_id`, `supplier_id`, `created_by`, `receipt_date`, `total_amount`, `note`, `created_at`, `updated_at`) VALUES
(1, 3, 1, '2025-11-02 14:53:00', 8540000.00, '', '2025-11-02 14:00:07', '2025-11-02 14:50:17'),
(2, 1, 1, '2025-11-02 15:22:00', 23100000.00, '', '2025-11-02 14:22:57', '2025-11-02 14:50:17'),
(3, 4, 1, '2025-11-02 15:32:00', 19000000.00, '', '2025-11-02 14:32:26', '2025-11-02 14:50:17'),
(4, 4, 1, '2025-11-11 08:55:00', 31000000.00, '', '2025-11-11 07:56:27', '2025-11-11 07:56:27'),
(5, 3, 1, '2025-11-11 10:07:00', 10000000.00, '', '2025-11-11 09:08:03', '2025-11-11 09:08:03'),
(7, 3, 1, '2025-11-11 12:26:00', 17000000.00, '', '2025-11-11 11:46:17', '2025-11-11 11:46:17'),
(8, 3, 1, '2025-12-04 09:19:00', 281627500.00, '', '2025-12-04 08:23:37', '2025-12-04 08:23:37'),
(9, 1, 1, '2025-12-04 09:33:00', 281694000.00, '', '2025-12-04 08:34:54', '2025-12-04 08:34:54'),
(10, 4, 1, '2025-12-04 10:07:00', 347655000.00, '', '2025-12-04 09:09:16', '2025-12-04 09:09:16'),
(11, 3, 1, '2025-12-08 15:06:00', 68400000.00, '', '2025-12-08 14:06:44', '2025-12-08 14:06:44'),
(12, 5, 1, '2025-12-16 09:12:00', 49840000.00, '', '2025-12-16 08:12:35', '2025-12-16 08:12:35');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `purchase_receipt_items`
--

CREATE TABLE `purchase_receipt_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `receipt_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` >= 0),
  `unit_price` decimal(15,2) NOT NULL CHECK (`unit_price` >= 0),
  `subtotal` decimal(15,2) GENERATED ALWAYS AS (`quantity` * `unit_price`) STORED,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `size_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `purchase_receipt_items`
--

INSERT INTO `purchase_receipt_items` (`id`, `receipt_id`, `product_id`, `quantity`, `unit_price`, `created_at`, `updated_at`, `size_id`) VALUES
(1, 1, 4, 70, 122000.00, '2025-11-02 14:00:07', '2025-11-11 09:06:22', 38),
(2, 2, 5, 100, 231000.00, '2025-11-02 14:22:57', '2025-11-11 09:06:22', 39),
(3, 3, 7, 100, 190000.00, '2025-11-02 14:32:26', '2025-11-11 09:06:22', 40),
(4, 4, 5, 100, 190000.00, '2025-11-11 07:56:27', '2025-11-11 09:06:22', 39),
(5, 4, 6, 100, 120000.00, '2025-11-11 07:56:27', '2025-11-11 09:06:22', 41),
(6, 5, 4, 100, 100000.00, '2025-11-11 09:08:03', '2025-11-11 09:08:03', 9),
(7, 7, 12, 10, 1000000.00, '2025-11-11 11:46:17', '2025-11-11 11:46:17', 50),
(8, 7, 12, 7, 1000000.00, '2025-11-11 11:46:17', '2025-11-11 11:46:17', 51),
(9, 8, 15, 20, 855000.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 169),
(10, 8, 15, 20, 855000.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 170),
(11, 8, 15, 20, 855000.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 171),
(12, 8, 15, 20, 855000.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 172),
(13, 8, 15, 20, 855000.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 173),
(14, 8, 13, 10, 1292000.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 58),
(15, 8, 13, 10, 1292000.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 59),
(16, 8, 13, 10, 1292000.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 60),
(17, 8, 13, 10, 1292000.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 61),
(18, 8, 13, 10, 1292000.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 62),
(19, 8, 14, 10, 1795500.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 65),
(20, 8, 14, 10, 1795500.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 66),
(21, 8, 14, 10, 1795500.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 68),
(22, 8, 14, 10, 1795500.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 69),
(23, 8, 14, 10, 1795500.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 67),
(24, 8, 11, 10, 835050.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 152),
(25, 8, 11, 10, 835050.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 153),
(26, 8, 11, 10, 835050.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 154),
(27, 8, 11, 10, 835050.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 155),
(28, 8, 11, 10, 835050.00, '2025-12-04 08:23:37', '2025-12-04 08:23:37', 156),
(29, 9, 7, 10, 190000.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 20),
(30, 9, 7, 10, 190000.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 21),
(31, 9, 7, 10, 190000.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 22),
(32, 9, 7, 10, 190000.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 23),
(33, 9, 7, 10, 190000.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 24),
(34, 9, 7, 10, 190000.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 25),
(35, 9, 9, 10, 1130500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 33),
(36, 9, 9, 10, 1130500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 34),
(37, 9, 9, 10, 1130500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 35),
(38, 9, 9, 10, 1130500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 36),
(39, 9, 9, 10, 1130500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 37),
(40, 9, 8, 10, 1320500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 26),
(41, 9, 8, 10, 1320500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 27),
(42, 9, 8, 10, 1320500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 28),
(43, 9, 8, 10, 1320500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 29),
(44, 9, 8, 10, 1320500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 30),
(45, 9, 8, 10, 1320500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 31),
(46, 9, 16, 10, 1358500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 177),
(47, 9, 16, 10, 1358500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 178),
(48, 9, 16, 10, 1358500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 179),
(49, 9, 16, 10, 1358500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 180),
(50, 9, 16, 10, 1358500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 181),
(51, 9, 16, 10, 1358500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 182),
(52, 9, 16, 10, 1358500.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 183),
(53, 9, 17, 10, 493050.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 184),
(54, 9, 17, 10, 493050.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 185),
(55, 9, 17, 10, 493050.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 186),
(56, 9, 17, 10, 493050.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 187),
(57, 9, 17, 10, 493050.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 188),
(58, 9, 17, 10, 493050.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 189),
(59, 9, 17, 10, 493050.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 190),
(60, 9, 17, 10, 493050.00, '2025-12-04 08:34:54', '2025-12-04 08:34:54', 191),
(61, 10, 12, 10, 1000000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 50),
(62, 10, 12, 10, 1000000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 51),
(63, 10, 12, 10, 1000000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 52),
(64, 10, 12, 10, 1000000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 53),
(65, 10, 12, 10, 1000000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 54),
(66, 10, 12, 10, 1000000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 55),
(67, 10, 12, 10, 1000000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 56),
(68, 10, 12, 10, 1000000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 57),
(69, 10, 12, 10, 1000000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 80),
(70, 10, 12, 10, 1000000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 81),
(71, 10, 12, 10, 1000000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 82),
(72, 10, 4, 10, 100000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 8),
(73, 10, 4, 10, 100000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 87),
(74, 10, 4, 10, 100000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 88),
(75, 10, 4, 10, 100000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 89),
(76, 10, 4, 10, 100000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 90),
(77, 10, 4, 10, 100000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 91),
(78, 10, 4, 10, 100000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 92),
(79, 10, 4, 10, 100000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 93),
(80, 10, 6, 10, 120000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 109),
(81, 10, 6, 10, 120000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 110),
(82, 10, 6, 10, 120000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 111),
(83, 10, 6, 10, 120000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 112),
(84, 10, 6, 10, 120000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 113),
(85, 10, 6, 10, 120000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 114),
(86, 10, 6, 10, 120000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 115),
(87, 10, 5, 10, 190000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 98),
(88, 10, 5, 10, 190000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 99),
(89, 10, 5, 10, 190000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 100),
(90, 10, 5, 10, 190000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 101),
(91, 10, 5, 10, 190000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 102),
(92, 10, 5, 10, 190000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 103),
(93, 10, 5, 10, 190000.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 104),
(94, 10, 10, 10, 1890500.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 116),
(95, 10, 10, 10, 1890500.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 117),
(96, 10, 10, 10, 1890500.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 118),
(97, 10, 10, 10, 1890500.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 119),
(98, 10, 10, 10, 1890500.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 120),
(99, 10, 10, 10, 1890500.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 121),
(100, 10, 10, 10, 1890500.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 122),
(101, 10, 10, 10, 1890500.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 123),
(102, 10, 10, 10, 1890500.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 124),
(103, 10, 10, 10, 1890500.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 125),
(104, 10, 10, 10, 1890500.00, '2025-12-04 09:09:16', '2025-12-04 09:09:16', 126),
(105, 11, 15, 10, 855000.00, '2025-12-08 14:06:44', '2025-12-08 14:06:44', 169),
(106, 11, 15, 10, 855000.00, '2025-12-08 14:06:44', '2025-12-08 14:06:44', 170),
(107, 11, 15, 10, 855000.00, '2025-12-08 14:06:44', '2025-12-08 14:06:44', 171),
(108, 11, 15, 10, 855000.00, '2025-12-08 14:06:44', '2025-12-08 14:06:44', 172),
(109, 11, 15, 10, 855000.00, '2025-12-08 14:06:44', '2025-12-08 14:06:44', 173),
(110, 11, 15, 10, 855000.00, '2025-12-08 14:06:44', '2025-12-08 14:06:44', 174),
(111, 11, 15, 10, 855000.00, '2025-12-08 14:06:44', '2025-12-08 14:06:44', 175),
(112, 11, 15, 10, 855000.00, '2025-12-08 14:06:44', '2025-12-08 14:06:44', 176),
(113, 12, 20, 10, 712000.00, '2025-12-16 08:12:35', '2025-12-16 08:12:35', 273),
(114, 12, 20, 10, 712000.00, '2025-12-16 08:12:35', '2025-12-16 08:12:35', 274),
(115, 12, 20, 10, 712000.00, '2025-12-16 08:12:35', '2025-12-16 08:12:35', 275),
(116, 12, 20, 10, 712000.00, '2025-12-16 08:12:35', '2025-12-16 08:12:35', 276),
(117, 12, 20, 10, 712000.00, '2025-12-16 08:12:35', '2025-12-16 08:12:35', 277),
(118, 12, 20, 10, 712000.00, '2025-12-16 08:12:35', '2025-12-16 08:12:35', 278),
(119, 12, 20, 10, 712000.00, '2025-12-16 08:12:35', '2025-12-16 08:12:35', 279);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shipping_status`
--

CREATE TABLE `shipping_status` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL,
  `note` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(150) NOT NULL,
  `supplier_address` varchar(255) DEFAULT NULL,
  `supplier_phone` varchar(20) DEFAULT NULL,
  `supplier_email` varchar(120) DEFAULT NULL,
  `note` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `supplier_address`, `supplier_phone`, `supplier_email`, `note`, `created_at`, `updated_at`) VALUES
(1, 'Công ty TNHH Gia Phát Shoes', '45 Nguyễn Hữu Cảnh, Q. Bình Thạnh, TP. HCM', '0908 334 556', 'giaphat.shoes@gmail.com', 'Chuyên cung cấp giày thể thao nam, nữ; hàng Việt Nam xuất khẩu.', '2025-11-02 13:32:08', '2025-11-02 13:32:08'),
(3, 'Công ty CP Sản Xuất Giày An Bình', '82 Tô Hiến Thành, Quận 10, TP. HCM', '0912 778 990', 'contact@anbinhfootwear.vn', 'Cung cấp giày da thật, giày tây cho phân khúc cao cấp.', '2025-11-02 13:35:11', '2025-11-02 13:35:11'),
(4, 'Công ty TNHH SneakerWorld Việt Nam', '23 Phan Xích Long, Phú Nhuận, TP. HCM', '0939 221 887', 'sales@sneakerworld.vn', 'Nhập khẩu giày Adidas, Nike, Converse chính hãng.', '2025-11-02 13:37:26', '2025-11-02 13:37:26'),
(5, 'Công ty TNHH Lacoste Việt Nam', 'Số 17 Lê Duẩn, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh, Việt Nam', '028 3823 4567', 'contact@lacoste-vietnam.vn', 'Nhà phân phối các sản phẩm giày sneaker nam, thời trang và phụ kiện thương hiệu Lacoste chính hãng tại thị trường Việt Nam.', '2025-12-14 11:44:43', '2025-12-14 11:44:43');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `phone_number`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Tiết Hoàng', 'vinhy115@gmail.com', '$2y$10$uGO4a6i77l73XKvM9tXVtujkUzqCxkgcPBqP8UTvnPltI43M6r7Ay', '0367070318', 'sóc trăng', 'Active', '2025-09-11 15:21:54', '2025-09-11 15:21:54');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_admin_email` (`email`),
  ADD KEY `idx_admin_status` (`status`),
  ADD KEY `idx_admin_type` (`type`);

--
-- Chỉ mục cho bảng `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_brand_name` (`name`),
  ADD KEY `idx_brand_status` (`status`);

--
-- Chỉ mục cho bảng `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_category_name` (`name`),
  ADD KEY `idx_category_status` (`status`),
  ADD KEY `fk_category_gender` (`gender_id`);

--
-- Chỉ mục cho bảng `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_feedback_email` (`email`);

--
-- Chỉ mục cho bảng `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_news_title` (`title`),
  ADD KEY `idx_news_newscategory_id` (`newscategory_id`),
  ADD KEY `idx_news_status` (`status`);

--
-- Chỉ mục cho bảng `newscategory`
--
ALTER TABLE `newscategory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_newscategory_name` (`name`),
  ADD KEY `idx_newscategory_status` (`status`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_orders_user_id` (`user_id`),
  ADD KEY `idx_orders_status` (`status`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_details_order_id` (`order_id`),
  ADD KEY `idx_order_details_product_id` (`product_id`),
  ADD KEY `fk_od_size` (`size_id`);

--
-- Chỉ mục cho bảng `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_product_name` (`name`),
  ADD KEY `idx_product_category_id` (`category_id`),
  ADD KEY `idx_product_brand_id` (`brand_id`);

--
-- Chỉ mục cho bảng `product_size`
--
ALTER TABLE `product_size`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_product_size` (`product_id`,`size`);

--
-- Chỉ mục cho bảng `purchase_receipts`
--
ALTER TABLE `purchase_receipts`
  ADD PRIMARY KEY (`receipt_id`),
  ADD KEY `idx_pr_supplier` (`supplier_id`),
  ADD KEY `idx_pr_created_by` (`created_by`),
  ADD KEY `idx_pr_date` (`receipt_date`);

--
-- Chỉ mục cho bảng `purchase_receipt_items`
--
ALTER TABLE `purchase_receipt_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_receipt_product_size` (`receipt_id`,`product_id`,`size_id`),
  ADD KEY `idx_pr_items_receipt` (`receipt_id`),
  ADD KEY `idx_pr_items_product` (`product_id`),
  ADD KEY `fk_pri_size` (`size_id`);

--
-- Chỉ mục cho bảng `shipping_status`
--
ALTER TABLE `shipping_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_shipping_status_order_id` (`order_id`);

--
-- Chỉ mục cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`),
  ADD UNIQUE KEY `uq_suppliers_email` (`supplier_email`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_user_email` (`email`),
  ADD KEY `idx_user_status` (`status`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `brand`
--
ALTER TABLE `brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `gender`
--
ALTER TABLE `gender`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `newscategory`
--
ALTER TABLE `newscategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `product_size`
--
ALTER TABLE `product_size`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=280;

--
-- AUTO_INCREMENT cho bảng `purchase_receipts`
--
ALTER TABLE `purchase_receipts`
  MODIFY `receipt_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `purchase_receipt_items`
--
ALTER TABLE `purchase_receipt_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT cho bảng `shipping_status`
--
ALTER TABLE `shipping_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `fk_category_gender` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`id`);

--
-- Các ràng buộc cho bảng `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_newscategory` FOREIGN KEY (`newscategory_id`) REFERENCES `newscategory` (`id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_od_size` FOREIGN KEY (`size_id`) REFERENCES `product_size` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_details_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_details_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_brand` FOREIGN KEY (`brand_id`) REFERENCES `brand` (`id`),
  ADD CONSTRAINT `fk_product_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

--
-- Các ràng buộc cho bảng `product_size`
--
ALTER TABLE `product_size`
  ADD CONSTRAINT `product_size_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Các ràng buộc cho bảng `purchase_receipts`
--
ALTER TABLE `purchase_receipts`
  ADD CONSTRAINT `fk_pr_created_by_admin` FOREIGN KEY (`created_by`) REFERENCES `admin` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pr_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `purchase_receipt_items`
--
ALTER TABLE `purchase_receipt_items`
  ADD CONSTRAINT `fk_pr_items_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pr_items_receipt` FOREIGN KEY (`receipt_id`) REFERENCES `purchase_receipts` (`receipt_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pri_size` FOREIGN KEY (`size_id`) REFERENCES `product_size` (`id`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `shipping_status`
--
ALTER TABLE `shipping_status`
  ADD CONSTRAINT `fk_shipping_status_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
