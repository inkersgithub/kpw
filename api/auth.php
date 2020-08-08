<?php
include '../inc/config.php';
include '../inc/MysqliDb.php';
$fhandle=fopen('php://input',"r");
$datareq = stream_get_contents($fhandle);
header('Content-Type: application/json');
error_reporting(E_ERROR | E_PARSE);
$fp=fopen("fname.txt","w");
fwrite($fp,$datareq);
if(isset($_REQUEST['loginp']) && isset($_REQUEST['pass']) && isset($_REQUEST['token']))
{
$userobj = new MysqliDb(HOST,USER,PSD,DB);
$userobj->where("(rpu_uid=? OR rpu_mobile=? OR rpu_email LIKE ?)",Array($_REQUEST['loginp'],$_REQUEST['loginp'],$_REQUEST['loginp']));
$userobj->where("rpu_password",md5($_REQUEST['pass']));  
$userobj->where("rpu_active",1); 
$userobj->where("rpu_block",0); 
$userobj->where("rpu_sts",0); 
$result = $userobj->getValue("rp_users","rpu_uid");
//$response["qry"]=$userobj->getLastQuery();
if($result)
{
    $data = Array ("rpk_uid" =>$result,
    "rpk_token" => $_REQUEST['token'],
    "rpk_sts" => '0');
$updateColumns = Array ("rpk_token");
$lastInsertId = "rpk_id";
$userobj->onDuplicate($updateColumns, $lastInsertId);
$id = $userobj->insert ('rp_token', $data);    
    
$response["msg"]="Auth Success.";
$response["uid"]=$result;
$response["sts"]="01";
}
else
{
$response["msg"]="Invalid User!";
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
