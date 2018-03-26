<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sys_user".
 *
 * @property integer $id
 * @property string $real_name
 * @property string $name
 * @property string $password
 * @property integer $status
 * @property string $comment
 * @property string $salt
 * @property integer $create_id
 * @property string $create_time
 * @property string $update_time
 * @property integer $update_id
 * @property string $new_pd
 * @property string $phone
 */
class SysUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'status', 'password'], 'required'],
            [['status', 'create_id', 'update_id', 'company', 'community', 'role'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['real_name', 'name', 'password', 'comment', 'salt'], 'string', 'max' => 100],
            [['new_pd'], 'string', 'max' => 128],
            [['phone'], 'string', 'max' => 12],
            [['name'], 'unique'],
			[['name', 'status'], 'required', 'message' => '确认密码不能为空'],
            ['n', 'compare', 'compareAttribute' => 'name', 'message' => '两次密码输入不一致'],
        ];
    }
	
	public $n;
	
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert))
		{
			if($insert)
			{
				//插入新纪录时自动添加以下字段
				$this->update_id = $_SESSION['user']['id'];
				$this->create_time = date('Y-m-d h:i:s');
				$this->update_time = date('Y-m-d h:i:s');
			}else{
				//修改时自动更新以下记录
				$this->update_time = date('Y-m-d h:i:s');
			}
			return true;
		}
		else{
			return false;
		}
	}
	
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'real_name' => '真实姓名',
            'name' => '新密码',
			'role' => '角色',
			'company' => '公司',
			'community' => '关联小区',
			'n' => '新密码',
            'password' => '旧密码',
            'status' => '状态',
            'comment' => '备注',
            'salt' => '密码盐',
            'create_id' => '创建者',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
            'update_id' => '操作人',
            'new_pd' => '新密码',
            'phone' => '联系方式',
        ];
    }
}
