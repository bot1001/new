<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "community_news".
 *
 * @property int $news_id id
 * @property int $community_id （关联隶属小区ID）
 * @property string $title （标题）
 * @property string $excerpt （摘要）
 * @property string $content （公告正文）
 * @property int $post_time （发布时间）
 * @property int $update_time （更新时间）
 * @property int $view_total （浏览量）
 * @property int $stick_top （置顶,默认0-不置顶，1-置顶）
 * @property int $status （状态，1-未审核，2-已审核（默认），3-删除）
 *
 * @property CommunityBasic $community
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'community_news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['community_id', 'title', 'content', 'stick_top', 'status'], 'required'],
            [['community_id', 'post_time', 'update_time', 'view_total', 'stick_top', 'status'], 'integer'],
            [['excerpt', 'content'], 'string'],
            [['title'], 'string', 'max' => 16],
            [['excerpt'], 'string', 'max' => 24],
            [['content'], 'string', 'max' => 512],
            [['community_id'], 'exist', 'skipOnError' => true, 'targetClass' => Community::className(), 'targetAttribute' => ['community_id' => 'community_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'news_id' => '编号',
            'community_id' => '小区',
            'title' => '标题',
            'excerpt' => '摘要',
            'content' => '正文',
            'post_time' => '发布时间',
            'update_time' => '更新时间',
            'view_total' => '浏览次数',
            'stick_top' => '排序',
            'status' => '状态',
        ];
    }
	
	public function beforeSave($insert)
	{
		if(parent::beforeSave($insert))
		{
			if($insert)
			{
				//插入新纪录时自动添加以下字段
				$this->view_total = 0;
				$this->post_time = time();
				$this->update_time = time();
			}else{
				//修改时自动更新以下记录
				$this->update_time = time();
			}
			return true;
		}
		else{
			return false;
		}
	}
	
	public function getB()
	{
	    $tmpStr = strip_tags($this->content);
		$tmpLen = mb_strlen($tmpStr);
		
		return mb_substr($tmpStr,0,12,'utf-8').(($tmpLen>12)?'...':'');	
	}
	
	public function getE()
	{
	    $tmpStr = strip_tags($this->excerpt);
		$tmpLen = mb_strlen($tmpStr);
		
		return mb_substr($tmpStr,0,12,'utf-8').(($tmpLen>12)?'...':'');	
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getC()
    {
        return $this->hasOne(Community::className(), ['community_id' => 'community_id']);
    }
}
