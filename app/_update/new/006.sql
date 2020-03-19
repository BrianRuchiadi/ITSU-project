/*
 Navicat Premium Data Transfer

 Source Server         : ITSU Enchancement
 Source Server Type    : MySQL
 Source Server Version : 100126
 Source Host           : 103.197.59.171:33066
 Source Schema         : itsu_kubikt_dev

 Target Server Type    : MySQL
 Target Server Version : 100126
 File Encoding         : 65001

 Date: 19/03/2020 16:28:09
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for contractmasterlog
-- ----------------------------
DROP TABLE IF EXISTS `contractmasterlog`;
CREATE TABLE `contractmasterlog`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `rcd_grp` bigint(20) NULL DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `trx_type` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `subtrx_type` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `contractmast_id` bigint(20) NULL DEFAULT NULL,
  `branchid` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `CNH_DocNo` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `CNH_CustomerID` bigint(20) NULL DEFAULT NULL,
  `CNH_Note` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `CNH_PostingDate` timestamp(0) NULL DEFAULT NULL,
  `CNH_DocDate` timestamp(0) NULL DEFAULT NULL,
  `CNH_NameRef` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `CNH_ContactRef` varchar(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `CNH_SalesAgent1` bigint(20) NULL DEFAULT NULL,
  `CNH_SalesAgent2` bigint(20) NULL DEFAULT NULL,
  `CNH_TotInstPeriod` int(11) NULL DEFAULT NULL,
  `CNH_Total` decimal(11, 2) NULL DEFAULT NULL,
  `CNH_Tax` decimal(11, 2) NULL DEFAULT NULL,
  `CNH_TaxableAmt` decimal(11, 2) NULL DEFAULT NULL,
  `CNH_NetTotal` decimal(11, 2) NULL DEFAULT NULL,
  `CNH_InstallAddress1` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `CNH_InstallAddress2` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `CNH_InstallAddress3` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `CNH_InstallAddress4` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `CNH_InstallPostcode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `CNH_InstallCity` bigint(20) NULL DEFAULT NULL,
  `CNH_InstallState` bigint(20) NULL DEFAULT NULL,
  `CNH_InstallCountry` bigint(20) NULL DEFAULT NULL,
  `CNH_TNCInd` int(1) NULL DEFAULT NULL,
  `CNH_CTOSInd` int(1) NULL DEFAULT NULL,
  `CNH_SmsTag` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `CNH_EmailVerify` int(1) NULL DEFAULT NULL,
  `CNH_WarehouseID` bigint(20) NULL DEFAULT NULL,
  `CNH_Status` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `CTOS_verify` int(1) NULL DEFAULT NULL,
  `CTOS_Score` int(11) NULL DEFAULT NULL,
  `do_complete_ind` int(1) NULL DEFAULT NULL,
  `CNH_EffectiveDay` int(2) NULL DEFAULT NULL,
  `CNH_StartDate` date NULL DEFAULT NULL,
  `CNH_EndDate` date NULL DEFAULT NULL,
  `CNH_ApproveDate` timestamp(0) NULL DEFAULT NULL,
  `CNH_RejectDate` timestamp(0) NULL DEFAULT NULL,
  `CNH_RejectDesc` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `CNH_CommissionMonth` int(2) NULL DEFAULT NULL,
  `CNH_CommissionStartDate` date NULL DEFAULT NULL,
  `contractmastdtl_id` bigint(20) NULL DEFAULT NULL,
  `CND_ItemID` bigint(20) NULL DEFAULT NULL,
  `CND_Description` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `CND_ItemUOMID` bigint(20) NULL DEFAULT NULL,
  `CND_ItemTypeID` bigint(20) NULL DEFAULT NULL,
  `CND_Qty` int(11) NULL DEFAULT NULL,
  `CND_UnitPrice` decimal(11, 2) NULL DEFAULT NULL,
  `CND_SubTotal` decimal(11, 2) NULL DEFAULT NULL,
  `CND_TaxAmt` decimal(11, 2) NULL DEFAULT NULL,
  `CND_TaxableAmt` decimal(11, 2) NULL DEFAULT NULL,
  `CND_Total` decimal(11, 2) NULL DEFAULT NULL,
  `CND_SerialNo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `CND_ItemSeq` int(11) NULL DEFAULT NULL,
  `CND_WarehouseID` bigint(20) NULL DEFAULT NULL,
  `CND_BinLocationID` bigint(20) NULL DEFAULT NULL,
  `cndeliveryorder_id` bigint(20) NULL DEFAULT NULL,
  `usr_created` bigint(20) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of contractmasterlog
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
