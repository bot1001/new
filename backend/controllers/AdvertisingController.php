<?php

namespace backend\controllers;

use Yii;
use app\models\Advertising;
use app\models\AdvertisingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AdvertisingController implements the CRUD actions for Advertising model.
 */
class AdvertisingController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
	
	public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => 'http://'.$_SERVER['HTTP_HOST'],//图片访问路径前缀
                    "imagePathFormat" => "/images/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                ],
            ]
        ];
    }

    /**
     * Lists all Advertising models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdvertisingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Advertising model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
		$model = \app\models\Advertising::find()
			->select('ad_id as id, ad_title as title, ad_excerpt as excerpt,
			          ad_poster as poster, ad_publish_community as community,
			          ad_type as type, ad_target_value as value, ad_location as location,
			          ad_created_time as time, 
					  ad_sort as sort, ad_status as status,property')
			->where(['ad_id' => "$id"])
			->asArray()
			->one();
		
		$com = explode(',', $model['community']); //拆分小区
		//查找小区
		$community= \app\models\CommunityBasic::find()
			->select('community_name, community_id')
			->where(['in', 'community_id', $com])
			->indexBy('community_id')
			->orderBy('community_id')
			->column();
		$type = ['1' => '文章', '2' => '链接'];
		$location = ['1' => '顶部', '2' => '底部'];
		$status = ['1' => '正常', '2' => '删除'];
		
		$type = $type[$model['type']];
		$location = $location[$model['location']];
		$status = $status[$model['status']];
		
        return $this->render('view', [
            'model' => $model,
			'community' => $community,
			'type' => $type,
			'location' => $location,
			'status' => $status
        ]);
    }

    /**
     * Creates a new Advertising model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Advertising();

        if ($model->load(Yii::$app->request->post()))
		{
			$post = $_POST['Advertising']; //接收传过来的数据
			
			$model->ad_title  = $post['ad_title'];
			$model->ad_excerpt  = $post['ad_excerpt'];
			$model->ad_poster  = $post['ad_poster'];
			$model->ad_type  = $post['ad_type'];
			$model->ad_target_value  = $post['ad_target_value'];
			$model->ad_location  = $post['ad_location'];
			$model->ad_sort  = $post['ad_sort'];
			$model->ad_status  = $post['ad_status'];
			
			$community = $post['ad_publish_community']; //接收发布小区
			$comm = implode(',', $community); //重组小区
			
			$model->ad_publish_community  = $comm;
			$model->property  = $post['property'];
			
			$model->save();
			
            return $this->redirect(['view', 'id' => $model->ad_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Advertising model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()))
		{
			$post = $_POST['Advertising']; //接收传过来的数据
			
			$model->ad_title  = $post['ad_title'];
			$model->ad_excerpt  = $post['ad_excerpt'];
			$model->ad_poster  = $post['label_img'];
			$model->ad_type  = $post['ad_type'];
			$model->ad_target_value  = $post['ad_target_value'];
			$model->ad_location  = $post['ad_location'];
			$model->ad_sort  = $post['ad_sort'];
			$model->ad_status  = $post['ad_status'];
			
			$community = $post['ad_publish_community']; //接收发布小区
			$comm = implode(',', $community); //重组小区
			
			$model->ad_publish_community  = $comm;
			$model->property  = $post['property'];
			
			$model->save();
			
            return $this->redirect(['view', 'id' => $model->ad_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Advertising model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Advertising model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Advertising the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Advertising::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
