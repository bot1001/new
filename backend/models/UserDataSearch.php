<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserData;

/**
 * UserDataSearch represents the model behind the search form about `app\models\UserData`.
 */
class UserDataSearch extends UserData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'gender', 'province_id', 'city_id', 'area_id', 'reg_time', 'property'], 'integer'],
            [['account_id', 'real_name', 'face_path', 'nickname'], 'safe'],
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
        $query = UserData::find()->where(['property' => 1]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' =>[
			    'defaultOrder' =>[
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
            'gender' => $this->gender,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
            'reg_time' => $this->reg_time,
            'property' => $this->property,
        ]);

        $query->andFilterWhere(['like', 'account_id', $this->account_id])
            ->andFilterWhere(['like', 'real_name', $this->real_name])
            ->andFilterWhere(['like', 'face_path', $this->face_path])
            ->andFilterWhere(['like', 'nickname', $this->nickname]);

        return $dataProvider;
    }
}
