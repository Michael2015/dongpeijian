<?php
date_default_timezone_set("Asia/Shanghai"); 
error_reporting(E_ALL);
include "./Util/mongo.php";
$app_id = isset($_GET['__app_id']) ? $_GET['__app_id'] : '10001';
$db = new mg($app_id);
$_GET['__t'] = date("Y-m-d H:i:s");
unset($_GET['__r']);
if(isset($_GET['__c']) && $_GET['__c'])
{
    //解析url ep : a=xxx&b=xxx
    $_c =  urldecode($_GET['__c']);
    parse_str($_c,$_r);
    foreach($_r as $k=>$v)
    {
        $_GET[$k] = $v;
    }
}
$db->insert($_GET);
?>