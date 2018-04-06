<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_invoice".
 *
 * @property integer $invoice_id
 * @property integer $community_id
 * @property integer $building_id
 * @property integer $realestate_id
 * @property string $description
 * @property string $year
 * @property string $month
 * @property string $invoice_amount
 * @property string $create_time
 * @property string $order_id
 * @property string $invoice_notes
 * @property string $payment_time
 * @property integer $invoice_status
 * @property string $update_time
 *
 * @property OrderBasic $orderBasic
 * @property OrderProducts[] $orders
 * @property OrderRelationshipAddress[] $orders0
 */
class UserInvoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			[['from', 'to'], 'date'],
			[['year', 'month', 'cost'], 'required', 'on' => ['c']],
            [['community_id', 'building_id', 'realestate_id', 'description', 'invoice_amount', 'create_time', 'invoice_status', //'cost', 'year', 'month'
			 ], 'required', 'on' => 'update'],
            [['community_id', 'building_id', 'realestate_id', 'invoice_status'], 'integer'],
            [['month'], 'integer', 'on' => ['c']],
            [['invoice_amount'], 'number'],
            [['create_time', 'invoice_notes', 'update_time'], 'string'],
            [['description'], 'string', 'max' => 200],
            [['order_id'], 'string', 'max' => 64],
            [['month'], 'string', 'max' => 39, 'on' => 'c'],
            [['payment_time'], 'string', 'max' => 22],
            [['community_id', 'building_id', 'realestate_id', 'year', 'month', 'description'], 'unique', 'targetAttribute' => ['community_id', 'building_id', 'realestate_id', 'year', 'month', 'description'], 'message' => '费项已存在，请勿重复提交'],
        ];
    }
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoice_id' => '编号',
            'community_id' => '小区',
            'building_id' => '楼宇',
            'realestate_id' => '房号',
            'description' => '详情',
			'cost' => '费项',
            'year' => '年',
            'month' => '月',
            'invoice_amount' => '合计',
            'create_time' => '创建时间',
            'order_id' => '订单编号',
            'invoice_notes' => '备注',
            'payment_time' => '支付时间',
            'invoice_status' => '状态',
            'update_time' => '更改时间',
			'from' => 'from',
            'name' => 'to',
			'to' => '费项名称',
			'file' => '文件',
        ];
    }    
}
