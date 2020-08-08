<?php
include("../inc/config.php");
include("../inc/MysqliDb.php");
include("../inc/functions.php");
include("../inc/mail-load.php");

ini_set('display_errors',0);

$fname=$_REQUEST["name"];
$mobile=$_REQUEST["mobile"];
$mailid=$_REQUEST["email"];
$pwd=$_REQUEST["password"];
$regobj=new MysqliDb(HOST,USER,PWD,DB);
$regobj->where("(u_email LIKE ? OR u_mobile LIKE ?)",Array($mailid,$mobile));
$loginarray=$regobj->getOne("ace_user","u_email,u_fname,u_id,u_mobile");
//echo $regobj->getLastQuery();
if($regobj->count >0)
{
$out["msg"]=$loginarray["u_email"]==$mailid?"specified email ID already in use":"";
$out["msg"]=$loginarray["u_mobile"]==$mobile?"given mobile number already in use":$out["msg"];
$out["sts"]="00";

}
else
{
$enpwd=md5($pwd);
$regarry=Array("u_fname"=>$fname,"u_mobile"=>$mobile,"u_email"=>$mailid,"u_password"=>$enpwd,"u_status"=>"0");
$regobj->insert("ace_user",$regarry);
$userid=$regobj->getInsertId();
//print_r($_POST);
//echo $regobj->getLastError()."fffffff";
if(!$regobj->getLastError()){
$out["msg"]="Successfully Registered.";
$out["sts"]="01";

$mailmsg="<!DOCTYPE html><html><head><title>Ace Edu Plus</title></head><body style='background-color:#fff;color:#666666;'><div style='max-width:700px margin:auto; '><table style='border-spacing:0;font-family:sans-serif;margin:0 auto;max-width:700px;width:100%; ' align='center'><tbody style='background-color:#000151;color:#fff;'><tr><td style='' ><table style='border-spacing:0;font-family:sans-serif' width='100%'><tbody><tr><td ></td></tr><tr></tr></tbody></table></td></tr><tr><td  style='padding:0'><p style='font-size:20px;font-weight:bold;line-height:22px;text-transform:none;text-align: center;color:#fff!important;'>Greeting from Ace Edu Plus </p><div style='color: #f7ea00!important;margin-left: 5px;font-size:16px;'>Welcome $fname</div><br><br><div style='font-family: Myriad Pro;font-size: 15px;padding: 15px 15px;margin: auto;'>Your account has been created successfully on Ace Edu Plus. Please use the below credentials to get access </div><tr><td width='25%' style='padding:10px;color:#fff!important;' >Your user name:  $mailid</td></tr><tr><td style='padding:10px; '>Password:  $pwd  </td></tr><tr></td></tr><tr style='background-color:#fff;color:#181818;'><td  style='border:1px solid #181818;' ><table align='center' width='100%'cellspacing='0' cellpadding='0'>      <tr>    <td></td>     <td align='center'>     </td><td align='right'><tr style='background-color: #000151'><td style='padding:10px;color: #f7ea00!important;margin-left: 5px;font-size:16px;'>For any help Contact us   : +91 8606387830 </td></tr></td></tr></table></td></tr></tbody></table></div></body></html>";   

 
$mail->isHTML(true);                                  
$mail->addAddress($mailid, $fname);  
$mail->addAddress('jissanto@gmail.com', 'Jiss Anto');
    $mail->addAddress('punyaashokkm@gmail.com', 'PUNYA');
    $mail->Subject = 'Ace Edu plus User registration';
    $mail->Body    = $mailmsg;
   // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

}
}
echo json_encode($out);
	
