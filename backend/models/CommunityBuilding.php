<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "community_building".
 *
 * @property int $building_id （楼宇ID）
 * @property int $community_id （关联隶属小区ID）
 * @property string $building_name （楼宇名字/代号）
 * @property string $building_parent （父级）
 * @property int $creater （创建人）
 * @property int $create_time （创建时间）
 *
 * @property SysUser $creater0
 * @property CostRelation[] $costRelations
 * @property WaterMeter[] $waterMeters
 */
class CommunityBuilding extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'community_building';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['community_id', 'building_name', 'creater', 'create_time'], 'required'],
            [['community_id', 'creater', 'create_time'], 'integer'],
            [['building_name', 'building_parent'], 'string', 'max' => 64],
            [['building_name', 'community_id'], 'unique', 'targetAttribute' => ['building_name', 'community_id']],
            [['creater'], 'exist', 'skipOnError' => true, 'targetClass' => SysUser::className(), 'targetAttribute' => ['creater' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'building_id' => '编号',
            'community_id' => '小区',
            'building_name' => '楼宇',
            'building_parent' => '上级',
            'creater' => '创建人',
            'create_time' => '创建时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreater0()
    {
        return $this->hasOne(SysUser::className(), ['id' => 'creater']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCostRelations()
    {
        return $this->hasMany(CostRelation::className(), ['building_id' => 'building_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getW()
    {
        return $this->hasMany(WaterMeter::className(), ['building' => 'building_id']);
    }
	
	public function getUserInvoices()
    {
        return $this->hasMany(UserInvoice::className(), ['building_id' => 'building_id']);
    }
	
	public function getC()
    {
        return $this->hasOne(CommunityBasic::className(), ['community_id' => 'community_id']);
    }
}
