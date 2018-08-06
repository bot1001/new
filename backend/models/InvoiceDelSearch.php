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
            [['amount'], 'number'],
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
        $c = $_SESSION['community'];
        $query = InvoiceDel::find();
        $query->joinWith('community')->where(['in', 'community_basic.community_id' , $c]);
        $query->joinWith('building');
        $query->joinWith('user');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder'=>
                [
                    'invoice_id' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //自定义搜索字段
        if($this->payment_time != ''){
            $payment_time = $this->payment_time;
            $times = explode(' to ', $payment_time);
            $query->andFilterWhere(['between', 'payment_time', strtotime(reset($times)), strtotime(end($times))]);
        }

        if($this->update_time != '')
        {
            $update_time = $this->update_time;
            $time = explode(' to ', $update_time);
            $query->andFilterWhere(['between', 'invoice_del.update_time', strtotime(reset($time)), strtotime(end($time))]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'invoice_id' => $this->invoice_id,
            'realestate_id' => $this->realestate_id,
            'user_id' => $this->user_id,
            'invoice_status' => $this->invoice_status,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'year', $this->year])
            ->andFilterWhere(['like', 'month', $this->month])
            ->andFilterWhere(['like', 'order_id', $this->order_id])
            ->andFilterWhere(['like', 'amount', $this->amount])
            ->andFilterWhere(['like', 'invoice_notes', $this->invoice_notes])
            ->andFilterWhere(['like', 'community_basic.community_id', $this->community])
            ->andFilterWhere(['like', 'community_building.building_name', $this->building])
            ->andFilterWhere(['like', 'community_realestate.room_number', $this->number])
            ->andFilterWhere(['like', 'community_realestate.room_name', $this->name])
            ->andFilterWhere(['like', 'sys_user.name', $this->user])
            ->andFilterWhere(['like', 'property', $this->property]);

        $dataProvider->sort->attributes['community'] =
            [
                'asc' => ['community_basic.community_id' => SORT_ASC],
                'desc' => ['community_basic.community_id' => SORT_DESC]
            ];

        $dataProvider->sort->attributes['building'] =
            [
                'asc' => ['community_building.building_id' => SORT_ASC],
                'desc' => ['community_building.building_id' => SORT_DESC],
            ];

        $dataProvider->sort->attributes['number'] =
            [
                'asc' => ['community_realestate.room_number' => SORT_ASC],
                'desc' => ['community_realestate.room_number' => SORT_DESC],
            ];

        $dataProvider->sort->attributes['name'] =
            [
                'asc' => ['community_realestate.room_name' => SORT_ASC],
                'desc' => ['community_realestate.room_name' => SORT_DESC],
            ];

        $dataProvider->sort->attributes['user'] =
            [
                'asc' => ['sys_user.name' => SORT_ASC],
                'desc' => ['sys_user.name' => SORT_DESC]
            ];

        return $dataProvider;
    }
}
