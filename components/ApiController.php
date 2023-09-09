<?php

namespace app\components;

use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;

/**
 * Default controller for the `api` module
 */
class ApiController extends Controller {


    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
    }

}
