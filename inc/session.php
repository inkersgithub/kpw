<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_POST['session_token']) || isset($_SESSION["session_token"]))
{

if(isset($_POST['session_token']))
{$session_token=$_POST['session_token'];}
if(isset($_SESSION['session_token']))
{$session_token=$_SESSION['session_token'];}
$db = new MysqliDb (HOST,USER,PSD,DB);
$db->join("admin a", "s.name=a.id", "LEFT");
$db->where("s.key",$session_token);
$db->Where("s.is_active",1);
$db->Where("a.is_active",1);
$usersession = $db->getOne ("session s");
$sessionip=$usersession['ip'];
if($usersession['control']>0)
{$_SESSION["session_token"]=$session_token;
$_SESSION["rpa_control"]=$usersession['control'];
$_SESSION["sessionuser"]=$usersession['name'];
$_SESSION["sessionuserid"]=$usersession['id'];
}
else{
    header("Location: ".BASE."/login");
}
}
else{header("Location: ".BASE."/login");}

if(isset($_GET['action']))
{
if($_GET['action']=="logout")
{
session_unset();
session_destroy(); 
header("Location: ".BASE."/login");
}
}


?>