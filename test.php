<?php
$postStr = <<<EOT
<xml>
 <ToUserName><![CDATA[隔壁老王]]></ToUserName>
 <FromUserName><![CDATA[fromUser]]></FromUserName>
 <CreateTime>1348831860</CreateTime>
 <MsgType><![CDATA[image]]></MsgType>
 <PicUrl><![CDATA[this is a url]]></PicUrl>
 <MediaId><![CDATA[media_id]]></MediaId>
 <MsgId>1234567890123456</MsgId>
 </xml>
EOT;

var_dump($postStr);

file_put_contents('data.txt', $postStr);

// xml 数据如何转换成为数组
$postObj = simplexml_load_string($postStr, "SimpleXMLElement", LIBXML_NOCDATA);
var_dump($postObj);

$tousername = $postObj->ToUserName;         // 开发者
$fromusername = $postObj->FromUserName;     // 用户
$createtime = $postObj->CreateTime;
$msgtype = $postObj->MsgType;
$picurl = $postObj->PicUrl;
$mediaid = $postObj->MediaId;
$msgid = $postObj->MsgId;

//echo $tousername;

$textTpl = <<<EOT
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>     // 用户
<FromUserName><![CDATA[%s]]></FromUserName>     // 开发者
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[%s]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>
EOT;

$time = time();
$msgtype = 'text';
$content = "欢迎来到微信公众号的开发世界！__GZPHP27";

// Return a formatted string
$retStr = sprintf($textTpl, $fromusername, $tousername, $time, $msgtype, $content);

var_dump($retStr);
