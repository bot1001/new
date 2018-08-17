<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserInvoice;
use app\models\Status;

/**
 * UserInvoiceSearch represents the model behind the search form about `app\models\UserInvoice`.
 */
class UserInvoiceSearch extends UserInvoice
{
	//搜索键
	public function attributes()
	{
		return array_merge(parent::attributes(),['room', 'number', 'name']);
	}
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_id', 'community_id', 'realestate_id', 'invoice_status'], 'integer'],
            [['building_id', 'description', 'year', 'month', 'create_time', 'order_id','room', 'number', 'name', 'invoice_notes', 'payment_time', 'update_time'], 'safe'],
            [['invoice_amount'], 'number'],
        ];
    }

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
		$c = $_SESSION['community'];

		$query = UserInvoice::find()->where(['in', 'user_invoice.community_id', $c]);
		
		ini_set( 'memory_limit', '3072M' ); // 调整PHP由默认占用内存为3072M(3GB)
		set_time_limit(0); //设置时间无限
		
		$query->joinWith('community');
		$query->joinWith('building');
		$query->joinWith('room');
        
        // add conditions that should always apply here->batch(10);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' =>['pageSize' => '15'],
			'sort' => [
			     'defaultOrder' =>[
			           'invoice_id' => SORT_DESC,
			           'year' => SORT_DESC,
			           'month' => SORT_DESC,
			           'description' => SORT_DESC,
		              ]
		         ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		//自定义搜索
		if($this->payment_time !=''){
			$time = explode(' to ',$this->payment_time);
			$one = (reset($time));
			$two = end($time);
            $query->andFilterWhere(['between', 'payment_time', strtotime($one),strtotime($two)]);
        }

        if($this->room != '')
        {
            $room = $this->room;
            $length = strlen($room);
            if($length == '3'){
                $room = str_pad($room,'4','0',STR_PAD_LEFT); //房号不足四位数自动补0
            }

            $query->andFilterWhere(['room_name' => $room]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'invoice_id' => $this->invoice_id,
            'user_invoice.community_id' => $this->community_id,
            'user_invoice.realestate_id' => $this->realestate_id,
            'invoice_amount' => $this->invoice_amount,
            'invoice_status' => $this->invoice_status,
            'year' => $this->year,
            'month' => $this->month,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'create_time', $this->create_time])
            ->andFilterWhere(['like', 'order_id', $this->order_id])
            ->andFilterWhere(['like', 'invoice_notes', $this->invoice_notes])
            ->andFilterWhere(['like', 'update_time', $this->update_time])
			->andFilterWhere(['community_building.building_name' => $this->building_id])
			->andFilterWhere(['community_realestate.room_number' => $this->getAttribute('number')])
            ->andFilterWhere(['like', 'community_realestate.owners_name', $this->name])	;

		$dataProvider -> sort->attributes['room']=
			[
				'asc' => ['room_name'=>SORT_ASC],
				'desc' => ['room_name'=>SORT_DESC],
			];

		$dataProvider -> sort->attributes['number']=
        [
            'asc' => ['room_number'=> SORT_ASC],
            'desc' => ['room_number' => SORT_DESC],
        ];

		$dataProvider -> sort->attributes['name']=
            [
                'asc' => ['owners_name' => SORT_ASC],
                'desc' => ['owners_name' => SORT_DESC]
            ];
		
        return $dataProvider;
    }
}
