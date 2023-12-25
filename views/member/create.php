<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Bank $model */

$this->title = 'Create Member';
$this->params['breadcrumbs'][] = ['label' => 'Member', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
