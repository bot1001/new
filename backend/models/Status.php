<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "status".
 *
 * @property integer $id
 * @property string $name
 * @property integer $invoice_status_id
 * @property integer $order_basic_status
 * @property integer $user_status
 * @property integer $ticket_status_id
 * @property integer $property
 */
class Status extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'status';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_status_id', 'order_basic_status', 'user_status', 'ticket_status_id', 'property'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'invoice_status_id' => 'Invoice Status ID',
            'order_basic_status' => 'Order Basic Status',
            'user_status' => 'User Status',
            'ticket_status_id' => 'Ticket Status ID',
            'property' => 'Property',
        ];
    }
}
