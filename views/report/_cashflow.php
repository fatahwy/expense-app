<?php

use app\components\ChartHelper;
use yii\bootstrap5\Html;
?>

<div class="row">
    <div class="col-md-12">
        <div class="">
            <?= ChartHelper::getBar($summary['labels'] ?? [], $summary['label'] ?? [], $summary['backgroundColor'] ?? [], $summary['data'] ?? []) ?>
        </div>
    </div>
    <?php if (!empty($summary['labels'])) { ?>
        <div class="col-md-12 mt-5 table-responsive">
            <table class="table table-condensed">
                <tr>
                    <th>Category</th>
                    <?php
                    foreach (($summary['labels'] ?? []) as $m) {
                        echo Html::tag('th', $m, ['class' => 'text-center']);
                    }
                    echo Html::tag('th', 'Total', ['class' => 'text-center']);
                    ?>
                </tr>
                <tr>
                    <td>Income</td>
                    <?php
                    $total = 0;
                    foreach (($summary['data'][0] ?? []) as $key => $m) {
                        $total += $m;
                        echo Html::tag('td', $formatter->asInteger($m), ['class' => 'text-end']);
                    }
                    echo Html::tag('td', $formatter->asInteger($total), ['class' => 'text-end']);
                    ?>
                </tr>
                <tr>
                    <td>Expense</td>
                    <?php
                    $total = 0;
                    foreach (($summary['data'][1] ?? []) as $key => $m) {
                        $total += $m;
                        echo Html::tag('td', $formatter->asInteger($m), ['class' => 'text-end']);
                    }
                    echo Html::tag('td', $formatter->asInteger($total), ['class' => 'text-end']);
                    ?>
                </tr>
                <tr>
                    <td>Total</td>
                    <?php
                    $total = 0;
                    foreach (($summary['data'][0] ?? []) as $key => $m) {
                        $v = $m - ($summary['data'][1][$key] ?? 0);
                        $total += $v;
                        echo Html::tag('td', $formatter->asInteger($v), ['class' => 'text-end']);
                    }
                    echo Html::tag('td', $formatter->asInteger($total), ['class' => 'text-end']);
                    ?>
                </tr>
            </table>
        </div>
    <?php } ?>
</div>