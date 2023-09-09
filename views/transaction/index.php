<?php

use app\components\Helper;
use app\models\Bank;
use kartik\grid\GridView;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View;
use richardfan\widget\JSRegister;
use kartik\icons\Icon;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = 'Transaksi';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mst-bank-index pt-2">

    <?php
    if (is_null($dataProvider)) {
        echo 'Account belum ditambahkan';
    } else {
        $js = JSRegister::begin();
    ?>
        <script>
            $('main').on('click', '.modalButton', function() {
                $('#modal').modal('show')
                    .find('#modalContent')
                    .load($(this).attr('value'));
            });

            $('#change-bank').on('change', function(e) {
                console.log(this.value);
                window.location.href = location.pathname + '?id=' + this.value;
            });
        </script>
    <?php
        $js->end();

        Modal::begin([
            'id' => 'modal',
            'size' => 'modal-md',
            'title' => 'Transaksi',
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();

        echo Html::tag('h3', 'Account: ' . Html::dropDownList(null, $user->bank_active, Bank::getList(), ['id' => 'change-bank']));

        echo Html::button(Helper::faAdd('Transaction'), ['class' => 'btn mb-3 btn-success btn modalButton', 'value' => Url::to(['process'])]);

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
            'beforeHeader' => $beforeHeader,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'date_trx',
                    'format' => 'date',
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
                        $btn = Html::button(Icon::show('edit', ['class' => 'fa-sm']), ['class' => 'btn btn-primary btm-sm modalButton', 'value' => Url::to(['process', 'transaction_id' => $model->transaction_id])]);
                        $btn .= '&nbsp';
                        $btn .= Html::button(Icon::show('trash', ['class' => 'fa-sm']), ['class' => 'btn btn-danger btm-sm', 'data-method' => 'post', 'data-confirm' => 'Anda yakin mau menghapus?', 'value' => Url::to(['delete', 'transaction_id' => $model->transaction_id])]);
                        return $btn;
                    }
                ]
            ],
            'toolbar' => []
        ]);
    }
    ?>


</div>