<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "label".
 *
 * @property int $label_id
 * @property int $bank_id
 * @property string $name
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Bank $bank
 * @property Transaction[] $transactions
 */
class Label extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'label';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bank_id', 'name'], 'required'],
            [['bank_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::class, 'targetAttribute' => ['bank_id' => 'bank_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'label_id' => 'Label ID',
            'bank_id' => 'Bank ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Bank]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBank()
    {
        return $this->hasOne(Bank::class, ['bank_id' => 'bank_id']);
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::class, ['label_id' => 'label_id']);
    }
}
