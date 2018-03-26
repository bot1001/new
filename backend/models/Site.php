<?php

namespace app\models;

use Yii;

class Site extends \yii\db\ActiveRecord
{
    public $from;
	public $to;
	public $new_pd;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from', 'to'], 'date'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'from' => 'from',
            'name' => 'to',
            //'property' => 'Property',
        ];
    }

}
