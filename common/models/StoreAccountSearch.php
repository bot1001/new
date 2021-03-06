<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StoreAccount;

/**
 * StoreAccountSearch represents the model behind the search form of `common\models\StoreAccount`.
 */
class StoreAccountSearch extends StoreAccount
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'work_number', 'role', 'status'], 'integer'],
            [['user_id','property', 'store_id', 'phone'], 'safe'],
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
        $query = StoreAccount::find();
        $query->joinWith('user')
        ->joinWith('store');

        $user = $_SESSION['user'];
        $market = array_column($user, 'market');

        if(in_array('2', $market)){ //判断是否是商城用户
            $user_id = Yii::$app->user->id;
            $query -> andWhere(['in', 'store_account.user_id', $user_id]);
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
            'id' => $this->id,
            'work_number' => $this->work_number,
            'role' => $this->role,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'sys_user.name', $this->user_id])
              ->andFilterWhere(['like', 'store_basic.store_name', $this->store_id])
              ->andFilterWhere(['like', 'sys_user.phone', $this->phone])
              ->andFilterWhere(['like', 'property', $this->property]);

        $dataProvider->sort->attributes['phone'] =
            [
                'desc' => ['phone' => SORT_DESC],
                'asc' => ['phone' => SORT_ASC],
            ];

        return $dataProvider;
    }
}
