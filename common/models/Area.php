<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "area".
 *
 * @property int $id 区域主键
 * @property string $area_name 区域名称
 * @property string $area_code 区域代码
 * @property string $area_short 区域简称
 * @property string $area_is_hot 是否热门(0:否、1:是)
 * @property int $area_sequence 区域序列
 * @property int $area_parent_id 上级主键
 * @property string $init_date 初始时间
 * @property string $init_addr 初始地址
 */
class Area extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['area_sequence', 'area_parent_id'], 'integer'],
            [['init_date'], 'safe'],
            [['area_name', 'init_addr'], 'string', 'max' => 16],
            [['area_code'], 'string', 'max' => 128],
            [['area_short'], 'string', 'max' => 32],
            [['area_is_hot'], 'string', 'max' => 1],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'area_name' => 'Area Name',
            'area_code' => 'Area Code',
            'area_short' => 'Area Short',
            'area_is_hot' => 'Area Is Hot',
            'area_sequence' => 'Area Sequence',
            'area_parent_id' => 'Area Parent ID',
            'init_date' => 'Init Date',
            'init_addr' => 'Init Addr',
        ];
    }
	
	//抽取省市数组
	public static function getProvince()
	{
		$province = self::find()
			->select('area_name, id')
			->where(['area_parent_id' => '0'])
			->indexBy('id')
			->orderBy('id ASC')
			->column();
		return $province;
	}

	//抽取市级数组
    static function getCity($parent)
    {
        $city = self::find() ->select('area_name, id')
               ->andFilterWhere(['area_parent_id' => "$parent"])
               ->indexBy('id')
               ->orderBy('id DESC')
               ->limit(80)
               ->column();

        return $city;
    }

    //地区查询
    static function getOne($id)
    {
        $area = self::find()
            ->select('area_name')
            ->where(['id' => $id])
            ->asArray()
            ->column();
            return $area['0'];
    }
}
