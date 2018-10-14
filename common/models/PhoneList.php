<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "phone_list".
 *
 * @property int $phone_id
 * @property string $phone_name （电话名称）
 * @property string $phone_number （号码）
 * @property int $parent_id （父级ID，默认为0）
 * @property int $have_lower （是否有下级，0-没有；1-有）
 * @property int $phone_sort （排序，默认0）
 */
class PhoneList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'phone_list';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone_name'], 'required'],
            [['parent_id', 'have_lower', 'phone_sort'], 'integer'],
            [['phone_name'], 'string', 'max' => 64],
            [['phone_number'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'phone_id' => 'Phone ID',
            'phone_name' => '名称',
            'phone_number' => '号码',
            'parent_id' => '类别',
            'have_lower' => '下级',
            'phone_sort' => '排序',
        ];
    }

    function getPhone()
    {
        return $this->hasOne(self::className(),['phone_id' => 'parent_id']);
    }
}
