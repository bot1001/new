<?php

namespace common\models;

use Yii;
use yii\helpers\Json;

/**
 * This is the model class for table "sms_client".
 *
 * @property int $id
 * @property int $user_id （客户ID）
 * @property int $type （短信类型：0=>手动;2=>自动）
 * @property int $community （小区：自动发送的时候才用到）
 * @property int $count （短信总量）
 * @property int $surplus （短信余量）
 * @property int $status （状态，0=>停止，1=>正常）
 * @property string $property （备注）
 *
 * @property SysUser $user
 */
class SmsClient extends \yii\db\ActiveRecord
{
    public $phone;
    public $building;
    public $number;
    public $room;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sms_client';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'community', 'count', 'surplus', 'status', 'property', 'phone'], 'required'],
            [['user_id', 'type', 'community', 'count', 'surplus', 'status'], 'integer'],
            [['property'], 'string', 'max' => 50],
            [['phone'], 'string', 'length' => '11'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '商户',
            'type' => '类型',
            'community' => '预发送小区',
            'phone' => '手机号码',
            'count' => '短信总量',
            'surplus' => '短信余量',
            'status' => '状态',
            'property' => '备注',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    static function send($realestate)
    {
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
        if(empty($amount)) //判断是否为空
        {
            $amount['amount'] = 0;
        }

        $now = (new \yii\db\Query()) //查询当月费用
        ->select('sum(invoice_amount) as amount')
            ->from('user_invoice')
            ->andwhere(['realestate_id' => "$realestate", 'invoice_status' => '0', 'year' => date('Y'), 'month' => date('m')])
            ->one();
        $now = $now['amount'];

        if(empty($now)) //判断是否为空
        {
            $now = '0';
        }

        $old = $amount['amount'] - $now;

        if($old == '0' && $now == '0')
        {
            $end = 0;
        }else{
            $end = 1;
        }

        $address = $massege['community'].' '.$massege['building'].' '.$massege['number'].'单元 '.$massege['name'];
        $result = ['name' => $address, 'now' => $now , 'old' => "$old", 'end' => "$end"];
        $result = Json::encode($result);

        return $result;
    }
}
