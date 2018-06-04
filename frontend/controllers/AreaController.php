<?php

namespace frontend\controllers;

use Yii;
use common\models\Area;
use app\models\AreaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * AreaController implements the CRUD actions for Area model.
 */
class AreaController extends Controller
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
     * Lists all Area models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AreaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	
	//三级联动之 楼宇
	public function actionCity( $selected = null ) 
	{
		if ( isset( $_POST[ 'depdrop_parents' ] ) ) 
		{
			$id = $_POST[ 'depdrop_parents' ];
			$list = Area::find()
				->andwhere( [ 'area_parent_id' => $id ] )
				->all();
			
			$isSelectedIn = false;
			if ( $id != null && count( $list ) > 0 ) {
				foreach ( $list as $i => $account ) {
					$out[] = [ 'id' => $account[ 'id' ], 'name' => $account[ 'area_name' ] ];
					if ( $i == 0 ) {
						$first = $account[ 'area_name' ];
					}
					if ( $account[ 'id' ] == $selected ) {
						$isSelectedIn = true;
					}
				}
				if ( !$isSelectedIn ) {
					$selected = $first;
				}
				echo Json::encode( [ 'output' => $out, 'selected' => $selected ] );
				return;
			}
		}
		echo Json::encode( [ 'output' => '', 'selected' => '' ] );
	}
	
	//三级联动之 小区
	public function actionCommunity( $selected = null ) 
	{
		if ( isset( $_POST[ 'depdrop_parents' ] ) ) 
		{
			$id = $_POST[ 'depdrop_parents' ];
			$list = \common\models\Community::find()
				->andwhere( [ 'area_id' => $id ] )
				->all();
			
			$isSelectedIn = false;
			if ( $id != null && count( $list ) > 0 ) {
				foreach ( $list as $i => $account ) {
					$out[] = [ 'id' => $account[ 'community_id' ], 'name' => $account[ 'community_name' ] ];
					if ( $i == 0 ) {
						$first = $account[ 'community_name' ];
					}
					if ( $account[ 'community_id' ] == $selected ) {
						$isSelectedIn = true;
					}
				}
				if ( !$isSelectedIn ) {
					$selected = $first;
				}
				echo Json::encode( [ 'output' => $out, 'selected' => $selected ] );
				return;
			}
		}
		echo Json::encode( [ 'output' => '', 'selected' => '' ] );
	}

    /**
     * Displays a single Area model.
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
     * Creates a new Area model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Area();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Area model.
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
     * Deletes an existing Area model.
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
     * Finds the Area model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Area the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Area::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
