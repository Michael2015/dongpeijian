<?php
include "./Util/Signature.php";
define('COMMUNICATE_ID', 'b04de914268d6027');
define('COMMUNICATE_KEY', '4a134e3907f5a3f36fe914de339be304');
use Golo\Signature;
//加密盐值
$serect_id = 'sAH0Uy0uw31WTpCe';
$user_id   =  isset($_GET['user_id']) ? $_GET['user_id'] : '';
//签名
$sign_encrypt = md5('user_id='.$user_id.$serect_id);
//版本号
$ver   =  isset($_GET['ver']) ? $_GET['ver'] : '';
//APP_id
$app_id = isset($_GET['app_id']) ? $_GET['app_id'] : '';
//token
$token = isset($_GET['token']) ? $_GET['token'] : '';

//跳转链接
$jump_url = '';
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['user_id']) && $_GET['user_id'])
{
//获取token
	$signature_obj = new Signature(COMMUNICATE_KEY, '', ['sign' => 1]);
	$request_param = [
		'action' => 'token.get_token',
		'communicate_id' => COMMUNICATE_ID,
		'version' => $ver,
		'app_id' => $app_id,
		'user_id'=> $user_id
	];
	$request_param['sign'] = $signature_obj->get_signature($request_param);
	$json_data = get_curl('https://cnglinner.dbscar.com?',$request_param);
	$token_data = json_decode($json_data,true);
    //验证token,验证成功并跳转
	if($token_data && $token_data['data'] === $token)
	{
        $jump_url = "https://golo.beimai.net/#/?user_id=".$user_id."&sign=".$sign_encrypt;
	}
}
//请求token接口，获取token
function get_curl($url,$data)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url.http_build_query($data));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	$info = curl_exec($ch);
	$code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
	curl_close($ch);
	return $info;
}
?>
<!DOCTYPE>
<html>
<head>
	<title>汽修大师</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="UTF-8">
	<script src="https://analytics.goloiov.cn/statistic.min.js?v=1.0.2"></script>
</head>
<style>
h1{font-size: 1.5em;margin-top: 2em;width: 90%;margin-left: 5%;}
p{width: 90%;margin-left: 5% ;}
.c_center h2
{
	display: flex;
	flex-direction:column;
	width:100%;
	height:100%;
	align-items:center; 
	background:#0099cc;
	justify-content:center;
}
span
{
	position: absolute;
	bottom: 0;
}
</style>
<body>
<!--导航开始-->
<?php if($jump_url){ ?>
	<script type="text/javascript">
		window.onload=function (){
			window.location.href = "<?php echo $jump_url; ?>";
		}
	</script>
<?php }else{ ?>
<h1>温馨提示:</h1><p>懂配件需要您先登陆才能使用，请返回并登陆。</p>
<?php } ?>
</body>
</html>
