<?php

namespace backend\controllers;

use Yii;
use app\models\TicketBasic;
use app\models\TicketSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\CommunityBasic;
use app\models\WorkR;
use app\models\CommunityBuilding;
use yii\helpers\ArrayHelper;
use kartik\grid\EditableColumnAction;

/**
 * TicketController implements the CRUD actions for TicketBasic model.
 */
class TicketController extends Controller
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
     * Lists all TicketBasic models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
	    $c = $_SESSION['user']['community'];
	    if(empty($c)){
	    	$comm = CommunityBasic::find()
	    		->select('community_name, community_id')
	    		->indexBy('community_id')
	    		->column();
			
	    	$build = CommunityBuilding::find()
	    		->select('building_name')
	    		->distinct()
	    		->indexBy('building_name')
	    		->column();
	    }else{
	    	$comm = CommunityBasic::find()
	    		->select(' community_name')
	    		->where(['community_id' => $c])
				->orderBy('community_name DESC')
	    		->indexBy('community_id')
	    		->column();
			
	    	$build = CommunityBuilding::find()
	    		->select('building_name')
	    		->where(['community_id' => $c])
	    		->distinct()
	    		->indexBy('building_name')
	    		->column();
	    }
		
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'comm' => $comm,
			'build' => $build
        ]);
    }

    /**
     * Displays a single TicketBasic model.
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
	
	public function actions() 
	{
		return ArrayHelper::merge( parent::actions(), [
			'ticket' => [ // identifier for your editable action
				'class' => EditableColumnAction::className(), // action class name
				'modelClass' => TicketBasic::className(), // the update model class
				'outputValue' => function ( $model, $attribute, $key, $index ) {},
				'ajaxOnly' => true,
			]
		] );
	}

    /**
     * Creates a new TicketBasic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TicketBasic();
		
		//获取用户绑定的小区
		$c = $_SESSION['user']['community'];
		if($c){
			//获取数组小区
			$community = CommunityBasic::find()
			->select('community_name, community_id')
			->where(['community_id' => "$c"])
			->orderBy('community_name')
			->indexBy('community_id')
			->column();
			
		    //获取处理人数据
		    $assignee = WorkR::find()->select('user_data.real_name, work_relationship_account.account_id')
		    	->joinWith('data')
		    	->where(['community_id' => "$c", 'account_superior' => '0'/*, 'account_status' => '3'*/])
		        ->indexBy('account_id')
		    	->orderBy('community_id')
		    	->column();
		}else{
		    $community = CommunityBasic::find()
		    	->select('community_name, community_id')
		    	->orderBy('community_name')
		    	->indexBy('community_id')
		    	->column();
			
			$assignee = WorkR::find()->select('user_data.real_name, work_relationship_account.account_id')
				->joinWith('data')
				->where(['account_superior' => '0'/*, 'account_status' => '3'*/])
			    ->indexBy('account_id')
				->orderBy('community_id')
				->column();
		}

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
			'community' => $community,
			'assignee' => $assignee
        ]);
    }

    /**
     * Updates an existing TicketBasic model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ticket_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TicketBasic model.
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
     * Finds the TicketBasic model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TicketBasic the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TicketBasic::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
