<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserAccount;

/**
 * UserSearch represents the model behind the search form about `app\models\UserAccount`.
 */
class UserSearch extends UserAccount
{
	public function attributes()
	{
		return array_merge(parent::attributes(),['community_name','building_name','reg_time','room_number','room_name','real_name','name']);
	}
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'account_role', 'new_message', 'status'], 'integer'],
            [['account_id', 'user_name', 'reg_time','community_name', 'mobile_phone', 'building_name', 'room_number','room_name','real_name', 'name','todate','fromdate'], 'safe'],
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
        //$query = UserAccount::find();
		
		$query = (new \yii\db\Query())->select([
		    'user_data.real_name','user_data.reg_time',
		    'user_account.mobile_phone','user_account.account_role',
		    'community_basic.community_name',
		    'community_building.building_name',
		    'community_realestate.room_name','community_realestate.room_number',
		    'status.name'])
		    ->from ('user_account')
		    ->join('inner join','user_relationship_realestate','user_relationship_realestate.account_id = user_account.account_id')
		    ->join('inner join','community_realestate','community_realestate.realestate_id =user_relationship_realestate.realestate_id')
		    ->join('inner join','community_basic','community_basic.community_id = community_realestate.community_id')
		    ->join('inner join','community_building','community_building.building_id = community_realestate.building_id')
		    ->join('inner join','user_data','user_data.account_id = user_account.account_id')
		    ->join('inner join','status','status.user_status = user_account.status')
		    ->where(['in', 'community_basic.community_id', $_SESSION['community']]);
		
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider( [
        	'query' => $query,
        	'sort' => [
        		'attributes' => [
        			'reg_time',
        			'community_name',
        			'building_name',
        			'room_number',
        			'room_name',
        			'mobile_phone',
        			'real_name',
        			'name',
        			'account_role',
        		],
        		'defaultOrder' => [
        			'reg_time' => SORT_DESC,
        		]
        	],
        ] );

        $this->load($params);

		//自定义搜索
		if($this->reg_time !=''){
			$time = explode(' to ',$this->reg_time);
			$one = reset($time);
			$two = end($time);
            $query->andFilterWhere(['between', 'reg_time', strtotime($one),strtotime($two)]);
        }
		
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'account_role' => $this->account_role,
            'new_message' => $this->new_message,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'account_id', $this->account_id])
            ->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'mobile_phone', $this->mobile_phone])
            ->andFilterWhere(['like', 'qq_openid', $this->qq_openid])
            ->andFilterWhere(['like', 'weixin_openid', $this->weixin_openid])
            ->andFilterWhere(['like', 'weibo_openid', $this->weibo_openid])
			->andFilterWhere(['like', 'building_name', $this->getAttribute('building_name')])
            ->andFilterWhere(['like','community_name',$this->getAttribute('community_name')])
			->andFilterWhere(['like','room_number',$this->getAttribute('room_number')])
			->andFilterWhere(['like','room_name',$this->getAttribute('room_name')])
			//->andFilterWhere(['like','reg_time',$this->getAttribute('reg_time')])
			->andFilterWhere(['like','real_name',$this->getAttribute('real_name')])
			->andFilterWhere(['like','name',$this->getAttribute('name')])			;

			return $dataProvider;
    }
}
