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
		return array_merge(parent::attributes(),['room.room_name','building.building_name','community.community_name']);
	}
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_id', 'community_id', 'building_id', 'realestate_id', 'invoice_status'], 'integer'],
            [['description', 'year', 'month', 'create_time', 'order_id','room.room_name','building.building_name', 'community.community_name', 'invoice_notes', 'payment_time', 'update_time'], 'safe'],
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
		if($_SESSION['user']['community']){
			$query = UserInvoice::find()->where(['user_invoice.community_id' => $_SESSION['user']['community']]);
		}else{
			$query = UserInvoice::find();
		}
        
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
		if($this->payment_time!=''){
			$time = explode(' to ',$this->payment_time);
			$one = (reset($time));
			$two = end($time);
            $query->andFilterWhere(['between', 'payment_time', strtotime($one),strtotime($two)]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'invoice_id' => $this->invoice_id,
            'community_id' => $this->community_id,
            'building_id' => $this->building_id,
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
			  ->join('inner join','community_basic','community_basic.community_id=user_invoice.community_id')
		      ->join('inner join','community_realestate','community_realestate.realestate_id=user_invoice.realestate_id')
		      ->andFilterWhere(['building_name' => $this->getAttribute('building.building_name')])
			  ->andFilterWhere(['room_name' => $this->getAttribute('room.room_name')])
			  ->andFilterWhere(['like','community_name',$this->getAttribute('community.community_name')]);
		
		$dataProvider -> sort->attributes['building.building_name']=
			[
				'asc' => ['building_name'=>SORT_ASC],
				'desc' => ['building_name'=>SORT_DESC],
			];
		$dataProvider -> sort->attributes['room.room_name']=
			[
				'asc' => ['room_name'=>SORT_ASC],
				'desc' => ['room_name'=>SORT_DESC],
			];
		$dataProvider -> sort->attributes['community.community_name']=
			[
				'asc' => ['community_name'=>SORT_ASC],
				'desc' => ['community_name'=>SORT_DESC],
			];

        return $dataProvider;
    }
}
