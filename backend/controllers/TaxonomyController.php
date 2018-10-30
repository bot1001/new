<?php

namespace backend\controllers;

use Yii;
use common\models\StoreTaxonomy;
use common\models\TaxonomySearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaxonomyController implements the CRUD actions for StoreTaxonomy model.
 */
class TaxonomyController extends Controller
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
     * Lists all StoreTaxonomy models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TaxonomySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StoreTaxonomy model.
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
     * Creates a new StoreTaxonomy model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StoreTaxonomy();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StoreTaxonomy model.
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

    //二级联动获取商品子类别
    function actionTax( $selected = null )
    {
        if ( isset( $_POST[ 'depdrop_parents' ] ) )
        {
            $id = $_POST[ 'depdrop_parents' ]; //接收传过来的id
            $list = StoreTaxonomy::brand($id);//查询数据
            $isSelectedIn = false;
            if ( $list != null && $id != null && count( $list ) > 0 ) {
                foreach ( $list as $i => $account ) {
                    $out[] = [ 'id' => $account[ 'id' ], 'name' => $account[ 'name' ] ];
                    if ( $i == 0 ) {
                        $first = $account[ 'id' ];
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

    /**
     * Deletes an existing StoreTaxonomy model.
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
     * Finds the StoreTaxonomy model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StoreTaxonomy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StoreTaxonomy::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
