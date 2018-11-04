<?php
return [
    'adminEmail' => '805093015@qq.com',

    'version' => '2.7.6.0.8', //软件版本号

	'Alipay' => [
	     //应用ID,您的APPID。
		'app_id' => "2018010501612409",//公司账号
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnH7pK+jy91Sq/DMdIIq7Wo5Bk27aD5Fjkgcr5+pe+PDaoOwc5iScWoKLJTyj8cZF1b6xSwLSToXWV3/+lfoZHjgBowoMRSlbL4HUm4PMmyrzSxSyHYut1TvxzHOxWgBG1ORk26NeZ4Kje1SH5C0OHwbEvVIfd3Q738OcW9Eap+xhDqJqK5FtG8kGxhCltPOMmtMeU86sQwURAa4A8C4OMk3XMiwHbZXw1dtBy7e1Afo9QhtHnS6aDcIVOJt83XRhq6CkzqyFNw1VEF84snwkslepyBgrnALjRJWy/wHT70b4+Di3t68cxTFL8KG5UZBzoBkm16gM3L2H3AH/b0pQ0wIDAQAB",
		//商户私钥
		'merchant_private_key' => "MIIEpQIBAAKCAQEA5eb8TJ3dPZ5tDoUTdMamZFLSV6Ba0QYf1eNZczW6B9SGr3JhowEUGMf2C8N0tHb4Sh31tEvwrNkFTcfehNOUlOZvu6AacgJ4YPLAZEw1z+LRJ72KqtcXeFljNOuc0euP3I9jJFtsVP+2G7jNlyS63gDyVCqYy0JkTNFTug/ULPa5bAFGJaPMkWrkSa0JzXIw6VeYBpirocdkcCsJe4T/P/WxA9b4rSwrN9WAyEN1a9Da6nT7LcJsAb5sO2EmwXQrgU2b+NxoMT4rFll2ThRjFJ8MClYK3waDEAqQUFtb0G6g4wkMiuMhDztuSHIeYRB9Bt2kJN6yEgNgh5kqnrjiOQIDAQABAoIBACX3gn0IqQvPCQWX1oG3eK0ivFf6RGU2lLVW6WPMYJ+Aaom44wTvXalGBI0mxzQyXYin7/bJf8KbyHNy6X2YD3aTo6oho5xmagjgrgMiTtS5CX99ntPd16NpZ6fKrUhBla2ABIhxjK9w11s2psUqLK4TC/H4YulWWogRHbmheZG/ENy1aUKaBFTEaQz+Kd1LSIphjcfQGezyYm5ge9lgoVIrTFSiwsxRenpBC4eekjXhVH7ZYHg0yRvCT1ch90iAyawKLoi+c+kyuBI1znTQbzErFxKIpuOH5kGnETzIskindWkSh8Sm5ZLtaoZBUYtshAIv9g2gZWO32X9fVVLVXIECgYEA/7K64xKkNNetojBCh0ViWbWd9G1ElNmZTD6yKzg79jWFCOomvlwPwBOcBu7XSb7ceocwQzh5yCtVoC+aFGvPBT060izvGE7b0N0xxej5HpBTNB0mb7RkttdzCHKaV7omaXgMLIEbsADXpDYDW+unQ2DqpcOZLO4nlv2hqt5OUkkCgYEA5ix10At4WDKjOF5EDY4Q+tsF3rMvMivfG2puboccmQwIqi7zWH8iRAZ0zbthY4KsXexv5ut8OHBDrsCx2EEo3uhrkb1thXGXF85vIUcRwkRs89KbK+Wd2kgf3wI0j5YKMieBmQbOgjWDPNJ0QH1bbNFCWEvIoU75EKK2UVDyEHECgYEAw9UOHzU6wCHjINGXUmTIg25+kCJToTDaoLNv33wNKG8q+X3juG1nvrMGD6VDC376+3iodQM++hh+VtW+Sx+aiSew8iFp6RMUdW3DXhElIsxkfQhKVMkzTXnK51BmdwPhwWso37juDKlIfsLDZdYg35DNhbE9klS/y3trIyNplskCgYEApnelRS/me7MzxMK4aS1ELolVN3lUOu7rzwIsmBdzoWTIp3yJVomsGJKqy1gn2TZavxsO222YS6E18h8/AG4vxvdFRQRBP2+lnPwZ93FU8LFYMwcwXpEUlxfupPUNWoNjIF7eJfR8SkO1hLmYhkOjaZH2b7Fviup4y4VCYpEuYKECgYEA2TuZVpApARSQqMmDrkpb2KfNfy8iNEUppYstYTZVPgtMfcifnVSCAXVK5+5C7qAm29jf3fPm3wnw9W6n6j1+IUzsiUuaUTw2PoZFXikReCi0+hzMO8bN7tOk2FA0ZTr5CRhivLxHk/Ye92Cdpm+PHKfKudIumdv1DwVH+TdOTYs=",		
		
		'notify_url' => "https://home.gxydwy.com/pay/notify",
		
		//同步跳转
		'return_url' => "https://home.gxydwy.com/pay/return",
		//编码格式
		'charset' => "UTF-8",
		//签名方式
		'sign_type'=>"RSA2",
		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
     ],

    'modal' => '..\..\..\common\modal\modal.php', //模态框文件路径

    'order' => [
        'way' => [ '0' => '欠费', 1 => '支付宝', 2 => '微信', 3 => '刷卡', 4 => '银行', '5' => '政府', 6 => '现金', 7 => '建行', 8 => '优惠' ],
        'status' => [ '1' => '未支付', '2' => '已支付', '3' => '已取消', '4' => '送货中', '5' => '已签收' ]
    ],
    'product' => [ //商品状态
        'status' => ['1' => '在售', '2' => '下架', '3' => '待审核', '4' => '审核中', '5' => '审核失败']
    ],
    'taxonomy' => [
        'type' => ['0' => '行业', '-1' => '品牌', '-2' => '商品']
    ],
];
