-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 19, 2020 at 09:39 AM
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
-- Table structure for table `contractmaster`
--

CREATE TABLE `contractmaster` (
  `id` int(11) UNSIGNED NOT NULL,
  `branchid` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CNH_DocNo` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CNH_CustomerID` int(11) UNSIGNED NOT NULL,
  `CNH_Note` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CNH_PostingDate` timestamp NULL DEFAULT NULL,
  `CNH_DocDate` timestamp NULL DEFAULT NULL,
  `CNH_NameRef` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CNH_ContactRef` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CNH_SalesAgent1` int(11) UNSIGNED NOT NULL,
  `CNH_SalesAgent2` int(11) UNSIGNED DEFAULT NULL,
  `CNH_TotInstPeriod` int(11) UNSIGNED NOT NULL,
  `CNH_Total` decimal(15,2) DEFAULT NULL,
  `CNH_Tax` decimal(15,2) DEFAULT NULL,
  `CNH_TaxableAmt` decimal(15,2) DEFAULT NULL,
  `CNH_NetTotal` decimal(15,2) DEFAULT NULL,
  `CNH_InstallAddress1` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `CNH_InstallAddress2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CNH_InstallAddress3` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CNH_InstallAddress4` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CNH_InstallPostCode` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `CNH_InstallCity` int(11) UNSIGNED NOT NULL,
  `CNH_InstallState` int(11) UNSIGNED NOT NULL,
  `CNH_InstallCountry` int(11) UNSIGNED NOT NULL,
  `CNH_TNCInd` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `CNH_CTOSInd` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `CNH_SmsTag` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CNH_EmailVerify` tinyint(1) DEFAULT NULL,
  `CNH_WarehouseID` int(11) UNSIGNED NOT NULL,
  `CNH_Status` varchar(50) DEFAULT NULL,
  `CTOS_verify` tinyint(1) NOT NULL DEFAULT '1',
  `CTOS_Score` int(11) DEFAULT NULL,
  `do_complete_ind` tinyint(1) NOT NULL DEFAULT '1',
  `CNH_EffectiveDay` int(100) DEFAULT NULL,
  `CNH_StartDate` date DEFAULT NULL,
  `CNH_EndDate` date DEFAULT NULL,
  `CNH_ApproveDate` timestamp NULL DEFAULT NULL,
  `CNH_RejectDate` timestamp NULL DEFAULT NULL,
  `CNH_RejectDesc` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CNH_CommissionMonth` int(10) UNSIGNED DEFAULT NULL,
  `CNH_CommissionStartDate` date DEFAULT NULL,
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
-- Indexes for table `contractmaster`
--
ALTER TABLE `contractmaster`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contractmaster`
--
ALTER TABLE `contractmaster`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;
