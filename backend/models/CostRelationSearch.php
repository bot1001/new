<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CostRelation;

/**
 * CostRelationSearch represents the model behind the search form of `app\models\CostRelation`.
 */
class CostRelationSearch extends CostRelation
{
	public function attributes()
	{
		return array_merge(parent::attributes(),['number', 'building', 'name', 'price', 'room_name']);
	}
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'community', 'building_id', 'realestate_id', 'cost_id', 'from'], 'integer'],
            [['number', 'building', 'property','name','price', 'room_name'], 'safe'],
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
		if(empty($_SESSION['user']))
		{
			$session = Yii::$app->session;
		    $session['user'] = Yii::$app->user->identity;
		    $c = $_SESSION['user']['community'];
		}else{
			$c = $_SESSION['user']['community'];
		}
		
        if($c){
			$query = Costrelation::find()->where(['cost_relation.community'=> "$c" ]);
		}else{
			$query = Costrelation::find();
		}

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider( [
        	'query' => $query,
        	'sort' => [
        		'defaultOrder' => [
        			'id' => SORT_DESC,
        		],
        		'attributes' => ['id', 'number', 'building', 'name', 'price', 'community', 'realestate_id', 'from', 'property', 'room_name' ]
        	]
        ] );

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
            'building_id' => $this->building_id,
            'cost_relation.realestate_id' => $this->realestate_id,
            'cost_id' => $this->cost_id,
            'from' => $this->from,
        ]);

        $query->andFilterWhere(['like', 'property', $this->property]);
		
		$query->join('INNER JOIN','cost_name','cost_name.cost_id = cost_relation.cost_id')
			->join('INNER JOIN','community_building','cost_relation.building_id = community_building.building_id')
			->join('INNER JOIN','community_realestate','community_realestate.realestate_id = cost_relation.realestate_id')
		->andFilterWhere(['like','community_realestate.room_number',$this->number])
		->andFilterWhere(['like','community_building.building_name',$this->building])
		->andFilterWhere(['like','community_realestate.room_name',$this->room_name])
		->andFilterWhere(['like','cost_name.cost_name',$this->name])
		->andFilterWhere(['like','cost_name.price',$this->price]);
		
        return $dataProvider;
    }
}
