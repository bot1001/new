<?php
/**
 * Created by PhpStorm.
 * User: 影
 * Date: 2018/11/7
 * Time: 21:07
 */

namespace app\models;

use yii\data\ActiveDataProvider;
use yii\base\Model;
use yii\db\Query;

class AccumulateSearch extends Model
{
    public $name;
    public $amount;

    public function rules()
    {
        return [
            [['amount'], 'integer'],
            [['name', 'amount'], 'safe'],
        ];
    }

    public function search($params){
        $query = (new Query())
            ->select('store_accumulate.account_id as id, user_data.real_name as name, sum(store_accumulate.amount) as amount')
            ->from('store_accumulate')
            ->join('inner join', 'user_data', 'user_data.account_id = store_accumulate.account_id')
            ->groupBy('store_accumulate.account_id');
        $query = (new Query())->from([ $query ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' =>[
                'pageSize' => '15',
            ],
            'sort' => [
                'attributes' => [
                    'name',
                    'amount'
                ],
            ]
        ]);

        $this->load($params);

        if(!empty($_GET['name'])){
            $name = $_GET['name'];
            $query->andFilterWhere(['like', 'name', $name]);
        }

        $from = '0'; //设置最小积分
        $to = '10000000'; //设置最大积分
        if(!empty($_GET['from'])){
            $from = $_GET['from'];
        }
        if(!empty($_GET['to'])) {
            $to = $_GET['to'];
        }

        $query->andFilterWhere(['between', 'amount', $from, $to]);

        return $dataProvider;
    }
}