<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Information;

/**
 * InformationSearch represents the model behind the search form of `app\models\Information`.
 */
class InformationSearch extends Information
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['remind_id', 'community', 'times', 'reading', 'target', 'ticket_number', 'remind_time'], 'integer'],
            [['detail', 'property', 'type'], 'safe'],
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
		$c = $_SESSION['community'];
		
        $query = Information::find()->where(['in', 'information.community', $c]);

		$query->joinWith('c')->joinWith('target0');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
		    	'defaultOrder' => [
		        	'remind_id' => SORT_DESC,
			        'remind_time' => SORT_DESC
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
            'remind_id' => $this->remind_id,
            'community' => $this->community,
            'times' => $this->times,
            'reading' => $this->reading,
            'type' => $this->type,
            'target' => $this->target,
            'ticket_number' => $this->ticket_number,
            'remind_time' => $this->remind_time,
        ]);

        $query->andFilterWhere(['like', 'detail', $this->detail])
            ->andFilterWhere(['like', 'property', $this->property]);

        return $dataProvider;
    }
}
