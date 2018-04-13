<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\WorkR;

/**
 * WorkRSearch represents the model behind the search form of `app\models\WorkR`.
 */
class WorkRSearch extends WorkR
{
	
	public function attributes()
	{
		return array_merge(parent::attributes(),['community', 'name', 'phone']);
	}
	
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'work_number', 'community_id', 'account_superior'], 'integer'],
            [['account_id', 'work_status', 'account_role', 'account_status', 'community', 'name', 'phone'], 'safe'],
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
        $query = WorkR::find();
		$query->joinWith('c');
		$query->joinWith('account');

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
            'id' => $this->id,
            'work_number' => $this->work_number,
            'community_basic.community_id' => $this->community,
            //'user_account.user_name' => $this->name,
            'community_id' => $this->community_id,
            'account_superior' => $this->account_superior,
        ]);

        $query->andFilterWhere(['like', 'account_id', $this->account_id])
            ->andFilterWhere(['like', 'work_status', $this->work_status])
            ->andFilterWhere(['like', 'user_account.user_name', $this->name])
            ->andFilterWhere(['like', 'user_account.mobile_phone', $this->phone])
            ->andFilterWhere(['like', 'account_role', $this->account_role])
            ->andFilterWhere(['like', 'account_status', $this->account_status]);

        return $dataProvider;
    }
}
