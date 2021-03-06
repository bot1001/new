<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CommunityRealestate;

/**
 * CommunityRealestatenSearch represents the model behind the search form about `app\models\CommunityRealestate`.
 */
class CommunityRealestatenSearch extends CommunityRealestate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['realestate_id', 'community_id'], 'integer'],
            [['room_name', 'room_number', 'owners_name', 'owners_cellphone', 'property', 'building_id'], 'safe'],
            [['acreage'], 'number'],
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
		$c = $_SESSION['community'];
		
		$query = CommunityRealestate::find()->where(['in', 'community_realestate.community_id', $c]);
		
        $query->joinWith('community0');
        $query->joinWith('building0');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
		    	'defaultOrder' => [
		        	'realestate_id' => SORT_DESC
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
            'realestate_id' => $this->realestate_id,
            'community_realestate.community_id' => $this->community_id,
            'acreage' => $this->acreage,
        ]);

        $query->andFilterWhere(['like', 'room_name', $this->room_name])
            ->andFilterWhere(['like', 'room_number', $this->room_number])
            ->andFilterWhere(['in', 'community_building.building_name', $this->building_id])
            ->andFilterWhere(['like', 'property', $this->property])
            ->andFilterWhere(['like', 'owners_name', $this->owners_name])
            ->andFilterWhere(['like', 'owners_cellphone', $this->owners_cellphone]);

        return $dataProvider;
    }
}
