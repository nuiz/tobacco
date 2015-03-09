-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2015 at 01:56 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tobacco2`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE IF NOT EXISTS `account` (
`account_id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `auth_token` varchar(100) NOT NULL,
  `level_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `username`, `password`, `auth_token`, `level_id`) VALUES
(1, 'super_admin', '111111', '74a500a2eee1b8274dae468ddb4892fb', 1),
(2, 'admin', '111111', 'dbf15f4586d616c706b1a0452b77c5a0', 2),
(3, 'cluster_1', '123456', '', 3),
(4, 'cluster_2', '111111', '', 3),
(5, 'cluster_3', '111111', '', 3),
(6, 'cluster_4', '111111', 'abe385a720287044eebb729f69aab0b8', 3),
(7, 'cluster_5', '222222', '', 3),
(8, 'cluster_6', 'zzzzzz', '', 3),
(9, 'cluster_test', '111111', '', 3),
(10, 'cccccc', '444444', '', 3),
(11, 'cluster_vvvv', 'aaa', '', 3),
(12, 'test_user', '111111', '', 0),
(13, 'test_user_0', '111111', '', 0),
(14, 'test_user_1', '111111', '', 0),
(15, 'test_user_2', '111111', '', 0),
(16, 'test_user_3', '111111', '', 0),
(17, 'test_user_4', '111111', '', 0),
(18, 'test_user_5', '111111', '', 0),
(19, 'test_user_6', '111111', '', 0),
(20, 'test_user_7', '111111', '', 0),
(21, 'test_user_8', '111111', '', 0),
(22, 'test_user_9', '111111', '', 0),
(23, 'test_user_10', '111111', '', 0),
(24, 'test_user_11', '111111', '', 0),
(25, 'test_user_12', '111111', '', 0),
(26, 'test_user_13', '111111', '', 0),
(27, 'test_user_14', '111111', '', 0),
(28, 'test_user_15', '111111', '', 0),
(29, 'test_user_16', '111111', '', 0),
(30, 'test_user_17', '111111', '', 0),
(31, 'test_user_18', '111111', '', 0),
(32, 'test_user_19', '111111', '', 0),
(33, 'test_user_20', '111111', '', 0),
(34, 'test_user_21', '111111', '', 0),
(35, 'test_user_22', '111111', '', 0),
(36, 'test_user_23', '111111', '', 0),
(37, 'test_user_24', '111111', '', 0),
(38, 'test_user_25', '111111', '', 0),
(39, 'test_user_26', '111111', '', 0),
(40, 'test_user_27', '111111', '', 0),
(41, 'test_user_28', '111111', '', 0),
(42, 'test_user_29', '111111', '', 0),
(43, 'test_user_30', '111111', '', 0),
(44, 'test_user_31', '111111', '', 0),
(45, 'test_user_32', '111111', '', 0),
(46, 'test_user_33', '111111', '', 0),
(47, 'test_user_34', '111111', '', 0),
(48, 'test_user_35', '111111', '', 0),
(49, 'test_user_36', '111111', '', 0),
(50, 'test_user_37', '111111', '', 4),
(51, 'test_user_38', '111111', '', 4),
(52, 'test_user_39', '111111', '', 4),
(53, 'test_user_40', '111111', '', 4),
(54, 'test_user_41', '111111', '', 0),
(55, 'test_user_42', '111111', '', 0),
(56, 'test_user_43', '111111', '', 0),
(57, 'test_user_44', '111111', '', 0),
(58, 'test_user_45', '111111', '', 0),
(59, 'test_user_46', '111111', '', 0),
(60, 'test_user_47', '111111', '', 0),
(61, 'test_user_48', '111111', '', 0),
(62, 'test_user_49', '111111', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `book_type`
--

CREATE TABLE IF NOT EXISTS `book_type` (
  `book_type_id` int(11) NOT NULL,
  `book_type_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `book_type`
--

INSERT INTO `book_type` (`book_type_id`, `book_type_name`) VALUES
(1, 'นิตยสาร'),
(2, 'หนังสือ');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
`category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `parent_id`) VALUES
(1, 'กฎหมาย', 0),
(2, 'จัดซื้อ/จัดจ้าง', 0),
(3, 'เทคโนโลยี', 0),
(4, 'การพิมพ์', 0),
(5, 'การผลิต', 0),
(6, 'การวิจัย', 0),
(7, 'ใบยา', 0),
(8, 'ความปลอดภัยและสิ่งแวดล้อม', 0),
(9, 'บุคคล/บริหาร', 0),
(10, 'งานช่าง', 0),
(11, 'โครงการย้ายโรงงานใหม่', 0),
(12, 'กำกับตรวจสอบ', 0),
(13, 'บัญชี การเงิน งบประมาน', 0),
(14, 'ผลิตภัณฑ์', 0),
(15, 'การแพทย์ อาหาร/สุขภาพ', 0),
(16, 'คาวมรู้ทั่วไป', 0),
(17, 'แผนยุทธศาสตร์', 0),
(18, 'การตลาด การขาย', 0),
(19, 'อบรมภายใน', 9),
(20, 'อบรมภายนอก', 9),
(21, 'เอกสารวิชาการต่างประเทศ', 6),
(22, 'วิธีทดสอบ', 6),
(23, 'รายงานโครงการ', 6),
(24, 'รายงานการดูงาน อบรมในประเทศ', 6),
(25, 'รายงานการดูงาน อบรมต่างประเทศ', 6),
(26, 'เอกสารระบบมาตรฐาน', 6),
(27, 'กฏหมายที่เกี่ยวข้องกับอุตสาหกรรมยาสูบ', 6),
(28, 'E-Cigarette', 6),
(29, 'Link เว็บเอกสารต่างๆ', 6),
(30, 'กลุ่มโรค', 15),
(31, 'กิจกรรมพัฒนาคุณภาพ', 15),
(32, 'ประเภทการปรับปรุงคุณภาพงาน', 15),
(33, ' รายงานการอบรมภายนอก', 15),
(34, 'ความรู้สำหรับประชาชน', 15),
(35, 'ฮาร์ดแวร์', 3),
(36, 'เน็ตเวิร์ก', 3),
(37, 'ระบบปฏิบัติการ', 3),
(38, 'ซอฟท์แวร์', 3),
(39, 'ความเสี่ยง/การควบคุมภายใน', 19),
(40, 'งานบุคคล/แรงงานสัมพันธ์/วิสาหกิจ/กองทุน', 19),
(41, 'ด้านจัดซื้อ/จัดจ้าง/ประมูล/ราคา', 19),
(42, 'บริหาร', 19),
(43, 'การผลิต/เครื่องจักร', 19),
(44, 'การตลาด/ขาย/โปรโมชั่น', 19),
(45, 'ใบยา', 19),
(46, 'ความปลอดภัย/อาชีวอนามัย/สิ่งแวดล้อม', 19),
(47, 'เทคโนโลยีสารสนเทศ', 19),
(48, 'การแพทย์', 19),
(49, 'บัญชีและการเงิน', 19),
(50, 'ด้านวิศกรรมและพัฒนา/ก่อสร้าง/ราคากลาง', 19),
(51, 'ภาษาต่างประเทศ', 19),
(52, 'ด้านกฏหมาย/แรงงาน', 19),
(53, 'ด้านการพิมพ์/กาว', 19),
(54, 'ส่วนตรวจสอบ', 19),
(55, 'งานคุณภาพ/KM/Kaizen/OCC/5ส./ISO', 19),
(56, 'ดูงานภายในปะรเทศ', 19),
(57, 'ดูงานต่างประเทศ', 19),
(58, 'ด้านการประกันภัย', 19),
(59, 'จริยธรรม/คุณธรรม/ธรรมาภิบาล', 19),
(60, 'ระบบประเมินองค์กร/SEPA/TRIS', 19),
(61, 'ความเสี่ยง/การควบคุมภายใน', 20),
(62, 'งานบุคคล/แรงงานสัมพันธ์/วิสาหกิจ/กองทุน', 20),
(63, 'ด้านจัดซื้อ/จัดจ้าง/ประมูล/ราคา', 20),
(64, 'บริหาร', 20),
(65, 'การผลิต/เครื่องจักร', 20),
(66, 'การตลาด/ขาย/โปรโมชั่น', 20),
(67, 'ใบยา', 20),
(68, 'ความปลอดภัย/อาชีวอนามัย/สิ่งแวดล้อม', 20),
(69, 'เทคโนโลยีสารสนเทศ', 20),
(70, 'การแพทย์', 20),
(71, 'บัญชีและการเงิน', 20),
(72, 'ด้านวิศกรรมและพัฒนา/ก่อสร้าง/ราคากลาง', 20),
(73, 'ภาษาต่างประเทศ', 20),
(74, 'ด้านกฏหมาย/แรงงาน', 20),
(75, 'ด้านการพิมพ์/กาว', 20),
(76, 'ส่วนตรวจสอบ', 20),
(77, 'งานคุณภาพ/KM/Kaizen/OCC/5ส./ISO', 20),
(78, 'ดูงานภายในปะรเทศ', 20),
(79, 'ดูงานต่างประเทศ', 20),
(80, 'ด้านการประกันภัย', 20),
(81, 'จริยธรรม/คุณธรรม/ธรรมาภิบาล', 20),
(82, 'ระบบประเมินองค์กร/SEPA/TRIS', 20),
(83, 'ต่างประเทศ', 22),
(84, 'ฝ่ายวิจัยฯ', 22),
(85, 'Coresta', 25),
(86, 'TSRC', 25),
(87, 'ACS', 25),
(88, 'R&D visit', 25),
(89, 'อบรมเครื่องมือและห้องปฏิบัติการ', 25),
(90, 'อายุรกรรม', 30),
(91, 'ศัลยกรรม', 30),
(92, 'สูติ นรีเวช', 30),
(93, 'กุมารเวช', 30),
(94, 'สร้างเสริมสุขภาพ', 30),
(95, 'อาชีวเวชศาสตร์', 30),
(96, 'KM', 31),
(97, 'Kaizen', 31),
(98, 'QCC', 31),
(99, 'CQI และ อื่นๆ', 31),
(100, 'ลดต้นทุน/ค่าใช้จ่าย', 32),
(101, 'เพิ่มผลผลิต/ประสิทธิภาพ', 32),
(102, 'ความปลอดภัย', 32),
(103, 'อนุรักษ์พลังงาน/คำนึงถึงสิ่งแวดล้อม', 32),
(104, 'เครื่องคอมพิวเตอร์ส่วนบุคคล', 35),
(105, 'เครื่องคอมพิวเตอร์พกพา', 35),
(106, 'อุปกรณ์พกพาไร้สาย ขนาดกลาง', 35),
(107, 'อุปกรณ์มือถือ', 35),
(108, 'Windows', 37),
(109, 'Windows Mobile', 37),
(110, 'Android', 37),
(111, 'IOS', 37),
(112, 'Symbian', 37),
(113, 'Linux', 37),
(114, 'ธุรกิจ', 38),
(115, 'การสื่อสาร', 38),
(116, 'การเงิน', 38),
(117, 'บันเทิง', 38),
(118, 'การศึกษา', 38),
(119, 'ข่าวสาร', 38),
(120, 'อรรถประโยชน์', 38);

-- --------------------------------------------------------

--
-- Table structure for table `config_system`
--

CREATE TABLE IF NOT EXISTS `config_system` (
  `config_name` varchar(255) NOT NULL,
  `config_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `config_system`
--

INSERT INTO `config_system` (`config_name`, `config_value`) VALUES
('extension_upload', 'jpeg,png,mp4'),
('upload_max_filesize', '40M');

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE IF NOT EXISTS `content` (
`content_id` int(11) NOT NULL,
  `content_name` varchar(250) NOT NULL,
  `content_description` varchar(255) DEFAULT NULL,
  `account_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `content_type` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`content_id`, `content_name`, `content_description`, `account_id`, `category_id`, `content_type`) VALUES
(1, 'test VidEO', 'test VidEO test VidEO', 1, 1, 'video'),
(2, 'test', 'test', 1, 1, 'video');

-- --------------------------------------------------------

--
-- Table structure for table `content_attach_file`
--

CREATE TABLE IF NOT EXISTS `content_attach_file` (
`id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `content_attach_file`
--

INSERT INTO `content_attach_file` (`id`, `content_id`, `file_name`, `file_path`) VALUES
(1, 1, '1.docx', 'file54fd6ffe7bfa9.docx');

-- --------------------------------------------------------

--
-- Table structure for table `content_book`
--

CREATE TABLE IF NOT EXISTS `content_book` (
  `content_id` int(11) NOT NULL,
  `book_path` varchar(255) NOT NULL,
  `book_author` varchar(255) DEFAULT NULL,
  `book_date` datetime DEFAULT NULL,
  `book_publishing_house` varchar(255) DEFAULT NULL,
  `book_type_id` int(11) NOT NULL,
  `book_cover_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `content_video`
--

CREATE TABLE IF NOT EXISTS `content_video` (
`id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `video_path` varchar(255) NOT NULL,
  `video_thumb_path` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `content_video`
--

INSERT INTO `content_video` (`id`, `content_id`, `video_path`, `video_thumb_path`) VALUES
(1, 1, 'file54fd6ffe769b8.mp4', 'file54fd6ffe77958.jpeg'),
(2, 1, 'file54fd6ffe78128.mp4', 'file54fd6ffe7b009.jpeg'),
(3, 2, 'file54fd8c5537756.mp4', 'file54fd8c5538ec7.jpeg'),
(4, 2, 'file54fd8c5539e67.mp4', 'file54fd8c553f070.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE IF NOT EXISTS `level` (
  `level_id` int(11) NOT NULL,
  `level_name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`level_id`, `level_name`, `created_at`, `updated_at`) VALUES
(0, 'user', '2015-02-03 17:45:25', '2015-02-03 17:45:25'),
(1, 'super_admin', '2015-02-03 17:45:25', '2015-02-03 17:45:25'),
(2, 'cluster_it', '2015-02-03 17:45:25', '2015-02-03 17:45:25'),
(3, 'cluster', '2015-02-03 17:45:25', '2015-02-03 17:45:25'),
(4, 'writer', '2015-02-03 17:45:25', '2015-02-03 17:45:25');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
`news_id` int(11) NOT NULL,
  `news_name` varchar(255) NOT NULL,
  `news_description` text NOT NULL,
  `news_image_path` varchar(255) NOT NULL,
  `account_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`news_id`, `news_name`, `news_description`, `news_image_path`, `account_id`) VALUES
(5, 'ทดสอบ', 'ทดสอบ ทดสอบ ทดสอบ', 'file54eef4812ba2c.jpg', 1),
(6, 'Test', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry''s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 'file54f3ddc8e95d2.jpg', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
 ADD PRIMARY KEY (`account_id`), ADD KEY `auth_token` (`auth_token`);

--
-- Indexes for table `book_type`
--
ALTER TABLE `book_type`
 ADD PRIMARY KEY (`book_type_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
 ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `config_system`
--
ALTER TABLE `config_system`
 ADD PRIMARY KEY (`config_name`);

--
-- Indexes for table `content`
--
ALTER TABLE `content`
 ADD PRIMARY KEY (`content_id`);

--
-- Indexes for table `content_attach_file`
--
ALTER TABLE `content_attach_file`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `content_book`
--
ALTER TABLE `content_book`
 ADD PRIMARY KEY (`content_id`);

--
-- Indexes for table `content_video`
--
ALTER TABLE `content_video`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
 ADD PRIMARY KEY (`level_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
 ADD PRIMARY KEY (`news_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=121;
--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
MODIFY `content_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `content_attach_file`
--
ALTER TABLE `content_attach_file`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `content_video`
--
ALTER TABLE `content_video`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
