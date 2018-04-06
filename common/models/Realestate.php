<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "community_realestate".
 *
 * @property int $realestate_id （房屋ID）
 * @property int $community_id （小区ID）
 * @property int $building_id （楼宇ID）
 * @property string $room_number （单元编号）
 * @property string $room_name （房号/单元名称）
 * @property string $owners_name （业主姓名） 
 * @property string $owners_cellphone （业主手机号码）
 * @property double $acreage （房屋面积）
 * @property int $finish 封顶时间
 * @property int $decoration 装修时间
 * @property int $delivery 交房时间
 * @property int $inherit 备用字段
 * @property string $orientation 房屋朝向
 * @property string $property 备注
 * @property int $commencement 开工时间
 *
 * @property CostRelation[] $costRelations
 * @property WaterMeter[] $waterMeters
 */
class Realestate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'community_realestate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['community_id', 'building_id', 'room_number', 'room_name', 'owners_name', 'owners_cellphone'], 'required'],
            [['community_id', 'building_id', 'finish', 'decoration', 'delivery', 'inherit', 'commencement'], 'integer'],
            [['acreage'], 'number'],
            [['room_number', 'room_name', 'owners_name'], 'string', 'max' => 64],
            [['owners_cellphone'], 'string', 'max' => 12],
            [['orientation', 'property'], 'string', 'max' => 50],
            [['community_id', 'building_id', 'room_name', 'room_number'], 'unique', 'targetAttribute' => ['community_id', 'building_id', 'room_name', 'room_number']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'realestate_id' => 'Realestate ID',
            'community_id' => '小区',
            'building_id' => '楼宇',
            'room_number' => '单元',
            'room_name' => '房号',
            'owners_name' => '姓名',
            'owners_cellphone' => '手机号码',
            'acreage' => '面积',
            'finish' => '交付时间',
            'decoration' => '装修时间',
            'delivery' => '交房时间',
            'inherit' => 'Inherit',
            'orientation' => '房屋朝向',
            'property' => 'Property',
            'commencement' => 'Commencement',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCostRelations()
    {
        return $this->hasMany(CostRelation::className(), ['realestate_id' => 'realestate_id']);
    }
	
	//获取关联小区
	public function getC()
    {
        return $this->hasOne(CommunityBasic::className(), ['community_id' => 'community_id']);
    }
	
	//获取关联楼宇
	public function getB()
    {
        return $this->hasOne(CommunityBuilding::className(), ['building_id' => 'building_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWaterMeters()
    {
        return $this->hasMany(WaterMeter::className(), ['realestate_id' => 'realestate_id']);
    }
}
