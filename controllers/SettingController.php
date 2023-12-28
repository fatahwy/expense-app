<?php

namespace app\controllers;

use app\components\Helper;
use app\models\Client;
use yii\bootstrap5\Html;

class SettingController extends BaseController
{

    public function actionIndex()
    {
        $model = Client::findOne(['client_id' => $this->user->client_id]);
        $modelInput = new Client();

        if ($modelInput->load($this->request->post())) {
            $model->locale = $modelInput->locale;

            if ($model->save()) {
                Helper::flashSucceed();
                return $this->redirect(['index']);
            }
            Helper::flashFailed(Html::errorSummary($model));
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
