<?php

namespace app\models;

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
			[['from', 'year', 'month', 'cost'], 'required', 'on' => ['c']],
            [['community_id', 'building_id', 'realestate_id', 'description', 'invoice_amount', 'create_time', 'invoice_status', //'cost', 'year', 'month'
			 ], 'required', 'on' => 'update'],
            [['community_id', 'realestate_id', 'invoice_status'], 'integer'],
            [['month'], 'integer', 'min' => 1, 'max' => 39, 'on' => ['c']],
            [['month'], 'integer'],
            [['invoice_amount'], 'number'],
            [['create_time', 'invoice_notes', 'update_time'], 'string'],
            [['description'], 'string', 'max' => 20],
            [['order_id'], 'string', 'max' => 64],
            ['month', 'in', 'range' => [1,2,3,4,5,6,7,8,9,10,11,12]], 
            [['payment_time'], 'string', 'max' => 22],
            [['community_id', 'building_id', 'realestate_id', 'year', 'month', 'description'], 'unique', 'targetAttribute' => ['community_id', 'building_id', 'realestate_id', 'year', 'month', 'description'], 'message' => '费项已存在，请勿重复提交'],
        ];
    }
	
	//费项名称
	public $cost;
	public $id;
	public $a;
	public $h;
	public $from;
	public $to;
	public $file;

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
			'from' => '起始日期',
            'name' => 'to',
			'to' => '费项名称',
			'file' => '文件',
        ];
    }

    //设置场景
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['update'] = ['community_id', 'building_id' , 'realestate_id', 'description', 'invoice_amount' ];
        $scenarios['c'] =  ['from', 'year', 'month', 'cost'];
        return $scenarios;
    }
	
	//处理搜索参数
	public static function Sum($get)
	{
		if($get['InvoiceSumSearch']['from'])
			{
				$time = explode(' to ',$_GET ['InvoiceSumSearch']['from']);
			    $l = "'\d{4}'is"; //时间提前格式
			    
			    $from02 = reset($time); //起始年月 str_pad($m,2,"0",STR_PAD_LEFT)
			    $to02 = end($time); //截止年月
			    
			    $f = explode('-', $from02); //拆分起始年月
			    $t = explode('-', $to02); //拆分截止年月
			    
                $year01 = reset($f); //提取起始年
                $year02 = reset($t); //提取截止年
			    
                $month01 = str_pad(end($f),2, '0', STR_PAD_LEFT); //提取起始月并自动补“0”
			    $month02 = str_pad(end($t), 2, '0', STR_PAD_LEFT); //提取截止月并自动补“0”
			    $day = date("t",strtotime("$year02-$month02")); //获取截止日期天数
			    
			    $from = $year01.'-'.$month01.'-'.'01'; //拼接起始日期
			    $to = $year02.'-'.$month02.'-'.$day; //拼接截止日期
			}else{
				$from = date('Y-m-d', time() );
			    $to = date('Y-m-d', time() );
			}
		
		$date['from'] = $from;
		$date['to'] = $to;
		return $date;
	}
	
	//过滤数组
	public static function Filter($data,$c_name, $comm)
	{
		//遍历数据源
		foreach ($data as $key=>$value)
        {
            $d[] = $value->attributes;
			
        }
		//遍历小区
		foreach($comm as $k => $name)
		{
			$amount = 0; //合计金额
			$d_amount = 0; //单个费项合计金额
			
			//遍历数据
			foreach($d as $key => $da)
			{
				if($da['community_id'] == "28")
				{
					$invoice[] = $da;
					$amount += $da['invoice_amount'];
				}else{
					continue;
				}
			}
			
			//遍历缴费项目
			if($invoice){
				foreach($invoice as $in)
			    {
			    	if($in['description' == $in])
			    	{
			    		$de[] = $in;
			    		$d_amount += $in['invoice_amount'];
			    	}else{
			    		continue;
			    	}
			    }
			}else{
				$de = '';
			}
			
			
			$filter[] = [$name, $amount, $d_amount];
			
			unset($invoice);
			unset($amount);
		}
		return $invoice;
	}
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(Status::className(), ['invoice_status_id' => 'invoice_status']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getCommunity()
    {
        return $this->hasOne(CommunityBasic::className(), ['community_id' => 'community_id']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getBuilding()
    {
        return $this->hasOne(CommunityBuilding::className(), ['building_id' => 'building_id']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(CommunityRealestate::className(), ['realestate_id' => 'realestate_id']);
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(OrderBasic::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(OrderProducts::className(), ['order_id' => 'order_id'])->viaTable('order_basic', ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders0()
    {
        return $this->hasMany(OrderRelationshipAddress::className(), ['order_id' => 'order_id'])->viaTable('order_basic', ['order_id' => 'order_id']);
    }
}
