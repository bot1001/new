<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sys_role".
 *
 * @property integer $id
 * @property string $name
 * @property string $source_key
 * @property integer $status
 * @property string $comment
 * @property string $create_time
 * @property string $update_time
 */
class SysRole extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sys_role';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'source_key'], 'required'],
            [['status'], 'integer'],
            [['create_time', 'update_time'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['source_key'], 'string', 'max' => 50],
            [['comment'], 'string', 'max' => 20],
            [['source_key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '序号',
            'name' => '名称',
            'source_key' => '角色类型',
            'status' => '状态',
            'comment' => '说明',
            'create_time' => '创建时间',
            'update_time' => '更新时间',
        ];
    }
}
