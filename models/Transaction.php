<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property int $transaction_id
 * @property int $is_income
 * @property int $bank_id
 * @property float $amount
 * @property int|null $label_id
 * @property string|null $note
 * @property string $date_trx
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Bank $bank
 * @property Label $label
 */
class Transaction extends \yii\db\ActiveRecord
{
    public $category;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_income', 'bank_id', 'amount', 'date_trx', 'category'], 'required'],
            [['is_income', 'bank_id', 'label_id'], 'integer'],
            [['amount'], 'number'],
            [['date_trx'], 'safe'],
            [['note', 'category'], 'string', 'max' => 255],
            [['bank_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bank::class, 'targetAttribute' => ['bank_id' => 'bank_id']],
            [['label_id'], 'exist', 'skipOnError' => true, 'targetClass' => Label::class, 'targetAttribute' => ['label_id' => 'label_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'transaction_id' => 'Transaction ID',
            'is_income' => 'Type',
            'bank_id' => 'Bank ID',
            'amount' => 'Amount',
            'label_id' => 'Category',
            'note' => 'Note',
            'date_trx' => 'Date Input',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function type($id = null)
    {
        $list = ['Pengeluaran', 'Pemasukan'];

        if (empty($list[$id])) {
            return $list;
        }

        return $list[$id];
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
     * Gets query for [[Label]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLabel()
    {
        return $this->hasOne(Label::class, ['label_id' => 'label_id']);
    }
}
