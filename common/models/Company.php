<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "company".
 *
 * @property int $id
 * @property string $name
 * @property int $creator
 * @property int $create_time
 * @property string $property
 *
 * @property SysUser $creator0
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'company';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['creator', 'create_time', 'parent'], 'integer'],
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
            'name' => '名字',
            'parent' => '总公司',
            'creator' => '创建人',
            'create_time' => '创建时间',
            'property' => '备注',
        ];
    }
	
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert))
		{
			if($insert)
			{
				//插入新纪录时自动添加以下字段
				$this->creator = $_SESSION['user']['0']['id'];
				$this->create_time = date(time());
			}
			return true;
		}
		else{
			return false;
		}
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCr()
    {
        return $this->hasOne(User::className(), ['id' => 'creator']);
    }
	
	static function getCompany()
	{
		$company = self::find()
			->select('name, id')
			->orderBy('id')
			->indexBy('id')
			->column();
			
		return $company;
	}
}
