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
            ['month', 'in', 'range' => [1,2,3,4,5,6,7,8,9,10,11,12], 'on' => ['up']], 
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
        $scenarios['update'] = ['community_id', 'building_id' , 'realestate_id', 'description', 'invoice_amount', 'month', 'year' ];
        $scenarios['c'] =  ['from', 'year', 'month', 'cost'];
        $scenarios['up'] =  ['community_id', 'building_id' , 'realestate_id', 'description', 'invoice_amount', 'month', 'year' ];
        return $scenarios;
    }
	
	//统计页面过滤数组
	public static function Summ($d, $f, $t)
	{
		$year01 = reset($f); //起始年
		$year02 = reset($t); //截止年
		
		//判断月份
		if(count($f) == 3 && count($t) == 3)
		{
			$month01 = $f['1'];
			$month02 = $t['1'];
		}else{
			$month01 = end($f);
			$month02 = end($t);
		}
		 
		foreach($d as $k => $dd)
		{
			if($dd['year'] == $year01 && $dd['year'] == $year02)
			{
				if($dd['month'] < $month01 || $dd['month'] > $month02)
				{
					unset($d[$k]);
				}else{
					continue;
				}
			}elseif($dd['year'] == $year01 && $dd['year'] < $year02)
			{
				if($dd['month'] < $month01)
				{
					unset($d[$k]);
				}else{
					continue;
				}
			}elseif($dd['year'] > $year01 && $dd['year'] <= $year02)
			{
				if($dd['month'] > $month02)
				{
					unset($d[$k]);
				}else{
					continue;
				}
			}
	    }
		
		return $d;
	}
	
	//过滤数组
	public static function Filter($data,$c_name, $comm)
	{
		//遍历数据源
		foreach ($data as $key=>$value)
        {
            $d[] = $value->attributes;
			
        }
		
		if(isset($d)){
			//遍历小区
		    foreach($comm as $k => $name)
		    {
		    	$amount = 0; //合计金额
		    	$d_amount = 0; //单个费项合计金额
		    	
		    	//遍历缴费数据
		    	foreach($d as $key => $da)
		    	{
				    if($da['community_id'] == "$key")
				    {
				    	$invoice[] = $da;
				    	$amount += $da['invoice_amount'];
				    }else{
				    	continue;
				    }
			    }
						    	
				if(isset($invoice))
				{
					//遍历缴费项目
				    foreach($c_name as $keys => $n) //遍历费项名称
				    {
				    	foreach($invoice as $in)
			            {
			            	if($in['description'] == $n)
			            	{
			            		$de[] = $in;
			            		$d_amount += $in['invoice_amount'];
				    			//unset($c_name["$keys"]);
			            	}else{
			            		continue;
			            	}
			            }
				    	$test[] = [$n => $d_amount];
				    }

		    	    $filter[] = [$name, $amount, $test];
		    	    
		    	    unset($invoice);
		    	    unset($amount);
		    	    unset($de);
				}else{
					continue;
				}
		    }
		}
		
		if(!isset($filter)){
			$name = $amount = $test = '';
			$filter[] = [$name, $amount, $test];
		}
		
		return $filter;
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
