<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StoreAccumulate;

/**
 * AccumulateSearch represents the model behind the search form of `common\models\StoreAccumulate`.
 */
class AccumulateSearch extends StoreAccumulate
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type'], 'integer'],
            [['account_id', 'property', 'name', 'phone', 'update_time'], 'safe'],
            [['amount'], 'number'],
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
        $query = StoreAccumulate::find();
        $query->joinWith('account')
            ->joinWith('data');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>[
                'defaultOrder' =>
                ['id' => SORT_DESC]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if($this->update_time != '')
        {
            $time = $this->update_time;
            $t = explode(' to ', $time);
            $first = strtotime(reset($t));
            $second = strtotime(end($t));

            $query->andFilterWhere(['in', 'update_time' , $first, $second]);
        }


        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'amount' => $this->amount,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'account_id', $this->account_id])
            ->andFilterWhere(['like', 'user_data.real_name', $this->name])
            ->andFilterWhere(['like', 'user_account.mobile_phone', $this->phone])
            ->andFilterWhere(['like', 'property', $this->property]);

        $dataProvider->sort->attributes['name'] =
            [
                'asc' => ['user_data.real_name' => SORT_ASC],
                'desc' => ['user_data.real_name' => SORT_DESC]
            ];

        $dataProvider->sort->attributes['phone'] =
            [
                'asc' => ['user_account.mobile_phone' => SORT_ASC],
                'desc' => ['user_account.mobile_phone' => SORT_DESC],
            ];

        return $dataProvider;
    }
}
