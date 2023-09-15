-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2022 at 02:26 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `etiano`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `blog_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_heading` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_subheading` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blog_details` varchar(2000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_main_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blog_meta_data` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blog_likes` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`blog_id`, `user_id`, `blog_heading`, `blog_subheading`, `blog_details`, `blog_main_image`, `blog_meta_data`, `blog_likes`, `created_at`, `updated_at`) VALUES
(1, '3', 'new blog', NULL, 'new blog add for testing', NULL, 'new blog add for testing', '0', '2022-01-18 08:43:32', '2022-01-18 08:43:32'),
(2, '3', 'new blog', NULL, 'new blog add for testing', NULL, 'new blog add for testing', '0', '2022-01-18 08:43:57', '2022-01-18 08:43:57'),
(3, '3', 'new blog', NULL, 'new blog add for testing', NULL, 'new blog add for testing', '0', '2022-01-18 08:44:51', '2022-01-18 08:44:51'),
(4, '3', 'new blog', NULL, 'new blog add for testing', NULL, 'new blog add for testing', '2', '2022-01-18 08:45:11', '2022-01-18 08:45:11'),
(9, '3', 'new blog add', NULL, 'new blog add for testing', '/assets/blog/3zLPWA05-35-27.jpg', 'new blog add for testing add', '0', '2022-01-19 12:05:27', '2022-01-19 12:05:27');

-- --------------------------------------------------------

--
-- Table structure for table `blog_images`
--

CREATE TABLE `blog_images` (
  `blog_image_id` bigint(20) UNSIGNED NOT NULL,
  `blog_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_images`
--

INSERT INTO `blog_images` (`blog_image_id`, `blog_id`, `blog_image`, `created_at`, `updated_at`) VALUES
(2, '6', '/assets/blog/rU8h6F02-15-45.png', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blog_likes`
--

CREATE TABLE `blog_likes` (
  `blog_likes_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `blog_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_likes`
--

INSERT INTO `blog_likes` (`blog_likes_id`, `user_id`, `blog_id`, `created_at`, `updated_at`) VALUES
(1, '1', '8', '2022-01-19 07:12:56', '2022-01-19 07:12:56'),
(2, '1', '4', '2022-01-19 07:14:01', '2022-01-19 07:14:01'),
(3, '1', '4', '2022-01-19 07:16:43', '2022-01-19 07:16:43'),
(4, '1', '4', '2022-01-19 07:16:55', '2022-01-19 07:16:55');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_01_10_053336_create_restaurant_table', 2),
(6, '2022_01_10_055816_create_restaurant_rating_table', 3),
(7, '2022_01_14_094949_create_blogs_table', 3),
(8, '2022_01_14_095406_create_blog_images_table', 3),
(9, '2022_01_19_110001_create_blog_likes_table', 4),
(10, '2022_01_19_125139_create_products_table', 5),
(11, '2022_01_20_100540_create_product_images_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `restaurant_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_image` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_desciption` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_buying_price` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_selling_price` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_status` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_quantity` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_rating` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_rating_count` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_sell_count` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_type` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_meta_data` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `restaurant_id`, `product_name`, `product_image`, `product_desciption`, `product_buying_price`, `product_selling_price`, `product_status`, `product_quantity`, `product_rating`, `product_rating_count`, `product_sell_count`, `product_type`, `product_meta_data`, `created_at`, `updated_at`) VALUES
(1, '2', 'new product', '/assets/product/8PzpHg10-18-52.jpg', 'testing demo product', NULL, '250', 'active', NULL, NULL, NULL, NULL, NULL, 'new product', '2022-01-20 04:48:52', '2022-01-20 04:48:52');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `product_images_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `p_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `restaurant`
--

CREATE TABLE `restaurant` (
  `restaurant_id` bigint(20) UNSIGNED NOT NULL,
  `restaurant_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restaurant_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restaurant_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restaurant_meta_deta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restaurant_added_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restaurant_rating` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restaurant_rating_count` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `restaurant_ph` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lng` varchar(55) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restaurant`
--

INSERT INTO `restaurant` (`restaurant_id`, `restaurant_name`, `restaurant_address`, `restaurant_image`, `restaurant_meta_deta`, `restaurant_added_by`, `restaurant_rating`, `restaurant_rating_count`, `restaurant_ph`, `lat`, `lng`, `created_at`, `updated_at`) VALUES
(1, 'Restauranr 1 ', 'malda, eb, 732101', 'https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg', 'restaurant 1 , restaurant', '3', '50', '10', NULL, '22.55454889235824,88.30188329729688', '88.30188329729688', NULL, NULL),
(2, 'Restauranr 2', 'malda, eb, 732101', 'https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg', 'restaurant 2, restaurant', '3', '45', '10', NULL, '25.04379632593405,87.9778949819944', '87.9778949819944', NULL, NULL),
(3, 'new restaurant', 'malda', 'https://im1.dineout.co.in/images/uploads/restaurant/sharpen/2/e/d/p29410-15743151555dd62493bdda1.jpg', 'new restaurant', '3', NULL, NULL, '8617063982', '25.04379632593405', '87.9778949819944', NULL, NULL),
(4, 'new restaurant', 'malda', 'https://im1.dineout.co.in/images/uploads/restaurant/sharpen/2/e/d/p29410-15743151555dd62493bdda1.jpg', 'new restaurant', '3', NULL, NULL, '8617063983', '25.04379632593405', '87.9778949819944', NULL, NULL),
(5, 'updated restaurant', 'malda', '/assets/restaurant/nCPCRz09-36-58.png', 'updated restaurant', '3', NULL, NULL, '8617063978', '25.04379632593405', '87.9778949819944', '2022-01-18 04:06:58', '2022-01-18 04:06:58'),
(6, 'new restaurant4', 'malda', 'https://im1.dineout.co.in/images/uploads/restaurant/sharpen/2/e/d/p29410-15743151555dd62493bdda1.jpg', 'new restaurant', '3', NULL, NULL, '8617063985', '22.646604929217204', '88.35165663393038', '2022-01-11 04:00:51', '2022-01-11 04:00:51'),
(7, NULL, NULL, 'https://im1.dineout.co.in/images/uploads/restaurant/sharpen/2/e/d/p29410-15743151555dd62493bdda1.jpg', NULL, '3', NULL, NULL, NULL, '22.646604929217204', '88.35165663393038', '2022-01-18 04:01:22', '2022-01-18 04:01:22'),
(8, 'new restaurant5', 'malda', 'https://im1.dineout.co.in/images/uploads/restaurant/sharpen/2/e/d/p29410-15743151555dd62493bdda1.jpg', 'new restaurant', '3', NULL, NULL, '8617063988', '22.55454889235824', '88.30188329729688', '2022-01-14 05:40:44', '2022-01-14 05:40:44'),
(9, 'new restaurant 6', 'malda', 'assets/restaurant/YCQ0M810-12-52.png', 'restaurant new', '3', NULL, NULL, '8617063971', '22.55454889235824', '88.30188329729688', '2022-01-15 04:42:52', '2022-01-15 04:42:52'),
(10, 'new restaurant 7', 'malda', 'assets/restaurant/ZTTnZU10-24-32.png', 'restaurant new 78', '3', NULL, NULL, '8617063972', '22.55454889235824', '88.30188329729688', '2022-01-15 04:54:32', '2022-01-15 04:54:32'),
(11, 'updated restaurant', 'malda', '/assets/restaurant/WPaRQz09-27-17.png', 'updated restaurant', '3', NULL, NULL, '8617063978', '22.55454889235824', '88.30188329729688', '2022-01-18 03:57:17', '2022-01-18 03:57:17');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_rating`
--

CREATE TABLE `restaurant_rating` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refer_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refarel_id` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `o_auth_id` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fb_id` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(55) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `role`, `refer_id`, `refarel_id`, `o_auth_id`, `fb_id`, `country`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'User', 'user@gmail.com', '8617063982', 'user', '123456789', '12345678', '', '', 'India', '2022-01-06 20:45:04', '$2y$10$HP5W.7TMGZhEgp7gY.jXD.QaJRXEVkwMJl.HB8srhANOxKY63mtwy', NULL, NULL, NULL),
(3, 'Admin', 'admin@gmail.com', '8617063983', 'admin', '123456780', '12345670', '', '', 'India', '2022-01-06 20:45:04', '$2y$10$HP5W.7TMGZhEgp7gY.jXD.QaJRXEVkwMJl.HB8srhANOxKY63mtwy', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`blog_id`);

--
-- Indexes for table `blog_images`
--
ALTER TABLE `blog_images`
  ADD PRIMARY KEY (`blog_image_id`);

--
-- Indexes for table `blog_likes`
--
ALTER TABLE `blog_likes`
  ADD PRIMARY KEY (`blog_likes_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`product_images_id`);

--
-- Indexes for table `restaurant`
--
ALTER TABLE `restaurant`
  ADD PRIMARY KEY (`restaurant_id`);

--
-- Indexes for table `restaurant_rating`
--
ALTER TABLE `restaurant_rating`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_refer_id_unique` (`refer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `blog_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `blog_images`
--
ALTER TABLE `blog_images`
  MODIFY `blog_image_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `blog_likes`
--
ALTER TABLE `blog_likes`
  MODIFY `blog_likes_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `product_images_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restaurant`
--
ALTER TABLE `restaurant`
  MODIFY `restaurant_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `restaurant_rating`
--
ALTER TABLE `restaurant_rating`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
