<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WaterMeter;
use app\models\CommunityRealestate;

/**
 * WaterSearch represents the model behind the search form of `app\models\WaterMeter`.
 */
class WaterSearch extends WaterMeter
{
	public function attributes()
	{
		return array_merge(parent::attributes(),['building', 'name', 'community']);
	}
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'realestate_id', 'year', 'month', 'readout'], 'integer'],
            [['property', 'building', 'name', 'community'], 'safe'],
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
			$query = WaterMeter::find();$query = (new \yii\db\Query())->select([
			    'community_basic.community_name as community','community_building.building_name as building',
			    'water_meter.year','water_meter.type','community_realestate.room_number as number','community_realestate.room_name as name',
			    'MAX(CASE month WHEN 1 THEN readout ELSE 0 END ) Jan',
                'MAX(CASE month WHEN 2 THEN readout ELSE 0 END ) Feb',
                'MAX(CASE month WHEN 3 THEN readout ELSE 0 END ) Mar',
                'MAX(CASE month WHEN 4 THEN readout ELSE 0 END ) Apr',
                'MAX(CASE month WHEN 5 THEN readout ELSE 0 END ) May',
                'MAX(CASE month WHEN 6 THEN readout ELSE 0 END ) Jun',
                'MAX(CASE month WHEN 7 THEN readout ELSE 0 END ) Jul',
                'MAX(CASE month WHEN 8 THEN readout ELSE 0 END ) Aug',
                'MAX(CASE month WHEN 9 THEN readout ELSE 0 END ) Sept',
                'MAX(CASE month WHEN 10 THEN readout ELSE 0 END ) Oct',
                'MAX(CASE month WHEN 11 THEN readout ELSE 0 END ) Nov',
                'MAX(CASE month WHEN 12 THEN readout ELSE 0 END ) D'])
			->from ('water_meter')
			->join('inner join','community_realestate','community_realestate.realestate_id = water_meter.realestate_id')
			->join('inner join','community_basic','community_basic.community_id = community_realestate.community_id')
			->join('inner join','community_building','community_building.building_id = community_realestate.building_id')
			->groupBy(['water_meter.realestate_id','year']);
		}else{
			// 提取房屋编码
			$query = WaterMeter::find();$query = (new \yii\db\Query())->select([
			    'community_basic.community_name as community','community_building.building_name as building',
			    'water_meter.year','water_meter.type','community_realestate.room_number as number','community_realestate.room_name as name',
			    'MAX(CASE month WHEN 1 THEN readout ELSE 0 END ) Jan',
                'MAX(CASE month WHEN 2 THEN readout ELSE 0 END ) Feb',
                'MAX(CASE month WHEN 3 THEN readout ELSE 0 END ) Mar',
                'MAX(CASE month WHEN 4 THEN readout ELSE 0 END ) Apr',
                'MAX(CASE month WHEN 5 THEN readout ELSE 0 END ) May',
                'MAX(CASE month WHEN 6 THEN readout ELSE 0 END ) Jun',
                'MAX(CASE month WHEN 7 THEN readout ELSE 0 END ) Jul',
                'MAX(CASE month WHEN 8 THEN readout ELSE 0 END ) Aug',
                'MAX(CASE month WHEN 9 THEN readout ELSE 0 END ) Sept',
                'MAX(CASE month WHEN 10 THEN readout ELSE 0 END ) Oct',
                'MAX(CASE month WHEN 11 THEN readout ELSE 0 END ) Nov',
                'MAX(CASE month WHEN 12 THEN readout ELSE 0 END ) D'])
			->from ('water_meter')
			->join('inner join','community_realestate','community_realestate.realestate_id = water_meter.realestate_id')
			->join('inner join','community_basic','community_basic.community_id = community_realestate.community_id')
			->join('inner join','community_building','community_building.building_id = community_realestate.building_id')
			->where(['water_meter.community' => $comm])
			->groupBy(['water_meter.realestate_id','year']);
		}
        
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			//'totalCount' => count($query),
    
    'pagination' => [
        'pageSize' => 20,
    ],
			/*'pagination' => ['pageSize' => '10'],//'pagination' => ['pageSize' => '10'],*/
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		
		if($this->community !='' && $this->building != '' && $this->name != ''){
			$community = CommunityBasic::find()
				->select('community_id')
				->where(['like','community_name',$this->community])
				->asArray()
				->one();
			$building = CommunityBuilding::find()
				->select('building_id')
				->andwhere(['community_id' => $community['community_id']])
				->andwhere(['like','building_name',$this->building])
				->asArray()
				->all();
			
			$c_id = array_column($building,'building_id');//
			$r_id = CommunityRealestate::find()
				->select('realestate_id')
				->andwhere(['in', 'building_id' , $c_id])
				->andwhere(['like','room_name',$this->name])
				->asArray()
				->all();
						
			$b_id = array_column($r_id,'realestate_id');
			$query->andFilterWhere(['in', 'water_meter.realestate_id', $b_id]);
			
		}elseif($this->community !='' && $this->building != ''){
			$community = CommunityBasic::find()
				->select('community_id')
				->where(['like','community_name',$this->community])
				->asArray()
				->one();
			$building = CommunityBuilding::find()
				->select('building_id')
				->andwhere(['community_id' => $community['community_id']])
				->andwhere(['like','building_name',$this->building])
				->asArray()
				->all();
			
			$c_id = array_column($building,'building_id');
			$r_id = CommunityRealestate::find()
				->select('realestate_id')
				->where(['in', 'building_id' , $c_id])
				->asArray()
				->all();
			$b_id = array_column($r_id,'realestate_id');
			$query->andFilterWhere(['in', 'water_meter.realestate_id', $b_id]);
		}elseif($this->building != '' && $this->name != ''){
			$building = CommunityBuilding::find()
				->select('building_id')
				->where(['like','building_name',$this->building])
				->asArray()
				->all();
			$c_id = array_column($building,'building_id');
			
			$r_id = CommunityRealestate::find()
				->select('realestate_id')
				->andwhere(['in', 'building_id' , $c_id])
				->andwhere(['like', 'room_name', $this->name])
				->asArray()
				->all();
			$b_id = array_column($r_id,'realestate_id');
			$query->andFilterWhere(['in', 'water_meter.realestate_id', $b_id]);
		}elseif($this->community !='' && $this->name != ''){
			$community = CommunityBasic::find()
				->select('community_id')
				->where(['like','community_name',$this->community])
				->asArray()
				->one();
			
			$reale = CommunityRealestate::find()
				->select('realestate_id')
				->andwhere(['community_id' => $community['community_id']])
				->andwhere(['like','room_name',$this->name])
				->asArray()
				->all();
			$id = array_column($reale,'realestate_id');
            $query->andFilterWhere(['in', 'water_meter.realestate_id', $id]);
		}elseif($this->community !=''){
			$community = CommunityBasic::find()
				->select('community_id')
				->where(['like','community_name',$this->community])
				->asArray()
				->one();
			
			$reale = CommunityRealestate::find()
				->select('realestate_id')
				->where(['community_id' => $community['community_id']])
				->asArray()
				->all();
			
			$id = array_column($reale,'realestate_id');
            $query->andFilterWhere(['in', 'water_meter.realestate_id', $id]);
        }elseif($this->building != ''){
			$building = CommunityBuilding::find()
				->select('building_id')
				->where(['like','building_name',$this->building])
				->asArray()
				->all();
			$c_id = array_column($building,'building_id');
			$r_id = CommunityRealestate::find()
				->select('realestate_id')
				->where(['in', 'building_id' , $c_id])
				->asArray()
				->all();
			$b_id = array_column($r_id,'realestate_id');
			$query->andFilterWhere(['in', 'water_meter.realestate_id', $b_id]);
		}elseif($this->name != ''){
			$r_name = CommunityRealestate::find()->select('realestate_id')
				->where(['like','room_name',$this->name])
				->asArray()
				->all();
			$r_id = array_column($r_name,'realestate_id');
			$query->andFilterWhere(['in', 'water_meter.realestate_id', $r_id]);
		}

        // grid filtering conditions
       $query->andFilterWhere([
            'id' => $this->id,
            'realestate_id' => $this->realestate_id,
            'year' => $this->year,
            'month' => $this->month,
            'readout' => $this->readout,
        ]);

        $query->andFilterWhere(['like', 'property', $this->property])
			->andFilterWhere(['in', 'type', $this->type]);

        return $dataProvider;
    }
}
