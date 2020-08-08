<?php
include '../inc/config.php';
include '../inc/MysqliDb.php';


header('Content-Type: application/json');
error_reporting(E_ERROR | E_PARSE);

if(isset($_REQUEST['loginp']) && isset($_REQUEST['token']))
{
$userobj = new MysqliDb(HOST,USER,PSD,DB);
$userobj->where("rpk_uid",$_REQUEST['loginp']);
$userobj->where("rpk_token",$_REQUEST['token']);  
$userobj->where("rpk_sts",0); 
$result = $userobj->getValue ("rp_token", "count(*)");
$response["qry"]=$userobj->getLastQuery();
if($result)
{
$response["msg"]="Handshaking Done.";
$response["sts"]="01";
}
else
{
$response["msg"]="Device is already in use!";
$response["sts"]="00";
}
}
else
{   
$response["msg"]="Invalid Request!";
$response["sts"]="03";
}
echo json_encode($response);

?>
