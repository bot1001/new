<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CommunityBuilding;

/**
 * BuildingSearch represents the model behind the search form of `app\models\CommunityBuilding`.
 */
class BuildingSearch extends CommunityBuilding
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['building_id', 'company', 'community_id', 'creater', 'create_time'], 'integer'],
            [['building_name', 'building_parent'], 'safe'],
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
		
		$query = CommunityBuilding::find()->where(['in', 'community_building.community_id', $_SESSION['community']]);		       
				   
		$query->joinWith('com');
		$query->joinWith('c');
		$query->joinWith('creater0');

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
            'building_id' => $this->building_id,
            'community_building.company' => $this->company,
            'community_building.community_id' => $this->community_id,
            'creater' => $this->creater,
            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'building_name', $this->building_name])
            ->andFilterWhere(['like', 'building_parent', $this->building_parent]);

        return $dataProvider;
    }
}
