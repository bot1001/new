<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\OrderBasic;

/**
 * OrderSearch represents the model behind the search form about `app\models\OrderBasic`.
 */
class OrderSearch extends OrderBasic
{
	public function attributes()
	{
		return array_merge(parent::attributes(),['order0.name','order0.mobile_phone','order0.address','order_id']);
	}
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'order_parent', 'create_time', 'order_type', 'invoice_id', 'status'], 'integer'],
            [['account_id', 'order0.name','payment_gateway', 'payment_time', 'order0.mobile_phone', 'order_id', 'order0.address', 'payment_number', 'description','todate','fromdate'], 'safe'],
            [['order_amount'], 'number'],
        ];
    }

	public $name;
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
		if($_SESSION['user']['community']){
			$comm = $_SESSION['user']['community'];
			$query = OrderBasic::find()->where(['in', 'account_id', "$comm"]);//'account_id' => $comm  where(['in' , 'id' , [1,2,3]])->all()
		}else{
			$query = OrderBasic::find();
		}
        
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' =>[
			       'defaultOrder' => [
			              'create_time' => SORT_DESC,
		            ]
		       ]
        ]);
		
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		
		if(isset($params['PostSearch']['fromdate']) && isset($params['PostSearch']['todate'])){
            $this->fromdate = $params['PostSearch']['fromdate'];
            $this->todate = $params['PostSearch']['todate'];
        }
				
		//自定义付款时间搜索
		if($this->payment_time != '')
		{
			$p_time = $this->payment_time;
			$t = explode(' to ', $p_time);
			$t01 = reset($t);
			$t02 = end($t);
			$query->andFilterWhere(['between', 'payment_time', strtotime($t01), strtotime($t02)]);
		}

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'order_parent' => $this->order_parent,
            'create_time' => $this->create_time,
            'order_type' => $this->order_type,
           // 'payment_time' => $this->payment_time,
            'order_amount' => $this->order_amount,
            'invoice_id' => $this->invoice_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'account_id', $this->account_id])
            ->andFilterWhere(['like', 'order_basic.order_id', $this->getAttribute('order_id')])
            ->andFilterWhere(['like', 'payment_gateway', $this->payment_gateway])
            ->andFilterWhere(['like', 'payment_number', $this->payment_number])
            ->andFilterWhere(['like', 'description', $this->description]);
		//关联查询
		$query->join('join','order_relationship_address','order_relationship_address.order_id=order_basic.order_id');
		$query->andFilterWhere(['like','name',$this->getAttribute('order0.name')])
		      ->andFilterWhere(['like','mobile_phone',$this->getAttribute('order0.mobile_phone')])
			  ->andFilterWhere(['like','address',$this->getAttribute('order0.address')]);
		//排序
		$dataProvider -> sort->attributes['order0.address']=
			[
				'asc' => ['address'=>SORT_ASC],
				'desc' => ['address'=>SORT_DESC],
			];
		$dataProvider -> sort->attributes['order0.name']=
			[
				'asc' => ['name'=>SORT_ASC],
				'desc' => ['name'=>SORT_DESC],
			];
		$dataProvider -> sort->attributes['order0.mobile_phone']=
			[
				'asc' => ['mobile_phone'=>SORT_ASC],
				'desc' => ['mobile_phone'=>SORT_DESC],
			];

        return $dataProvider;
    }
}
