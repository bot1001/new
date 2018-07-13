<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SmsLog;

/**
 * SmsLogSearch represents the model behind the search form of `app\models\SmsLog`.
 */
class SmsLogSearch extends SmsLog
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'count', 'success'], 'integer'],
            [['sign_name', 'sms', 'property', 'sms_time'], 'safe'],
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
        $query = SmsLog::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' =>[
                    'id' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        //重新改写发送时间字段逻辑
        if(!empty($this->sms_time))
        {
            $time = $this->sms_time;
            $sms_time = explode(' to ', $time);

            $from = reset($sms_time);
            $to = end($sms_time);

            $query->andFilterWhere(['between', 'sms_time', strtotime($from), strtotime($to)]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'count' => $this->count,
            'success' => $this->success,
        ]);

        $query->andFilterWhere(['like', 'sign_name', $this->sign_name])
            ->andFilterWhere(['like', 'sms', $this->sms])
            ->andFilterWhere(['like', 'property', $this->property]);

        return $dataProvider;
    }
}
