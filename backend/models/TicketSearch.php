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
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ticket_id', 'community_id', 'realestate_id', 'tickets_taxonomy', 'create_time', 'is_attachment', 'remind'], 'integer'],
            [['ticket_number', 'account_id', 'explain1', 'contact_person', 'contact_phone', 'assignee_id', 'reply_total', 'ticket_status'], 'safe'],
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
        $query = TicketBasic::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'remind' => $this->remind,
        ]);

        $query->andFilterWhere(['like', 'ticket_number', $this->ticket_number])
            ->andFilterWhere(['like', 'account_id', $this->account_id])
            ->andFilterWhere(['like', 'explain1', $this->explain1])
            ->andFilterWhere(['like', 'contact_person', $this->contact_person])
            ->andFilterWhere(['like', 'contact_phone', $this->contact_phone])
            ->andFilterWhere(['like', 'assignee_id', $this->assignee_id])
            ->andFilterWhere(['like', 'reply_total', $this->reply_total])
            ->andFilterWhere(['like', 'ticket_status', $this->ticket_status]);

        return $dataProvider;
    }
}
