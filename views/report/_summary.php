<?php

use app\components\ChartHelper;

?>
<div class="row">
    <div class="col-md-6">
        <h6>Income: <?= $formatter->asCurrency($income['total'] ?? 0) ?></h6>
        <div>
            <?= ChartHelper::getPie($income['labels'] ?? [], $income['backgroundColor'] ?? [], $income['data'] ?? []) ?>
        </div>
    </div>
    <div class="col-md-6">
        <h6>Expense: <?= $formatter->asCurrency($expense['total'] ?? 0) ?></h6>
        <div>
            <?= ChartHelper::getPie($expense['labels'] ?? [], $expense['backgroundColor'] ?? [], $expense['data'] ?? []) ?>
        </div>
    </div>

</div>