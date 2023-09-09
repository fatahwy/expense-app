<?php

namespace app\controllers;

use app\models\Bank;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Transaction;
use yii\data\ActiveDataProvider;

class SiteController extends BaseController
{

    public function actionIndex()
    {
        $user = $this->user;
        $dataProvider = null;

        if(empty($user->default_account)) {
            $bank = Bank::findOne(['client_id' => $user->client_id]);
            $user->default_account = $bank->bank_id ?? null;
        }

        if ($user->default_account) {
            $query = Transaction::find()
                ->where(['bank_id' => $user->default_account])
                ->cache();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                //            'pagination' => [
                //                'pageSize' => 1
                //            ],
                //            'sort' => [
                //                'defaultOrder' => [
                //                    'id_branch' => SORT_DESC,
                //                ]
                //            ],
            ]);
        }

        return $this->render('index', [
            'user' => $user,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
