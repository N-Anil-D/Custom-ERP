<?php

namespace App\Console\Commands\TestOrOld;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\{DB, Log};

class ErpTestBuild extends Command
{
    protected $signature    = 'testorold:ErpTestBuild';
    protected $description  = 'Erp hazırlık sürecinde db de test için veri hazırlar | for nad';

    public function handle()
    {
        Log::channel('console')->info($this->signature.' is start');
        $this->createProductsView();
        $this->createNotificationsView();
        $this->insertWarehouses();
        $this->insertUnits();
        // $this->insertItems();
        // $this->insertItemWarehouse();
        echo 'success';
        Log::channel('console')->info($this->signature.' is end');
    }

    public function createProductsView()
    {
        DB::select('DROP VIEW products');
        DB::select('
                create view rdglobal_portalapp.products as 

                SELECT 
                users.id as user_id,
                erp_warehouses.id as warehouses_id,
                erp_warehouses.name as erp_warehouses_name,
                erp_items_warehouses.item_id as erp_items_warehouses_item_id, 
                erp_items_warehouses.amount as erp_items_warehouses_amount,
                erp_items.id as erp_items_id, 
                erp_items.code as erp_items_code,
                erp_items.name as erp_items_name, 
                erp_items.unit_id as erp_items_unit_id, 
                erp_units.content as erp_units_content,
                erp_items.type as erp_items_type, 
                erp_items.content as erp_items_content
                
                FROM 
                rdglobal_portalapp.erp_warehouses as erp_warehouses left join 
                rdglobal_portalapp.erp_items_warehouses as erp_items_warehouses on erp_items_warehouses.warehouse_id = erp_warehouses.id left join
                rdglobal_portalapp.erp_items as erp_items on erp_items.id = erp_items_warehouses.item_id left join
                rdglobal_portalapp.erp_users_warehouses as erp_users_warehouses on erp_users_warehouses.warehouse_id = erp_warehouses.id left join
                rdglobal_portalapp.users as users on users.id = erp_users_warehouses.user_id inner join
                rdglobal_portalapp.erp_units as erp_units on erp_units.id = erp_items.unit_id
                
                where 
                erp_warehouses.deleted_at is null and 
                erp_items_warehouses.deleted_at is null and 
                erp_items.deleted_at is null and
                erp_users_warehouses.deleted_at is null and
                erp_units.deleted_at is null and
                erp_items.id is not null and
                users.id is not null
                
                order by erp_items.id
        ');
    }

    public function createNotificationsView()
    {
        DB::select('DROP VIEW notifications');
        DB::select('
            create view rdglobal_portalapp.notifications as 

            SELECT 
            users.id as user_id,
            erp_approvals.id as erp_approvals_id,
            erp_approvals.item_id as erp_approvals_item_id, 
            erp_approvals.content as erp_approvals_content, 
            erp_approvals.file as erp_approvals_file,
            erp_approvals.type as erp_approvals_type,
            erp_approvals.status as erp_approvals_status,
            erp_approvals.notify as erp_approvals_notify,
            erp_approvals.sender_user as erp_approvals_sender_user,
            erp_approvals.dwindling_warehouse_id as erp_approvals_dwindling_warehouse_id,
            erp_approvals.increased_warehouse_id as erp_approvals_increased_warehouse_id,
            erp_approvals.amount as erp_approvals_amount,
            erp_approvals.created_at as erp_approvals_created_at
            
            FROM 
            rdglobal_portalapp.users as users inner join 
            rdglobal_portalapp.erp_users_warehouses as erp_users_warehouses on users.id = erp_users_warehouses.user_id inner join 
            rdglobal_portalapp.erp_approvals as erp_approvals on erp_approvals.increased_warehouse_id = erp_users_warehouses.warehouse_id inner join
            rdglobal_portalapp.erp_warehouses as erp_warehouses on erp_warehouses.id = erp_approvals.increased_warehouse_id 
            
            where erp_users_warehouses.deleted_at is null and
            erp_approvals.deleted_at is null and 
            erp_warehouses.deleted_at is null and 
            users.id is not null
        ');
    }

    public function insertWarehouses()
    {
        DB::select('truncate table erp_warehouses');
        DB::select("
            INSERT INTO `erp_warehouses` (`id`, `code`, `name`, `content`, `created_at`, `updated_at`, `deleted_at`) VALUES
            (1, 'INVLOG00Z000000', 'INVAlogistic', 'Merkez depo | INVAlogistic', '2023-02-24 16:22:50', '2023-02-24 16:25:31', NULL),
            (2, 'INVFAC00ZCLN001', 'CLEAN ROOM 1', 'ZEMİN KAT', '2023-02-24 16:23:51', '2023-03-08 12:44:17', NULL),
            (3, 'INVFAC00ZCLN002', 'CLEAN ROOM 2', 'ZEMİN KAT', '2023-02-24 16:24:23', '2023-03-08 11:45:04', NULL),
            (4, 'INVFAC00ZCLN003', 'CLEAN ROOM 3', 'ZEMİN KAT', '2023-02-24 16:24:37', '2023-03-08 11:44:59', NULL),
            (5, 'INVFAC00ZCLN004', 'CLEAN ROOM 4', 'ZEMİN KAT', '2023-02-24 16:24:50', '2023-03-08 11:44:45', NULL),
            (6, 'INVFAC00ZCLN005', 'CLEAN ROOM 5', 'ZEMİN KAT', '2023-02-24 16:25:03', '2023-03-08 11:44:36', NULL),
            (7, 'INVFAC00ZCLN006', 'CLEAN ROOM 6', 'ZEMİN KAT', '2023-02-24 16:27:27', '2023-03-08 11:44:31', NULL),
            (8, 'INVFAC00ZCLN007', 'CLEAN ROOM 7', 'ZEMİN KAT', '2023-02-24 16:27:38', '2023-03-08 11:44:27', NULL),
            (9, 'INVFAC00ZLAB001', 'LABORATORY', 'ZEMİN KAT', '2023-02-24 16:27:50', '2023-03-08 11:44:22', NULL),
            (10, 'INVFAC00ZPRD001', 'PRODUCT LABROTORY', 'ZEMİN KAT', '2023-02-24 16:28:04', '2023-03-08 11:44:18', NULL),
            (11, 'INVFAC00ZSHM001', 'SLIDING HEAT MACHİNE AREA', 'ZEMİN KAT', '2023-03-08 11:43:13', '2023-03-08 11:44:12', NULL),
            (12, 'INVFAC00ZINJ001', 'INJECTION AREA', 'ZEMİN KAT', '2023-03-08 11:43:47', '2023-03-08 11:43:47', NULL),
            (13, 'INVFAC00ZUMA001', 'UNIVERSAL MACHİNE AREA', 'ZEMİN KAT', '2023-03-08 11:45:48', '2023-03-08 11:45:48', NULL),
            (14, 'INVFAC00ZEMA001', 'ELECTRICAL DISCHARGE MACHINING AREA', 'ZEMİN KAT', '2023-03-08 11:46:51', '2023-03-08 11:46:51', NULL),
            (15, 'INVFAC00Z5AC001', '5 AXIS CNC AREA', 'ZEMİN KAT', '2023-03-08 11:48:39', '2023-03-08 11:48:39', NULL),
            (16, 'INVFAC00Z3AC001', '3 AXIS CNC AREA', 'ZEMİN KAT', '2023-03-08 11:50:02', '2023-03-08 11:50:02', NULL),
            (17, 'INVFAC00ZCPT001', 'CHEMICAL POLISHINGHEAT TREATMENT LABORATORY', 'ZEMİN KAT', '2023-03-08 11:51:24', '2023-03-08 11:51:24', NULL),
            (18, 'INVFAC00ZCDA001', 'CARD DEVICES AREA', 'ZEMİN KAT', '2023-03-08 11:51:51', '2023-03-08 11:51:51', NULL),
            (19, 'INVFAC00ZLCA001', 'LASER CUTTING AREA', 'ZEMİN KAT', '2023-03-08 11:52:16', '2023-03-08 11:52:16', NULL),
            (20, 'INVFAC00ZRDA001', 'ROTATIONAL DEVİCES AREA', 'ZEMİN KAT', '2023-03-08 11:52:36', '2023-03-08 11:52:36', NULL),
            (21, 'INVFAC00ZMPA001', 'MICROCOIL PRODUCTION AREA', 'ZEMİN KAT', '2023-03-08 11:53:06', '2023-03-08 11:53:06', NULL),
            (22, 'INVFAC00ZWBA001', 'WIRE & BRAID PROCESSING AREA', 'ZEMİN KAT', '2023-03-08 11:54:52', '2023-03-08 11:54:52', NULL),
            (23, 'INVFAC00ZSLB001', 'STERILIZATION & LABELING & BOXING AREA', 'ZEMİN KAT', '2023-03-08 11:56:14', '2023-03-08 11:56:14', NULL),
            (24, 'INVFAC001PBK001', 'PTCA BALON KRIMP - GMP BSL 2-3 CLEAN ROOM', '1. KAT', '2023-03-08 12:00:23', '2023-03-08 12:00:23', NULL),
            (25, 'INVFAC001VNB001', 'VENABLOCK - GMP BSL 2-3 CLEAN ROOM', '1. KAT', '2023-03-08 12:01:30', '2023-03-08 12:02:14', NULL),
            (26, 'INVFAC001PKT001', 'PAKETLEME', '1. KAT', '2023-03-08 12:02:52', '2023-03-08 12:02:52', NULL),
            (27, 'INVFAC001YHD001', 'YARI MAMUL-HAMMADDE DEPO', '1. KAT', '2023-03-08 12:03:56', '2023-03-08 12:05:17', NULL),
            (28, 'INVFAC001INJ001', 'BİTMİŞ ÜRÜN DEPOSU', '1. KAT', '2023-03-08 12:05:37', '2023-03-08 12:05:37', NULL),
            (29, 'INVFAC001MAR001', 'MEKANİK ARGE', '1. KAT', '2023-03-08 12:06:21', '2023-03-08 12:06:21', NULL),
            (30, 'INVFAC001EAR001', 'ELEKTRONİK ARGE', '1. KAT', '2023-03-08 12:07:06', '2023-03-08 12:07:06', NULL),
            (31, 'INVFAC001ARG001', 'STENT İLAÇLAMA', '1. KAT', '2023-03-08 12:07:06', '2023-03-08 12:07:06', NULL)
        ");
    }

    public function insertUnits()
    {
        DB::select('truncate table erp_units');
        DB::select("
            INSERT INTO `erp_units` (`id`, `code`, `content`, `created_at`, `updated_at`, `deleted_at`) VALUES
            (1, 'AD', 'ADET', '2023-02-24 20:00:44', '2023-02-24 20:00:44', NULL),
            (2, 'GR', 'GRAM', '2023-02-24 20:00:57', '2023-02-24 20:00:57', NULL),
            (3, 'KG', 'KİLOGRAM', '2023-02-24 20:01:09', '2023-02-24 20:01:09', NULL),
            (4, 'LT', 'LİTRE', '2023-02-24 20:01:21', '2023-02-24 20:01:21', NULL),
            (5, 'MT', 'METRE', '2023-02-24 20:01:33', '2023-02-24 20:01:33', NULL),
            (6, 'MG', 'MİLİGRAM', '2023-02-24 20:01:33', '2023-02-24 20:01:33', NULL),
            (7, 'ST', 'SET', '2023-02-24 20:01:33', '2023-02-24 20:01:33', NULL)
        ");
    }

    public function insertItems()
    {
        DB::select('truncate table erp_items');
        DB::select("
            INSERT INTO `erp_items` (`id`, `unit_id`, `code`, `name`, `content`, `type`, `created_at`, `updated_at`, `deleted_at`) VALUES
            (1, 1, 'HM.MET.01091', 'SELDINGER İĞNE 18G 7 cm', 'SELDINGER İĞNE 18G 7 cm', 0, '2023-02-24 20:03:22', '2023-02-24 20:03:22', NULL),
            (2, 1, 'HM.PK.00601', 'VİAL ŞİŞE KAPAĞI (1.5 ml)', 'VİAL ŞİŞE KAPAĞI (1.5 ml)', 0, '2023-02-24 20:03:53', '2023-02-24 20:03:53', NULL),
            (3, 1, 'HM.PK.01160', 'RFID ETİKET', 'RFID ETİKET', 0, '2023-02-24 20:04:25', '2023-02-24 20:04:25', NULL),
            (4, 1, 'HM.PK.01278', 'STERİLİZASYON ZARFI 20X30 CM', 'STERİLİZASYON ZARFI 20X30 CM', 0, '2023-02-24 20:04:54', '2023-02-24 20:04:54', NULL),
            (5, 1, 'HM.PLS.01283', 'BD ENJECTÖR 3ML', 'BD ENJECTÖR 3ML', 0, '2023-02-24 20:05:41', '2023-02-24 20:05:41', NULL),
            (6, 2, 'HM.MET.00452', 'EZİLMİŞ TEL SİZE 0,475MM', 'EZİLMİŞ TEL SİZE 0,475MM', 0, '2023-02-24 20:07:19', '2023-02-24 20:07:19', NULL),
            (7, 2, 'HM.KİM.00183', 'SODYUM KLORÜR', 'SODYUM KLORÜR', 0, '2023-02-24 20:08:06', '2023-02-24 20:08:06', NULL),
            (8, 2, 'HM.KİM.00193', 'SODYUM DİHİDROJEN FOSFAT', 'SODYUM DİHİDROJEN FOSFAT', 0, '2023-02-24 20:08:30', '2023-02-24 20:08:30', NULL),
            (9, 2, 'HM.KİM.00208', 'SODYUM HYALÜRONİK ASİT', 'SODYUM HYALÜRONİK ASİT', 0, '2023-02-24 20:09:11', '2023-02-24 20:09:11', NULL),
            (10, 2, 'HM.MET.00336', '304V .1524 SPRİNG BRİGHT', '304V .1524 SPRİNG BRİGHT', 0, '2023-02-24 20:09:48', '2023-02-24 20:09:48', NULL),
            (11, 3, 'HM.PK.00592', 'ÜÇ KENAR KİLİTLİ TORBA (BALON ALÜMİNYUM FOLYO) 24X34', 'ÜÇ KENAR KİLİTLİ TORBA (BALON ALÜMİNYUM FOLYO) 24X34', 0, '2023-02-24 20:10:54', '2023-02-24 20:10:54', NULL),
            (12, 3, 'HM.GRN.00065', 'İNFİNO POLİKARBONAT PC COPOLYMER NATURAL HAMMADDE', 'İNFİNO POLİKARBONAT PC COPOLYMER NATURAL HAMMADDE', 0, '2023-02-24 20:11:27', '2023-02-24 20:11:27', NULL),
            (13, 3, 'HM.KİM.00147', 'TB100 PİCKLİNG A TİTANYUM STRİPPER', 'TB100 PİCKLİNG A TİTANYUM STRİPPER', 0, '2023-02-24 20:11:58', '2023-02-24 20:11:58', NULL),
            (14, 3, 'HM.KİM.00154', 'E2 NİTİNOL ELEKTROPOLİSAJ ELEKTROLİTİ', 'E2 NİTİNOL ELEKTROPOLİSAJ ELEKTROLİTİ', 0, '2023-02-24 20:12:28', '2023-02-24 20:12:28', NULL),
            (15, 3, 'HM.KİM.00209', 'HY-B SK PİCKLİNG KONSANTRE', 'HY-B SK PİCKLİNG KONSANTRE', 0, '2023-02-24 20:13:00', '2023-02-24 20:13:00', NULL),
            (16, 4, 'HM.KİM.00181', 'LOCTİTE AA3922 YAPIŞTIRICI', 'LOCTİTE AA3922 YAPIŞTIRICI', 0, '2023-02-24 20:14:00', '2023-02-24 20:14:00', NULL),
            (17, 5, 'HM.07383', 'PTFE TEFLON BORU 4F ID:1,00mm OD:1,35mm', 'PTFE TEFLON BORU 4F ID:1,00mm OD:1,35mm', 0, '2023-02-24 20:14:49', '2023-02-24 20:14:49', NULL),
            (18, 5, 'HM.ALET.01361', '0.07 mm nitinol fort wayne', '0.07 mm nitinol fort wayne', 0, '2023-02-24 20:15:21', '2023-02-24 20:15:21', NULL),
            (19, 5, 'HM.ELK.07361', '0.22 mm ELEKTİRİK KABLOSU (SİYAH)', '0.22 mm ELEKTİRİK KABLOSU (SİYAH)', 0, '2023-02-24 20:15:56', '2023-02-24 20:15:56', NULL),
            (20, 5, 'HM.ELK.00794', 'DARALAN MAKARON HØ13/6,5MM SIYAH', 'DARALAN MAKARON HØ13/6,5MM SIYAH', 0, '2023-02-24 20:17:31', '2023-02-24 20:17:31', NULL),
            (21, 5, 'HM.07388', 'PTFE TEFLON BORU 9F ID:2,70mm OD:3,00mm', 'PTFE TEFLON BORU 9F ID:2,70mm OD:3,00mm', 0, '2023-02-24 20:18:13', '2023-02-24 20:18:13', NULL),
            (22, 1, '8683498155180', 'Atlas CoCr İlaçlı Koroner Stent Sistemi 2.0 x 8 mm', 'Atlas CoCr İlaçlı Koroner Stent Sistemi 2.0 x 8 mm', 2, '2023-02-24 20:21:35', '2023-02-24 20:21:35', NULL),
            (23, 1, '8683498158280', 'Temren Aterektomi Sistemi I 5F, 90 cm', 'Temren Aterektomi Sistemi I 5F, 90 cm', 2, '2023-02-24 20:22:05', '2023-02-24 20:22:05', NULL),
            (24, 1, '8681410061342', 'Mantis Trombektomi Sistemi I 7F, 90cm', 'Mantis Trombektomi Sistemi I 7F, 90cm', 2, '2023-02-24 20:22:27', '2023-02-24 20:22:27', NULL),
            (25, 1, '8681410066637', 'Angiocath Kılavuz Kateter, 4F, Örgülü, Hidrofilik, Sağ kıvrık, 90 cm', 'Angiocath Kılavuz Kateter, 4F, Örgülü, Hidrofilik, Sağ kıvrık, 90 cm', 2, '2023-02-24 20:23:12', '2023-02-24 20:23:12', NULL),
            (26, 1, '8683498158303', 'Dolphin Destek Kateteri, 4F, 90 cm', 'Dolphin Destek Kateteri, 4F, 90 cm', 2, '2023-02-24 17:23:39', '2023-02-24 17:23:39', NULL),
            (27, 6, '0001-TEST', 'PAKLİTAKSEL', 'PAKLİTAKSEL', 1, '2023-02-27 15:35:57', '2023-02-27 15:35:57', NULL),
            (28, 2, '0002-TEST', 'GRİLAMİD HAMMADDE', 'GRİLAMİD HAMMADDE', 1, '2023-02-27 15:36:29', '2023-02-27 15:36:29', NULL),
            (29, 1, '0003-TEST', 'PTIR RİNG', 'PTIR RİNG', 1, '2023-02-27 15:36:58', '2023-02-27 15:36:58', NULL)
        ");
    }

    public function insertItemWarehouse()
    {
        DB::select('truncate table erp_items_warehouses');
        DB::select("
            INSERT INTO `erp_items_warehouses` (`item_id`, `warehouse_id`, `amount`, `created_at`, `updated_at`)  
            SELECT i.id, w.id, 10, now(), now() FROM erp_warehouses as w, erp_items as i
        ");
    }
}
