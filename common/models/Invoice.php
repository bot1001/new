<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_invoice".
 *
 * @property int $invoice_id （账单ID）
 * @property int $community_id （关联隶属小区ID）
 * @property int $building_id
 * @property int $realestate_id （关联房屋ID）
 * @property string $year  年
 * @property string $month  月
 * @property string $description （账单说明）
 * @property string $invoice_amount （账单金额）
 * @property string $create_time （账单创建时间）
 * @property string $order_id （订单号，默认为空，支付成功后写入）
 * @property string $invoice_notes （备注）
 * @property string $payment_time （支付时间）
 * @property string $update_time
 * @property int $invoice_status （账单状态）（-3,删除,0,未缴纳，1,银行代缴,2,线上已缴纳，3,线下已缴纳4.优惠免单，5.政府代缴）
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['community_id', 'building_id', 'realestate_id', 'description', 'invoice_amount', 'create_time', 'invoice_status'], 'required'],
            [['community_id', 'building_id', 'realestate_id', 'invoice_status'], 'integer'],
            [['invoice_amount'], 'number'],
            [['create_time', 'order_id', 'invoice_notes', 'update_time'], 'string'],
            [['year'], 'string', 'max' => 4],
            [['month'], 'integer', 'min' => 1,'max' => 4],
            [['description'], 'string', 'max' => 200],
            [['payment_time'], 'string', 'max' => 22],
            [['community_id', 'building_id', 'realestate_id', 'description', 'year', 'month'], 'unique', 'targetAttribute' => ['community_id', 'building_id', 'realestate_id', 'description', 'year', 'month']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'invoice_id' => '序号',
            'community_id' => '小区',
            'building_id' => '楼宇',
            'realestate_id' => '房号',
            'year' => '年份',
            'month' => '月份',
            'description' => '详情',
            'invoice_amount' => '合计',
            'create_time' => '创建时间',
            'order_id' => '缴费编号',
            'invoice_notes' => '备注',
            'payment_time' => '支付时间',
            'update_time' => '更新时间',
            'invoice_status' => '状态',
        ];
    }
	
	//将时间戳转换成时间然后在activeform输出
	public function afterFind()
    {
        parent::afterFind();
        $this->payment_time = date('Y-m-d H:s:i', $this->payment_time);
    }
	
	//计算预交费用
	public static function prepay($cost, $month, $id)
	{
		$house = $_SESSION['house']['0']; //获取房号
		$d = date('Y-m'); //当前月
		
		$i = 0;
		for($i; $i <= $month; $i++)
		{
		    $date = date('Y-m', strtotime("+1 month", strtotime($d))); //获取次月时间
		    
		    $time = explode('-', $date); //拆分年月
		    
		    //遍历并重组费项
		    foreach($cost as $c){
		    	$invoice['id'] = reset($id);
		    	$invoice['community'] = $house['community_id'];
		    	$invoice['building'] = $house['building_id'];
		    	$invoice['description'] = $c['cost'];
		    	$invoice['notes'] = $c['property'];
		    	$invoice['amount'] = $c['price'];
		    }
			
			$prepay[] = $invoice;
		}
		return $prepay;
	}
}
