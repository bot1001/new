<?php

namespace common\models;

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
class Cost extends \yii\db\ActiveRecord
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
            [['price', 'cost_name', 'level', 'inv', 'sale', 'formula'], 'required'],
            [['price'], 'number'],
			//[['cost_name', 'level', 'price'],'unique'],
			[['create_time', 'update_time'], 'safe'],
            [['inv','parent','level', 'builder', 'sale', 'formula'], 'integer'],
            [['cost_name', 'property'], 'string', 'max' => 50],
            [['cost_name', 'price', 'level', 'inv', 'sale', 'formula'], 'unique', 'targetAttribute' => ['cost_name', 'price', 'level', 'inv'], 'message' => '重复操作，请勿提交'],
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
			'sale' => '优惠',
			'formula' => '计费方式',
			'create_time' => '创建时间',
			'update_time' => '更新时间',
			'builder' => '创建者',
            'property' => '备注',
        ];
    }
}
