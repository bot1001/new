<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CostName;

/**
 * CostNameSearch represents the model behind the search form of `app\models\CostName`.
 */
class CostNameSearch extends CostName
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cost_id', 'level', 'inv', 'parent', 'builder'], 'integer'],
            [['cost_name', 'property', 'create_time', 'update_time'], 'safe'],
            [['price'], 'number'],
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
        $query = CostName::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
		    	'defaultOrder' => [
		        	'level' => SORT_ASC,
			        'cost_id' => SORT_DESC,
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
            'cost_id' => $this->cost_id,
            'level' => $this->level,
            'price' => $this->price,
            'inv' => $this->inv,
            'parent' => $this->parent,
            'builder' => $this->builder,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'cost_name', $this->cost_name])
            ->andFilterWhere(['like', 'property', $this->property]);

        return $dataProvider;
    }
}
