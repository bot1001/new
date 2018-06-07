<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form of `common\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'order_parent', 'create_time', 'order_type', 'payment_time', 'invoice_id', 'status'], 'integer'],
            [['account_id', 'order_id', 'payment_gateway', 'payment_number', 'description'], 'safe'],
            [['order_amount'], 'number'],
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
        $query = Order::find();

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
            'id' => $this->id,
            'order_parent' => $this->order_parent,
            'create_time' => $this->create_time,
            'order_type' => $this->order_type,
            'payment_time' => $this->payment_time,
            'order_amount' => $this->order_amount,
            'invoice_id' => $this->invoice_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'account_id', $this->account_id])
            ->andFilterWhere(['like', 'order_id', $this->order_id])
            ->andFilterWhere(['like', 'payment_gateway', $this->payment_gateway])
            ->andFilterWhere(['like', 'payment_number', $this->payment_number])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
