<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserInvoice;

/**
 * InvoiceSumSearch represents the model behind the search form of `app\models\UserInvoice`.
 */
class InvoiceSumSearch extends UserInvoice
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['invoice_id', 'building_id', 'realestate_id', 'invoice_status'], 'integer'],
            [['year', 'month', 'description', 'payment_time', 'from', 'community_id', 'create_time', 'order_id', 'invoice_notes', 'payment_time', 'update_time'], 'safe'],
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
        if($_SESSION['user']['community']){
			$query = UserInvoice::find()->where(['user_invoice.community_id' => $_SESSION['user']['community']]);
		}else{
			$query = UserInvoice::find();
		}

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		
		if(!empty($this->from)) //如果查找费项名称时间不为空，执行以下代码
		{
			$time = explode(' to ',$this->from);
			
			$from02 = reset($time); //起始年月 str_pad($m,2,"0",STR_PAD_LEFT)
			$to02 = end($time); //截止年月
			
			$f = explode('-', $from02); //拆分起始年月
			$t = explode('-', $to02); //拆分截止年月
			
            $year01 = reset($f); //提取起始年
            $year02 = reset($t); //提取截止年
			
            $month01 = str_pad(end($f),2, '0', STR_PAD_LEFT); //提取起始月并自动补“0”
			$month02 = str_pad(end($t), 2, '0', STR_PAD_LEFT); //提取截止月并自动补“0”
			
			$day = date("t",strtotime("$year02-$month02")); //获取截止日期天数
			$from = $year01.'-'.$month01.'-'.'01'; //拼接起始日期
			$to = $year02.'-'.$month02.'-'.$day; //拼接截止日期
			
			if($year01 == $year02) //如果筛选的起始年份和截止年份相等，执行以下代码
			{
				$query->andFilterWhere(['between', 'month', $month01, $month02]);
			}
			$query->andFilterWhere(['between', 'year', $year01, $year02]);
		}/*else{
			$query->andFilterWhere(['like', 'year', date('Y')])
				->andFilterWhere(['in', 'month', date('m')]);
		}*/	
		
		if($this->payment_time !== '') //如果支付时间为空 执行以下代码
		{ 
			$time01 = explode(' to ',$this->payment_time);
			$one = (reset($time01));
			$two = end($time01);
            $query->andFilterWhere(['between', 'payment_time', strtotime($one),strtotime($two)]);
		}elseif($this->from !== ''){
			//如果费项日期为空，执行以下代码
			$query ->andFilterWhere(['between', 'payment_time', strtotime($from), strtotime($to)]);
		}elseif($this->invoice_status !== ''){
			//以上两个条件不成成立，执行以下代码
			$query->andFilterWhere(['like', 'payment_time', $this->payment_time]);
		}else{
			$query->andFilterWhere(['between', 'payment_time', strtotime(date('Y-m-d')), date(time()) ]);
		}
		
        // grid filtering conditions
        $query->andFilterWhere([
            'invoice_id' => $this->invoice_id,
            'building_id' => $this->building_id,
            'realestate_id' => $this->realestate_id,
            'invoice_amount' => $this->invoice_amount,
            'invoice_status' => $this->invoice_status,
        ]);

      $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['in', 'community_id', $this->community_id])
            ->andFilterWhere(['like', 'create_time', $this->create_time])
            ->andFilterWhere(['like', 'order_id', $this->order_id])
            ->andFilterWhere(['like', 'invoice_notes', $this->invoice_notes])
            ->andFilterWhere(['like', 'update_time', $this->update_time]);

        return $dataProvider;
    }
}
