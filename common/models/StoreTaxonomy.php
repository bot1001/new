<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "store_taxonomy".
 *
 * @property int $id ID
 * @property string $name 行业名称
 * @property int $creator 创建人
 * @property int $sort 排序
 * @property int $create_time 创建时间
 * @property string $property 备注
 *
 * @property StoreBasic[] $storeBasics
 */
class StoreTaxonomy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store_taxonomy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'type', 'creator', 'create_time'], 'required'],
            [['creator', 'type', 'sort', 'create_time'], 'integer'],
            [['name', 'property'], 'string', 'max' => 50],
            [['name'], 'unique'],
            [['creator'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['creator' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'type' => '类别',
            'creator' => '创建人',
            'sort' => '排序',
            'create_time' => '创建时间',
            'property' => '备注',
        ];
    }

    //查询时间转换
    function afterFind()
    {
        parent::afterFind(); //继承父查询
        return $this->create_time = date('Y-m-d H:i:s', $this->create_time);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['store_taxonomy' => 'id']);
    }

    public function getCreator0()
    {
        return $this->hasOne(User::className(), ['id' => 'creator']);
    }

    //获取类别数组
    static function T($type)
    {
        $taxonomy = self::find() //查询数据
            ->select('name, id')
            ->where(['type' => "$type"])
            ->asArray()
            ->all();

        return $taxonomy;
    }

    //获取类别
    static function Taxonomy($type)
    {
        $taxonomy = self::find()
            ->select('name, id')
            ->indexBy('id')
            ->orderBy('id')
            ->column();

        return $taxonomy;
    }
}
