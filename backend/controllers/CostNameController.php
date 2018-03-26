<?php

namespace backend\controllers;

use Yii;
use app\models\CostName;
use app\models\CostNameSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CostNameController implements the CRUD actions for CostName model.
 */
class CostNameController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all CostName models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CostNameSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	//检查用户是否登录
	public function  beforeAction($action)
    {
        if(Yii::$app->user->isGuest){
            $this->redirect(['/login']);
            return false;
        }
        return true;
    }

    /**
     * Displays a single CostName model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CostName model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
		$role = $_SESSION[ 'user' ][ 'role' ];
    	if ( $role == 1 || $role == 14 || $role == 10 || $role == 7) {

    		$model = new CostName();

    		if ( $model->load( Yii::$app->request->post() ) && $model->save() ) {
    			return $this->redirect( [ 'index'] );
    		} else {
    			return $this->renderAjax( 'create', [
    				'model' => $model,
    			] );
    		}
    	} else {
    		$session = Yii::$app->session;
    		$session->setFlash( 'm', '1' );
    		return $this->redirect( Yii::$app->request->referrer );
    	}
    }

    /**
     * Updates an existing CostName model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
		$role = $_SESSION[ 'user' ][ 'role' ];
    	if ( $role == 1 || $role == 14 || $role == 10 || $role == 7) 
		{
    		$model = $this->findModel( $id );
    		if ( $model->load( Yii::$app->request->post() ) && $model->save() ) {
    			return $this->redirect( Yii::$app->request->referrer );
    		} else {
    			return $this->renderAjax( 'update', [
    				'model' => $model,
    			] );
    		}
    	} else {
    		/*$session = Yii::$app->session;
    		$session->setFlash( 'm', '1' );*/
			echo "<script>alert('权限不足，请返回')</script>";
			exit;
    		return $this->redirect( Yii::$app->request->referrer );
    	}
    }

    /**
     * Deletes an existing CostName model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CostName model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CostName the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CostName::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
