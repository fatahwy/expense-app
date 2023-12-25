<?php

use app\components\Helper;
use app\models\Bank;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View;
use richardfan\widget\JSRegister;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Transaction';
$this->params['breadcrumbs'][] = $this->title;

Yii::$app->formatter->locale = $this->context->locale;

if (is_null($dataProvider)) {
    echo 'Account has not added';
} else {
    echo "<div class='row mb-3'>";
    echo "<div class='col-md-1 col-sm-3 me-3'>";
    echo Html::tag('h3', 'Account');
    echo "</div>";
    echo "<div class='col-md-2 col-sm-9'>";
    echo Select2::widget([
        'name' => '',
        'value' => $user->bank_active,
        'data' => Bank::getList(),
        'options' => [
            'id' => 'change-bank'
        ],
    ]);
    echo "</div>";
    echo "</div>";

    echo Html::button(Helper::faAdd('Transaction'), ['class' => 'btn mb-3 btn-success btn-sm modalButton', 'modal-title' => 'Create', 'value' => Url::to(['process'])]);

    $labelOption = [
        'class' => 'text-left',
        'style' => 'border: 0px solid;padding:0px;padding-left:5px;',
        'colspan' => 2,
    ];
    $valueOption = [
        'class' => 'text-left',
        'style' => 'border: 0px solid;padding:0px;padding-left:5px;',
        'colspan' => 4,
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
                'filter' => Html::activeTextInput($model, 'date_trx', [
                    'class' => 'form-control',
                    'type' => 'date',
                ]),
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
                    $btn = Html::button(Helper::faUpdate(''), ['class' => 'btn btn-primary btn-sm mb-1 modalButton', 'modal-title' => 'Update', 'title' => 'Update',  'value' => Url::to(['process', 'transaction_id' => $model->transaction_id])]);
                    $btn .= '&nbsp';
                    $btn .= Html::a(Helper::faDelete(''), Url::to(['delete', 'transaction_id' => $model->transaction_id]), ['class' => 'btn btn-danger btn-sm mb-1', 'title' => 'Delete', 'data-method' => 'post', 'data-confirm' => 'Anda yakin mau menghapus?']);
                    return $btn;
                },
                'width' => '10%',
                'contentOptions' => ['class' => 'text-center']
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
