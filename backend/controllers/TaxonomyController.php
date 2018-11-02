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
        $searchModel = new TaxonomySearch(); //实例化搜索模型

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams); //设置数据提供器
        $dataProvider->query->where['type'] = '0';

        $data = $searchModel->search(Yii::$app->request->queryParams);
        $data->query->where['type'] = '-1';

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'data' => $data,
        ]);
    }

    function actionProduct()
    {
        $searchModel = new TaxonomySearch(); //实例化搜索模型
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where['type'] = '-2';

        return $this->render('products', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
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
    public function actionCreate($type)
    {
        $model = new StoreTaxonomy();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('create', [
            'model' => $model,
            'type' => $type
        ]);
    }

    /**
     * Updates an existing StoreTaxonomy model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $type)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'type' => $type
        ]);
    }

    //三级级联动获取商城分类
    function actionBrand( $selected = null )
    {
        if ( isset( $_POST[ 'depdrop_parents' ] ) )
        {
            $id = $_POST[ 'depdrop_parents' ]; //接收传过来的id
            $list = StoreTaxonomy::brand($id['0']);//查询数据
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

    //三级级联动获取商品分类
    function actionTax( $selected = null )
    {
        if ( isset( $_POST[ 'depdrop_parents' ] ) )
        {
            $id = $_POST[ 'depdrop_parents' ]; //接收传过来的id
            $list = StoreTaxonomy::tax($id['0']);//查询数据
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
