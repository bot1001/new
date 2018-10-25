<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrderAddress;

/**
 * AddressSearch represents the model behind the search form of `common\models\OrderAddress`.
 */
class AddressSearch extends OrderAddress
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'province_id', 'city_id', 'area_id'], 'integer'],
            [['order_id', 'address', 'zipcode', 'mobile_phone', 'name', 'create_time', 'payment_time', 'status', 'amount', 'way'], 'safe'],
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
        $query = OrderAddress::find();
        $query->joinWith('order')
            ->joinWith('products')
        ->where(['order_basic.order_type' => '2']);

        $user = $_SESSION['user'];
        $market = array_column($user, 'market');

        if(in_array('2', $market)){ //判断是否是商城用户
            $store_id = $_SESSION['community'];
            $query -> andWhere(['in', 'order_products.store_id', $store_id]);
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
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
            'order_basic.payment_gateway' => $this->way,
            'order_basic.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'order_id', $this->order_id])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'zipcode', $this->zipcode])
            ->andFilterWhere(['like', 'mobile_phone', $this->mobile_phone])
            ->andFilterWhere(['like', 'name', $this->name]);

        $dataProvider->sort->attributes['status'] =
            [
              'asc' => ['order_basic.status' => SORT_ASC],
              'desc' => ['order_basic.status' => SORT_ASC],
            ];

        $dataProvider->sort->attributes['way'] =
            [
              'asc' => ['order_basic.payment_gateway' => SORT_ASC],
              'desc' => ['order_basic.payment_gateway' => SORT_ASC],
            ];

        return $dataProvider;
    }
}
