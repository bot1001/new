<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_relationship_realestate".
 *
 * @property integer $id
 * @property string $account_id
 * @property integer $realestate_id
 *
 * @property CommunityRealestate $realestate
 * @property UserAccount $account
 */
class UserRealestate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_relationship_realestate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'realestate_id'], 'required'],
            [['realestate_id'], 'integer'],
            [['account_id'], 'string', 'max' => 64],
            [['account_id', 'realestate_id'], 'unique', 'targetAttribute' => ['account_id', 'realestate_id'], 'message' => 'The combination of Account ID and Realestate ID has already been taken.'],
            [['realestate_id'], 'exist', 'skipOnError' => true, 'targetClass' => Realestate::className(), 'targetAttribute' => ['realestate_id' => 'realestate_id']],
            [['account_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAccount::className(), 'targetAttribute' => ['account_id' => 'account_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => '用户_ID',
            'realestate_id' => '关联房屋_ID',
        ];
    }
	
	public function beforeSave($insert){
		if(parent::beforeSave($insert)){
			if($insert){
				$this->property = 1;
			}else{
				$this->property = 1 ;
			}
			return true;
		}else{
			return false;
		}
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRealestate()
    {
        return $this->hasOne(CommunityRealestate::className(), ['realestate_id' => 'realestate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(UserAccount::className(), ['account_id' => 'account_id']);
    }
}
