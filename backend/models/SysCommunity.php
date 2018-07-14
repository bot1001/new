<?php

namespace app\models;

use Yii;
use common\models\Company;

/**
 * This is the model class for table "sys_user_community".
 *
 * @property int $id （ID）
 * @property int $sys_user_id （后台用户ID）
 * @property string $community_id （绑定小区ID,多个小区之间用 , 隔开）
 * @property int $own_add （权限增, 1:是,0,否）
 * @property int $own_delete （权限删, 1:是,0,否）
 * @property int $own_update （权限改, 1:是,0,否）
 * @property int $own_select （权限查, 1:是,0,否）
 *
 * @property SysUser $sysUser
 */
class SysCommunity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sys_user_community';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sys_user_id', 'community_id'/*, 'own_add', 'own_delete', 'own_update', 'own_select'*/], 'required'],
            [['sys_user_id', 'own_add', 'own_delete', 'own_update', 'own_select'], 'integer'],
            //[['community_id'], 'string', 'max' => 128],
           [['sys_user_id'], 'unique', 'targetAttribute' => ['sys_user_id']],
            //[['sys_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => SysUser::className(), 'targetAttribute' => ['sys_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sys_user_id' => '用户',
            'community_id' => '关联小区编码',
            'own_add' => '添加',
            'own_delete' => '删除',
            'own_update' => '更新',
            'own_select' => '查找',
        ];
    }
	
	//插入数据前保存数据
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert))
		{
			if($insert)
			{
				$this->own_add = 0;
				$this->own_delete = 0;
				$this->own_update = 0;
				$this->own_select = 0;
			}
			return true;
		}else{
			return false;
		}
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSysUser()
    {
        return $this->hasOne(SysUser::className(), ['id' => 'sys_user_id']);
    }
	
	//建立公司关系
	public function getCom()
    {
        return $this->hasOne(Company::className(), ['id' => 'company'])->viaTable('sys_user', ['id' => 'sys_user_id']);
    }
}
