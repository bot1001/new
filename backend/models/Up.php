<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

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
		
		/*if ($this->validate())  
        {  
            $rootPath = "uploads/"; //定义上传的根目录  
            $ext = $this->photo->extension;   //获取文件的后缀(*格式*)  
            $randName = time() . rand(1000, 9999) . "." . $ext; //重新编译文件名称  
            $path = abs(crc32($randName) % 500);    //编译第二层文件夹名称  
            $rootPath = $rootPath . $path . "/";    //拼接  
            if (!file_exists($path)){   //判断该目录是否存在  
                mkdir($rootPath,true);  
            }  
            $re = $this->photo->saveAs($rootPath . $randName);        //调用内置封装类**执行上传  
            if($re){  
                return $rootPath . $randName;   //上传成功**返回文件的路径名称  
            }else{  
                return false;     
            }  
        }  
        else  
        {  
            return false;  
        }*/
    }
}