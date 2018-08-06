<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\InvoiceDel;

/**
 * InvoiceDelSearch represents the model behind the search form of `common\models\InvoiceDel`.
 */
class InvoiceDelSearch extends InvoiceDel
{
    function attributes()
    {
        return array_merge(parent::attributes(),['community', 'building','number', 'name', 'user']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id', 'realestate_id', 'user_id', 'invoice_status'], 'integer'],
            [['description', 'year', 'month', 'order_id', 'invoice_notes', 'payment_time', 'update_time', 'property', 'community', 'building','number', 'name', 'user'], 'safe'],
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
        $query = InvoiceDel::find();
        $query->joinWith('community');
        $query->joinWith('building');

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
            'invoice_id' => $this->invoice_id,
            'realestate_id' => $this->realestate_id,
            'invoice_amount' => $this->invoice_amount,
            'user_id' => $this->user_id,
            'invoice_status' => $this->invoice_status,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'year', $this->year])
            ->andFilterWhere(['like', 'month', $this->month])
            ->andFilterWhere(['like', 'order_id', $this->order_id])
            ->andFilterWhere(['like', 'invoice_notes', $this->invoice_notes])
            ->andFilterWhere(['like', 'payment_time', $this->payment_time])
            ->andFilterWhere(['like', 'update_time', $this->update_time])
            ->andFilterWhere(['like', 'property', $this->property]);

        return $dataProvider;
    }
}
