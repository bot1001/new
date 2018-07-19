<?php

namespace backend\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use kartik\grid\EditableColumnAction;
use app\models\TicketReply;
use app\models\TicketBasic;
use app\models\WorkR;
use app\models\TicketReplySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TicketReplyController implements the CRUD actions for TicketReply model.
 */
class TicketReplyController extends Controller
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
     * Lists all TicketReply models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TicketReplySearch();
		
		if(isset($_GET['id']))
		{
			$id = $_GET['id'];
			$ids = TicketReply::find()
                ->select('reply_id')
                ->where(['ticket_id' => "$id"])
                ->asArray()
                ->one();

			return $this->renderAjax('view', [
			    'model' => $this->findModel($ids)
            ]);
		}else{
			$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
		}
    }
	
	//GridView 页面直接编辑代码
	public function actions()
   {
       return ArrayHelper::merge(parent::actions(), [
           'reply' => [                                       // identifier for your editable action
               'class' => EditableColumnAction::className(),     // action class name
               'modelClass' => TicketReply::className(),                // the update model class
               'outputValue' => function ($model, $attribute, $key, $index) {
               },
               'ajaxOnly' => true,
           ]
       ]);
   }

    /**
     * Displays a single TicketReply model.
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

    /**
     * Creates a new TicketReply model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new TicketReply();
		
		if($id){
			$model->ticket_id = $id;
		}

        if ($model->load(Yii::$app->request->post()))
		{
			$model = new TicketReply(); //实例化模型
			$post = $_POST['TicketReply']; //接收传值
			
			$transaction = Yii::$app->db->beginTransaction();
			try{
				$ticket = TicketBasic::updateAll(['assignee_id' => $post['account_id']], 
												  'ticket_id = :id', [':id' => $post['ticket_id']]);
			
				if($ticket == '1'){
					$model->ticket_id = $post['ticket_id'];
					$model->account_id = $post['account_id'];
					$model->content = $post['content'];
					
					$e = $model->save();
				}
				
				if($e == 1)
				{
					$transaction->commit();
				}else{
					$transaction->rollback();
					return $this->redirect(Yii::$app->request->referrer);
				}
			}catch(\Exception $e){
				print_r($e);
			}
            return $this->redirect(['/ticket/index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TicketReply model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->reply_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TicketReply model.
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
     * Finds the TicketReply model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TicketReply the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TicketReply::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
