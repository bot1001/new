<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProductsSearch represents the model behind the search form of `common\models\Products`.
 */
class ProductsSearch extends Products
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_quantity', 'sale'], 'integer'],
            [['order_id', 'product_id', 'store_id', 'product_name', 'phone', 'add', 'name', 'create_time', 'payment_time', 'status', 'amount'], 'safe'],
            [['product_price'], 'number'],
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
        $store = $_SESSION['community'];//获取对应商店id
        $store = array_unique($store);

        $query = Products::find()
        ->joinWith('store')
        ->joinWith('address')
        ->joinWith('order')
        ->Where(['order_products.store_id' => reset($store)]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
//            'sort' => [
//                'attributes' => [
//                    'address',
//                    'phone'
//                ],
//                'defaultOrder' => [
//                    'reg_time' => SORT_DESC,
//                ]
//            ],
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
            'product_quantity' => $this->product_quantity,
            'sale' => $this->sale,
            'product_price' => $this->product_price,
        ]);

        $query->andFilterWhere(['like', 'order_id', $this->order_id])
            ->andFilterWhere(['like', 'order_relationship_address.address', $this->add])
            ->andFilterWhere(['like', 'order_relationship_address.mobile_phone', $this->phone])
            ->andFilterWhere(['like', 'order_relationship_address.name', $this->name])
            ->andFilterWhere(['like', 'order_basic.create_time', $this->create_time])
            ->andFilterWhere(['like', 'order_basic.payment_time', $this->payment_time])
            ->andFilterWhere(['like', 'order_basic.status', $this->status])
            ->andFilterWhere(['like', 'order_basic.order_amount', $this->amount])
            ->andFilterWhere(['like', 'product_id', $this->product_id])
            ->andFilterWhere(['like', 'store_id', $this->store_id])
            ->andFilterWhere(['like', 'product_name', $this->product_name]);

        $dataProvider->sort->attributes['phone'] =
            [
                'asc' => ['mobile_phone' => SORT_ASC],
                'desc' => ['mobile_phone' => SORT_DESC],
            ];

        $dataProvider->sort->attributes['add'] =
            [
                'asc' => ['address' => SORT_ASC],
                'desc' => ['address' => SORT_DESC],
            ];

        $dataProvider->sort->attributes['name'] =
            [
                'asc' => ['name' => SORT_ASC],
                'desc' => ['name' => SORT_DESC],
            ];

        $dataProvider->sort->attributes['status'] =
            [
                'asc' => ['status' => SORT_ASC],
                'desc' => ['status' => SORT_DESC],
            ];

        $dataProvider->sort->attributes['create_time'] =
            [
                'asc' => ['create_time' => SORT_ASC],
                'desc' => ['create_time' => SORT_DESC],
            ];

        $dataProvider->sort->attributes['payment_time'] =
            [
                'asc' => ['payment_time' => SORT_ASC],
                'desc' => ['payment_time' => SORT_DESC],
            ];

        $dataProvider->sort->attributes['amount'] =
            [
                'asc' => ['order_amount' => SORT_ASC],
                'desc' => ['order_amount' => SORT_DESC],
            ];

        return $dataProvider;
    }
}
