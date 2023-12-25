<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use richardfan\widget\JSRegister;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
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

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                    'inputOptions' => ['class' => 'col-lg-3 form-control'],
                    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password', [
                'options' => ['class' => 'form-group has-feedback'],
                'inputTemplate' => '{input}<div class="input-group-text cursor-pointer" id="toggle-password"><i class="fas fa-eye-slash"></i></div>',
                'template' => '{label}{beginWrapper}{input}{error}{endWrapper}',
                'wrapperOptions' => ['class' => 'input-group mb-3']
            ])
                ->passwordInput(['id' => 'input-password'])->label('Password') ?>

            <div class="form-group">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary block']) ?>
            </div>

            Don't have an account? <a href="/site/register">Sign up</a>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>