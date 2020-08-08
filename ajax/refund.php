<?php
include '../inc/config.php';
include '../inc/functions.php';
include '../inc/MysqliDb.php';
$userIP = $_SERVER['REMOTE_ADDR'];
$refuser=$_SESSION['sessionuser'];

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_REQUEST['txntbid']) && isset($_REQUEST['refamt']))
   {$refuser=$_REQUEST['refuser'];
$db = new MysqliDb (HOST,USER,PSD,DB);
$db->where("rpt_id",$_REQUEST["txntbid"]);
$db->where("rpt_stscod","00");
$txn = $db->getOne ("rp_transactions");

if($txn['rpt_id']>0)
{
$db->where("rpr_rpid",$txn['rpt_pid']);
$scssrfd = $db->getOne ("rp_refund", "sum(rpr_refamt) as ttl, count(*) as cnt");
if($_REQUEST['refamt']<=$txn['rpt_amt'] && $_REQUEST['refamt']>=$scssrfd['ttl'])
{
$refid = strtoupper("RFD".random(4).$txn['rpt_dt']);
$data = Array (
"rpr_refid" => $refid,
"rpr_rpid" => $txn['rpt_pid'],
"rpr_txnid" => $txn['rpt_txnid'],
"rpr_txnamt" => $txn['rpt_amt'],
"rpr_refamt" => $_REQUEST['refamt'],
"rpr_userip" => $userIP,
"rpr_username" => $refuser,
"rpr_refsts" => 0,
"rpr_sts" => 0
);
$id = $db->insert ('rp_refund', $data);
if($id)
{
$response= Array(
    "msg"=>"Refund Initiated successfully, Ref ID is ".$refid,
    "sts"=>"01"); 
}else
{
$response= Array(
    "msg"=>"Error in Initiating Refund! ".$db->getLastError(),
    "sts"=>"03");    
}
}
else
{
    $response= Array(
    "msg"=>"Refund Amount is Greater than Txn Amount!",
    "sts"=>"03");
}

}else
{
    $response= Array(
    "msg"=>"Invalid Txn !",
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
