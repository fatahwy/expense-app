<?php

use richardfan\widget\JSRegister;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;

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

<h1><?= Html::encode($this->title) ?></h1>

<p>Please fill out the following fields to register:</p>

<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-md-5">
        <?= $form->field($model, 'username') ?>

        <?= $form->field($model, 'password', [
            'options' => ['class' => 'form-group has-feedback'],
            'inputTemplate' => '{input}<div class="input-group-text cursor-pointer" id="toggle-password"><i class="fas fa-eye-slash"></i></div>',
            'template' => '{label}{beginWrapper}{input}{error}{endWrapper}',
            'wrapperOptions' => ['class' => 'input-group mb-3']
        ])
            ->passwordInput(['id' => 'input-password'])->label('Password') ?>

        <?= $form->field($model, 'email') ?>

        <div class="form-group">
            <?= Html::submitButton('Register', ['class' => 'btn btn-primary']) ?>
        </div>

        Have an account already? <a href="/site/login">Log in</a>
    </div>
</div>


<?php ActiveForm::end(); ?>