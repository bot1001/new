<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order_relationship_address".
 *
 * @property int $id
 * @property string $order_id （订单号）
 * @property int $province_id （省份）
 * @property int $city_id （城市）
 * @property int $area_id （区/县）
 * @property string $address （详细地址）
 * @property string $zipcode （邮编）
 * @property string $mobile_phone （手机号码）
 * @property string $name （收件人姓名）
 */
class OrderAddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_relationship_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'mobile_phone', 'name'], 'required'],
            [['province_id', 'city_id', 'area_id'], 'integer'],
            [['order_id', 'address', 'zipcode'], 'string', 'max' => 64],
            [['mobile_phone'], 'string', 'max' => 12],
            [['name'], 'string', 'max' => 32],
            [['order_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'province_id' => 'Province ID',
            'city_id' => 'City ID',
            'area_id' => 'Area ID',
            'address' => 'Address',
            'zipcode' => 'Zipcode',
            'mobile_phone' => 'Mobile Phone',
            'name' => 'Name',
        ];
    }
	
	public function getOrder() 
	{ 
	    return $this->hasOne(Order::className(), ['order_id' => 'order_id']);
	} 
}
