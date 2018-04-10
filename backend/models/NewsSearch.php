<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CommunityNews;

/**
 * NewsSearch represents the model behind the search form of `app\models\CommunityNews`.
 */
class NewsSearch extends CommunityNews
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['news_id', 'community_id', 'post_time', 'update_time', 'view_total', 'stick_top', 'status'], 'integer'],
            [['title', 'excerpt', 'content'], 'safe'],
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
		$c = $_SESSION['user']['community'];
		
		if(!empty($c)){
			$query = CommunityNews::find()->where(['community_id' => "$c"]);
		}else{
			$query = CommunityNews::find();
		}
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort' =>[
		    	'defaultOrder' => [
			        'status' => SORT_ASC,
		        	'stick_top' => SORT_DESC,
		        	'news_id' => SORT_DESC
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
            'news_id' => $this->news_id,
            'community_id' => $this->community_id,
            'post_time' => $this->post_time,
            'update_time' => $this->update_time,
            'view_total' => $this->view_total,
            'stick_top' => $this->stick_top,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'excerpt', $this->excerpt])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
