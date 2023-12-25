<?php

use app\components\Helper;
use richardfan\widget\JSRegister;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var yii\web\View $this */
/** @var app\models\Account $model */
/** @var ActiveForm $form */

$isNewRecord = $model->isNewRecord;
JSRegister::begin()
?>
<script>
    $('#toggle-password').click(function(e) {
        const isShow = $('#toggle-password').find('.fa-eye-slash').length;

        if (isShow) {
            $('#toggle-password').find('.fa-eye-slash').addClass('fa-eye').removeClass('fa-eye-slash');
        } else {
            $('#toggle-password').find('.fa-eye').addClass('fa-eye-slash').removeClass('fa-eye');
        }

        $('#input-password').attr('type', isShow ? 'text' : 'password');
    })
</script>
<?php JSRegister::end() ?>

<div class="member-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'disabled' => !$isNewRecord]) ?>

    <?= $form->field($model, 'password', [
        'options' => ['class' => 'form-group has-feedback'],
        'inputTemplate' => '{input}<div class="input-group-text cursor-pointer" id="toggle-password"><i class="fas fa-eye-slash"></i></div>',
        'template' => '{label}{beginWrapper}{input}{error}{endWrapper}',
        'wrapperOptions' => ['class' => 'input-group mb-3']
    ])
        ->passwordInput(['id' => 'input-password'])->label($isNewRecord ? 'Password' : 'New Password (*leave blank if no changes are required)') ?>

    <?= $form->field($model, 'role')->radioList([Helper::ROLE_ADMIN => 'Admin', Helper::ROLE_MEMBER => 'Member']) ?>

    <div class="form-group text-end">
        <?= Html::submitButton($isNewRecord ? Helper::faSave() : Helper::faUpdate(), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>