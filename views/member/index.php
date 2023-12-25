<?php

use app\components\ButtonActionColumn;
use app\components\Helper;
use kartik\grid\GridView;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Member';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Helper::faAdd('Member'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
            [
                'attribute' => 'role',
                'value' => function ($m) {
                    return $m->role == Helper::ROLE_ADMIN ? 'Admin' : 'Member';
                }
            ],
            [
                'class' => ButtonActionColumn::className(),
                'template' => '{update} {delete}',
            ],
        ],
        'toolbar' => ''
    ]); ?>
</div>