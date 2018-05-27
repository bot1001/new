<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "advertising".
 *
 * @property int $ad_id （广告ID）
 * @property string $ad_title （标题）
 * @property string $ad_excerpt （摘要）
 * @property string $ad_poster （缩略图）
 * @property string $ad_publish_community （发布小区，多个小区用“,”区分）
 * @property string $ad_type （类型，1-文章；2-外链；）
 * @property string $ad_target_value （目标值，ID/URL等）
 * @property int $ad_location （广告位置ID）
 * @property int $ad_created_time （创建时间）
 * @property int $ad_sort （排序,默认0）
 * @property int $ad_status （状态）
 * @property string $property （备注）
 */
class Advertising extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'advertising';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ad_title', 'ad_excerpt', 'ad_poster', 'ad_publish_community', 'ad_type', 'ad_target_value', 'ad_location', 'ad_created_time', 'ad_status', 'property'], 'required'],
            [['ad_excerpt', 'ad_target_value'], 'string'],
            [['ad_location', 'ad_created_time', 'ad_sort', 'ad_status'], 'integer'],
            [['ad_title'], 'string', 'max' => 64],
            [['ad_poster'], 'string', 'max' => 300],
            [['ad_publish_community'], 'string', 'max' => 225],
            [['ad_type'], 'string', 'max' => 20],
            [['property'], 'string', 'max' => 160],
            [['ad_title'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ad_id' => '编号',
            'ad_title' => '标题',
            'ad_excerpt' => '摘要',
            'ad_poster' => '缩略图',
            'ad_publish_community' => '可见小区',
            'ad_type' => '类型',
            'ad_target_value' => 'Value',
            'ad_location' => '位置',
            'ad_created_time' => '创建时间',
            'ad_sort' => '排序',
            'ad_status' => '状态',
            'property' => '备注',
        ];
    }
}
