<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "community_realestate".
 *
 * * @property int $realestate_id （房屋ID）
 * @property int $community_id （小区ID）
 * @property int $building_id （楼宇ID）
 * @property string $room_number （单元编号）
 * @property string $room_name （房号/单元名称）
 * @property string $owners_name （业主姓名）
 * @property string $owners_cellphone （业主手机号码）
 * @property double $acreage （房屋面积）
 * @property int $inherit （交房时间）
 * @property int $decoration （装修时间）
 * @property int $commencement （开工时间）
 * @property int $finish （封顶时间）
 * @property int $delivery （交付时间)
 * @property string $orientation （房屋朝向）
 * @property string $property  (备注
 *
 * @property UserInvoice[] $userInvoices
 * @property UserRelationshipRealestate[] $userRelationshipRealestates
 * @property UserAccount[] $accounts
 */
class CommunityRealestate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'community_realestate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
       return [
            [['community_id', 'building_id', 'room_name', 'room_number', 'owners_name', 'owners_cellphone'], 'required'],
            [['community_id', 'building_id'], 'integer'],
			[['acreage'], 'number', 'max' => 1500],
		    [['owners_name'], 'string', 'max' => 32],
		    //[['orientation'], 'string', 'max' => 11],
		   [['finish', 'inherit', 'decoration', 'commencement', 'delivery'], function($attr, $params) {
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
            [['room_number'], 'string', 'max' => 2, 'on' => 'update'],
            [['room_name'], 'string', 'max' => 7, 'on' => 'update'],
            [['room_number'], 'integer', 'max' => 10, 'on' => 'create'],
            [['room_name'], 'string', 'max' => 6, 'on' => 'create'],
            [['owners_cellphone'], 'string', 'max' => 12, 'on' => 'update'],
           [['community_id', 'building_id', 'room_name', 'room_number', 'owners_name'],
			 'unique', 'targetAttribute' => ['community_id', 'building_id', 'room_name', 'room_number'], 
			 'message' => '数据重复，请勿再次提交！'],
        ];
    }
	
	//将时间戳转换成时间然后在activeform输出
	public function afterFind()
    {
        parent::afterFind();
        $this->finish = date('Y-m-d', $this->finish);
        $this->inherit = date('Y-m-d', $this->inherit);
        $this->decoration = date('Y-m-d', $this->decoration);
        $this->commencement = date('Y-m-d', $this->commencement);
        $this->delivery = date('Y-m-d', $this->delivery);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'realestate_id' => '编号',
            'community_id' => '小区',
            'building_id' => '楼宇',
            'room_name' => '房号',
            'room_number' => '单元',
            'owners_name' => '业主',
            'owners_cellphone' => '手机',
			'acreage' => '面积',
			'finish' => '交付时间',
			'inherit' => '交房时间', //多余字段
		    'decoration' => '装修时间',
		    'commencement' => '开工时间',
		    'delivery' => '交房时间',
		    'orientation' => '房屋朝向',
		    'property' => '备注',
        ];
    }
	
	//设置场景
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['update'] = ['community_id', 'building_id' , 
								'room_number', 'room_name', 'owners_name', 
								'owners_cellphone', 'acreage', 'finish', 
								'inherit', 'decoration', 'delivery', 'commencement', 
								'orientation', 'property'];
		
        $scenarios['create'] = ['community_id', 'building_id' , 
								'room_number', 'room_name', 'owners_name', 
								'owners_cellphone', 'acreage', 'finish', 
								'inherit', 'decoration', 'delivery', 'commencement',
								'orientation', 'property'];
        return $scenarios;
    }

    //批量操作
	public function batchHandle($ids = [],$status = 3)
	{
        foreach ($ids as $k=>$v){
            $model = $this->has(['id'=>$v]);
            $model->status = $status;
            if(!$model->save(false))
                return new BadRequestHttpException('操作失败！');
        }
        return true;
    }
     
    //其中has方法如下：
    public function has($where=[], $field='*') 
	{
        $result = $this->_query
            ->select($field)
            ->where($where)
            ->one();
        return empty($result) ? false : $result;
    }

    //由小区获取提取单元
    static function community_number($community_id)
    {
        $number = self::find()
            ->select('room_number')
            ->where(['in', 'community_id', $community_id])
            ->orderBy('room_number')
            ->indexBy('room_number')
            ->column();

        return $number;
    }

    //由楼宇获取提取单元
    static function Number($building_id)
    {
        $number = self::find()
            ->select('room_number')
            ->where(['building_id' => "$building_id"])
            ->orderBy('room_number')
            ->indexBy('room_number')
            ->column();

        return $number;
    }
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getUserInvoices()
    {
        return $this->hasMany(UserInvoice::className(), ['realestate_id' => 'realestate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRelationshipRealestates()
    {
        return $this->hasMany(UserRelationshipRealestate::className(), ['realestate_id' => 'realestate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(UserAccount::className(), ['account_id' => 'account_id'])->viaTable('user_relationship_realestate', ['realestate_id' => 'realestate_id']);
    }
	
	//获取关联小区
	public function getCommunity0()
    {
        return $this->hasOne(CommunityBasic::className(), ['community_id' => 'community_id']);
    }
	
	//获取关联楼宇
	public function getBuilding0()
    {
        return $this->hasOne(CommunityBuilding::className(), ['building_id' => 'building_id']);
    }
	
	public function getH()
    {
        return $this->hasOne(HouseInfo::className(), ['realestate' => 'realestate_id']);
    }
}
