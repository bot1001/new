<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WaterMeter;

/**
 * WaterSearch01 represents the model behind the search form of `app\models\WaterMeter`.
 */
class WaterSearch01 extends WaterMeter
{
	public function attributes()
	{
		return array_merge(parent::attributes(),['build', 'name', 'type']);
	}
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'community', 'building', 'realestate_id', 'year', 'month', 'readout', 'property'], 'integer'],
			[['build', 'name', 'type'],'safe'],
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
		$comm = $_SESSION['user']['community'];
		if(empty($comm)){
			$query = WaterMeter::find();
		}else{
			$query = WaterMeter::find()->where(['community' => $comm]);
		}
        

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
        		'defaultOrder' => [
        			'year' => SORT_DESC,
        			'month' => SORT_DESC
        		],
			//'attributes' => ['c', 'year', 'month', 'name', 'realestate_id',  'property', 'readout' ]
        	],
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
            'community' => $this->community,
            'building' => $this->building,
            'realestate_id' => $this->realestate_id,
            'year' => $this->year,
            'month' => $this->month,
            'readout' => $this->readout,
			'type' => $this->type,
            'property' => $this->property,
        ]);
		
		$query->join('INNER JOIN','community_building','community_building.building_id = water_meter.building')
			->join('INNER JOIN','community_realestate','community_realestate.realestate_id = water_meter.realestate_id')
			->andFilterWhere(['like', 'room_name' , $this->name])
			->andFilterWhere(['like', 'building_name', $this->build])
			;
		
		$dataProvider -> sort->attributes['build']=
			[
				'asc' => ['building_name'=>SORT_ASC],
				'desc' => ['building_name'=>SORT_DESC],
			];
		
		$dataProvider -> sort->attributes['name']=
			[
				'asc' => ['room_name'=>SORT_ASC],
				'desc' => ['room_name'=>SORT_DESC],
			];

        return $dataProvider;
    }
}
