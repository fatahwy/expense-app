<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Bank $model */

$this->title = 'Update Bank: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Account', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bank-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
