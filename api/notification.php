<?php
include '../inc/config.php';
include '../inc/MysqliDb.php';
header('Content-Type: application/json');
error_reporting(E_ERROR | E_PARSE);
$fhandle=fopen('php://input',"r");
$datareq = stream_get_contents($fhandle);
$request=json_decode($datareq,true);
if(sizeof($request)>0)
{
$STATUS_CODE=03;
$PRIMARY_ID= $request['PRIMARY_ID'];
$SECONDARY_ID = $request['SECONDARY_ID'];
$MERCHANT_PAN= $request['MERCHANT_PAN'];
$TXN_ID= $request['TXN_ID'];
$TXN_DATE_TIME= $request['TXN_DATE_TIME'];
$TXN_DATE= substr($TXN_DATE_TIME,0,8);
$AUTH_CODE= $request['AUTH_CODE'];
$TXN_AMOUNT= $request['TXN_AMOUNT'];
$RRN= $request['RRN'];
$CONSUMER_PAN= $request['CONSUMER_PAN'];
if(isset($request['STATUS_CODE'])){$STATUS_CODE= $request['STATUS_CODE'];}
$STATUS_DESC= $request['STATUS_DESC'];
$NRN=time()*2;
$ntfnobj = new MysqliDb (HOST,USER,PSD,DB);
$datantfn = Array ("rpn_rqst" => $datareq);
$id = $ntfnobj->insert ('rp_notifications', $datantfn);
if($id)
{
$data = Array (
	'rpt_sid' => $SECONDARY_ID,
	'rpt_dt' => $TXN_DATE_TIME,
	'rpt_amt' => $TXN_AMOUNT,
	'rpt_acod' => $AUTH_CODE,
    'rpt_rrn' => $RRN,
    'rpt_cpan' => $CONSUMER_PAN,
	'rpt_stscod' => $STATUS_CODE,
    'rpt_stsdes' => $STATUS_DESC

);
$txnobj = new MysqliDb (HOST,USER,PSD,DB);
$txnobj->where ('rpt_pid', $PRIMARY_ID);
$txnobj->where ('rpt_mpan', $MERCHANT_PAN);
$txnobj->where ('rpt_amt', $TXN_AMOUNT);
if ($txnobj->update ('rp_transactions', $data))
{
    $response= Array(
        "TXN_ID"=>$TXN_ID,
        "NOTIFICATION_REF_NO"=>$NRN."NID".$id,
    "STATUS_CODE"=>"00",
    "STATUS_DESC"=>"SUCCESS"
        );
}
else
{
    $response= Array(
        "TXN_ID"=>$TXN_ID,
        "NOTIFICATION_REF_NO"=>$NRN."NID".$id,
    "STATUS_CODE"=>"01",
    "STATUS_DESC"=>"FAILED"
        );
}
}
else
{
    $response= Array(
        "MSG"=>"ERROR! SOMETHING WENT WRONG",
    "STATUS_CODE"=>"03"
 
        );
}
}else
{
    $response= Array(
        "MSG"=>"INVALID REQUEST",
    "STATUS_CODE"=>"04"
 
        );
}

echo json_encode($response);

?>
