<?php
//生成带参数二维码
$access_token = "EhxHO5iTocdoDzt24X5tUOaxhRbDNKe8XOIom-j6ZR95kGnLatBrdZvpKWVZlpyMEvo_D5wDVdOTdchWO-m7SWxWpTygkLkbPwfFyiIziOQ";

//临时
$qrcode = '{"expire_seconds": 1800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 10000}}}';
//永久 其中1000为场景值，1-100000,用户扫描不同的场景二维码可以返回不同的信息
$qrcode = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 1000}}}';
//请求接口，返回二维码ticket
$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";
$result = https_request($url,$qrcode);
$jsoninfo = json_decode($result, true);
$ticket = $jsoninfo["ticket"];
echo "ticket = $ticket\n";

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

//通过ticket换取二维码
//换取二维码接口如下
$url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);
$imageInfo = downloadWeixinFile($url);
//将图片保存为一个文件
$filename = "qrcode.jpg";
$local_file = fopen($filename, 'w');
if (false !== $local_file){
    if (false !== fwrite($local_file, $imageInfo["body"])) {
        fclose($local_file);
    }
}

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
    return array_merge(array('body' => $package), array('header' => $httpinfo)); 
}

