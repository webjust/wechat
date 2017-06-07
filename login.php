<?php 
include './wxModel.php';
include './vendor/autoload.php';
include './db.php';

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$wxObj = new wxModel();
$userinfo = $wxObj->getUserDetail();
dump($userinfo);

/*
openid	用户的唯一标识
nickname	用户昵称
sex	用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
province	用户个人资料填写的省份
city	普通用户个人资料填写的城市
country	国家，如中国为CN
headimgurl	用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。
privilege	用户特权信息，json 数组，如微信沃卡用户为（chinaunicom）
 */
$data = array(
	'openid' => $userinfo['openid'],
	'nickname' => $userinfo['nickname'],
	'sex' => $userinfo['sex'],
	'province' => $userinfo['province'],
	'city' => $userinfo['city'],
	'country' => $userinfo['country'],
	'headimgurl' => $userinfo['headimgurl'],
	'privilege' => $userinfo['privilege']
);
$database->insert('user', $data);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
</head>
<body>
<h1>LOGIN PAGE</h1>	
</body>
</html>