<?php

namespace app\controllers;

use app\components\Helper;
use app\models\Account;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class MemberController extends BaseController
{

    public function actionIndex()
    {
        $user = $this->user;
        $query = Account::find()
            ->where(['client_id' => $user->client_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $user = $this->user;
        $model = new Account();
        $model->scenario = 'create';

        if ($model->load($this->request->post())) {
            $model->client_id = $user->client_id;

            $password = trim($model->password);
            $model->password = $password > 0 ? md5($password) : null;

            if ($model->save()) {
                Helper::flashSucceed();
                return $this->redirect(['index']);
            }
            Helper::flashFailed(Html::errorSummary($model));
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->password = null;

        if ($model->load($this->request->post())) {
            $password = trim($model->password);
            $model->username = $model->oldAttributes['username'];
            $model->password = $password > 0 ? md5($password) : $model->oldAttributes['password'];

            if ($model->save()) {
                Helper::flashSucceed();
                return $this->redirect(['index']);
            }
            Helper::flashFailed(Html::errorSummary($model));
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        Helper::flashSucceed();
        $model->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($user_id)
    {
        $model = Account::findOne(['user_id' => $user_id, 'client_id' => $this->user->client_id]);
        if ($model) {
            return $model;
        }

        throw new NotFoundHttpException('User not found');
    }
}
