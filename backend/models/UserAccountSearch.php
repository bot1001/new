<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\UserAccount;

/**
 * UserAccountSearch represents the model behind the search form about `app\models\UserAccount`.
 */
class UserAccountSearch extends UserAccount
{
	public function attributes()
	{
		return array_merge(parent::attributes(),['number']);
	}
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'account_role', 'new_message', 'status'], 'integer'],
            [['account_id', 'number', 'user_name', 'password', 'mobile_phone', 'qq_openid', 'weixin_openid', 'weibo_openid'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = UserAccount::find()->where(['user_account.account_role' =>'1']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' => [
			    'defaultOrder' => [
			        'user_id' => SORT_DESC
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
            'user_id' => $this->user_id,
            'user_account.account_role' => $this->account_role,
            'new_message' => $this->new_message,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'account_id', $this->account_id])
            ->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'work_relationship_account.work_number', $this->number])
            ->andFilterWhere(['like', 'mobile_phone', $this->mobile_phone])
            ->andFilterWhere(['like', 'qq_openid', $this->qq_openid])
            ->andFilterWhere(['like', 'weixin_openid', $this->weixin_openid])
            ->andFilterWhere(['like', 'weibo_openid', $this->weibo_openid]);
		
		$dataProvider -> sort->attributes['number']=
			[
				'asc' => ['work_number'=>SORT_ASC],
				'desc' => ['work_number'=>SORT_DESC],
			];

        return $dataProvider;
    }
}
