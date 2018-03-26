<?php

namespace backend\controllers;
use Yii;
use yii\db\Query;
use app\models\EpmsFilesign;
use app\models\EpmsFilesignState;

class ServerController extends \yii\web\Controller
{

    
    public $layout = 'pm';
    
    public function  beforeAction($action)
    {
        if(Yii::$app->user->isGuest){
            $this->redirect(['login/index']);
            return false;
        }
        return true;
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionUpdates(){
        $state = EpmsFilesignState::find()->all();
        $query = (new Query())
        ->select(['epms_filesign.id as id','create','pr','filename','epms_filesign_state.state as state','remark','filetype'])
        ->from('epms_filesign')
        ->where(['show'=>1,'epms_filesign.id'=>Yii::$app->request->post('check')])
        ->join('LEFT JOIN','epms_filesign_state','epms_filesign.state = epms_filesign_state.id');
        $res = $query->all();
        
        Yii::$app->session['updates'] = Yii::$app->request->post('check');
        $fileType = Yii::$app->edb->createcommand("select name,value from epms_menu where `type`='fileType' order by orderby")->queryAll();
        $depMenu = Yii::$app->edb->createcommand("select name,value from epms_menu where `type`='depMenu' order by orderby")->queryAll();
        return $this->render('updates',[
            'list'=>$res,
            'state'=>(object)$state,
            'fileType'=>$fileType,
            'depMenu'=>$depMenu,
        ]);
    }
    
    public function actionUps(){
        $files = Yii::$app->session->get('updates');
        $state = (object)Yii::$app->request->post();
        foreach($files as $file){
            $tmp = EpmsFilesign::findOne(['id'=>$file]);
            $tmp->last = date('Y-m-d h:i:s');
            $tmp->state = "1";
            $tmp->signstate = $state->select;
            $tmp->save();
        }
        return $this->redirect(['zeus/pmtracking','act'=>Yii::$app->request->get('act')]);
    }
    
    public function actionOks(){
        $files = Yii::$app->session->get('updates');
        $state = (object)Yii::$app->request->post();
        foreach($files as $file){
            $tmp = EpmsFilesign::findOne(['id'=>$file]);
            $tmp->end = date('Y-m-d h:i:s');
            $tmp->state = "2";
            $tmp->signstate = $state->select;
            $tmp->save();
            
            
        }
        return $this->redirect(['zeus/pmtracking','act'=>Yii::$app->request->get('act')]);
    }
    
    public function actionBacks(){
        $files = Yii::$app->session->get('updates');
        $state = (object)Yii::$app->request->post();
        foreach($files as $file){
            $tmp = EpmsFilesign::findOne(['id'=>$file]);
            $tmp->end = date('Y-m-d h:i:s');
            $tmp->state = "3";
            $tmp->backinfo = $state->backinfo;
            $tmp->save();
        }
        //$msg = new AliMsg();
        //$msg->sendMsg("534","2330",(object)[
        //    'name'=>$user->Name,
        //],$user->Phone);
        return $this->redirect(['zeus/pmtracking','act'=>Yii::$app->request->get('act')]);
    }
    
    public function actionConvertfiletype(){
        $files = Yii::$app->session->get('updates');
        $state = (object)Yii::$app->request->post();
        foreach($files as $file){
            $tmp = EpmsFilesign::findOne(['id'=>$file]);
            $tmp->filetype=$state->filetype;
            $tmp->save();
        }
        return $this->redirect(['zeus/pmtracking','act'=>Yii::$app->request->get('act')]);
    }
    
    public function actionConvertdeptype(){
        $files = Yii::$app->session->get('updates');
        $state = (object)Yii::$app->request->post();
        foreach($files as $file){
            $tmp = EpmsFilesign::findOne(['id'=>$file]);
            $tmp->department=$state->deptype;
            $tmp->save();
        }
        return $this->redirect(['zeus/pmtracking','act'=>Yii::$app->request->get('act')]);
    }

}
