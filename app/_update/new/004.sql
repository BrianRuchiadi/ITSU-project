-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 19, 2020 at 09:54 AM
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
-- Table structure for table `contractmasterdtl`
--

CREATE TABLE `contractmasterdtl` (
  `id` int(11) UNSIGNED NOT NULL,
  `contractmast_id` int(11) UNSIGNED NOT NULL,
  `CND_ItemID` int(11) UNSIGNED NOT NULL,
  `CND_Description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CND_ItemUOMID` int(11) UNSIGNED NOT NULL,
  `CND_ItemTypeID` int(11) UNSIGNED NOT NULL,
  `CND_Qty` int(11) UNSIGNED NOT NULL,
  `CND_UnitPrice` decimal(15,2) NOT NULL DEFAULT '0.00',
  `CND_SubTotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `CND_TaxAmt` decimal(15,2) NOT NULL DEFAULT '0.00',
  `CND_TaxableAmt` decimal(15,2) NOT NULL DEFAULT '0.00',
  `CND_Total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `CND_SerialNo` varchar(255) DEFAULT NULL,
  `CND_ItemSeq` int(11) UNSIGNED NOT NULL,
  `CND_WarehouseID` int(11) UNSIGNED NOT NULL,
  `CND_BinLocationID` int(11) UNSIGNED NOT NULL,
  `cndeliveryorder_id` int(11) UNSIGNED NOT NULL,
  `usr_created` int(11) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usr_updated` int(11) UNSIGNED DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `usr_deleted` int(11) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contractmasterdtl`
--
ALTER TABLE `contractmasterdtl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contractmasterdtl`
--
ALTER TABLE `contractmasterdtl`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
