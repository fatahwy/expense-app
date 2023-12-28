<?php

namespace app\components;

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use Yii;
use yii\helpers\ArrayHelper;

class Helper
{

    const STAT_INACTIVE = 0;
    const STAT_ACTIVE = 1;
    const ROLE_ADMIN = 1;
    const ROLE_MEMBER = 2;

    public static function isGuest()
    {
        return Yii::$app->user ? Yii::$app->user->isGuest : true;
    }

    public static function identity()
    {
        return self::isGuest() ? NULL : Yii::$app->user->identity;
    }

    public static function role($role)
    {
        $user = self::identity();
        if ($user) {
            return $user->role == $role;
        }
        return false;
    }

    public static function isAdmin()
    {
        return self::role(self::ROLE_ADMIN);
    }

    public static function isMember()
    {
        return self::role(self::ROLE_MEMBER);
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
        return self::setFlash('success', (empty($msg) ? 'Process Successful.' : $msg));
    }

    public static function flashFailed($msg = '')
    {
        return self::setFlash('danger', (empty($msg) ? 'Process failed!' : $msg));
    }

    public static function filterValue($value)
    {
        return strlen($value) === 0 ? null : $value;
    }

    public static function getMonths($locale = "id_ID")
    {
        $month = [];
        setlocale(LC_TIME, "$locale.utf8");
        for ($i = 1; $i <= 12; $i++) {
            $month[$i] = strftime('%B', strtotime('01-' . $i . '-2000'));
        }
        return $month;
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

    public static function today()
    {
        return date('Y-m-d');
    }

    public static function now()
    {
        return date('Y-m-d H:i:s');
    }

    public static function getColor()
    {
        $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
        $color = '#' . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)] . $rand[rand(0, 15)];

        return $color;
    }

    public static function faReset($text = 'Reset')
    {
        return "<i class='fa'>&#xf021;</i> $text";
    }

    public static function faSearch($text = 'Search')
    {
        return "<i class='fa fa-search'></i> $text";
    }

    public static function faAdd($text = 'Add')
    {
        return "<i class='fa fa-plus'></i> $text";
    }

    public static function faSave($text = 'Save')
    {
        return "<i class='fa fa-save'></i> $text";
    }

    public static function faUpdate($text = 'Update')
    {
        return "<i class='fa fa-pencil-alt'></i> $text";
    }

    public static function faDelete($text = 'Delete')
    {
        return "<i class='fa fa-trash'></i> $text";
    }

    public static function saveImgDataUrl($data_url, $path)
    {
        list($type, $data) = explode(';', $data_url);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);

        file_put_contents($path, $data);
    }
}
