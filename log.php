<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
include "./Util/mongo.php";
$db = new mongo();
$_GET['__t'] = isset($_GET['__t']) ? date("Y-m-d H:i:s",mktime($_GET['__t'])) : '';
unset($_GET['__r']);
print_r(PHP_VERSION);
if(isset($_GET['__c']) && $_GET['__c'])
{
    //parse string  ep : a=xxx&b=xxx
    $_c =  urldecode($_GET['__c']);
    parse_str($_c,$_r);
    foreach($_r as $k=>$v)
    {
        $_GET[$k] = $v;
    }
}
$db->insert($_GET);
?>