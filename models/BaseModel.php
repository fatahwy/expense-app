<?php

namespace app\models;

use yii\db\ActiveRecord;

class BaseModel extends ActiveRecord {

    public function behaviors() {
        return [
            'softDelete' => [
                'class' => 'amnah\yii2\behaviors\SoftDelete',
                // these are the default values, which you can omit
                'attribute' => 'delete_time',
                'value' => null, // this is the same format as in TimestampBehavior
                'safeMode' => true, // this processes '$model->delete()' calls as soft-deletes
            ],
        ];
    }

    public static function find() {
        $tableScheme = self::getTableSchema();
        $tableName = $tableScheme->name;

        $model = parent::find()->where([$tableName . '.delete_time' => null]);

        return $model;
    }

}
