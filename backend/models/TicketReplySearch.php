<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TicketReply;

/**
 * TicketReplySearch represents the model behind the search form of `app\models\TicketReply`.
 */
class TicketReplySearch extends TicketReply
{
	//搜索键
	public function attributes()
	{
		return array_merge(parent::attributes(),['name']);
	}
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reply_id', 'ticket_id', 'is_attachment', 'reply_status'], 'integer'],
            [['account_id', 'content', 'name', 'reply_time'], 'safe'],
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
        $query = TicketReply::find();
		$query->joinWith('d');
		$query->joinWith('t');
		$query->where(['in', 'ticket_basic.community_id', $_SESSION['community']]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'=> [
		    	'defaultOrder' =>[
		        	'reply_id' => SORT_DESC
		        ]
		    ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		
		if($this->reply_time != '')
		{
			$time = explode(' to ',$this->reply_time);
			$one = reset($time);
			$two = end($time);
            $query->andFilterWhere(['between', 'reply_time', strtotime($one),strtotime($two)]);
		}

        // grid filtering conditions
        $query->andFilterWhere([
            'reply_id' => $this->reply_id,
            'ticket_reply.ticket_id' => $this->ticket_id,
            'is_attachment' => $this->is_attachment,
            //'reply_time' => $this->reply_time,
            'reply_status' => $this->reply_status,
        ]);

        $query->andFilterWhere(['like', 'account_id', $this->account_id])
            ->andFilterWhere(['like', 'content', $this->content])
			->andFilterWhere(['like', 'user_data.real_name', $this->name]);

		$dataProvider -> sort->attributes['name']=
			[
				'asc' => ['real_name'=>SORT_ASC],
				'desc' => ['real_name'=>SORT_DESC],
			];
		
        return $dataProvider;
    }
}
