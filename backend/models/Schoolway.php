<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "schoolway".
 *
 * @property integer $id
 * @property string $name
 * @property string $property
 *
 * @property EpmsUserinfo[] $epmsUserinfos
 */
class Schoolway extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'schoolway';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'property'], 'string', 'max' => 50],
            [['name'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '就学方式',
            'property' => 'Property',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEpmsUserinfos()
    {
        return $this->hasMany(EpmsUserinfo::className(), ['GetSchool' => 'id']);
    }
}
