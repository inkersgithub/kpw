<?php
include '../inc/config.php';
include '../inc/functions.php';
include '../inc/MysqliDb.php';
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
die("cheating");
}
ini_set('display_errors', 0);
$action=$_REQUEST['action'];
switch($action):
case 'addUsers':
$name=$_POST["txtName"];	
$mail=$_POST["txtEmail"];	
$phone=$_POST["txtPhone"];	
$addr=$_POST["txtAddr"];	
$pwd=md5($_POST["txtPwd"]);
$usrobj=new MysqliDb(HOST,USER,PWD,DB);
$usrobj->where("u_email",$mail);
$usrobj->where("u_mobile",$phone);
//$usrobj->where("u_password",$pwd);
$usrarr=$usrobj->get("ace_user",null,"u_id,u_password");
//echo $usrobj->getLastQuery();
$usrArray=Array("u_fname"=>$name,"u_email"=>$mail,"u_mobile"=>$phone,"u_password"=>$pwd,"u_address"=>$addr);
//print_r($usrArray);exit;
if($usrobj->count >0){
if($usrarr["u_password"]==$pwd){
$out["status"]="User Already Exist";
}
else{
$out["status"]="User Already Exist";
}
}else{
$usrobj->insert("ace_user",$usrArray);
//echo $usrobj->getLastQuery();
if(!$usrobj->getLastError()){
$out["status"]="done";

}	
}
echo json_encode($out);
break;	
/*ADD/EDIT SUBJECTS*/
case 'addSubject':
$subid=$_POST["subid"];
$sub=$_POST["txtSubj"];
$code=$_POST["txtsubcode"];
$subobj=new MysqliDb(HOST,USER,PWD,DB);
$subarr=Array("sub_name"=>$sub,"sub_sname"=>$code);
if($subid){
$subobj->where("sub_id",$subid);
$subobj->update("ace_subject",$subarr);
}
else{
$subobj->insert("ace_subject",$subarr);
	}
if(!$subobj->getLastError()){
	$out["status"]="done";
}	
else{
	$out["status"]="error";
}
echo json_encode($out);
break;
/*ADD/EDIT SUBJECTS END*/
/*ADD/EDIT COURSES*/
case 'addcourse':
//print_r($_POST);exit;
$csid=$_POST["csid"];
$crsnm=$_POST["txtcrs"];
$code=$_POST["txtcode"];
$fee=$_POST["txtfee"];
$sdate=date("Y-m-d",strtotime($_POST["txtsdate"]));
$edate=date("Y-m-d",strtotime($_POST["txtedate"]));
$crsobj=new MysqliDb(HOST,USER,PWD,DB);
$crsarr=Array("course_name"=>$crsnm,"course_sname"=>$code,"course_sdate"=>$sdate,"course_edate"=>$edate,"course_fee"=>$fee);
if($csid){
$crsobj->where("course_id",$csid);
$crsobj->update("ace_course",$crsarr);
}
else{
$crsobj->insert("ace_course",$crsarr);
	}
if(!$crsobj->getLastError()){
	$out["status"]="done";
}	
else{
	$out["status"]="error";
}
echo json_encode($out);
break;
/*ADD/EDIT COURSES END*/
/*ASSIGN COURSE TO USERS*/
case 'assignCourse':
//print_r($_POST);
//$uid=$_POST["usid"];
$uid=$_POST["usrid"];
$crsarr=$_POST["selcrs"];
$sdatearr=$_POST["txtsdate"];
$edatearr=$_POST["txtedate"];
$asgobj=new MysqliDb(HOST,USER,PWD,DB);
foreach ($crsarr as $key => $crs) {
	/*$sdate=date("Y-m-d",strtotime($sdatearr[$key]));
	$edate=date("Y-m-d",strtotime($edatearr[$key]));*/
	$sql[]="($uid,$crs)";
}
$qry="INSERT INTO user_course (u_id,course_id) VALUES ".implode(',', $sql);
//echo $qry;
$asgobj->rawQuery($qry);
if(!$asgobj->getLastError()){
$out["status"]="done";
	}
	else{
$out["status"]="error";

	}
	echo json_encode($out);

break;
/*ASSIGN COURSE TO USERS ENDS*/
/*ASSIGN SUBJECT TO COURSE */
case 'subAssgn':
//print_r($_POST);
$courseid=$_POST["csid"];
$subar=$_POST["subj"];
$subobj=new MysqliDb(HOST,USER,PWD,DB);
foreach ($subar as $key => $sbar) {
	$sbjarr=explode("|", $sbar);
	$csid=$sbjarr[1]?$sbjarr[1]:'NULL';
	$sql[]="($csid,$sbjarr[0],$courseid)";
	$ascsid[]=$sbjarr[1];
}
//print_r($ascsid);
$subobj->where("cs_id",$ascsid,"NOT IN");
$subobj->where("course_id",$courseid);
$subobj->delete("course_subject");

$qry= 'INSERT INTO course_subject (cs_id,sub_id,course_id) VALUES '.implode(",", $sql)."ON DUPLICATE KEY UPDATE cs_id=VALUES(cs_id),sub_id=VALUES(sub_id),course_id=VALUES(course_id)";
$subobj->rawQuery($qry);
if(!$subobj->getLastError()){
$out["status"]="done";
}
else{
$out["status"]="done";
}
echo json_encode($out);
	break;
/*ASSIGN SUBJECT TO COURSE ENDS*/
case 'addTopic':
//print_r($_POST);
$topid=$_POST["topid"];
$courseid=$_POST["courseid"];
$csid=$_POST["csid"];
$subid=$_POST["subid"];
$title=$_POST["txtTitle"];
$descri=$_POST["txtdescr"];
$topobj=new MysqliDb(HOST,USER,PWD,DB);
$toparr=Array("sub_id"=>$subid,"cs_id"=>$csid,"course_id"=>$courseid,"top_title"=>$title);

if($topid){
$topobj->where("top_id",$topid);
$topobj->update("ace_topic",$toparr);
}
else{
$topobj->insert("ace_topic",$toparr);
	}
if(!$topobj->getLastError()){
	$out["status"]="done";
}	
else{
	$out["status"]="error";
}
echo json_encode($out);
	break;
case 'activeCourse':
$crsid=$_POST["crsid"];
$sts=$_POST["sts"];
$crsobj=new MysqliDb(HOST,USER,PWD,DB);
$crsarr=Array("course_status"=>$sts);
$crsobj->where("course_id",$crsid);
$crsobj->update("ace_course",$crsarr);
if(!$crsobj->getLastError()){
	$out["status"]="done";
}	
else{
	$out["status"]="error";
}
echo json_encode($out);
		break;	
case 'getMaterials':
//print_r($_POST);
$topid=$_POST["topid"];
$topobj=new MysqliDb(HOST,USER,PWD,DB);
$topobj->where("top_id",$topid);
$matarr=$topobj->get("ace_materials",null,"*");
echo json_encode($matarr);
			break;		
case 'AddMaterials':
//print_r($_POST);exit;
$matid=$_POST["matid"];
$topid=$_POST["topid"];
$typ=$_POST["seltyp"];
$titlearr=$_POST["txthead"];
$urls=$_POST["txturl"];
$urls=$_POST["txturl"];
$delids=explode(",", $_POST["delids"]);
$topobj=new MysqliDb(HOST,USER,PWD,DB);
if($delids[0]){
$topobj->where("am_id",$delids,"IN");
$topobj->delete("ace_materials");	
}
foreach ($titlearr as $key => $tit) {
	$type=$typ[$key];
	$url=$urls[$key];
	$amid=$matid[$key]?$matid[$key]:"NULL";
	$sql[]="($amid,$topid,'$url','$tit',$type)";
}
$qry= 'INSERT INTO ace_materials (am_id,top_id,am_url,am_title,am_type) VALUES '.implode(",", $sql)."ON DUPLICATE KEY UPDATE am_id=VALUES(am_id),top_id=VALUES(top_id),am_url=VALUES(am_url),am_title=VALUES(am_title),am_type=VALUES(am_type)";
//echo $qry;exit;
$topobj->rawQuery($qry);
if(!$topobj->getLastError()){
	$out["status"]="done";
}	
else{
	$out["status"]="error";
}
echo json_encode($out);
		break;
break;	
endswitch;	