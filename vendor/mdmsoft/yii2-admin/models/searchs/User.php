<?php

namespace mdm\admin\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SysUser;

/**
 * User represents the model behind the search form about `mdm\admin\models\User`.
 */
class User extends SysUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'company', 'role', 'status', 'create_id', 'update_id'], 'integer'],
            [['community', 'real_name', 'name', 'phone', 'password', 'comment', 'salt', 'create_time', 'update_time', 'new_pd'], 'safe'],
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
         $query = SysUser::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            $query->where('1=0');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'company' => $this->company,
            'role' => $this->role,
            'status' => $this->status,
            'create_id' => $this->create_id,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'update_id' => $this->update_id,
        ]);

        $query->andFilterWhere(['like', 'community', $this->community])
            ->andFilterWhere(['like', 'real_name', $this->real_name])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'salt', $this->salt])
            ->andFilterWhere(['like', 'new_pd', $this->new_pd]);

        return $dataProvider;
    }
}
