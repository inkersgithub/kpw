
<?php
include("../inc/config.php");
include("../inc/MysqliDb.php");
include("../inc/functions.php");

ini_set('display_errors',0);
$action=$_REQUEST['action'];
switch($action):
case 'get-subscriptions':
$uid=$_REQUEST["uid"];
$usrobj=new MysqliDb(HOST,USER,PWD,DB);
$usrobj->where('au.u_id',$uid);
$usrobj->join("user_course uc",'uc.u_id=au.u_id',"INNER");
$usrobj->join("ace_course cs",'cs.course_id=uc.course_id ',"INNER");
$userr=$usrobj->get("ace_user au",null,"cs.course_id,cs.course_name,cs.course_sname,cs.course_sdate,cs.course_edate,cs.course_status,cs.course_fee");
if($usrobj->count>0){
foreach ($userr as $key => $rslt) {
	$crsarr[]=Array("csid"=>$rslt["course_id"],"name"=>$rslt["course_name"],"sname"=>$rslt["course_sname"],"sdate"=>date("d-m-Y",strtotime($rslt["course_sdate"])),"edate"=>date("d-m-Y",strtotime($rslt["course_edate"])),"fee"=>$rslt["course_fee"]);

	}	

$out['courses']=$crsarr;	
$out["msg"]="done";
$out["sts"]="01";	
}
else{
$out["msg"]="No subscriptions";
$out["sts"]="00";		
}
echo json_encode($out);
break;	
case 'getcourse':
$usrobj=new MysqliDb(HOST,USER,PWD,DB);
$usrobj->where("course_status",1);
$crsarr=$usrobj->get("ace_course au",null,"course_id,course_name,course_sname,course_sdate,course_edate,course_status,course_fee");
if($usrobj->count>0){
foreach ($crsarr as $key => $rslt) {
	$crs[]=Array("csid"=>$rslt["course_id"],"name"=>$rslt["course_name"],"sname"=>$rslt["course_sname"]);

	}	

$out['courses']=$crs;	
$out["msg"]="done";
$out["sts"]="01";	
}
else{
$out["msg"]="No Courses";
$out["sts"]="00";		
}
echo json_encode($out);
	break;
case 'get-course-detail':
$csid=$_REQUEST["csid"];
$usrobj=new MysqliDb(HOST,USER,PWD,DB);
$usrobj->where("course_status",1);
$usrobj->where("course_id",$csid);
$crsarr=$usrobj->get("ace_course au",null,"course_id,course_name,course_sname,course_sdate,course_edate,course_status,course_fee");
if($usrobj->count>0){
foreach ($crsarr as $key => $rslt) {
	$crsdetl[]=Array("name"=>$rslt["course_name"],"sname"=>$rslt["course_sname"],"sdate"=>date("d-m-Y",strtotime($rslt["course_sdate"])),"edate"=>date("d-m-Y",strtotime($rslt["course_edate"])),"fee"=>$rslt["course_fee"]);

	}	

$usrobj->where("sb.course_id",$csid);
$usrobj->join("ace_subject asb","asb.sub_id=sb.sub_id");
$sbsarr=$usrobj->get("course_subject sb",null,"asb.sub_id,asb.sub_name,asb.sub_sname");
foreach ($sbsarr as $key => $rslt) {
	$sbjdetl[]=Array("name"=>$rslt["sub_name"],"sname"=>$rslt["sub_sname"],"sbid"=>$rslt["sub_id"]);

	}	

$out['courses']=$crsdetl;	
$out['subjects']=$sbjdetl;	
$out["msg"]="done";
$out["sts"]="01";	
}
else{
$out["msg"]="No Courses";
$out["sts"]="00";		
}
echo json_encode($out);
	break;

case 'payment-start':
$crsid=$_REQUEST["csid"];
$uid=$_REQUEST["uid"];
$usrobj=new MysqliDb(HOST,USER,PWD,DB);
$usrobj->where('uc.u_id',$uid);
$usrobj->where('cs.course_id',$uid);
$usrobj->join("user_course uc",'uc.course_id=cs.course_id',"INNER");

$userr=$usrobj->getOne("ace_course cs","cs.course_id,cs.course_fee");
$trnarr=Array("u_id"=>$uid,"course_id"=>$crsid,"course_fee"=>$userr["course_fee"]);
$usrobj->insert("ace_transactions",$trnarr);
$tid=$usrobj->getInsertId();
if(!$usrobj->getlastError()){
$out["trid"]=$tid;
$out["fee"]=$userr["course_fee"];
$out["msg"]="done";
$out["sts"]="01";
}
else{
$out["msg"]="No Courses";
$out["sts"]="00";		
}
echo json_encode($out);
		break;	
case 'payment-done':
$txnid=$_POST["txnid"];
$rtxnid=$_POST["rtxnid"];
$txnobj=new MysqliDb(HOST,USER,PWD,DB);
$txnobj->where("at_id",$txnid);
$txn=$txnobj->getOne("ace_transactions","u_id,course_id");
$txnar=Array("at_razorpayid"=>$rtxnid);
$txnobj->where("at_id",$txnid);
$txnobj->update("ace_transactions",$txnar);
 
$usarr=Array("u_id"=>$txn['u_id'],"course_id"=>$txn['course_id']);
$txnobj->insert("user_course",$usarr);
if(!$txnobj->getlastError()){

$out["msg"]="done";
$out["sts"]="01";
}
else{
$out["msg"]="Error";
$out["sts"]="00";		
}
echo json_encode($out);

			break;	
case 'get-topics':
//ini_set("display_errors", 1);
$csid=$_REQUEST["csid"];
$sbid=$_REQUEST["sbid"];
$topobj=new MysqliDb(HOST,USER,PWD,DB);
$topobj->where("sub_id",$sbid);
$topobj->where("course_id",$csid);
$toarr=$topobj->get("ace_topic",null,"top_id,top_title");
//echo $topobj->getLastQuery();
if($topobj->count>0){
foreach ($toarr as $key => $top) {
	$tops[]=Array("topid"=>$top["top_id"],"topic"=>$top["top_title"]);
}
$out['topics']=$tops;	
$out["msg"]="done";
$out["sts"]="01";	
}else{
$out["msg"]="No Topics";
$out["sts"]="00";
}
echo json_encode($out);
break;	
case 'material':
$topid=$_REQUEST["topid"];
$topobj=new MysqliDb(HOST,USER,PWD,DB);
$topobj->where("top_id",$topid);
$matarr=$topobj->get("ace_materials",null,"am_url,am_title,am_type");
if($topobj->count>0){
foreach ($matarr as $key => $mat) {
	if($mat["am_type"]==0){
	$nots[]=Array("title"=>$mat["am_title"],"link"=>$mat["am_url"]);
	}
	if($mat["am_type"]==1){
	$vidar[]=Array("title"=>$mat["am_title"],"link"=>$mat["am_url"]);
	}
}
$out['videos']=$vidar;	
$out['notes']=$nots;	
$out["msg"]="done";
$out["sts"]="01";	
}else{
$out["msg"]="No Topics";
$out["sts"]="00";
}
echo json_encode($out);
break;

		endswitch;	