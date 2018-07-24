<?php

namespace backend\controllers;

use app\models\Sms;
use Yii;
use common\models\SmsClient;
use app\models\SmsClientSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SmsClientController implements the CRUD actions for SmsClient model.
 */
class SmsClientController extends Controller
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
     * Lists all SmsClient models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SmsClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SmsClient model.
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
     * Creates a new SmsClient model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SmsClient();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SmsClient model.
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
     * Deletes an existing SmsClient model.
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

    function actionSend()
    {
        $sms = Sms::Sms();
        return $this->render('send', ['sms' => $sms]);
    }

    //手动发送月缴费短信
    function actionSend01($sms, $name)
    {
        $model = new SmsClient();
        $signName = $name; //发送短信模板名称
        $phone = '15296500211'; //接收手机号码$m['phone'];//
        $SMS = $sms; //短信模板编号
        $guest = '裕达集团'; //客户

        return $this->render('form', ['model' => $model, 'sms' => $sms, 'name' => $name, 'guest' => $guest]);
        print_r($sms);exit;
        $SmsParam = "{name:'$address',now:'$now',old:'$old',guest:'$guest'}"; //组合短信信息
        $result = Sms::Send($signName, $phone, $SMS, $SmsParam); //调用发送短信类
    }

    //短信预览
    function actionMessage()
    {
        $get = $_GET['SmsClient'];

        $realestate = $get['room']; //房号ID

        //房屋信息
        $massege = (new \yii\db\Query())
            ->select('community_basic.community_name as community, community_building.building_name as building, community_realestate.room_number as number, community_realestate.room_name as name')
            ->from('community_realestate')
            ->join('inner join','community_basic', 'community_basic.community_id = community_realestate.community_id')
            ->join('inner join', 'community_building', 'community_building.building_id = community_realestate.building_id')
            ->where(['community_realestate.realestate_id' => "$realestate"])
            ->one();

        $amount = (new \yii\db\Query()) //查询总欠费
            ->select('sum(invoice_amount) as amount')
            ->from('user_invoice')
            ->andwhere(['realestate_id' => "$realestate", 'invoice_status' => '0'])
            ->one();
        $old = $amount['amount'];

        $now = (new \yii\db\Query()) //查询当月费用
        ->select('sum(invoice_amount) as amount')
            ->from('user_invoice')
            ->andwhere(['realestate_id' => "$realestate", 'invoice_status' => '0', 'year' => date('Y'), 'month' => date('m')])
            ->one();
        $now = $now['amount'];

        if(empty($now)){
            $now = '0';
        }

        $address = $massege['community'].' '.$massege['building'].' '.$massege['number'].'单元 '.$massege['name'];
        $result = ['name' => $address, 'now' => $now , 'old' => $old];
        $result = Json::encode($result);

        if($amount == 0)
        {
            return false;
        }else{
            return $result;
        }
    }

    /**
     * Finds the SmsClient model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SmsClient the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SmsClient::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
