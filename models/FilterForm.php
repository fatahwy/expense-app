<?php

namespace app\models;

use yii\base\Model;

class FilterForm extends Model
{

    public $type;
    public $year;
    public $bank_id;

    public function __construct($fieldName = null)
    {
        parent::__construct();
        $this->type = $fieldName;
    }

    public function rules()
    {
        return [
            [['type', 'year'], 'string'],
            [['bank_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'bank_id' => 'Bank Account',
        ];
    }
}
