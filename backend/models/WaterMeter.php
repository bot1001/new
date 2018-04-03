<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "water_meter".
 *
 * @property int $id
 * @property int $community 小区
 * @property int $building 楼宇
 * @property int $realestate_id 房屋编号，也是水表号
 * @property int $year 年
 * @property int $month 月
 * @property int $readout 水表读数
 * @property int $type 费表类型，0代表水表，1代表电表
 * @property int $property
 *
 * @property CommunityBasic $community0
 * @property CommunityBuilding $building0
 * @property CommunityRealestate $realestate
 */
class WaterMeter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'water_meter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['community', 'building', 'realestate_id', 'year', 'month', 'readout', 'type', 'property'], 'integer'],
            [['realestate_id', 'year', 'month', 'type', 'readout'], 'required'],
            [['realestate_id', 'year', 'month', 'readout', 'type'], 'unique', 'targetAttribute' => ['realestate_id', 'year', 'month', 'readout', 'type']],
            [['community'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityBasic::className(), 'targetAttribute' => ['community' => 'community_id']],
            [['building'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityBuilding::className(), 'targetAttribute' => ['building' => 'building_id']],
            [['realestate_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityRealestate::className(), 'targetAttribute' => ['realestate_id' => 'realestate_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'community' => '小区',
            'building' => '楼宇',
            'realestate_id' => '房号',
            'year' => '年份',
            'month' => '月份',
            'readout' => '读数',
            'type' => '表类',
            'property' => '备注',
			'name' => '房号'
        ];
    }
	
	//保存前自动插入
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert))
		{
			if($insert)
			{
				//插入新纪录时自动添加以下字段
				$this->property = date(time());
			}
			return true;
		}
		else{
			return false;
		}
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getC()
    {
        return $this->hasOne(CommunityBasic::className(), ['community_id' => 'community']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getB()
    {
        return $this->hasOne(CommunityBuilding::className(), ['building_id' => 'building']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getR()
    {
        return $this->hasOne(CommunityRealestate::className(), ['realestate_id' => 'realestate_id']);
    }
}
