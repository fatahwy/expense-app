<?php

use app\components\Helper;
use kartik\typeahead\TypeaheadBasic;
use richardfan\widget\JSRegister;
use yii\bootstrap5\Html;
use kartik\number\NumberControl;
use yii\bootstrap5\ActiveForm;
use yii\jui\DatePicker;

/** @var yii\web\View $this */
/** @var app\models\Transaction $model */
/** @var ActiveForm $form */

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

    <?= $form->field($model, 'date_trx')->widget(DatePicker::classname(), [
        'options' => ['class' => 'form-control'],
        'dateFormat' => 'yyyy-MM-dd',
    ]); ?>

    <?= $form->field($model, 'is_income')->radioList(['Expense', 'Income']) ?>

    <?= $form->field($model, 'category')->widget(TypeaheadBasic::classname(), [
        'data' => $category,
        'options' => ['placeholder' => 'Category'],
        'pluginOptions' => ['highlight' => true, 'minLength' => 0, 'allowClear' => true]
    ])
    ?>

    <?= $form->field($model, 'amount')->widget(NumberControl::classname(), [
        'displayOptions' => ['type' => 'tel'],
        'maskedInputOptions' => [
            'groupSeparator' => $this->context->locale == 'id-ID' ? '.' : ',',
            'radixPoint' => $this->context->locale == 'id-ID' ? ',' : '.',
            'allowMinus' => false,
            'digits' => 0,
            'rightAlign' => false,
        ],
    ]); ?>

    <?= $form->field($model, 'note')->textarea() ?>

    <div class="form-group text-end">
        <?= Html::button(Helper::faReset(), ['class' => 'btn btn-secondary btn-reset']) ?>
        <?= Html::submitButton(Helper::faSave(), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>