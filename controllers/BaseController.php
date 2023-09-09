<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class BaseController extends Controller
{

    public $user;

    public function behaviors()
    {
        $accessControl = [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'logout' => ['post'],
                ],
            ],
        ];

        return array_merge(parent::behaviors(), $accessControl);
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {
        $user = Yii::$app->user->identity;
        $this->user = $user;

        if (parent::beforeAction($action)) {
            // $this->doLog($action);
            return true;
        }

        return false;
    }

    public function test($object = [])
    {
        $user = Yii::$app->user->identity;
        if ($user->user_id == 1) {
            echo '<pre>';
            print_r($object);
            die;
        }
    }
}
