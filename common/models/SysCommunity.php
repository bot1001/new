<?php

namespace common\models;

use Yii;

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
            [['sys_user_id', 'community_id', 'own_add', 'own_delete', 'own_update', 'own_select'], 'required'],
            [['sys_user_id', 'own_add', 'own_delete', 'own_update', 'own_select'], 'integer'],
            [['community_id'], 'string', 'max' => 128],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sys_user_id' => 'Sys User ID',
            'community_id' => 'Community ID',
            'own_add' => 'Own Add',
            'own_delete' => 'Own Delete',
            'own_update' => 'Own Update',
            'own_select' => 'Own Select',
        ];
    }
}
