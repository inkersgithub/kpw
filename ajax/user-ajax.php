<?php
include '../inc/config.php';
include '../inc/functions.php';
include '../inc/MysqliDb.php';
// if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
// die("cheating");
// }
ini_set('display_errors', 0);
$action=$_REQUEST['action'];
switch($action):
case 'addUsers':
//print_r($_REQUEST);exit;
$uid=$_REQUEST["uid"];	
$name=$_REQUEST["txtName"];	
$mail=$_REQUEST["txtEmail"];	
$phone=$_REQUEST["txtPhone"];	
$addr=$_REQUEST["txtAddr"];	
$pwd=md5($_REQUEST["txtPwd"]);
$usrobj=new MysqliDb(HOST,USER,PWD,DB);
$updarr=Array("u_fname"=>$name,"u_email"=>$mail,"u_mobile"=>$phone,"u_address"=>$addr);
$usrArray=Array("u_fname"=>$name,"u_email"=>$mail,"u_mobile"=>$phone,"u_address"=>$addr,"u_password"=>$pwd);

if($uid){
$usrobj->where("u_id",$uid);
$usrobj->update("ace_user",$updarr);
$out["status"]="done";

}
else{
$usrobj->where("u_email",$mail);
$usrobj->where("u_mobile",$phone);
//$usrobj->where("u_password",$pwd);
$usrarr=$usrobj->get("ace_user",null,"u_id,u_password");
//echo $usrobj->getLastQuery();
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
}
echo json_encode($out);
break;	
/*ADD/EDIT SUBJECTS*/
case 'addSubject':

//print_r($_FILES);exit;
$subid=$_REQUEST["subid"];
$sub=$_REQUEST["txtSubj"];
$code=$_REQUEST["txtsubcode"];
$img = $_FILES['txtFile']['name'];
$tmp = $_FILES['txtFile']['tmp_name'];

$dir="../assets/sb-img/";
$ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

$subobj=new MysqliDb(HOST,USER,PWD,DB);
$subarr=Array("sub_name"=>$sub,"sub_sname"=>$code);
if($subid){
$subobj->where("sub_id",$subid);
$subobj->update("ace_subject",$subarr);
$subobj->where("sub_id",$subid);
$dlmg=$subobj->getValue("ace_subject","sub_img");
if($tmp){
unlink($dir.$dlmg);
}
}
else{
$subobj->insert("ace_subject",$subarr);
$subid=$subobj->getInsertId();

	}
if($tmp){

move_uploaded_file($tmp, $dir.$subid.".".$ext);
$imgnm=$subid.".".$ext;
$subobj->where("sub_id",$subid);
$subobj->update("ace_subject",Array("sub_img"=>$imgnm));
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

//print_r($_FILES);exit;
// print_r($_REQUEST);

$csid=$_REQUEST["csid"];
$crsnm=$_REQUEST["txtcrs"];
$code=$_REQUEST["txtcode"];
$fee=$_REQUEST["txtfee"];
$link=$_REQUEST["txtlink"];
$descri=$_REQUEST["txtdescri"];
$link=$_REQUEST["txtlink"]?$_REQUEST["txtlink"]:"";
$live=$_REQUEST["chklive"]?$_REQUEST["chklive"]:"0";

$img = $_FILES['txtFile']['name'];
$tmp = $_FILES['txtFile']['tmp_name'];

$dir="../assets/cs-img/";
$ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

if($live==0){
$link="";	
}
$sdate=date("Y-m-d",strtotime($_REQUEST["txtsdate"]));
$edate=date("Y-m-d",strtotime($_REQUEST["txtedate"]));
$crsobj=new MysqliDb(HOST,USER,PWD,DB);
$crsarr=Array("course_name"=>$crsnm,"course_sname"=>$code,"course_sdate"=>$sdate,"course_edate"=>$edate,"course_fee"=>$fee,"course_live"=>$live,"live_link"=>$link,"course_description"=>$descri);
//print_r($crsarr);exit;

if($csid){
$crsobj->where("course_id",$csid);
$crsobj->update("ace_course",$crsarr);
$crsobj->where("course_id",$csid);
$dlmg=$crsobj->getValue("ace_course","course_img");
if($tmp){
unlink($dir.$dlmg);
}
}
else{
$crsobj->insert("ace_course",$crsarr);
$csid=$crsobj->getInsertId();
}
if($tmp){

move_uploaded_file($tmp, $dir.$csid.".".$ext);
$imgnm=$csid.".".$ext;
$crsobj->where("course_id",$csid);
$crsobj->update("ace_course",Array("course_img"=>$imgnm));
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
ini_set("display_errors", 0);
//print_r($_REQUEST);
//$uid=$_REQUEST["usid"];
$uid=$_REQUEST["usid"];
$csids=$_REQUEST["ucid"];
$crsarr=$_REQUEST["selcrs"];
$sdatearr=$_REQUEST["txtsdate"];
$edatearr=$_REQUEST["txtedate"];
$delids=explode(",", $_REQUEST["delids"]);

$asgobj=new MysqliDb(HOST,USER,PWD,DB);
if($delids[0]){
$asgobj->where("uc_id",$delids,"IN");
$asgobj->delete("user_course");	
}
foreach ($crsarr as $key => $crs) {
	/*$sdate=date("Y-m-d",strtotime($sdatearr[$key]));
	$edate=date("Y-m-d",strtotime($edatearr[$key]));*/
	$csid=$csids[$key]?$csids[$key]:"NULL";
	$sql[]="($csid,$uid,$crs)";
}
//print_r($sql);
$qry="INSERT INTO user_course (uc_id,u_id,course_id) VALUES ".implode(',', $sql)."ON DUPLICATE KEY UPDATE uc_id=VALUES(uc_id),course_id=VALUES(course_id),u_id=VALUES(u_id)";
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
//print_r($_REQUEST);
$courseid=$_REQUEST["csid"];
$subar=$_REQUEST["subj"];
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
$subobj->update("course_subject",Array("cs_status"=>9));

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
//print_r($_REQUEST);
$topid=$_REQUEST["topid"];
$courseid=$_REQUEST["courseid"];
$csid=$_REQUEST["csid"];
$subid=$_REQUEST["subid"];
$title=$_REQUEST["txtTitle"];
$descri=$_REQUEST["txtdescr"];

$img = $_FILES['txtFile']['name'];
$tmp = $_FILES['txtFile']['tmp_name'];

$dir="../assets/top-img/";
$ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

$topobj=new MysqliDb(HOST,USER,PWD,DB);
$toparr=Array("sub_id"=>$subid,"cs_id"=>$csid,"course_id"=>$courseid,"top_title"=>$title,"top_description"=>$descri);

if($topid){
$topobj->where("top_id",$topid);
$topobj->update("ace_topic",$toparr);
$topobj->where("top_id",$topid);
$dlmg=$topobj->getValue("ace_topic","top_img");
if($tmp){
unlink($dir.$dlmg);
}
}
else{
$topobj->insert("ace_topic",$toparr);
$topid=$topobj->getInsertId();

	}
	if($tmp){
$imgnm="tp".time()."1.".$ext;
move_uploaded_file($tmp, $dir.$imgnm);
//$imgnm=$topid.".".$ext;
$topobj->where("top_id",$topid);
$topobj->update("ace_topic",Array("top_img"=>$imgnm));
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
$crsid=$_REQUEST["crsid"];
$sts=$_REQUEST["sts"];
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
//print_r($_REQUEST);
$topid=$_REQUEST["topid"];
$topobj=new MysqliDb(HOST,USER,PWD,DB);
$topobj->where("top_id",$topid);
$topobj->where("am_type",0,"<>");
$matarr=$topobj->get("ace_materials",null,"*");
echo json_encode($matarr);
			break;
case 'getNotes':
//print_r($_REQUEST);
$topid=$_REQUEST["topid"];
$topobj=new MysqliDb(HOST,USER,PWD,DB);
$topobj->where("top_id",$topid);
$topobj->where("am_type",0);
$matarr=$topobj->get("ace_materials",null,"*");
echo json_encode($matarr);
			break;		
case 'AddMaterials':
/*print_r($_FILES);
print_r($_REQUEST);exit;*/
$matid=$_REQUEST["matid"];
$topid=$_REQUEST["topid"];
$typ=$_REQUEST["seltyp"];
$titlearr=$_REQUEST["txthead"];
$urls=$_REQUEST["txturl"];
$urls=$_REQUEST["txturl"];
$delids=explode(",", $_REQUEST["delids"]);
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

case 'getUsrSbj':
//print_r($_REQUEST);
$uid=$_REQUEST["uid"];
$usobj=new MysqliDb(HOST,USER,PWD,DB);
$usobj->where("u_id",$uid);
$usrcrs=$usobj->get("user_course",null,"uc_id,course_id as csid");
echo json_encode($usrcrs);
	break;
case 'DelTopics':
$delid=$_REQUEST["delid"];
$topobj=new MysqliDb(HOST,USER,PWD,DB);
$topobj->where("top_id",$delid);
$topobj->delete("ace_topic");

$topobj->where("top_id",$delid);
$topobj->delete("ace_materials");
if(!$topobj->getLastError()){
	$out["status"]="done";
}	
else{
	$out["status"]="error";
}
echo json_encode($out);
		break;
case 'DelUser':
$delid=$_REQUEST["delid"];
$delobj=new MysqliDb(HOST,USER,PWD,DB);
$delsql="INSERT INTO delete_user (u_id,u_fname,u_lname,u_mobile,u_password,u_email,u_address,u_device,u_course,u_status,u_time) SELECT u_id,u_fname,u_lname,u_mobile,u_password,u_email,u_address,u_device,u_course,u_status,u_time FROM ace_user WHERE u_id='$delid'";
	$delobj->rawQuery($delsql);
	$delobj->where("u_id",$delid);
	$delobj->delete("ace_user");
	$delobj->where("u_id",$delid);
	$delobj->delete("user_course");
	if(!$delobj->getLastError()){
	$out["status"]="done";
}	
else{
	$out["status"]="error";
}
echo json_encode($out);
break;		
case 'DelSubjects':
$delid=$_REQUEST["delid"];
$delobj=new MysqliDb(HOST,USER,PWD,DB);
$delobj->where("sub_id",$delid);
$delobj->delete("ace_subject");
if(!$delobj->getLastError()){
	$out["status"]="done";
}	
else{
	$out["status"]="error";
}
echo json_encode($out);
	break;
case 'addNoty':
//ini_set("display_errors", 1);
$tle=$_REQUEST["txthead"];
$cont=$_REQUEST["txtcont"];
$glb=$_REQUEST["chknot"]?"1":"0";
$glb=$_REQUEST["chknot"]?"1":"0";
$crs=$glb==0 ?$_REQUEST["selcrs"]:"0";
$str=$glb==1?"global":"cs".$_REQUEST["selcrs"];

$notobj=new MysqliDb(HOST,USER,PWD,DB);
$notarr=Array("not_title"=>$tle,"not_content"=>$cont,"not_type"=>$glb,"course_id"=>$crs);
//print_r($notarr);
pushMessage($tle,$cont,$str);
$notobj->insert("ace_notification",$notarr);
if(!$notobj->getLastError()){
	$out["status"]="done";
}	
else{
	$out["status"]="error";
}
echo json_encode($out);
		break;	
case 'DelCourse':
$delid=$_REQUEST["delid"];
$delobj=new MysqliDb(HOST,USER,PWD,DB);
$delobj->where("course_id",$delid);
$delobj->update("ace_course",Array("course_status"=>9));
if(!$delobj->getLastError()){
	$out["status"]="done";
}	
else{
	$out["status"]="error";
}
echo json_encode($out);
	break;	
case "useractive":
 $uid=$_REQUEST["uid"];
 $sts=$_REQUEST["sts"];
$actobj=new MysqliDb(HOST,USER,PWD,DB);
$actobj->where("u_id",$uid);
$actobj->update("ace_user",Array("u_status"=>$sts));
if(!$actobj->getLastError()){
	$out["status"]="done";
	if($sts==9)
	{
		    pushMessage("KSEB Power Failure Notification","There is a fuse burnout in your distribution transformer, it will take 25 min to re establish the supply.","global");
	}
}	
else{
	$out["status"]="error";
}
echo json_encode($out);  
    break;

 case 'AddNotes':
 /*print_r($_REQUEST);
 print_r($_FILES);exit;*/

$matid=$_REQUEST["matid"];
$topid=$_REQUEST["topid"];
$titlearr=$_REQUEST["txthead"];
$pdfarr=$_REQUEST["flpdf"];

$imgarr = $_FILES['flenots']['name'];
$tmparr = $_FILES['flenots']['tmp_name'];
$dir="../assets/notes/";
$delids=explode(",", $_REQUEST["delids"][0]);
//print_r($pdfarr);exit;
$topobj=new MysqliDb(HOST,USER,PWD,DB);

foreach ($titlearr as $key => $tit) {
$url=time().$key.".pdf";
$tmp=$tmparr[$key]?$tmparr[$key]:"";
$flnm=$tmparr[$key]?$url:$pdfarr[$key];
//echo $flnm;
if($tmp){
	move_uploaded_file($tmp, $dir.$flnm);
}
	$amid=$matid[$key]?$matid[$key]:"NULL";
	$sql[]="($amid,$topid,'$flnm','$tit',0)";
}
$qry= 'INSERT INTO ace_materials (am_id,top_id,am_url,am_title,am_type) VALUES '.implode(",", $sql)."ON DUPLICATE KEY UPDATE am_id=VALUES(am_id),top_id=VALUES(top_id),am_url=VALUES(am_url),am_title=VALUES(am_title),am_type=VALUES(am_type)";
//echo $qry;exit;
$topobj->rawQuery($qry);
if($delids[0]){
$topobj->where("am_id",$delids,"IN");
$pdfarr=$topobj->get("ace_materials",null,"am_url");
foreach ($pdfarr as $key => $pdf) {
		unlink($dir.$pdf["am_url"]);
	}	
$topobj->where("am_id",$delids,"IN");
$topobj->delete("ace_materials");	
}
if(!$topobj->getLastError()){
	$out["status"]="done";
}	
else{
	$out["status"]="error";
}
echo json_encode($out);
    	break;   
/*USER REFRESH*/    	
case 'userRefresh':
$uid=$_REQUEST["uid"];
$refobj=new MysqliDb(HOST,USER,PWD,DB);
$refobj->where("u_id",$uid);
$refobj->update("ace_user",Array("u_status"=>0));
if(!$refobj->getLastError()){
	$out["status"]="done";
}	
else{
	$out["status"]="error";
}
echo json_encode($out);  
	break;    	
    	
endswitch;	