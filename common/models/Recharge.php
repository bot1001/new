<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "recharge".
 *
 * @property int $id
 * @property string $name （充值项目名称）
 * @property string $price （项目金额）
 * @property int $type （充值项目类型，1=>电费）
 * @property int $create_time （创建时间）
 * @property int $creater （创建人）
 * @property string $property （备注）
 */
class Recharge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'recharge';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price', 'create_time', 'creater', 'type'], 'required'],
            [['price'], 'number'],
            [['create_time'], function($arr){
                if($this->hasErrors()) return false;
                $datetime = $this->{$arr};
                $time = strtotime($datetime);
                if($time == false){
                    $this->addError($arr, '时间格式错误.');
                    return false;
                }
                $this->{$arr} = $time;
                return true;
            }],
            [['type'], 'integer'],
            [['name', 'property'], 'string', 'max' => 50],
            [['creater'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creater' => 'id']],
        ];
    }

    //查询后时间转换
    function afterFind()
    {
        parent::afterFind(); // 继承父级
        $this->create_time = date('Y-m-d H:i:s');
    }

    //插入数据前自动保存
    function beforeSave($insert)
    {
        if(parent::beforeSave($insert)) //判断是否为新纪录
        {
            if($insert){ //默认创建人id和创建时间
                $this->creater = $_SESSION['user']['0']['id'];
                $this->create_time = time();
            }
            return true;
        }else{
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'price' => '价格',
            'type' => '类别',
            'create_time' => '创建时间',
            'creater' => '创建人',
            'property' => '备注',
        ];
    }

    //数据表关联
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'creater']);
    }
}
