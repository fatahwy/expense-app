<?php

namespace app\components;

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class Helper
{

    const STAT_INACTIVE = 0;
    const STAT_ACTIVE = 1;
    const STAT_PENDING = 2;
    const STAT_RETUR = 3;
    const STAT_FAIL = 4;
    const STAT_CANCEL = 5;

    public static function isGuest()
    {
        return Yii::$app->user ? Yii::$app->user->isGuest : true;
    }

    public static function identity()
    {
        return self::isGuest() ? NULL : Yii::$app->user->identity;
    }

    public static function getBaseUrl($file = NULL)
    {
        return Url::base(true) . '/' . $file;
    }

    public static function getBaseImg($file = NULL)
    {
        return Url::base(true) . '/images/' . $file;
    }

    public static function getBaseFile($file = NULL)
    {
        return Url::base(true) . '/uploads/' . $file;
    }

    public static function session($key, $set = NULL)
    {
        return $set ? Yii::$app->session->set($key, $set) : Yii::$app->session->get($key);
    }

    public static function getFlash($key)
    {
        return Yii::$app->session->getFlash($key);
    }

    public static function setFlash($key, $set)
    {
        return Yii::$app->session->setFlash($key, $set);
    }

    public static function flashSucceed($msg = '')
    {
        return self::setFlash('success', (empty($msg) ? 'Proses berhasil.' : $msg));
    }

    public static function flashFailed($msg = '')
    {
        return self::setFlash('danger', (empty($msg) ? 'Proses gagal!' : $msg));
    }

    public static function textPaymentType($stat = null)
    {
        $stats = ['Kredit', 'Tunai'];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function textDiscountType($stat = null)
    {
        $stats = ['Rp', 'Persen (%)'];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function textStatus($stat = null)
    {
        $stats = ['NON-AKTIF', 'AKTIF'];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function textSaleReceiptType($stat = null)
    {
        $stats = ['Non Resep', 'Resep', 'Swamedikasi'];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function textReceiptOrigin($stat = null)
    {
        $stats = ['Internal' => 'Internal', 'External' => 'External'];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function textPemesanan($stat = null)
    {
        $stats = ['Menunggu', 'Diterima'];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function textDrugCategory($stat = null)
    {
        $stats = ['Keamanan', 'Farmakologi'];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function textSaldoType($stat = null)
    {
        $stats = ['debit' => 'Pengeluaran', 'kredit' => 'Pemasukan'];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function textCashierPaymentType($stat = null)
    {
        $stats = [1 => 'Tunai', 2 => 'QRIS', 3 => 'EDC'];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function textInkasoPaymentStat($stat = null)
    {
        $stats = [
            Helper::STAT_INACTIVE => 'Belum Lunas',
            Helper::STAT_ACTIVE => 'Lunas',
            Helper::STAT_PENDING => 'Menunggu Pembayaran',
            Helper::STAT_RETUR => 'Menunggu Retur',
            Helper::STAT_FAIL => 'Jatuh Tempo',
        ];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function textPemesananIcon($stat = null)
    {
        $stats = [
            '<span title="Menunggu" class="badge badge-warning">Menunggu</span>',
            '<span title="Diterima" class="badge badge-success">Diterima</span>',
        ];

        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function filterValue($value)
    {
        return strlen($value) === 0 ? null : $value;
    }

    public static function textBloodGroup($stat = null)
    {
        $stats = [
            'A' => 'A',
            'B' => 'B',
            'AB' => 'AB',
            'O' => 'O',
        ];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function textValid($stat = null)
    {
        $stats = [
            1 => 'Valid',
            0 => 'Invalid',
            2 => 'Meragukan',
        ];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function calcPercent($val, $percent)
    {
        return ($val * $percent) / 100;
    }

    public static function getSaleStat($stat = null)
    {
        $stats = [
            Helper::STAT_INACTIVE => 'Void',
            Helper::STAT_ACTIVE => 'Regular',
            Helper::STAT_PENDING => 'Pending',
            Helper::STAT_RETUR => 'Retur',
        ];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function textLabel($text, $stat)
    {
        $stats = [
            Helper::STAT_INACTIVE => 'danger',
            Helper::STAT_ACTIVE => 'success',
            Helper::STAT_PENDING => 'warning',
            Helper::STAT_RETUR => 'warning',
            Helper::STAT_FAIL => 'danger',
        ];
        $str = isset($stats[$stat]) ? $stats[$stat] : 'primary';
        return "<span class='badge badge-$str'>$text</span>";
    }

    public static function getHowPayment($id = null)
    {
        $list = ['Tunai', 'Cash Bank'];
        return empty($list[$id]) ? $list : $list[$id];
    }

    public static function sumArray($arr, $key)
    {
        $sum = 0;
        foreach ($arr as $row) {
            $sum += is_object($row) ? $row->$key : $row[$key];
        }
        return $sum;
    }

    public static function getDates()
    {
        $date = [];
        for ($i = 1; $i <= 31; $i++) {
            $date[$i] = $i;
        }
        return $date;
    }

    public static function getMonths()
    {
        $month = [];
        setlocale(LC_TIME, 'id_ID.utf8');
        for ($i = 1; $i <= 12; $i++) {
            $month[$i] = $i == 2 ? 'Feb' : strftime('%b', strtotime('01-' . $i . '-2000'));
        }
        return $month;
    }

    public static function getYears()
    {
        $year = [];
        for ($i = ((int) date('Y')); $i <= ((int) date('Y')) + 1; $i++) {
            $year[$i] = $i;
        }
        return $year;
    }

    public static function diffHour($date, $hour = 48, $hourafter = 48)
    {
        $diff = date_diff(date_create(), date_create($date), false);
        $h = ($diff->y * 8760 + $diff->m * 30 * 24 + $diff->d * 24 + $diff->h + $diff->i / 60) * (1 - ($diff->invert * 2));
        return $h <= $hour && $h > (-1 * $hourafter);
    }

    public static function diffMinutes($date, $minutes = 48, $minutesafter = 48)
    {
        return self::diffHour($date, $minutes / 60, $minutesafter / 60);
    }

    public static function calcAge($dob, $ref = null)
    {
        $now = $ref ? $ref : time();
        return round(($now - strtotime($dob)) / (31557600), 2);
    }

    //    CODE GENERATOR

    public static function getRandom($len, $format = 1)
    {
        $sets = ['0956731248', 'STUVWXGHNOPQRYZIJKLMABCDEF', 'ijrstuvwhnopqyzxklmabcdefg'];
        $seeds = [
            $sets[0], // numeric
            $sets[1], // uppercase
            $sets[2], // lowercase
            $sets[0] . $sets[1] . $sets[2], // all
            $sets[0] . $sets[1], //numeric uppercase
            $sets[0] . $sets[2] // numeric lowercase
        ];
        $key = $seeds[$format];
        $keyLen = strlen($key);
        $str = '';
        for ($i = 0; $i < $len; $i++) {
            $str .= $key[rand(0, $keyLen - 1)];
        }
        return $str;
    }

    public static function generateCode($id = NULL)
    {
        $salt = empty($id) ? date('dms') : $id;
        $code = self::getRandom(8 - strlen($salt)) . $salt;
        return $code;
    }

    public static function arrMapLower($model, $key, $value)
    {
        $locations = [];
        foreach ($model as $val) {
            $lowerName = trim(strtolower($val[$key]));
            $locations[$lowerName] = $val[$value];
        }

        return $locations;
    }

    public static function getInt($str)
    {
        return (int) preg_replace('/\D/', '', $str);
    }

    public static function upperTrim($str)
    {
        return strtolower(trim($str));
    }

    public static function lowerTrim($str)
    {
        return strtolower(trim($str));
    }

    public static function printr($var, $return = true)
    {
        $dump = '<pre>';
        $dump .= print_r($var, true);
        $dump .= '</pre>';

        if ($return) {
            return $dump;
        } else {
            echo $dump;
        }
    }

    public static function getCustomSummary($count, $pagination, $staticBegin = true)
    {
        $totalCount = $pagination->totalCount;
        $begin = $staticBegin ? 1 : $pagination->getPage() * $pagination->pageSize + 1;
        $end = $begin + $count - 1;
        if ($begin > $end) {
            $begin = $end;
        } else if ($end > $totalCount) {
            $end = $totalCount;
        }

        return "Menampilkan $begin-$end of $totalCount items.";
    }

    public static function penyebut($value)
    {
        $value = abs($value);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($value < 12) {
            $temp = " " . $huruf[$value];
        } else if ($value < 20) {
            $temp = self::penyebut($value - 10) . " belas";
        } else if ($value < 100) {
            $temp = self::penyebut($value / 10) . " puluh" . self::penyebut($value % 10);
        } else if ($value < 200) {
            $temp = " seratus" . self::penyebut($value - 100);
        } else if ($value < 1000) {
            $temp = self::penyebut($value / 100) . " ratus" . self::penyebut($value % 100);
        } else if ($value < 2000) {
            $temp = " seribu" . self::penyebut($value - 1000);
        } else if ($value < 1000000) {
            $temp = self::penyebut($value / 1000) . " ribu" . self::penyebut($value % 1000);
        } else if ($value < 1000000000) {
            $temp = self::penyebut($value / 1000000) . " juta" . self::penyebut($value % 1000000);
        } else if ($value < 1000000000000) {
            $temp = self::penyebut($value / 1000000000) . " milyar" . self::penyebut(fmod($value, 1000000000));
        } else if ($value < 1000000000000000) {
            $temp = self::penyebut($value / 1000000000000) . " trilyun" . self::penyebut(fmod($value, 1000000000000));
        }

        return ucwords($temp);
    }

    public static function terbilang($value)
    {
        $res = trim(self::penyebut($value));

        if ($value < 0) {
            $res = "minus " . $res;
        }

        return $res;
    }

    public static function getFormatStruk($id = null)
    {
        $formats = [
            1 => [
                'name' => "Mini (58 mm)",
                'path' => self::getBaseImg('struck/mini.png'),
                'viewFile' => 'thermal',
                'style' => "width: 150px",
            ],
            //    2 => [
            //        'name' => "Medium (10,5 cm)",
            //    ],
            3 => [
                'name' => "Wide (21 cm / A4)",
                'path' => self::getBaseImg('struck/wide-21.png'),
                'viewFile' => 'a4',
                'style' => "width: 300px",
            ],
        ];

        return empty($formats[$id]) ? $formats : $formats[$id];
    }

    public static function getPioInterviewMethod($stat = null)
    {
        $stats = ['Lisan', 'Tertulis', 'Telpon'];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function getPioInterviewerStat($stat = null)
    {
        $stats = ['Pasien', 'Keluarga Pasien', 'Petugas Kesehatan'];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function getPioDeliverAnswerDuration($stat = null)
    {
        $stats = ['Segera', 'Dalam 24 Jam', 'Lebih 24 jam'];
        return $stats[$stat] ?? $stats;
    }

    public static function getPioQuestionType($stat = null)
    {
        $stats = [
            'Identifikasi Obat' => 'Identifikasi Obat',
            'Interaksi Obat' => 'Interaksi Obat',
            'Harga Obat' => 'Harga Obat',
            'Kontra Indikasi' => 'Kontra Indikasi',
            'Cara Pemakaian' => 'Cara Pemakaian',
            'Stabilitas' => 'Stabilitas',
            'Dosis' => 'Dosis',
            'Keracunan' => 'Keracunan',
            'Efek Samping Obat' => 'Efek Samping Obat',
            'Penggunaan Terapeutik' => 'Penggunaan Terapeutik',
            'Farmakokinetika' => 'Farmakokinetika',
            'Farmakodinamika' => 'Farmakodinamika',
            'Ketersediaan Obat' => 'Ketersediaan Obat',
            'Lain Lain' => 'Lain lain'
        ];
        return empty($stats[$stat]) ? $stats : $stats[$stat];
    }

    public static function convertToRoman($integer)
    {
        // Convert the integer into an integer (just to make sure)
        $integer = intval($integer);
        $result = '';

        // Create a lookup array that contains all of the Roman numerals.
        $lookup = array(
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        );

        foreach ($lookup as $roman => $value) {
            // Determine the number of matches
            $matches = intval($integer / $value);

            // Add the same number of characters to the string
            $result .= str_repeat($roman, $matches);

            // Set the integer to be the remainder of the integer and the value
            $integer = $integer % $value;
        }

        // The Roman numeral should be built, return it
        return $result;
    }

    public static function setExportList($data)
    {
        if (!empty($data['beforeHeader'])) {
            $data['contentBefore'] = [];

            foreach ($data['beforeHeader'] as $v) {
                if (!empty($v['columns'])) {
                    $label = implode(' ', ArrayHelper::getColumn($v['columns'], 'content'));

                    $data['contentBefore'][] = [
                        'value' => $label,
                    ];
                }
            }
            unset($data['beforeHeader']);
        }

        if (!empty($data['columns'])) {
            foreach ($data['columns'] as $i => $_) {
                if (!empty($data['columns'][$i]['format'])) {
                    $data['columns'][$i]['format'] = 'text';
                }
            }
        }

        return $data;
    }

    public static function cGridExport($dataProvider, $columns, $title, $id = 'selector', $filterModel = null, $toggleAllData = true)
    {
        $btnExport = ExportMenu::widget([
            'filename' => $title . '-' . date('Ymd'),
            'dataProvider' => $dataProvider,
        ] + self::setExportList($columns));

        return GridView::widget(array_merge([
            'filterModel' => $filterModel,
            'dataProvider' => $dataProvider,
            'id' => "gridview-id-$id",
            'toolbar' => [
                $btnExport,
                $toggleAllData ? '{toggleData}' : '',
            ]
        ], $columns));
    }

    public static function getDueDate()
    {
        $dueDate =  date('Y-m-28');

        if (DBHelper::today() >= $dueDate) {
            $dueDate = date('Y-m-28', strtotime('+1 month'));
        }

        return $dueDate;
    }

    public static function cacheFlush()
    {
        \Yii::$app->cache->flush();
    }

    public static function faSearch($text = 'Search')
    {
        return "<i class='fa fa-search'></i> $text";
    }

    public static function faAdd($text = 'Tambah')
    {
        return "<i class='fa fa-plus'></i> $text";
    }

    public static function faSave($text = 'Save')
    {
        return "<i class='fa fa-save'></i> $text";
    }

    public static function faDownload($text = 'Download')
    {
        return "<i class='fa fa-download'></i> $text";
    }

    public static function faUpdate($text = 'Update')
    {
        return "<i class='fa fa-pencil-alt'></i> $text";
    }

    public static function faRefresh($text = 'Update')
    {
        return "<i class='fa fa-redo-alt'></i> $text";
    }

    public static function faUpload($text = 'Import')
    {
        return "<i class='fa fa-upload'></i> $text";
    }

    public static function faDelete($text = 'Delete')
    {
        return "<i class='fa fa-trash'></i> $text";
    }

    public static function faList($text = 'List')
    {
        return "<i class='fa fa-list'></i> $text";
    }

    public static function faPrint($text = 'Print')
    {
        return "<i class='fa fa-print'></i> $text";
    }

    public static function saveImgDataUrl($data_url, $path)
    {
        list($type, $data) = explode(';', $data_url);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);

        file_put_contents($path, $data);
    }
}
