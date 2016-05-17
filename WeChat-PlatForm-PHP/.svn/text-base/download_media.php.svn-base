<?php
//本脚本根据media_id从平台下载媒体文件
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

//下载图片
$mediaid = "UPiTmEFfwJw3qwKXUQv9gj95IJVWwimtaBMK3fNj6wccXiXAWYxn_h2ZXij3Cn6N";
$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid";
$fileInfo = downloadWeixinFile($url);
//存储图片位置
$filename = "/home/taomee/kendy/weixin/data/down_image.jpg";
saveWeixinFile($filename, $fileInfo["body"]);

#//下载语音
#$mediaid = "5Idx79V9E3XfBCz_A50gr1a1_klgPpJnb_eq73yz0bn-prhIsNlwI3n6jQgshmWk";
#$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid";
#$fileInfo = downloadWeixinFile($url);
#$filename = "down_voice.mp3";
#saveWeixinFile($filename, $fileInfo["body"]);
#
#//下载缩略图
#$mediaid = "2RhP0caRKHVOmZO5AKelHkK--vqPPwgUaRp5-WE63dvmmPRWiYVKgvNblIp_gv79";
#$url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid";
#$fileInfo = downloadWeixinFile($url);
#$filename = "down_thumb.jpg";
#saveWeixinFile($filename, $fileInfo["body"]);

function downloadWeixinFile($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_NOBODY, 0);    //只取body头
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $package = curl_exec($ch);
    $httpinfo = curl_getinfo($ch);
    curl_close($ch);
    $imageAll = array_merge(array('header' => $httpinfo), array('body' => $package));
    return $imageAll;
}

function saveWeixinFile($filename, $filecontent)
{
    $local_file = fopen($filename, 'w');
    if (false !== $local_file){
        if (false !== fwrite($local_file, $filecontent)) {
            fclose($local_file);
        }
    }
}
