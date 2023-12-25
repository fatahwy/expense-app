<?php

namespace app\controllers;

use app\components\Helper;
use app\models\Bank;
use app\models\FilterForm;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class ReportController extends BaseController
{

    public function actionIndex()
    {
        $req = $this->request;
        $idClient = $this->user->client_id;

        $listYear = $this->listYear($idClient);
        $listBank = ArrayHelper::map(Bank::findAll(['client_id' => $idClient]), 'bank_id', 'name');

        $model = new FilterForm('summary');
        $model->load($req->get());
        $model->year = $listYear[$model->year] ?? array_key_first($listYear);
        $model->bank_id = $model->bank_id ?: array_key_first($listBank);

        $formatter = Yii::$app->formatter;
        $formatter->locale = $this->locale;

        $data = [
            'formatter' => $formatter,
            'model' => $model,
        ];

        switch ($model->type) {
            case 'cashflow':
                $models = (new Query())
                    ->select(['trx.is_income', 'month(trx.date_trx) month', 'sum(abs(trx.amount)) amount'])
                    ->from(['trx' => 'transaction'])
                    ->innerJoin(['l' => 'label'], 'trx.label_id=l.label_id')
                    ->where(['trx.bank_id' => $model->bank_id])
                    ->andWhere(['year(trx.date_trx)' => $model->year])
                    ->groupBy(['trx.is_income', 'month(trx.date_trx)'])
                    ->all();

                $summary = [
                    'labels' => []
                ];

                foreach (Helper::getMonths($this->locale) as $value) {
                    $summary['labels'][] = $value;
                    $summary['backgroundColor'][0][] = '#7BD3EA';
                    $summary['backgroundColor'][1][] = '#F6D6D6';
                    $summary['data'][0][] = 0;
                    $summary['data'][1][] = 0;
                    $summary['label'][0] = 'Income';
                    $summary['label'][1] = 'Expense';
                }

                foreach ($models as $m) {
                    $summary['data'][$m['is_income'] ? 0 : 1][$m['month']] = $m['amount'];
                }

                $data['summary'] = $summary;
                break;
            default: // summary
                $models = (new Query())
                    ->select(['trx.is_income', 'l.name', 'sum(abs(trx.amount)) amount'])
                    ->from(['trx' => 'transaction'])
                    ->innerJoin(['l' => 'label'], 'trx.label_id=l.label_id')
                    ->where(['trx.bank_id' => $model->bank_id])
                    ->andWhere(['year(trx.date_trx)' => $model->year])
                    ->groupBy(['trx.is_income', 'l.name'])
                    ->all();

                $income = [];
                $expense = [];

                foreach ($models as $m) {
                    if ($m['is_income']) {
                        $income['total'] =  ($income['total'] ?? 0) + $m['amount'];
                        $income['labels'][] = $m['name'];
                        $income['data'][] = $m['amount'];
                        $income['backgroundColor'][] = Helper::getColor();
                    } else {
                        $expense['total'] =  ($expense['total'] ?? 0) + $m['amount'];
                        $expense['labels'][] = $m['name'];
                        $expense['data'][] = $m['amount'];
                        $expense['backgroundColor'][] = Helper::getColor();
                    }
                }

                $data = array_merge($data, [
                    'income' => $income,
                    'expense' => $expense,
                ]);
                break;
        }

        return $this->render('index', [
            'data' => $data,
            'listYear' => $listYear,
            'listBank' => $listBank,
            'model' => $model,
            'type' => $model->type,
        ]);
    }

    private function listYear($idClient)
    {
        $listYear = Yii::$app->cache->getOrSet("$idClient", function () use ($idClient) {
            $modelListYear = (new Query())
                ->select([new Expression('distinct year(date_trx) year')])
                ->from(['trx' => 'transaction'])
                ->innerJoin(['b' => 'bank'], 'b.bank_id=trx.bank_id')
                ->where(['client_id' => $idClient])
                ->all();

            $listYear = [];
            foreach ($modelListYear as $m) {
                $listYear[$m['year']] = $m['year'];
            }

            return $listYear;
        }, 500);

        return $listYear;
    }
}
