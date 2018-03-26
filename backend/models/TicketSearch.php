<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TicketBasic;

/**
 * TicketSearch represents the model behind the search form about `app\models\TicketBasic`.
 */
class TicketSearch extends TicketBasic
{
	//搜索键
	public function attributes()
	{
		return array_merge(parent::attributes(),['ticket_number', 'explain1','create_time','community_name',
												 'building_name','room_number','room_name','real_name','name','mobile_phone','ticket_id']);
	}
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ticket_id', 'community_id', 'realestate_id', 'tickets_taxonomy', 'create_time', 'is_attachment'], 'integer'],
            [['account_id','contact_person', 'contact_phone', 'assignee_id', 'reply_total', 'ticket_status',
			  'ticket_number', 'explain1','create_time','community_name','building_name','room_number','room_name','real_name','name','mobile_phone','ticket_id'], 'safe'],
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
		//原生MySQL语句
        //$query = TicketBasic::find();
		//自定义MySQL语句
		
		if($_SESSION['user']['community']){
			$query = (new \yii\db\Query())->select([
		    	'ticket_basic.ticket_number','ticket_basic.explain1','ticket_basic.create_time','ticket_basic.ticket_id',
		    	'community_basic.community_name',
		    	'community_basic.community_id',
		    	'community_building.building_name',
		    	'community_realestate.room_number','community_realestate.room_name',
		    	'user_account.mobile_phone',
		    	'user_data.real_name',
		    	'status.name'
		    ])
		    	->from ('ticket_basic')
		    	->join('inner join','community_realestate','community_realestate.realestate_id=ticket_basic.realestate_id')
		    	->join('inner join','community_basic','community_basic.community_id=community_realestate.community_id')
		    	->join('inner join','community_building','community_building.building_id=community_realestate.building_id')
		    	->join('inner join','user_account','user_account.account_id=ticket_basic.account_id')
		    	->join('inner join','user_data','user_data.account_id=ticket_basic.account_id')
		    	->join('inner join','status','status.ticket_status_id = ticket_basic.ticket_status')
				->where(['community_basic.community_id' => $_SESSION['user']['community']]);
		}else{
			$query = (new \yii\db\Query())->select([
		    	'ticket_basic.ticket_number','ticket_basic.explain1','ticket_basic.create_time','ticket_basic.ticket_id',
		    	'community_basic.community_name',
		    	'community_building.building_name',
		    	'community_realestate.room_number','community_realestate.room_name',
		    	'user_account.mobile_phone',
		    	'user_data.real_name',
		    	'status.name'
		    ])
		    	->from ('ticket_basic')
		    	->join('inner join','community_realestate','community_realestate.realestate_id=ticket_basic.realestate_id')
		    	->join('inner join','community_basic','community_basic.community_id=community_realestate.community_id')
		    	->join('inner join','community_building','community_building.building_id=community_realestate.building_id')
		    	->join('inner join','user_account','user_account.account_id=ticket_basic.account_id')
		    	->join('inner join','user_data','user_data.account_id=ticket_basic.account_id')
		    	->join('inner join','status','status.ticket_status_id = ticket_basic.ticket_status');
		    }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			//排序
			'sort' => [
            'attributes' => [
                'ticket_number',
                'explain1',
                'create_time',
			    'community_name',
			    'building_name',
			    'room_number',
			    'room_name',
			    'real_name',
			    'name',
			    'mobile_phone',
			    'ticket_id'
                ],
			//默认排序字段
            'defaultOrder' =>[
			           'create_time' => SORT_DESC,
		              ]
			],
        ]);

		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'ticket_id' => $this->ticket_id,
            'community_id' => $this->community_id,
            'realestate_id' => $this->realestate_id,
            'tickets_taxonomy' => $this->tickets_taxonomy,
            'create_time' => $this->create_time,
            'is_attachment' => $this->is_attachment,
        ]);

        $query->andFilterWhere(['like', 'ticket_number', $this->ticket_number])
            ->andFilterWhere(['like', 'account_id', $this->account_id])
            ->andFilterWhere(['like', 'explain1', $this->explain1])
            ->andFilterWhere(['like', 'contact_person', $this->contact_person])
            ->andFilterWhere(['like', 'contact_phone', $this->contact_phone])
            ->andFilterWhere(['like', 'assignee_id', $this->assignee_id])
            ->andFilterWhere(['like', 'reply_total', $this->reply_total])
			->andFilterWhere(['like','ticket_number',$this->ticket_number])
            ->andFilterWhere(['like','create_time',$this->create_time])
            ->andFilterWhere(['like','community_name',$this->community_name])
            ->andFilterWhere(['like','building_name',$this->building_name])
            ->andFilterWhere(['like','room_number',$this->room_number])
            ->andFilterWhere(['like','room_number',$this->room_number])
            ->andFilterWhere(['like','real_name',$this->real_name])
            ->andFilterWhere(['like','name',$this->name])
            ->andFilterWhere(['like','mobile_phone',$this->mobile_phone])
            ->andFilterWhere(['like','ticket_id',$this->ticket_id]);

        return $dataProvider;
    }
}
