<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Member $model */

$this->title = 'Update Member: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Member', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bank-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
