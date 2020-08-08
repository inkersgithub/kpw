<?php
include '../inc/config.php';
include '../inc/MysqliDb.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
$txnobj = new MysqliDb (HOST,USER,PSD,DB);

if(isset($_POST['pid']) && isset($_POST['mpan']) && isset($_POST['amt']) && isset($_POST['stscod']) && isset($_POST['stsdes']))
{

$pid =$_POST['pid'];
$mpan = $_POST['mpan'];
$txnamt = $_POST['amt'];
$stscode = $_POST['stscod'];
$stsdes = $_POST['stsdes'];
$insertDB = Array("rpt_pid"=>$pid,"rpt_mpan"=>$mpan,"rpt_amt"=>$txnamt,"rpt_stscod"=>$stscode,"rpt_stsdes"=>$stsdes);
$result = $txnobj->insert("rp_transactions",$insertDB);
$response["qry"]=$txnobj->getLastQuery();
if($result)
    {
        $response= Array(
            "msg"=>"Transaction updated successfully",
            "sts"=>"01"
        );
    }
    else{

        $response= Array(
            "msg"=>"Error in updating!",
            "sts"=>"00"
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