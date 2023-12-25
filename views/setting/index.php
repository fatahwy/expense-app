<?php

use app\components\Helper;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Client $model */

$this->title = 'Setting';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="setting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'email')->textInput(['type' => 'email']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'locale')->dropDownList(['id-ID' => 'IDR', 'en-US' => 'USD']) ?>
        </div>
    </div>

    <div class="form-group text-end">
        <?= Html::submitButton(Helper::faUpdate(), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>