<?php
//define your token
define("TOKEN", "xdl2017");
$wechatObj = new wxModel();

if (isset($_GET['echostr']))
{
    $wechatObj->valid();
}
else
{
    // 接收微信服务器发送过来的xml
    $wechatObj->responseMsg();
}

$wechatObj->valid();

class wxModel
{
    /*
     * 接口配置信息，此信息需要你有自己的服务器资源，填写的URL需要正确响应微信发送的Token验证*/
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    /*
     * 微信发送消息，开发者服务器接收xml格式数据，然后进行业务的逻辑处理*/
    public function responseMsg()
    {
        // < 5.6       $GLOBALS
        // PHP > 7.0   file_get_contents()
        // file_put_contents('data.txt', $postStr);
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];          // POST数据

        // 使用 Medoo 类 把xml数据写入数据库
        include './db.php';
        $data = array(
            'xml' => $postStr,
        );
        $database->insert('xml', $data);

        if (!empty($postStr)){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            // Disable the ability to load external entities
            libxml_disable_entity_loader(true);

            // 接收到微信服务器发送过来的xml数据：分为：时间、消息，按照 msgType 分，转换为对象
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

            $tousername = $postObj->ToUserName;
            $fromusername = $postObj->FromUserName;
            $time = time();
            $msgtype = $postObj->MsgType;
            $content = "欢迎来到微信公众号的开发世界！__GZPHP27";

            /*
            <xml>
            <ToUserName><![CDATA[toUser]]></ToUserName>
            <FromUserName><![CDATA[fromUser]]></FromUserName>
            <CreateTime>12345678</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[你好]]></Content>
            </xml>
            */
            // 发送消息的xml模板：文本消息
            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";

            $time = time();
            $msgtype = 'text';
            $content = "欢迎来到微信公众号的开发世界！__GZPHP27";

            // Return a formatted string
            $retStr = sprintf($textTpl, $fromusername, $tousername, $time, $msgtype, $content);
            echo $retStr;

        }else {
            echo "";
            exit;
        }
    }

    /*
     * 验证服务器地址的有效性*/
    private function checkSignature()
    {
        /*
        1）将token、timestamp、nonce三个参数进行字典序排序
        2）将三个参数字符串拼接成一个字符串进行sha1加密
        3）开发者获得加密后的字符串可与signature对比，标识该请求来源于微信
         */
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $_GET["signature"];

        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;

        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}

?>