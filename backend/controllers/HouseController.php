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
		$community = CommunityBasic::find()
			->select('community_name')
			->indexBy('community_name')
			->column();
				
		$building =CommunityBuilding::find() //查询和账户向关联的楼宇
			->select('building_name')
			->where(['in', 'community_id', $_SESSION['community']])
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
        $model = $this->findModel($id); //实例化模型
        $ids = HouseInfo::find()->where(['house_id' => "$id"])->select('realestate')->asArray()->one();

        //房屋信息
        $reale = (new yii\db\Query())
            ->select('community_basic.community_name as community, community_building.building_name as building, community_realestate.room_number as number, community_realestate.room_name as room')
            ->from('community_realestate')
            ->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
            ->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id')
            ->where(['community_realestate.realestate_id' => $ids['realestate']])
            ->one();

        //拼接房屋信息
        $room = $reale['community'].' '.$reale['building'].' '.$reale['number'].'单元 '. $reale['room'];
        return $this->render('view', [
            'model' => $model,
            'room' => $room
        ]);
    }

	public function actionIndex01($id)
    {
        $searchModel = new houseSearch();
        $searchModel->realestate = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        //房屋信息
        $reale = (new yii\db\Query())
            ->select('community_basic.community_name as community, community_building.building_name as building, community_realestate.room_number as number, community_realestate.room_name as room')
            ->from('community_realestate')
            ->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
            ->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id')
            ->where(['community_realestate.realestate_id' => "$id"])
            ->one();

        //拼接房屋信息
        $room = $reale['community'].' '.$reale['building'].' '.$reale['number'].'单元 '. $reale['room'];

        return $this->renderAjax('index01', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'room' => $room,
            'id' => $id
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
			'building' => $building
        ]);
    }

    function actionC()
    {
        $model= New houseInfo;
        $get=$_GET;
        if(isset($get['room'] ) && isset($get['id'])){
            $room=$get['room'];
            $id=$get['id'];
        }else{
            $session=Yii::$app->session;
            $session->setFlash('fail', '2');
            return $this->redirect(Yii::$app->request->referrer);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('form',[
            'model'=>$model,
            'id' => $id,
            'room'=>$room
        ]);
    }

    /**
     * Updates an existing HouseInfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        $model= New houseInfo;
        $get=$_GET;
        if(isset($get['id'])){
            $id=$get['id'];
        }else{
            $session=Yii::$app->session;
            $session->setFlash('fail', '1');
            return $this->redirect(Yii::$app->request->referrer);
        }
        $model = $this->findModel($id);

        $ids = HouseInfo::find()->where(['house_id' => "$id"])->select('realestate')->asArray()->one();

        //房屋信息
        $reale = (new yii\db\Query())
            ->select('community_basic.community_name as community, community_building.building_name as building, community_realestate.room_number as number, community_realestate.room_name as room')
            ->from('community_realestate')
            ->join('inner join', 'community_basic', 'community_basic.community_id = community_realestate.community_id')
            ->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id')
            ->where(['community_realestate.realestate_id' => $ids['realestate']])
            ->one();

        //拼接房屋信息
        $room = $reale['community'].' '.$reale['building'].' '.$reale['number'].'单元 '. $reale['room'];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->house_id]);
        }

        return $this->render('form', [
            'model' => $model,
            'room' => $room,
            'id'=> $id
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
