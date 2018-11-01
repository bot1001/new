<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Products;

/**
 * ProductSearch represents the model behind the search form of `common\models\Products`.
 */
class ProductSearch extends Products
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'product_quantity', 'sale'], 'integer'],
            [['order_id', 'product_id', 'store_id', 'product_name'], 'safe'],
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
        $query = Products::find();
//        $query->joinWith('order')->where(['order_basic.order_type' => '2']);

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
            'product_quantity' => $this->product_quantity,
            'sale' => $this->sale,
            'product_price' => $this->product_price,
        ]);

        $query->andFilterWhere(['like', 'order_id', $this->order_id])
            ->andFilterWhere(['like', 'product_id', $this->product_id])
            ->andFilterWhere(['like', 'store_id', $this->store_id])
            ->andFilterWhere(['like', 'product_name', $this->product_name]);

        return $dataProvider;
    }
}
