<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016072800109035",
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsmzTjhoCMfORUunhcE1yq2G8JY4pjnSl4hKZITHXBtFA5dMeEwDSLezTlX6QDJl3S3rBKWuXZetNMkJ+eCgIt5X3OAz1vKBM86nUAf3oywQSPi14kXQawambghL98/s0lUvn72HKOIgr53aeJwbdVm3bWD5tYvaYAwMRp5l9YKbLxs3mUXkmD2rpypVdBdav1dcohJt3VnvGhnRbRMbUdl5jXELM4G0Ky5pYTWYd+iT6FuqVZeiYh7MGloJ2j9ijRD/CeSs0XJvmg5ZxSysNK2DNLTRlQj/hXMpUAgav4ngTVHVvzGBzQDLHtIKY8gfjNVz+5PplczlSMKpuVHxC3wIDAQAB",
		//商户私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEAsMjBKFh7GXw5O3gyARjDTXEuYJQUw1Xvs7MnDsmTe7Cuz+RYFx5FFCKdMsxcyqTsu3kc2c+mdyfZx5pIF7z0ApMdVnUvKdb37vp6jlfBqiEvKKRpie7MsPsg1vMRACXpHbF6sryCb5tC68FXvZB16GcYzlSH1U7llxJNot1WTEhE7b8/OMUgu1t9zItqnl4uyHhXf8P41e4xZejaVPxbJlsTfVWgDxM3Y1+iwW8oHOG/khE6j5pq40PrUjzq1yRFr7j1RLuJSI2koWVw6qVTd5eIzHE0ON15RlSMepraJ6Qz26QkKz3m2AMulYwRDZ76tP2LTr6OZh7rwuk9iVcBvQIDAQABAoIBADUsLOz1sBD6TDyW6nNp/1RLtqdV4ujd8Dscp6LK/pE1k170T4qkkCJ+RsoArehFsu6wfv0EeA1e7yIaRZTeQIaFuU1H8eejwO8gZ7xoqNeDI6wVx6i1KtwQrM/6TCK/RF6qAz2/dXwcNwSyIrHhwZUlbVCiHDkMggHuf2t9J3fJxOvEoR3REEi/GvWTdm77HRfXLN7+jLP/qlTfZ58iq8ydSCv50e5xCr4pzPWQYQJDODfmwwOz4VrYB5O2b1OzLWf+hvBb9B6dJYU73VXkv4OytEK5TysUGT8r+TjazT+TSx+phHCbkoQ3k0JKBgJb/a9RzQ5rjP9mkzWbboT/zAECgYEA2rZ3Sg2EO85kHv8+7GKLsaq8cE7bjTVYUbbfan6x36NdfVbYq8ynMcnCFLjoLsAhFC9mjd3tJBlCya6cb4/1XJhiQuGMqo9IqrNKEq/Qd4zRQYBBZ1HGlDQ82+4eVBCzfVr00haSme4IvQsTvYo2HqTUpZ5D0g9JbkNreIKmqmMCgYEAzuxaE3mQFnXoxthVEHzXUaYysvR/9PHhHcEyNayZTS7ssXVsW6H3vys4Af1XTeZBkoOC2DHLjGqBg/rs6NxYdF/pNEWjl1BRY9+LIH4hDG5HNs/AMQBlzLdtZhixo6tMAKVnXwpHhQVGmy3UemrdOQHUKkIHrabJnLdnsKgqTV8CgYAU0+V+PtVF4Ly2GvaCCkxlSe4R/+B2jQrxFSoneRM1SdhgVEHj8mRFoIID+SvbL962jmEEx4qKsoEitaceFKZ3/+bzmYkMwQJZhyNZrjZ6/AT9aNpRnX78pBDbnMx0kvaUzHGeBBpH4FwirIhft2a5+lZpwy2QNnZ2sqLsYfy/IwKBgQC0H6hVlZdpBeDI315FCPeCWtN9VjrgpYGaHigv8vxL5NIjtBzMM1Tvc5bAnKDX7d0cxiArREV1CO2PTunV1qGlRCxD1W8Pc9o1v01jzofEQ2b4fqZFwZvcNbwkiNBxsdZqJZGzeMZDNBF/WcjBe67xRfdDhdEbR7nvEvRPIkQYrwKBgCv4HdLb6/mbn2xdwzKvGoAxbG+VAHzNH1gVbubfo0bYuoG9AWEjWk4KKrHrG0fErYpRkcwdL2oF3GJCq+/F7SfvEd4cwcmD8ucMBBgWe5tq3KpR5Ne3z9TbJr8zmSV7NhCaiM5LNbSk4MQg6ACLM3azWpof7X3DD9DsS9kNP2Bk",		
		
		//异步通知地址
		'notify_url' => "http://home.gxydwy.com/pay/notify",
		
		//同步跳转
		'return_url' => "http://www.yuda.com/index.php/pay/pay",
		//编码格式
		'charset' => "UTF-8",
		//签名方式
		'sign_type'=>"RSA2",
		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",
);