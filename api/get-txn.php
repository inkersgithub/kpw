<?php
include '../inc/config.php';
include '../inc/MysqliDb.php';
header('Content-Type: application/json');
error_reporting(E_ERROR | E_PARSE);

$txnobj = new MysqliDb (HOST,USER,PSD,DB);
if(isset($_REQUEST))
{
if(isset($_REQUEST['tid']))
   {
    $txnid=$_REQUEST['tid'];
    $txnobj->orderBy("rpt_id","DESC");
    $txnobj->where ("rpt_id", $txnid);
    $txnobj->where ("rpt_sts", 0);
echo $txnobj->JsonBuilder()->get("rp_transactions",null); }
else if(isset($_REQUEST['s']))
{
    $txnobj->orderBy("rpt_id","DESC");
    $txnobj->where ("rpt_sts", 0);
    $txnobj->where ("rpt_pid", "%".$_REQUEST['s']."%","like");
      $txnobj->orWhere ("rpt_amt", "%".$_REQUEST['s']."%","like");
echo $txnobj->JsonBuilder()->get("rp_transactions",null,"rpt_id,rpt_pid,rpt_txnid,rpt_amt,rpt_stscod,DATE_FORMAT(rpt_tstamp, '%M %d %Y, %h:%i %p') as txn_date");
}
else
{
    $txnobj->orderBy("rpt_id","DESC");
    $txnobj->where ("rpt_sts", 0);
echo $txnobj->JsonBuilder()->get("rp_transactions",null,"rpt_id,rpt_pid,rpt_txnid,rpt_amt,rpt_stscod,DATE_FORMAT(rpt_tstamp, '%M %d %Y, %h:%i %p') as txn_date");
}
}
else
{
    $response= Array(
        "msg"=>"Invalid Request!",
        "sts"=>"03"
    );
}

//echo json_encode($response);

?>
