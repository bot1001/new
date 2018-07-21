<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sms".
 *
 * @property int $id
 * @property string $sign_name （模板名称）
 * @property string $sms （模板编号）
 * @property int $count （变量数量）
 * @property int $creator （创建人ID）
 * @property int $create_time （创建时间）
 * @property string $property （备注）
 */
class Sms extends \yii\db\ActiveRecord
{
    public $name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sign_name', 'sms', 'count', 'status', 'property'], 'required'],
            [['count', 'creator', 'create_time'], 'integer', 'min' => '1'],
            [['status'], 'integer'],
            [['sign_name', 'sms', 'property'], 'string', 'max' => 50],
            [['sign_name', 'sms', 'count'], 'unique', 'targetAttribute' => ['sign_name', 'sms', 'count']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sign_name' => '模板名称',
            'sms' => '模板编号',
            'count' => '变量总数',
            'status' => '状态',
            'creator' => '创建人',
            'create_time' => '创建时间',
            'property' => '备注',
        ];
    }

    //数据保存前自动插入
    function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($insert)
            {
                //插入新纪录时自动添加以下字段
                $this->create_time = time();
                $this->creator = $_SESSION['user']['0']['id'];
            }
            return true;
        }
        else{
            return false;
        }
    }

    //同系统用户建立关联
    public function getSys()
    {
        return $this->hasOne(SysUser::className(), ['id' => 'creator']);
    }
}
