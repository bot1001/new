<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SysCommunity;

/**
 * SysCommunitySearch represents the model behind the search form of `app\models\SysCommunity`.
 */
class SysCommunitySearch extends SysCommunity
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sys_user_id', 'own_add', 'own_delete', 'own_update', 'own_select'], 'integer'],
            [['community_id'], 'safe'],
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
        $query = SysCommunity::find();
		//$query->joinWith('sysUser');
		$query->joinWith('com');

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
            'sys_user_id' => $this->sys_user_id,
            'own_add' => $this->own_add,
            'own_delete' => $this->own_delete,
            'own_update' => $this->own_update,
            'own_select' => $this->own_select,
        ]);

        $query->andFilterWhere(['like', 'community_id', $this->community_id]);

        return $dataProvider;
    }
}
