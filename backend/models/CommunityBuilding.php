<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "community_building".
 *
 * @property int $building_id （楼宇ID）
 * @property int $company （公司）
 * @property int $community_id （关联隶属小区ID）
 * @property string $building_name （楼宇名字/代号）
 * @property string $building_parent （父级）
 * @property int $creater 创建者
 * @property int $create_time 创建时间
 *
 * @property CommunityBasic $community
 * @property Company $company0
 * @property SysUser $creater0
 * @property CostRelation[] $costRelations
 * @property UserInvoice[] $userInvoices
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
            [['company', 'community_id', 'creater', 'create_time'], 'integer'],
            [['community_id', 'building_name'], 'required'],
            [['building_name', 'building_parent'], 'string', 'max' => 64],
            [['company', 'community_id', 'building_name'], 'unique', 'targetAttribute' => ['company', 'community_id', 'building_name']],
            [['community_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommunityBasic::className(), 'targetAttribute' => ['community_id' => 'community_id']],
            [['company'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company' => 'id']],
            [['creater'], 'exist', 'skipOnError' => true, 'targetClass' => SysUser::className(), 'targetAttribute' => ['creater' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'building_id' => '序号',
            'company' => '公司',
            'community_id' => '小区',
            'building_name' => '楼宇',
            'building_parent' => 'Building Parent',
            'creater' => '创建人',
            'create_time' => '创建时间',
        ];
    }

    public function beforeSave($insert)
	{
		if(parent::beforeSave($insert))
		{
			if($insert)
			{
				//插入新纪录时自动添加以下字段
				$this->creater = $_SESSION['user']['0']['id'];
				$this->create_time = date(time());
			}
			return true;
		}
		else{
			return false;
		}
	}
	
	public function getCom()
	{
	    return $this->hasOne(Company::className(), ['id' => 'company']);
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
