<?php
include 'inc/config.php';
include 'inc/functions.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="<?=BASE?>/img/icon.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>KPOWER</title>

  <!-- Custom fonts for this template-->
  <link href="<?=BASE?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="<?=BASE?>/css/sb-admin-2.css" rel="stylesheet">
  <link href="<?=BASE?>/css/style.css" rel="stylesheet">


</head>

<body style="background-image:url('<?=BASE?>/img/bg.jpg'); background-size:500px;">



  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center mtop15p">

      <div class="col-lg-5">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                      <div class="sidebar-brand-icon" style="padding-bottom:10px; display:inline-block">
                      <img src="<?=BASE?>/img/longlogo.png" style="width:160px; " alt="">
                     
                      </div>
                     
                  </div>
                  <form class="user" id="regForm"  method="post">
                    <div class="form-group">
                      <label style="font-size:14px;">Email ID</label>
                      <input type="email" class="form-control"  aria-describedby="emailHelp" placeholder="name@yourdomain.com" id="regEmailInput" name="email" required>
                    </div>
                    <div class="form-group">
                      <label style="font-size:14px;">Password</label>
                      <input type="password" class="form-control" placeholder="*********" id="regPassInput" name="password" required>
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                      </div>
                    </div>
                    <button id="regBtn" class="btn btn-primary btn-block">
                      Login
                    </button>
                  </form>
                 <!--<br>-->
                 <!-- <div class="text-center">-->
                 <!--   <a class="small" href="forgot-password.html">Forgot Password?</a>-->
                 <!-- </div>-->
                </div>

              </div>

            </div>

          </div>
        </div>

      </div>

    </div>

  </div>
  
  <div style="position:fixed; bottom:0;left:0px; padding:20px;">
    <b style="font-size:11px;">By Inkers Tech Labs</b>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="<?=BASE?>/vendor/jquery/jquery.min.js"></script>
  <script src="<?=BASE?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src=<?=BASE?>/"vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?=BASE?>/js/sb-admin-2.min.js"></script>
  <script src="<?=BASE?>/js/script.js"></script>
  
<script>
$(document).ready(function(){

    var valreg=false;


$('#regEmailInput').change(function() {
var letters = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
if (this.value.match(letters)) {
    valreg =  true;
} else {
showToast('Please enter a valid Email ID.');
valreg = false;
}
});

$('#regPassInput').change(function() {
if (this.value.length>=6) {
    valreg =  true;
} else {
showToast('Invalid password length!');
valreg = false;
}
});

//regForm
$(document).on("submit","#regForm",function(evt){
evt.preventDefault();

if($('#regEmailInput').val().length>5){
$("#regBtn").html("Please wait...").attr("disabled","disabled");

$.ajax({
url: 'ajax/login',
type: 'POST',
data:$("#regForm").serialize(),
success: function(response, textStatus, xhr) {
console.log(response);
$("#regBtn").html("Login").removeAttr("disabled");
try{
var jresp=$.parseJSON(JSON.stringify(response));
if(jresp.sts==="01"){
showToast("Login successful.");
setTimeout(function() {
    
PostToken("<?=BASE?>/",jresp.token);
}, 1000);
}
else
{
showToast(jresp.msg);
}
}catch(exp){
showToast("Something went wrong, Please try again");
}
//called when successful
},
error: function(xhr, textStatus, errorThrown) {
//called when there is an error
}
});
}else{
    showToast("Something went wrong, Please try again");
}   
}); 


});

    </script>

</body>

</html>
