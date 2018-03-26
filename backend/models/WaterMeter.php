<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "water_meter".
 *
 * @property int $id
 * @property int $realestate_id 房屋编号
 * @property int $year 年
 * @property int $month 月
 * @property int $readout 水表读数
 * @property string $property 备注
 */
class WaterMeter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'water_meter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['community', 'building', 'realestate_id', 'year', 'month', 'readout', 'property'], 'integer'],
           [['realestate_id', 'year', 'month'], 'required'],
           [['community', 'building', 'realestate_id', 'year', 'month'], 'unique', 'targetAttribute' => ['community', 'building', 'realestate_id', 'year', 'month']],
           [['community'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityBasic::className(), 'targetAttribute' => ['community' => 'community_id']],
           [['building'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityBuilding::className(), 'targetAttribute' => ['building' => 'building_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '序号',
			'community' => '小区',
			'building' => '楼宇',
            'realestate_id' => '关联房屋',
            'year' => '年份',
            'month' => '月份',
            'readout' => '读数',
            'property' => '创建时间',
			'building' => '楼宇',
			'name' => '房号',
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
	
	//关联房号
	public function getR()
    {
        return $this->hasOne(CommunityRealestate::className(), ['realestate_id' => 'realestate_id']);
    }
	
	//关联小区
	public function getC()
   {
       return $this->hasOne(CommunityBasic::className(), ['community_id' => 'community']);
   }
   /**
    * 关联楼宇
    */
   public function getB()
   {
       return $this->hasOne(CommunityBuilding::className(), ['building_id' => 'building']);
   }
}
