<?php
date_default_timezone_set("Asia/Shanghai"); 
include "./Util/mongo.php";
$db = new mongo();
$host =  'localhost';


if($_SERVER['REQUEST_METHOD'] == 'GET')
{
//开始日期
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
//结束日期
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';

//------------last day all people
$last_day = strtotime('-1 day');
$where = ['__t'=>['$lt'=>date('Y-m-d 23:59:59',$last_day),'$gt'=>date('Y-m-d 00:00:00',$last_day)],'__h'=>$host];
$result = $db->where($where)->select();
if($result)
{
    $uid_arr = array_column($result,'uuuuid');
    //---------每日进入页面的单个用户，以ID为准计一个用户，去重
    $last_day_total = count(array_unique($uid_arr));
    //---------进入方式
    $all_source = array_count_values(array_column($result,'s'));
    //Array([1] => 4[2] => 1[3] => 1)
    //pv total 每日打开页面的次数，打开关闭后，重新进入，算2次
    $open_page_total = count($result);
    //old client 
    $old_client_sum = 0;
    foreach(array_unique($uid_arr) as $uid)
    {
        $where2 = ['__t'=>['$lt'=>date('Y-m-d 00:00:00',$last_day)],'uuuuid'=>$uid,'__h'=>$host];
        $is_exist = $db->where($where2)->find();
        if($is_exist)
        {
            //日浏览老用户数
            $old_client_sum = $old_client_sum +1;
        }
    } 
    //old client  total
    //$old_sum
    //new client 日浏览新用户数
    $new_client_sum =  $last_day_total - $old_client_sum;
}

 //every client avg time 
 //--------------------

 //一周内，平均同一用户打开页面的次数
 $a_week = strtotime('-1 week');
 $where3 = ['__t'=>['$gt'=>date('Y-m-d 00:00:00',$a_week)],'__h'=>$host];
 $result3 = $db->where($where3)->select(); 
if($result3)
{
    $temp_arr = array_count_values(array_column($result3,'uuuuid'));
    $a_week_avg_pv =  round(array_sum($temp_arr)/count($temp_arr),2);
}
//同一用户在相邻两次打开页面的间隔时间，间隔为0-24h，1天，2天…如此类推
$where4 = ['__h'=>$host];
$db->option = ['sort' => [ '__t' => -1],'projection'=>['__t'=>1,'uuuuid'=>1]];
$result4 = $db->where($where4)->select();
if($result4)
{
    $all_client_log = [];
    $all_client_time_diff = [];
    foreach($result4 as $key=>$value)
    {
        if(!isset($value['uuuuid']) || (isset($all_client_log[$value['uuuuid']]) && count($all_client_log[$value['uuuuid']]) == 2))
        {
           continue;
        }
        if(isset($all_client_log[$value['uuuuid']]))
        {
              $time_stamp1 = strtotime(current($all_client_log[$value['uuuuid']]));
              $time_stamp2 = strtotime($value['__t']);
              $all_client_time_diff[$value['uuuuid']] = $time_stamp1 - $time_stamp2;
        }
        $all_client_log[$value['uuuuid']][] = $value['__t'];
    }
    //$new_all_client_log = array_filter($all_client_log,function($v){return count($v) == 2 ? 1 : 0;});
    //同一用户在相邻两次打开页面的间隔时间，间隔为0-24h，1天，2天…如此类推(平均值，多少小时)
   print_r(round(array_sum($all_client_time_diff) / count($all_client_time_diff) / 3600,2)); 
}

}
?>