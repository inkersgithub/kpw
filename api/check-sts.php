<?php
include '../inc/config.php';
include '../inc/MysqliDb.php';
error_reporting(E_ERROR | E_PARSE);
$txnobj = new MysqliDb (HOST,USER,PSD,DB);
if(isset($_REQUEST['pid']) && isset($_REQUEST['mpan']) && isset($_REQUEST['amt']) )
{
$pid =$_REQUEST['pid'];
$mpan = $_REQUEST['mpan'];
$txnamt = $_REQUEST['amt'];
$stscode = $_REQUEST['stscod'];
$stsdes = $_REQUEST['stsdes'];
if($pid&&$mpan&&$txnamt)
{
$txnobj->where("rpt_pid",$pid); 
$txnobj->where("rpt_mpan",$mpan); 
$txnobj->where("rpt_amt",$txnamt); 
$txnobj->where("rpt_sts",0); 
$result = $txnobj->getValue("rp_transactions","rpt_stscod");
if($result=="00")
    {
        $response= Array(
            "msg"=>"Success",
            "sts"=>$result
        );
    }
    else{

        $response= Array(
            "msg"=>"Error!",
            "sts"=>$result
        );
    }

} else
{
    $response= Array(
        "msg"=>"Mandatory fields are missing!",
        "sts"=>"02"
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