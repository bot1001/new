<?php

namespace backend\controllers;

use Yii;
use app\models\HouseInfo;
use app\models\CommunityBuilding;
use app\models\CommunityBasic;
use app\models\houseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use kartik\grid\EditableColumnAction;

/**
 * HouseController implements the CRUD actions for HouseInfo model.
 */
class HouseController extends Controller
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

    /**
     * Lists all HouseInfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new houseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		$comm = $_SESSION['community']; //从回话中获取小区编号
		$community = CommunityBasic::find() //查询和账户相关联的小区名称
			->select('community_name, community_id')
			->where(['in', 'community_id', $comm])
			->orderBy('community_name DESC')
			->indexBy('community_id')
			->column();
		
		$building =CommunityBuilding::find() //查询和账户向关联的楼宇
			->select('building_name')
			->where(['in', 'community_id', $comm])
			->distinct()
			->orderBy('building_name ASC')
			->indexBy('building_name')
			->column();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'community' => $community,
			'building' => $building
        ]);
    }
	
	//GridView页面直接编辑
	public function actions()
   {
       return ArrayHelper::merge(parent::actions(), [
           'house' => [
               'class' => EditableColumnAction::className(),     
               'modelClass' => HouseInfo::className(),                // the update model class
               'outputValue' => function ($model, $attribute, $key, $index) {
               },
               'ajaxOnly' => true,
           ]
       ]);
   }

    /**
     * Displays a single HouseInfo model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

	public function actionView01($id)
    {
		$model = HouseInfo::find()->where(['realestate' => $id])->asArray()->all();
		
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new HouseInfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new HouseInfo();
		
		$comm = $_SESSION['community']; //从回话中获取小区编号
		$community = CommunityBasic::find() //查询和账户相关联的小区名称
			->select('community_name, community_id')
			->where(['in', 'community_id', $comm])
			->orderBy('community_name DESC')
			->indexBy('community_id')
			->column();
		
		$building =CommunityBuilding::find() //查询和账户向关联的楼宇
			->select('building_name')
			->where(['in', 'community_id', $comm])
			->distinct()
			->orderBy('building_name ASC')
			->indexBy('building_name')
			->column();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
			'community' => $community,
			'building' => $building
        ]);
    }

    /**
     * Updates an existing HouseInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		
		$comm = $_SESSION['community']; //从回话中获取小区编号
		$community = CommunityBasic::find() //查询和账户相关联的小区名称
			->select('community_name, community_id')
			->where(['in', 'community_id', $comm])
			->orderBy('community_name DESC')
			->indexBy('community_id')
			->column();
		
		$building =CommunityBuilding::find() //查询和账户向关联的楼宇
			->select('building_name')
			->where(['in', 'community_id', $comm])
			->distinct()
			->orderBy('building_name ASC')
			->indexBy('building_name')
			->column();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->house_id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
			'community' => $community,
			'building' => $building
        ]);
    }

    /**
     * Deletes an existing HouseInfo model.
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
     * Finds the HouseInfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HouseInfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HouseInfo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
