<?php
//
// 菜单事件推送
// 用户点击菜单，响应对应的消息
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
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if($tmpStr == $signature){
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
            $RX_TYPE = trim($postObj->MsgType);

            switch ($RX_TYPE)
            {
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;
            }
            echo $result;
        }else {
            echo "";
            exit;
        }
    }
    
    private function receiveEvent($object)
    {
        $content = "";
        switch ($object->Event)
        {
            case "subscribe":   //关注事件
				$contentStr[] = array("Title" =>"欢迎关注爱恩工作室",
				"Description" =>"点击图片关注或者微信搜索爱恩工作室",
				"PicUrl" =>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg",
				"Url" =>"weixin://addfriend/pondbaystudio");
            case "unsubscribe": //取消关注事件
                break;
			case "CLICK":  //点击菜单事件
				switch ($object->EventKey)
				{
					case "公司简介":
						$contentStr[] = array("Title" =>"公司简介",
						"Description" =>"爱恩工作室提供移动互联网相关的产品及服务",
						"PicUrl" =>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg",
						"Url" =>"http://m.cnblogs.com/?u=txw1958");
						break;
					default:
						$contentStr[] = array("Title" =>"默认菜单回复",
						"Description" =>"您正在使用的是爱恩工作室的自定义菜单测试接口",
						"PicUrl" =>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg",
						"Url" =>"http://m.cnblogs.com/?u=txw1958");
						break;
				}
				break;
			default:
				break;

        }
        $result = $this->transmitNews($object, $contentStr);
        return $result;
    }

    /*  
     * 回复图文消息
     */
    private function transmitNews($object, $arr_item)
    {   
        if(!is_array($arr_item))
            return;

        $itemTpl = "<item>
			<Title><![CDATA[%s]]></Title>
			<Description><![CDATA[%s]]></Description>
			<PicUrl><![CDATA[%s]]></PicUrl>
			<Url><![CDATA[%s]]></Url>
			</item>";
        $item_str = ""; 
        foreach ($arr_item as $item)
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);

        $newsTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[news]]></MsgType>
			<Content><![CDATA[]]></Content>
			<ArticleCount>%s</ArticleCount>
			<Articles>
			$item_str</Articles>
			</xml>";

        $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($arr_item));
        return $result;
    }   
}
?>
