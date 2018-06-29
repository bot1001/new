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
		return array_merge(parent::attributes(),['room.room_name']);
	}
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_id', 'community_id', 'realestate_id', 'invoice_status'], 'integer'],
            [['building_id', 'description', 'year', 'month', 'create_time', 'order_id','room.room_name', 'invoice_notes', 'payment_time', 'update_time'], 'safe'],
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
		//$query->joinWith('building');
		$query->joinWith('room');
        
        // add conditions that should always apply here->batch(10);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' =>['pageSize' => '15'],
			'sort' => [
			     'defaultOrder' =>[
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
        // grid filtering conditions
        $query->andFilterWhere([
            'invoice_id' => $this->invoice_id,
            'user_invoice.community_id' => $this->community_id,
            'realestate_id' => $this->realestate_id,
            'invoice_amount' => $this->invoice_amount,
            'invoice_status' => $this->invoice_status,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'year', $this->year])
            ->andFilterWhere(['like', 'month', $this->month])
            ->andFilterWhere(['like', 'create_time', $this->create_time])
            ->andFilterWhere(['like', 'order_id', $this->order_id])
            ->andFilterWhere(['like', 'invoice_notes', $this->invoice_notes])
            //->andFilterWhere(['like', 'payment_time', $this->payment_time])
            ->andFilterWhere(['like', 'update_time', $this->update_time]);
		
		$query->join('inner join','community_building','community_building.building_id=user_invoice.building_id')
			->andFilterWhere(['community_building.building_name' => $this->building_id])
			->andFilterWhere(['room_name' => $this->getAttribute('room.room_name')]);
		
		$dataProvider -> sort->attributes['room.room_name']=
			[
				'asc' => ['room_name'=>SORT_ASC],
				'desc' => ['room_name'=>SORT_DESC],
			];
		
        return $dataProvider;
    }
}
