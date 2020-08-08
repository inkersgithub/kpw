
<?php
include("../inc/config.php");
include("../inc/MysqliDb.php");
include("../inc/functions.php");

ini_set('display_errors',0);
$action=$_REQUEST['action'];
switch($action):
case 'get-subscriptions':
$uid=$_REQUEST["uid"];
$now=date("Y-m-d");
$usrobj=new MysqliDb(HOST,USER,PWD,DB);
$usrobj->where('au.u_id',$uid);
$usrobj->where('cs.course_edate',$now,">=");
$usrobj->join("user_course uc",'uc.u_id=au.u_id',"INNER");
$usrobj->join("ace_course cs",'cs.course_id=uc.course_id ',"INNER");
$userr=$usrobj->get("ace_user au",null,"cs.course_id,cs.course_name,cs.course_sname,cs.course_sdate,cs.course_edate,cs.course_status,cs.course_fee");
//echo $usrobj->getLastQuery();
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
$usrobj->where("course_fee",0,">");
$crsarr=$usrobj->get("ace_course au",null,"course_id,course_name,course_sname,course_sdate,course_edate,course_status,course_fee");
if($usrobj->count>0){
foreach ($crsarr as $key => $rslt) {
	$crs[]=Array("csid"=>$rslt["course_id"],"name"=>$rslt["course_name"],"sname"=>$rslt["course_sname"],"sdate"=>$rslt["course_sdate"],"edate"=>$rslt["course_edate"],"fee"=>$rslt["course_fee"]);

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
$crsarr=$usrobj->get("ace_course au",null,"course_id,course_name,course_sname,course_sdate,course_edate,course_status,course_fee,live_link,course_live,course_description");
if($usrobj->count>0){
foreach ($crsarr as $key => $rslt) {
	$crsdetl[]=Array("name"=>$rslt["course_name"],"sname"=>$rslt["course_sname"],"sdate"=>date("d-m-Y",strtotime($rslt["course_sdate"])),"edate"=>date("d-m-Y",strtotime($rslt["course_edate"])),"fee"=>$rslt["course_fee"],"live"=>$rslt['course_live'],"link"=>$rslt['live_link']);
	$des=$rslt['course_description'];

	}	

$usrobj->where("sb.course_id",$csid);
$usrobj->where("sb.cs_status",0);
$usrobj->join("ace_subject asb","asb.sub_id=sb.sub_id");
$sbsarr=$usrobj->get("course_subject sb",null,"asb.sub_id,asb.sub_name,asb.sub_sname");
foreach ($sbsarr as $key => $rslt) {
	$sbjdetl[]=Array("name"=>$rslt["sub_name"],"sname"=>$rslt["sub_sname"],"sbid"=>$rslt["sub_id"]);

	}	

$out['courses']=$crsdetl;	
$out['subjects']=$sbjdetl;	
$out['des']=$des?$des:"No Data";
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
$usrobj->where('cs.course_id',$crsid);
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
$toarr=$topobj->get("ace_topic",null,"top_id,top_title,top_img");
$topicnt= $topobj->count;
//echo $topobj->getLastQuery();
if($topobj->count>0){
foreach ($toarr as $key => $top) {
	$topids[]=$top["top_id"];
	$tops[]=Array("topid"=>$top["top_id"],"topic"=>$top["top_title"],"img"=>$top["top_img"]);
}
$topobj->where("top_id",$topids,"IN");
$topobj->groupBy("am_type");
$matarr=$topobj->get("ace_materials",null,"count(top_id) as cnt,am_type");
//echo $topobj->getLastQuery();
foreach($matarr as $index =>$mat){
  
if($mat["am_type"]==1){
    $vidcnt= $mat["cnt"]?$mat["cnt"]:0;
}
if($mat["am_type"]==0){
    $notcnt= $mat["cnt"]?$mat["cnt"]:0;
}

}
$out['topics']=$tops;	
$out['topicnt']=$topicnt?$topicnt:0;	
$out['vidcnt']=$vidcnt?$vidcnt:0;	
$out['notcnt']=$notcnt?$notcnt:0;	
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
case 'get-notification':
//ini_set("display_errors", 1);
$uid=$_REQUEST["uid"];

//$csid=$_REQUEST["csid"];
$crsobj=new MysqliDb(HOST,USER,PWD,DB);
$crsobj->where("u_id",$uid);
$csidarr=$crsobj->get("user_course",null,'course_id');
foreach ($csidarr as $key => $crs) {
	$crsid[]=$crs["course_id"];
}
//print_r($crsid);exit;

//$crsobj->where("(an.course_id=? OR an.course_id=?)",Array('0',$csid));
$crsobj->where("an.course_id",$crsid,"IN");
$crsobj->orwhere("an.course_id",0);
$crsobj->join("ace_course cs","cs.course_id=an.course_id AND course_status=1","LEFT");
$courarr=$crsobj->get("ace_notification an",null,"an.not_id,an.not_title,an.not_content,an.not_type,an.course_id,an.not_date,IF(not_type=0,cs.course_name,'All Courses') as csname");
//echo $crsobj->getLastQuery();
if($crsobj->count>0){
foreach ($courarr as $key => $rslt) {
	$notarr[]=Array("title"=>$rslt["not_title"],"content"=>$rslt["not_content"],"notdate"=>date("d M Y",strtotime($rslt["not_date"])),"csname"=>$rslt["csname"]);

	}	

$out['notification']=$notarr;	
$out["msg"]="done";
$out["sts"]="01";	
}
else{
$out["msg"]="No Notifications";
$out["sts"]="00";		
}
echo json_encode($out);
	break;
case 'latnotify':
//ini_set("display_errors", 1);
$uid=$_REQUEST["uid"];

$notid=$_REQUEST["notid"];
$crsobj=new MysqliDb(HOST,USER,PWD,DB);
$crsobj->where("u_id",$uid);
$csidarr=$crsobj->get("user_course",null,'course_id');
if($crsobj->count>0){
foreach ($csidarr as $key => $crs) {
	$crsid[]=$crs["course_id"];
}
//print_r($crsid);exit;
$crid=implode("," ,$crsid);

$qry="SELECT an.not_id FROM ace_notification an LEFT JOIN ace_course cs on cs.course_id=an.course_id AND course_status=1 WHERE an.not_id > $notid AND (an.course_id IN ($crid) OR an.course_id = '0') ";

$notarr=$crsobj->rawQuery($qry);
if($crsobj->count>0){
    $cnt=0;
foreach($notarr AS $not){
    $cnt++;
    $notids[]=$not["not_id"];
    
}

//print_r($notids);
$out['cnt']=$cnt;	
$out['notid']=max($notids);
$out["msg"]="done";
$out["sts"]="01";	
}
else{
$out["msg"]="No Notifications";
$out["sts"]="00";	
$out['cnt']=0;	
$out['notid']=$notid;
}
}
else{
$out['cnt']=0;	
$out['notid']=$notid;
$out["msg"]="No Notifications";
$out["sts"]="00";  
}
echo json_encode($out);
	break;	
case "topicmaterial":
$subid=$_REQUEST["sbid"];
$crsid=$_REQUEST["csid"];
$topobj=new MysqliDb(HOST,USER,PWD,DB);
$topobj->where("tp.sub_id",$subid);
$topobj->where("tp.course_id",$crsid);
$topobj->join("ace_topic tp","tp.top_id=mt.top_id");
$matarr=$topobj->get("ace_materials mt",null,"mt.am_url,mt.am_title,mt.am_type,tp.top_title,mt.top_id,tp.top_img");
//echo $topobj->getLastQuery();
//print_r($matarr);
if($topobj->count>0){
foreach ($matarr as $key => $mat) {
	if($mat["am_type"]==0){
	    $typ='notes';
		}
	if($mat["am_type"]==1){
	    $typ='videos';
	}
	$ttl[]=Array("topicnm"=>$mat['top_title']);
		$img[]=$mat['top_img'];
	$topics[$mat['top_title']][]=Array("title"=>$mat['am_title'],"link"=>$mat['am_url'],"type"=>$typ);
}
$out['img']=$img[0];	
$out['topicname']=$topics;
$out["msg"]="Topics";
$out["sts"]="01";

/*
$out['videos']=$vidar;	
$out['notes']=$nots;	
$out["msg"]="done";
$out["sts"]="01";	*/
}else{
$out["msg"]="No Topics";
$out["sts"]="00";
}
echo json_encode($out);
break;
		endswitch;	