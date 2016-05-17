<?php
//本脚本用户创建自定义菜单

//json格式自定义菜单
$jsonmenu = '{
      "button":[
      {
            "name":"关于我们",
           "sub_button":[
            {
               "type":"click",
               "name":"公司简介",
               "key":"公司简介"
            },
            {
               "type":"click",
               "name":"社会责任",
               "key":"社会责任"
            },
            {
               "type":"click",
               "name":"联系我们",
               "key":"联系我们"
            }]
       },
       {
           "name":"产品服务",
           "sub_button":[
            {
               "type":"click",
               "name":"微信平台",
               "key":"微信平台"
            },
            {
               "type":"click",
               "name":"微博应用",
               "key":"微博应用"
            },
            {
                "type":"click",
                "name":"手机网站",
                "key":"手机网站"
            }]
       },
       {
           "name":"技术支持",
           "sub_button":[
            {
               "type":"click",
               "name":"文档下载",
               "key":"文档下载"
            },
            {
               "type":"click",
               "name":"技术社区",
               "key":"技术社区"
            },
            {
                "type":"click",
                "name":"服务热线",
                "key":"服务热线"
            }]
       }]
 }';
//***************************************************
//测试号获得access_token
$appid = "wx8b10fb761e13c43a";
$appsecret = "310c2b1335eb19e0bc784c1e70100250";
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$output = curl_exec($ch);
curl_close($ch);
$jsoninfo = json_decode($output, true);
$access_token = $jsoninfo["access_token"];
echo "access_token = $access_token\n";

//***************************************************
//创建菜单接口
$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
$result = https_request($url, $jsonmenu);
//var_dump()方法是判断一个变量的类型与长度,并输出变量的数值
var_dump($result);

function https_request($url,$data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}
