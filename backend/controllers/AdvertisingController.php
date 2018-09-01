<?php

namespace backend\controllers;

use Yii;
use common\models\Advertising;
use app\models\AdvertisingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\grid\EditableColumnAction;
use yii\helpers\ArrayHelper;

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
        return ArrayHelper::merge(parent::actions(), [
            'advertising' => [                                       // identifier for your editable action
                'class' => EditableColumnAction::className(),     // action class name
                'modelClass' => Advertising::className(),                // the update model class
                'outputValue' => function ($model, $attribute, $key, $index) {
                },
                'ajaxOnly' => true,
            ],
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => 'http://'.$_SERVER['HTTP_HOST'],//图片访问路径前缀
                    "imagePathFormat" => "/img/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                    "imageMaxSize" => 1024000,
                ],
            ]
        ]);
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
		$model = Advertising::find()
			->select('ad_id as id, ad_title as title, ad_excerpt as excerpt,
			          ad_poster as poster, ad_publish_community as community,
			          ad_type as type, ad_target_value as value, ad_location as location,
			          from_unixtime(ad_created_time) as create_time, from_unixtime( ad_end_time ) as end_time,
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
		$status = [0 => '待审核', '1' => '上架', '2' => '下架', 3 => '审核失败'];

        //重新赋值发布平台起
		$platform = [1=>'APP',2=>'PC', 3=> '微信'];
        $value = explode(',', $model['value']); //分裂数组
        $result = '';
        foreach ($value as $v){
            $result .= $platform[$v].' '; //组合数组
        }
        $model['value'] = $result;
        //重新赋值发布平台止
		
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
			$model->ad_location  = $post['ad_location'];
			$model->ad_sort  = $post['ad_sort'];

			$value = $post['ad_target_value'];
			$value = explode(',', $value); //重组发布平台信息
            $model->ad_target_value = $value;

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
			$model->ad_poster  = $post['ad_poster'];
			$model->ad_type  = $post['ad_type'];
			$model->ad_target_value  = '1';
			$model->ad_location  = $post['ad_location'];
			$model->ad_sort  = $post['ad_sort'];

            $value = $post['ad_target_value'];
            $value = implode(',', $value); //重组发布平台信息
            $model->ad_target_value = $value;

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
