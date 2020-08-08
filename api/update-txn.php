<?php
include '../inc/const.php';
include '../inc/MysqliDb.php';
error_reporting(E_ERROR | E_PARSE);
$txnobj = new MysqliDb (HOST,USER,PSD,DB);
if(isset($_REQUEST))
{
$data  = json_decode(trim(preg_replace('/\s\s+/', ' ', $_REQUEST['d'])));

$pid = $data->PRIMARY_ID;
$sid = $data->SECONDARY_ID;
$mpan = $data->MERCHANT_PAN;
$txnid = $data->TXN_ID;
$txndt = $data->TXN_DATE_TIME;
$txnamt = $data->TXN_AMOUNT;
$acod = $data->AUTH_CODE;
$rrn = $data->RRN;
$tamt = $data->TIP_AMOUNT;
$cpan = $data->CONSUMER_PAN;
$stscode = $data->STATUS_CODE;
$stsdes = $data->STATUS_DESC;
$insertDB = Array("rpt_pid"=>$pid,"rpt_sid"=>$sid,"rpt_mpan"=>$mpan,"rpt_txnid"=>$txnid,"rpt_dt"=>$txndt,"rpt_amt"=>$txnamt,"rpt_acod"=>$acod,"rpt_rrn"=>$rrn,"rpt_tamt"=>$tamt,"rpt_cpan"=>$cpan,"rpt_stscod"=>$stscode,"rpt_stsdes"=>$stsdes);
if($pid&&$txnid&&$txnamt&&$stscode)
{$txnobj->insert('rp_transactions',$insertDB);
if(!$txnobj->getLastError())
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