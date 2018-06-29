<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "house_info".
 *
 * @property int $house_id
 * @property int $realestate
 * @property string $name
 * @property string $phone
 * @property string $IDcard
 * @property int $creater
 * @property int $create
 * @property int $update
 * @property int $status
 * @property string $address
 * @property int $politics
 * @property string $property
 *
 * @property CommunityRealestate $realestate0
 */
class HouseInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'house_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['realestate', 'name',   'phone'], 'required'],
            [['realestate', 'creater', 'create', 'update', 'status', 'politics'], 'integer'],
            [['name'], 'string', 'max' => 32],
            //[['phone'], 'string', 'max' => 16],
            [['IDcard'], 'string', 'length' => 18],
            [['address', 'property'], 'string', 'max' => 50],
            [['realestate', 'name', 'IDcard'], 'unique', 'targetAttribute' => ['realestate', 'name', 'IDcard']],
            [['realestate'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityRealestate::className(), 'targetAttribute' => ['realestate' => 'realestate_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'house_id' => '序号',
            'realestate' => '房号',
            'name' => '名字',
            'phone' => '手机号码',
            'IDcard' => '身份证',
            'creater' => '创建人',
            'create' => '创建时间',
            'update' => '更新时间',
            'status' => '状态',
            'address' => '地址',
            'politics' => '政治面貌',
            'property' => '备注',
        ];
    }	
}
