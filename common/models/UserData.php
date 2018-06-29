<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_data".
 *
 * @property integer $id
 * @property string $account_id
 * @property string $real_name
 * @property integer $gender
 * @property string $face_path
 * @property integer $province_id
 * @property integer $city_id
 * @property integer $area_id
 * @property integer $reg_time
 * @property string $nickname
 *
 * @property UserRelationshipRealestate $account
 */
class UserData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reg_time'], 'unique'],
            [['account_id','real_name', 'province_id', 'city_id', 'area_id',], 'required'],
            [['gender', 'province_id', 'city_id', 'area_id', 'reg_time'], 'integer'],
            [['account_id', 'real_name', 'nickname'], 'string', 'max' => 32],
            [['face_path'], 'string', 'max' => 300],
            //[['account_id'], 'unique', 'targetAttribute' => ['account_id']，'message' => '重复提交'],
            [['account_id', 'real_name'], 'unique', 'targetAttribute' => ['account_id'], 'message' => '重复提交'],
            //[['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserRelationshipRealestate::className(), 'targetAttribute' => ['account_id' => 'account_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => '用户串码',
            'real_name' => '真实姓名',
            'gender' => '性别',
            'face_path' => '图像路径',
            'province_id' => '省份',
            'city_id' => '城市',
            'area_id' => '县/区',
            'reg_time' => '注册时间',
            'nickname' => '昵称',
        ];
    }
	
	public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($insert)
            {
                $this->reg_time = date(time());
				$this->property = 1;
                }
                return true;
            }else{
            return false;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(UserRelationshipRealestate::className(), ['account_id' => 'account_id']);
    }
}
