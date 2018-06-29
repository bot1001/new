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
 * @property int $ad_end_time （到期日期）
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
            [['ad_title', 'ad_excerpt', 'ad_poster', 'ad_publish_community', 'ad_type', 'ad_target_value', 'ad_location', 'ad_created_time', 'ad_end_time', 'ad_status'], 'required'],
            [['ad_excerpt', 'ad_target_value'], 'string'],
            [['ad_location', 'ad_created_time', 'ad_sort', 'ad_status'], 'integer'],
            [['ad_title'], 'string', 'max' => 64],
            [['ad_poster'], 'string', 'max' => 300],
			[['ad_end_time'], function($attr, $params) {
                if ($this->hasErrors()) return false;

                $datetime = $this->{$attr};
                
                $time = strtotime($datetime);
                // 验证时间格式是否正确
                if ($time === false) {
                    $this->addError($attr, '时间格式错误.');
                    return false;
                }
                // 将转换为时间戳后的时间赋值给time属性
                $this->{$attr} = $time;
                return true;
            }],
//            [['ad_publish_community'], 'string', 'max' => 225],
            [['ad_type'], 'string', 'max' => 20],
            [['property'], 'string', 'max' => 50],
        ];
    }
	
	public $label_img;
	
	//将时间戳转换成时间然后在activeform输出
	public function afterFind()
    {
        parent::afterFind();
        $this->ad_end_time = date('Y-m-d', $this->ad_end_time);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ad_id' => '编号',
            'ad_title' => '标题',
            'ad_excerpt' => '正文',
            'ad_poster' => '缩略图',
            'ad_publish_community' => '可见小区',
            'ad_type' => '类型',
            'ad_target_value' => 'Value',
            'ad_location' => '位置',
            'ad_created_time' => '创建时间',
			'ad_end_time' => '截止时间',
            'ad_sort' => '排序',
            'ad_status' => '状态',
            'property' => '备注',
        ];
    }
	
	//保存前自动插入
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert))
		{
			if($insert)
			{
				//插入新纪录时自动添加以下字段
				$this->ad_created_time = date(time());
			}
			return true;
		}
		else{
			return false;
		}
	}
}
