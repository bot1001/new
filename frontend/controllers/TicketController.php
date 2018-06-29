<?php

namespace frontend\controllers;

use Yii;
use common\models\TicketBasic;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
		
		
        return $this->render('index', [
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
	
    /**
     * Creates a new TicketBasic model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        return $this->renderAjax('create', [
            'model' => $model,
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
