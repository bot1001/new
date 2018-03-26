<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TicketReply;

/**
 * TicketReplySearch represents the model behind the search form about `app\models\TicketReply`.
 */
class TicketReplySearch extends TicketReply
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reply_id', 'ticket_id', 'is_attachment', 'reply_time', 'reply_status'], 'integer'],
            [['account_id', 'content'], 'safe'],
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
        $query = TicketReply::find();

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
            'reply_id' => $this->reply_id,
            'ticket_id' => $this->ticket_id,
            'is_attachment' => $this->is_attachment,
            'reply_time' => $this->reply_time,
            'reply_status' => $this->reply_status,
        ]);

        $query->andFilterWhere(['like', 'account_id', $this->account_id])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
