<?php

namespace app\models;

use app\components\Helper;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bank".
 *
 * @property int $bank_id
 * @property int $client_id
 * @property string $name
 * @property float|null $total
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property int|null $delete_time
 *
 * @property Client $client
 * @property Label[] $labels
 * @property Transaction[] $transactions
 * @property User[] $users
 */
class Bank extends BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'name'], 'required'],
            [['client_id', 'delete_time', 'show_label'], 'integer'],
            [['total'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['client_id' => 'client_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bank_id' => 'Bank ID',
            'client_id' => 'Client ID',
            'name' => 'Name',
            'total' => 'Total',
            'show_label' => 'Show Summary Category Transaction',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'delete_time' => 'Delete Time',
        ];
    }

    public static function getList()
    {
        $model = self::find()
            ->andWhere(['client_id' => Helper::identity()->client_id])
            ->all();

        return ArrayHelper::map($model, 'bank_id', 'name');
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
     * Gets query for [[Labels]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLabels()
    {
        return $this->hasMany(Label::class, ['bank_id' => 'bank_id']);
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ['bank_id' => 'bank_id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::class, ['default_account' => 'bank_id']);
    }
}
