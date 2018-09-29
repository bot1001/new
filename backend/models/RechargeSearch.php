<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Recharge;

/**
 * RechargeSearch represents the model behind the search form of `common\models\Recharge`.
 */
class RechargeSearch extends Recharge
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type'], 'integer'],
            [['name', 'creater', 'property', 'create_time'], 'safe'],
            [['price'], 'number'],
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
        $query = Recharge::find();
        $query->joinWith('user');

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

        //处理搜索条件
        if($this->create_time != '')
        {
            $time = explode(' to ', $this->create_time); //分割搜索时间
            $first = strtotime(reset($time)); //转换成时间戳
            $second = strtotime(end($time));
//            print_r($second);exit;

            $query->andFilterWhere(['between', 'recharge.create_time', $first, $second]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'price' => $this->price,
            'type' => $this->type,
//            'create_time' => $this->create_time,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'sys_user.name', $this->creater])
            ->andFilterWhere(['like', 'property', $this->property]);

        return $dataProvider;
    }
}
