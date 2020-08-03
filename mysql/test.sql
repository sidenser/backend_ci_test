-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2020 at 06:40 PM
-- Server version: 8.0.18
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `boosterpack`
--

CREATE TABLE `boosterpack` (
  `id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bank` decimal(10,2) NOT NULL DEFAULT '0.00',
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `boosterpack`
--

INSERT INTO `boosterpack` (`id`, `price`, `bank`, `time_created`) VALUES
(1, '5.00', '5.00', '2020-03-30 00:17:28'),
(2, '20.00', '18.00', '2020-03-30 00:17:28'),
(3, '50.00', '0.00', '2020-03-30 00:17:28');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `assign_id` int(10) UNSIGNED NOT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `likes` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `parent_id`, `user_id`, `assign_id`, `text`, `likes`, `time_created`) VALUES
(1, NULL, 1, 1, 'Ну чо ассигн проверим', 8, '2020-03-27 21:39:44'),
(2, NULL, 1, 1, 'Второй коммент', 0, '2020-03-27 21:39:55'),
(3, NULL, 2, 1, 'Второй коммент от второго человека', 0, '2020-03-27 21:40:22'),
(4, 1, 1, 1, 'Фууу!', 0, '2020-08-03 16:59:41'),
(5, 1, 1, 1, 'Фууу!', 0, '2020-08-03 17:17:58'),
(6, 0, 1, 1, 'Фууу!', 0, '2020-08-03 17:18:06');

-- --------------------------------------------------------

--
-- Table structure for table `comment_like`
--

CREATE TABLE `comment_like` (
  `id` int(10) UNSIGNED NOT NULL,
  `comment_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment_like`
--

INSERT INTO `comment_like` (`id`, `comment_id`, `user_id`, `time_created`) VALUES
(1, 1, 1, '2020-08-03 18:03:53'),
(2, 1, 1, '2020-08-03 18:03:55'),
(3, 1, 1, '2020-08-03 18:03:56'),
(4, 1, 1, '2020-08-03 18:03:57'),
(5, 1, 1, '2020-08-03 18:03:57'),
(6, 1, 1, '2020-08-03 18:03:58'),
(7, 1, 1, '2020-08-03 18:03:58'),
(8, 1, 1, '2020-08-03 18:03:58');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `img` varchar(1024) DEFAULT NULL,
  `likes` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `user_id`, `text`, `img`, `likes`, `time_created`) VALUES
(1, 1, 'Тестовый постик 1', '/images/posts/1.png', 2, '2018-08-30 13:31:14'),
(2, 1, 'Печальный пост', '/images/posts/2.png', 0, '2018-10-11 01:33:27');

-- --------------------------------------------------------

--
-- Table structure for table `post_like`
--

CREATE TABLE `post_like` (
  `id` int(10) UNSIGNED NOT NULL,
  `post_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_like`
--

INSERT INTO `post_like` (`id`, `post_id`, `user_id`, `time_created`) VALUES
(1, 1, 1, '2020-08-03 18:01:57'),
(2, 1, 1, '2020-08-03 18:03:30');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `comment` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`id`, `user_id`, `amount`, `comment`, `time_created`) VALUES
(1, 1, '10.00', 'Add money', '2020-08-03 11:53:17'),
(2, 1, '12.00', 'Add money', '2020-08-03 11:58:18'),
(3, 1, '12.00', 'Add money', '2020-08-03 11:58:55'),
(4, 1, '221.00', 'Add money', '2020-08-03 11:59:18'),
(5, 1, '12.00', 'Add money', '2020-08-03 11:59:31'),
(6, 1, '12.00', 'Add money', '2020-08-03 11:59:57'),
(7, 1, '12.00', 'Add money', '2020-08-03 12:01:15'),
(8, 1, '12.00', 'Add money', '2020-08-03 12:01:54'),
(9, 1, '12.00', 'Add money', '2020-08-03 12:02:19'),
(10, 1, '12.00', 'Add money', '2020-08-03 12:05:50'),
(11, 1, '-5.00', 'Buy Boosterpack #1', '2020-08-03 13:38:32'),
(12, 1, '-5.00', 'Buy Boosterpack #1', '2020-08-03 13:41:53'),
(13, 1, '-5.00', 'Buy Boosterpack #1', '2020-08-03 13:43:32'),
(14, 1, '-5.00', 'Buy Boosterpack #1', '2020-08-03 13:49:06'),
(15, 1, '-20.00', 'Buy Boosterpack #2', '2020-08-03 13:49:11'),
(16, 1, '-5.00', 'Buy Boosterpack #1', '2020-08-03 13:50:25'),
(17, 1, '-20.00', 'Buy Boosterpack #2', '2020-08-03 14:11:42'),
(18, 1, '-5.00', 'Buy Boosterpack #1', '2020-08-03 14:13:03');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(60) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `personaname` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `avatarfull` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `rights` tinyint(4) NOT NULL DEFAULT '0',
  `wallet_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `wallet_total_refilled` decimal(10,2) NOT NULL DEFAULT '0.00',
  `wallet_total_withdrawn` decimal(10,2) NOT NULL DEFAULT '0.00',
  `likes` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `time_created` datetime NOT NULL,
  `time_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `personaname`, `avatarfull`, `rights`, `wallet_balance`, `wallet_total_refilled`, `wallet_total_withdrawn`, `likes`, `time_created`) VALUES
(1, 'admin', '123456', 'AdminProGod', 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/96/967871835afdb29f131325125d4395d55386c07a_full.jpg', 0, '247.00', '337.00', '90.00', 0, '2019-07-26 01:53:54'),
(2, 'simpleuser@niceadminmail.pl', '123456', 'simpleuser', 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/86/86a0c845038332896455a566a1f805660a13609b_full.jpg', 0, '0.00', '0.00', '0.00', 0, '2019-07-26 01:53:54');

-- --------------------------------------------------------

--
-- Table structure for table `user_boosterpack`
--

CREATE TABLE `user_boosterpack` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `boosterpack_id` int(10) UNSIGNED NOT NULL,
  `count_like` int(10) UNSIGNED NOT NULL,
  `time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_boosterpack`
--

INSERT INTO `user_boosterpack` (`id`, `user_id`, `boosterpack_id`, `count_like`, `time_created`) VALUES
(1, 1, 1, 4, '2020-08-03 13:41:53'),
(2, 1, 1, 4, '2020-08-03 13:43:32'),
(3, 1, 1, 4, '2020-08-03 13:49:06'),
(4, 1, 2, 15, '2020-08-03 13:49:11'),
(5, 1, 2, 7, '2020-08-03 14:11:42'),
(6, 1, 1, 1, '2020-08-03 14:13:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `boosterpack`
--
ALTER TABLE `boosterpack`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `assign_id` (`assign_id`);

--
-- Indexes for table `comment_like`
--
ALTER TABLE `comment_like`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_like`
--
ALTER TABLE `post_like`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `time_created` (`time_created`),
  ADD KEY `time_updated` (`time_updated`);

--
-- Indexes for table `user_boosterpack`
--
ALTER TABLE `user_boosterpack`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `boosterpack_id` (`boosterpack_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `boosterpack`
--
ALTER TABLE `boosterpack`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `comment_like`
--
ALTER TABLE `comment_like`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `post_like`
--
ALTER TABLE `post_like`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_boosterpack`
--
ALTER TABLE `user_boosterpack`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
