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
use app\models\TicketReply;
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
		
	    $c = $_SESSION['community'];
	   
	    $comm = CommunityBasic::find()
	    	->select(' community_name, community_id')
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
		
<<<<<<< HEAD
		//判断是否存在状态参数
=======
		//判断是有是来自处理结果页面的查询
		if(isset($_GET['ticket_id']))
		{
			$id = $_GET['ticket_id'];
			$searchModel->ticket_id = $id;
		}
		
		//判断是否存在状态传值
>>>>>>> master
		if(isset($_GET['ticket_status'])){
			$get = $_GET;
			$searchModel->ticket_status = $get['ticket_status'];
		}
		
<<<<<<< HEAD
		//判断是否存在小区和楼宇参数
		if(isset($_GET['building'])&& isset($_GET['c']))
		{
			$c = $_GET['community'];
			$building = $_GET['building'];;
			$searchModel->community_id = $c;
=======
		//判断是否存在小区传值
		if(isset($_GET['community'])){
			$get = $_GET;
			$searchModel->community_id = $get['community'];
		}
		
		//判断是否存在小区和楼宇传值
		if(isset($_GET['building'])&& isset($_GET['c']))
		{
			$building = $_GET['building'];;
>>>>>>> master
			$searchModel->building = $building;
		}
		
		//配置数据提供器
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
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
		/*$reply = (new \yii\db\Query())->select('ticket_reply.content as content, user_data.real_name as name')
			->from('ticket_basic')
			->join('inner join', ' /*', 'ticket_reply.ticket_id = ticket_basic.ticket_id')
			->join('inner join', 'user_data', 'user_data.account_id = ticket_reply.account_id')
			->join('inner join', 'user_account', 'user_account.account_id = ticket_reply.assignee_id')
			->where(['ticket_basic.ticket_id' => "$id"])
			->orderBy('reply_time DESC')
			->all();*/
		
        return $this->render('view', [
            'model' => $this->findModel($id),
			//'reply' => $reply
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
		$c = $_SESSION['community'];
		
		$community = CommunityBasic::find()
			->select('community_name, community_id')
			->where(['in', 'community_id', $c])
			->orderBy('community_name')
			->indexBy('community_id')
			->column();
				
		$assignee = (new \yii\db\Query())
			->select('user_account.user_name, user_account.account_id')
			->from('user_account')
			->join('inner join', 'work_relationship_account', 'work_relationship_account.account_id = user_account.account_id')
			->andwhere(['user_account.status' => '1'])
			->andwhere(['in', 'work_relationship_account.community_id', $c])
			->orderBy('community_id')
		    ->indexBy('account_id')
			->column();
		
		//随机产生12位数订单号，格式为年+月+日+1到999999随机获取6位数
		$number = date('ymd').str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
		
		$model->ticket_number = $number;

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
			'community' => $community,
			'assignee' => $assignee,
			'number' => $number
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
		
		//获取用户绑定的小区
		$c = $_SESSION['community'];
		
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->ticket_id]);
        }

        return $this->render('update', [
            'model' => $model,
			'community' => $community,
			'assignee' => $assignee
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
