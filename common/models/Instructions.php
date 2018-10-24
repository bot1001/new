<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "instructions".
 *
 * @property int $id 文章ID
 * @property string $title 标题
 * @property int $author 作者
 * @property string $content 内容
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $type 类型，1=>后台, 2=>微信, 3 =>APP
 * @property string $version 版本号
 * @property string $property 备注
 *
 * @property SysUser $author0
 */
class Instructions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'instructions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'title', 'content', 'type', 'version'], 'required'],
            [['id', 'author', 'create_time', 'update_time', 'type'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['content'], 'string', 'max' => 2000],
            [['version'], 'string', 'max' => 32],
            [['property'], 'string', 'max' => 64],
            [['id'], 'unique'],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'author' => '作者',
            'content' => '内容',
            'create_time' => '创建时间',
            'update_time' => '修改时间',
            'type' => '类型',
            'version' => '版本号',
            'property' => '备注',
        ];
    }

//    function beforeSave($insert)
//    {
//        if(parent::beforeSave($insert))
//        {
//            if($insert){
//                $this->create_time = time();
//                $this->update_time = time();
//                $this->author = Yii::$app->user->id;
//            }else{
//                $this->update_time = time();
//            }
//            return true;
//        }else{
//            return false;
//        }
//    }

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($this->isNewRecord)
            {
                $this->create_time = time();
                $this->update_time = time();
                $this->author = Yii::$app->user->id;
            }else{
                $this->update_time = time();
            }
            return true;
        }
        else
            return false;
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAu()
    {
        return $this->hasOne(User::className(), ['id' => 'author']);
    }
}
