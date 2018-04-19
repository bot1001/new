<?php

namespace backend\controllers;

use Yii;
use app\models\SysCommunity;
use app\models\Company;
use app\models\SysCommunitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SysController implements the CRUD actions for SysCommunity model.
 */
class SysController extends Controller
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
     * Lists all SysCommunity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SysCommunitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		//获取公司相关
		$company = Company::find()
			     ->select('name, id')
			     ->orderBy('name ASC')
			     ->indexBy('id')
			     ->column();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
			'company' => $company
        ]);
    }

    /**
     * Displays a single SysCommunity model.
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
     * Creates a new SysCommunity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SysCommunity();
		
        if ($model->load(Yii::$app->request->post())) 
		{
			//获取用户ID
			$id = $_POST['SysCommunity']['sys_user_id']; //print_r($id);exit;
			$community_id = $_POST['SysCommunity']['community_id']; //接收小区ID
			$c_id = implode(',', $community_id);  //提取小区ID
			$s_c = SysCommunity::find() //对应关联是否存在
				->where(['community_id' => "$id"])
				->asArray()
				->all();
			
			if($s_c){
				$e = SysCommunity::UpdateAll(['community_id' => $c_id],'sys_user_id = :id',[':id' => $id]);
			}else{
				$model->sys_user_id = $id;
			    $model->community_id = "$c_id";
			    $model->own_add = 0;
			    $model->own_delete = '0';
			    $model->own_update = '0';
			    $model->own_select = '0';
			    $e = $model->save();
			}
			
			if($e){
				return $this->redirect(['index']);
			}else{
				return $this->redirect(Yii::$app->request->referrer);
			}
        }
		
		if(isset($_GET['id'])){
			$id = $_GET['id'];
		}else{
			$id = 0;
		}

        return $this->render('create', [
            'model' => $model,
			'id' => $id
        ]);
    }

    /**
     * Updates an existing SysCommunity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SysCommunity model.
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
     * Finds the SysCommunity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SysCommunity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SysCommunity::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
