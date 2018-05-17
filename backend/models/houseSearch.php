<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\HouseInfo;

/**
 * houseSearch represents the model behind the search form of `app\models\HouseInfo`.
 */
class houseSearch extends HouseInfo
{
	public function attributes()
	{
		return array_merge(parent::attributes(), ['community', 'building', 'number', 'room_name']);
	}
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['house_id', 'realestate', 'creater', 'create', 'update', 'status', 'politics'], 'integer'],
            [['name', 'phone', 'IDcard', 'address', 'property', 'community', 'building', 'number', 'room_name'], 'safe'],
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
        $query = HouseInfo::find();
		$query->joinWith('c');
		$query->joinWith('b');

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
            'house_id' => $this->house_id,
            'realestate' => $this->realestate,
            'creater' => $this->creater,
            'create' => $this->create,
            'update' => $this->update,
            'status' => $this->status,
            'politics' => $this->politics,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
			->andFilterWhere(['like', 'community_basic.community_name', $this->community])
			->andFilterWhere(['like', 'community_building.building_name', $this->building])
            ->andFilterWhere(['like', 'IDcard', $this->IDcard])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'property', $this->property]);

        return $dataProvider;
    }
}
