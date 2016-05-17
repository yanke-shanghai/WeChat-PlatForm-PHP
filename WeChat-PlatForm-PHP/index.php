<?php

define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest{
	//验证消息
    public function valid(){
		//随机字符串
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

	//检查签名
    private function checkSignature(){
		//微信加密签名
        $signature = $_GET["signature"];
		//时间戳
        $timestamp = $_GET["timestamp"];
		//随机数
        $nonce = $_GET["nonce"];
        $token = TOKEN;
		//将token timestamp nonce进行字典序排序
        $tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		//implode()将数组组成字符串
        $tmpStr = implode( $tmpArr );
		//对字符串进行SHA-1加密
        $tmpStr = sha1( $tmpStr );

		//与微信提供的加密签名做对比，证明请求来自微信
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

	//相应消息
    public function responseMsg(){
		//接收POST数据
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        if (!empty($postStr)){
			//simplexml_load_string() 函数把 XML 字符串载入对象中。
			//LIBXML_NOCDATA表示将CDATA合并为文章节点
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $creatTime = $postObj->CreatTime;
            $msgType = $postObj->MsgType;
            $msgId = $postObj->MsgId;
            $content = trim($postObj->Content);
            $time = time();
			//构造要恢复的xml包
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
            if($content == "123"){
                $msgType = "text";
                $contentStr = "$fromUsername $toUsername $creatTime $msgType $msgId";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }else{
                $msgType = "text";
                $contentStr = "kendy 最 帅！";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
        }else{
            echo "";
            exit;
        }
    }
}
?>
