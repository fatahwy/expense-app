<?php

use app\components\Helper;
use app\models\Bank;
use kartik\grid\GridView;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View;
use richardfan\widget\JSRegister;
use kartik\icons\Icon;
use yii\jui\DatePicker;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Transaction';
$this->params['breadcrumbs'][] = $this->title;

if (is_null($dataProvider)) {
    echo 'Account belum ditambahkan';
} else {
    echo Html::tag('h3', 'Account: ' . Html::dropDownList(null, $user->bank_active, Bank::getList(), ['id' => 'change-bank']));

    echo Html::button(Helper::faAdd('Transaction'), ['class' => 'btn mb-3 btn-success btn-sm modalButton', 'modal-title' => 'Create', 'value' => Url::to(['process'])]);

    $labelOption = [
        'class' => 'text-left pl-3',
        'style' => 'border: 0px solid;padding:0px;padding-left:5px;',
        'colspan' => 2,
    ];
    $valueOption = [
        'class' => 'text-left',
        'style' => 'border: 0px solid;padding:0px',
        'colspan' => 9,
    ];
    $beforeHeader = [
        [
            'columns' => [
                [
                    'content' => 'Saldo',
                    'options' => $labelOption,
                ],
                [
                    'content' => Yii::$app->formatter->asCurrency($bankTotal),
                    'options' => $valueOption
                ],
            ]
        ],
    ];

    foreach ($total as $label => $m) {
        $beforeHeader[] =    [
            'columns' => [
                [
                    'content' => $label,
                    'options' => $labelOption
                ],
                [
                    'content' => Yii::$app->formatter->asCurrency($m),
                    'options' => $valueOption,
                ],
            ]
        ];
    }

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $model,
        'beforeHeader' => $beforeHeader,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'date_trx',
                'format' => 'date',
                'filter' => DatePicker::widget([
                    'model' => $model,
                    'options' => ['class' => 'form-control'],
                    'attribute' => 'date_trx',
                    'dateFormat' => 'yyyy-MM-dd',
                ])
            ],
            [
                'attribute' => 'label_id',
                'value' => function ($model) {
                    return $model->label->name ?? '';
                },
            ],
            [
                'attribute' => 'amount',
                'format' => 'integer',
                'contentOptions' => ['class' => 'text-end']
            ],
            'note',
            [
                'format' => 'raw',
                'value' => function ($model) {
                    $btn = Html::button(Icon::show('edit', ['class' => 'fa-sm']), ['class' => 'btn btn-primary btm-sm modalButton', 'modal-title' => 'Update',  'value' => Url::to(['process', 'transaction_id' => $model->transaction_id])]);
                    $btn .= '&nbsp';
                    $btn .= Html::a(Icon::show('trash', ['class' => 'fa-sm']), Url::to(['delete', 'transaction_id' => $model->transaction_id]), ['class' => 'btn btn-danger btm-sm', 'data-method' => 'post', 'data-confirm' => 'Anda yakin mau menghapus?']);
                    return $btn;
                },
                'width' => '20%',
            ]
        ],
        'toolbar' => []
    ]);

    $js = JSRegister::begin();
?>
    <script>
        $('#change-bank').on('change', function(e) {
            window.location.href = location.pathname + '?id=' + this.value;
        });
    </script>
<?php $js->end();
}