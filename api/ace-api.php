<?php
include("../inc/config.php");
include("../inc/MysqliDb.php");
include("../inc/functions.php");


ini_set('display_errors',0);
$action=$_REQUEST['action'];
switch($action):
case 'login':
    
$user=$_REQUEST["moboremail"];
$pwd=md5($_REQUEST["password"]);
//$pnid=$_REQUEST["pnid"]?$_REQUEST["pnid"]:NULL;
$loginobj=new MysqliDb(HOST,USER,PWD,DB);
$loginobj->where("u_status",9,"<>");
$loginobj->where("u_password",$pwd);
$loginobj->where("(u_email=? OR u_mobile=?)",Array($user,$user));
$logarr=$loginobj->getOne('ace_user','u_id,u_fname,u_password,u_mobile,u_email,u_address,u_device,u_status');
//echo $loginobj->getLastQuery();
if($loginobj->count >0)
{
if($logarr["u_password"]==$pwd)
{
if($logarr['u_status']>=0){
$out['fname']=$logarr['u_fname'];
$out['mobile']=$logarr['u_mobile'];
$out['email']=$logarr['u_email'];
$out['uid']=$logarr['u_id'];
$out['address']=$logarr['u_address'];
$out['uc']=$logarr['u_device'];
$out['sts']="01";  
$out['msg']="Successfully Logged In"; 
//u_pnid 
$loginobj->where('u_id',$logarr['u_id']);
$loginobj->update("ace_user",Array("u_status"=>'1'));
}
else{
$out['sts']="00";  
$out['msg']="Already logged in on another device !.";	
}
}
else{
$out['sts']="00";  
$out['msg']="Invalid Password !";  
}
}
else{
$out['sts']="00";  
$out['msg']="Invalid credentials !";
}
echo json_encode($out);
	break;
case 'register':
$fname=$_REQUEST["name"];
$mobile=$_REQUEST["mobile"];
$mailid=$_REQUEST["email"];
$regobj=new MysqliDb(HOST,USER,PWD,DB);
$regobj->where("(u_email LIKE ? OR u_mobile LIKE ?)",Array($mailid,$mobile));
$loginarray=$regobj->getOne("ace_user","u_email,u_fname,u_id,u_mobile");
//echo $regobj->getLastQuery();
if($regobj->count >0)
{
$out["msg"]=$loginarray["u_email"]==$mailid?"Specified email ID already in use":"";
$out["msg"]=$loginarray["u_mobile"]==$mobile?"Given mobile number already in use":$out["msg"];
$out["sts"]="00";

}
else
{
  $str="1234567890asdfghjklzxcvbnmqwertyuipASDGHJKLZXCVBNMQWERTYUIP";
  for($i=0;$i<=5;$i++)
  {
    $pwd.=substr($str,rand(0,60),1);
  }

$enpwd=md5($pwd);
$regarry=Array("u_fname"=>$fname,"u_mobile"=>$mobile,"u_email"=>$mailid,"u_password"=>$enpwd,"u_status"=>"0");
$regobj->insert("ace_user",$regarry);
$userid=$regobj->getInsertId();
//print_r($_POST);
//echo $regobj->getLastError()."fffffff";
if(!$regobj->getLastError()){
$out["msg"]="Successfully Registered.Password has been sent to your email";
$out["sts"]="01";

$mailmsg="<!DOCTYPE html>
<html>

<head>
    <title>Ace Edu Plus</title>
</head>

<body style='background-color:#fff;color:#666666;'>
    <div style='max-width:700px; margin:50px; '>
        <table style='border-spacing:0;font-family:sans-serif;margin:0 auto;max-width:700px;width:100%; ' align='center'>
            <tbody style='background-color:#fff; color:#000;'>
                <tr>
                    <td style=''>
                        <table style='border-spacing:0;font-family:sans-serif' width='100%'>
                            <tbody>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr></tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style='padding:0'>
                        <p style='font-size:20px;font-weight:bold;line-height:22px;text-transform:none;text-align: left;color:#000!important;'>
                            <img src='https://aceeduplus.in/img/longlogo.png' style='width: 120px;'> </p>
                        <div style='color: #000!important;font-size:16px;'>Hello <b>$fname</b>,<br>Greeting from Ace Edu Plus</div><br>
                        <div style='margin: auto;'>Your account has been created successfully on Ace Edu Plus. Please use the below credentials to get access.</div>
                        <br>
                        <tr>
                            <td width='25%' style='color:#000!important;'>Your login Email ID: <b>$mailid</b></td>
                        </tr>
                        <tr>
                            <td style=''>Password: <b>$pwd</b> </td>
                        </tr>
                        <tr>
                    </td>
                    </tr>
                    <tr style='background-color:#fff;color:#181818;'>
                        <td style='border:0px solid #181818;'>
                            <br>
                            <table align='center' border=0 width='100%' cellspacing='0' cellpadding='0'>
                                <tr>
                                    <td></td>
                                    <td align='center'> </td>
                                    <td align='right'>
                                        <tr style='background-color: #999; '>
                                           <td style='padding:10px;color: #000!important;margin-left: 5px; font-weight:bold;font-size:16px;'>For any help Contact us : +91 9037199928/ +91 7034199928 </td>
                                        </tr>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
</body>

</html>";   

 
$mail->isHTML(true);                                  
$mail->addAddress($mailid, $fname);  
$mail->addBCC('jissanto@gmail.com', 'Jiss Anto');
    $mail->addBCC('punyaashokkm@gmail.com', 'PUNYA');
    
    $mail->Subject = 'Ace Edu plus User registration';
    $mail->Body    = $mailmsg;
   // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

}
}
echo json_encode($out);
break;	
case 'forgot':
$frguser=$_REQUEST["moboremail"];
$regobj=new MysqliDb(HOST,USER,PWD,DB);
$regobj->where("(u_email LIKE ? OR u_mobile LIKE ?)",Array($frguser,$frguser));
$loginarray=$regobj->getOne("ace_user","u_email,u_fname,u_id,u_mobile");
$mailid=$loginarray["u_email"];
$fname=$loginarray["u_fname"];
$mobile=$loginarray["u_mobile"];
//echo $regobj->getLastQuery();
if($regobj->count >0)

{
 $str="1234567890asdfghjklzxcvbnmqwertyuipASDGHJKLZXCVBNMQWERTYUIP";
  for($i=0;$i<=5;$i++)
  {
    $pwd.=substr($str,rand(0,60),1);
  }
//echo $pwd;
$enpwd=md5($pwd);
$regarry=Array("u_password"=>$enpwd);
$regobj->where("u_id",$loginarray['u_id']);
$regobj->update("ace_user",$regarry);
//echo $regobj->getLastQuery();
if(!$regobj->getLastError()){
$out["msg"]="Password has been sent to your email.";
$out["sts"]="01";
$mailmsg="<!DOCTYPE html>
<html>

<head>
    <title>Ace Edu Plus</title>
</head>

<body style='background-color:#fff;color:#666666;'>
    <div style='max-width:700px; margin:50px; '>
        <table style='border-spacing:0;font-family:sans-serif;margin:0 auto;max-width:700px;width:100%; ' align='center'>
            <tbody style='background-color:#fff; color:#000;'>
                <tr>
                    <td style=''>
                        <table style='border-spacing:0;font-family:sans-serif' width='100%'>
                            <tbody>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr></tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style='padding:0'>
                        <p style='font-size:20px;font-weight:bold;line-height:22px;text-transform:none;text-align: left;color:#000!important;'>
                            <img src='https://aceeduplus.in/img/longlogo.png' style='width: 120px;'> </p>
                        <div style='color: #000!important;font-size:16px;'>Hello <b>$fname</b>,<br>Greeting from Ace Edu Plus</div><br>
                        <div style='margin: auto;'>Your account password has been reset successfully on Ace EduPlus. Please use the below credentials to get access.</div>
                        <br>
                        <tr>
                            <td width='25%' style='color:#000!important;'>Your login Email ID: <b>$mailid</b></td>
                        </tr>
                        <tr>
                            <td style=''>Password: <b>$pwd</b> </td>
                        </tr>
                        <tr>
                    </td>
                    </tr>
                    <tr style='background-color:#fff;color:#181818;'>
                        <td style='border:0px solid #181818;'>
                            <br>
                            <table align='center' border=0 width='100%' cellspacing='0' cellpadding='0'>
                                <tr>
                                    <td></td>
                                    <td align='center'> </td>
                                    <td align='right'>
                                        <tr style='background-color: #999; '>
                                            <td style='padding:10px;color: #000!important;margin-left: 5px; font-weight:bold;font-size:16px;'>For any help Contact us : +91 9037199928/ +91 7034199928 </td>
                                        </tr>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
</body>

</html>";   

 
$mail->isHTML(true);                                  
$mail->addAddress($mailid, $fname);  
//$mail->addAddress('jissanto@gmail.com', 'Jiss Anto');
//$EMAILOBJ->addBCC('punyaashokkm@gmail.com');

    $mail->addBCC('punyaashokkm@gmail.com', 'PUNYA');
    $mail->Subject = 'Ace Edu plus Reset password';
    $mail->Body    = $mailmsg;
   // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();


}
}
else{
$out["msg"]="User is not Registered";
$out["sts"]="01";
}
echo json_encode($out);
	break;
case "userStatus":
$uid=$_REQUEST["uid"];
//$pnid=$_REQUEST["pnid"];
$regobj=new MysqliDb(HOST,USER,PWD,DB);
$regobj->where("u_status",9,"<>");
$regobj->where("u_id",$uid);
$user=$regobj->getOne("ace_user","u_id,u_pnid");
if($regobj->count>0){
  /*if($pnid){
     if($pnid==$user["u_pnid"]) {
         $out["sts"]="01"; 
         $out["msg"]="Active";
         $out["vcode"]="1";
     }
     else{
         $out["sts"]="00"; 
   $out["msg"]="InActive";
     }
  }  
  else{
   $out["sts"]="01"; 
   $out["msg"]="Active";
   $out["vcode"]="1";
  }*/
   $out["sts"]="01"; 
   $out["msg"]="Active";
   $out["vcode"]="1";
}else{

     $out["sts"]="00"; 
   $out["msg"]="There is a fuse burnout in your distribution transformer, it will take 25 min to re establish the supply.";
}
echo json_encode($out);
break;    
case 'updatecheck':
 $newversn="1.0";
 $newcode="1";
 $apversn=$_REQUEST["vername"];
 $apcode=$_REQUEST["vercode"];
 if($apversn==$newversn && $apcode==$newcode){
  $out["sts"]="00"; 
   $out["msg"]="no update";
 }
 else{
  $out['sts']="01";
  $out['msg']="New update Available";

 }
echo json_encode($out);
endswitch;	