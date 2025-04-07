-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 29 Mar 2023, 11:39:16
-- Sunucu sürümü: 10.4.24-MariaDB
-- PHP Sürümü: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `rdglobal_portalapp`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `system_mac_infos`
--

CREATE TABLE `system_mac_infos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mac` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_ver` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser_ver` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `system_mac_infos`
--

INSERT INTO `system_mac_infos` (`id`, `ip`, `mac`, `device`, `device_ver`, `browser`, `browser_ver`, `user`, `location`, `type`, `created_at`, `updated_at`) VALUES
(7, '192.168.1.21', 'EC-1F-72-CA-C4-79', 'AndroidOS', '7.0', 'Chrome', '111.0.0.0', '1', '2', '1', '2023-03-29 00:58:19', '2023-03-29 00:58:19'),
(9, '172.16.0.187', '28-39-26-CD-1E-E7', 'Windows', '10.0', 'Chrome', '111.0.0.0', 'NAD', 'IT/1/Sabit', '0', '2023-03-29 05:46:43', '2023-03-29 05:46:43'),
(10, '172.16.2.69', 'BC-54-2F-23-73-B4', 'Windows', '10.0', 'Chrome', '111.0.0.0', 'BEYTULLAH EMİN YALÇIN', 'IT/1.KAT/Taşınabilir', '0', '2023-03-29 06:01:32', '2023-03-29 06:01:32'),
(11, '54.128.0.252', '28-39-26-CD-1E-E7', 'Windows', '10.0', 'Chrome', '113.0.0.0', 'NAD', 'IT/1/Taşınabilir', '0', '2023-03-29 06:43:51', '2023-03-29 06:43:51'),
(12, '172.16.2.101', '28-C2-1F-36-87-1F', 'AndroidOS', '13', 'Chrome', '106.0.5249.126', 'Beytullah emin Yalçın', 'It/kat 1/taşınabilir', '1', '2023-03-29 06:49:07', '2023-03-29 06:49:07'),
(13, '172.16.2.99', 'F4-DB-E3-79-2E-9F', 'iOS', '16_1', 'Chrome', '111.0.5563.72', 'NAD', 'IT', '1', '2023-03-29 06:49:15', '2023-03-29 06:49:15'),
(14, '172.16.2.104', '30-74-67-2C-1E-CD', 'AndroidOS', '12', 'Chrome', '111.0.0.0', 'Nisa Özgen', 'IT', '1', '2023-03-29 06:59:13', '2023-03-29 06:59:13'),
(15, '172.16.0.195', '80-91-33-6A-51-27', 'Windows', '10.0', 'Chrome', '111.0.0.0', 'gizem gürcam', 'danışma/giriş kat/taşınabilir', '0', '2023-03-29 07:06:45', '2023-03-29 07:06:45'),
(16, '172.16.2.82', 'BC-D0-74-98-AA-5D', 'OS X', '10_15_7', 'Chrome', '111.0.0.0', 'Necla Karakas', 'Üst Oda2 :)', '0', '2023-03-29 07:06:54', '2023-03-29 07:06:54'),
(17, '172.16.2.82', 'BC-D0-74-98-AA-5D', 'OS X', '10_15_7', 'Chrome', '111.0.0.0', 'Necla Karakas', 'Üst Oda2 :)', '0', '2023-03-29 07:06:55', '2023-03-29 07:06:55'),
(18, '172.16.0.253', '30-E3-7A-3C-5B-72', 'Windows', '10.0', 'Chrome', '111.0.0.0', 'ŞİFANUR ALTINDAĞ', 'DANIŞMA/GİRİŞ/EVET', '0', '2023-03-29 07:17:47', '2023-03-29 07:17:47'),
(19, '172.16.0.130', 'AC-50-DE-02-36-E3', 'Windows', '10.0', 'Chrome', '111.0.0.0', 'Mehmet Necati İLERİ', '1. kat satınalma odası', '0', '2023-03-29 07:23:03', '2023-03-29 07:23:03'),
(20, '172.16.2.112', '18-19-D6-B6-4C-E5', 'AndroidOS', '11', 'Chrome', '106.0.5249.126', 'Mehmet Necati ILERI', 'Satinalma', '0', '2023-03-29 07:29:24', '2023-03-29 07:29:24'),
(21, '172.16.2.113', '44-90-BB-1A-19-B7', 'iOS', '16_3', 'Safari', '604.1', 'Engin Habiboğlu', 'Özel kalem', '0', '2023-03-29 07:30:46', '2023-03-29 07:30:46'),
(22, '172.16.2.114', '8C-98-6B-5D-24-C2', 'iOS', '16_3', 'Chrome', '111.0.5563.101', 'Engin Habiboğlu', 'Özel kalem', '1', '2023-03-29 07:31:32', '2023-03-29 07:31:32'),
(23, '172.16.2.116', '34-82-C5-7B-55-7C', 'AndroidOS', '10', 'Chrome', '106.0.5249.126', 'Funda Yildiz', 'Satis/ zemin', '0', '2023-03-29 07:35:49', '2023-03-29 07:35:49'),
(24, '172.16.2.117', 'F4-02-28-7E-E3-0F', 'AndroidOS', '13', 'Chrome', '110.0.0.0', 'Enis Macit', '1. Kat / istanbul odası', '1', '2023-03-29 07:40:43', '2023-03-29 07:40:43'),
(25, '172.16.2.122', '8C-98-6B-58-17-AC', 'iOS', '16_3_1', 'Safari', '16.3', 'Necla Karakaş', '2. Kat', '1', '2023-03-29 07:49:44', '2023-03-29 07:49:44'),
(26, '172.16.2.127', 'F8-8F-07-51-BC-FC', 'AndroidOS', '11', 'Chrome', '109.0.0.0', 'Necla Karakaş', '2. Kat', '0', '2023-03-29 07:53:48', '2023-03-29 07:53:48'),
(28, '172.16.2.129', 'AC-E4-B5-8A-F5-AD', 'iOS', '15_7_3', 'Safari', '15.6.4', 'Mustafa sandal', 'Giriş-idarişler-evet', '1', '2023-03-29 08:06:08', '2023-03-29 08:06:08'),
(29, '172.16.2.130', 'B4-1A-1D-56-F5-F2', 'AndroidOS', '10', 'Chrome', '111.0.0.0', 'Mustafa Sandal iş telefonu', 'İdari işler / zemin / taşinabilir', '0', '2023-03-29 08:06:42', '2023-03-29 08:06:42');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `system_mac_infos`
--
ALTER TABLE `system_mac_infos`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `system_mac_infos`
--
ALTER TABLE `system_mac_infos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
