<?php

namespace app\controllers;

use app\components\DBHelper;
use app\models\Bank;
use app\models\Label;
use app\models\Transaction;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TransactionController extends BaseController
{

    public function actionIndex($id = null)
    {
        $user = $this->user;
        $dataProvider = null;

        if ($id) {
            $bank = Bank::findOne(['bank_id' => $id, 'client_id' => $user->client_id]);
            if ($bank) {
                $user->bank_active = $bank->bank_id;
                $user->save();
            }
        }

        if (empty($user->bank_active)) {
            $bank = Bank::findOne(['client_id' => $user->client_id]);
            $user->bank_active = $bank->bank_id ?? null;
            $user->save();
        }
        $bankTotal = $user->defaultAccount->total;
        $query = Transaction::find()
            ->where(['transaction.bank_id' => $user->bank_active]);

        $mainQuery = clone $query;

        $modelTotal = $query->select(['transaction.label_id', new Expression('sum(amount) amount')])
            ->innerJoinWith(['label'])
            ->groupBy(['transaction.label_id'])
            ->all();

        $total = [];
        foreach ($modelTotal as $m) {
            $total[$m->label->name] = $m->amount;
        }

        if ($user->bank_active) {
            $dataProvider = new ActiveDataProvider([
                'query' => $mainQuery
                    ->orderBy(['date_trx' => SORT_DESC, 'transaction_id' => SORT_DESC]),
                'pagination' => [
                    'pageSize' => 20
                ],
                'sort' => false,
            ]);
        }

        return $this->render('index', [
            'total' => $total,
            'bankTotal' => $bankTotal,
            'user' => $user,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($transaction_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($transaction_id),
        ]);
    }

    public function actionProcess($transaction_id = null)
    {
        $user = $this->user;

        if ($transaction_id) {
            $model = $this->findModel($transaction_id);
            $model->category = $model->label->name ?? '';
            $model->amount = abs($model->amount);
        } else {
            $model = new Transaction();
            $model->date_trx = DBHelper::today();
            $model->is_income = 0; // pengeluaran
        }
        $labels = Label::find()
            ->where(['bank_id' => $user->bank_active])
            ->all();

        $category = $categoryIds = [];
        foreach ($labels as $m) {
            $category[$m->name] = $m->name;
            $categoryIds[$m->name] = $m->label_id;
        }

        $account = $user->defaultAccount;
        $bankTotal = $account->total;

        if ($model->load($this->request->post())) {
            $model->bank_id = $user->bank_active;

            $error = ActiveForm::validate($model);
            if ($error) {
                $this->response->format = Response::FORMAT_JSON;
                return $error;
            }

            $flag = true;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->category) {
                    if (empty($category[$model->category])) {
                        $modelLabel = new Label();
                        $modelLabel->bank_id = $user->bank_active;
                        $modelLabel->name = $model->category;
                        $flag = $modelLabel->save();

                        $model->label_id = $modelLabel->label_id;
                    } else {
                        $model->label_id = $categoryIds[$m->name];
                    }
                }

                $amount = $model->amount = abs($model->amount) * ($model->is_income ? 1 : -1);
                if ($model->isNewRecord) {
                    $bankTotal += $amount;
                } else {
                    $bankTotal -= ($model->oldAttributes['amount'] - $amount);
                }

                $flag = $flag && $model->save();
                $account->total = $bankTotal;
                $flag = $flag && $account->save();

                if ($flag) {
                    $transaction->commit();
                    return 1;
                }
            } catch (\Throwable $th) {
                echo '<pre>';
                print_r($th);
                die;
            }

            return 0;
        }

        return $this->renderAjax('process', [
            'model' => $model,
            'category' => $category,
        ]);
    }

    /**
     * Deletes an existing Transaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $transaction_id Transaction ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($transaction_id)
    {
        $this->findModel($transaction_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $transaction_id Transaction ID
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($transaction_id)
    {
        if (($model = Transaction::findOne(['transaction_id' => $transaction_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}