-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3308
-- Generation Time: Dec 28, 2023 at 06:57 AM
-- Server version: 5.7.24
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clinic2`
--
CREATE DATABASE IF NOT EXISTS `clinic2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `clinic2`;

-- --------------------------------------------------------

--
-- Table structure for table `admin_module`
--

DROP TABLE IF EXISTS `admin_module`;
CREATE TABLE IF NOT EXISTS `admin_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) NOT NULL,
  `module_description` varchar(100) NOT NULL,
  `parent_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `module_name` (`module_name`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_module`
--

INSERT INTO `admin_module` (`id`, `module_name`, `module_description`, `parent_id`) VALUES
(1, 'admin_user', 'User Account', 1),
(2, 'admin_role', 'Role', 1),
(3, 'admin_permission', 'Permission', 1),
(4, 'admin_module', 'Module', 1),
(5, 'admin_parent', 'Parent Module', 1),
(6, 'maintenance_db', 'Database', 2),
(7, 'queue_general', 'General Queue', 3),
(8, 'maintenance_settings', 'Settings', 2),
(9, 'setup_common_status', 'Common Status', 6),
(10, 'data_client', 'Client', 7),
(11, 'data_insurance', 'Insurance', 7),
(12, 'data_trans_type', 'Trans Type', 7),
(13, 'data_product', 'Product', 7),
(14, 'data_category', 'Category', 7),
(15, 'data_result_set', 'Result Set', 7),
(16, 'data_package', 'Package', 7),
(17, 'data_location', 'Location', 7),
(18, 'data_uom', 'UOM', 7),
(19, 'current_dashboard', 'Dashboard', 8),
(20, 'current_transaction', 'Transaction', 8),
(21, 'location_cashier', 'Cashier', 9),
(22, 'location_xray', 'X-Ray', 9),
(23, 'location_laboratory', 'Laboratory', 9),
(24, 'location_audiometry', 'Audiometry', 9),
(25, 'location_spiro', 'Spiro', 9),
(26, 'location_ecg', 'ECG', 9),
(27, 'location_doctor_office', 'Doctor\'s Office', 9),
(28, 'location_pharmacy', 'Pharmacy', 9),
(29, 'location_triage', 'Triage', 9),
(30, 'Location_preemp', 'Pre-Employment', 9),
(31, 'report_patient', 'Patient', 12),
(32, 'report_transaction', 'Transaction', 12),
(33, 'report_cash', 'Cash', 12),
(34, 'report_po', 'PO', 12),
(35, 'data_patient', 'Patient', 7),
(36, 'data_payment_type', 'Payment Type', 7),
(37, 'data_payment_method', 'Payment Method', 7),
(38, 'location_reception', 'Reception', 9),
(39, 'accounting_invoice_charges', 'Invoice Charges', 13),
(40, 'accounting_invoice_insurance', 'Invoice Insurance', 13),
(41, 'pharmacy_transaction', 'Transaction', 14),
(42, 'pharmacy_receiving', 'Receive Item', 14),
(43, 'pharmacy_inventory', 'Inventory', 14);

-- --------------------------------------------------------

--
-- Table structure for table `admin_mod_perm`
--

DROP TABLE IF EXISTS `admin_mod_perm`;
CREATE TABLE IF NOT EXISTS `admin_mod_perm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_id` (`role_id`,`module_id`,`permission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=543 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_mod_perm`
--

INSERT INTO `admin_mod_perm` (`id`, `role_id`, `module_id`, `permission_id`) VALUES
(1, 1, 1, 1),
(2, 1, 1, 2),
(3, 1, 2, 1),
(4, 1, 3, 1),
(84, 1, 3, 2),
(85, 1, 3, 3),
(5, 1, 4, 1),
(6, 1, 4, 2),
(7, 1, 4, 3),
(88, 1, 5, 1),
(87, 1, 5, 2),
(86, 1, 5, 3),
(92, 1, 6, 1),
(93, 1, 6, 8),
(94, 1, 7, 8),
(89, 1, 8, 1),
(90, 1, 8, 2),
(91, 1, 8, 3),
(114, 1, 9, 1),
(116, 1, 9, 2),
(115, 1, 9, 3),
(113, 1, 9, 6),
(120, 1, 10, 1),
(121, 1, 11, 1),
(126, 1, 12, 1),
(124, 1, 13, 1),
(119, 1, 14, 1),
(125, 1, 15, 1),
(123, 1, 16, 1),
(122, 1, 17, 1),
(143, 1, 18, 6),
(117, 1, 19, 8),
(118, 1, 20, 1),
(129, 1, 21, 1),
(138, 1, 22, 1),
(133, 1, 23, 1),
(128, 1, 24, 1),
(136, 1, 25, 1),
(131, 1, 26, 1),
(130, 1, 27, 1),
(134, 1, 28, 1),
(137, 1, 29, 1),
(135, 1, 30, 1),
(140, 1, 31, 1),
(142, 1, 32, 1),
(139, 1, 33, 1),
(141, 1, 34, 1),
(144, 1, 35, 6),
(148, 1, 36, 1),
(150, 1, 36, 2),
(149, 1, 36, 3),
(147, 1, 36, 6),
(152, 1, 37, 1),
(154, 1, 37, 2),
(153, 1, 37, 3),
(151, 1, 37, 6),
(211, 1, 38, 1),
(212, 1, 38, 2),
(214, 1, 38, 3),
(213, 1, 38, 6),
(215, 1, 39, 1),
(216, 1, 40, 1),
(217, 1, 41, 1),
(218, 1, 42, 1),
(219, 1, 43, 1),
(95, 2, 1, 1),
(96, 2, 1, 2),
(382, 2, 1, 3),
(97, 2, 2, 1),
(383, 2, 2, 3),
(107, 2, 6, 1),
(108, 2, 6, 8),
(110, 2, 8, 1),
(111, 2, 8, 2),
(112, 2, 8, 3),
(396, 2, 10, 1),
(430, 2, 10, 2),
(451, 2, 10, 3),
(452, 2, 10, 6),
(397, 2, 10, 16),
(431, 2, 11, 1),
(432, 2, 11, 2),
(455, 2, 11, 3),
(456, 2, 11, 6),
(433, 2, 13, 1),
(434, 2, 13, 2),
(463, 2, 13, 3),
(464, 2, 13, 6),
(425, 2, 14, 1),
(426, 2, 14, 2),
(447, 2, 14, 3),
(448, 2, 14, 6),
(435, 2, 18, 1),
(436, 2, 18, 2),
(467, 2, 18, 3),
(468, 2, 18, 6),
(384, 2, 19, 14),
(386, 2, 20, 7),
(385, 2, 20, 14),
(471, 2, 20, 20),
(472, 2, 20, 21),
(481, 2, 20, 26),
(419, 2, 21, 7),
(421, 2, 21, 13),
(517, 2, 21, 17),
(420, 2, 21, 22),
(424, 2, 21, 23),
(423, 2, 21, 24),
(443, 2, 21, 25),
(473, 2, 22, 13),
(529, 2, 22, 17),
(475, 2, 22, 18),
(476, 2, 22, 19),
(474, 2, 22, 21),
(415, 2, 23, 13),
(414, 2, 23, 14),
(520, 2, 23, 17),
(417, 2, 23, 18),
(418, 2, 23, 19),
(416, 2, 23, 20),
(483, 2, 24, 7),
(482, 2, 24, 10),
(516, 2, 24, 17),
(487, 2, 24, 18),
(488, 2, 24, 19),
(485, 2, 24, 20),
(484, 2, 24, 21),
(486, 2, 24, 26),
(504, 2, 25, 7),
(503, 2, 25, 10),
(527, 2, 25, 17),
(509, 2, 25, 18),
(510, 2, 25, 19),
(507, 2, 25, 20),
(506, 2, 25, 21),
(508, 2, 25, 26),
(497, 2, 26, 7),
(496, 2, 26, 10),
(519, 2, 26, 17),
(501, 2, 26, 18),
(502, 2, 26, 19),
(499, 2, 26, 20),
(498, 2, 26, 21),
(500, 2, 26, 26),
(490, 2, 27, 7),
(489, 2, 27, 10),
(521, 2, 27, 15),
(518, 2, 27, 17),
(494, 2, 27, 18),
(495, 2, 27, 19),
(492, 2, 27, 20),
(491, 2, 27, 21),
(493, 2, 27, 26),
(511, 2, 28, 13),
(524, 2, 28, 17),
(512, 2, 28, 18),
(513, 2, 28, 19),
(407, 2, 29, 7),
(406, 2, 29, 10),
(408, 2, 29, 13),
(409, 2, 29, 15),
(410, 2, 29, 16),
(411, 2, 29, 17),
(412, 2, 29, 18),
(413, 2, 29, 19),
(399, 2, 30, 7),
(398, 2, 30, 10),
(400, 2, 30, 13),
(401, 2, 30, 15),
(402, 2, 30, 16),
(403, 2, 30, 17),
(404, 2, 30, 18),
(405, 2, 30, 19),
(394, 2, 35, 1),
(395, 2, 35, 2),
(459, 2, 35, 3),
(460, 2, 35, 6),
(387, 2, 38, 1),
(388, 2, 38, 2),
(392, 2, 38, 7),
(389, 2, 38, 10),
(390, 2, 38, 11),
(391, 2, 38, 12),
(393, 2, 38, 13),
(526, 2, 38, 17),
(437, 2, 39, 10),
(438, 2, 40, 10),
(337, 4, 20, 7),
(336, 4, 20, 14),
(339, 4, 20, 20),
(338, 4, 20, 21),
(340, 4, 20, 26),
(342, 4, 24, 7),
(341, 4, 24, 10),
(534, 4, 24, 15),
(536, 4, 24, 16),
(535, 4, 24, 17),
(346, 4, 24, 18),
(347, 4, 24, 19),
(344, 4, 24, 20),
(343, 4, 24, 21),
(345, 4, 24, 26),
(363, 4, 25, 7),
(362, 4, 25, 10),
(541, 4, 25, 15),
(542, 4, 25, 16),
(540, 4, 25, 17),
(367, 4, 25, 18),
(368, 4, 25, 19),
(365, 4, 25, 20),
(364, 4, 25, 21),
(366, 4, 25, 26),
(356, 4, 26, 7),
(355, 4, 26, 10),
(538, 4, 26, 15),
(537, 4, 26, 16),
(539, 4, 26, 17),
(360, 4, 26, 18),
(361, 4, 26, 19),
(358, 4, 26, 20),
(357, 4, 26, 21),
(359, 4, 26, 26),
(349, 4, 27, 7),
(348, 4, 27, 10),
(532, 4, 27, 15),
(531, 4, 27, 17),
(353, 4, 27, 18),
(354, 4, 27, 19),
(351, 4, 27, 20),
(350, 4, 27, 21),
(352, 4, 27, 26),
(266, 5, 20, 14),
(267, 5, 21, 7),
(269, 5, 21, 13),
(268, 5, 21, 22),
(272, 5, 21, 23),
(270, 5, 21, 24),
(260, 6, 20, 14),
(262, 6, 23, 13),
(261, 6, 23, 14),
(264, 6, 23, 18),
(265, 6, 23, 19),
(263, 6, 23, 20),
(228, 7, 20, 14),
(230, 7, 30, 7),
(229, 7, 30, 10),
(231, 7, 30, 13),
(232, 7, 30, 15),
(233, 7, 30, 16),
(234, 7, 30, 17),
(235, 7, 30, 18),
(236, 7, 30, 19),
(515, 8, 6, 10),
(309, 8, 10, 1),
(310, 8, 10, 2),
(311, 8, 10, 3),
(312, 8, 10, 6),
(313, 8, 11, 1),
(314, 8, 11, 2),
(315, 8, 11, 3),
(316, 8, 11, 6),
(321, 8, 13, 1),
(322, 8, 13, 2),
(323, 8, 13, 3),
(324, 8, 13, 6),
(305, 8, 14, 1),
(306, 8, 14, 2),
(307, 8, 14, 3),
(308, 8, 14, 6),
(326, 8, 18, 1),
(327, 8, 18, 2),
(328, 8, 18, 3),
(329, 8, 18, 6),
(298, 8, 20, 14),
(299, 8, 21, 7),
(301, 8, 21, 13),
(300, 8, 21, 22),
(303, 8, 21, 23),
(302, 8, 21, 24),
(304, 8, 21, 25),
(317, 8, 35, 1),
(318, 8, 35, 2),
(319, 8, 35, 3),
(320, 8, 35, 6),
(375, 8, 39, 10),
(376, 8, 40, 10),
(330, 9, 20, 14),
(331, 9, 20, 21),
(332, 9, 22, 13),
(334, 9, 22, 18),
(335, 9, 22, 19),
(333, 9, 22, 21),
(369, 10, 20, 14),
(373, 10, 24, 13),
(371, 10, 24, 18),
(372, 10, 24, 19),
(377, 13, 19, 14),
(378, 13, 20, 14),
(381, 13, 28, 13),
(379, 13, 28, 18),
(380, 13, 28, 19),
(155, 14, 1, 1),
(156, 14, 1, 2),
(157, 14, 2, 1),
(158, 14, 3, 1),
(159, 14, 3, 2),
(160, 14, 3, 3),
(161, 14, 4, 1),
(162, 14, 4, 2),
(163, 14, 4, 3),
(164, 14, 5, 1),
(165, 14, 5, 2),
(166, 14, 5, 3),
(167, 14, 6, 1),
(168, 14, 6, 8),
(169, 14, 7, 8),
(170, 14, 8, 1),
(171, 14, 8, 2),
(172, 14, 8, 3),
(173, 14, 9, 1),
(174, 14, 9, 2),
(175, 14, 9, 3),
(176, 14, 9, 6),
(177, 14, 10, 1),
(178, 14, 11, 1),
(179, 14, 12, 1),
(180, 14, 13, 1),
(181, 14, 14, 1),
(182, 14, 15, 1),
(183, 14, 16, 1),
(184, 14, 17, 1),
(185, 14, 18, 6),
(186, 14, 19, 8),
(187, 14, 20, 1),
(198, 14, 31, 1),
(199, 14, 32, 1),
(200, 14, 33, 1),
(201, 14, 34, 1),
(202, 14, 35, 6),
(203, 14, 36, 1),
(204, 14, 36, 2),
(205, 14, 36, 3),
(206, 14, 36, 6),
(207, 14, 37, 1),
(208, 14, 37, 2),
(209, 14, 37, 3),
(210, 14, 37, 6),
(282, 15, 10, 1),
(283, 15, 10, 2),
(220, 15, 20, 7),
(285, 15, 35, 1),
(284, 15, 35, 2),
(221, 15, 38, 1),
(222, 15, 38, 2),
(226, 15, 38, 7),
(223, 15, 38, 10),
(224, 15, 38, 11),
(225, 15, 38, 12),
(227, 15, 38, 13),
(237, 16, 20, 14),
(253, 16, 29, 7),
(252, 16, 29, 10),
(254, 16, 29, 13),
(255, 16, 29, 15),
(256, 16, 29, 16),
(257, 16, 29, 17),
(258, 16, 29, 18),
(259, 16, 29, 19),
(288, 17, 10, 1),
(289, 17, 10, 2),
(290, 17, 11, 1),
(291, 17, 11, 2),
(294, 17, 13, 1),
(295, 17, 13, 2),
(286, 17, 14, 1),
(287, 17, 14, 2),
(296, 17, 18, 1),
(297, 17, 18, 2),
(273, 17, 20, 14),
(274, 17, 21, 7),
(275, 17, 21, 13),
(276, 17, 21, 22),
(277, 17, 21, 23),
(278, 17, 21, 24),
(292, 17, 35, 1),
(293, 17, 35, 2),
(280, 17, 39, 10),
(281, 17, 40, 10);

-- --------------------------------------------------------

--
-- Table structure for table `admin_parent`
--

DROP TABLE IF EXISTS `admin_parent`;
CREATE TABLE IF NOT EXISTS `admin_parent` (
  `parent_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_name` varchar(100) NOT NULL,
  `parent_description` varchar(100) NOT NULL,
  `parent_icon` varchar(50) NOT NULL DEFAULT '',
  `parent_order` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`parent_id`),
  UNIQUE KEY `parent_name` (`parent_name`),
  UNIQUE KEY `parent_order` (`parent_order`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_parent`
--

INSERT INTO `admin_parent` (`parent_id`, `parent_name`, `parent_description`, `parent_icon`, `parent_order`) VALUES
(1, 'Administration', 'Administration', 'fa-gear', 100),
(2, 'Maintenance', 'Maintenance', 'fa-wrench', 99),
(3, 'Queuing', 'Queuing', 'fa-sort-alpha-asc', 98),
(4, 'Dashboard', 'Dashboard', 'fa-home', 97),
(5, 'Home', 'Home', 'fa-home', 96),
(6, 'Setup', 'Setup', 'fa-thumbs-up', 95),
(7, 'Data', 'Data', 'fa-tags', 4),
(8, 'Current', 'Current', 'fa-home', 1),
(9, 'Location', 'Location', 'fa-arrow-right', 2),
(11, 'Accounts', 'Accounts', 'fa-list', 3),
(12, 'Report', 'Report', ' fa-bar-chart-o', 5),
(13, 'Accounting', 'Accounting', 'fa-list', 6),
(14, 'Pharmacy', 'Pharmacy', 'fa-medkit', 7);

-- --------------------------------------------------------

--
-- Table structure for table `admin_permission`
--

DROP TABLE IF EXISTS `admin_permission`;
CREATE TABLE IF NOT EXISTS `admin_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission` (`permission`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_permission`
--

INSERT INTO `admin_permission` (`id`, `permission`) VALUES
(6, 'Activate'),
(1, 'Add'),
(5, 'Audit'),
(12, 'Cancel'),
(11, 'Complete'),
(3, 'Delete'),
(9, 'Export'),
(8, 'Generate'),
(22, 'Insurance Invoice'),
(15, 'Item Add'),
(19, 'Item Complete'),
(17, 'Item Delete'),
(16, 'Item Modify'),
(18, 'Item Working'),
(20, 'Lab Result'),
(2, 'Modify'),
(24, 'Payment Add'),
(25, 'Payment Delete'),
(23, 'Payment View'),
(26, 'Prescription'),
(7, 'Print'),
(13, 'Send'),
(4, 'Sendback'),
(10, 'Update'),
(14, 'View'),
(21, 'Xray Result');

-- --------------------------------------------------------

--
-- Table structure for table `admin_role`
--

DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE IF NOT EXISTS `admin_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_role`
--

INSERT INTO `admin_role` (`id`, `role_name`) VALUES
(8, 'Accounts Head'),
(17, 'Accounts Staff'),
(1, 'Administrator'),
(10, 'Audiometry'),
(5, 'Cashier'),
(4, 'Doctor'),
(11, 'ECG'),
(6, 'Laboratory'),
(14, 'Moderator'),
(13, 'Pharmacy'),
(7, 'Pre-Employment'),
(15, 'Reception'),
(12, 'Spirometry'),
(16, 'Triage'),
(3, 'User'),
(2, 'VIP'),
(9, 'X-ray');

-- --------------------------------------------------------

--
-- Table structure for table `app_currency`
--

DROP TABLE IF EXISTS `app_currency`;
CREATE TABLE IF NOT EXISTS `app_currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(100) NOT NULL,
  `currency` varchar(50) NOT NULL,
  `code` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=268 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_currency`
--

INSERT INTO `app_currency` (`id`, `country`, `currency`, `code`) VALUES
(1, 'AFGHANISTAN', 'Afghani', 'AFN'),
(2, 'ALBANIA', 'Lek', 'ALL'),
(3, 'ALGERIA', 'Algerian Dinar', 'DZD'),
(4, 'AMERICAN SAMOA', 'US Dollar', 'USD'),
(5, 'ANDORRA', 'Euro', 'EUR'),
(6, 'ANGOLA', 'Kwanza', 'AOA'),
(7, 'ANGUILLA', 'East Caribbean Dollar', 'XCD'),
(8, 'ANTIGUA AND BARBUDA', 'East Caribbean Dollar', 'XCD'),
(9, 'ARGENTINA', 'Argentine Peso', 'ARS'),
(10, 'ARMENIA', 'Armenian Dram', 'AMD'),
(11, 'ARUBA', 'Aruban Florin', 'AWG'),
(12, 'AUSTRALIA', 'Australian Dollar', 'AUD'),
(13, 'AUSTRIA', 'Euro', 'EUR'),
(14, 'AZERBAIJAN', 'Azerbaijanian Manat', 'AZN'),
(15, 'BAHAMAS (THE)', 'Bahamian Dollar', 'BSD'),
(16, 'BAHRAIN', 'Bahraini Dinar', 'BHD'),
(17, 'BANGLADESH', 'Taka', 'BDT'),
(18, 'BARBADOS', 'Barbados Dollar', 'BBD'),
(19, 'BELARUS', 'Belarussian Ruble', 'BYN'),
(20, 'BELGIUM', 'Euro', 'EUR'),
(21, 'BELIZE', 'Belize Dollar', 'BZD'),
(22, 'BENIN', 'CFA Franc BCEAO', 'XOF'),
(23, 'BERMUDA', 'Bermudian Dollar', 'BMD'),
(24, 'BHUTAN', 'Ngultrum', 'BTN'),
(25, 'BHUTAN', 'Indian Rupee', 'INR'),
(26, 'BOLIVIA (PLURINATIONAL STATE OF)', 'Boliviano', 'BOB'),
(27, 'BOLIVIA (PLURINATIONAL STATE OF)', 'Mvdol', 'BOV'),
(28, 'BONAIRE, SINT EUSTATIUS AND SABA', 'US Dollar', 'USD'),
(29, 'BOSNIA AND HERZEGOVINA', 'Convertible Mark', 'BAM'),
(30, 'BOTSWANA', 'Pula', 'BWP'),
(31, 'BOUVET ISLAND', 'Norwegian Krone', 'NOK'),
(32, 'BRAZIL', 'Brazilian Real', 'BRL'),
(33, 'BRITISH INDIAN OCEAN TERRITORY (THE)', 'US Dollar', 'USD'),
(34, 'BRUNEI DARUSSALAM', 'Brunei Dollar', 'BND'),
(35, 'BULGARIA', 'Bulgarian Lev', 'BGN'),
(36, 'BURKINA FASO', 'CFA Franc BCEAO', 'XOF'),
(37, 'BURUNDI', 'Burundi Franc', 'BIF'),
(38, 'CABO VERDE', 'Cabo Verde Escudo', 'CVE'),
(39, 'CAMBODIA', 'Riel', 'KHR'),
(40, 'CAMEROON', 'CFA Franc BEAC', 'XAF'),
(41, 'CANADA', 'Canadian Dollar', 'CAD'),
(42, 'CAYMAN ISLANDS (THE)', 'Cayman Islands Dollar', 'KYD'),
(43, 'CENTRAL AFRICAN REPUBLIC (THE)', 'CFA Franc BEAC', 'XAF'),
(44, 'CHAD', 'CFA Franc BEAC', 'XAF'),
(45, 'CHILE', 'Unidad de Fomento', 'CLF'),
(46, 'CHILE', 'Chilean Peso', 'CLP'),
(47, 'CHINA', 'Yuan Renminbi', 'CNY'),
(48, 'CHRISTMAS ISLAND', 'Australian Dollar', 'AUD'),
(49, 'COCOS (KEELING) ISLANDS (THE)', 'Australian Dollar', 'AUD'),
(50, 'COLOMBIA', 'Colombian Peso', 'COP'),
(51, 'COLOMBIA', 'Unidad de Valor Real', 'COU'),
(52, 'COMOROS (THE)', 'Comoro Franc', 'KMF'),
(53, 'CONGO (THE DEMOCRATIC REPUBLIC OF THE)', 'Congolese Franc', 'CDF'),
(54, 'CONGO (THE)', 'CFA Franc BEAC', 'XAF'),
(55, 'COOK ISLANDS (THE)', 'New Zealand Dollar', 'NZD'),
(56, 'COSTA RICA', 'Costa Rican Colon', 'CRC'),
(57, 'CROATIA', 'Kuna', 'HRK'),
(58, 'CUBA', 'Peso Convertible', 'CUC'),
(59, 'CUBA', 'Cuban Peso', 'CUP'),
(60, 'CURAÇAO', 'Netherlands Antillean Guilder', 'ANG'),
(61, 'CYPRUS', 'Euro', 'EUR'),
(62, 'CZECH REPUBLIC (THE)', 'Czech Koruna', 'CZK'),
(63, 'CÔTE D\'IVOIRE', 'CFA Franc BCEAO', 'XOF'),
(64, 'DENMARK', 'Danish Krone', 'DKK'),
(65, 'DJIBOUTI', 'Djibouti Franc', 'DJF'),
(66, 'DOMINICA', 'East Caribbean Dollar', 'XCD'),
(67, 'DOMINICAN REPUBLIC (THE)', 'Dominican Peso', 'DOP'),
(68, 'ECUADOR', 'US Dollar', 'USD'),
(69, 'EGYPT', 'Egyptian Pound', 'EGP'),
(70, 'EL SALVADOR', 'El Salvador Colon', 'SVC'),
(71, 'EL SALVADOR', 'US Dollar', 'USD'),
(72, 'EQUATORIAL GUINEA', 'CFA Franc BEAC', 'XAF'),
(73, 'ERITREA', 'Nakfa', 'ERN'),
(74, 'ESTONIA', 'Euro', 'EUR'),
(75, 'ETHIOPIA', 'Ethiopian Birr', 'ETB'),
(76, 'EUROPEAN UNION', 'Euro', 'EUR'),
(77, 'FALKLAND ISLANDS (THE) [MALVINAS]', 'Falkland Islands Pound', 'FKP'),
(78, 'FAROE ISLANDS (THE)', 'Danish Krone', 'DKK'),
(79, 'FIJI', 'Fiji Dollar', 'FJD'),
(80, 'FINLAND', 'Euro', 'EUR'),
(81, 'FRANCE', 'Euro', 'EUR'),
(82, 'FRENCH GUIANA', 'Euro', 'EUR'),
(83, 'FRENCH POLYNESIA', 'CFP Franc', 'XPF'),
(84, 'FRENCH SOUTHERN TERRITORIES (THE)', 'Euro', 'EUR'),
(85, 'GABON', 'CFA Franc BEAC', 'XAF'),
(86, 'GAMBIA (THE)', 'Dalasi', 'GMD'),
(87, 'GEORGIA', 'Lari', 'GEL'),
(88, 'GERMANY', 'Euro', 'EUR'),
(89, 'GHANA', 'Ghana Cedi', 'GHS'),
(90, 'GIBRALTAR', 'Gibraltar Pound', 'GIP'),
(91, 'GREECE', 'Euro', 'EUR'),
(92, 'GREENLAND', 'Danish Krone', 'DKK'),
(93, 'GRENADA', 'East Caribbean Dollar', 'XCD'),
(94, 'GUADELOUPE', 'Euro', 'EUR'),
(95, 'GUAM', 'US Dollar', 'USD'),
(96, 'GUATEMALA', 'Quetzal', 'GTQ'),
(97, 'GUERNSEY', 'Pound Sterling', 'GBP'),
(98, 'GUINEA', 'Guinea Franc', 'GNF'),
(99, 'GUINEA-BISSAU', 'CFA Franc BCEAO', 'XOF'),
(100, 'GUYANA', 'Guyana Dollar', 'GYD'),
(101, 'HAITI', 'Gourde', 'HTG'),
(102, 'HAITI', 'US Dollar', 'USD'),
(103, 'HEARD ISLAND AND McDONALD ISLANDS', 'Australian Dollar', 'AUD'),
(104, 'HOLY SEE (THE)', 'Euro', 'EUR'),
(105, 'HONDURAS', 'Lempira', 'HNL'),
(106, 'HONG KONG', 'Hong Kong Dollar', 'HKD'),
(107, 'HUNGARY', 'Forint', 'HUF'),
(108, 'ICELAND', 'Iceland Krona', 'ISK'),
(109, 'INDIA', 'Indian Rupee', 'INR'),
(110, 'INDONESIA', 'Rupiah', 'IDR'),
(111, 'INTERNATIONAL MONETARY FUND (IMF) ', 'SDR (Special Drawing Right)', 'XDR'),
(112, 'IRAN (ISLAMIC REPUBLIC OF)', 'Iranian Rial', 'IRR'),
(113, 'IRAQ', 'Iraqi Dinar', 'IQD'),
(114, 'IRELAND', 'Euro', 'EUR'),
(115, 'ISLE OF MAN', 'Pound Sterling', 'GBP'),
(116, 'ISRAEL', 'New Israeli Sheqel', 'ILS'),
(117, 'ITALY', 'Euro', 'EUR'),
(118, 'JAMAICA', 'Jamaican Dollar', 'JMD'),
(119, 'JAPAN', 'Yen', 'JPY'),
(120, 'JERSEY', 'Pound Sterling', 'GBP'),
(121, 'JORDAN', 'Jordanian Dinar', 'JOD'),
(122, 'KAZAKHSTAN', 'Tenge', 'KZT'),
(123, 'KENYA', 'Kenyan Shilling', 'KES'),
(124, 'KIRIBATI', 'Australian Dollar', 'AUD'),
(125, 'KOREA (THE DEMOCRATIC PEOPLE’S REPUBLIC OF)', 'North Korean Won', 'KPW'),
(126, 'KOREA (THE REPUBLIC OF)', 'Won', 'KRW'),
(127, 'KUWAIT', 'Kuwaiti Dinar', 'KWD'),
(128, 'KYRGYZSTAN', 'Som', 'KGS'),
(129, 'LAO PEOPLE’S DEMOCRATIC REPUBLIC (THE)', 'Kip', 'LAK'),
(130, 'LATVIA', 'Euro', 'EUR'),
(131, 'LEBANON', 'Lebanese Pound', 'LBP'),
(132, 'LESOTHO', 'Loti', 'LSL'),
(133, 'LESOTHO', 'Rand', 'ZAR'),
(134, 'LIBERIA', 'Liberian Dollar', 'LRD'),
(135, 'LIBYA', 'Libyan Dinar', 'LYD'),
(136, 'LIECHTENSTEIN', 'Swiss Franc', 'CHF'),
(137, 'LITHUANIA', 'Euro', 'EUR'),
(138, 'LUXEMBOURG', 'Euro', 'EUR'),
(139, 'MACAO', 'Pataca', 'MOP'),
(140, 'MADAGASCAR', 'Malagasy Ariary', 'MGA'),
(141, 'MALAWI', 'Kwacha', 'MWK'),
(142, 'MALAYSIA', 'Malaysian Ringgit', 'MYR'),
(143, 'MALDIVES', 'Rufiyaa', 'MVR'),
(144, 'MALI', 'CFA Franc BCEAO', 'XOF'),
(145, 'MALTA', 'Euro', 'EUR'),
(146, 'MARSHALL ISLANDS (THE)', 'US Dollar', 'USD'),
(147, 'MARTINIQUE', 'Euro', 'EUR'),
(148, 'MAURITANIA', 'Ouguiya', 'MRU'),
(149, 'MAURITIUS', 'Mauritius Rupee', 'MUR'),
(150, 'MAYOTTE', 'Euro', 'EUR'),
(151, 'MEMBER COUNTRIES OF THE AFRICAN DEVELOPMENT BANK GROUP', 'ADB Unit of Account', 'XUA'),
(152, 'MEXICO', 'Mexican Peso', 'MXN'),
(153, 'MEXICO', 'Mexican Unidad de Inversion (UDI)', 'MXV'),
(154, 'MICRONESIA (FEDERATED STATES OF)', 'US Dollar', 'USD'),
(155, 'MOLDOVA (THE REPUBLIC OF)', 'Moldovan Leu', 'MDL'),
(156, 'MONACO', 'Euro', 'EUR'),
(157, 'MONGOLIA', 'Tugrik', 'MNT'),
(158, 'MONTENEGRO', 'Euro', 'EUR'),
(159, 'MONTSERRAT', 'East Caribbean Dollar', 'XCD'),
(160, 'MOROCCO', 'Moroccan Dirham', 'MAD'),
(161, 'MOZAMBIQUE', 'Mozambique Metical', 'MZN'),
(162, 'MYANMAR', 'Kyat', 'MMK'),
(163, 'NAMIBIA', 'Namibia Dollar', 'NAD'),
(164, 'NAMIBIA', 'Rand', 'ZAR'),
(165, 'NAURU', 'Australian Dollar', 'AUD'),
(166, 'NEPAL', 'Nepalese Rupee', 'NPR'),
(167, 'NETHERLANDS (THE)', 'Euro', 'EUR'),
(168, 'NEW CALEDONIA', 'CFP Franc', 'XPF'),
(169, 'NEW ZEALAND', 'New Zealand Dollar', 'NZD'),
(170, 'NICARAGUA', 'Cordoba Oro', 'NIO'),
(171, 'NIGER (THE)', 'CFA Franc BCEAO', 'XOF'),
(172, 'NIGERIA', 'Naira', 'NGN'),
(173, 'NIUE', 'New Zealand Dollar', 'NZD'),
(174, 'NORFOLK ISLAND', 'Australian Dollar', 'AUD'),
(175, 'NORTHERN MARIANA ISLANDS (THE)', 'US Dollar', 'USD'),
(176, 'NORWAY', 'Norwegian Krone', 'NOK'),
(177, 'OMAN', 'Rial Omani', 'OMR'),
(178, 'PAKISTAN', 'Pakistan Rupee', 'PKR'),
(179, 'PALAU', 'US Dollar', 'USD'),
(180, 'PALESTINE, STATE OF', 'No universal currency', ''),
(181, 'PANAMA', 'Balboa', 'PAB'),
(182, 'PANAMA', 'US Dollar', 'USD'),
(183, 'PAPUA NEW GUINEA', 'Kina', 'PGK'),
(184, 'PARAGUAY', 'Guarani', 'PYG'),
(185, 'PERU', 'Nuevo Sol', 'PEN'),
(186, 'PHILIPPINES (THE)', 'Philippine Peso', 'PHP'),
(187, 'PITCAIRN', 'New Zealand Dollar', 'NZD'),
(188, 'POLAND', 'Zloty', 'PLN'),
(189, 'PORTUGAL', 'Euro', 'EUR'),
(190, 'PUERTO RICO', 'US Dollar', 'USD'),
(191, 'QATAR', 'Qatari Rial', 'QAR'),
(192, 'REPUBLIC OF NORTH MACEDONIA', 'Denar', 'MKD'),
(193, 'ROMANIA', 'Romanian Leu', 'RON'),
(194, 'RUSSIAN FEDERATION (THE)', 'Russian Ruble', 'RUB'),
(195, 'RWANDA', 'Rwanda Franc', 'RWF'),
(196, 'RÉUNION', 'Euro', 'EUR'),
(197, 'SAINT BARTHÉLEMY', 'Euro', 'EUR'),
(198, 'SAINT HELENA, ASCENSION AND TRISTAN DA CUNHA', 'Saint Helena Pound', 'SHP'),
(199, 'SAINT KITTS AND NEVIS', 'East Caribbean Dollar', 'XCD'),
(200, 'SAINT LUCIA', 'East Caribbean Dollar', 'XCD'),
(201, 'SAINT MARTIN (FRENCH PART)', 'Euro', 'EUR'),
(202, 'SAINT PIERRE AND MIQUELON', 'Euro', 'EUR'),
(203, 'SAINT VINCENT AND THE GRENADINES', 'East Caribbean Dollar', 'XCD'),
(204, 'SAMOA', 'Tala', 'WST'),
(205, 'SAN MARINO', 'Euro', 'EUR'),
(206, 'SAO TOME AND PRINCIPE', 'Dobra', 'STN'),
(207, 'SAUDI ARABIA', 'Saudi Riyal', 'SAR'),
(208, 'SENEGAL', 'CFA Franc BCEAO', 'XOF'),
(209, 'SERBIA', 'Serbian Dinar', 'RSD'),
(210, 'SEYCHELLES', 'Seychelles Rupee', 'SCR'),
(211, 'SIERRA LEONE', 'Leone', 'SLL'),
(212, 'SINGAPORE', 'Singapore Dollar', 'SGD'),
(213, 'SINT MAARTEN (DUTCH PART)', 'Netherlands Antillean Guilder', 'ANG'),
(214, 'SISTEMA UNITARIO DE COMPENSACION REGIONAL DE PAGOS \"SUCRE\"', 'Sucre', 'XSU'),
(215, 'SLOVAKIA', 'Euro', 'EUR'),
(216, 'SLOVENIA', 'Euro', 'EUR'),
(217, 'SOLOMON ISLANDS', 'Solomon Islands Dollar', 'SBD'),
(218, 'SOMALIA', 'Somali Shilling', 'SOS'),
(219, 'SOUTH AFRICA', 'Rand', 'ZAR'),
(220, 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'No universal currency', ''),
(221, 'SOUTH SUDAN', 'South Sudanese Pound', 'SSP'),
(222, 'SPAIN', 'Euro', 'EUR'),
(223, 'SRI LANKA', 'Sri Lanka Rupee', 'LKR'),
(224, 'SUDAN (THE)', 'Sudanese Pound', 'SDG'),
(225, 'SURINAME', 'Surinam Dollar', 'SRD'),
(226, 'SVALBARD AND JAN MAYEN', 'Norwegian Krone', 'NOK'),
(227, 'SWAZILAND', 'Lilangeni', 'SZL'),
(228, 'SWEDEN', 'Swedish Krona', 'SEK'),
(229, 'SWITZERLAND', 'WIR Euro', 'CHE'),
(230, 'SWITZERLAND', 'Swiss Franc', 'CHF'),
(231, 'SWITZERLAND', 'WIR Franc', 'CHW'),
(232, 'SYRIAN ARAB REPUBLIC', 'Syrian Pound', 'SYP'),
(233, 'TAIWAN (PROVINCE OF CHINA)', 'New Taiwan Dollar', 'TWD'),
(234, 'TAJIKISTAN', 'Somoni', 'TJS'),
(235, 'TANZANIA, UNITED REPUBLIC OF', 'Tanzanian Shilling', 'TZS'),
(236, 'THAILAND', 'Baht', 'THB'),
(237, 'TIMOR-LESTE', 'US Dollar', 'USD'),
(238, 'TOGO', 'CFA Franc BCEAO', 'XOF'),
(239, 'TOKELAU', 'New Zealand Dollar', 'NZD'),
(240, 'TONGA', 'Pa’anga', 'TOP'),
(241, 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago Dollar', 'TTD'),
(242, 'TUNISIA', 'Tunisian Dinar', 'TND'),
(243, 'TURKEY', 'Turkish Lira', 'TRY'),
(244, 'TURKMENISTAN', 'Turkmenistan New Manat', 'TMT'),
(245, 'TURKS AND CAICOS ISLANDS (THE)', 'US Dollar', 'USD'),
(246, 'TUVALU', 'Australian Dollar', 'AUD'),
(247, 'UGANDA', 'Uganda Shilling', 'UGX'),
(248, 'UKRAINE', 'Hryvnia', 'UAH'),
(249, 'UNITED ARAB EMIRATES (THE)', 'UAE Dirham', 'AED'),
(250, 'UNITED KINGDOM OF GREAT BRITAIN AND NORTHERN IRELAND (THE)', 'Pound Sterling', 'GBP'),
(251, 'UNITED STATES MINOR OUTLYING ISLANDS (THE)', 'US Dollar', 'USD'),
(252, 'UNITED STATES OF AMERICA (THE)', 'US Dollar', 'USD'),
(253, 'UNITED STATES OF AMERICA (THE)', 'US Dollar (Next day)', 'USN'),
(254, 'URUGUAY', 'Uruguay Peso en Unidades Indexadas (URUIURUI)', 'UYI'),
(255, 'URUGUAY', 'Peso Uruguayo', 'UYU'),
(256, 'UZBEKISTAN', 'Uzbekistan Sum', 'UZS'),
(257, 'VANUATU', 'Vatu', 'VUV'),
(258, 'VENEZUELA (BOLIVARIAN REPUBLIC OF)', 'Bolivar', 'VEF'),
(259, 'VIET NAM', 'Dong', 'VND'),
(260, 'VIRGIN ISLANDS (BRITISH)', 'US Dollar', 'USD'),
(261, 'VIRGIN ISLANDS (U.S.)', 'US Dollar', 'USD'),
(262, 'WALLIS AND FUTUNA', 'CFP Franc', 'XPF'),
(263, 'WESTERN SAHARA', 'Moroccan Dirham', 'MAD'),
(264, 'YEMEN', 'Yemeni Rial', 'YER'),
(265, 'ZAMBIA', 'Zambian Kwacha', 'ZMW'),
(266, 'ZIMBABWE', 'Zimbabwe Dollar', 'ZWL'),
(267, 'ÅLAND ISLANDS', 'Euro', 'EUR');

-- --------------------------------------------------------

--
-- Table structure for table `app_details`
--

DROP TABLE IF EXISTS `app_details`;
CREATE TABLE IF NOT EXISTS `app_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_code` varchar(10) DEFAULT NULL,
  `app_name` varchar(100) DEFAULT NULL,
  `app_version` varchar(50) DEFAULT NULL,
  `company_code` varchar(10) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_address` varchar(200) DEFAULT NULL,
  `company_contact` varchar(50) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `company_logo` varchar(10) DEFAULT NULL,
  `session_prefix` varchar(10) NOT NULL,
  `email_protocol` varchar(10) DEFAULT NULL,
  `smtp_crypto` varchar(5) DEFAULT NULL,
  `smtp_host` varchar(50) DEFAULT NULL,
  `smtp_user` varchar(50) DEFAULT NULL,
  `smtp_pass` varchar(50) DEFAULT NULL,
  `smtp_port` int(11) DEFAULT NULL,
  `sms_api_code` varchar(255) DEFAULT NULL,
  `sms_mobile_no` varchar(20) DEFAULT NULL,
  `sms_email` varchar(50) DEFAULT NULL,
  `sms_name` varchar(100) DEFAULT NULL,
  `timezone_id` int(11) NOT NULL DEFAULT '292',
  `timer_countdown` int(11) NOT NULL DEFAULT '120' COMMENT 'seconds',
  `gst_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `currency_id` int(11) DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `dt_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_details`
--

INSERT INTO `app_details` (`id`, `app_code`, `app_name`, `app_version`, `company_code`, `company_name`, `company_address`, `company_contact`, `contact_person`, `company_logo`, `session_prefix`, `email_protocol`, `smtp_crypto`, `smtp_host`, `smtp_user`, `smtp_pass`, `smtp_port`, `sms_api_code`, `sms_mobile_no`, `sms_email`, `sms_name`, `timezone_id`, `timer_countdown`, `gst_percent`, `currency_id`, `updated_by`, `dt_updated`) VALUES
(1, 'NSO', 'noSystems Online', '1.0', 'LWMC', 'Lae Wellness Medical & Diagnostic Clinic', 'Lae City', '472 76666', '', 'png', 'clinic2', 'smtp', 'tls', 'smtp.gmail.com', 'support@frabellefpg.com', '!!fpg!!fr@b3ll3!', 587, 'TR-PACES754484_WDYSI', '223123', 'euay@asdf.com', 'asdfdf', 290, 120, '0.00', 183, 14, '2023-12-28 03:30:14');

-- --------------------------------------------------------

--
-- Table structure for table `app_timezone`
--

DROP TABLE IF EXISTS `app_timezone`;
CREATE TABLE IF NOT EXISTS `app_timezone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country_code` varchar(5) NOT NULL,
  `zone` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=426 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_timezone`
--

INSERT INTO `app_timezone` (`id`, `country_code`, `zone`) VALUES
(1, 'AD', 'Europe/Andorra'),
(2, 'AE', 'Asia/Dubai'),
(3, 'AF', 'Asia/Kabul'),
(4, 'AG', 'America/Antigua'),
(5, 'AI', 'America/Anguilla'),
(6, 'AL', 'Europe/Tirane'),
(7, 'AM', 'Asia/Yerevan'),
(8, 'AO', 'Africa/Luanda'),
(9, 'AQ', 'Antarctica/McMurdo'),
(10, 'AQ', 'Antarctica/Casey'),
(11, 'AQ', 'Antarctica/Davis'),
(12, 'AQ', 'Antarctica/DumontDUrville'),
(13, 'AQ', 'Antarctica/Mawson'),
(14, 'AQ', 'Antarctica/Palmer'),
(15, 'AQ', 'Antarctica/Rothera'),
(16, 'AQ', 'Antarctica/Syowa'),
(17, 'AQ', 'Antarctica/Troll'),
(18, 'AQ', 'Antarctica/Vostok'),
(19, 'AR', 'America/Argentina/Buenos_Aires'),
(20, 'AR', 'America/Argentina/Cordoba'),
(21, 'AR', 'America/Argentina/Salta'),
(22, 'AR', 'America/Argentina/Jujuy'),
(23, 'AR', 'America/Argentina/Tucuman'),
(24, 'AR', 'America/Argentina/Catamarca'),
(25, 'AR', 'America/Argentina/La_Rioja'),
(26, 'AR', 'America/Argentina/San_Juan'),
(27, 'AR', 'America/Argentina/Mendoza'),
(28, 'AR', 'America/Argentina/San_Luis'),
(29, 'AR', 'America/Argentina/Rio_Gallegos'),
(30, 'AR', 'America/Argentina/Ushuaia'),
(31, 'AS', 'Pacific/Pago_Pago'),
(32, 'AT', 'Europe/Vienna'),
(33, 'AU', 'Australia/Lord_Howe'),
(34, 'AU', 'Antarctica/Macquarie'),
(35, 'AU', 'Australia/Hobart'),
(36, 'AU', 'Australia/Currie'),
(37, 'AU', 'Australia/Melbourne'),
(38, 'AU', 'Australia/Sydney'),
(39, 'AU', 'Australia/Broken_Hill'),
(40, 'AU', 'Australia/Brisbane'),
(41, 'AU', 'Australia/Lindeman'),
(42, 'AU', 'Australia/Adelaide'),
(43, 'AU', 'Australia/Darwin'),
(44, 'AU', 'Australia/Perth'),
(45, 'AU', 'Australia/Eucla'),
(46, 'AW', 'America/Aruba'),
(47, 'AX', 'Europe/Mariehamn'),
(48, 'AZ', 'Asia/Baku'),
(49, 'BA', 'Europe/Sarajevo'),
(50, 'BB', 'America/Barbados'),
(51, 'BD', 'Asia/Dhaka'),
(52, 'BE', 'Europe/Brussels'),
(53, 'BF', 'Africa/Ouagadougou'),
(54, 'BG', 'Europe/Sofia'),
(55, 'BH', 'Asia/Bahrain'),
(56, 'BI', 'Africa/Bujumbura'),
(57, 'BJ', 'Africa/Porto-Novo'),
(58, 'BL', 'America/St_Barthelemy'),
(59, 'BM', 'Atlantic/Bermuda'),
(60, 'BN', 'Asia/Brunei'),
(61, 'BO', 'America/La_Paz'),
(62, 'BQ', 'America/Kralendijk'),
(63, 'BR', 'America/Noronha'),
(64, 'BR', 'America/Belem'),
(65, 'BR', 'America/Fortaleza'),
(66, 'BR', 'America/Recife'),
(67, 'BR', 'America/Araguaina'),
(68, 'BR', 'America/Maceio'),
(69, 'BR', 'America/Bahia'),
(70, 'BR', 'America/Sao_Paulo'),
(71, 'BR', 'America/Campo_Grande'),
(72, 'BR', 'America/Cuiaba'),
(73, 'BR', 'America/Santarem'),
(74, 'BR', 'America/Porto_Velho'),
(75, 'BR', 'America/Boa_Vista'),
(76, 'BR', 'America/Manaus'),
(77, 'BR', 'America/Eirunepe'),
(78, 'BR', 'America/Rio_Branco'),
(79, 'BS', 'America/Nassau'),
(80, 'BT', 'Asia/Thimphu'),
(81, 'BW', 'Africa/Gaborone'),
(82, 'BY', 'Europe/Minsk'),
(83, 'BZ', 'America/Belize'),
(84, 'CA', 'America/St_Johns'),
(85, 'CA', 'America/Halifax'),
(86, 'CA', 'America/Glace_Bay'),
(87, 'CA', 'America/Moncton'),
(88, 'CA', 'America/Goose_Bay'),
(89, 'CA', 'America/Blanc-Sablon'),
(90, 'CA', 'America/Toronto'),
(91, 'CA', 'America/Nipigon'),
(92, 'CA', 'America/Thunder_Bay'),
(93, 'CA', 'America/Iqaluit'),
(94, 'CA', 'America/Pangnirtung'),
(95, 'CA', 'America/Atikokan'),
(96, 'CA', 'America/Winnipeg'),
(97, 'CA', 'America/Rainy_River'),
(98, 'CA', 'America/Resolute'),
(99, 'CA', 'America/Rankin_Inlet'),
(100, 'CA', 'America/Regina'),
(101, 'CA', 'America/Swift_Current'),
(102, 'CA', 'America/Edmonton'),
(103, 'CA', 'America/Cambridge_Bay'),
(104, 'CA', 'America/Yellowknife'),
(105, 'CA', 'America/Inuvik'),
(106, 'CA', 'America/Creston'),
(107, 'CA', 'America/Dawson_Creek'),
(108, 'CA', 'America/Fort_Nelson'),
(109, 'CA', 'America/Vancouver'),
(110, 'CA', 'America/Whitehorse'),
(111, 'CA', 'America/Dawson'),
(112, 'CC', 'Indian/Cocos'),
(113, 'CD', 'Africa/Kinshasa'),
(114, 'CD', 'Africa/Lubumbashi'),
(115, 'CF', 'Africa/Bangui'),
(116, 'CG', 'Africa/Brazzaville'),
(117, 'CH', 'Europe/Zurich'),
(118, 'CI', 'Africa/Abidjan'),
(119, 'CK', 'Pacific/Rarotonga'),
(120, 'CL', 'America/Santiago'),
(121, 'CL', 'America/Punta_Arenas'),
(122, 'CL', 'Pacific/Easter'),
(123, 'CM', 'Africa/Douala'),
(124, 'CN', 'Asia/Shanghai'),
(125, 'CN', 'Asia/Urumqi'),
(126, 'CO', 'America/Bogota'),
(127, 'CR', 'America/Costa_Rica'),
(128, 'CU', 'America/Havana'),
(129, 'CV', 'Atlantic/Cape_Verde'),
(130, 'CW', 'America/Curacao'),
(131, 'CX', 'Indian/Christmas'),
(132, 'CY', 'Asia/Nicosia'),
(133, 'CY', 'Asia/Famagusta'),
(134, 'CZ', 'Europe/Prague'),
(135, 'DE', 'Europe/Berlin'),
(136, 'DE', 'Europe/Busingen'),
(137, 'DJ', 'Africa/Djibouti'),
(138, 'DK', 'Europe/Copenhagen'),
(139, 'DM', 'America/Dominica'),
(140, 'DO', 'America/Santo_Domingo'),
(141, 'DZ', 'Africa/Algiers'),
(142, 'EC', 'America/Guayaquil'),
(143, 'EC', 'Pacific/Galapagos'),
(144, 'EE', 'Europe/Tallinn'),
(145, 'EG', 'Africa/Cairo'),
(146, 'EH', 'Africa/El_Aaiun'),
(147, 'ER', 'Africa/Asmara'),
(148, 'ES', 'Europe/Madrid'),
(149, 'ES', 'Africa/Ceuta'),
(150, 'ES', 'Atlantic/Canary'),
(151, 'ET', 'Africa/Addis_Ababa'),
(152, 'FI', 'Europe/Helsinki'),
(153, 'FJ', 'Pacific/Fiji'),
(154, 'FK', 'Atlantic/Stanley'),
(155, 'FM', 'Pacific/Chuuk'),
(156, 'FM', 'Pacific/Pohnpei'),
(157, 'FM', 'Pacific/Kosrae'),
(158, 'FO', 'Atlantic/Faroe'),
(159, 'FR', 'Europe/Paris'),
(160, 'GA', 'Africa/Libreville'),
(161, 'GB', 'Europe/London'),
(162, 'GD', 'America/Grenada'),
(163, 'GE', 'Asia/Tbilisi'),
(164, 'GF', 'America/Cayenne'),
(165, 'GG', 'Europe/Guernsey'),
(166, 'GH', 'Africa/Accra'),
(167, 'GI', 'Europe/Gibraltar'),
(168, 'GL', 'America/Godthab'),
(169, 'GL', 'America/Danmarkshavn'),
(170, 'GL', 'America/Scoresbysund'),
(171, 'GL', 'America/Thule'),
(172, 'GM', 'Africa/Banjul'),
(173, 'GN', 'Africa/Conakry'),
(174, 'GP', 'America/Guadeloupe'),
(175, 'GQ', 'Africa/Malabo'),
(176, 'GR', 'Europe/Athens'),
(177, 'GS', 'Atlantic/South_Georgia'),
(178, 'GT', 'America/Guatemala'),
(179, 'GU', 'Pacific/Guam'),
(180, 'GW', 'Africa/Bissau'),
(181, 'GY', 'America/Guyana'),
(182, 'HK', 'Asia/Hong_Kong'),
(183, 'HN', 'America/Tegucigalpa'),
(184, 'HR', 'Europe/Zagreb'),
(185, 'HT', 'America/Port-au-Prince'),
(186, 'HU', 'Europe/Budapest'),
(187, 'ID', 'Asia/Jakarta'),
(188, 'ID', 'Asia/Pontianak'),
(189, 'ID', 'Asia/Makassar'),
(190, 'ID', 'Asia/Jayapura'),
(191, 'IE', 'Europe/Dublin'),
(192, 'IL', 'Asia/Jerusalem'),
(193, 'IM', 'Europe/Isle_of_Man'),
(194, 'IN', 'Asia/Kolkata'),
(195, 'IO', 'Indian/Chagos'),
(196, 'IQ', 'Asia/Baghdad'),
(197, 'IR', 'Asia/Tehran'),
(198, 'IS', 'Atlantic/Reykjavik'),
(199, 'IT', 'Europe/Rome'),
(200, 'JE', 'Europe/Jersey'),
(201, 'JM', 'America/Jamaica'),
(202, 'JO', 'Asia/Amman'),
(203, 'JP', 'Asia/Tokyo'),
(204, 'KE', 'Africa/Nairobi'),
(205, 'KG', 'Asia/Bishkek'),
(206, 'KH', 'Asia/Phnom_Penh'),
(207, 'KI', 'Pacific/Tarawa'),
(208, 'KI', 'Pacific/Enderbury'),
(209, 'KI', 'Pacific/Kiritimati'),
(210, 'KM', 'Indian/Comoro'),
(211, 'KN', 'America/St_Kitts'),
(212, 'KP', 'Asia/Pyongyang'),
(213, 'KR', 'Asia/Seoul'),
(214, 'KW', 'Asia/Kuwait'),
(215, 'KY', 'America/Cayman'),
(216, 'KZ', 'Asia/Almaty'),
(217, 'KZ', 'Asia/Qyzylorda'),
(218, 'KZ', 'Asia/Qostanay'),
(219, 'KZ', 'Asia/Aqtobe'),
(220, 'KZ', 'Asia/Aqtau'),
(221, 'KZ', 'Asia/Atyrau'),
(222, 'KZ', 'Asia/Oral'),
(223, 'LA', 'Asia/Vientiane'),
(224, 'LB', 'Asia/Beirut'),
(225, 'LC', 'America/St_Lucia'),
(226, 'LI', 'Europe/Vaduz'),
(227, 'LK', 'Asia/Colombo'),
(228, 'LR', 'Africa/Monrovia'),
(229, 'LS', 'Africa/Maseru'),
(230, 'LT', 'Europe/Vilnius'),
(231, 'LU', 'Europe/Luxembourg'),
(232, 'LV', 'Europe/Riga'),
(233, 'LY', 'Africa/Tripoli'),
(234, 'MA', 'Africa/Casablanca'),
(235, 'MC', 'Europe/Monaco'),
(236, 'MD', 'Europe/Chisinau'),
(237, 'ME', 'Europe/Podgorica'),
(238, 'MF', 'America/Marigot'),
(239, 'MG', 'Indian/Antananarivo'),
(240, 'MH', 'Pacific/Majuro'),
(241, 'MH', 'Pacific/Kwajalein'),
(242, 'MK', 'Europe/Skopje'),
(243, 'ML', 'Africa/Bamako'),
(244, 'MM', 'Asia/Yangon'),
(245, 'MN', 'Asia/Ulaanbaatar'),
(246, 'MN', 'Asia/Hovd'),
(247, 'MN', 'Asia/Choibalsan'),
(248, 'MO', 'Asia/Macau'),
(249, 'MP', 'Pacific/Saipan'),
(250, 'MQ', 'America/Martinique'),
(251, 'MR', 'Africa/Nouakchott'),
(252, 'MS', 'America/Montserrat'),
(253, 'MT', 'Europe/Malta'),
(254, 'MU', 'Indian/Mauritius'),
(255, 'MV', 'Indian/Maldives'),
(256, 'MW', 'Africa/Blantyre'),
(257, 'MX', 'America/Mexico_City'),
(258, 'MX', 'America/Cancun'),
(259, 'MX', 'America/Merida'),
(260, 'MX', 'America/Monterrey'),
(261, 'MX', 'America/Matamoros'),
(262, 'MX', 'America/Mazatlan'),
(263, 'MX', 'America/Chihuahua'),
(264, 'MX', 'America/Ojinaga'),
(265, 'MX', 'America/Hermosillo'),
(266, 'MX', 'America/Tijuana'),
(267, 'MX', 'America/Bahia_Banderas'),
(268, 'MY', 'Asia/Kuala_Lumpur'),
(269, 'MY', 'Asia/Kuching'),
(270, 'MZ', 'Africa/Maputo'),
(271, 'NA', 'Africa/Windhoek'),
(272, 'NC', 'Pacific/Noumea'),
(273, 'NE', 'Africa/Niamey'),
(274, 'NF', 'Pacific/Norfolk'),
(275, 'NG', 'Africa/Lagos'),
(276, 'NI', 'America/Managua'),
(277, 'NL', 'Europe/Amsterdam'),
(278, 'NO', 'Europe/Oslo'),
(279, 'NP', 'Asia/Kathmandu'),
(280, 'NR', 'Pacific/Nauru'),
(281, 'NU', 'Pacific/Niue'),
(282, 'NZ', 'Pacific/Auckland'),
(283, 'NZ', 'Pacific/Chatham'),
(284, 'OM', 'Asia/Muscat'),
(285, 'PA', 'America/Panama'),
(286, 'PE', 'America/Lima'),
(287, 'PF', 'Pacific/Tahiti'),
(288, 'PF', 'Pacific/Marquesas'),
(289, 'PF', 'Pacific/Gambier'),
(290, 'PG', 'Pacific/Port_Moresby'),
(291, 'PG', 'Pacific/Bougainville'),
(292, 'PH', 'Asia/Manila'),
(293, 'PK', 'Asia/Karachi'),
(294, 'PL', 'Europe/Warsaw'),
(295, 'PM', 'America/Miquelon'),
(296, 'PN', 'Pacific/Pitcairn'),
(297, 'PR', 'America/Puerto_Rico'),
(298, 'PS', 'Asia/Gaza'),
(299, 'PS', 'Asia/Hebron'),
(300, 'PT', 'Europe/Lisbon'),
(301, 'PT', 'Atlantic/Madeira'),
(302, 'PT', 'Atlantic/Azores'),
(303, 'PW', 'Pacific/Palau'),
(304, 'PY', 'America/Asuncion'),
(305, 'QA', 'Asia/Qatar'),
(306, 'RE', 'Indian/Reunion'),
(307, 'RO', 'Europe/Bucharest'),
(308, 'RS', 'Europe/Belgrade'),
(309, 'RU', 'Europe/Kaliningrad'),
(310, 'RU', 'Europe/Moscow'),
(311, 'UA', 'Europe/Simferopol'),
(312, 'RU', 'Europe/Kirov'),
(313, 'RU', 'Europe/Astrakhan'),
(314, 'RU', 'Europe/Volgograd'),
(315, 'RU', 'Europe/Saratov'),
(316, 'RU', 'Europe/Ulyanovsk'),
(317, 'RU', 'Europe/Samara'),
(318, 'RU', 'Asia/Yekaterinburg'),
(319, 'RU', 'Asia/Omsk'),
(320, 'RU', 'Asia/Novosibirsk'),
(321, 'RU', 'Asia/Barnaul'),
(322, 'RU', 'Asia/Tomsk'),
(323, 'RU', 'Asia/Novokuznetsk'),
(324, 'RU', 'Asia/Krasnoyarsk'),
(325, 'RU', 'Asia/Irkutsk'),
(326, 'RU', 'Asia/Chita'),
(327, 'RU', 'Asia/Yakutsk'),
(328, 'RU', 'Asia/Khandyga'),
(329, 'RU', 'Asia/Vladivostok'),
(330, 'RU', 'Asia/Ust-Nera'),
(331, 'RU', 'Asia/Magadan'),
(332, 'RU', 'Asia/Sakhalin'),
(333, 'RU', 'Asia/Srednekolymsk'),
(334, 'RU', 'Asia/Kamchatka'),
(335, 'RU', 'Asia/Anadyr'),
(336, 'RW', 'Africa/Kigali'),
(337, 'SA', 'Asia/Riyadh'),
(338, 'SB', 'Pacific/Guadalcanal'),
(339, 'SC', 'Indian/Mahe'),
(340, 'SD', 'Africa/Khartoum'),
(341, 'SE', 'Europe/Stockholm'),
(342, 'SG', 'Asia/Singapore'),
(343, 'SH', 'Atlantic/St_Helena'),
(344, 'SI', 'Europe/Ljubljana'),
(345, 'SJ', 'Arctic/Longyearbyen'),
(346, 'SK', 'Europe/Bratislava'),
(347, 'SL', 'Africa/Freetown'),
(348, 'SM', 'Europe/San_Marino'),
(349, 'SN', 'Africa/Dakar'),
(350, 'SO', 'Africa/Mogadishu'),
(351, 'SR', 'America/Paramaribo'),
(352, 'SS', 'Africa/Juba'),
(353, 'ST', 'Africa/Sao_Tome'),
(354, 'SV', 'America/El_Salvador'),
(355, 'SX', 'America/Lower_Princes'),
(356, 'SY', 'Asia/Damascus'),
(357, 'SZ', 'Africa/Mbabane'),
(358, 'TC', 'America/Grand_Turk'),
(359, 'TD', 'Africa/Ndjamena'),
(360, 'TF', 'Indian/Kerguelen'),
(361, 'TG', 'Africa/Lome'),
(362, 'TH', 'Asia/Bangkok'),
(363, 'TJ', 'Asia/Dushanbe'),
(364, 'TK', 'Pacific/Fakaofo'),
(365, 'TL', 'Asia/Dili'),
(366, 'TM', 'Asia/Ashgabat'),
(367, 'TN', 'Africa/Tunis'),
(368, 'TO', 'Pacific/Tongatapu'),
(369, 'TR', 'Europe/Istanbul'),
(370, 'TT', 'America/Port_of_Spain'),
(371, 'TV', 'Pacific/Funafuti'),
(372, 'TW', 'Asia/Taipei'),
(373, 'TZ', 'Africa/Dar_es_Salaam'),
(374, 'UA', 'Europe/Kiev'),
(375, 'UA', 'Europe/Uzhgorod'),
(376, 'UA', 'Europe/Zaporozhye'),
(377, 'UG', 'Africa/Kampala'),
(378, 'UM', 'Pacific/Midway'),
(379, 'UM', 'Pacific/Wake'),
(380, 'US', 'America/New_York'),
(381, 'US', 'America/Detroit'),
(382, 'US', 'America/Kentucky/Louisville'),
(383, 'US', 'America/Kentucky/Monticello'),
(384, 'US', 'America/Indiana/Indianapolis'),
(385, 'US', 'America/Indiana/Vincennes'),
(386, 'US', 'America/Indiana/Winamac'),
(387, 'US', 'America/Indiana/Marengo'),
(388, 'US', 'America/Indiana/Petersburg'),
(389, 'US', 'America/Indiana/Vevay'),
(390, 'US', 'America/Chicago'),
(391, 'US', 'America/Indiana/Tell_City'),
(392, 'US', 'America/Indiana/Knox'),
(393, 'US', 'America/Menominee'),
(394, 'US', 'America/North_Dakota/Center'),
(395, 'US', 'America/North_Dakota/New_Salem'),
(396, 'US', 'America/North_Dakota/Beulah'),
(397, 'US', 'America/Denver'),
(398, 'US', 'America/Boise'),
(399, 'US', 'America/Phoenix'),
(400, 'US', 'America/Los_Angeles'),
(401, 'US', 'America/Anchorage'),
(402, 'US', 'America/Juneau'),
(403, 'US', 'America/Sitka'),
(404, 'US', 'America/Metlakatla'),
(405, 'US', 'America/Yakutat'),
(406, 'US', 'America/Nome'),
(407, 'US', 'America/Adak'),
(408, 'US', 'Pacific/Honolulu'),
(409, 'UY', 'America/Montevideo'),
(410, 'UZ', 'Asia/Samarkand'),
(411, 'UZ', 'Asia/Tashkent'),
(412, 'VA', 'Europe/Vatican'),
(413, 'VC', 'America/St_Vincent'),
(414, 'VE', 'America/Caracas'),
(415, 'VG', 'America/Tortola'),
(416, 'VI', 'America/St_Thomas'),
(417, 'VN', 'Asia/Ho_Chi_Minh'),
(418, 'VU', 'Pacific/Efate'),
(419, 'WF', 'Pacific/Wallis'),
(420, 'WS', 'Pacific/Apia'),
(421, 'YE', 'Asia/Aden'),
(422, 'YT', 'Indian/Mayotte'),
(423, 'ZA', 'Africa/Johannesburg'),
(424, 'ZM', 'Africa/Lusaka'),
(425, 'ZW', 'Africa/Harare');

-- --------------------------------------------------------

--
-- Table structure for table `app_version`
--

DROP TABLE IF EXISTS `app_version`;
CREATE TABLE IF NOT EXISTS `app_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version_no` varchar(20) NOT NULL,
  `version_description` text NOT NULL,
  `dt_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `version_no` (`version_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `app_version`
--

INSERT INTO `app_version` (`id`, `version_no`, `version_description`, `dt_updated`, `updated_by`) VALUES
(1, '2020.05.10', '* Initial Release', '2020-04-01 06:02:59', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_reason` text,
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `deleted_reason`, `status_id`) VALUES
(1, 'Laboratory', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(2, 'Pharmacy', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(3, 'Diagnostic', NULL, 1, '2023-02-27 19:36:57', 1, NULL, 0, '', 2),
(4, 'Services', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(5, 'Supply', NULL, 0, NULL, 0, NULL, 0, NULL, 2),
(6, 'X-Ray', NULL, 0, NULL, 0, NULL, 0, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `category_status`
--

DROP TABLE IF EXISTS `category_status`;
CREATE TABLE IF NOT EXISTS `category_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category_status`
--

INSERT INTO `category_status` (`id`, `status`) VALUES
(2, 'ACTIVE'),
(1, 'INACTIVE');

-- --------------------------------------------------------

--
-- Table structure for table `charging_types`
--

DROP TABLE IF EXISTS `charging_types`;
CREATE TABLE IF NOT EXISTS `charging_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `charging_type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `charging_type` (`charging_type`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `charging_types`
--

INSERT INTO `charging_types` (`id`, `charging_type`) VALUES
(2, 'COMPANY'),
(1, 'INDIVIDUAL');

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sender_id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `chat_status`
--

DROP TABLE IF EXISTS `chat_status`;
CREATE TABLE IF NOT EXISTS `chat_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat_status`
--

INSERT INTO `chat_status` (`id`, `status`) VALUES
(2, 'ACTIVE'),
(1, 'DELETED');

-- --------------------------------------------------------

--
-- Table structure for table `civils`
--

DROP TABLE IF EXISTS `civils`;
CREATE TABLE IF NOT EXISTS `civils` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `civil` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `civil` (`civil`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `civils`
--

INSERT INTO `civils` (`id`, `civil`) VALUES
(2, 'MARRIED'),
(3, 'SEPARATED'),
(1, 'SINGLE'),
(4, 'WIDOW');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `address` text,
  `phone` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_reason` text,
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `address`, `phone`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `deleted_reason`, `status_id`) VALUES
(1, 'AGILITY/DSV', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(2, 'Ahi Investment Ltd', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(3, 'AMALPACK LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(4, 'ASSOCIATED MILLS LIMITED', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(5, 'BARLOW INDUSTRIES LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(6, 'BISMARK MARITIME', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(7, 'BLACK CAT LIMITED', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(8, 'BNG TRADING PNG LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(9, 'BOROKO MOTORS', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(10, 'BRIAN BELL GROUP', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(11, 'BRIDGESTONE TYRES (PNG) LIMITED', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(12, 'CAPEX SOLUTION', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(13, 'CAPITAL GENERAL INSURANCE COMPANY', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(14, 'CENCON PACKAGING LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(15, 'CHEMICA LIMITED', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(16, 'CONSORT EXPRESS LINE', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(17, 'CROSBIES LIMITED', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(18, 'DATEC (PNG) LIMITED', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(19, 'EDWARD DE SAGUN', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(20, 'DULUX GROUP', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(21, 'DUNLOP (PNG) LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(22, 'E-WASTE MANAGEMENT SERVICES', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(23, 'EAST WEST TRANSPORT', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(24, 'EDDIE ENGINEERING SERVICE LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(25, 'ENDTIMES CONTRUCTORS', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(26, 'EXECUTIVE SECURITY SYSTEMS (ESS)', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(27, 'EXPRESS FREIGHT MANAGEMENT', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(28, 'FRABELLE (PNG) LTD-Cannery', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(29, 'FTM CONSTRUCTION LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(30, 'GAMOGA AND CO. LAWYERS', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(31, 'GM FLORES HOSPITAL', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(32, 'GUARD DOG SECURITY SERVICES LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(33, 'HARDWARE HAUS', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(34, 'HARMONY', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(35, 'HASTINGS DEERING', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(36, 'HAUSMAN HARDWARE', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(37, 'HBS PNG LIMITED', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(38, 'HIDDEN VALLEY', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(39, 'HIGHWAY TRUCK & CAR REPAIRS LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(40, 'HORNIBROOK NGI LIMITED', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(41, 'IDEAL AUTO PARTS', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(42, 'INTERNATIONAL FOOD CORP. LTD (IFC)', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(43, 'IPI TRANSPORT LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(44, 'JV PNG INVESTMENTS CONST LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(45, 'K.K. KINGSTON LIMITED', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(46, 'K92 MINING LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(47, 'KENMORE LIMITED', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(48, 'KUTUBU TRANSPORT LIMITED', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(49, 'LAE BISCUIT CO. LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(50, 'LAE BROTHERS ENGINEERING SERVICES LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(51, 'LAE BUILDERS & CONTRACTORS LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(52, 'LAE INTERNATIONAL HOTEL', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(53, 'LAE PACKAGING INDUSTRIES LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(54, 'LCR PNG LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(55, 'LUTHERAN SHIPPING', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(56, 'MAINLAND HOLDINGS', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(57, 'MAJESTIC SEAFOODS CORPORATION LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(58, 'MALA AUTO PARTS', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(59, 'MAPAI TRANSPORT LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(60, 'MARKHAM CULVERTS', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(61, 'MASTER SYSTEM TECHNOLOGIES', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(62, 'MAXIMUM SECURITY', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(63, 'MAYUME CONTRACTORS K92 MINE', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(64, 'MOROBE STATIONERY', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(65, 'MOTOR VEHICLE INSURANCE LIMITED', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(66, 'MOTORIST DISCOUNT CENTER LTD (MDC)', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(67, 'MPG WATUT LLG CHAIRMAN MINES', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(68, 'MULTI ELECTRICAL SERVICES', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(69, 'NAIKO RESOURCES LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(70, 'NAMBAWAN SAVINGS & LOAN LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(71, 'NAMBAWAN SEAFOODS PNG LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(72, 'NATIONAL AGRICULTURAL QUARANTINE  (NAQIA)', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(73, 'NATIONAL FINANCE', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(74, 'NATIONAL FISHERIES AUTHORITY', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(75, 'NATIONAL MARITIME SAFETY AUTHORITY', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(76, 'NCI PACKAGING PNG LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(77, 'NCS HOLDINGS LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(78, 'NESTLE PNG LIMITED', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(79, 'NIUGINI ELECTRICAL CO. LTD', NULL, NULL, '2023-12-09 23:14:40', 0, NULL, 0, NULL, 0, NULL, 2),
(80, 'NIUGINI POWER SYSTEM', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(81, 'ORICA SINGAPORE (PTE) LTD', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(82, 'ORIENTAL CONSULTANTS GLOBAL', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(83, 'ORIGIN ENERGY PNG LIMITED', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(84, 'PACIFIC ASSURANCE GROUP', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(85, 'PACIFIC FOAM LTD', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(86, 'PACIFIC INDUSTRIES LTD', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(87, 'PACIFIC MMI INSURANCE LTD', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(88, 'PACIFIC TOWING LTD', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(89, 'PAGINI TRANSPORT LTD P O BOX 1559', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(90, 'PAPINDO TRADING COMPANY LTD', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(91, 'PARADISE FOODS LIMITED', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(92, 'PELGEN\'S LIMITED', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(93, 'PLACEMENTS PNG LTD', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(94, 'PLUMBERS & BUILDERS SUPPLIES', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(95, 'PNG CONCRETE AGGREGATES', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(96, 'PNG MOTORS', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(97, 'PNG PORTS CORPORATION', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(98, 'PNG READY MIX CONCRETE', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(99, 'PNG TAIHEIYO CEMENT', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(100, 'PORGERA JOINT VENTURE', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(101, 'PRIMA SMALL GOODS', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(102, 'PROFESSIONALS LAE LTD', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(103, 'QBE INSURANCE PNG LTD', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(104, 'QUEST EXPLORATION DRILLING (PNG) LTD', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(105, 'R & SONS CONSTRUCTIONS', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(106, 'RAGGIANA INTERNATIONAL ACADEMY', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(107, 'RAMU AGRI INDUSTRIES LTD', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(108, 'RAUMAI 18 LIMITED', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(109, 'SBS ELECTRICAL LTD', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(110, 'SEETO KUI (HOLDINGS) LIMITED', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(111, 'SIMBERI GOLD COMPANY LIMITED', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(112, 'SOUTH PACIFIC INTERNATIONAL CONTAINER LIMITED', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(113, 'SOUTH SEAS LINES LIMITED', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(114, 'SP BREWERY', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(115, 'TE PNG', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(116, 'THE INTERNATIONAL SCHOOL OF LAE', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(117, 'TRAISA TRANSPORT LIMITED', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(118, 'TRANS WONDERLAND', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(119, 'TRUKAI INDUSTRIES', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(120, 'UMW Niugini Ltd', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(121, 'WAFI GOLPU JOINT VENTURE', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(122, 'WARRIOR INDUSTRIES', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2),
(123, 'WORLD VISION', NULL, NULL, '2023-12-09 23:14:41', 0, NULL, 0, NULL, 0, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `client_status`
--

DROP TABLE IF EXISTS `client_status`;
CREATE TABLE IF NOT EXISTS `client_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `client_status`
--

INSERT INTO `client_status` (`id`, `status`) VALUES
(2, 'ACTIVE'),
(1, 'DELETED');

-- --------------------------------------------------------

--
-- Table structure for table `genders`
--

DROP TABLE IF EXISTS `genders`;
CREATE TABLE IF NOT EXISTS `genders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gender` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gender` (`gender`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `genders`
--

INSERT INTO `genders` (`id`, `gender`) VALUES
(2, 'FEMALE'),
(1, 'MALE');

-- --------------------------------------------------------

--
-- Table structure for table `insurances`
--

DROP TABLE IF EXISTS `insurances`;
CREATE TABLE IF NOT EXISTS `insurances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `company_name` varchar(200) DEFAULT NULL,
  `value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `value_type_id` int(11) NOT NULL,
  `commission_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `commission_type_id` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_reason` text,
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `insurances`
--

INSERT INTO `insurances` (`id`, `name`, `company_name`, `value`, `value_type_id`, `commission_value`, `commission_type_id`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `deleted_reason`, `status_id`) VALUES
(1, 'Capital Life Insurance 10%', 'Capital Life Insurance', '90.00', 1, '0.00', 1, NULL, 1, '2023-11-07 06:27:10', 1, NULL, 0, '', 2),
(2, 'Capital Life Insurance 20%', 'Capital Life Insurance', '80.00', 1, '0.00', 1, NULL, 1, '2023-11-07 06:26:41', 1, NULL, 0, NULL, 2),
(3, 'Capital Life Insurance 15%', 'Capital Life Insurance', '85.00', 1, '0.00', 1, NULL, 1, '2023-11-07 06:27:03', 1, NULL, 0, NULL, 2),
(4, 'Nasfund Insurance 2%', 'Nasfund', '10.00', 1, '0.00', 1, NULL, 1, '2023-11-07 06:26:29', 1, NULL, 0, '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `insurance_status`
--

DROP TABLE IF EXISTS `insurance_status`;
CREATE TABLE IF NOT EXISTS `insurance_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `insurance_status`
--

INSERT INTO `insurance_status` (`id`, `status`) VALUES
(2, 'ACTIVE'),
(1, 'DELETED');

-- --------------------------------------------------------

--
-- Table structure for table `insurance_value_types`
--

DROP TABLE IF EXISTS `insurance_value_types`;
CREATE TABLE IF NOT EXISTS `insurance_value_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value_type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `value_type` (`value_type`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `insurance_value_types`
--

INSERT INTO `insurance_value_types` (`id`, `value_type`) VALUES
(2, 'AMOUNT'),
(1, 'PERCENTAGE');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint(20) NOT NULL,
  `qty` decimal(20,2) NOT NULL DEFAULT '1.00',
  `product_id` int(11) NOT NULL,
  `price` decimal(20,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `commission_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `insurance_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total` decimal(20,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_reason` text,
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `item_status`
--

DROP TABLE IF EXISTS `item_status`;
CREATE TABLE IF NOT EXISTS `item_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item_status`
--

INSERT INTO `item_status` (`id`, `status`) VALUES
(1, 'CANCELLED'),
(4, 'COMPLETED'),
(3, 'ONGOING'),
(2, 'PENDING');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_reason` text,
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `location` (`location`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `deleted_reason`, `status_id`) VALUES
(1, 'Reception', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(2, 'Triage', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(3, 'Cashier', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(4, 'Pre-Emp', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(5, 'Doc Office', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(6, 'Laboratory', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(7, 'X-Ray', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(8, 'Audiometry', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(9, 'Spiro', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(10, 'ECG', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(11, 'Pharmacy', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(12, 'ER', NULL, 1, NULL, 0, NULL, 0, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `location_status`
--

DROP TABLE IF EXISTS `location_status`;
CREATE TABLE IF NOT EXISTS `location_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `location_status`
--

INSERT INTO `location_status` (`id`, `status`) VALUES
(2, 'ACTIVE'),
(1, 'DELETED');

-- --------------------------------------------------------

--
-- Table structure for table `log_login`
--

DROP TABLE IF EXISTS `log_login`;
CREATE TABLE IF NOT EXISTS `log_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dt` varchar(30) NOT NULL,
  `user_id` int(11) NOT NULL,
  `local_ip` varchar(64) DEFAULT NULL,
  `public_ip` varchar(64) DEFAULT NULL,
  `computer_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

DROP TABLE IF EXISTS `packages`;
CREATE TABLE IF NOT EXISTS `packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `package` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_reason` text,
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `package` (`package`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `package`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `deleted_reason`, `status_id`) VALUES
(1, 'Frabelle Annual Med', NULL, 1, '2023-01-06 07:48:29', 1, NULL, 0, NULL, 2),
(2, 'Tahiyo Annual Med', NULL, 1, NULL, 0, NULL, 0, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `package_status`
--

DROP TABLE IF EXISTS `package_status`;
CREATE TABLE IF NOT EXISTS `package_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `package_status`
--

INSERT INTO `package_status` (`id`, `status`) VALUES
(2, 'ACTIVE'),
(1, 'DELETED');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
CREATE TABLE IF NOT EXISTS `patients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lastname` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) DEFAULT NULL,
  `gender_id` int(11) NOT NULL DEFAULT '1',
  `civil_id` int(11) NOT NULL DEFAULT '1',
  `contact_no` varchar(50) DEFAULT NULL,
  `address` text,
  `guardian_name` varchar(200) DEFAULT NULL,
  `guardian_contact` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_reason` text,
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `lastname` (`lastname`,`firstname`,`middlename`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `lastname`, `firstname`, `middlename`, `gender_id`, `civil_id`, `contact_no`, `address`, `guardian_name`, `guardian_contact`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `deleted_reason`, `status_id`) VALUES
(1, 'Hindang', 'Jaypee', 'R', 1, 2, '111', 'sad', NULL, NULL, NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(2, 'Doe', 'John', 'Kie', 1, 2, '', '', NULL, NULL, NULL, 1, '2023-01-13 13:36:57', 1, NULL, 0, NULL, 2),
(3, 'soriano', 'anna', '', 2, 1, '', '', NULL, NULL, NULL, 1, NULL, 0, NULL, 0, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `patient_status`
--

DROP TABLE IF EXISTS `patient_status`;
CREATE TABLE IF NOT EXISTS `patient_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient_status`
--

INSERT INTO `patient_status` (`id`, `status`) VALUES
(2, 'ACTIVE'),
(1, 'DELETED');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classification` varchar(50) NOT NULL DEFAULT 'Charges',
  `date` date NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `payment_type_id` int(11) NOT NULL,
  `amount_due` decimal(10,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(10,2) NOT NULL,
  `tender_amount` decimal(10,2) NOT NULL,
  `change_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `reference` text,
  `remarks` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_reason` text,
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `classification`, `date`, `transaction_id`, `payment_type_id`, `amount_due`, `amount`, `tender_amount`, `change_amount`, `reference`, `remarks`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `deleted_reason`, `status_id`) VALUES
(1, 'Charges', '2023-10-29', 3, 1, '5.41', '3.41', '40.00', '36.59', '', '', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(2, 'Charges', '2023-10-29', 3, 1, '106.76', '76.76', '100.00', '23.24', '', '', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(3, 'Charges', '2023-10-29', 3, 1, '133.95', '133.95', '200.00', '66.05', '', '', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(4, 'Charges', '2023-11-07', 1, 1, '9.20', '5.20', '10.00', '4.80', '', '', NULL, 1, NULL, 0, '2023-11-10 23:40:53', 1, NULL, 1),
(5, 'Charges', '2023-11-09', 5, 1, '96.35', '96.35', '100.00', '3.65', '', '', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(6, 'Charges', '2023-11-11', 6, 1, '160.00', '160.00', '200.00', '40.00', '', '', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(7, 'Charges', '2023-11-23', 2, 1, '4.60', '4.60', '5.00', '0.40', '', '', NULL, 1, NULL, 0, '2023-11-23 08:06:53', 1, NULL, 1),
(8, 'Charges', '2023-11-23', 4, 3, '188.00', '100.00', '100.00', '0.00', 'PA-123', '', NULL, 1, NULL, 0, '2023-11-23 11:57:12', 1, NULL, 1),
(9, 'Charges', '2023-11-23', 4, 3, '88.00', '80.00', '100.00', '20.00', 'PA-244', '', NULL, 1, NULL, 0, NULL, 0, NULL, 4),
(10, 'Charges', '2023-11-23', 2, 3, '4.60', '3.20', '5.00', '1.80', 'pgdd', '', NULL, 1, NULL, 0, '2023-11-23 11:57:34', 1, NULL, 1),
(11, 'Charges', '2023-11-23', 4, 3, '8.00', '5.00', '10.00', '5.00', 'dsads', '', NULL, 1, NULL, 0, NULL, 0, NULL, 4),
(12, 'Charges', '2023-11-23', 4, 3, '3.00', '2.00', '33.00', '31.00', 'ds', '', NULL, 1, NULL, 0, NULL, 0, NULL, 4),
(13, 'Charges', '2023-11-23', 2, 3, '1.40', '1.00', '2.00', '1.00', 'dd', '', NULL, 1, NULL, 0, NULL, 0, NULL, 4),
(14, 'Charges', '2023-11-23', 2, 3, '3.60', '3.60', '4.00', '0.40', 'ddd', '', NULL, 1, NULL, 0, NULL, 0, NULL, 4),
(15, 'Charges', '2023-11-23', 4, 3, '101.00', '101.00', '110.00', '9.00', '', '', NULL, 1, NULL, 0, NULL, 0, NULL, 4),
(16, 'Charges', '2023-11-23', 7, 3, '1115.23', '1115.23', '5000.00', '3884.77', 'ddd', '', NULL, 1, NULL, 0, NULL, 0, NULL, 4),
(17, 'Charges', '2023-11-24', 8, 1, '94.73', '34.73', '100.00', '65.27', '', '', NULL, 1, NULL, 0, NULL, 0, NULL, 4),
(18, 'Charges', '2023-11-24', 8, 3, '60.00', '50.00', '111.00', '61.00', '55343', '', NULL, 1, NULL, 0, NULL, 0, NULL, 4),
(19, 'Charges', '2023-11-25', 3, 1, '75.25', '75.25', '100.00', '24.75', '', '', NULL, 1, NULL, 0, NULL, 0, NULL, 4),
(20, 'Charges', '2023-12-09', 10, 1, '34.50', '24.50', '100.00', '75.50', 'test', '', NULL, 1, NULL, 0, NULL, 0, NULL, 4),
(21, 'Charges', '2023-12-10', 10, 1, '10.00', '7.00', '10.00', '3.00', '', '', NULL, 1, NULL, 0, NULL, 0, NULL, 4),
(22, 'Charges', '2023-12-10', 12, 1, '48.00', '38.00', '60.00', '22.00', '', '', NULL, 5, NULL, 0, NULL, 0, NULL, 4),
(23, 'Charges', '2023-12-27', 12, 1, '34.90', '4.90', '5.00', '0.10', '', '', NULL, 1, NULL, 0, NULL, 0, NULL, 4);

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

DROP TABLE IF EXISTS `payment_methods`;
CREATE TABLE IF NOT EXISTS `payment_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_method` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_reason` text,
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_method` (`payment_method`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `payment_method`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `deleted_reason`, `status_id`) VALUES
(1, 'CASH - Clinic Hour', NULL, 1, NULL, 0, NULL, 0, NULL, 3),
(2, 'PO - Clinic Hour', NULL, 1, NULL, 0, NULL, 0, NULL, 3),
(3, 'CASH - After Clinic Hour', NULL, 1, NULL, 0, NULL, 0, NULL, 3),
(4, 'PO - After Clinic Hour', NULL, 1, NULL, 0, NULL, 0, NULL, 3);

-- --------------------------------------------------------

--
-- Table structure for table `payment_method_status`
--

DROP TABLE IF EXISTS `payment_method_status`;
CREATE TABLE IF NOT EXISTS `payment_method_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment_method_status`
--

INSERT INTO `payment_method_status` (`id`, `status`) VALUES
(2, 'ACTIVE'),
(1, 'DELETED'),
(3, 'LOCKED');

-- --------------------------------------------------------

--
-- Table structure for table `payment_status`
--

DROP TABLE IF EXISTS `payment_status`;
CREATE TABLE IF NOT EXISTS `payment_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment_status`
--

INSERT INTO `payment_status` (`id`, `status`) VALUES
(1, 'CANCELLED'),
(4, 'COMPLETED'),
(3, 'PARTIAL'),
(2, 'UNPAID');

-- --------------------------------------------------------

--
-- Table structure for table `payment_types`
--

DROP TABLE IF EXISTS `payment_types`;
CREATE TABLE IF NOT EXISTS `payment_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_reason` text,
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_type` (`payment_type`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment_types`
--

INSERT INTO `payment_types` (`id`, `payment_type`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `deleted_reason`, `status_id`) VALUES
(1, 'CASH', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(2, 'ePOS', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(3, 'CHEQUE', NULL, 1, '2023-09-18 14:51:22', 1, NULL, 0, NULL, 2),
(4, 'Online', NULL, 1, NULL, 0, NULL, 0, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `payment_type_status`
--

DROP TABLE IF EXISTS `payment_type_status`;
CREATE TABLE IF NOT EXISTS `payment_type_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment_type_status`
--

INSERT INTO `payment_type_status` (`id`, `status`) VALUES
(2, 'ACTIVE'),
(1, 'DELETED');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

DROP TABLE IF EXISTS `prescriptions`;
CREATE TABLE IF NOT EXISTS `prescriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` bigint(20) NOT NULL,
  `qty` decimal(20,2) NOT NULL DEFAULT '1.00',
  `product_id` int(11) NOT NULL,
  `instruction` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_reason` text,
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `prescription_status`
--

DROP TABLE IF EXISTS `prescription_status`;
CREATE TABLE IF NOT EXISTS `prescription_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prescription_status`
--

INSERT INTO `prescription_status` (`id`, `status`) VALUES
(2, 'ACTIVE'),
(1, 'DELETED');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `uom_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `amount_po` decimal(20,2) NOT NULL DEFAULT '0.00',
  `after_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `after_amount_po` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_reason` text,
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=863 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `code`, `name`, `uom_id`, `category_id`, `amount`, `amount_po`, `after_amount`, `after_amount_po`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `deleted_reason`, `status_id`) VALUES
(1, 'X001', 'XRAY CHEST - PA View', 2, 6, '150.00', '190.00', '175.00', '220.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(2, 'X002', 'XRAY CHEST - Lateral Decubitus', 2, 6, '150.00', '190.00', '175.00', '220.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(3, 'X003', ' XRAY NASAL SINUSES - Water\'s View', 2, 6, '160.00', '200.00', '185.00', '230.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(4, 'X004', ' XRAY NASAL SINUSES - Caldwell\'s View', 2, 6, '160.00', '200.00', '185.00', '230.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(5, 'X005', ' XRAY NASAL SINUSES -Towne\'s View', 2, 6, '160.00', '200.00', '185.00', '230.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(6, 'X006', ' XRAY NASAL SINUSES - Lateral View', 2, 6, '160.00', '200.00', '185.00', '230.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(7, 'X007', 'XRAY SKULL AP/LATERAL - 2 View', 2, 6, '230.00', '290.00', '265.00', '335.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(8, 'X008', 'XRAY MASTOID (AP / 2 LATERAL)', 2, 6, '370.00', '470.00', '425.00', '545.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(9, 'X009', 'XRAY TMJ (Temporomandibular Joint)', 2, 6, '470.00', '630.00', '540.00', '725.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(10, 'X010', 'XRAY PLAIN ABDOMEN - Supine/Upright', 2, 6, '250.00', '300.00', '290.00', '345.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(11, 'X011', 'XRAY PLAIN ABDOMEN - Lateral Decubitus', 2, 6, '250.00', '300.00', '185.00', '230.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(12, 'X012', 'XRAY SPINE: CERVICAL', 2, 6, '280.00', '330.00', '325.00', '380.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(13, 'X013', 'XRAY SPINE: THORACIC', 2, 6, '280.00', '330.00', '325.00', '380.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(14, 'X014', 'XRAY SPINE: LUMBAR', 2, 6, '280.00', '330.00', '325.00', '380.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(15, 'X015', 'XRAY THORACO-LUMBAR APL', 2, 6, '360.00', '420.00', '415.00', '485.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(16, 'X016', 'XRAY LUMBOSACRAL APL', 2, 6, '360.00', '420.00', '415.00', '485.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(17, 'X017', 'XRAY KUB (Kidney, Ureter, Bladder)', 2, 6, '180.00', '220.00', '210.00', '225.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(18, 'X018', 'XRAY PELVIS', 2, 6, '180.00', '220.00', '210.00', '260.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(19, 'X019', 'XRAY FEMUR AP/LATERAL', 2, 6, '250.00', '300.00', '290.00', '345.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(20, 'X020', 'XRAY KNEE JOINT AP/LATERAL', 2, 6, '250.00', '300.00', '290.00', '345.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(21, 'X021', 'XRAY LEG (One Leg Only) AP/LAT', 2, 6, '250.00', '300.00', '290.00', '345.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(22, 'X022', 'XRAY ANKLE AP/LATERAL', 2, 6, '200.00', '250.00', '230.00', '290.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(23, 'X023', 'XRAY FOOT AP/LATERAL', 2, 6, '200.00', '250.00', '230.00', '290.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(24, 'X024', 'XRAY SHOULDER AP/LATERAL', 2, 6, '250.00', '310.00', '290.00', '360.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(25, 'X025', 'XRAY CLAVICLE', 2, 6, '160.00', '200.00', '185.00', '230.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(26, 'X026', 'XRAY STERNUM', 2, 6, '160.00', '200.00', '185.00', '230.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(27, 'X027', 'XRAY RIBS', 2, 6, '160.00', '200.00', '255.00', '330.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(28, 'X028', 'XRAY ELBOW AP/LATERAL', 2, 6, '220.00', '280.00', '255.00', '330.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(29, 'X029', 'XRAY FOREARM', 2, 6, '220.00', '280.00', '255.00', '330.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(30, 'X030', 'XRAY WRIST JOINT AP/LATERAL', 2, 6, '220.00', '280.00', '255.00', '330.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(31, 'X031', 'XRAY HAND AP/LATERAL', 2, 6, '220.00', '280.00', '255.00', '330.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(32, 'P001', 'Acetylcysteine 600mg sachet', 1, 2, '5.00', '6.00', '5.00', '6.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(33, 'P002', 'Aciclovir 400mg tablet', 1, 2, '6.00', '6.25', '6.00', '6.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(34, 'P003', 'Adrenalin 1mg injection', 1, 2, '20.00', '25.00', '20.00', '25.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(35, 'P004', 'Albendazole 200mg tablet', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(36, 'P005', 'Allopurinol 100mg tablet', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(37, 'P006', 'Allopurinol 300mg tablet ', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(38, 'P007', 'Alprazolam 0.5mg tablet', 1, 2, '5.50', '6.25', '5.50', '6.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(39, 'P008', 'Aluminum Hydroxide/Antacid 200mg tablet (GELUSIL)', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(40, 'P009', 'Aluminum Hydroxide/Antacid 400mg tablet (GENERIC)', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(41, 'P010', 'Aluminum Hydroxide 200mg tablet (ANTACID)', 1, 2, '1.75', '1.75', '1.75', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(42, 'P011', 'Aluminum Hydroxide 400mg tablet (ANTACID)', 1, 2, '2.00', '2.00', '2.00', '2.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(43, 'P012', 'Sodium Alginate+NaHCO3+Ca CO3 300ml liquid susp (GAVISCON)', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(44, 'P013', 'Sodium Alginate+NaHCO3+Ca CO3 150ml liquid susp (GAVISCON)', 1, 2, '20.00', '25.00', '20.00', '25.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(45, 'P014', 'Ambroxol Drops', 1, 2, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(46, 'P015', 'Amikacin Sulfate 125mg', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(47, 'P016', 'Amikacin Sulfate 250mg/2ml', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(48, 'P017', 'Aminophyllin 250mg/10ml injection', 1, 2, '190.00', '210.00', '190.00', '210.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(49, 'P018', 'Amlodipine 5mg tablet (generic)', 1, 2, '4.50', '5.00', '4.50', '5.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(50, 'P019', 'Amlodipine 10mg tablet (generic)', 1, 2, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(51, 'P020', 'Amoxicillin 100mg/20ml drops', 1, 2, '12.00', '13.50', '12.00', '13.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(52, 'P021', 'Amoxicillin 125mg/5ml/ 105ml suspension', 1, 2, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(53, 'P022', 'Amoxicillin 250mg/5ml/ 105ml suspension ', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(54, 'P023', 'Amoxicillin 500mg capsule', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(55, 'P024', 'Amoxicillin 500mg injection', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(56, 'P025', 'Ampicillin 500Mg vial (AMBILIN)', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(57, 'P026', 'Ampicillin 1000mg vial', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(58, 'P027', 'Anusol suppositories', 1, 2, '6.50', '7.25', '6.50', '7.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(59, 'P028', 'Artemeter 60ml suspension (MALA DUO)', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(60, 'P029', 'Artemeter 80mg tablet (MALA DUO)', 1, 2, '9.50', '10.50', '9.50', '10.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(61, 'P030', 'Artemeter+Lumefantrine 20mg/120mg ', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(62, 'P031', 'Artemeter+Lumefantrine 80mg/480mg (ALBEETER-L)', 1, 2, '9.50', '10.50', '9.50', '10.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(63, 'P032', 'Artemether 1ml injection/ampule', 1, 2, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(64, 'P033', 'Ascorbic Acid 100mg syrup (APCEE) not covered by insurance', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(65, 'P034', 'Ascorbic Acid 15ml drops', 1, 2, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(66, 'P035', 'Ascorbic Acid 30ml drops', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(67, 'P036', 'Ascorbic Acid 500mg tablet', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(68, 'P037', 'Ascorbic Acid+Zinc 120ml syrup (CEELIN Plus)', 1, 2, '40.00', '44.00', '40.00', '44.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(69, 'P038', 'Aspirin 80mg tablet', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(70, 'P039', 'Aspirin 100mg tablet ', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(71, 'P040', 'Atenolol 50mg tablet', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(72, 'P041', 'Atorvastatin 10mg tablet', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(73, 'P042', 'Atorvastatin 20mg tablet ', 1, 2, '3.50', '4.00', '3.50', '4.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(74, 'P043', 'Atorvastatin 40mg tablet ', 1, 2, '4.50', '5.00', '4.50', '5.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(75, 'P044', 'Atovaquone+Propaniul 250/100mg tablet (MALARONE) 12\'s', 1, 2, '65.00', '71.50', '65.00', '71.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(76, 'P045', 'Atropine 600mCg injection', 1, 2, '185.00', '205.00', '185.00', '205.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(77, 'P046', 'Azithromycin 100mg/5ml/15ml suspension', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(78, 'P047', 'Azithromycin 200mg/5ml/15ml suspension', 1, 2, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(79, 'P048', 'Azithromycin 500mg tablet', 1, 2, '9.50', '10.50', '9.50', '10.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(80, 'P049', 'Benzanthine Penicillin 2.4mega injection', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(81, 'P050', 'Benzyl Benzoate 250mg/ml/200ml (ASCABIOL Lotion)', 1, 2, '156.00', '172.00', '156.00', '172.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(82, 'P051', 'Benzyl Penicillin 600mg vial (CRYSTAPEN)', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(83, 'P052', 'Betahistine 8mg tablet', 1, 2, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(84, 'P053', 'Betahistine 16mg tablet  (SERC)', 1, 2, '6.00', '6.75', '6.00', '6.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(85, 'P054', 'Bisacodyl 5mg supp. (DULCOLAX)', 1, 2, '10.00', '11.00', '10.00', '11.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(86, 'P055', 'Bisacodyl 10mg supp. (DULCOLAX)', 1, 2, '12.00', '13.25', '12.00', '13.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(87, 'P056', 'Bisacodyl 5mg tablet (DULCOLAX)', 1, 2, '3.00', '3.50', '3.00', '3.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(88, 'P057', 'Blumea balsamifera 500mg tablet SAMBONG LEAF/RENALEAF anti-urolithiasis/diuretic', 1, 2, '3.00', '3.50', '3.00', '3.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(89, 'P058', 'Bromhexine 8mg tablet', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(90, 'P059', 'Budesonide nebule 0.5mg/2ml  (nebule only)', 1, 2, '10.00', '11.00', '10.00', '11.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(91, 'P060', 'Butamirate 50mg tablet (SINECOD FORTE)', 1, 2, '9.00', '10.00', '9.00', '10.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(92, 'P061', 'Calamine Lotion 30ml', 1, 2, '12.00', '13.25', '12.00', '13.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(93, 'P062', 'Calamine Lotion 200ml', 1, 2, '65.00', '72.00', '65.00', '72.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(94, 'P063', 'Calamine Lotion 500ml', 1, 2, '80.00', '88.00', '80.00', '88.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(95, 'P064', 'Calamine + Zinc Oxide ointment   (CALAZIN) 15grm tubes', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(96, 'P065', 'Calcibloc 5mg capsule', 1, 2, '3.50', '4.00', '3.50', '4.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(97, 'P066', 'Calcium Ascorbate 500mg cap (NUTRI-C): prevention & treatment of VitC def ', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(98, 'P067', 'Calcium Carbonate 600mg tablet (CALTRATE plain)', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(99, 'P068', 'Calcium 600mg+1000IU Vit.D tablet (CALTRATE BONE & MUSCLE)', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(100, 'P069', 'Calcium+Vit. D 500mg tablet (CALCIGEN)', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(101, 'P070', 'Canesten Vaginal Pessary 100mg supp', 1, 2, '6.00', '6.75', '6.00', '6.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(102, 'P071', 'Captopril 25mg tablet', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(103, 'P072', 'Carbimazole 5mg tablet', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(104, 'P073', 'Carbocisteine 500mg capsule', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(105, 'P074', 'Carbocisteine 50mg/20ml drops (HEALTHCARE INFANT COUGH MUCOLYTIC)', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(106, 'P075', 'Carbocisteine 100mg/5ml 60ml syrup (HEALTHCARE COUGH MUCOLYTIC)', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(107, 'P076', 'Carbocisteine 250mg/5ml 60ml syrup', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(108, 'P077', 'Carboxymethylcellulose Na eye drops 10ml (MEDFRESH-C)', 1, 2, '45.00', '49.50', '45.00', '49.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(109, 'P078', 'Cefaclor 250mg capsule ', 1, 2, '3.50', '4.00', '3.50', '4.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(110, 'P079', 'Cefaclor 500mg capsule ', 1, 2, '7.00', '7.75', '7.00', '7.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(111, 'P080', 'Cefaclor 125mg/5ml/100ml suspension', 1, 2, '60.00', '66.00', '60.00', '66.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(112, 'P081', 'Cefaclor 125mg/5ml/30ml suspension ', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(113, 'P082', 'Cefaclor 250mg/5ml/75ml ', 1, 2, '55.00', '60.50', '55.00', '60.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(114, 'P083', 'Cefalexin 100mg/10ml drops ', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(115, 'P084', 'Cefalexin 125mg/5ml 100ml (SANDOZ)', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(116, 'P085', 'Cefalexin 125mg/5ml 120ml (HEALTHCARE)', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(117, 'P086', 'Cefalexin 125mg/30ml suspension', 1, 2, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(118, 'P087', 'Cefalexin 250mg/60ml suspension ', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(119, 'P088', 'Cefalexin 500mg capsule', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(120, 'P089', 'Cefixime 20mg/ml 10ml drops', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(121, 'P090', 'Cefixime 100mg/60ml suspension ', 1, 2, '45.00', '50.00', '45.00', '50.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(122, 'P091', 'Cefixime 200mg capsule ', 1, 2, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(123, 'P092', 'Ceftazidime 1g injection', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(124, 'P093', 'Ceftriaxone 1g vial', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(125, 'P094', 'Cefuroxime 125mg/50ml susp', 1, 2, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(126, 'P095', 'Cefuroxime 250mg/50ml susp', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(127, 'P096', 'Cefuroxime 500mg tablet', 1, 2, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(128, 'P097', 'Cefuroxime 750mg vial/injection', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(129, 'P098', 'Celecoxib 100mg capsule', 1, 2, '3.50', '4.00', '3.50', '4.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(130, 'P099', 'Celecoxib 200mg capsule', 1, 2, '6.00', '6.75', '6.00', '6.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(131, 'P100', 'Celecoxib 400mg capsule', 1, 2, '8.50', '9.50', '8.50', '9.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(132, 'P101', 'CERUMOL wax softener ear drops 10ml', 1, 2, '55.00', '60.50', '55.00', '60.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(133, 'P102', 'WAXONIL ear drops 10ml', 1, 2, '25.00', '28.50', '25.00', '28.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(134, 'P103', 'Cetirizine drops', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(135, 'P104', 'Cetirizine 10mg tablet (SAPHZIN)', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(136, 'P105', 'Cetirizine 60ml syrup', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(137, 'P106', 'Chloramphenicol 5ml Eye drops', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(138, 'P107', 'Chloramphenicol+Dexa+NaPhosphate 5ml Eye drops (CHLOROCOL PLUS)', 1, 2, '40.00', '44.00', '40.00', '44.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(139, 'P108', 'Polymyxin+Neomycin+Dexa eye drops (SYNTEMAX)', 1, 2, '40.00', '45.00', '40.00', '45.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(140, 'P109', 'Chloramphenicol 10ml Ear drops (REMICOL)1% for painful infected ears', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(141, 'P110', 'Polymyxin+Neomycin+Dexa ear drops (RAPIDAX V)', 1, 2, '35.00', '40.00', '35.00', '40.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(142, 'P111', 'Chloramphenicol 125mg/60ml suspension', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(143, 'P112', 'Chloramphenicol Cap 250mg', 1, 2, '1.00', '1.00', '1.00', '1.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(144, 'P113', 'Chloramphenicol Cap 500mg', 1, 2, '1.50', '1.50', '1.50', '1.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(145, 'P114', 'Chloramphenicol 125mg suspension 120mls', 1, 2, '25.00', '28.50', '25.00', '28.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(146, 'P115', 'Chloramphenicol 500mg capsule', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(147, 'P116', 'Chloramphenicol+Dexamethasone 10ml Eye/Ear drops', 1, 2, '40.00', '44.00', '40.00', '44.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(148, 'P117', 'Chlorpheniramine+Phenylephrine oral drops 50ml (SELFCARE Cough Decongestant)', 1, 2, '25.00', '28.50', '25.00', '28.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(149, 'P118', 'Chlorpromazine HCI 100mg tablet (LARGACTIL)', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(150, 'P119', 'Cilostazol 50mg tablet (PLETAAL)', 1, 2, '4.00', '4.50', '4.00', '4.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(151, 'P120', 'Cilostazol 100mg tablet (generic)', 1, 2, '6.00', '6.75', '6.00', '6.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(152, 'P121', 'Ciprofloxacin 500mg tablet', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(153, 'P122', 'Cinnarizine Tab 25mg', 1, 2, '1.50', '1.50', '1.50', '1.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(154, 'P123', 'Cipcal 100mg Tablet', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(155, 'P124', 'Ciprofloxacin IV 2mg', 1, 2, '65.00', '75.00', '65.00', '75.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(156, 'P125', 'Ciprofloxacin+Hydroxypropyl MethylCellulose Eye/Ear drops 10ml (CIFBAX)', 1, 2, '40.00', '44.00', '40.00', '44.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(157, 'P126', 'Citicholine 250mg/ml injection', 1, 2, '90.00', '99.00', '90.00', '99.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(158, 'P127', 'Claratyne 60ml syrup', 1, 2, '80.00', '88.00', '80.00', '88.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(159, 'P128', 'Clarithromycin 125mg/70ml suspension', 1, 2, '40.00', '44.00', '40.00', '44.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(160, 'P129', 'Clarithromycin 125mg/5ml/50ml suspension ', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(161, 'P130', 'Clarithromycin 250mg/5ml/70ml suspension', 1, 2, '50.00', '55.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(162, 'P131', 'Clarithromycin 250mg tablet', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(163, 'P132', 'Clarithromycin 500mg tablet ', 1, 2, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(164, 'P133', 'Clindamycin 150mg capsule', 1, 2, '6.00', '6.75', '6.00', '6.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(165, 'P134', 'Clindamycin 300mg capsule ', 1, 2, '8.50', '9.50', '8.50', '9.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(166, 'P135', 'Clonazepam 2mg tablet', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(167, 'P136', 'Clonidine HCl 100mcg tablet (CATAPRESS)', 1, 2, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(168, 'P137', 'Clopidogrel 75mg tablet', 1, 2, '3.50', '4.00', '3.50', '4.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(169, 'P138', 'Clonidine Tab 100mg ', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(170, 'P139', 'Clotrimoxazole Vaginal Pessaries Box of 6', 1, 2, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(171, 'P140', 'Cloxacillin 500mg capsule', 1, 2, '4.00', '4.50', '4.00', '4.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(172, 'P141', 'Cloxacillin 125mg/60ml suspension ', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(173, 'P142', 'Clotinec Dusting Powder 75gm', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(174, 'P143', 'Clotrimazole Cream (Clozol)15g', 1, 2, '30.00', '30.00', '30.00', '30.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(175, 'P144', 'Cloxacillin 250mg/60ml suspension ', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(176, 'P145', 'Co-Amoxiclav 125mg/31.25ml suspension/75ml', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(177, 'P146', 'Co-AmoxiClav 250mg/60ml suspension', 1, 2, '50.00', '55.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(178, 'P147', 'Co-AmoxiClav 457mg/5ml/30ml suspension', 1, 2, '40.00', '44.00', '40.00', '44.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(179, 'P148', 'Co-AmoxiClav 625mg tablet ', 1, 2, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(180, 'P149', 'Co-Codamol 500mg tablet', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(181, 'P150', 'CoenzymeQ 150mg soft caps(CoQ 10) CVS healthy heart function; healthy bld lipids', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(182, 'P151', 'Colchicine 500mCg (COLGOUT)', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(183, 'P152', 'Co-AmoxiClav Tab Duo 1g', 1, 2, '3.50', '3.50', '3.50', '3.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(184, 'P153', 'Cold & Flu tablet ', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(185, 'P154', 'Conjugated Estrogens 0.625mg tablet (PREMARIN)', 1, 2, '6.00', '6.75', '6.00', '6.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(186, 'P155', 'Cotrimoxazole 100ml suspension', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(187, 'P156', 'Cotrimoxazole 480mg tablet', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(188, 'P157', 'Cough & Cold capsule', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(189, 'P158', 'Cough mucolytic capsule', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(190, 'P159', 'Cutarub C2P 500ml', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(191, 'P160', 'Cytotec 200mCg tablet', 1, 2, '5.50', '6.25', '5.50', '6.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(192, 'P161', 'Depo Medrol IM', 1, 2, '45.00', '49.50', '45.00', '49.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(193, 'P162', 'Depo-PROVERA 150mg/ml  injection', 1, 2, '65.00', '71.50', '65.00', '71.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(194, 'P163', 'Dexamethasone 4mg ampule/inj.', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(195, 'P164', 'Dexamethasone 8mg ampule/inj.', 1, 2, '57.00', '62.75', '57.00', '62.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(196, 'P165', 'Dexamethasone 0.5mg/4mg tablet', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(197, 'P166', 'Dexamethasone + Framycetin (FRAMOPTIC) eye & ear drops 10mls', 1, 2, '40.00', '44.00', '40.00', '44.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(198, 'P167', 'Dextromethorphan HBr (Vicks Formula 44 15mg/5ml/54ml)', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(199, 'P168', 'Diazepam 10 mg ampule', 1, 2, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(200, 'P169', 'DexamethEyeDropMAXIDEX10ml', 1, 2, '25.00', '25.00', '25.00', '25.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(201, 'P170', 'Diamicron Tab 80mg', 1, 2, '2.50', '2.50', '2.50', '2.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(202, 'P171', 'Diazepam 5mg tablet', 1, 2, '3.00', '3.50', '3.00', '3.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(203, 'P172', 'Diclofenac 25mg tablet', 1, 2, '1.00', '2.00', '1.00', '2.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(204, 'P173', 'Diclofenac 50mg tablet', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(205, 'P174', 'Diclofenac 100mg tablet', 1, 2, '1.75', '2.00', '1.75', '2.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(206, 'P175', 'Diclofenac 75mg ampule', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(207, 'P176', 'Dicycloverine 10mg tablet', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(208, 'P177', 'Dicycloverine 5mg/15ml drops', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(209, 'P178', 'Dicycloverine 60ml suspension', 1, 2, '38.00', '42.00', '38.00', '42.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(210, 'P179', 'Diltiazem 30mg tablet', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(211, 'P180', 'Diltiazem 90mg tablet', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(212, 'P181', 'Diosmin+Hesperidin 500mg tablet (DAFLON)', 1, 2, '22.00', '24.50', '22.00', '24.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(213, 'P182', 'Diphenhydramine 60ml syrup (HISTAZYN)', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(214, 'P183', 'Diphenhydramine 50mg capsule', 1, 2, '7.50', '8.50', '7.50', '8.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(215, 'P184', 'Diphenhydramine 50mg/ml ampule', 1, 2, '45.00', '50.00', '45.00', '50.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(216, 'P185', 'Diphenhydramine hydrochloride 12.5mg/60ml', 1, 2, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(217, 'P186', 'Phenylpropanolamin 10ml drops (DISUDRIN)', 1, 2, '25.00', '25.00', '25.00', '25.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(218, 'P187', 'Domperidone 10mg tablet (MOTILIUM)', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(219, 'P188', 'Phenylpropanolamine 60ml syrup (COLDEZE)', 1, 2, '35.00', '35.00', '35.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(220, 'P189', 'Doxycycline 100mg tablet', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(221, 'P190', 'Dydrogesterone 10mg tablet (DUPHASTON)', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(222, 'P191', 'Enalapril 5mg tablet', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(223, 'P192', 'Enalapril 10mg tablet', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(224, 'P193', 'Diphynhydramine Syrup 60ml', 1, 2, '35.00', '35.00', '35.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(225, 'P194', 'Enoxaparin 40mg  injection (CLEXANE)', 1, 2, '46.00', '50.75', '46.00', '50.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(226, 'P195', 'Epinephrine 1ml ampule ( ADRENALINE )', 1, 2, '50.00', '55.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(227, 'P196', 'Erythromycin 125mg/5ml/100ml suspension(ERYKO)', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(228, 'P197', 'Erythromycin 250mg suspension', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(229, 'P198', 'Erythromycin 500mg capsule', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(230, 'P199', 'Esomeprazole ACT 40mg vial', 1, 2, '55.00', '60.50', '55.00', '60.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(231, 'P200', 'Duo Cotecxin Tablet', 1, 2, '5.00', '5.00', '5.00', '5.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(232, 'P201', 'Etoricoxib 60mg tablet (ARCOXIA)', 1, 2, '7.00', '7.75', '7.00', '7.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(233, 'P202', 'Etoricoxib 120mg tablet (ARCOXIA)', 1, 2, '10.00', '11.00', '10.00', '11.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(234, 'P203', 'Evening Primrose 1000mg NW', 1, 2, '20.00', '20.00', '20.00', '20.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(235, 'P204', 'Etoricoxib 120mg tablet (generic)', 1, 2, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(236, 'P205', 'Ferrous Sulfate 300mg tablet (VITAMINS)', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(237, 'P206', 'Ferrous Sulfate 30mg+Folic Acid 550mcg/ 150ml syrup REDION-XT (VITAMINS)', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(238, 'P207', 'Ferrous Sulfate+B-Complex SYRUP 30mg/5ml/120ml  (FERLIN)', 1, 2, '40.00', '44.00', '40.00', '44.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(239, 'P208', 'Ferrous Sulfate+Folic Acid 200mg/0.4mg tablets (FEFOL) (VITAMINS )', 1, 2, '3.00', '3.50', '3.00', '3.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(240, 'P209', 'Fibrosine 5.9g sachet (METAMUCIL)', 1, 2, '5.50', '6.25', '5.50', '6.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(241, 'P210', 'Erythromycin Suspension 125mg', 1, 2, '25.00', '25.00', '25.00', '25.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(242, 'P211', 'Fish Oil 1500mg (Nature\'s Way)', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(243, 'P212', 'Flucloxacillin 125mg/5ml suspension', 1, 2, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(244, 'P213', 'Flucloxacillin 250mg capsule', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(245, 'P214', 'Flucloxacillin 500mg capsule', 1, 2, '4.00', '4.50', '4.00', '4.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(246, 'P215', 'Etoricoxib/Arcoxia 60mgTab', 1, 2, '12.00', '12.00', '12.00', '12.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(247, 'P216', 'Flucloxacillin 500mg injection', 1, 2, '24.00', '27.50', '24.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(248, 'P217', 'Fluconazole 150mg capsule ', 1, 2, '7.00', '7.75', '7.00', '7.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(249, 'P218', 'Fluconazole 200mg tablet (DIFLUCAN)', 1, 2, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(250, 'P219', 'Fluconazole 200mg tablet Generics', 1, 2, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(251, 'P220', 'Folic Acid plain (Ritemed)5mg tablets', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(252, 'P221', 'Fosinopril 20mg tablet', 1, 2, '3.50', '4.00', '3.50', '4.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(253, 'P222', 'Furosemide 20mg/2ml injection', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(254, 'P223', 'Furosemide 40mg/4ml injection', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(255, 'P224', 'Furazolidone 500mg tablet', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(256, 'P225', 'FerrousSO4 100mg+Folic 0.5mg', 1, 2, '2.00', '2.00', '2.00', '2.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(257, 'P226', 'FerrousSO4 300mg+Folic .25gm', 1, 2, '5.00', '5.00', '5.00', '5.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(258, 'P227', 'FerrousSO4200mg+Folic 0.4mg', 1, 2, '2.00', '2.00', '2.00', '2.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(259, 'P228', 'Furazolidone 50mg/60ml suspension', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(260, 'P229', 'Furosemide 20mg tablet', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(261, 'P230', 'Flexy Gel Liniment(Diclofenac)', 1, 2, '30.00', '30.00', '30.00', '30.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(262, 'P231', 'Flomaxtra/Tamsulosin 400ug30\'s', 1, 2, '20.00', '20.00', '20.00', '20.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(263, 'P232', 'Furosemide 40mg tablet', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(264, 'P233', 'Gaviscon tablet', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(265, 'P234', 'Gentamycin 40mg injection', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(266, 'P235', 'Gentamycin 80mg/ 2ml injection', 1, 2, '40.00', '44.00', '40.00', '44.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(267, 'P236', 'Gentamycin Eye/Ear drops 3mg/ml/5ml (KLONTAR)', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(268, 'P237', 'Gliclazide 80mg tablet', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(269, 'P238', 'Glimepiride 2mg ', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(270, 'P239', 'Glimepiride 3mg', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(271, 'P240', 'Glucosamine 750mg+ChondroitinSO4-BovineNa+200mg tablets NATURE\'S WAY', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(272, 'P241', 'Glucosamine 1500mg tablet', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(273, 'P242', 'Glucosamine w/ Fish Oil capsule', 1, 2, '1.75', '2.00', '1.75', '2.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(274, 'P243', 'Griseofulvin 500mg tablet', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(275, 'P244', 'Guaifenesin DM 60ml syrup (ROBITUSSIN)', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(276, 'P245', 'Guaifenesin DM 120ml syrup (ROBITUSSIN)', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(277, 'P246', 'Gentamicin Inj 80mg/2ml', 1, 2, '30.00', '30.00', '30.00', '30.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(278, 'P247', 'Gentamycin eye/ear drops 10ml', 1, 2, '32.00', '32.00', '32.00', '32.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(279, 'P248', 'Guaifenesin Dry Cough 100ml (ROBITUSSIN)', 1, 2, '50.00', '55.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(280, 'P249', 'Guaifenesin BP+Bromhexine HCI 100ml (SELFCARE)', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(281, 'P250', 'Garciana Cambogia', 1, 2, '3.00', '3.00', '3.00', '3.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(282, 'P251', 'Gaviscon sachet', 1, 2, '3.00', '3.00', '3.00', '3.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(283, 'P252', 'Guaifenesin+Salbutamol 120ml (HEALTHCARE)', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(284, 'P253', 'Glimeperide Tablet 2mg (Amaryl', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(285, 'P254', 'Hydralazine 20mg injection (APRESOLINE)', 1, 2, '240.00', '264.00', '240.00', '264.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(286, 'P255', 'Hydrocortisone 100mg injection (generic)', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(287, 'P256', 'Glucosamine Cap 1000mg', 1, 2, '2.00', '2.00', '2.00', '2.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(288, 'P257', 'Hydrocortisone 100mg injection (SOLU CORTEF)', 1, 2, '40.00', '44.00', '40.00', '44.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(289, 'P258', 'Hydrocortisone 200mg injection (SOLU CORTEF)', 1, 2, '45.00', '49.50', '45.00', '49.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(290, 'P259', 'Glycerol Suppository Adult 12\'', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(291, 'P260', 'Hydroxyzine 10mg tablet (ITERAX)', 1, 2, '6.00', '6.75', '6.00', '6.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(292, 'P261', 'Heraclene Tab 1mg Dibencozide', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(293, 'P262', 'Himalaya Speman 60s', 1, 2, '6.00', '6.00', '6.00', '6.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(294, 'P263', 'Hydroxyzine 25mg tablet (ITERAX)', 1, 2, '6.50', '7.25', '6.50', '7.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(295, 'P264', 'Hypromellose eye drops 15ml (Poly-Tears)', 1, 2, '45.00', '49.50', '45.00', '49.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(296, 'P265', 'Hyoscine N BuTylBromide 10mg tablet (BUSCOPAN)', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(297, 'P266', 'Hyoscine N Buyl Brumide 20mg injection (BUSCOPAN)', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(298, 'P267', 'Ibuprofen 200mg tablet', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(299, 'P268', 'Ibuprofen 400mg tablet', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(300, 'P269', 'Indomethacin 25mg capsule (INDONEX)', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(301, 'P270', 'Inosiplex 250mg/60ml syrup (IMMUNOSIN)', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(302, 'P271', 'Inosiplex 500mg tablet (IMMUNOSIN)', 1, 2, '10.00', '11.00', '10.00', '11.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(303, 'P272', 'Insulin (Humulin R)', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(304, 'P273', 'Isoniazid 60ml syrup', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(305, 'P274', 'Isopropyl Alcohol 70% 500ml ( GREEN CROSS)', 1, 2, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(306, 'P275', 'Isopropyl Alcohol 70% 60ml ( GREEN CROSS)', 1, 2, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(307, 'P276', 'Isosorbide Dinitrate 5mg tablet (ISORDIL)', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(308, 'P277', 'Isoxsuprine 10mg tablet (DUVADILAN)', 1, 2, '4.00', '4.50', '4.00', '4.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(309, 'P278', 'Ketoconazole tablet', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(310, 'P279', 'Ketorolac 30mg injection', 1, 2, '100.00', '110.00', '100.00', '110.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(311, 'P280', 'Isoprinosine 250mg 60ml', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(312, 'P281', 'Lactulose 120ml liquid (DULAX)', 1, 2, '40.00', '45.00', '40.00', '45.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(313, 'P282', 'Lactulose 500ml oral liquid (DUPHALAC)', 1, 2, '100.00', '110.00', '100.00', '110.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(314, 'P283', 'Lagundex 300mg syrup (LAGUNDI)', 1, 2, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(315, 'P284', 'Lanoxin 0.25mg tablet', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(316, 'P285', 'Lanzoprazole FDT 30mg', 1, 2, '8.00', '9.00', '8.00', '9.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(317, 'P286', 'Lecithin 1,200mg capsules (LECITHIN SWISSE): for liver health & function', 1, 2, '3.50', '4.00', '3.50', '4.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(318, 'P287', 'Levocetrizine+Montelukast 10mg/5mg (ZYKAST)', 1, 2, '10.00', '11.00', '10.00', '11.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(319, 'P288', 'Levofloxacin 500mg tablet', 1, 2, '7.00', '7.75', '7.00', '7.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(320, 'P289', 'Kids Smart Vita Gummies N/W', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(321, 'P290', 'Levothyroxine 50mCg tablet (ELTROXIN)', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(322, 'P291', 'Levothyroxine 50mg tablet ', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(323, 'P292', 'Lanzoprazole 15mg ODT', 1, 2, '7.50', '7.50', '7.50', '7.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(324, 'P293', 'Levothyroxine 75mg tablet ', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(325, 'P294', 'Lidocaine 2% injection', 1, 2, '6.00', '6.00', '6.00', '6.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(326, 'P295', 'Lidocaine injection 25mg/5ml', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(327, 'P296', 'Loperamide 2mg capsule', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(328, 'P297', 'Levothyroxine sodium 50 mgs', 1, 2, '1.50', '1.50', '1.50', '1.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(329, 'P298', 'Lidocaine Injection 1%', 1, 2, '3.00', '3.00', '3.00', '3.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(330, 'P299', 'Loratadine 5mg/60ml syrup', 1, 2, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(331, 'P300', 'Liver Marin', 1, 2, '3.00', '3.00', '3.00', '3.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(332, 'P301', 'Losartan 50mg tablet', 1, 2, '1.75', '2.00', '1.75', '2.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(333, 'P302', 'Losartan 100mg tablet', 1, 2, '2.75', '3.25', '2.75', '3.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(334, 'P303', 'Lubricating Jelly 3 grams', 1, 2, '3.00', '3.25', '3.00', '3.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(335, 'P304', 'Magnesium Sulfate injection', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(336, 'P305', 'Mala Cotecxin', 1, 2, '4.00', '4.50', '4.00', '4.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(337, 'P306', 'Mebendazole 100mg tablet', 1, 2, '5.50', '6.25', '5.50', '6.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(338, 'P307', 'Mebendazole 60ml suspension', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(339, 'P308', 'MEBO Burn & Wound Ointment', 1, 2, '200.00', '220.00', '200.00', '220.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(340, 'P309', 'Medroxyprogesterone Acetate 10mg tablet (PROVERA)', 1, 2, '8.00', '8.25', '8.00', '8.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(341, 'P310', 'Medroxyprogesterone Acetate 150mg vial (DEPOTONE)', 1, 2, '0.00', '71.50', '0.00', '71.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(342, 'P311', 'Malanil Tablet 250/100mg 12\'s', 1, 2, '12.00', '12.00', '12.00', '12.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(343, 'P312', 'Mefenamic Acid 250mg capsule', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(344, 'P313', 'Marcain Spinal .5% Heavy', 1, 2, '750.00', '750.00', '750.00', '750.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(345, 'P314', 'Mefenamic Acid 500mg capsule/tablet (MICTAL)', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(346, 'P315', 'Mefenamic Acid suspension', 1, 2, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(347, 'P316', 'Mebendazole Tab 500mg', 1, 2, '1.50', '1.50', '1.50', '1.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(348, 'P317', 'Meloxicam 7.5 mg tablet (MOBIC)', 1, 2, '2.75', '3.25', '2.75', '3.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(349, 'P318', 'Metformin 500mg tablet ', 1, 2, '1.25', '1.50', '1.25', '1.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(350, 'P319', 'Methyergometrine injection (METHERGIN)', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(351, 'P320', 'Methyldopa 250mg tablet (ALDOMET)', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(352, 'P321', 'Methylprednisolone16mg tablet (MEDROL)', 1, 2, '12.00', '13.50', '12.00', '13.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(353, 'P322', 'MefloquineTab Lariam 250mg 8\'s', 1, 2, '46.50', '46.50', '46.50', '46.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(354, 'P323', 'Metoclopramide 10mg tablet', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(355, 'P324', 'Meloxicam Tab 7.5mg Apo', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(356, 'P325', 'Merifol capsules', 1, 2, '1.50', '1.50', '1.50', '1.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(357, 'P326', 'Merifol iron tonic 200ml syrup', 1, 2, '30.00', '30.00', '30.00', '30.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(358, 'P327', 'Metoclopramide 5mg ampule', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(359, 'P328', 'Metoclopramide 10mg ampule', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(360, 'P329', 'Metoclopromide 5mg/50ml syrup', 1, 2, '16.00', '16.00', '16.00', '16.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(361, 'P330', 'Metronidazole Tab 500mg', 1, 2, '1.50', '1.50', '1.50', '1.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(362, 'P331', 'Metoclopramide 5mg/5ml/50ml', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(363, 'P332', 'Metoprolol 50mg tablet (NEOBLOC)', 1, 2, '1.75', '2.00', '1.75', '2.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(364, 'P333', 'Metoclopramide Injection 10mg', 1, 2, '30.00', '30.00', '30.00', '30.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(365, 'P334', 'Metoprolol 100mg tablet (NEOBLOC)', 1, 2, '2.25', '2.50', '2.25', '2.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(366, 'P335', 'Metronidazole 200mg tablet', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(367, 'P336', 'Metronidazole 500mg tablet', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(368, 'P337', 'Metronidazole 400mg tablet ', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(369, 'P338', 'Metronidazole 400mg tablet (FLAGYL)', 1, 2, '6.00', '6.75', '6.00', '6.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(370, 'P339', 'Metronidazole 125mg/60ml suspension', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(371, 'P340', 'Metronidazole 200mg/5ml suspension (FLAGYL)', 1, 2, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(372, 'P341', 'Metronidazole suppository', 1, 2, '25.00', '25.00', '25.00', '25.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(373, 'P342', 'Metronidazole IV 500mg/100ml ', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(374, 'P343', 'Micardis 40mg tablet', 1, 2, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(375, 'P344', 'Micardis 80mg tablet', 1, 2, '8.00', '9.00', '8.00', '9.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(376, 'P345', 'Metronidazole Tablet 500mg', 1, 2, '2.50', '2.50', '2.50', '2.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(377, 'P346', 'Micardis Plus 40mg/12.5mg tablet', 1, 2, '5.50', '6.00', '5.50', '6.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(378, 'P347', 'Micardis Plus 80mg/12.5mg ', 1, 2, '8.00', '9.00', '8.00', '9.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(379, 'P348', 'Microgynon 50ED tablet (Fertlity Pills)', 1, 2, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(380, 'P349', 'Midazolam 50mg/5ml ampule', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(381, 'P350', 'Micardis 80mg Plus 12.5mg', 1, 2, '6.50', '6.50', '6.50', '6.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(382, 'P351', 'Montelukast 10mg tablet', 1, 2, '4.00', '4.50', '4.00', '4.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(383, 'P352', 'Montelukast 4mg & 5mg tablet', 1, 2, '3.50', '4.00', '3.50', '4.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(384, 'P353', 'Montelukast GH Tab 100mg', 1, 2, '4.00', '4.00', '4.00', '4.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(385, 'P354', 'Multivitamins 100mg/15ml drops', 1, 2, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(386, 'P355', 'Montelukast Tablet 5mg 100\'s', 1, 2, '3.50', '3.50', '3.50', '3.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(387, 'P356', 'Multivitamins 60ml syrup ', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(388, 'P357', 'Multivitamins: VitC 100mg+B1,6,12,E,A,D 120ml syrup (SELVON-C)', 1, 2, '40.00', '45.00', '40.00', '45.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(389, 'P358', 'Multivitamins Hematinic w/ Zinc (MERIFOL)', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(390, 'P359', 'Multivitamins tablet complete (HEALTHCARE)', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(391, 'P360', 'Multivitamins with Antioxidants  (NATURE\'s WAY)', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(392, 'P361', 'Multivitamins+Appetite Stimulant (SUPRADYN)', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(393, 'P362', 'Multivitamins+B-Complex (CENOVIS)', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(394, 'P363', 'Multivitamins+Iron 15ml drops (MULTILEM)', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(395, 'P364', 'Multivitamins+Iron 60ml syrup ', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(396, 'P365', 'Multivitamins+Iron 200ml syrup', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(397, 'P366', 'Multivitamins+Iron tablet', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(398, 'P367', 'Mulitvitamins+Iron+Vit. C tablet', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(399, 'P368', 'Multivitamins Kids (BLACKMORE)', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(400, 'P369', 'Multivit Kids Vit.A200mcg/B6/B12/C/D/E/H(VITA GUMMIES MULTIVIT+VEGGIES)pastilles                                    ', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2);
INSERT INTO `products` (`id`, `code`, `name`, `uom_id`, `category_id`, `amount`, `amount_po`, `after_amount`, `after_amount_po`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `deleted_reason`, `status_id`) VALUES
(401, 'P370', 'Vit E Gel Cap 400IU', 1, 2, '4.00', '4.00', '4.00', '4.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(402, 'P371', 'Vit E Tab 1000IU', 1, 2, '2.00', '2.00', '2.00', '2.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(403, 'P372', 'Multivitamins Kids Fussy Eaters (VITA GUMMIES)', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(404, 'P373', 'Naproxen 500mg tablet', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(405, 'P374', 'Nifedipine 5mg tablet (ADALAT)', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(406, 'P375', 'Nifedipine 10mg tablet (ADALAT)', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(407, 'P376', 'Nifedipine 20mg tablet (ADALAT)', 1, 2, '3.00', '3.50', '3.00', '3.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(408, 'P377', 'MX3 tablet', 1, 2, '2.00', '2.00', '2.00', '2.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(409, 'P378', 'Nitro Extreme bottle', 1, 2, '220.00', '220.00', '220.00', '220.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(410, 'P379', 'Nitro Fx Drops', 1, 2, '200.00', '200.00', '200.00', '200.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(411, 'P380', 'Nasatapp Drop', 1, 2, '18.00', '18.00', '18.00', '18.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(412, 'P381', 'Nasonex Allergy65 meterd spray', 1, 2, '120.00', '120.00', '120.00', '120.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(413, 'P382', 'Natura-Ceuticals/C24/7 Capsule', 1, 2, '3.50', '3.50', '3.50', '3.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(414, 'P383', 'Norfloxacin 400mg tablet', 1, 2, '2.80', '3.50', '2.80', '3.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(415, 'P384', 'Nutroplex 120ml syrup', 1, 2, '30.00', '30.00', '30.00', '30.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(416, 'P385', 'Nystatin (100,000 IU) 30ml drops', 1, 2, '65.00', '72.00', '65.00', '72.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(417, 'P386', 'Oestrogen Premium 625mcg/Premarin tablet', 1, 2, '6.50', '6.50', '6.50', '6.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(418, 'P387', 'Ofloxacin 200mg tablet', 1, 2, '4.50', '5.00', '4.50', '5.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(419, 'P388', 'Ofloxacin 400mg tablet', 1, 2, '5.50', '6.25', '5.50', '6.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(420, 'P389', 'Omeprazole 20mg capsule', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(421, 'P390', 'Omeprazole 40mg capsule', 1, 2, '3.00', '3.50', '3.00', '3.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(422, 'P391', 'Omeprazole 40mg vial', 1, 2, '50.00', '55.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(423, 'P392', 'Orlistat 120mg capsule (XENICAL)', 1, 2, '7.50', '8.25', '7.50', '8.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(424, 'P393', 'ORS (Oral Rehydration Salt) ', 1, 2, '6.00', '6.25', '6.00', '6.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(425, 'P394', 'Oxantel+Pyrantel 10ml (QUANTREL)', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(426, 'P395', 'Oxytocin 10 \'u\' ampule (SYNTOMETRINE)', 1, 2, '95.00', '104.50', '95.00', '104.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(427, 'P396', 'Pantoprazole Sodium+Domperidone 40+10mg tablet', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(428, 'P397', 'Pantoprazole injection', 1, 2, '50.00', '55.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(429, 'P398', 'Paracetamol pedia 15ml drops (CROCIN)', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(430, 'P399', 'Paracetamol ORAL DROPS/15ml ( HEALTHCARE )', 1, 2, '20.00', '25.00', '20.00', '25.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(431, 'P400', 'Omega3 Antioxidant Sachet', 1, 2, '8.96', '8.96', '8.96', '8.96', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(432, 'P401', 'Paracetamol 125mg supp. (OPIGESIC)', 1, 2, '10.00', '11.00', '10.00', '11.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(433, 'P402', 'Paracetamol 250mg supp.', 1, 2, '20.00', '22.50', '20.00', '22.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(434, 'P403', 'Paracetamol 250mg/60ml syrup (BIOGIC)', 1, 2, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(435, 'P404', 'Opigesic Suppository 125mg', 1, 2, '10.00', '10.00', '10.00', '10.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(436, 'P405', 'Opigesic Suppository 250mg', 1, 2, '10.00', '10.00', '10.00', '10.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(437, 'P406', 'Oroxine Tablet 50mcg', 1, 2, '1.50', '1.50', '1.50', '1.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(438, 'P407', 'Paracetamol 125mg +Phenylephrine HCl+Chlorpheniramine 60ml Syrup (COLDEZE)', 1, 2, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(439, 'P408', 'Paracetamol 300mg/2ml injection', 1, 2, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(440, 'P409', 'Paracetamol 500mg tablet (generic)', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(441, 'P410', 'Paracetamol + Codeine ( CO-CODAMOL)', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(442, 'P411', 'Paracetamol+Chlorphenamine+Phenylephrine 500mg tablet (BIOFLU)', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(443, 'P412', 'ParacetamolPARA+ PhenylpropanolaminePPA325mg+Chlorphenamine tab(SYMDEX-D)', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(444, 'P413', 'PARAcetamol+PPA(phenylpropanolamine)+CPMchlorphenamine syrup 60ml(SYMDEX)', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(445, 'P414', 'Paracetamol 325mg + Ibuprofen 400mg tablets (IBUGESIC PLUS)', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(446, 'P415', 'Pethidine 50mg ampule', 1, 2, '60.00', '66.00', '60.00', '66.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(447, 'P416', 'Phenobarbital Tablet 30mg', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(448, 'P417', 'Phenylpropanolamine 10ml drops (DIMETAPP, DISUDRIN)', 1, 2, '25.00', '25.00', '25.00', '25.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(449, 'P418', 'Phenylephrine+Chlorphenamine 10ml drops  (DISUDRIN)', 1, 2, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(450, 'P419', 'Phenylpropanolamine 60ml syrup (DIMETAPP)', 1, 2, '35.00', '35.00', '35.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(451, 'P420', 'Phenobarbital Tablet 30mg', 1, 2, '1.50', '1.50', '1.50', '1.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(452, 'P421', 'Phenylephrine+Chlorphenamine 60ml syrup (DISUDRIN)', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(453, 'P422', 'Phenylephrine+Chlorphenamine 30ml drops (NEOZEP)', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(454, 'P423', 'Phenylephrine+Chlorphenamine+Paracetamol 60ml syrup (NEOZEP)', 1, 2, '35.00', '37.50', '35.00', '37.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(455, 'P424', 'Phenylephrine+Paracetamol NonDrowsy tablet (NEOZEP)', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(456, 'P425', 'Phospolipids 300mg capsule (ESSENTIALE FORTE)', 1, 2, '1.75', '2.00', '1.75', '2.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(457, 'P426', 'Pinene+Camphene tablet (ROWACHOL)', 1, 2, '8.00', '8.00', '8.00', '8.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(458, 'P427', 'Phytomenadione Inj 10mg', 1, 2, '25.00', '25.00', '25.00', '25.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(459, 'P428', 'Phytomenadione Inj 1mg/0.1ml', 1, 2, '22.50', '25.00', '22.50', '25.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(460, 'P429', 'Pinene+Camphene tablet (ROWATINEX)', 1, 2, '13.00', '14.50', '13.00', '14.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(461, 'P430', 'Piroxicam 10mg tablet', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(462, 'P431', 'Piroxicam 20mg tablet', 1, 2, '2.50', '2.50', '2.50', '2.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(463, 'P432', 'Polymyxin B+Neomycin SO4 + Dexa 5ml (RAPIDAX) Ear drops', 1, 2, '45.00', '50.00', '45.00', '50.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(464, 'P433', 'Polymyxin+Neomycin+Dexa (SYNTEMAX) eye drops', 1, 2, '45.00', '48.50', '45.00', '48.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(465, 'P434', 'Polymycetin-DX', 1, 2, '40.00', '44.00', '40.00', '44.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(466, 'P435', 'Polymexin Eye Sore drops', 1, 2, '25.00', '25.00', '25.00', '25.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(467, 'P436', 'Ponstan Cap SF 500mg', 1, 2, '6.50', '7.25', '6.50', '7.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(468, 'P437', 'Potassium Chloride 600mg tablet', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(469, 'P438', 'Potassium Citrate 10mEq tablet (ACALKA)', 1, 2, '6.50', '7.25', '6.50', '7.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(470, 'P439', 'Prednisolone 5mCg tablet', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(471, 'P440', 'Prednisolone 25mCg tablet', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(472, 'P441', 'Prednisone 10mg/5ml suspension', 1, 2, '50.00', '55.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(473, 'P442', 'Primaquine 7.5mg tablet', 1, 2, '1.00', '1.00', '1.00', '1.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(474, 'P443', 'Pregabalin 150mg (LYRICA) for nerve-related pain', 1, 2, '7.00', '7.75', '7.00', '7.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(475, 'P444', 'Primaquine 7.5mg tablet', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(476, 'P445', 'Propranolol 10mg tablet (INDERAL)', 1, 2, '3.00', '3.50', '3.00', '3.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(477, 'P446', 'Propranolol 40mg tablet (INDERAL)', 1, 2, '4.50', '5.00', '4.50', '5.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(478, 'P447', 'Propylthiouracil PTU 5mg tablet', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(479, 'P448', 'Provera 10mg tablet', 1, 2, '7.50', '8.50', '7.50', '8.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(480, 'P449', 'Pyrantel Pamoate Suspension 15ml', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(481, 'P450', 'Quinine HCI 600mg ampule/injection', 1, 2, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(482, 'P451', 'Quinine Sulfate 300mg tablet', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(483, 'P452', 'Rebamipide 100mg tablet (MUCOSTA)', 1, 2, '6.00', '6.00', '6.00', '6.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(484, 'P453', 'Renalin Multi-Herb 500mg', 1, 2, '2.50', '2.50', '2.50', '2.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(485, 'P454', 'Ranitidine ampule (ZANTRICID)', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(486, 'P455', 'Ritalin Tablet 10mg tablet (for ADHD)', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(487, 'P456', 'Salbutamol 2.5mcg nebule (nebule only)', 1, 2, '6.00', '6.75', '6.00', '6.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(488, 'P457', 'Salbutamol 5mcg nebule  (nebule only)', 1, 2, '7.00', '7.75', '7.00', '7.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(489, 'P458', 'Salbutamol 2mg tablet', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(490, 'P459', 'Salbutamol 4mg tablet', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(491, 'P460', 'Salbutamol+Carbocistiene Broncho capsule (SOLMUX)', 1, 2, '4.00', '4.50', '4.00', '4.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(492, 'P461', 'Salbutamol+Carbocistine 120ml Bronco syrup (SOLMUX)', 1, 2, '30.00', '30.00', '30.00', '30.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(493, 'P462', 'Rosuvastatin Tab 10mg', 1, 2, '4.50', '4.50', '4.50', '4.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(494, 'P463', 'Rosuvastatin Tab 20mg', 1, 2, '6.50', '7.25', '6.50', '7.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(495, 'P464', 'Salbutamol+Guiafenesin Expectorant capsule (VENTOLIN)', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(496, 'P465', 'Salbutamol+ Guiafenesin Expectorant syrup (VENTOLIN)', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(497, 'P466', 'Salbutamol+Guaifenesin 500mg capsule (VENTREX G)', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(498, 'P467', 'Salbutamol+Guiafenesin 1mg/ 50mg/60ml suspension (GUIACOF)', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(499, 'P468', 'Saline Nasal spray (STUFFINOSE) 30ml', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(500, 'P469', 'Bromhexine 4mg+Guiafenesin 100mg 100ml syrup (SELFCARE CHESTY COUGH MEDICINE)', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(501, 'P470', 'Senna fruit 7.5mg tablets for constipation (SENNA / SENOKOT)', 1, 2, '3.00', '3.25', '3.00', '3.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(502, 'P471', 'Salbutamol+Ipatropium nebule (IPRACARE)', 1, 2, '7.00', '7.75', '7.00', '7.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(503, 'P472', 'Sertaline (ZOLOFT) 100mg tablets', 1, 2, '4.50', '5.00', '4.50', '5.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(504, 'P473', 'Sodium Chloride (Salinase/Stuffinose) 30ml Nasal drops', 1, 2, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(505, 'P474', 'Senna Concentrate tablet (SENOKOT)', 1, 2, '1.50', '1.50', '1.50', '1.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(506, 'P475', 'Salbutamol Syrup 2mg 60ml', 1, 2, '20.00', '20.00', '20.00', '20.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(507, 'P476', 'Salbutamol Nebule 2.5mg', 1, 2, '5.00', '5.00', '5.00', '5.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(508, 'P477', 'Salbutamol Nebules 5mg', 1, 2, '30.00', '30.00', '30.00', '30.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(509, 'P478', 'Silymarin 125mg capsule (LIVERAIDE)', 1, 2, '5.00', '5.00', '5.00', '5.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(510, 'P479', 'DR WONG\'S SULFUR SOAP 80 grms Scabicide-Pediculicide (not covered by insurance)', 1, 2, '10.00', '11.00', '10.00', '11.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(511, 'P480', 'Silymarin Liver Detox (Nature\'sway)', 1, 2, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(512, 'P481', 'Simvastatin 10mg tablet', 1, 2, '2.00', '2.00', '2.00', '2.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(513, 'P482', 'Shilajit 100 Cap', 1, 2, '3.00', '3.00', '3.00', '3.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(514, 'P483', 'Simvastatin 20mg tablet', 1, 2, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(515, 'P484', 'Sodium Alginate+Bicarbonate+Calcium heartburn 150ml suspension', 1, 2, '20.00', '22.50', '20.00', '22.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(516, 'P485', 'Sodium Bicarbonate 840mg capsule (SODIBIC)', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(517, 'P486', 'Sulfadoxine+Pyrimethamine 525mg (FANSIDAR)', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(518, 'P487', 'Tamsulosin HCL 0.4mg tablet (VELTAM 0.4)', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(519, 'P488', 'SodiumBicarbonate 560mg Tab', 1, 2, '20.00', '20.00', '20.00', '20.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(520, 'P489', 'Tamsulosin HCl 200mcg tablets (generic)', 1, 2, '3.00', '3.50', '3.00', '3.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(521, 'P490', 'Tapazole 5mg tablet', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(522, 'P491', 'Tapazole 20mg tablet', 1, 2, '5.50', '6.25', '5.50', '6.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(523, 'P492', 'SoluCortef Vial  200mg', 1, 2, '5.50', '5.50', '5.50', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(524, 'P493', 'SoluCortef Vial 100mg', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(525, 'P494', 'Speman Increase Tablet 60\'', 1, 2, '30.00', '30.00', '30.00', '30.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(526, 'P495', 'Stud 5000 spray', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(527, 'P496', 'Sulmox Bronco Syrup 60ml', 1, 2, '300.00', '300.00', '300.00', '300.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(528, 'P497', 'Sunrise Multivitamins 30\'s', 1, 2, '29.00', '29.00', '29.00', '29.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(529, 'P498', 'Sunset EPA/DHA Caps/Bot 90\'s', 1, 2, '250.00', '250.00', '250.00', '250.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(530, 'P499', 'Symbicort Turbohaler 200/60', 1, 2, '315.00', '315.00', '315.00', '315.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(531, 'P500', 'Syntometrine ampule', 1, 2, '408.00', '408.00', '408.00', '408.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(532, 'P501', 'Telmisartan 40mg tablet', 1, 2, '4.00', '4.50', '4.00', '4.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(533, 'P502', 'Telmisartan 80mg + Hydrochlorothiazide 12.5mg (APO) tablets', 1, 2, '8.00', '8.25', '8.00', '8.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(534, 'P503', 'Telmisartan 40mg/12.5mg Hydrochlorothiazide (MICARDIS PLUS)', 1, 2, '5.00', '5.25', '5.00', '5.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(535, 'P504', 'Terazosin 5mg tablet (HYTRIN)', 1, 2, '17.00', '18.75', '17.00', '18.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(536, 'P505', 'Tramadol ampule', 1, 2, '45.00', '50.00', '45.00', '50.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(537, 'P506', 'Tergecef 200mg capsule', 1, 2, '45.00', '45.00', '45.00', '45.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(538, 'P507', 'Tiki Tiki Drops B Complex 30ml', 1, 2, '25.00', '25.00', '25.00', '25.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(539, 'P508', 'Tiki-Tiki Plus Drops 15ml', 1, 2, '20.00', '20.00', '20.00', '20.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(540, 'P509', 'Tracrium 25mg/2.5ml Ampule', 1, 2, '15.00', '15.00', '15.00', '15.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(541, 'P510', 'Tramadol 50mg tablet', 1, 2, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(542, 'P511', 'Tramadol HCl 100mg tablet', 1, 2, '5.50', '6.25', '5.50', '6.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(543, 'P512', 'Tramadol + Paracetamol 37.5/325mg (REMP)', 1, 2, '5.50', '6.25', '5.50', '6.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(544, 'P513', 'Tranexamic Acid 100mg/2.5ml capsule', 1, 2, '5.50', '6.25', '5.50', '6.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(545, 'P514', 'Tranexamic Acid ampule/injection', 1, 2, '50.00', '55.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(546, 'P515', 'Trimetazidine MR 35mg tablet (VASTAREL)', 1, 2, '12.00', '13.25', '12.00', '13.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(547, 'P516', 'Ultratab', 1, 2, '12.00', '12.00', '12.00', '12.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(548, 'P517', 'Valu-Pak Multivitamin Tablet', 1, 2, '1.00', '1.00', '1.00', '1.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(549, 'P518', 'Valu-Pak Omega 3 Fish Oil Caps', 1, 2, '1.00', '1.00', '1.00', '1.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(550, 'P519', 'Vitamin A 25000 IU softgel capsule', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(551, 'P520', 'Vit. B-Complex tablet (NERVITA tabs like NEUROBION PLAIN)', 1, 2, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(552, 'P521', 'Vit. B-Complex tablet + Paracetamol  (COM-B-FORTE like DOLO-NEUROBION) ', 1, 2, '7.00', '7.50', '7.00', '7.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(553, 'P522', 'Vit B-Complex + Calcium + Magnesium & Zinc Capsules (ZINCOPLEX forte)', 1, 2, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(554, 'P523', 'Vit. B Complex tablets (NATURE\'S WAY MEGA B executive stress)', 1, 2, '1.50', '1.75', '1.50', '1.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(555, 'P524', 'Vitamin:  Zinc capsules 50mg', 1, 2, '2.50', '3.00', '2.50', '3.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(556, 'P525', 'Vicks Vaporub 10g', 1, 2, '3.00', '3.00', '3.00', '3.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(557, 'P526', 'Vitamin Ferrous+ BComplex 120ml (FERLIN)', 1, 2, '15.00', '15.00', '15.00', '15.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(558, 'P527', 'Vitamin:  Zinc syrup 20mg/5ml 60ml', 1, 2, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(559, 'P528', 'Vitamin: Zinc oral drops 10mg/ml 15ml', 1, 2, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(560, 'P529', 'VitaminD3 1000IU/cholecalciferol25mcgVITAMIN D cap: helps Ca+absorption', 1, 2, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(561, 'P530', 'Vit. E capsule (NEUROGEN-E)', 1, 2, '3.00', '3.25', '3.00', '3.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(562, 'P531', 'Vit. K 1mg/1ml ampule', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(563, 'P532', 'Vit. K 10mg ampule (MENADIONE)', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(564, 'P533', 'Vit A Cap 5000IU', 1, 2, '30.00', '30.00', '30.00', '30.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(565, 'P534', 'Vit. B-Complex Syr 120ml/POLYNERVE', 1, 2, '10.00', '10.00', '10.00', '10.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(566, 'P535', 'Vitamin C+Zinc Syrup 60ml syrup (INCREMIN)', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(567, 'P536', 'Vitanex Jnr, Bcomplex+L-Lysine', 1, 2, '35.00', '35.00', '35.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(568, 'P537', 'VitBcomp+Buc+Lys+Fe cap (Ferle', 1, 2, '35.00', '35.00', '35.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(569, 'P538', 'Whynot12 Intimate cream RC-Men', 1, 2, '3.00', '3.00', '3.00', '3.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(570, 'P539', 'Zolpidem Tartrate 10mg tablet (STILNOX)', 1, 2, '22.00', '24.50', '22.00', '24.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(571, 'P540', 'Antibiotic Ointment 20g GENERICS', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(572, 'P541', 'Antifungal Cream 10g GENERICS', 1, 2, '35.00', '35.00', '35.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(573, 'P542', 'Anusol Ointment 50g', 1, 2, '68.00', '68.00', '68.00', '68.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(574, 'P543', 'Betamethasone Valerate Cream (Betnovate Skin Cream) 20g ', 1, 2, '35.00', '40.00', '35.00', '40.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(575, 'P544', 'Burn Ointment 15g (Chlorobutanol+Benzalkonium Chloride+Alpha Tocopheryl Acetate)', 1, 2, '40.00', '40.00', '40.00', '40.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(576, 'P545', 'Burn Ointment United Home (Benzocaine+Boric Acid+ Eucalyptus) ointment 30g', 1, 2, '40.00', '40.00', '40.00', '40.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(577, 'P546', 'Calmoseptine Ointment 3.5g sachet (Calamine+Zinc Oxide)', 1, 2, '15.00', '15.00', '15.00', '15.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(578, 'P547', 'Calamine + Zinc Oxide  15 grms cream ( CALAZIN )', 1, 2, '40.00', '45.00', '40.00', '45.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(579, 'P548', 'ClobetazolePropionate+NeomycinSulfate+MiconazoleNitrate+NeomycinSulfate(CLOBETA GM (cream) 10gms  ', 1, 2, '35.00', '35.00', '35.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(580, 'P549', 'Clobetasol Propionate500mcg/g/5grms  ( DERMOVATE) cream', 1, 2, '48.00', '48.00', '48.00', '48.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(581, 'P550', 'Clozolo Cream 15g (CLOTRIMAZOLE)', 1, 2, '30.00', '30.00', '30.00', '30.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(582, 'P551', 'Clotrimazole+Beclomethasone Dipropionate+Neomycin Sulfate Cream (CANDIDERMA) CREAM 10 grms', 1, 2, '55.00', '55.00', '55.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(583, 'P552', 'Clotrimazole 10mg cream 20 grms tube', 1, 2, '30.00', '35.00', '30.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(584, 'P553', 'CLOTRINEC (Cotrimazole Dusting Powder)', 1, 2, '25.00', '25.00', '25.00', '25.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(585, 'P554', 'Diclofenac Sod+Methyl Salicylate+Linseed Oil+Menthol Gel (DICLOZED) 30 grms', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(586, 'P555', 'Gamma Benzene Hexachloride & Cetrimide Lotion for scabies (NECSAB lotion ) 100ml', 1, 2, '30.00', '30.00', '30.00', '30.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(587, 'P556', 'Itraconazole, Ofloxacin, Ornidazole & Clobetasol Propionate Cream  (KONAN DERM) antifungal&antibacterial', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(588, 'P557', 'Ketoconazole+Neomycin+Clobetasol Propionate Cream (KETOZEST PLUS) 15grms', 1, 2, '35.00', '35.00', '35.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(589, 'P558', 'Ketoconazole 2% Cream (KT-SAL) 20 grms', 1, 2, '35.00', '35.00', '35.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(590, 'P559', 'Laboderm- OC 15grams (Ofloxacin+Ordinazole+Terbinafine HCl+Clobetazole Propionate)', 1, 2, '35.00', '35.00', '35.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(591, 'P560', 'MEBO Burn & Wound Ointment 40grms', 1, 2, '200.00', '220.00', '200.00', '220.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(592, 'P561', 'Neomycin+PolymyxinBSO4+Bacitracin Zinc(NEOPAR-PLUS) 15gr oint ', 1, 2, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(593, 'P562', 'Permethrin5% Lotion for Scabies 50ml', 1, 2, '40.00', '45.00', '40.00', '45.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(594, 'P563', 'MUPIROCIN  ointment 20mg/g/5 grms', 1, 2, '38.00', '38.00', '38.00', '38.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(595, 'P564', 'Quadrotopic Ointment (Bethamethasone+Cliquinol+Gentamicin+Tolnaflate)', 1, 2, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(596, 'P565', 'Silver Sulfadiazine cream', 1, 2, '40.00', '45.00', '40.00', '45.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(597, 'P566', 'BCG', 1, 2, '60.00', '60.00', '60.00', '60.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(598, 'P567', 'ORAL POLIO VACCINE', 1, 2, '60.00', '60.00', '60.00', '60.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(599, 'P568', 'PENTAVALENT VACCINE ( 5 in 1 )', 1, 2, '250.00', '250.00', '250.00', '250.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(600, 'P569', 'ROTAVIRUS VACCINE', 1, 2, '500.00', '500.00', '500.00', '500.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(601, 'P570', 'INFANRIX VACCINE ( 5 in 1  with acellular pertussis component )', 1, 2, '500.00', '500.00', '500.00', '500.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(602, 'P571', 'HEPATITIS B VACCINE ( PEDIA )', 1, 2, '60.00', '60.00', '60.00', '60.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(603, 'P572', 'Zyrtec oral solution', 1, 2, '25.00', '25.00', '25.00', '25.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(604, 'P573', 'Zyrtec tablet', 1, 2, '6.50', '6.50', '6.50', '6.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(605, 'P574', 'HEPATITIS B VACCINE ( ADULT )', 1, 2, '120.00', '120.00', '120.00', '120.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(606, 'P575', 'MEASLES VACCINE', 1, 2, '100.00', '100.00', '100.00', '100.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(607, 'P576', 'MMR VACCINE', 1, 2, '350.00', '350.00', '350.00', '350.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(608, 'P577', 'FLU VACCINE', 1, 2, '270.00', '270.00', '270.00', '270.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(609, 'P578', 'HEPATITIS A VACCINE', 1, 2, '400.00', '400.00', '400.00', '400.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(610, 'P579', 'PNEUMOCOCCAL VACCINE', 1, 2, '450.00', '450.00', '450.00', '450.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(611, 'P580', 'MENINGOCOCCAL VACCINE', 1, 2, '400.00', '400.00', '400.00', '400.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(612, 'P581', 'VARICELLA ( CHICKENPOX ) VACCINE', 1, 2, '600.00', '600.00', '600.00', '600.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(613, 'P582', 'TETANUS TOXOID VACCINE', 1, 2, '60.00', '60.00', '60.00', '60.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(614, 'P583', 'JAPANESE ENCEPHALITIS VACCINE', 1, 2, '285.00', '285.00', '285.00', '285.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(615, 'P584', 'PREVENAR VACCINE', 1, 2, '800.00', '800.00', '800.00', '800.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(616, 'P585', 'TYPHOID VACCINE', 1, 2, '200.00', '200.00', '200.00', '200.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(617, 'P586', 'CERVICAL CANCER VACCINE', 1, 2, '400.00', '400.00', '400.00', '400.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(618, 'P587', 'CERVICAL COLLAR ( SOFT )', 1, 2, '120.00', '140.00', '120.00', '140.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(619, 'P588', 'KNEE SUPPORT', 1, 2, '100.00', '115.00', '100.00', '115.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(620, 'P589', 'ANKLE SUPPORT', 1, 2, '100.00', '115.00', '100.00', '115.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(621, 'P590', 'CLAVICULAR SUPPORT', 1, 2, '175.00', '202.00', '175.00', '202.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(622, 'P591', 'LUMBAR / BACK SUPPORT', 1, 2, '450.00', '520.00', '450.00', '520.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(623, 'P592', 'VARICOSE STOCKINGS', 1, 2, '300.00', '350.00', '300.00', '350.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(624, 'P593', 'ABDOMINAL BINDER', 1, 2, '80.00', '90.00', '80.00', '90.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(625, 'P594', 'ATHLETE SUPPORTER                 ', 1, 2, '150.00', '165.00', '150.00', '165.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(626, 'L001', 'Full Blood Count', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(627, 'L002', 'BT (Bleeding Time)', 2, 1, '20.00', '30.00', '25.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(628, 'L003', 'CT (Clotting Time)', 2, 1, '20.00', '30.00', '25.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(629, 'L004', 'ESR (Erythrocyte Sedimentation Rate)', 2, 1, '25.00', '30.00', '30.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(630, 'L005', 'ABO Blood Typing with RH Typing', 2, 1, '35.00', '40.00', '40.50', '46.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(631, 'L006', 'G6PD (Glucose 6-Phosphate Dehydrogenase)', 2, 1, '40.00', '50.00', '46.00', '60.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(632, 'L007', 'FBS/RBS (Fasting/Random Blood Glucosesugar)', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(633, 'L008', 'Total Cholesterol', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(634, 'L009', 'Triglycerides', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(635, 'L010', 'HDL (High Density Lipoprotien)', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(636, 'L011', 'LDL (Low Density Lipoprotien)', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(637, 'L012', 'VLDL (Very Low Density Lipoprotien)', 2, 1, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(638, 'L013', 'BUN (Blood Uric Nitrogen)', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(639, 'L014', 'Total Bilirubin', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(640, 'L015', 'Direct Bilirubin', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(641, 'L016', 'Indirect Bilirubin', 2, 1, '0.00', '0.00', '0.00', '0.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(642, 'L017', 'Urea (Blood Urea Nitrogen / BUN)', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(643, 'L018', 'Uric Acid', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(644, 'L019', 'ALT/SGPT (Alanine Transaminase) - Liverenzymes', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(645, 'L020', 'AST/SGOT (Aspartate Aminotransferase) - Liverenzymes', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(646, 'L021', 'GGT (Gamma-Glutamyl Transferase) - Liver', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(647, 'L022', 'ALP (Alanine Transaminase) - Liver', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(648, 'L023', 'Alkaline Phosphatase', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(649, 'L024', 'Total Protiens', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(650, 'L025', 'Albumin', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(651, 'L026', 'TP A/G Ratio (Total Proteins Albumin/Globulinratio)', 2, 1, '80.00', '85.00', '95.00', '100.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(652, 'L027', 'HbA1C (Hemoglobin A1C)', 2, 1, '80.00', '90.00', '95.00', '105.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(653, 'L028', 'Electrolytes (Na+, K+, Cl-, iCa-)', 2, 1, '160.00', '180.00', '175.00', '190.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(654, 'L029', 'Rapid Typhoid Test (IgG & IgM)', 2, 1, '35.00', '40.00', '42.00', '50.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(655, 'L030', 'VDRL/RPR', 2, 1, '30.00', '35.00', '35.00', '42.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(656, 'L031', 'RAPID SYPHILIS TEST', 2, 1, '30.00', '35.00', '35.00', '42.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(657, 'L032', 'HIV ANTIBODY TEST (HIV 1/ HIV 2 )', 2, 1, '25.00', '30.00', '30.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(658, 'L033', 'HBsAg ( Hepatitis B Antigen Rapid test )', 2, 1, '25.00', '30.00', '30.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(659, 'L034', 'HAV ( Hepatitis A Antibody IgG/ IgM Rapid Test)', 2, 1, '25.00', '30.00', '30.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(660, 'L035', 'TB RAPID IgG/IgM Antibody Test', 2, 1, '25.00', '30.00', '30.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(661, 'L036', 'ASO SCREENING TEST ( Anti-Streptolysin - O )', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(662, 'L037', 'RF SCREENING TEST (RHEUMATOID FACTOR)', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(663, 'L038', 'H. PYLORI TEST ( HELICOBACTER PYLORI IN GASTRITIS PEPTIC ULCER DISEASE )', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(664, 'L039', 'TROPONIN I(RELEASED WHEN HEART MUSCLES DAMAGED AS IN HEART ATTACK)', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(665, 'L040', 'CHLAMYDIA RAPID TEST', 2, 1, '35.00', '40.00', '42.00', '50.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(666, 'L041', 'GONORRHEA RAPID TEST', 2, 1, '30.00', '35.00', '35.00', '42.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(667, 'L042', 'COMPLETE URINALYSIS', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(668, 'L043', 'FECALYSIS/ STOOL EXAM', 2, 1, '30.00', '40.00', '35.00', '48.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(669, 'L044', 'SEMEN ANALYSIS', 2, 1, '35.00', '45.00', '42.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(670, 'L045', 'PREGNANCY TEST (HCG/URINE/BLOOD)', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(671, 'L046', 'OCCULT BLOOD TEST', 2, 1, '40.00', '45.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(672, 'L047', 'MALARIA SMEAR', 2, 1, '18.00', '20.00', '25.00', '30.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(673, 'L048', 'MALARIA ANTIGEN RAPID TEST', 2, 1, '28.00', '30.00', '35.00', '40.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(674, 'L049', 'MALARIA ANTIBODY RAPID TEST', 2, 1, '28.00', '30.00', '35.00', '40.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(675, 'L050', 'DENGUE DUO TEST (NS1, IgM/IgG)', 2, 1, '100.00', '110.00', '115.00', '130.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(676, 'L051', 'HVS Wet Prep (High Vaginal Swab)', 2, 1, '30.00', '35.00', '35.00', '42.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(677, 'L052', 'FUNGAL EXAM (KOH)', 2, 1, '30.00', '35.00', '35.00', '42.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(678, 'L053', 'GRAM STAIN', 2, 1, '35.00', '40.00', '42.00', '50.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(679, 'L054', 'DOA TEST (URINE) DRUG OF ABUSE', 2, 1, '60.00', '65.00', '60.00', '65.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(680, 'L055', 'ALCOHOL BREATH TEST', 2, 1, '35.00', '40.00', '35.00', '40.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(681, 'L056', 'TFT (THYROID FUNCTION TESTS) - T3', 2, 1, '150.00', '160.00', '150.00', '160.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(682, 'L057', 'TFT (THYROID FUNCTION TESTS) - T4', 2, 1, '150.00', '160.00', '150.00', '160.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(683, 'L058', 'TFT (THYROID FUNCTION TESTS) - TSH', 2, 1, '150.00', '160.00', '150.00', '160.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(684, 'L059', 'TFT (THYROID FUNCTION TESTS) - FT4', 2, 1, '150.00', '160.00', '150.00', '160.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(685, 'L060', 'CHOLINESTERASE TEST -TEST POISONING FROMORGANO-PHOSPHATES IN PESTICIDES', 2, 1, '30.00', '35.00', '30.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(686, 'L061', 'PSA ( PROSTATE SPECIFIC ANTIGEN )', 2, 1, '150.00', '165.00', '150.00', '165.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(687, 'L062', 'CEA (CARCINOEMBRYONIC ANTIGEN) - OVARY, COLON,THYROID', 2, 1, '150.00', '165.00', '150.00', '165.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(688, 'L063', 'AFP ( ALPHA FETOPROTEIN ) - LIVER, OVARIES,TESTICLES', 2, 1, '150.00', '165.00', '150.00', '165.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(689, 'L064', 'ECG (12 LEADS)', 2, 1, '120.00', '135.00', '120.00', '135.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(690, 'L065', 'SPIROMETRY', 2, 1, '150.00', '165.00', '150.00', '165.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(691, 'L066', 'AUDIOMETRY', 2, 1, '180.00', '200.00', '180.00', '200.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(692, 'S001', 'Emergency Room Fee', 2, 4, '50.00', '60.00', '60.00', '70.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(693, 'S002', 'Nursing Fee', 2, 4, '15.00', '25.00', '15.00', '25.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(694, 'S003', 'Injection Fee - Artemether', 2, 4, '15.00', '16.50', '20.00', '25.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(695, 'S004', 'Injection Fee - Other', 2, 4, '25.00', '27.50', '30.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(696, 'S005', 'Dressing Fee', 2, 4, '50.00', '60.00', '60.00', '70.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(697, 'S006', 'Nebulization - Salbutamol Pediatric 2.5mcg Nebule', 2, 4, '30.00', '35.00', '35.00', '40.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(698, 'S007', 'Nebulization - Salbutamol Adult 5mcg Nebule', 2, 4, '35.00', '40.00', '40.25', '47.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(699, 'S008', 'Nebulization - Budesonide Nebule', 2, 4, '50.00', '58.00', '57.50', '66.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(700, 'S009', 'Nebulization - Salbutamol + Ipatropium Nebule', 2, 4, '50.00', '58.00', '57.50', '66.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(701, 'S010', 'Suction Machine - Light Use', 2, 4, '40.00', '46.00', '46.00', '53.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(702, 'S011', 'Suction Machine - Heavy Use', 2, 4, '60.00', '70.00', '69.00', '80.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(703, 'S012', 'Pulse Oximeter Fee', 2, 4, '20.00', '25.00', '25.00', '28.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(704, 'S013', 'Cardiac Monitor with Pulse Oximeter Fee', 2, 4, '100.00', '120.00', '120.00', '140.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(705, 'S014', 'Cautery Machine', 2, 4, '100.00', '115.00', '115.00', '135.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(706, 'S015', 'Doppler or Fetal Monitor', 2, 4, '50.00', '60.00', '65.00', '75.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(707, 'S016', 'Viginal Speculum', 2, 4, '25.00', '30.00', '30.00', '35.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(708, 'S017', 'Oxygen Concentrator', 2, 4, '60.00', '70.00', '70.00', '85.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(709, 'S018', 'PAP Smear Fee', 2, 4, '150.00', '175.00', '175.00', '220.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(710, 'S019', 'Pediatric Circumcision', 2, 4, '620.00', '720.00', '750.00', '850.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(711, 'S020', 'Adult Circumcision', 2, 4, '770.00', '895.00', '890.00', '1030.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(712, 'S021', 'Indwelling Cathether Insertion - IDC Insertion', 2, 4, '275.00', '320.00', '320.00', '370.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(713, 'S022', 'NGT Insertion', 2, 4, '275.00', '320.00', '320.00', '370.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(714, 'S023', 'NGT Insertion with Gastric Lavage', 2, 4, '375.00', '470.00', '320.00', '370.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(715, 'S024', 'Ear Irrigation', 2, 4, '270.00', '315.00', '320.00', '370.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(716, 'S025', 'Removal Foreign Body - Ear', 2, 4, '150.00', '165.00', '175.00', '200.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(717, 'S026', 'Removal Foreign Body - Eye', 2, 4, '200.00', '220.00', '230.00', '265.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(718, 'S027', 'Removal of Cast, Upper Extremities', 2, 4, '80.00', '100.00', '95.00', '115.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(719, 'S028', 'Removal of Cast, Lower Extremities', 2, 4, '100.00', '150.00', '115.00', '175.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(720, 'S029', 'Incision and Drainage', 2, 4, '250.00', '300.00', '290.00', '335.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(721, 'S030', 'Excision of Cyst - Small Cyst', 2, 4, '500.00', '575.00', '850.00', '978.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(722, 'S031', 'Excision of Cyst - Large Cyst', 2, 4, '800.00', '920.00', '1050.00', '1210.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(723, 'S032', 'Emergency Medical Case - Adult', 2, 4, '500.00', '550.00', '600.00', '700.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(724, 'S033', 'Emergency Medical Case - Pediatric', 2, 4, '500.00', '550.00', '600.00', '700.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(725, 'S034', 'Emergency Surgical', 2, 4, '800.00', '880.00', '800.00', '800.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(726, 'S035', 'Death Certificate', 2, 4, '125.00', '137.00', '145.00', '145.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(727, 'S036', 'Referral Letter', 2, 4, '70.00', '77.00', '80.50', '80.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(728, 'S037', 'Medical Report', 2, 4, '175.00', '200.00', '210.00', '210.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(729, 'S038', 'Workers Compensation Report: Interim', 2, 4, '175.00', '200.00', '210.00', '210.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(730, 'S039', 'Workers Compensation Report: Final', 2, 4, '175.00', '200.00', '210.00', '210.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(731, 'S040', 'Medical Certificate', 2, 4, '7.50', '8.50', '8.50', '8.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(732, 'S041', 'IV Insertion Fee', 2, 4, '80.00', '90.00', '100.00', '120.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(733, 'S042', 'Ceftriaxone 1g Vial', 2, 4, '35.00', '38.50', '42.35', '46.60', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(734, 'S043', 'Sterile Water', 2, 4, '5.00', '5.50', '6.05', '6.65', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(735, 'S044', 'Syringe & Needle 10mL', 2, 4, '12.00', '13.50', '14.85', '16.35', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(736, 'S045', 'Skin Test Syringe & Needle 1mL', 2, 4, '6.00', '6.75', '7.45', '8.20', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(737, 'Y001', 'Casting Fee', 2, 5, '350.00', '405.00', '350.00', '405.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(738, 'Y002', 'Plaster of Paris 6', 2, 5, '55.00', '60.50', '55.00', '60.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(739, 'Y003', 'Wadding Sheet', 2, 5, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(740, 'Y004', 'Clean Gloves', 2, 5, '8.00', '9.00', '8.00', '9.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(741, 'Y005', 'Fiber Glass Casting Tape 5', 2, 5, '85.00', '95.00', '85.00', '95.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(742, 'Y006', 'Plaster of Paris 4', 2, 5, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(743, 'Y007', 'Wadding Sheet 4', 2, 5, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(744, 'Y008', 'Fiber Glass 4', 2, 5, '75.00', '85.00', '75.00', '85.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(745, 'Y009', 'Elastic Bandage 6', 2, 5, '40.00', '45.00', '40.00', '45.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(746, 'Y010', 'Elastic Bandage 4', 2, 5, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(747, 'Y011', 'Fiber Glass 5', 2, 5, '85.00', '95.00', '85.00', '95.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(748, 'Y012', 'Fiber Glass 3', 2, 5, '65.00', '75.00', '65.00', '75.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(749, 'Y013', 'Oxygen', 2, 5, '2.00', '2.50', '2.50', '3.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(750, 'Y014', 'IV Cannula', 2, 5, '35.00', '38.50', '40.25', '46.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(751, 'Y015', 'IV Fluid 1 Liter/500 mL', 2, 5, '50.00', '55.00', '57.50', '66.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(752, 'Y016', 'IV Tubing Set (Macroset/Microset)', 2, 5, '35.00', '38.50', '40.25', '46.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(753, 'Y017', 'Crepe Bandange', 2, 5, '25.00', '28.50', '28.75', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(754, 'Y018', 'Heplock', 2, 5, '25.00', '27.50', '28.75', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(755, 'Y019', 'Burette', 2, 5, '65.00', '72.00', '75.00', '86.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(756, 'Y020', 'G20 Drawing Needle', 2, 5, '1.00', '1.25', '1.35', '1.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(757, 'Y021', '3 way stop cock', 2, 5, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(758, 'Y022', 'Alcohol Isopropyl 500ml', 2, 5, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(759, 'Y023', 'Alcohol Isopropyl 75ml', 2, 5, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(760, 'Y024', 'Alcohol Swab', 2, 5, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(761, 'Y025', 'Ankle Support', 2, 5, '100.00', '110.00', '100.00', '110.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(762, 'Y026', 'Arm Sling (Blue/Black)', 2, 5, '40.00', '45.00', '40.00', '45.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(763, 'Y027', 'Asepto Syringe', 2, 5, '38.00', '42.00', '38.00', '42.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(764, 'Y028', 'Baby Diapers / piece', 2, 5, '7.00', '7.75', '7.00', '7.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(765, 'Y029', 'Blood Bag', 2, 5, '65.00', '71.50', '65.00', '71.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(766, 'Y030', 'Blood Coll.Tube 5ml Red Lid', 2, 5, '3.00', '3.50', '3.00', '3.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(767, 'Y031', 'Blood Lancet', 2, 5, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(768, 'Y032', 'Blood Transfusion Set', 2, 5, '55.00', '60.50', '55.00', '60.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(769, 'Y033', 'Burette Set 150ml', 2, 5, '65.00', '72.00', '65.00', '72.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(770, 'Y034', 'Catheter, Foley Balloon, all size', 2, 5, '60.00', '66.00', '60.00', '66.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(771, 'Y035', 'Cervical Collar Support', 2, 5, '100.00', '110.00', '100.00', '110.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(772, 'Y036', 'Clavicular Brace Support', 2, 5, '165.00', '181.50', '165.00', '181.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(773, 'Y037', 'Cotton Wool/Balls, Sterile, per pack 10\'s', 2, 5, '2.50', '2.75', '2.50', '2.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(774, 'Y038', 'Crepe Bandage (Large)', 2, 5, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(775, 'Y039', 'Crepe Bandage (Small) 5cm x 1.5m', 2, 5, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(776, 'Y040', 'Crutches (Large)', 2, 5, '285.00', '315.00', '285.00', '315.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(777, 'Y041', 'Depo-Provera vial', 2, 5, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(778, 'Y042', 'Dressing Tray/Set', 2, 5, '40.00', '45.00', '40.00', '45.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(779, 'Y043', 'Elastic Bandage 2\"', 2, 5, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(780, 'Y044', 'Elastic Bandage 3\"', 2, 5, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(781, 'Y045', 'Elastic Bandage 4\"', 2, 5, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(782, 'Y046', 'Elastic Bandage 6\"', 2, 5, '40.00', '45.00', '40.00', '45.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(783, 'Y047', 'Elastic Gauze', 2, 5, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(784, 'Y048', 'Emergency Kit Large Blue', 2, 5, '330.00', '365.00', '330.00', '365.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(785, 'Y049', 'Emergency Kit Medium blue', 2, 5, '220.00', '245.00', '220.00', '245.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(786, 'Y050', 'Eye Patch', 2, 5, '8.00', '9.00', '8.00', '9.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(787, 'Y051', 'Face Mask', 2, 5, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(788, 'Y052', 'Feeding Tube Fr10', 2, 5, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(789, 'Y053', 'Feeding Tube Fr5', 2, 5, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(790, 'Y054', 'Feeding Tube Fr6', 2, 5, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(791, 'Y055', 'Fiberglass Casting Tape 3\"', 2, 5, '50.00', '55.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(792, 'Y056', 'Fiberglass Casting Tape 4\"', 2, 5, '55.00', '60.50', '55.00', '60.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(793, 'Y057', 'Fiberglass Casting Tape 5\"', 2, 5, '60.00', '66.00', '60.00', '66.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(794, 'Y058', 'Fleet Enema 133ml', 2, 5, '75.00', '85.00', '75.00', '85.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(795, 'Y059', 'Glucose water 50% 50mL', 2, 5, '75.00', '82.50', '75.00', '82.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(796, 'Y060', 'Gloves, Sterile Latex/pair', 2, 5, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(797, 'Y061', 'Gloves, Unsterile Latex/ Pair', 2, 5, '8.00', '9.00', '8.00', '9.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(798, 'Y062', 'Head Cap (Hair Net)', 2, 5, '2.00', '2.25', '2.00', '2.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2);
INSERT INTO `products` (`id`, `code`, `name`, `uom_id`, `category_id`, `amount`, `amount_po`, `after_amount`, `after_amount_po`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `deleted_reason`, `status_id`) VALUES
(799, 'Y063', 'Health Book Adult/Baby', 2, 5, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(800, 'Y064', 'Health Book for Pregnant Women', 2, 5, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(801, 'Y065', 'Heplock', 2, 5, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(802, 'Y066', 'Hot Water Bag', 2, 5, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(803, 'Y067', 'Hydrogen Peroxide 120ml', 2, 5, '25.00', '28.50', '25.00', '28.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(804, 'Y068', 'Ice Bag', 2, 5, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(805, 'Y069', 'Infusion Set/IV giving Set/ IV Tubing Set  Adult', 2, 5, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(806, 'Y070', 'Infusion Set / IV giving Set/ IV Tubing Set Pedia', 2, 5, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(807, 'Y071', 'IV Canullas, all sizes', 2, 5, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(808, 'Y072', 'IV Fluids: all 1 Litre', 2, 5, '50.00', '55.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(809, 'Y073', 'IV Fluids: all 500 ML', 2, 5, '45.00', '49.50', '45.00', '49.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(810, 'Y074', 'Nasal Cannula Adult', 2, 5, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(811, 'Y075', 'Nasal Cannula Infant', 2, 5, '25.00', '28.50', '25.00', '28.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(812, 'Y076', 'Nebulizing Kit', 2, 5, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(813, 'Y077', 'Needles,  All Sizes', 2, 5, '1.00', '1.25', '1.00', '1.25', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(814, 'Y078', 'Pedia Urine Bag', 2, 5, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(815, 'Y079', 'Penrose Drain', 2, 5, '6.00', '6.75', '6.00', '6.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(816, 'Y080', 'Plaster 1 Inch (Micropore)', 2, 5, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(817, 'Y081', 'Plaster 1/2 Inch (Micropore)', 2, 5, '25.00', '28.50', '25.00', '28.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(818, 'Y082', 'Plaster (Leukoplast) / Per Foot', 2, 5, '10.00', '11.00', '10.00', '11.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(819, 'Y083', 'Plaster 3 Inches Cotton', 2, 5, '10.00', '11.00', '10.00', '11.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(820, 'Y084', 'Plaster of Paris (POP) 4\"', 2, 5, '50.00', '55.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(821, 'Y085', 'Plaster of Paris (POP) 6\"', 2, 5, '55.00', '60.50', '55.00', '60.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(822, 'Y086', 'Plaster of Paris (POP) Fiberglass 4\"', 2, 5, '75.00', '85.00', '75.00', '85.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(823, 'Y087', 'Plaster of Paris (POP) Fiberglass 5\"', 2, 5, '85.00', '95.00', '85.00', '95.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(824, 'Y088', 'Plaster of Paris (POP) Fibreglass 3\"', 2, 5, '65.00', '75.00', '65.00', '75.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(825, 'Y089', 'Povidone Iodine (Betadine) 100ml', 2, 5, '40.00', '45.00', '40.00', '45.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(826, 'Y090', 'Povidone Iodine (Betadine) 60ml', 2, 5, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(827, 'Y091', 'Sofratulle/Jelonet/Parafin Gauze', 2, 5, '15.00', '18.50', '15.00', '18.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(828, 'Y092', 'Splint, Adult (Hand Splint)', 2, 5, '40.00', '44.00', '40.00', '44.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(829, 'Y093', 'Splint, Pedia (Hand Splint)', 2, 5, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(830, 'Y094', 'Sterile Burn Pack', 2, 5, '25.00', '28.50', '25.00', '28.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(831, 'Y095', 'Sterile Combine Dressing/ Pack 5\'s', 2, 5, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(832, 'Y096', 'Sterile Gauze Pack  5 pcs', 2, 5, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(833, 'Y097', 'Sterile Laporotomy Pack', 2, 5, '35.00', '38.50', '35.00', '38.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(834, 'Y098', 'Sterile Latex Gloves/pair', 2, 5, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(835, 'Y099', 'Sterile Water 10ml', 2, 5, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(836, 'Y100', 'Steri-Strip', 2, 5, '25.00', '28.50', '25.00', '28.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(837, 'Y101', 'Stockinet per foot', 2, 5, '30.00', '33.00', '30.00', '33.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(838, 'Y102', 'Stomach Tube', 2, 5, '25.00', '27.50', '25.00', '27.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(839, 'Y103', 'Suction Catherer, All Sizes', 2, 5, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(840, 'Y104', 'Surgical Blades, All Sizes', 2, 5, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(841, 'Y105', 'Surgical Cap', 2, 5, '5.00', '5.50', '5.00', '5.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(842, 'Y106', 'Suture Tray', 2, 5, '40.00', '45.00', '40.00', '45.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(843, 'Y107', 'Sutures Chromic, All Sizes', 2, 5, '55.00', '60.50', '55.00', '60.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(844, 'Y108', 'Sutures Ethicon, All Sizes', 2, 5, '55.00', '60.50', '55.00', '60.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(845, 'Y109', 'Sutures Nylon, All Sizes', 2, 5, '55.00', '60.50', '55.00', '60.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(846, 'Y110', 'Sutures Prolene, all Sizes', 2, 5, '65.00', '72.00', '65.00', '72.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(847, 'Y111', 'Sutures Silk (Sizes 1.00 to 4.0)', 2, 5, '50.00', '55.00', '50.00', '55.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(848, 'Y112', 'Sutures Silk 5.0', 2, 5, '55.00', '60.50', '55.00', '60.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(849, 'Y113', 'Sutures Vicryl, All Sizes', 2, 5, '60.00', '66.00', '60.00', '66.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(850, 'Y114', 'Syringe 1ml', 2, 5, '6.00', '6.75', '6.00', '6.75', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(851, 'Y115', 'Syringe 3ml', 2, 5, '8.00', '9.00', '8.00', '9.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(852, 'Y116', 'Syringe 5ml', 2, 5, '8.00', '9.00', '8.00', '9.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(853, 'Y117', 'Syringe 10ml', 2, 5, '12.00', '13.50', '12.00', '13.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(854, 'Y118', 'Syringe (20ml)', 2, 5, '15.00', '16.50', '15.00', '16.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(855, 'Y119', 'Syringe (50ml)', 2, 5, '40.00', '45.00', '40.00', '45.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(856, 'Y120', 'Syringe And Needle 3ml,5ml syr', 2, 5, '8.00', '9.00', '8.00', '9.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(857, 'Y121', 'Thoracic Catheter', 2, 5, '55.00', '60.50', '55.00', '60.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(858, 'Y122', 'Triangular Bandage', 2, 5, '20.00', '22.00', '20.00', '22.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(859, 'Y123', 'Umbilical Catherer', 2, 5, '18.00', '20.00', '18.00', '20.00', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(860, 'Y124', 'Underpads (not covered by insurance)', 2, 5, '25.00', '28.50', '25.00', '28.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(861, 'Y125', 'Urine Bag', 2, 5, '25.00', '28.50', '25.00', '28.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(862, 'Y126', 'Wadding Sheet 4\"', 2, 5, '25.00', '28.50', '25.00', '28.50', NULL, 1, NULL, 0, NULL, 0, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `product_status`
--

DROP TABLE IF EXISTS `product_status`;
CREATE TABLE IF NOT EXISTS `product_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_status`
--

INSERT INTO `product_status` (`id`, `status`) VALUES
(2, 'ACTIVE'),
(1, 'DELETED');

-- --------------------------------------------------------

--
-- Table structure for table `queues`
--

DROP TABLE IF EXISTS `queues`;
CREATE TABLE IF NOT EXISTS `queues` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `queue` varchar(15) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `queue` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `queues`
--

INSERT INTO `queues` (`id`, `queue`) VALUES
(2, 'CALLING'),
(4, 'DONE'),
(3, 'SERVING'),
(1, 'WAITING');

-- --------------------------------------------------------

--
-- Table structure for table `quotes`
--

DROP TABLE IF EXISTS `quotes`;
CREATE TABLE IF NOT EXISTS `quotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quotes` text NOT NULL,
  `author` varchar(100) NOT NULL DEFAULT '',
  `dt_added` varchar(30) NOT NULL,
  `added_by` int(11) NOT NULL DEFAULT '0',
  `status_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quotes`
--

INSERT INTO `quotes` (`id`, `quotes`, `author`, `dt_added`, `added_by`, `status_id`) VALUES
(1, 'Ability is what you\'re capable of doing. \r\nMotivation determines what you do. \r\nAttitude determines how well you do it.', 'Lou Holtz', '2016-08-17 09:45:00 PM', 1, 1),
(2, 'A great attitude becomes a great day, which becomes a great month, which becomes a great year, which becomes a great life.', 'Mandy Hale', '2016-08-17 09:45:00 PM', 1, 1),
(3, 'All our dreams can come true - if we have the courage to pursue them.', 'Walt Disney', '2016-08-17 09:45:00 PM', 1, 1),
(4, 'All things are difficult before they are easy.', 'Thomas Fuller', '2016-08-17 09:45:00 PM', 1, 1),
(5, 'Always be a first-rate version of yourself, instead of a second-rate version of somebody else.', 'Judy Garland', '2016-08-17 09:45:00 PM', 1, 1),
(6, 'Always dream and shoot higher than you know you can do. Don\'t bother just to be better than your contemporaries or predecessors. Try to be better than yourself.', 'William Faulkner', '2016-08-17 09:45:00 PM', 1, 1),
(7, 'Always remember: \r\nYou\'re braver than you believe, \r\nstronger than you seem, \r\nand smarter than you think.', 'A.A. Milne - Christopher Robin to Pooh', '2016-08-17 09:45:00 PM', 1, 1),
(8, 'A man is but of product of his thought. \r\nWhat he thinks he becomes.', 'Mahatma Gandhi', '2016-08-17 09:45:00 PM', 1, 1),
(9, 'And will you succeed? Yes indeed, yes indeed! Ninety-eight and three-quarters percent guaranteed!', 'Dr. Seuss', '2016-08-17 09:45:00 PM', 1, 1),
(10, 'Anyone who has never made a mistake has never tried anything new', 'Albert Einstein', '2016-08-17 09:45:00 PM', 1, 1),
(11, 'A person will only leave their comfort zone once they decide that magic and adventure outweigh complete certainty and security.', 'Doe Zantamata', '2016-08-17 09:45:00 PM', 1, 1),
(12, 'A problem is a chance for you to do your best.', 'Duke Ellington', '2016-08-17 09:45:00 PM', 1, 1),
(13, 'A ship in harbor is safe - but that is not what ships are built for.', 'John A. Shedd', '2016-08-17 09:45:00 PM', 1, 1),
(14, 'Beautiful pictures are developed from negatives in a dark room. So if you see darkness in your life be reassured that a beautiful picture is being prepared.', 'Author Unknown', '2016-08-17 10:00:00 PM', 1, 1),
(15, 'Confidence is contagious. So is lack of confidence.', 'Vince Lombardi', '2016-08-17 10:00:00 PM', 1, 1),
(16, 'Courage is very important. Like a muscle, it is strengthened by use.', 'Ruth Gordon', '2016-08-17 10:00:00 PM', 1, 1),
(17, 'Don\'t be afraid to give your best to what seemingly are small jobs. Every time you conquer one it makes you that much stronger. If you do the little jobs well, the big ones will tend to take care of themselves.', 'Dale Carnegie', '2016-08-17 10:00:00 PM', 0, 1),
(18, 'Don\'t cry because it\'s over. Smile because it happened.', 'Dr. Seuss', '2016-08-17 10:00:00 PM', 0, 1),
(19, 'Don\'t give up. I believe in you all. \r\nA person\'s a person no matter how small.', 'Dr. Seuss', '2016-08-17 10:00:00 PM', 1, 1),
(20, 'They say you only fall in love once, but that can\'t be true. Every time I look at you, I fall in love all over again.', 'Anonymous', '2016-08-17 10:00:00 PM', 1, 1),
(21, 'Every love story is beautiful but ours is my favorite.', 'Anonymous', '2016-08-17 10:00:00 PM', 1, 1),
(22, 'When I saw you, I was afraid to meet you. When I met you, I was afraid to kiss you. When I kissed you, I was afraid to love you. Now that I love you, I\'m afraid to lose you.', 'Rene Yasenek', '2016-08-17 10:00:00 PM', 1, 1),
(23, 'The best way to not get your heart broken, is pretending you don\'t have one.', 'Charlie Sheen', '2016-08-17 10:00:00 PM', 1, 1),
(24, 'A true friend knows your weaknesses but shows you your strengths; feels your fears but fortifies your faith; sees your anxieties but frees your spirit; recognizes your disabilities but emphasizes your possibilities.', 'William Arthur Ward', '2016-08-17 10:00:00 PM', 1, 1),
(25, 'If I could give you one thing in life, I would give you the ability to see yourself through my eyes, only then will you realize how special you are to me.', 'Anonymous', '2016-08-17 10:00:00 PM', 1, 1),
(26, 'Some people are like clouds. When they go away, it\'s a brighter day.', 'Anonymous', '2016-08-17 10:13:00 PM', 1, 1),
(27, 'Don\'t know where your kids are in the house? Turn off the internet and they\'ll show up quickly.', 'Anonymous', '2016-08-17 10:13:00 PM', 1, 1),
(28, 'I changed my password everywhere to \'incorrect.\' That way when I forget it, it always reminds me, \'Your password is incorrect.\'', 'Anonymous', '2016-08-17 10:13:00 PM', 1, 1),
(29, 'When you wake up at 6 in the morning, you close your eyes for 5 minutes and it\'s already 6:45. When you\'re at work and it\'s 2:30, you close your eyes for 5 minutes and it\'s 2:31.', 'Anonymous', '2016-08-17 10:13:00 PM', 1, 1),
(30, 'A best friend is like a four leaf clover, hard to find, lucky to have.', 'Anonymous', '2016-08-17 10:13:00 PM', 1, 1),
(31, 'I know the voices in my head aren\'t real..... but sometimes their ideas are just absolutely awesome!', 'Anonymous', '2016-08-17 10:13:00 PM', 1, 1),
(32, 'I don\'t need a hair stylist, my pillow gives me a new hairstyle every morning. ', 'Anonymous', '2016-08-17 10:13:00 PM', 1, 1),
(33, 'I don\'t need a hair stylist, my pillow gives me a new hairstyle every morning. ', 'Anonymous', '2016-08-17 10:13:00 PM', 1, 1),
(34, 'I miss the days when you could just push someone in the swimming pool without worrying about their cell phone.', 'Anonymous', '2016-08-17 10:13:00 PM', 1, 1),
(35, 'I\'m not running away from hard work, I\'m too lazy to run.', 'Anonymous', '2016-08-17 10:13:00 PM', 0, 1),
(36, 'Dear humans, in case you forgot, I used to be your Internet. Sincerely, The Library.  ', 'Anonymous', '2016-08-17 10:13:00 PM', 1, 1),
(37, 'For you, I would swim across the ocean. LOL, just kidding, there are sharks in there.', 'Anonymous', '2016-08-17 10:13:00 PM', 1, 1),
(38, 'Never wrestle with a pig. You\'ll both get dirty, and the pig likes it.', 'Anonymous', '2016-08-17 10:13:00 PM', 1, 1),
(39, 'Your eyes water when you yawn because you miss your bed and it makes you sad. ', 'Anonymous', '2016-08-17 10:13:00 PM', 1, 1),
(40, 'Reporter: \"Excuse me, may I interview you?\" \r\nMan: \"Yes!\" \r\nReporter: \"Name?\" \r\nMan: \"Abdul Al-Rhazim.\" \r\nReporter: \"Sex?\" \r\nMan: \"Three to five times a week.\" \r\nReporter: \"No no! I mean male or female?\" \r\nMan: \"Yes, male, female... sometimes camel.\" \r\nReporter: \"Holy cow!\" \r\nMan: \"Yes, cow, sheep... animals in general.\" \r\nReporter: \"But isn\'t that hostile?\" \r\nMan: \"Yes, horse style, dog style, any style.\" \r\nReporter: \"Oh dear!\" \r\nMan: \"No, no deer. Deer run too fast. Hard to catch.\"', 'anonymous', '2016-10-20 04:01:00 PM', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `result_sets`
--

DROP TABLE IF EXISTS `result_sets`;
CREATE TABLE IF NOT EXISTS `result_sets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `result_label` varchar(100) NOT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `unit` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_reason` text,
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id` (`product_id`,`result_label`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `result_sets`
--

INSERT INTO `result_sets` (`id`, `product_id`, `result_label`, `reference`, `unit`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `deleted_reason`, `status_id`) VALUES
(1, 2, 'Result', '100', 'mG', NULL, 1, '2023-01-11 08:58:40', 1, NULL, 0, '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `result_set_status`
--

DROP TABLE IF EXISTS `result_set_status`;
CREATE TABLE IF NOT EXISTS `result_set_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `result_set_status`
--

INSERT INTO `result_set_status` (`id`, `status`) VALUES
(2, 'ACTIVE'),
(1, 'DELETED');

-- --------------------------------------------------------

--
-- Table structure for table `setup_common_status`
--

DROP TABLE IF EXISTS `setup_common_status`;
CREATE TABLE IF NOT EXISTS `setup_common_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `setup_common_status`
--

INSERT INTO `setup_common_status` (`id`, `status`) VALUES
(2, 'ACTIVE'),
(1, 'DELETED');

-- --------------------------------------------------------

--
-- Table structure for table `trails`
--

DROP TABLE IF EXISTS `trails`;
CREATE TABLE IF NOT EXISTS `trails` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `action` varchar(50) NOT NULL,
  `transaction_id` bigint(20) NOT NULL,
  `item_id` bigint(20) NOT NULL DEFAULT '0',
  `item_name` varchar(255) NOT NULL,
  `item_status` varchar(50) DEFAULT NULL,
  `remarks` text,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=540 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `trail_db_backup`
--

DROP TABLE IF EXISTS `trail_db_backup`;
CREATE TABLE IF NOT EXISTS `trail_db_backup` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `remarks` varchar(100) DEFAULT NULL,
  `performed_by` int(11) NOT NULL DEFAULT '0',
  `status_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trail_db_restore`
--

DROP TABLE IF EXISTS `trail_db_restore`;
CREATE TABLE IF NOT EXISTS `trail_db_restore` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `remarks` varchar(100) DEFAULT NULL,
  `performed_by` int(11) NOT NULL DEFAULT '0',
  `status_id` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trail_db_status`
--

DROP TABLE IF EXISTS `trail_db_status`;
CREATE TABLE IF NOT EXISTS `trail_db_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL DEFAULT '1',
  `date` date NOT NULL,
  `trans_type_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `charging_type_id` int(11) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `po_no` varchar(50) DEFAULT NULL,
  `client_id` int(11) NOT NULL DEFAULT '0',
  `insurance_id` int(11) NOT NULL DEFAULT '0',
  `insurance_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `insurance_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `doctor_id` int(11) NOT NULL DEFAULT '0',
  `remarks` text,
  `diagnosis` text,
  `subtotal` decimal(20,2) NOT NULL DEFAULT '0.00' COMMENT 'Tax Based Amount',
  `discount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `gst_percent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gst_amount` decimal(20,2) NOT NULL DEFAULT '0.00',
  `total_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(20,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_reason` text,
  `queue_id` int(11) NOT NULL DEFAULT '1',
  `payment_status_id` int(11) NOT NULL DEFAULT '2',
  `insurance_payment_status_id` int(11) NOT NULL DEFAULT '2',
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_status`
--

DROP TABLE IF EXISTS `transaction_status`;
CREATE TABLE IF NOT EXISTS `transaction_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transaction_status`
--

INSERT INTO `transaction_status` (`id`, `status`) VALUES
(1, 'CANCELLED'),
(4, 'COMPLETED'),
(3, 'CONFIRMED'),
(2, 'DRAFT');

-- --------------------------------------------------------

--
-- Table structure for table `trans_types`
--

DROP TABLE IF EXISTS `trans_types`;
CREATE TABLE IF NOT EXISTS `trans_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_type` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_reason` text,
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `trans_type` (`trans_type`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trans_types`
--

INSERT INTO `trans_types` (`id`, `trans_type`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `deleted_reason`, `status_id`) VALUES
(1, 'Pre-Medical', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(2, 'Annual Medical', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(3, 'Laboratory Only', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(4, 'Consultation', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(5, 'Pharmacy', NULL, 1, NULL, 0, NULL, 0, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `trans_type_status`
--

DROP TABLE IF EXISTS `trans_type_status`;
CREATE TABLE IF NOT EXISTS `trans_type_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trans_type_status`
--

INSERT INTO `trans_type_status` (`id`, `status`) VALUES
(2, 'ACTIVE'),
(1, 'DELETED');

-- --------------------------------------------------------

--
-- Table structure for table `uoms`
--

DROP TABLE IF EXISTS `uoms`;
CREATE TABLE IF NOT EXISTS `uoms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `deleted_by` int(11) NOT NULL DEFAULT '0',
  `deleted_reason` text,
  `status_id` int(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `uoms`
--

INSERT INTO `uoms` (`id`, `code`, `name`, `created_at`, `created_by`, `updated_at`, `updated_by`, `deleted_at`, `deleted_by`, `deleted_reason`, `status_id`) VALUES
(1, 'PC', 'Piece/s', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(2, 'LOT', 'Lot/s', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(3, 'LTR', 'Liter/s', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(4, 'KG', 'Kilogram/s', NULL, 1, '2023-01-06 07:20:36', 1, NULL, 0, NULL, 2),
(5, 'Mg', 'Millegram/s', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(6, 'BOX', 'Box/es', NULL, 1, NULL, 0, NULL, 0, NULL, 2),
(7, 'GAL', 'Gallon/s', NULL, 1, NULL, 0, NULL, 0, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `uom_status`
--

DROP TABLE IF EXISTS `uom_status`;
CREATE TABLE IF NOT EXISTS `uom_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `uom_status`
--

INSERT INTO `uom_status` (`id`, `status`) VALUES
(2, 'ACTIVE'),
(1, 'DELETED');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `mname` varchar(50) NOT NULL DEFAULT '',
  `position` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `created_by` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `fname`, `lname`, `mname`, `position`, `email`, `role_id`, `status_id`, `created_by`, `date_created`) VALUES
(1, 'jaypee.hindang', '$2y$12$w7Gc5KyU4VvcAZiKf/IDfOF48kY4Hw1/Ggwnj3wqiOJASbOTuBIKe', 'jaypee', 'hindang', '', NULL, 'eujay_29@yahoo.com.ph', 1, 1, 1, '0000-00-00 00:00:00'),
(2, 'admin', '$2y$12$O.OdxGCB9nNV8TQWtjiw2uejjP/Ro3al1ffGR5qifU/6tqye3mQSG', 'Admin', 'Admin', '', NULL, 'jaypee.hindang@frabellefpg.com', 14, 1, 0, '0000-00-00 00:00:00'),
(3, 'leah.solano', '$2y$12$LKrUsGOHElYyExb.ISKshuCJmp1V4S2VL47B5gtEWf7jrw8tCFWRG', 'Leah', 'Solano', '', NULL, '', 2, 1, 0, '0000-00-00 00:00:00'),
(4, 'lito.solano', '$2y$12$uqW3r3LAqECAqLch.xIPt.yXvaItiWl.UJfFtAFceE78J9FL9L9YK', 'Lito', 'Solano', '', NULL, '', 2, 1, 0, '0000-00-00 00:00:00'),
(5, 'cashier', '$2y$12$NgXlbKARS4v5pjxFAWCIWOkN8c4V2rA/Tyl.ItVq7zL3Mp4NmarAy', 'Cashier', 'Staff', '', NULL, '', 5, 1, 0, '0000-00-00 00:00:00'),
(6, 'triage', '$2y$12$HXWuoP4QACmnUZMSyAQhE.qmAfQifu1vChC.FVvvH2.SoGAyeDBzW', 'Triage', 'Staff', '', NULL, '', 16, 1, 0, '0000-00-00 00:00:00'),
(7, 'accounts.head', '$2y$12$I42rD2hd.XSAWQEVbDdsJO1zmO00BALnaIARy1gp3c5HB25Er9IIe', 'Accounts', 'Head', '', NULL, '', 8, 1, 0, '0000-00-00 00:00:00'),
(8, 'laboratory', '$2y$12$FHH.rmvgWiLcR8ox6SbM4ujx4LB4iRiXwm0AVSdTJGoVky0pI6Z7O', 'Laboratory', 'Staff', '', NULL, '', 6, 1, 0, '0000-00-00 00:00:00'),
(9, 'audiometry', '$2y$12$pKPCT2A1VA..MprVmj/45eHFSdkDOZNKafvNPskIo0WzymEbJcoE6', 'Audiometry', 'Staff', '', NULL, '', 10, 1, 0, '0000-00-00 00:00:00'),
(10, 'pre-employment', '$2y$12$u8kogfEYIjB1CTt1d/uIJexoTQ8zdTs.p6LLLTo6LGfbURQAIUJJ2', 'Pre', 'Employement', '', NULL, '', 7, 1, 0, '0000-00-00 00:00:00'),
(11, 'accounts.staff', '$2y$12$/KIxayWLN52vrX.jOgM2sOGWmJ.AfJot9TtabyccMOGn/LfBYxHoy', 'Accounts', 'Staff', '', NULL, '', 17, 1, 0, '0000-00-00 00:00:00'),
(12, 'reception', '$2y$12$TLCc8VjptN55oSpQ3.7EyebRc4u184C0QucTk/h/l7UO/s2k7N/eC', 'Reception', 'Staff', '', NULL, '', 15, 1, 0, '0000-00-00 00:00:00'),
(13, 'pharmacy', '$2y$12$QG5YL7GBMtI4Ed8qBBbRlOwngGvOpNxMJWPCnakSeeEuB124CRVCi', 'Pharmacy', 'Staff', '', NULL, '', 13, 1, 0, '0000-00-00 00:00:00'),
(14, 'vip', '$2y$12$X5pRx6fqNDwm/3.CIElUF.x7mUX0IOs/Vn/U3pfA8WgJ1ExGjTWky', 'VIP', 'STAFF', '', NULL, '', 2, 1, 0, '0000-00-00 00:00:00'),
(15, 'doctor', '$2y$12$fWM2BmUXgfteMPqFlVZCtuj0vMNAq.3ZXUoOvMBtjTN0ACvWlPIM6', 'Doctor', 'Staff', '', NULL, '', 4, 1, 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_status`
--

DROP TABLE IF EXISTS `user_status`;
CREATE TABLE IF NOT EXISTS `user_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_status`
--

INSERT INTO `user_status` (`id`, `status`) VALUES
(1, 'ACTIVE'),
(2, 'INACTIVE');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
