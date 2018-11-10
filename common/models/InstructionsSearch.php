<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Instructions;

/**
 * InstructionsSearch represents the model behind the search form of `common\models\Instructions`.
 */
class InstructionsSearch extends Instructions
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'author', 'create_time', 'update_time', 'type', 'status', 'sort'], 'integer'],
            [['title', 'content', 'version', 'property'], 'safe'],
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
        $query = Instructions::find()
            ->joinWith('au');

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
            'author' => $this->author,
            'sort' => $this->sort,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'instructions.status', $this->status])
            ->andFilterWhere(['like', 'property', $this->property]);

        return $dataProvider;
    }
}
