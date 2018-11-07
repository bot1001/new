<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Accumulate;

/**
 * AccumulateSearch represents the model behind the search form of `common\models\Accumulate`.
 */
class AccumulateSearch extends Accumulate
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'amount', 'income', 'type', 'create_time', 'status'], 'integer'],
            [['account_id', 'order_id', 'property', 'name'], 'safe'],
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
        $query = Accumulate::find();
        $query ->joinWith('data')
        ->joinWith('order');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
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
            'amount' => $this->amount,
            'income' => $this->income,
            'type' => $this->type,
            'create_time' => $this->create_time,
            'store_accumulate.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'store_accumulate.account_id', $this->account_id])
            ->andFilterWhere(['like', 'store_accumulate.order_id', $this->order_id])
            ->andFilterWhere(['like', 'user_data.real_name', $this->name])
            ->andFilterWhere(['like', 'property', $this->property]);

        $dataProvider->sort->attributes['name'] =
            [
                'asc' => ['user_data.real_name' => SORT_ASC],
                'desc' => ['user_data.real_name' => SORT_DESC],
            ];

        return $dataProvider;
    }
}
