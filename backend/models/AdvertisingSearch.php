<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Advertising;

/**
 * AdvertisingSearch represents the model behind the search form of `app\models\Advertising`.
 */
class AdvertisingSearch extends Advertising
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ad_id', 'ad_location', 'ad_created_time', 'ad_end_time', 'ad_sort', 'ad_status'], 'integer'],
            [['ad_title', 'ad_excerpt', 'ad_poster', 'ad_publish_community', 'ad_type', 'ad_target_value', 'property'], 'safe'],
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
        $query = Advertising::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
		    	'defaultOrder' => [
			        'ad_status' => SORT_ASC,
		        	'ad_id' => SORT_DESC		        	
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
            'ad_id' => $this->ad_id,
            'ad_location' => $this->ad_location,
            'ad_created_time' => $this->ad_created_time,
            'ad_end_time' => $this->ad_end_time,
            'ad_sort' => $this->ad_sort,
            'ad_status' => $this->ad_status,
        ]);

        $query->andFilterWhere(['like', 'ad_title', $this->ad_title])
            ->andFilterWhere(['like', 'ad_excerpt', $this->ad_excerpt])
            ->andFilterWhere(['like', 'ad_poster', $this->ad_poster])
            ->andFilterWhere(['like', 'ad_publish_community', $this->ad_publish_community])
            ->andFilterWhere(['like', 'ad_type', $this->ad_type])
            ->andFilterWhere(['like', 'ad_target_value', $this->ad_target_value])
            ->andFilterWhere(['like', 'property', $this->property]);

        return $dataProvider;
    }
}
