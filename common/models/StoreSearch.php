<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Store;

/**
 * StoreSearch represents the model behind the search form of `common\models\Store`.
 */
class StoreSearch extends Store
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_id', 'province_id', 'city_id', 'area_id', 'add_time', 'is_certificate', 'store_sort', 'type', 'store_taxonomy'], 'integer'],
            [['store_cover', 'store_name', 'community_id', 'store_address', 'store_introduce', 'store_phone', 'store_status'], 'safe'],
            [['store_longitude', 'store_latitude'], 'number'],
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
        $query = Store::find();
        $query->joinWith('taxonomy')
            ->joinWith('province');

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
            'store_id' => $this->store_id,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
            'store_longitude' => $this->store_longitude,
            'store_latitude' => $this->store_latitude,
            'add_time' => $this->add_time,
            'is_certificate' => $this->is_certificate,
            'store_sort' => $this->store_sort,
            'type' => $this->type,
            'store_taxonomy' => $this->store_taxonomy,
        ]);

        $query->andFilterWhere(['like', 'store_cover', $this->store_cover])
            ->andFilterWhere(['like', 'store_name', $this->store_name])
            ->andFilterWhere(['like', 'community_id', $this->community_id])
            ->andFilterWhere(['like', 'store_address', $this->store_address])
            ->andFilterWhere(['like', 'store_introduce', $this->store_introduce])
            ->andFilterWhere(['like', 'store_phone', $this->store_phone])
            ->andFilterWhere(['like', 'store_status', $this->store_status]);

        return $dataProvider;
    }
}
