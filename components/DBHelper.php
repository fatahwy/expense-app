<?php

namespace app\components;

use app\models\AuthItem;
use app\models\AuthItemChild;
use app\models\mdmsoft\Menu;
use app\models\MstBranch;
use app\models\TrsProductStock;
use Yii;
use yii\db\Exception;
use yii\db\Expression;
use yii\db\Query;

class DBHelper
{

    public static function doLogin($user, $remember = false)
    {
        self::doLoginUpdate($user);
        $id = Yii::$app->user->login($user, $remember ? 3600 * 24 * 30 : 1440);
        return $id;
    }

    public static function doLoginUpdate($user)
    {
        $user->lastlogin = self::now();
        $user->passwordrepeat = $user->password;
        return $user->save();
    }

    public static function nextID($id, $table, $id_branch, $isDaily = true)
    {
        $c = $isDaily ? date('Ymd') : date('Ym');
        $sql = "SELECT COALESCE(MAX(" . $id . "),0) id FROM $table "
            . "WHERE $id LIKE '$c%' AND delete_time IS NULL AND id_branch = $id_branch";
        $retval = Yii::$app->getDb()->createCommand($sql)->queryOne();

        $res = $retval['id'] == 0 ? (int) (date('Ymd') . "000") : ($retval['id'] + 1);
        if ($isDaily) {
            return $res;
        }

        return date('Ymd') . substr($res, 8);
    }

    public static function nextIDSale($id, $table, $id_branch, $isDaily = true)
    {
        $c = $isDaily ? date('Ymd') : date('Ym');
        $sql = "SELECT COALESCE(MAX(" . $id . "),0) id FROM $table "
            . "WHERE $id LIKE '$c%' AND delete_time IS NULL AND id_branch = $id_branch AND status in (1,2) AND char_length($id) = 11";
        $retval = Yii::$app->getDb()->createCommand($sql)->queryOne();

        $res = $retval['id'] == 0 ? (int) (date('Ymd') . "000") : ($retval['id'] + 1);
        if ($isDaily) {
            return $res;
        }

        return date('Ymd') . substr($res, 8);
    }

    public static function today()
    {
        return date('Y-m-d');
    }

    public static function now()
    {
        return date('Y-m-d H:i:s');
    }

    public static function toSqlDate($date, $format = 'Y-m-d')
    {
        if (!empty($date)) {
            return date($format, strtotime($date));
        }
        return null;
    }

    public static function toHumanDate($date)
    {
        if (!empty($date)) {
            return date('d-m-Y', strtotime($date));
        }
        return null;
    }

    public static function initMenu()
    {
        $dashboard = 1;
        $report = 2;
        $master = 3;
        $data = 4;
        $trx = 5;
        $purchase = 6;
        $monitoring = 7;
        $warehouse = 8;
        $accountancy = 9;
        $setting = 10;
        $log = 11;

        // name, parent, route, stat
        $menu[$dashboard] = ['Dashboard', null, '/site/index', 1];
        $menu[$report] = ['Laporan', null, null, 1];
        $menu[$master] = ['Master', null, null, 1];
        $menu[$data] = ['Data', null, null, 1];
        $menu[$trx] = ['Transaksi', null, null, 1];
        $menu[$purchase] = ['Pembelian ', null, null, 1];
        $menu[$monitoring] = ['Monitoring', null, null, 1];
        $menu[$warehouse] = ['Gudang', null, null, 1];
        $menu[$accountancy] = ['Akutansi', null, null, 1];
        $menu[$setting] = ['Setting', null, null, 1];
        $menu[$log] = ['Log', null, '/log', 1];

        $submenu = [
            // Laporan
            ['Penjualan', $report, '/report/sale/index', 1],
            ['Pembelian', $report, '/report/purchase/index', 1],
            ['Pendapatan', $report, '/report/income/index', 1],
            ['Operasional', $report, '/report/expense/index', 1],
            ['Probabilitas', $report, '/report/probability/index', 1],
            ['Swamedikasi', $report, '/report/swamedic/index', 1],
            ['Laba Rugi', $report, '/report/laba-rugi/index', 1],
            ['Pareto', $report, '/report/pareto/index', 1],
            ['Stock Opname', $report, '/report/stock-opname/index', 1],
            ['Penolakan', $report, '/report/rejection/index', 1],
            ['PMR', $report, '/report/pmr/index', 1],
            ['Piutang', $report, '/report/piutang/index', 1],
            ['Cash Flow', $report, '/report/cashflow/index', 1],
            ['Perubahan Modal', $report, '/report/capital-change/index', 1],
            // Master
            ['Store', $master, '/master/store/index', 0],
            ['Cabang', $master, '/master/branch/index', 1],
            ['Supplier', $master, '/master/supplier/index', 1],
            ['Tipe Produk', $master, '/master/product-type/index', 1],
            ['Kemasan', $master, '/master/package/index', 1],
            ['Satuan Dosis', $master, '/master/dose-unit/index', 1],
            ['Sediaan', $master, '/master/sediaan/index', 1],
            ['Golongan', $master, '/master/drug-category/index', 1],
            ['User', $master, '/user/index', 1],
            ['Jam Kerja', $master, '/master/workhour/index', 1],
            ['Asuransi', $master, '/master/insurance/index', 1],
            ['Bank', $master, '/master/bank/index', 1],
            ['Role', $master, '/master/role/index', 1],
            ['Modal Cabang', $master, '/master/capital/index', 0],
            // Data           
            ['Produk', $data, '/master/product/index', 1],
            ['Dokter External', $data, '/master/doctor-external/index', 1],
            ['Member', $data, '/master/member/index', 1],
            ['Promo', $data, '/master/promo/index', 1],
            ['Gudang ', $data, '/master/warehouse/index', 1],
            ['Rak', $data, '/master/rack/index', 1],
            ['Layanan Resep', $data, '/master/receipt-service/index', 1],
            // Trx
            ['Kasir', $trx, '/trx/cashier/create', 1],
            ['List Transaksi', $trx, '/trx/cashier/index', 1],
            ['Riwayat Shift', $trx, '/trx/shift/index', 1],
            ['Swamedikasi', $trx, '/trx/swamedic/index', 1],
            ['Dokter', $trx, '/trx/doctor/index', 1],
            ['Farmasi', $trx, '/trx/pharmacy/index', 1],
            ['Jadwal kerja', $trx, '/trx/schedule/index', 1],
            // Purchase
            ['Pemesanan', $purchase, '/trx/procurement/index', 1],
            ['Pemesanan Cabang', $purchase, '/trx/procurement-branch/index', 1],
            ['Penerimaan', $purchase, '/trx/reception/index', 1],
            ['Inkaso', $purchase, '/trx/inkaso/index', 1],
            ['Retur', $purchase, '/trx/retur/index', 1],
            // Monitoring
            ['User Login', $monitoring, '/monitoring/user/index', 1],
            // Warehouse
            ['Stok Produk', $warehouse, '/report/stock/index', 1],
            ['Penyesuaian Produk', $warehouse, '/trx/adjust/index', 1],
            ['Defecta', $warehouse, '/report/defecta/create?TrsDefecta%5Bmessage%5D=-1', 1],
            ['Approval Defecta', $warehouse, '/report/defecta/list-approval', 1],
            ['Stok Opname', $warehouse, '/trx/stock-opname/index', 1],
            ['Moving Stok', $warehouse, '/trx/stock-move/index', 1],
            // Akutansi
            ['Plan Target', $accountancy, '/report/plan-target/index', 1],
            ['Kategori', $accountancy, '/master/coa/index', 1],
            ['Pemasukan dan Pengeluaran', $accountancy, '/trx/accounting/index', 1],
            ['Cash Bank', $accountancy, '/trx/cashbank/index', 1],
            ['Modal', $accountancy, '/trx/capital/index', 1],
            ['Dividen', $accountancy, '/trx/dividen/index', 1],
            // Setting
            ['Konfigurasi', $setting, '/setting/index', 1],
            ['Access Rule', $setting, '/access-rule/index', 1],
        ];

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $flag = true;

            Yii::$app->db->createCommand("truncate table menu")->execute();
            foreach ($menu as $i => $d) {
                $model = new Menu();
                $model->id = $i;
                $model->name = $d[0];
                $model->parent = $d[1];
                $model->route = $d[2];
                $model->stat = $d[3];
                $model->order = $i;

                if (($flag = $model->save()) == false) {
                    $transaction->rollBack();
                    break;
                }
            }

            $counter = count($menu);
            foreach ($submenu as $i => $d) {
                $id = $counter + $i + 1;
                $model = new Menu();
                $model->id = $id;
                $model->name = $d[0];
                $model->parent = $d[1];
                $model->route = $d[2];
                $model->stat = $d[3];
                $model->order = $id;

                if (($flag = $model->save()) == false) {
                    $transaction->rollBack();
                    echo '<pre>';
                    print_r($model);
                    die;
                }
            }

            if ($flag) {
                $transaction->commit();
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }

        // ACCESS RULE
        $authItems = [
            // Super
            'all_access' => ' Super Akses|Semua Menu',
            'all_branch' => " Super Akses|Semua Cabang",
            // Dashboard
            'dashboard' => '',
            // Laporan
            '/report/sale/*' => "Report|Penjualan",
            '/report/expense/*' => "Report|Pengeluaran",
            '/report/income/*' => "Report|Pemasukan",
            '/report/laba-rugi/*' => "Report|Laba - Rugi",
            '/report/pareto/*' => "Report|Pareto",
            '/report/plan-target/*' => "Report|Plan Target",
            '/report/probability/*' => "Report|Probabilitas",
            '/report/rejection/*' => "Report|Penolakan",
            '/report/stock-opname/*' => "Report|Stock Opname",
            '/report/swamedic/*' => "Report|Swamedikasi",
            '/report/purchase/*' => "Report|Pembelian",
            '/report/pmr/*' => "Report|PMR",
            '/report/piutang/*' => "Report|Piutang",
            '/report/cashflow/*' => "Report|Cash Flow",
            '/report/capital-change/*' => "Report|Perubahan Modal",
            // Master
            '/master/branch/*' => "Master|Cabang",
            '/master/product/*' => "Master|Produk",
            '/master/supplier/*' => "Master|Supplier",
            '/master/dose-unit/*' => "Master|Satuan Dosis",
            '/master/sediaan/*' => "Master|Sediaan",
            '/master/workhour/*' => "Master|Jam Kerja",
            '/master/drug-category/*' => "Master|Katergori Obat",
            '/master/package/*' => "Master|Kemasan",
            '/master/receipt-service/*' => "Master|Layanan Resep",
            '/user/*' => "User|Kelola User",
            '/master/coa/*' => "Master|Kategori Akutansi",
            '/master/insurance/*' => "Master|Asuransi",
            '/master/job/*' => "Master|Profesi",
            '/master/promo/*' => "Master|Promo",
            '/master/bank/*' => "Master|Bank",
            '/master/role/*' => "Master|Role",
            // Data
            '/master/member/*' => "Data|Member",
            '/master/doctor-external/*' => "Data|Dokter External",
            '/master/rack/*' => "Data|Rak",
            '/master/warehouse/*' => "Data|Gudang",
            // Transaksi
            'cashier' => [
                'label' => "Transaksi|Kasir",
                'route_menu' => '/trx/cashier/create'
            ],
            '/trx/shift/*' => "Transaksi|Riwayat Shift",
            '/trx/swamedic/*' => "Transaksi|Swamedikasi",
            '/trx/doctor/*' => "Transaksi|Dokter",
            '/trx/pharmacy/*' => "Transaksi|Farmasi",
            '/trx/schedule/*' => "Transaksi|Jadwal Kerja",
            '/trx/rejection/*' => "Transaksi|Penolakan",
            // Pembelian
            '/trx/procurement/*' => "Pembelian|Pemesanan",
            '/trx/procurement-branch/*' => "Pembelian|Pemesanan Cabang",
            '/trx/reception/*' => "Pembelian|Penerimaan",
            '/trx/inkaso/*' => "Pembelian|Inkaso",
            '/trx/retur/*' => "Pembelian|Retur",
            // Monitoring
            '/monitoring/user/*' => "Monitoring|User Login",
            // Gudang
            '/report/stock/*' => "Gudang|Stok Terkini",
            '/trx/adjust/*' => "Gudang|Penyesuaian Produk",
            'defecta' => [
                'label' => "Gudang|Defecta",
                'route_menu' => '/report/defecta/create?TrsDefecta%5Bmessage%5D=-1'
            ],
            'admin_approval' => [
                'label' => "Gudang|Approval Admin",
                'route_menu' => '/report/defecta/list-approval'
            ],
            'outlet_approval' => [
                'label' => "Gudang|Approval Outlet",
                'route_menu' => '/report/defecta/list-approval'
            ],
            '/trx/stock-opname/*' => "Gudang|Stok Opname",
            '/trx/stock-move/*' => "Gudang|Moving Stock",
            // Akutansi
            '/trx/accounting/*' => "Akutansi|Kategori dan Input Pemasukan dan Pengeluaran",
            '/trx/cashbank/*' => "Akutansi|Cash Bank",
            '/trx/capital/*' => "Akutansi|Modal",
            '/trx/dividen/*' => "Akutansi|Dividen",
            // Setting
            '/setting/*' => "Setting|Konfigurasi",
            '/access-rule/*' => "Setting|Hak Akses",
        ];

        $authItemChilds = [
            'dashboard' => [
                "/api/api/*",
                "/api/location/*",
                "/api/member/*",
                "/api/procurement/*",
                "/api/receipt/*",
                "/api/reception/*",
                "/api/schedule/*",
                "/api/supplier/*",
                "/api/swamedication/*",
                "/gridview/*",
                "/site/*",
            ],
            'all_access' => [
                "/*",
                "admin_approval",
                "outlet_approval",
            ],
            'cashier' => [
                '/trx/cashier/*',
                '/trx/cashier-manual/*',
                '/trx/shift/open',
                '/trx/shift/close',
                '/trx/shift/download',
                '/trx/swamedic/createmodal',
                '/trx/swamedic/listmodal',
            ],
            'defecta' => [
                '/report/defecta/*',
            ],
            'admin_approval' => [
                '/report/defecta/approval',
                '/report/defecta/list-approval',
            ],
            'outlet_approval' => [
                '/report/defecta/approval',
                '/report/defecta/list-approval',
            ],
        ];

        AuthItem::updateAll(['description' => null]);
        $i = 0;
        foreach ($authItems as $route => $data) {
            $model = AuthItem::findOne(['name' => $route, 'type' => 2]);

            if (is_array($data)) {
                $description = $data['label'];
                $route_menu = $data['route_menu'];
            } else {
                $route_menu = $route;
                $description = $data;
                if (strpos($route, '*') !== false) {
                    $route_menu = str_replace('*', 'index', $route);
                }
            }

            if ($model) {
                $model->description = $description;
            } else {
                $model = new AuthItem();
                $model->name = $route;
                $model->type = 2;
                $model->description = $description;
            }
            $model->route_menu = $route_menu;
            $model->order_val = $i;
            $model->save();
            $i++;
        }

        foreach ($authItemChilds as $parent => $arrRoute) {
            AuthItemChild::deleteAll(['parent' => $parent]);

            foreach ($arrRoute as $child) {
                $model = new AuthItemChild();
                $model->parent = $parent;
                $model->child = $child;
                $model->save();
            }
        }
        Helper::cacheFlush();
    }

    public static function initView()
    {
        // USER CASHIER ACTIVE
        $queryUserCashierActive = "
                SELECT user.user_id, username, nip, s.id_branch,s.workhour_start, s.workhour_end, s.id_schedule, s.datetime_open, s.datetime_close FROM `user`
                INNER JOIN `trs_schedule` s ON `user`.`user_id` = `s`.`user_id` 
                WHERE s.`workhour_start` <= NOW() AND s.`workhour_end` > NOW() AND datetime_open IS NOT NULL AND datetime_close IS NULL;";
        // END USER CASHIER ACTIVE

        Yii::$app->db->createCommand("CREATE OR REPLACE VIEW user_cashier_active AS $queryUserCashierActive")->execute();
    }

    public static function getProductLog($id_branch, $isOrdered = true)
    {
        $ordered = $isOrdered ? "
        UNION ALL
        SELECT '1' no, CONCAT('stock_adjust',sad.id_adjust_detail) id, 'expired' type, id_product, 0 quantity, sad.id_warehouse, sad.id_rack, sad.created_at, sa.id_user_input FROM trs_adjust_detail sad
        INNER JOIN trs_adjust sa ON sad.id_adjust = sa.id_adjust
        WHERE sa.delete_time IS NULL AND sa.id_branch=$id_branch AND sa.type=1 

        ORDER BY created_at ASC, id_warehouse DESC, no ASC
        " : "";

        $queryProductLog = "
            SELECT '1' no, CONCAT('buy',rd.id_reception_detail) id, 'buy' type, rd.id_product, rd.total_quantity quantity, r.id_warehouse, NULL id_rack, r.created_at, r.id_user_input FROM trs_reception_detail rd
            INNER JOIN trs_reception r ON r.id_reception = rd.id_reception
            WHERE r.delete_time IS NULL AND r.id_branch=$id_branch 
            UNION ALL
            SELECT '1' no, CONCAT('sell',rd.id_reception_detail) id, 'sell' type, rd.id_product_supplier_branch id_product, (rd.total_quantity*-1) quantity, null id_warehouse, rd.id_rack id_rack, r.created_at, r.id_user_input FROM trs_reception_detail rd
            INNER JOIN trs_reception r ON r.id_reception = rd.id_reception
            WHERE r.delete_time IS NULL AND r.id_supplier_branch=$id_branch 
            UNION ALL
            SELECT '1' no, CONCAT('retur',ret.id_retur) id, 'retur' type, rd.id_product, ret.quantity*-1 quantity, ret.id_warehouse, ret.id_rack, ret.created_at, ret.id_user_input FROM trs_retur ret
            INNER JOIN trs_reception_detail rd ON ret.id_reception_detail=rd.id_reception_detail
            INNER JOIN trs_reception r ON r.id_reception = rd.id_reception
            WHERE ret.delete_time IS NULL AND r.id_branch=$id_branch 
            UNION ALL
            SELECT '2' no, CONCAT('sale',sd.id_sale_detail) id, 'sale' type, id_product, coalesce(total_quantity*-1, 0) quantity, NULL id_warehouse, id_rack, s.trx_at created_at, s.id_user_input FROM trs_sale_detail sd
            INNER JOIN trs_sale s ON sd.id_sale = s.id_sale
            WHERE s.delete_time IS NULL AND s.type in ('SALE', 'RECEIPT_SALE') AND sd.type in ('SALE', 'RECEIPT_SALE') AND s.status in (1, 3) AND s.id_branch=$id_branch 
            UNION ALL
            SELECT '1' no, CONCAT('so',sod.id_stock_opname_detail) id, CASE WHEN count_diff >= 0 THEN 'so_plus' else 'so_min' END type,id_product, count_diff quantity, coalesce(sod.id_warehouse, so.id_warehouse) id_warehouse, coalesce(sod.id_rack, so.id_rack) id_rack, sod.created_at, so.id_user_input FROM trs_stock_opname_detail sod
            INNER JOIN trs_stock_opname so ON sod.id_stock_opname = so.id_stock_opname
            WHERE so.delete_time IS NULL AND so.id_branch=$id_branch 
            UNION ALL
            SELECT '1' no, CONCAT('sa',sad.id_adjust_detail) id, CASE WHEN count_diff >= 0 THEN 'sa_plus' else 'sa_min' END type,id_product, count_diff quantity, sad.id_warehouse, sad.id_rack, sad.created_at, sa.id_user_input FROM trs_adjust_detail sad
            INNER JOIN trs_adjust sa ON sad.id_adjust = sa.id_adjust
            WHERE sa.delete_time IS NULL AND sa.id_branch=$id_branch AND sa.type=0 
            UNION ALL
            SELECT '1' no, CONCAT('se',sad.id_adjust_detail) id, CASE WHEN count_manual >= 0 THEN 'exp_ret' else 'exp' END type,id_product, count_manual quantity, sad.id_warehouse, sad.id_rack, sad.created_at, sa.id_user_input FROM trs_adjust_detail sad
            INNER JOIN trs_adjust sa ON sad.id_adjust = sa.id_adjust
            WHERE sa.delete_time IS NULL AND sa.id_branch=$id_branch AND sa.type=-1 
            UNION ALL
            SELECT '1' no, CONCAT('smw',id_stock_move) id, 'move' type, sm.id_product, total*-1 quantity, CASE WHEN from_warehouse=1 THEN id_loc_from ELSE NULL END id_warehouse, CASE WHEN from_warehouse=1 THEN NULL ELSE id_loc_from END id_rack, sm.created_at, sm.id_user_input FROM trs_stock_move sm
            INNER JOIN mst_product p ON p.id_product = sm.id_product
            WHERE p.id_branch=$id_branch 
            UNION ALL
            SELECT '2' no, CONCAT('smr',id_stock_move) id, 'move' type, sm.id_product, total*1 quantity, CASE WHEN from_warehouse=1 THEN NULL ELSE id_loc_to END id_warehouse, CASE WHEN from_warehouse=1 THEN id_loc_to ELSE NULL END id_rack, sm.created_at, sm.id_user_input FROM trs_stock_move sm
            INNER JOIN mst_product p ON p.id_product = sm.id_product
            WHERE p.id_branch=$id_branch 
            UNION ALL
            SELECT '1' no, CONCAT('void',sd.id_sale_detail) id, 'void' type, sd.id_product, total_quantity quantity, null id_warehouse, id_rack, s.void_at created_at, s.id_user_input FROM trs_sale_detail sd 
            INNER JOIN trs_sale s ON s.id_sale=sd.id_sale 
            WHERE s.status = 3 AND s.id_branch=$id_branch 
            $ordered";

        $q = (new Query())->from(['pl' => "($queryProductLog)"]);

        return $q;
    }

    public static function getProductStock($id_branch, $hideZeroRackStock = false)
    {
        $queryProductLog = self::getProductLog($id_branch, false)->createCommand()->getSql();

        $queryProductStock = "
                SELECT p.id_branch, p.stock_minimal, p.id_product, p.name, p.barcode, p.manufacture, p.id_dose_unit, p.id_package_1, p.purchase_price, p.selling_price, p.selling_price_receipt, p.dose, p.is_consignment, 
                    CASE WHEN trx_stock.id_warehouse IS NULL AND trx_stock.id_rack IS NULL THEN w.id_warehouse ELSE trx_stock.id_warehouse END id_warehouse, trx_stock.id_rack, coalesce(trx_stock.total, 0) total 
                FROM mst_product p
                LEFT JOIN (
                    SELECT id_branch, id_warehouse FROM mst_warehouse WHERE delete_time IS NULL AND id_branch=$id_branch ORDER BY id_warehouse LIMIT 1
                ) w ON p.id_branch=w.id_branch
                LEFT JOIN (
                    SELECT id_product, id_warehouse, id_rack, sum(quantity) total FROM ($queryProductLog) pl 
                    group by id_product, id_warehouse, id_rack
                ) trx_stock ON trx_stock.id_product = p.id_product
                WHERE p.status=1 and p.delete_time is NULL AND p.id_branch=$id_branch";

        $q = (new Query())
            ->from(['ps' => "($queryProductStock)"]);

        if ($hideZeroRackStock) {
            $q->where([
                'or',
                ['not', ['ps.id_warehouse' => null]],
                ['>', 'ps.total', 0]
            ]);
        }

        return $q;
    }

    public static function updateProductStock($id_branch = null)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $flag = true;
        try {
            if (!$id_branch) {
                Yii::$app->db->createCommand()->truncateTable('trs_product_stock')->execute();
            }

            foreach (MstBranch::find()->andFilterWhere(['id_branch' => $id_branch])->all() as $b) {
                foreach (self::getProductStock($b->id_branch)->all() as  $m) {
                    $model = TrsProductStock::find()
                        ->where(['id_branch' => $b->id_branch, 'id_product' => $m['id_product']])
                        ->andFilterWhere(['id_warehouse' => $m['id_warehouse'] ?: NULL])
                        ->andFilterWhere(['id_rack' => $m['id_rack'] ?: NULL])
                        ->one();
                    if (!$model) {
                        $model = new TrsProductStock();
                        $model->id_branch = $b->id_branch;
                        $model->id_product = $m['id_product'];
                        $model->id_warehouse = $m['id_warehouse'] ?: NULL;
                        $model->id_rack = $m['id_rack'] ?: NULL;
                    }
                    $model->total = $m['total'];

                    $flag = $flag && $model->save();
                    if (!$flag) {
                        echo '<pre>';
                        print_r($model);
                        die;
                    }
                }
            }

            if ($flag) {
                $transaction->commit();
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function getPlainProductAvailable($id_branch, $calcExpired = false)
    {
        $qProductStock = self::getProductLog($id_branch, false)
            ->select(['id_product', 'sum(quantity) quantity'])
            ->groupBy(['id_product']);

        if (!$calcExpired) {
            $qProductStock->where(['not', ['type' => ['exp', 'exp_ret']]]);
        }

        return $qProductStock;
    }

    public static function getProductAvailable($id_branch, $allProduct = true)
    {
        if ($allProduct) {
            $query = self::getProductStock($id_branch, true)
                ->select(['id_product', 'sum(total) quantity'])
                ->groupBy(['id_product']);
        } else {
            $queryProductLog = self::getProductLog($id_branch, false)->createCommand()->getSql();

            $queryProductStock = "
                SELECT id_product, id_warehouse, id_rack, sum(quantity) quantity FROM ($queryProductLog) pl 
                group by id_product, id_warehouse, id_rack
            ";

            $query = (new Query())
                ->from(['ps' => "($queryProductStock)"])
                ->where([
                    'or',
                    ['not', ['ps.id_warehouse' => null]],
                    ['>', 'ps.quantity', 0]
                ]);
        }

        return $query;
    }

    public function getTotalAsetProduct($id_branch)
    {
        $query = self::getProductStock($id_branch, true)
            ->select(['is_consignment', 'sum((purchase_price)*total) total'])
            ->groupBy(['is_consignment']);

        return $query;
    }

    // -- 

    public static function getProductExpired($id_branch, $calcExpired = true)
    {
        // menampilkan daftar list expired produk
        $qListExpired = " SELECT id_product, sum(quantity) quantity, expired_date, no_batch FROM (
                    (
                        SELECT rd.id_product, (rd.quantity-coalesce(ret.retur_quantity,0)) AS quantity, expired_date, no_batch FROM trs_reception_detail rd
                        INNER JOIN trs_reception r ON r.id_reception = rd.id_reception
                        LEFT JOIN (
                            SELECT id_reception_detail, sum(coalesce(quantity, 0)) retur_quantity FROM trs_retur
                            WHERE delete_time IS NULL
                            GROUP BY id_reception_detail
                        ) ret ON ret.id_reception_detail=rd.id_reception_detail
                        WHERE r.delete_time IS NULL AND expired_date IS NOT NULL AND r.id_branch=$id_branch 
                    ) UNION ALL (
                        SELECT id_product, count_diff AS quantity, expired_at AS expired_date, no_batch FROM trs_stock_opname_detail sod
                        INNER JOIN trs_stock_opname so ON sod.id_stock_opname = so.id_stock_opname
                        WHERE so.delete_time IS NULL AND count_diff > 0 AND expired_at IS NOT NULL AND so.id_branch=$id_branch 
                    ) UNION ALL (
                        SELECT id_product, count_manual AS quantity, expired_date, no_batch FROM trs_adjust_detail ad
                        INNER JOIN trs_adjust a ON ad.id_adjust = a.id_adjust
                        WHERE a.delete_time IS NULL AND a.id_branch=$id_branch AND a.type=1
                    )
                ) y
                GROUP BY id_product, expired_date, no_batch
                ORDER BY id_product asc, expired_date asc
                ";

        // merge product stok dan list product expired
        $subQuery  = (new Query())
            ->select([
                'le.*', 's.quantity real_stock',
            ])
            ->from(['le' => "($qListExpired)"])
            ->andWhere(['>', 's.quantity', 0])
            ->innerJoin(['s' => self::getPlainProductAvailable($id_branch, $calcExpired)], 'le.id_product=s.id_product');

        $query = (new Query())
            ->select([
                "expired_date", "no_batch",
                new Expression("CASE WHEN @IdProduct=l.id_product THEN if(@Temp>l.quantity,l.quantity,@Temp) ELSE if(l.quantity>l.real_stock,l.real_stock,l.quantity) END total_expired"),
                new Expression("@Temp := CASE WHEN @IdProduct=l.id_product THEN @Temp ELSE l.real_stock END - l.quantity temp_sisa_stok"), // hasil setelah dikurang dari quantity (bisa jadi dari penjualan)
                new Expression("@IdProduct := l.id_product id_product"),
            ])
            ->from(['l' => $subQuery, 't' => new Expression("(SELECT @IdProduct := 0, @Temp := 0)")]);

        $mainQuery = (new Query())
            ->select(['id_product', "expired_date", "no_batch", 'total_expired'])
            ->from(['s' => $query])
            ->where(['>', 'total_expired', 0]);

        return $mainQuery;
    }

    public static function getLastTimeStockMove($id_branch)
    {
        $query = "
            select id_product, max(date) date from (
                select sm.id_product, max(sm.created_at) date from trs_stock_move sm
                inner join mst_product p on p.id_product=sm.id_product
                where p.id_branch=$id_branch and sm.from_warehouse=1
                group by sm.id_product
                union all
                select sod.id_product, max(sod.created_at) date from trs_stock_opname_detail sod
                inner join trs_stock_opname so on so.id_stock_opname=sod.id_stock_opname
                where so.id_branch=$id_branch and so.delete_time is null
                group by id_product
            )x
            group by id_product
";
        return (new Query())->from(['pe' => "($query)"]);
    }
}
