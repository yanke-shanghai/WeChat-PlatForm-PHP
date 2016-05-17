<?php

//
// 接收用户消息
// 根据用户的openid，调用客服接口回复消息
//

define("TOKEN", "weixin");

$wechatObj = new wechatCallbackapiTest();
if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
}else{
    $wechatObj->valid();
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$openid = $postObj->FromUserName;
			$access_token = "EhxHO5iTocdoDzt24X5tUOaxhRbDNKe8XOIom-j6ZR95kGnLatBrdZvpKWVZlpyMEvo_D5wDVdOTdchWO-m7SWxWpTygkLkbPwfFyiIziOQ";
			//客服发送信息接口
			$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$access_token;
			//然后向该OpenID发送客服消息，这里可以同时发送文本消息和音乐消息，相关代码如下：
			//发送最炫民族风的介绍
			$data = '{
				"touser":"'.$openid.'",
				"msgtype":"text",
				"text":
				{
					 "content":"《最炫民族风》是凤凰传奇演唱的歌曲，是其第三张专辑《最炫民族风》的主打歌，于2009年5月27日全亚洲同步发行>，2012年3月起在世界范围内走红。其彩铃下载量超过5000万。"
				}
			}';
			$this->https_request($url,$data);
			//发送图片，音乐等，需要获得mediaid，这里暂时没有实现
			$data = '{
				"touser": "'.$openid.'",
				"msgtype": "music",
				"music": {
					"title": "最炫民族风",
					"description": "凤凰传奇",
					"musicurl": "http://122.228.226.40/music/zxmzf.mp3",
					"hqmusicurl": "http://122.228.226.40/music/zxmzf.mp3",
					"thumb_media_id": "b8as-GpA_EFqVoPY7vPT3fpVZBWJb27K77De2dc_0FZml-UExlTMP7IVMz89uh3W"
				}
			}';
			$this->https_request($url,$data);

        }else {
            exit;
        }
    }

	public function https_request($url,$data)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		if (curl_errno($curl)) {
		   return 'Errno'.curl_error($curl);
		}
		curl_close($curl);
		return $result;
	}
}
?>
