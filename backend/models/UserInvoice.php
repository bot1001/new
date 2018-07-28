<?php

namespace app\models;

use Yii;
use app\models\CommunityBasic;

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
	public $name;

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
            'name' => '业主',
			'cost' => '费项',
            'year' => '年份',
            'month' => '月份',
            'invoice_amount' => '合计',
            'create_time' => '创建时间',
            'order_id' => '订单编号',
            'invoice_notes' => '备注',
            'payment_time' => '支付时间',
            'invoice_status' => '状态',
            'update_time' => '更改时间',
			'from' => '起始日期',
//            'name' => 'to',
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
		
	//批量生成费项
	public static function Add()
	{
		$community = CommunityBasic::find()
			->select('community_id')
			->orderBy('community_id ASC')
			->asArray()
			->all();

		foreach($community as $comm)
		{
		    $query = ( new\ yii\ db\ Query() )->select( [
		    	'community_realestate.community_id',
		    	'community_realestate.building_id',
		    	'cost_relation.realestate_id',
		    	'community_realestate.acreage',
		    	'cost_relation.cost_id',
		    	'cost_name.cost_name',
		    	'cost_name.parent',
		    	'cost_name.sale',
		    	'cost_name.formula',
		    	'cost_name.price',
		    	'cost_name.property',
		    ] )
		    	->from( 'cost_relation' )
		    	->join( 'left join', 'community_realestate', 'cost_relation.realestate_id = community_realestate.realestate_id' )
		    	->join( 'left join', 'community_building', 'community_building.building_id = community_realestate.building_id' )
		    	->join( 'left join', 'community_basic', 'community_basic.community_id = community_realestate.community_id' )
		    	->join( 'left join', 'cost_name', 'cost_relation.cost_id = cost_name.cost_id' )
		    	->andwhere( ['in', 'community_realestate.community_id', $comm ] )
		    	->andwhere( ['<', 'cost_relation.from', time() ] )
		    	->andwhere(['cost_name.inv' =>1, 'cost_relation.status' => '1']);		
		    
		    $y = date( 'Y' );
		    $m = date( 'm' );
		    $f = date( time() );

		    foreach ( $query ->batch(50) as $qu )
		    {
		    	foreach($qu as $q){
		    	    $community = $q[ 'community_id' ];
		    	    $building = $q[ 'building_id' ];
		    	    $realestate = $q[ 'realestate_id' ];
		    	    $cost = $q[ 'cost_id' ];
		    	    $description = $q[ 'cost_name' ];
		    	    $price = $q[ 'price' ];
		    	    $acreage = $q[ 'acreage' ];
		    	    $notes = $q['property'];

		    	    if ( $q['formula'] == "1" ) {
		    	    	$p = $price*$acreage;
		    	    	$price = round($p,2); //保留两位小数点
		    	    }elseif($q['formula'] == '2'){
		    	        $p = $price*date('t');
		    	        $price = round($p, 2);
                    }

		    	    //查入语句
		    	    $sql = "insert ignore into user_invoice(community_id,building_id,realestate_id,description, year, month, invoice_amount,create_time,invoice_status, invoice_notes)
		    	    values ('$community','$building', '$realestate','$description', '$y', '$m', '$price','$f','0','$notes')";
		    	    $result = Yii::$app->db->createCommand( $sql )->execute();
		    	}
		    }
		}
		return true;
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
