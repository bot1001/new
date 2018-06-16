<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cost_relation".
 *
 * @property int $id
 * @property int $community
 * @property int $building_id
 * @property int $realestate_id 房号_ID
 * @property int $cost_id 费项名称
 * @property int $from 启用时间
 * @property string $property
 *
 * @property CommunityBasic $community
 * @property CommunityBuilding $building
 * @property CommunityRealestate $realestate
 * @property CostName $cost
 */
class CostRelation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cost_relation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['community', 'building_id', 'realestate_id', 'cost_id', 'from'], 'required'],
            [['community', 'building_id', 'cost_id'], 'integer'],
            [['property'], 'string', 'max' => 50],
			[['from'], function($attr, $params) {
                if ($this->hasErrors()) return false;
                $datetime = $this->{$attr};
                $time = strtotime($datetime);
                // 验证时间格式是否正确
                if ($time === false) {
                    $this->addError($attr, '时间格式错误.');
                    return false;
                }
                // 将转换为时间戳后的时间赋值给time属性
                $this->{$attr} = $time;
                return true;
            }],
            [['community', 'building_id', 'realestate_id', 'cost_id'], 'unique', 'targetAttribute' => ['community', 'building_id', 'realestate_id', 'cost_id']],
            [['community'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityBasic::className(), 'targetAttribute' => ['community' => 'community_id']],
            [['building_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityBuilding::className(), 'targetAttribute' => ['building_id' => 'building_id']],
            [['realestate_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityRealestate::className(), 'targetAttribute' => ['realestate_id' => 'realestate_id']],
            [['cost_id'], 'exist', 'skipOnError' => true, 'targetClass' => CostName::className(), 'targetAttribute' => ['cost_id' => 'cost_id']],
        ];
    }
	
	public $price;
	public $p;
	public $number;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'community' => '小区',
            'building_id' => '楼宇',
            'number' => '单元',
            'realestate_id' => '房号',
            'cost_id' => '费项',
            'from' => '启用日期',
            'property' => '备注',
			'price' => '单价/元'
        ];
    }
	
	//将时间戳转换成时间然后在activeform输出
	public function afterFind()
    {
        parent::afterFind();
        $this->from = date('Y-m-d', $this->from);
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
        return $this->hasOne(CommunityBuilding::className(), ['building_id' => 'building_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getR()
    {
        return $this->hasOne(CommunityRealestate::className(), ['realestate_id' => 'realestate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCos()
    {
        return $this->hasOne(CostName::className(), ['cost_id' => 'cost_id']);
    }
}

