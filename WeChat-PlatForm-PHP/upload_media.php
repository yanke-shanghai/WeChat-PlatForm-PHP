<?php
//本脚本用于上传媒体文件,并获得media_id
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

//上传图片
$type = "image";
$filepath = "/home/taomee/kendy/weixin/data/test.jpg";
$filedata = array("media"  => "@".$filepath);
$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";
$result = https_request($url, $filedata); 
var_dump($result); 

//上传语音
$type = "voice";
$filepath = "/home/taomee/kendy/weixin/data/test.mp3";
$filedata = array("media"  => "@".$filepath);
$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";
$result = https_request($url, $filedata); 
var_dump($result); 

//上传视频
$type = "video";
$filepath = "/home/taomee/kendy/weixin/data/test.mp4";
$filedata = array("media"  => "@".$filepath);
$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";
$result = https_request($url, $filedata);
var_dump($result);

//上传缩略图 64K
$type = "thumb";
$filepath = "/home/taomee/kendy/weixin/data/test.jpg";
$filedata = array("media"  => "@".$filepath);
$url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";
$result = https_request($url, $filedata);
var_dump($result);


function https_request($url, $data = null){
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
