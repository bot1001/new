<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_relationship_address".
 *
 * @property integer $id
 * @property string $order_id
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $area_id
 * @property string $address
 * @property string $zipcode
 * @property string $mobile_phone
 * @property string $name
 *
 * @property OrderBasic $orderBasic
 * @property OrderProducts[] $orders
 * @property UserInvoice[] $orders0
 */
class OrderRelationshipAddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_relationship_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'province_id', 'city_id', 'area_id', 'address', 'zipcode', 'mobile_phone', 'name'], 'required'],
            [['province_id', 'city_id', 'area_id'], 'integer'],
            [['order_id', 'address', 'zipcode'], 'string', 'max' => 64],
            [['mobile_phone'], 'string', 'max' => 11],
            [['name'], 'string', 'max' => 32],
            [['order_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '订单编号',
            'province_id' => 'Province ID',
            'city_id' => 'City ID',
            'area_id' => 'Area ID',
            'address' => '地址',
            'zipcode' => 'Zipcode',
            'mobile_phone' => '手机号码',
            'name' => '名字',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderBasic()
    {
        return $this->hasOne(OrderBasic::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(OrderProducts::className(), ['order_id' => 'order_id'])->viaTable('order_basic', ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders0()
    {
        return $this->hasMany(UserInvoice::className(), ['order_id' => 'order_id'])->viaTable('order_basic', ['order_id' => 'order_id']);
    }
}
