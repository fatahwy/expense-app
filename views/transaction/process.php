<?php

use kartik\typeahead\TypeaheadBasic;
use richardfan\widget\JSRegister;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Html;
use kartik\number\NumberControl;

/** @var yii\web\View $this */
/** @var app\models\Transaction $model */
/** @var yii\widgets\ActiveForm $form */

$js = JSRegister::begin();
?>
<script>
    $('#trx-form').on('submit', function(e) {
        return false;
    });

    $('#trx-form').on('beforeSubmit', function(e) {
        e.preventDefault();

        $.post($('#trx-form').attr('action'), $('#trx-form').serialize(), function(data, status) {

            if (data) {
                console.log(data, status);
                $.pjax.reload('#kv-pjax-container', {
                    timeout: 3000
                });
            }
            $('#modal').modal('hide');
        });
    });

    $('.btn-reset').click(function(e) {
        e.preventDefault();
        $('#trx-form').trigger('reset');
    });
</script>
<?php $js->end() ?>

<div class="transaction-form">

    <?php $form = ActiveForm::begin(['id' => 'trx-form']); ?>

    <?= $form->field($model, 'date_trx')->textInput(['type' => 'date']) ?>

    <?= $form->field($model, 'is_income')->radioList(['Pengeluaran', 'Pemasukan']) ?>

    <?= $form->field($model, 'amount')->widget(NumberControl::classname(), [
        'maskedInputOptions' => [
            'groupSeparator' => '.',
            'radixPoint' => ',',
            'allowMinus' => false,
            'digits' => 0,
            'rightAlign' => false,
        ],
    ]); ?>

    <?= $form->field($model, 'category')->widget(TypeaheadBasic::classname(), [
        'data' => $category,
        'options' => ['placeholder' => 'Kategori'],
        'pluginOptions' => ['highlight' => true, 'minLength' => 0, 'allowClear' => true]
    ])
    ?>

    <?= $form->field($model, 'note')->textarea() ?>

    <div class="form-group text-end">
        <?= Html::button('Reset', ['class' => 'btn btn-secondary btn-reset']) ?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>