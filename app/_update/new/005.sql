-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 19, 2020 at 10:07 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `itsu_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `contractmasterattachment`
--

CREATE TABLE `contractmasterattachment` (
  `id` int(11) UNSIGNED NOT NULL,
  `contractmast_id` int(11) UNSIGNED NOT NULL,
  `icno_file` blob,
  `icno_mime` varchar(255) DEFAULT NULL,
  `icno_size` int(11) UNSIGNED DEFAULT NULL,
  `income_file` blob,
  `income_mime` varchar(255) DEFAULT NULL,
  `income_size` int(11) UNSIGNED DEFAULT NULL,
  `bankstatement_file` blob,
  `bankstatement_mime` varchar(255) DEFAULT NULL,
  `bankstatement_size` int(11) UNSIGNED DEFAULT NULL,
  `comp_form_file` blob,
  `comp_form_mime` varchar(255) DEFAULT NULL,
  `comp_form_size` int(11) UNSIGNED DEFAULT NULL,
  `comp_icno_file` blob,
  `comp_icno_mime` varchar(255) DEFAULT NULL,
  `comp_icno_size` int(11) UNSIGNED DEFAULT NULL,
  `comp_bankstatement_file` blob,
  `comp_bankstatement_mime` varchar(255) DEFAULT NULL,
  `comp_bankstatement_size` int(11) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contractmasterattachment`
--
ALTER TABLE `contractmasterattachment`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contractmasterattachment`
--
ALTER TABLE `contractmasterattachment`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
