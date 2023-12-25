<?php

use app\components\Helper;
use kartik\select2\Select2;
use kartik\tabs\TabsX;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'Report';
$this->params['breadcrumbs'][] = $this->title;

$url = ['index'];
foreach ($model->toArray() as $key => $value) {
    $url["FilterForm[$key]"] = $value;
}
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php
$form = ActiveForm::begin(['action' => Url::toRoute('index'), 'method' => 'GET']);
echo $form->field($model, 'type')->hiddenInput()->label(false);
?>

<div class="row mb-3">
    <div class="col-md-2">
        <?= $form->field($model, 'year')->widget(Select2::classname(), ['data' => $listYear]); ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'bank_id')->widget(Select2::classname(), ['data' => $listBank]); ?>
    </div>
    <div class="col-md-2 d-flex align-items-center">
        <?= Html::submitButton(Helper::faSearch('Filter'), ['class' => 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?= TabsX::widget([
    'position' => TabsX::POS_ABOVE,
    'bordered' => true,
    'items' => [
        [
            'active' => $type == 'summary',
            'label' => 'Summary',
            'url' => Url::to(array_merge($url, ['FilterForm[type]' => 'summary'])),
            'content' => $this->render('_summary', array_merge($data, ['type' => 'summary'])),
        ],
        [
            'active' => $type == 'cashflow',
            'label' => 'Cashflow',
            'url' => Url::to(array_merge($url, ['FilterForm[type]' => 'cashflow'])),
            'content' => $this->render('_cashflow', array_merge($data, ['type' => 'cashflow'])),
        ],
    ],
]);
?>