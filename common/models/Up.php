<?php

namespace common\models;

use yii\base\Model;
use yii\web\UploadedFile;
use common\models\ProductProperty;
use common\models\Product;

class Up extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false,'uploadRequired' => '必须选择上传文件'],
        ];
    }
	
	public function attributeLabels()
	{
		return [
			'file' => '文件',
		];
	}
    
    public function upload()
    {
        if ($this->validate()) {
            $this->file->saveAs('uplaod/' . $this->file->baseName . '.' . $this->file->extension);
            return true;
        } else {
            return false;
        }
    }
}