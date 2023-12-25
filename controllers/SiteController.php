<?php

namespace app\controllers;

use app\components\Helper;
use app\models\Account;
use app\models\Client;
use Yii;
use app\models\LoginForm;
use yii\bootstrap5\Html;
use yii\web\Controller;

class SiteController extends Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

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

    public function actionRegister()
    {
        $model = new Account();
        $model->scenario = 'register';

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $client = new Client();
                $client->name = $model->username;
                $client->email = $model->email;
                $client->save();

                $model->password = md5(trim($model->password));
                $model->role = Helper::ROLE_ADMIN;
                $model->client_id = $client->client_id;

                if ($model->save()) {
                    $this->sendConfirmationEmail($model->email);
                    $transaction->commit();
                    Helper::flashSucceed('Registration successful, please check your email!.');
                    return $this->refresh();
                }
                $model->password = null;
                Helper::flashFailed(Html::errorSummary([$model, $client]));
            } catch (\Throwable $th) {
                Helper::flashFailed('Something error.' . $th->getMessage());
            }
        }

        return $this->render('register', ['model' => $model]);
    }

    public function actionConfirm($email)
    {
        $user = Account::find()
            ->innerJoinWith(['client'])
            ->where(['email' => $email])
            ->one();

        if (!$user) {
            Helper::flashFailed('User not found.');
            return $this->redirect(['login']);
        }
        $client =$user->client;

        if ($client->verified_at) {
            Helper::flashFailed('User has verified. Please login');
            return $this->redirect(['login']);
        }

        $client->verified_at = Helper::now();
        $client->save();

        Helper::flashSucceed('User confirmed successfully!');
        return $this->redirect(['site/login']);
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    protected function sendConfirmationEmail($email)
    {
        $params = Yii::$app->params;
        $confirmationLink = Yii::$app->urlManager->createAbsoluteUrl(['site/confirm', 'email' => $email]);
        $message = Yii::$app->mailer->compose()
            ->setFrom($params['senderEmail'])
            ->setTo($email)
            ->setSubject('Confirmation Email')
            ->setHtmlBody("Click the following link to confirm your account: <a href='$confirmationLink'>confirm</a>");

        return $message->send();
    }
}
