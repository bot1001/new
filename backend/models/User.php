<?php

namespace app\models;

class User extends /*\yii\base\Object*/ \yii\db\ActiveRecord implements \yii\web\IdentityInterface
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
            [['name', 'new_pd'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 32],
            [['authKey'], 'string', 'max' => 100],
            [['accessToken'], 'string', 'max' => 100],
        ];
    }

    /**
     * 表字段别名
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'real_name' => '真实姓名',
            'name' => '昵称',
			'role' => '角色',
			'company' => '公司',
			'community' => '关联小区',
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
