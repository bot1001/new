<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CommunityBasic;

/**
 * CommunityBasicSearch represents the model behind the search form about `app\models\CommunityBasic`.
 */
class CommunityBasicSearch extends CommunityBasic
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['community_id', 'province_id', 'city_id', 'area_id'], 'integer'],
            [['community_name', 'community_logo', 'community_address'], 'safe'],
            [['community_longitude', 'community_latitude'], 'number'],
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
		$c = $_SESSION['user']['community'];
		if(!empty($c)){
			$query = CommunityBasic::find()->where(['community_id' => $c]);
		}else{
			$query = CommunityBasic::find();
		}

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
            'community_id' => $this->community_id,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
            'community_longitude' => $this->community_longitude,
            'community_latitude' => $this->community_latitude,
        ]);

        $query->andFilterWhere(['like', 'community_name', $this->community_name])
            ->andFilterWhere(['like', 'community_logo', $this->community_logo])
            ->andFilterWhere(['like', 'community_address', $this->community_address]);

        return $dataProvider;
    }
}
