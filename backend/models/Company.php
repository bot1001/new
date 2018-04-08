<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property string $name
 * @property int $creator
 * @property int $create_time
 * @property string $property
 *
 * @property SysUser $creator0
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'creator', 'create_time', 'property'], 'required'],
            [['creator', 'create_time'], 'integer'],
            [['name', 'property'], 'string', 'max' => 50],
            [['name'], 'unique'],
            [['creator'], 'exist', 'skipOnError' => true, 'targetClass' => SysUser::className(), 'targetAttribute' => ['creator' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名字',
            'creator' => '创建人',
            'create_time' => '创建时间',
            'property' => '备注',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreator0()
    {
        return $this->hasOne(SysUser::className(), ['id' => 'creator']);
    }
}
