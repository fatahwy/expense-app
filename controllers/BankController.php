<?php

namespace app\controllers;

use app\models\Bank;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class BankController extends BaseController
{

    public function actionIndex()
    {
        $user = $this->user;
        $query = Bank::find()
            ->where(['client_id' => $user->client_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'bank_id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $user = $this->user;
        $model = new Bank();

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->client_id = $user->client_id;

            if ($model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($bank_id)
    {
        $model = $this->findModel($bank_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($bank_id)
    {
        $this->findModel($bank_id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($bank_id)
    {
        if (($model = Bank::findOne(['bank_id' => $bank_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
