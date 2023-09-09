<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $user_id
 * @property string $username
 * @property string $password
 * @property int|null $bank_active
 * @property int $client_id
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Client $client
 * @property Bank $defaultAccount
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'client_id'], 'required'],
            [['password'], 'string'],
            [['bank_active', 'client_id'], 'integer'],
            [['username'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['client_id' => 'client_id']],
            [['bank_active'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::class, 'targetAttribute' => ['bank_active' => 'bank_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'username' => 'Username',
            'password' => 'Password',
            'bank_active' => 'Default Account',
            'client_id' => 'Client ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['client_id' => 'client_id']);
    }

    /**
     * Gets query for [[DefaultAccount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDefaultAccount()
    {
        return $this->hasOne(Bank::class, ['bank_id' => 'bank_active']);
    }
}
