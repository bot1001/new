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
		$c = $_SESSION['community'];
		
        $query = HouseInfo::find();
		$query->joinWith('c');
		$query->joinWith('b');
		
		$query->select('community_basic.community_name as community, 
		house_info.*, 
		community_building.building_name as building,
		community_realestate.room_number as number,
		community_realestate.room_name as room_name')->where(['in', 'community_basic.community_id', $c]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>[
                'defaultOrder' => [
                    'house_id' => SORT_DESC
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
            'house_id' => $this->house_id,
            'realestate' => $this->realestate,
            'creater' => $this->creater,
			'community_building.building_name' => $this->building,
			'community_realestate.room_number' =>  $this->number,
			'community_realestate.room_name' => $this->room_name,
            'create' => $this->create,
            'update' => $this->update,
            'status' => $this->status,
            'politics' => $this->politics,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
			->andFilterWhere(['like', 'community_basic.community_name', $this->community])
			//->andFilterWhere(['like', 'community_building.building_name', $this->building])
			//->andFilterWhere(['like', 'community_realestate.room_number', $this->number])
			//->andFilterWhere(['like', 'community_realestate.room_name', $this->room_name])
            ->andFilterWhere(['like', 'IDcard', $this->IDcard])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'property', $this->property]);
		
		//小区排序
		$dataProvider->sort->attributes['community'] = 
		[
			'asc' => ['community' => SORT_ASC],
			'desc' => ['community' => SORT_DESC],
		];
		
		$dataProvider->sort->attributes['building']=
			[
				'asc' => ['building' => SORT_ASC],
				'desc' => ['building' => SORT_DESC]
			];
		
		$dataProvider->sort->attributes['number']=
			[
				'asc' => ['number' => SORT_ASC],
				'desc' => ['number' => SORT_DESC]
			];
		
		$dataProvider->sort->attributes['room_name']=
			[
				'asc' => ['room_name' => SORT_ASC],
				'desc' => ['room_name' => SORT_DESC]
			];

        return $dataProvider;
    }
}
