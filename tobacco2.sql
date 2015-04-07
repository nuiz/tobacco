-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 07, 2015 at 02:45 AM
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
  `level_id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `cluster_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `username`, `password`, `auth_token`, `level_id`, `firstname`, `lastname`, `cluster_id`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', '111111', '74a500a2eee1b8274dae468ddb4892fb', 1, 'superadmin', 'superadmin', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(2, 'admin', '111111', 'dbf15f4586d616c706b1a0452b77c5a0', 2, 'admin', 'admin', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(3, 'cluster_1', '123456', 'a516e21a009882f9dd3ea5064d614908', 3, 'cluster1', 'cluster1', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(4, 'cluster_2', '111111', '', 3, 'cluster2', 'cluster2', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(5, 'cluster_3', '111111', '', 3, 'cluster3', 'cluster3', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(6, 'cluster_4', '111111', 'abe385a720287044eebb729f69aab0b8', 3, 'cluster4', 'cluster4', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(7, 'clusteraaaa', '111111', '', 3, 'clusteraaaa', 'clusteraaaa', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(12, 'test_user', '111111', '', 0, 'สุวิจักขณ์', 'บึงประเสริฐสุข', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(13, 'test_user_0', '111111', '', 0, 'สุวิช', 'ดีมี', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(14, 'test_user_1', '111111', '', 0, 'สุวิจักขณ์', 'สัภยาหงษ์สกุล', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(15, 'test_user_2', '111111', '', 0, 'สกานต์', 'ลิ่มนิธิกานต์', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(16, 'test_user_3', '111111', '', 0, 'ผกาพันธุ์', 'นุมาศ', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(17, 'test_user_4', '111111', '', 0, 'พัชร์กันต์', 'อนันต์นฤนาถ', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(18, 'test_user_5', '111111', '', 0, 'วิลาศิณี', 'เลียวสิริไพโรจน์', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(19, 'test_user_6', '111111', '', 0, 'พิพัฒน์', 'พงศ์ไพจิตร', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(20, 'test_user_7', '111111', '', 0, 'ชเยศ', 'จิตชาญวิชัย', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(21, 'test_user_8', '111111', '', 0, 'สุนา', 'ดวงเพชร', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(22, 'test_user_9', '111111', '', 0, 'สุชาติ', 'สุโขรัมย์', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(23, 'test_user_10', '111111', '', 0, 'เสนม', 'แมนประโคน', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(24, 'test_user_11', '111111', '', 0, 'วัชรากร', 'พันชน', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(25, 'test_user_12', '111111', '', 0, 'สมโภช', 'กิยะแพทย์', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(26, 'test_user_13', '111111', '', 0, 'เคือง', 'เป็นครบ', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(50, 'test_user_37', '111111', '', 4, 'ธงชัย', 'ใจดี', 6, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(51, 'test_user_38', '111111', '', 0, 'อนันต์', 'พลาสติก', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(52, 'test_user_39', '111111', '', 0, 'พัชราภรณ์', 'จันทร์เหนือ', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(53, 'test_user_40', '111111', '', 0, 'นนทนันท์', 'เมฆโปธิ', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(54, 'test_user_41', '111111', '', 4, 'พีรพล', 'ออชัยธง', 3, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(55, 'test_user_42', '111111', '', 0, 'อัญทิกา', 'ทาส้าว', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(56, 'test_user_43', '111111', '', 0, 'ธีรพล', 'วงค์ลังกา', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(57, 'test_user_44', '111111', '', 0, 'วรพงษ์', 'ศรีสุวรรณ์', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(58, 'test_user_45', '111111', '', 0, 'ปฏิณญา', 'รัตนพงค์', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00'),
(59, 'test_user_46', '111111', '', 0, 'ภานุมาศ', 'ประกายเพชร', NULL, '2015-02-10 10:25:00', '2015-02-10 10:25:00');

-- --------------------------------------------------------

--
-- Table structure for table `book_place`
--

CREATE TABLE IF NOT EXISTS `book_place` (
`book_place_id` int(11) NOT NULL,
  `book_place_name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `book_place`
--

INSERT INTO `book_place` (`book_place_id`, `book_place_name`) VALUES
(1, 'ห้องสมุด 1'),
(2, 'ห้องสมุด 2'),
(3, 'ห้องสมุด 3'),
(4, 'ห้องสมุด 4'),
(5, 'ห้องสมุด 5');

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
('maxsize_attach_file_upload', '40M'),
('maxsize_book_upload', '40M'),
('maxsize_image_upload', '40M'),
('maxsize_video_upload', '40M');

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
  `content_type` varchar(50) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`content_id`, `content_name`, `content_description`, `account_id`, `category_id`, `content_type`, `created_at`, `updated_at`) VALUES
(1, 'ทดสอบ', 'ทดสอบ ทดสอบ ทดสอบ', 1, 3, 'video', 1428358870, 1428358870),
(2, 'ทดสอบ ebook', 'ทดสอบ ebook', 1, 7, 'book', 1428359988, 1428359988);

-- --------------------------------------------------------

--
-- Table structure for table `content_attach_file`
--

CREATE TABLE IF NOT EXISTS `content_attach_file` (
`id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `content_book`
--

CREATE TABLE IF NOT EXISTS `content_book` (
  `content_id` int(11) NOT NULL,
  `book_path` varchar(255) NOT NULL,
  `book_author` varchar(255) DEFAULT NULL,
  `book_date` date DEFAULT NULL,
  `book_publishing_house` varchar(255) DEFAULT NULL,
  `book_type_id` int(11) NOT NULL,
  `book_cover_path` varchar(255) NOT NULL,
  `book_places` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `content_book`
--

INSERT INTO `content_book` (`content_id`, `book_path`, `book_author`, `book_date`, `book_publishing_house`, `book_type_id`, `book_cover_path`, `book_places`) VALUES
(2, 'file55230b34221eb.pdf', 'ทดสอบ ชื่อผู้แต่ง', '2015-02-16', 'ทดสอบ สำนักพิมพ์', 2, 'file55230b3422da3.jpg', '["\\u0e2b\\u0e49\\u0e2d\\u0e07\\u0e2a\\u0e21\\u0e38\\u0e14 3","\\u0e2b\\u0e49\\u0e2d\\u0e07\\u0e2a\\u0e21\\u0e38\\u0e14 4"]');

-- --------------------------------------------------------

--
-- Table structure for table `content_video`
--

CREATE TABLE IF NOT EXISTS `content_video` (
`id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `video_path` varchar(255) NOT NULL,
  `video_thumb_path` varchar(255) NOT NULL,
  `video_name` varchar(255) NOT NULL,
  `seq` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `content_video`
--

INSERT INTO `content_video` (`id`, `content_id`, `video_path`, `video_thumb_path`, `video_name`, `seq`) VALUES
(1, 1, 'file552306d64aa9b.mp4', 'file552306d6546dd.jpeg', 'Adam Levine - Lost Stars', 1),
(2, 1, 'file552306d654ead.mp4', 'file552306d65c3df.jpeg', 'Passenger - Let Her Go', 2);

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE IF NOT EXISTS `faq` (
`faq_id` int(11) NOT NULL,
  `faq_question` varchar(255) NOT NULL,
  `faq_answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE IF NOT EXISTS `guru` (
`guru_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `guru_cat_id` int(11) NOT NULL,
  `guru_history` text NOT NULL,
  `guru_telephone` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`guru_id`, `account_id`, `guru_cat_id`, `guru_history`, `guru_telephone`) VALUES
(1, 50, 1, 'ttttt', '0844444444'),
(4, 19, 11, 'คุมงานก่อหินมาก่อน', '0811111111'),
(5, 26, 3, 'เคเคเคเเคค', '0111111111'),
(6, 59, 14, 'asca', 'cc');

-- --------------------------------------------------------

--
-- Table structure for table `guru_category`
--

CREATE TABLE IF NOT EXISTS `guru_category` (
`guru_cat_id` int(11) NOT NULL,
  `guru_cat_name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `guru_category`
--

INSERT INTO `guru_category` (`guru_cat_id`, `guru_cat_name`) VALUES
(1, 'ผู้เชี่ยวชาญกฎหมาย'),
(2, 'ผู้เชี่ยวชาญด้านการผลิต'),
(3, 'ผู้เชี่ยชาญการพิมพ์'),
(4, 'ผู้เชี่ยวชาญการแพทย์'),
(5, 'ผู้เชี่ยวชาญใบยา'),
(6, 'ผู้เชี่ยวชาญเทคโนโลยี'),
(7, 'ผู้เชี่ยวชาญบริหาร'),
(8, 'ผู้เชี่ยวชาญผลิตภัณฑ์'),
(9, 'ผู้เชี่ยชาญบัญชีและงบประมาน'),
(10, 'ผู้เชี่ยวชาญการวิจัย'),
(11, 'ผู้เชี่ยวชาญการช่าง'),
(12, 'ผู้เชี่ยวชาญการตลาดการขาย'),
(13, 'ผู้เชี่ยวชาญกำกับตรวจสอบ'),
(14, 'ผู้เชี่ยวชาญด้านแผนยุทธศาสตร์');

-- --------------------------------------------------------

--
-- Table structure for table `kmcenter`
--

CREATE TABLE IF NOT EXISTS `kmcenter` (
`kmcenter_id` int(11) NOT NULL,
  `kmcenter_name` varchar(255) NOT NULL,
  `kmcenter_map_pic` varchar(255) NOT NULL,
  `kmcenter_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(2, 'admin', '2015-02-03 17:45:25', '2015-02-03 17:45:25'),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
 ADD PRIMARY KEY (`account_id`), ADD KEY `auth_token` (`auth_token`);

--
-- Indexes for table `book_place`
--
ALTER TABLE `book_place`
 ADD PRIMARY KEY (`book_place_id`);

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
-- Indexes for table `faq`
--
ALTER TABLE `faq`
 ADD PRIMARY KEY (`faq_id`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
 ADD PRIMARY KEY (`guru_id`);

--
-- Indexes for table `guru_category`
--
ALTER TABLE `guru_category`
 ADD PRIMARY KEY (`guru_cat_id`);

--
-- Indexes for table `kmcenter`
--
ALTER TABLE `kmcenter`
 ADD PRIMARY KEY (`kmcenter_id`);

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
MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT for table `book_place`
--
ALTER TABLE `book_place`
MODIFY `book_place_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
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
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `content_video`
--
ALTER TABLE `content_video`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
MODIFY `faq_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
MODIFY `guru_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `guru_category`
--
ALTER TABLE `guru_category`
MODIFY `guru_cat_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `kmcenter`
--
ALTER TABLE `kmcenter`
MODIFY `kmcenter_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
