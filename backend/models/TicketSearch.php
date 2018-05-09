<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TicketBasic;

/**
 * TicketSearch represents the model behind the search form of `app\models\TicketBasic`.
 */
class TicketSearch extends TicketBasic
{
	public function attributes()
	{
		return array_merge(parent::attributes(),['building', 'name']);
	}
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticket_id', 'community_id', 'realestate_id', 'tickets_taxonomy', 'is_attachment', 'remind'], 'integer'],
            [['building', 'create_time', 'name', 'ticket_number', 'account_id', 'explain1', 'contact_person', 'contact_phone', 'assignee_id', 'reply_total', 'ticket_status'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
		$community = $_SESSION['community'];
		if($community){
			$query = TicketBasic::find()->where(['in', 'ticket_basic.community_id', $community]);
		}else{
			$query = TicketBasic::find();
		}
        
		$query->joinWith(['c']);
		$query->joinWith(['b']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
		    	'defaultOrder' =>[
		        	'ticket_id' => SORT_DESC
		        ]
		    ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		
		//自定义搜索时间
		if($this->create_time != '')
		{
			$time = explode(' to ', $this->create_time);
			$one = reset($time); //2018-08-13 to 2018-08-23
			$two = end($time);
			$query->andFilterWhere(['between', 'ticket_basic.create_time', strtotime($one), strtotime($two)]);
		}

        // grid filtering conditions
        $query->andFilterWhere([
            'ticket_id' => $this->ticket_id,
            'ticket_basic.community_id' => $this->community_id,
            'realestate_id' => $this->realestate_id,
            'tickets_taxonomy' => $this->tickets_taxonomy,
            'is_attachment' => $this->is_attachment,
            'remind' => $this->remind,
			'ticket_status' => $this->ticket_status
        ]);

        $query->andFilterWhere(['like', 'ticket_number', $this->ticket_number])
            ->andFilterWhere(['like', 'account_id', $this->account_id])
            ->andFilterWhere(['like', 'explain1', $this->explain1])
            ->andFilterWhere(['in', 'building_name', $this->getAttribute('building')])
            ->andFilterWhere(['like', 'community_realestate.room_name', $this->name])
            ->andFilterWhere(['like', 'contact_person', $this->contact_person])
            ->andFilterWhere(['like', 'contact_phone', $this->contact_phone])
            ->andFilterWhere(['like', 'assignee_id', $this->assignee_id])
            ->andFilterWhere(['like', 'reply_total', $this->reply_total]);
		
		$dataProvider -> sort->attributes['name']=
			[
				'asc' => ['community_realestate.room_name'=>SORT_ASC],
				'desc' => ['community_realestate.room_name'=>SORT_DESC],
			];
		
		$dataProvider-> sort->attributes['building']=
			[
				'asc' => ['building_name'=>SORT_ASC],
				'desc' => ['building_name'=>SORT_DESC],
			];

        return $dataProvider;
    }
}
