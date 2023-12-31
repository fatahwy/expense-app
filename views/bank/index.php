<?php

use app\components\ButtonActionColumn;
use app\components\Helper;
use app\models\Bank;
use kartik\grid\GridView;
use yii\bootstrap5\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Bank Account';
$this->params['breadcrumbs'][] = $this->title;

Yii::$app->formatter->locale = $this->context->locale;
?>
<div class="bank-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Helper::faAdd('Account'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'show_label:boolean',
            [
                'attribute' => 'total',
                'format' => 'integer',
                'contentOptions' => ['class' => 'text-end'],
            ],
            [
                'class' => ButtonActionColumn::className(),
                'template' => '{update} {delete}',
                'urlCreator' => function ($action, Bank $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'bank_id' => $model->bank_id]);
                }
            ],
        ],
        'toolbar' => ''
    ]); ?>
</div>