<?php
include '../inc/config.php';
include '../inc/functions.php';
include '../inc/MysqliDb.php';
$userIP = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_REQUEST['email']) && isset($_REQUEST['password']))
   {
$db = new MysqliDb (HOST,USER,PSD,DB);
$db->where("email",$_REQUEST["email"]);
$db->Where("password",md5($_REQUEST["password"]));
$user = $db->getOne ("admin");

if($user['id']>0)
{
$stoken = session_token();
$data = Array (
"key" => $stoken,
"name" => $user['id'],
"access" => $user['control'],
"ip" => $userIP,
"browser" => $userAgent,
"lastlogin" => date("d/m/Y h:i:s A"),
"is_active" => 1,
"sts" => 0
);
$id = $db->insert ('session', $data);
if($id)
{
$response= Array(
    "token"=>$stoken,
    "access"=>$user['control'],
    "name"=>$user['name'],
    "ip"=>$userIP,
    "msg"=>"Login Success",
    "sts"=>"01"); 
}else
{
$response= Array(
    "msg"=>"Error in generating token! ".$db->getLastError(),
    "sts"=>"03");    
}
}else
{
    $response= Array(
    "msg"=>"Invalid Login !",
    "sts"=>"06"
);
}
}
else
{
    $response= Array(
        "msg"=>"Invalid Request!",
        "sts"=>"03"
    );
}

echo json_encode($response);

?>
