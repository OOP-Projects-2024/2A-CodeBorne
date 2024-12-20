-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 20, 2024 at 08:44 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `forum_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories_tbl`
--

CREATE TABLE `categories_tbl` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `isdeleted` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories_tbl`
--

INSERT INTO `categories_tbl` (`id`, `name`, `user_id`, `description`, `isdeleted`, `created_at`) VALUES
(402, 'Anime', 12, 'Anime-related posts', 0, '2024-12-20 19:14:22'),
(403, 'Accounting PH', 13, 'Community for Filipino accountants', 1, '2024-12-20 19:14:22'),
(404, 'Boxing Forum', 14, 'Boxing-related posts', 0, '2024-12-20 19:17:05'),
(405, 'Games', 15, 'Video games-related posts', 0, '2024-12-20 19:17:05');

-- --------------------------------------------------------

--
-- Table structure for table `comments_tbl`
--

CREATE TABLE `comments_tbl` (
  `id` int(11) NOT NULL,
  `posts_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `parentcomment_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments_tbl`
--

INSERT INTO `comments_tbl` (`id`, `posts_id`, `user_id`, `parentcomment_id`, `content`, `created_at`, `is_deleted`) VALUES
(202, 314, 5, NULL, 'yes', '2024-12-20 19:37:25', 0),
(203, 314, 7, NULL, 'no', '2024-12-20 19:39:37', 0),
(204, 314, 8, 202, 'why tho?', '2024-12-20 19:39:37', 0),
(250, 308, 10, NULL, 'Amazing suggestions', '2024-12-20 19:42:39', 0),
(251, 308, 13, NULL, 'Not impressive', '2024-12-20 19:42:39', 0),
(253, 313, 9, NULL, 'I put all in strength', '2024-12-20 19:42:39', 0),
(254, 313, 3, NULL, 'Speed is a must', '2024-12-20 19:42:39', 0);

-- --------------------------------------------------------

--
-- Table structure for table `posts_tbl`
--

CREATE TABLE `posts_tbl` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `isdeleted` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts_tbl`
--

INSERT INTO `posts_tbl` (`id`, `user_id`, `category_id`, `title`, `content`, `isdeleted`, `created_at`) VALUES
(302, 2, 402, 'Who Will Win?', 'Kid Goku from Dragon Ball vs Kenshiro from Fist of the North Star', 0, '2024-12-20 19:20:34'),
(303, 2, 402, 'Top 10 Must-Watch Anime for Beginners', 'Discover the perfect anime series to kickstart your journey into the world of Japanese animation.', 0, '2024-12-20 19:20:34'),
(305, 2, 402, 'Hidden Gems: Underrated Anime You Need to See', 'Explore lesser-known masterpieces that deserve a spot on your watchlist', 0, '2024-12-20 19:22:34'),
(306, 3, 402, 'The Ultimate Anime Marathon Guide', 'Plan your next binge-watch session with these highly addictive series.', 0, '2024-12-20 19:22:34'),
(307, 3, 402, 'Upcoming Anime Releases to Watch Out For', 'Stay ahead of the curve with the latest anime titles dropping this season.', 0, '2024-12-20 19:24:11'),
(308, 4, 402, 'Best Anime Movies for a Cozy Night In', 'Unwind with these heartwarming and action-packed anime films.', 0, '2024-12-20 19:24:11'),
(309, 4, 402, 'Epic Battles: Anime Series with the Best Fight Scenes', 'Dive into thrilling anime featuring jaw-dropping action and intense rivalries.', 1, '2024-12-20 19:25:48'),
(310, 7, 402, 'Romantic Anime That Will Tug at Your Heartstrings', 'Fall in love with these beautifully crafted stories of romance and emotion.', 0, '2024-12-20 19:25:48'),
(311, 12, 404, 'Who is the best Heavy Weight Boxer?', 'Muhammad Ali, Mike Tyson, or Larry Holmes?', 0, '2024-12-20 19:31:14'),
(312, 12, 404, 'Who is will win in pea condition?', 'Manny Pacman vs Floyd MayWeather', 1, '2024-12-20 19:31:14'),
(313, 12, 404, 'Customize your boxer', 'Strength, Speed, and Durability', 0, '2024-12-20 19:33:46'),
(314, 14, 403, 'Accounting and AI', 'Do you think accounting will be automized?', 0, '2024-12-20 19:33:46'),
(315, 16, 404, 'Underrated Boxers', 'Larry Holmes and Floyd Patterson', 0, '2024-12-20 19:36:36'),
(316, 16, 404, 'Interesting thought', 'Can a best of the best light weight boxer beat a low class heavy weight boxer', 0, '2024-12-20 19:36:36');

-- --------------------------------------------------------

--
-- Table structure for table `users_tbl`
--

CREATE TABLE `users_tbl` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `token` varchar(10000) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `role` int(11) NOT NULL DEFAULT 0,
  `isdeleted` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_tbl`
--

INSERT INTO `users_tbl` (`id`, `username`, `password`, `token`, `bio`, `role`, `isdeleted`, `created_at`) VALUES
(2, 'James_Lawrence', '$2y$10$9Q73X9g34CQh9ZEhlO4ASu02qafXr0oRb7M0Ooj7lrddBRYzWha22', 'NDcxZjNhNmU0ZTQwM2I1ZmQzM2UwOTQ0YzgzODE3OTRlYWJjOTlkYzlmNTEwZmNmYmIyODUzNDUzYmFiMmVlYQ==', 'Ask me anything about Dragon Ball Z.', 0, 0, '2024-12-20 18:45:46'),
(3, 'Jay_Jay', '$2y$10$r0FUY9/s2nTcwqcIEFh6nuK0J3UASM/9VTVe4hFbXNbx9cAsNiM6q', 'MGNjOTk5NDViY2ZkYThiOTgzOTMyODk1M2NhNDIwOWU3MWM0ZDUzNjZiZmNlZTU5ZTk2ZjllM2RlYzFmMDA2Ng==', 'I love Pokemon and playing League of Legends.', 0, 0, '2024-12-20 18:46:47'),
(4, 'Vincent_Batallones', '$2y$10$PdsNp4etxZ9klXvFBaPFde5FyQ58pLLYTvb0uFP8gnbCpg6YaIxTG', 'ZTIwNTU0YjUwYWIxMWE0M2I1MDlhNWFhYzQyMmNhMzg4Yjc4YzBmYTlhNmZlZmRmMjVkNWFjZDZiZGRiMTU4Yg==', 'I am Batman. Not joking.', 0, 0, '2024-12-20 18:47:22'),
(5, 'Aiko_Heart', '$2y$10$1Ji/9hyJRfrcYt1TKFU6Y.8KKM//XeS.j3M.9BNK4y8PbOAqdlkVe', 'ZmVlZTY4NjA1ODBlZThjNzg2ZmU0NDNmMDlhZjM4MGIzMDE3ZGQ3ZjVhNTFjNDZmYzFhYzU1Nzc1NjJmN2E2YQ==', NULL, 0, 0, '2024-12-20 18:47:51'),
(6, 'John_Rodge', '$2y$10$FxXHvSLDxlJeFn17bXnwGuf5mTNYokCAYKzN/P3UIMiyeppBsfHRe', 'MmE0OWU2ODNlMzVhMTk3ODNhZTk0YTk2YWJmNjgwNjllYWIyOGZjMGUyOTlmYTY2N2M2YTAwZjc1MzFmZDkyYw==', 'I maxed out my leadership skills.', 0, 0, '2024-12-20 18:48:19'),
(7, 'Carl_Nicolas', '$2y$10$Hvad/TCwFOT6nvH5H8V57ODUNYkA6qhek.ep0rMByhsRmUuNCkYCu', 'ZWZjM2QyNmIzYWM2ODRjOGQ0NmZjZDY5MjFlYTAyYmVmN2U3YzhjODk2MjNiMTY4MzQ0ZjAyNGM2YmZlZTNjYw==', 'I am the Protagonist of the world we living in.', 0, 0, '2024-12-20 18:48:50'),
(8, 'Dench_Gregorio', '$2y$10$W0OImmY/z5Uj6rWbcmupUOPSQWye4J7bQkDQv2foqNQp8sxlgatrC', 'NThiNmUzZjliOGFhNzhjNWUxZjg1ODY2ZWYyOTQ1MzVkNjkxYzUzMTJhOTljNGQ0ZGQ5YzkzYzIyYzAyNWI2Mg==', 'GTA V all day.', 0, 0, '2024-12-20 18:49:41'),
(9, 'Moist_Critcal', '$2y$10$WKHqtqM0lkA5WUxxUw5Fc.dptKeuA95AIajbil5tt5Sb2Utev5ELa', 'NmFkMWRjMTdlMjM0MWNmZDI2NjJjZTI4YzEzZmY1MDIyNzhjM2IyMjc0ODA5YjA0MWVjMDNlYzQ2ZjU2MzBiMA==', 'I punched Sneako.', 0, 0, '2024-12-20 18:50:07'),
(10, 'Sailor_Moon', '$2y$10$r8GUEfgfRdeXkvnWOIgZz.PNtqgujK2339dmuHrWtcWtHu2Fj8bn6', 'NmNkNTRjZTVlNDZkMDY3MDIwYjZhNzE4MzVkNjM4ZTM3YWIzNzkzNjY4Y2YwNjFkZjFiNTMzZTQxZTFiYWJmZA==', NULL, 0, 0, '2024-12-20 18:51:08'),
(11, 'MaryAnn_Garza', '$2y$10$W..ZzxgA4MJCll3nsMceG.S87qrueXizy09W.W7ZvG4bqTzEeXJky', 'M2U1YjA1M2MyMzJjNjYwOTVkMjAyNWM5OTc2ZDAwNjZkYTkwNWQzMDYyMWRjMWUyN2Q0YjQ2OWYwYzU4ZDVjOQ==', NULL, 0, 0, '2024-12-20 18:51:38'),
(12, 'Sir_Luy', '$2y$10$XIEO24CYMImTZkUo06pdROqEEOaG8AdghoD63xRblqU5nNXLDHmA.', 'ZjY1OWM0NjYxNjZmYTI0ZDEwZDI0MTcwOWE1NDBkNDdmNTU4NzIyOWIyZmNkMmQ5ZjVjZWVmOTljZDkzMWU2ZA==', 'I am a DSA prof', 1, 0, '2024-12-20 18:57:22'),
(13, 'Sir_Kenneth', '$2y$10$YmKZ6dh1nNfpJsEHLSeHYuuZP0ESqw9ViCTb3p6ZA4LSvnS5IB08C', 'MDVhMmEwYmE3ZmY0Nzk2NDdlZjk3MDdmM2M0NTA3M2U0YjMxZGJjNzk0ODYwYWRmYzBhMGYxZTNkOGExOTA2OQ==', 'I am a Discrete Structure prof', 1, 0, '2024-12-20 18:58:14'),
(14, 'Sir_Paul', '$2y$10$MFeNT4mtPrzM/ZX75jMaDOpN0dfeH4HcCeoUJ3mDsMf/ZKA/N.XUu', 'NGM4OWY5N2FlZDU4MjEwZDFmYmRhNDZlYTE1ZjQ3OWM2YjkzMzliZGU5ZTRkMTY1ZjNiNTJhN2RkYTcwZDg2YQ==', 'I am an Operating System prof', 1, 0, '2024-12-20 18:59:03'),
(15, 'Mr.Forumemployee', '$2y$10$Hf21UXoYv4Qae0/bScgYVev6AjafewIrf5jL6PVjDVKjjfUKCU5dy', 'MGEyYTcwMjdiMDRkMmNlMmFkMTNlMTg0MzYwZmY1ZDUxODk5OTNlM2U1NTZjMjcxM2NiZWNjYzgyNTY3ZjkwYw==', 'I am monitoring all of you', 2, 0, '2024-12-20 19:01:45'),
(16, 'Mr.Forumemployee2', '$2y$10$dDxYbu5glrRxZL6DsarE1Ojn21lkmnzoQ7N0GDEXTy4rNhAQgF/uS', 'NDk2YmQ1OGI2NDNhZDk2NDY4YTkwY2U0YTQwYmI2NmQwMDg2ZmE5ODhiOTYwMWY4NzRlOWZiNjVhMWU2MGUxYg==', 'Follow the Forum guidlines', 2, 0, '2024-12-20 19:02:27'),
(17, 'Bad_Guy', '$2y$10$SakRVs0Ni.5jKoSYsyu3o.7PWAWJ8BQMBPu4ukb/hVjdrN.XxkQP.', NULL, 'I have my own rules', 3, 0, '2024-12-20 19:03:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories_tbl`
--
ALTER TABLE `categories_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `comments_tbl`
--
ALTER TABLE `comments_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_id` (`posts_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parentcomment_id` (`parentcomment_id`);

--
-- Indexes for table `posts_tbl`
--
ALTER TABLE `posts_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category _id` (`category_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users_tbl`
--
ALTER TABLE `users_tbl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories_tbl`
--
ALTER TABLE `categories_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1236;

--
-- AUTO_INCREMENT for table `comments_tbl`
--
ALTER TABLE `comments_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=255;

--
-- AUTO_INCREMENT for table `posts_tbl`
--
ALTER TABLE `posts_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=411;

--
-- AUTO_INCREMENT for table `users_tbl`
--
ALTER TABLE `users_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories_tbl`
--
ALTER TABLE `categories_tbl`
  ADD CONSTRAINT `categories_tbl_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users_tbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comments_tbl`
--
ALTER TABLE `comments_tbl`
  ADD CONSTRAINT `comments_tbl_ibfk_1` FOREIGN KEY (`posts_id`) REFERENCES `posts_tbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_tbl_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users_tbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_tbl_ibfk_3` FOREIGN KEY (`parentcomment_id`) REFERENCES `comments_tbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts_tbl`
--
ALTER TABLE `posts_tbl`
  ADD CONSTRAINT `posts_tbl_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories_tbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_tbl_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users_tbl` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
