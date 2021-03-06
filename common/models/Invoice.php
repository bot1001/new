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
            [['community_id', 'building_id', 'realestate_id', 'description', 'invoice_amount', 'year', 'invoice_status'], 'required'],
			[['month'], 'required', 'message' => '月数不能为空'],
            [['community_id', 'building_id', 'realestate_id', 'invoice_status'], 'integer'],
            [['invoice_amount'], 'number'],
            [['order_id', 'invoice_notes', 'update_time'], 'string'],
//            [['year', 'month'], 'string', 'max' => 4],
            [['month'], 'integer', 'min' => 1,'max' => 39],
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
	public static function prepay($cost, $year, $month, $id)
	{
		$house = $_SESSION['home']; //获取房号
		
		$sale = 0; //优惠计算
		
		foreach($cost as $c)
		{
			$checking = 0; //费项验证结果, 默认不存在
			$date = date('Y-m', strtotime("-1 month", strtotime($year))); //预交时间退格一个月
		    //遍历并重组费项
		    for($i = 1; $i <= $month; $i++)
			{	
				$date = date('Y-m', strtotime("+1 month", strtotime($date))); //获取次月时间
		    
		        $time = explode('-', $date); //拆分年月
				if($checking == '0'){
					$check = Invoice::find() //验证费项是否存在
					->andwhere(['in', 'year', reset($time)])
					->andwhere(['in', 'month', end($time)])
					->andwhere(['in', 'realestate_id', $id])
					->andwhere(['in', 'description', $c['cost']])
					->asArray()
					->one();
				}								
				
				if($check){
					continue; 
				}else{
					$checking = '1';
				}
				
		    	$invoice['id'] = $id; //房号ID
				$invoice['community_id'] = $house['community_id']; //小区ID
		    	$invoice['community'] = $house['community']; //小区名称
		    	$invoice['building_id'] = $house['building_id']; //楼宇ID
		    	$invoice['building'] = $house['building']; //楼宇名称
		    	$invoice['year'] = reset($time); //年份
		    	$invoice['month'] = end($time); //月份
				$invoice['description'] = $c['cost']; //详情
				
				//判断物业费
				if($c['sale'] == '1'){
					if(strtotime($date) > strtotime(date('Y-m')))
					{
						$sale ++;
					}
				}
				
				if($sale%13 == 0 && $sale > 0 && $c['sale'] == '1'){//判断优惠条件，满一年（13个月）
					$invoice['sale'] = '1';
					$invoice['notes'] = '缴费优惠';
				}else{
					$invoice['sale'] = '0';
					$invoice['notes'] = $c['property'];
				}
				
				if($c['formula'] == '1'){ //判断计费方式
					$amount = $c['price']*$house['acreage'];
				}else{
					$amount = $c['price'];
				}

				$invoice['amount'] = number_format($amount, 2); //保留两位小区点
				
				$prepay[] = $invoice; //收集数据
		    }
		}
		
		if(empty($prepay))
		{
			$prepay = '';
		}
		
		return $prepay;
	}
}
