<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cost_name".
 *
 * @property integer $cost_id
 * @property string $cost_name
 * @property string $price
 * @property integer $inv
 * @property string $property
 *
 * @property CostRelation[] $costRelations
 * @property CommunityRealestate[] $realestates
 */
class CostName extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cost_name';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price','cost_name','level','inv'], 'required'],
            [['price'], 'number'],
			//[['cost_name', 'level', 'price'],'unique'],
			[['create_time', 'update_time'], 'safe'],
            [['inv','parent','level', 'builder'], 'integer'],
            [['cost_name', 'property'], 'string', 'max' => 50],
            [['cost_name', 'price', 'level', 'inv'], 'unique', 'targetAttribute' => ['cost_name', 'price', 'level', 'inv'], 'message' => '重复操作，请勿提交'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cost_id' => '序号',
            'cost_name' => '名称',
			'level' => '层级',
            'price' => '单价/元',
            'inv' => '固定费用',
			'parent' => '类别',
			'create_time' => '创建时间',
			'update_time' => '更新时间',
			'builder' => '创建者',
            'property' => '备注',
        ];
    }
	
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert))
		{
			if($insert)
			{
				//插入新纪录时自动添加以下字段
				$this->builder = $_SESSION['user']['id'];
				$this->create_time = date('Y-m-d h:i:s');
				$this->update_time = date('Y-m-d h:i:s');
			}else{
				//修改时自动更新以下记录
				$this->update_time = date('Y-m-d h:i:s');
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
    public function getCostRelations()
    {
        return $this->hasMany(CostRelation::className(), ['cost_id' => 'cost_id']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getSys()
    {
        return $this->hasOne(SysUser::className(), ['id' => 'builder']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRealestates()
    {
        return $this->hasMany(CommunityRealestate::className(), ['realestate_id' => 'realestate_id'])->viaTable('cost_relation', ['cost_id' => 'cost_id']);
    }
}
