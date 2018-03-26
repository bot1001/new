<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserRelationshipRealestate;

/**
 * UserRelationshipRealestateSearch represents the model behind the search form about `app\models\UserRelationshipRealestate`.
 */
class UserRelationshipRealestateSearch extends UserRelationshipRealestate
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'realestate_id'], 'integer'],
            [['id','account_id', 'realestate_id'], 'required'],
            [['account_id'], 'safe'],
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
        $query = UserRelationshipRealestate::find();

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
            'realestate_id' => $this->realestate_id,
        ]);

        $query->andFilterWhere(['like', 'account_id', $this->account_id]);

        return $dataProvider;
    }
}
