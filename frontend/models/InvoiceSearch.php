<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Invoice;

/**
 * InvoiceSearch represents the model behind the search form of `common\models\Invoice`.
 */
class InvoiceSearch extends Invoice
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id', 'community_id', 'building_id', 'realestate_id', 'invoice_status'], 'integer'],
            [['year', 'month', 'description', 'create_time', 'order_id', 'invoice_notes', 'payment_time', 'update_time'], 'safe'],
            [['invoice_amount'], 'number'],
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
		$id = $_SESSION['home']['id']; //提取关联房屋id
		$community = $_SESSION['home']['community_id']; //提取关联小区ID
		$buiding = $_SESSION['home']['building_id']; //提取关联楼宇ID
		
        $query = Invoice::find()->where(['community_id' => "$community", 'building_id' => "$buiding", 'realestate_id' => "$id"]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => [
			'pageSize' => 15,
		    ],
			'sort' => [
			     'defaultOrder' =>[
			           'invoice_status' => SORT_ASC,
			           'year' => SORT_DESC,
			           'month' => SORT_DESC,
			           'description' => SORT_DESC,
		              ]
		         ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'invoice_id' => $this->invoice_id,
            'community_id' => $this->community_id,
            'building_id' => $this->building_id,
            'realestate_id' => $this->realestate_id,
            'invoice_amount' => $this->invoice_amount,
            'invoice_status' => $this->invoice_status,
        ]);

        $query->andFilterWhere(['like', 'year', $this->year])
            ->andFilterWhere(['like', 'month', $this->month])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'create_time', $this->create_time])
            ->andFilterWhere(['like', 'order_id', $this->order_id])
            ->andFilterWhere(['like', 'invoice_notes', $this->invoice_notes])
            ->andFilterWhere(['like', 'payment_time', $this->payment_time])
            ->andFilterWhere(['like', 'update_time', $this->update_time]);

        return $dataProvider;
    }
}
