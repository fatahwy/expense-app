<?php

use app\components\Helper;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Bank $model */
/** @var ActiveForm $form */
?>

<div class="bank-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'show_label')->checkbox() ?>

    <div class="form-group text-end">
        <?= Html::submitButton($model->isNewRecord ? Helper::faSave() : Helper::faUpdate(), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>