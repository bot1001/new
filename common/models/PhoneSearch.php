<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PhoneList;

/**
 * PhoneSearch represents the model behind the search form of `common\models\PhoneList`.
 */
class PhoneSearch extends PhoneList
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone_id', 'phone_number', 'parent_id', 'have_lower', 'phone_sort'], 'integer'],
            [['phone_name'], 'safe'],
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
        $query = PhoneList::find();
        $query->where(['!=', 'parent_id', '0']);

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
            'phone_id' => $this->phone_id,
            'phone_number' => $this->phone_number,
            'parent_id' => $this->parent_id,
            'have_lower' => $this->have_lower,
            'phone_sort' => $this->phone_sort,
        ]);

        $query->andFilterWhere(['like', 'phone_name', $this->phone_name]);

        return $dataProvider;
    }
}
