<?php

namespace app\models;

use yii\base\NotSupportedException;

class User extends Account implements \yii\web\IdentityInterface
{
    public $authKey;
    public $accessToken;

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $model = self::find()
            ->innerJoinWith(['client'])
            ->where(['user_id' => $id])
            ->andWhere(['not', ['verified_at' => null]])
            ->one();

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $model = self::find()
            ->innerJoinWith(['client'])
            ->where(['username' => $username])
            ->andWhere(['not', ['verified_at' => null]])
            ->one();

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }
}
