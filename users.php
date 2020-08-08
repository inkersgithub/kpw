<?php
include 'inc/config.php';
include 'inc/MysqliDb.php';
include 'inc/session.php';
include 'inc/template.php';
include 'inc/functions.php';

headBlock("Users");
$fdt=date('d-m-Y');

?>  


<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

<?php sideBar(); ?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

<!-- Main Content -->
<div id="content">

<?php topBar(); ?>

<!------------------------------------------------------------------ Begin Page Content ------------------------------------------------------>
<div class="container-fluid">


<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-0">

<div style="position:fixed; bottom:30px; right:30px;"> 
<button class="btn btn-primary btn-circle btn-lg" data-toggle="modal" data-target="#userModal">
<i class="fas fa-fw fa-plus"></i>
</button>


</div>

<!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->

<!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
</div>



<div class="card shadow mb-4" style="width: 100%;">
<div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Users</h6></div>
<div class="card-body">
<?php
$usrobj=new MysqliDb(HOST,USER,PWD,DB);
$usrobj->groupBy('au.u_id');
$usrobj->orderBy('au.u_id',"DESC");
$usrobj->join("user_course uc",'uc.u_id=au.u_id',"LEFT");
$usrobj->join("ace_course cs",'cs.course_id=uc.course_id AND cs.course_status=1',"LEFT");
$userr=$usrobj->get("ace_user au",null,"au.u_id,u_fname,u_lname,u_mobile,u_password,u_email,u_address,u_device,u_course,u_status,u_time,GROUP_CONCAT(cs.course_name) AS crs,au.u_status");
?>
<table class="table table-ordered" id="tblUsers">
  <thead>
    <tr>
      <th width="5%">Si no.</th>
      <th  width="10%">Name</th>
      <th  width="10%">Email</th>
      <th  width="10%">Mobile</th>
      <th width="30%">Address</th>
      <th  width="15%">Consumer No.</th>
      <th  width="25%">Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($userr as $key => $usr) {
        
        $cls=($usr["u_status"]==0 OR $usr["u_status"]==1)?"btn-success":"btn-danger";
        $sts=($usr["u_status"]==0 OR $usr["u_status"]==1)?"Supply Available":"Supply Not Available";
        $stsval=($usr["u_status"]==9)?"0":"9";
      ?>
      <tr>
        <td><?php echo $key+1;?></td>
        <td><?php echo $usr["u_fname"]?></td>
        <td><?php echo $usr["u_email"]?></td>
        <td><?php echo $usr["u_mobile"]?></td>
         <td><?php echo $usr["u_address"]?></td>
          <td><?php echo $usr["u_device"]?></td>
        <td><a href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#userModal" data-id='<?php echo $usr["u_id"]?>' data-nm="<?php echo $usr["u_fname"]?>" data-email="<?php echo $usr["u_email"]?>" data-mobile="<?php echo $usr["u_mobile"]?>" data-addr="<?php echo $usr["u_address"]?>" ><i class="fa fa-edit"></i></a>&nbsp;<button class="btn btn-sm <?php echo $cls?> btnActive" data-sts="<?php echo $stsval?>" data-id="<?php echo $usr["u_id"]?>" ><?php echo $sts?></button>&nbsp;<a href="#"  data-id='<?php echo $usr["u_id"]?>' class="btn btn-primary btn-sm btnrefresh" hidden><i class="fa fa-refresh"></i>
</a></td>
      </tr>
      <?php
    }
      ?>
    
  </tbody>
</table>
</div></div>
<!-- Modal -->






<!-- /.container-fluid -->
<!-------------------------------------------------------------------------------------------------------------------------------------->

</div>
<!-- End of Main Content -->


<!-- Modal -->
<div id="userModal" class="modal fade" role="dialog">
<div class="modal-dialog" >

<div class="modal-content" >
 <div style=" width:100%; border-radius:4px 4px 0 0;" class="bg-gradient-primary">
        <button class="btn text-white" disabled>Add new User</button>
         <button style="float:right;" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#userModal">
<span class="icon text-white-50">
<i class="fas fa-fw fa-times"></i>
</span>
</button>
      </div>

<div class="modal-body">
<div class="">


<form class="user" id="frmUsers" method="POST" >
  <input type="hidden" name="action" value="addUsers">
  <input type="hidden" name="uid" id="usrid" value="">
<div class="form-group row">
<div class="col-sm-6 mb-3 mb-sm-0">
<input style="
padding: 20px 10px;" type="" class="form-control required" id="txtName" placeholder="First Name" name="txtName">
</div>
<div class="col-sm-6" style="padding-left: 0px;">
<input style="
padding: 20px 10px;" type="email" class="form-control required" id="txtEmail" placeholder="Email Address" name="txtEmail">
</div>
</div>


<div class="form-group">
<input style="
padding: 20px 10px;" type="text" class="form-control required" id="txtPhone" placeholder="Phone" name="txtPhone">
</div>
<div class="form-group">
<input style="
padding: 20px 10px;" type="text" class="form-control required" id="txtAddr" placeholder="Address" name="txtAddr">
</div>

<div class="form-group">
<input style="
padding: 20px 10px;" type="text" class="form-control " id="txtPwd" placeholder="Password" name="txtPwd">
</div>
<a style="float:right; min-width:100px;" href="#"  type="button" class="btn btn-primary btn-sm " id="btnRegister">
Submit 
</a>


</form>
</div>

</div>
<div>


</div>

</div>

</div>
</div>

<div id="assignModal" class="modal fade" role="dialog">
<div class="modal-dialog" >

<div class="modal-content" >
 <div style=" width:100%; border-radius:4px 4px 0 0;" class="bg-gradient-primary">
        <button class="btn text-white" disabled>Assign a course to <b id="usrnm">dd</b></button>
         <button style="float:right;" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#assignModal">
<span class="icon text-white-50">
<i class="fas fa-fw fa-times"></i>
</span>
</button>
      </div>

<div class="modal-body">
<div class="">


<form class="user" id="frmAssign" method="POST" >
<input type="hidden" name="usid" id="uid" >
<input type="hidden" name="action" 
value="assignCourse">

<?php 
$assobj=new MysqliDb(HOST,USER,PWD,DB);
$assobj->where("course_status",1);
$crsarr=$assobj->get("ace_course",null,"course_id,course_name");
?>
<div id="assbdy">
<div class="form-group row assWrp" >
<div class="col-sm-10 mb-3 mb-sm-0">
<select class="form-control selcrs" name="selcrs[]">
  <option value="">Select</option>
  <?php 
foreach ($crsarr as $key => $crs) {
 echo "<option value='$crs[course_id]'>$crs[course_name]</option>";
}
  ?>
</select>
</div>


</div>
</div>
<div class="form-group row">

<div class="col-sm-10 "></div>
<div class="col-sm-2 pull-right" style="padding-left: 40px;">
<button type="button" class="btn btn-sm btn-primary" id="btnAdd"><i class="fa fa-plus" ></i> </button>
</div>
</div>

<a style="float:right; min-width:100px;" href="#"  type="button" class="btn btn-primary btn-sm" id="btnAssign">
Assign 
</a>


</form>
</div>

</div>
<div>


</div>

</div>

</div>
</div>
<div id="delModal" class="modal fade" role="dialog">
<div class="modal-dialog modal-danger" >

<!-- Modal content-->
<div class="modal-content" style="border-radius:0;">

<div class="modal-body">
<h4 class="modal-title" style="padding-bottom: 10px;
text-align: center;font-size: 20px;">Delete Topics</h4>
<div class="">

<form class="user" id="frmdelet" method="POST" >
<input type="hidden" name="action" value="DelUser">
<input type="hidden" name="delid" value="" id="delid">


<div class="form-group">

 <span> Do you Really want to Delete this User</span>

</div>
<a style="border-radius: 0px;
padding:  10px;" href="#"  type="button" class="btn btn-danger btn-user btn-block" id="btnDel">
Delete 
</a>


</form>
</div>

</div>
<div>


</div>

</div>

</div>
</div>
<!-- Footer -->
<?php copyRight(); ?> 
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
<i class="fas fa-angle-up"></i>
</a>


<?php
logoutModel();
footBlock();
?>
<script type="text/javascript">
  $('#tblUsers').DataTable();
$('#userModal').on('show.bs.modal', function (e) {
  if($(e.relatedTarget).data('id')){
  $(e.relatedTarget).data('id')?$("#usrid").val($(e.relatedTarget).data('id')):$("#usrid").val("");
  $(e.relatedTarget).data('id')?$("#txtName").val($(e.relatedTarget).data('nm')):$("#txtName").val("");
  $(e.relatedTarget).data('id')?$("#txtPhone").val($(e.relatedTarget).data('mobile')):$("#txtPhone").val("");
  $(e.relatedTarget).data('id')?$("#txtAddr").val($(e.relatedTarget).data('addr')):$("#txtAddr").val("");
  $(e.relatedTarget).data('id')?$("#txtEmail").val($(e.relatedTarget).data('email')):$("#txtEmail").val("");
  $("#txtPwd").hide();
    
}else{
    $("#usrid,#txtName,#txtPhone,#txtAddr,#txtEmail").val("");
  $("#txtPwd").val("");
  $("#txtPwd").show();
   //console.log(random_strings(6));
    $("#txtPwd").val(random_strings(6));
}
   
});


  jQuery(document).ready(function($) {
      $(document).on('click', '.btnrefresh', function(event) {
  event.preventDefault();
 $.ajax({
   url: '<?php echo BASE?>/ajax/user-ajax.php',
   type: 'POST',
   data: {'uid': $(this).data("id"),"action":"userRefresh"},
   success:function(resp){
console.log(resp);
jsn=$.parseJSON(resp);
if(jsn.status=='done'){
  location.reload();
}
   }
 })
 .done(function() {
   console.log("success");
 });
 
});
 $('#delModal').on('show.bs.modal', function (e) {
      $("#delid").val($(e.relatedTarget).data('id'));
    });
    
    $(document).on("click",".btnActive",function(event) {  
    event.preventDefault();
     
   
   $.ajax({
     url: '<?php echo BASE?>/ajax/user-ajax.php',
     type: 'POST',
     data: {"action":'useractive',"uid":$(this).data("id"),"sts":$(this).data("sts")},
     success:function(response){
    console.log(response);
    resp=$.parseJSON(response);
    if(resp.status=="done"){
      
      setTimeout(function() {location.reload()}, 1000);
    }
   }
   })
   .done(function() {
     console.log("success");
   });
 
  });
    
    
    
$(document).on("click","#btnDel",function(event) {  
    event.preventDefault();
     
    $('#btnDel').addClass('disabled').attr("disabled","disabled");
   $.ajax({
     url: '<?php echo BASE?>/ajax/user-ajax.php',
     type: 'POST',
     data: $("#frmdelet").serialize(),
     success:function(response){
    console.log(response);
    resp=$.parseJSON(response);
    if(resp.status=="done"){
      showToast("User successfully Deleted  .");
      setTimeout(function() {location.reload()}, 1000);
    }
   }
   })
   .done(function() {
     console.log("success");
   });
 
  });
  $(document).on("click","#btnRegister",function(event) {
        event.preventDefault();
        valid=true;
        $(".has-error").removeClass("has-error");
$('#frmUsers .required').each(function () {
       if(!$(this).val()){
        valid=false;
        $(this).addClass('has-error');
       }
        });

if(valid){

   $.ajax({
     url: '<?php echo BASE?>/ajax/user-ajax.php',
     type: 'POST',
     data: $("#frmUsers").serialize(),
     success:function(response){
    console.log(response);
    resp=$.parseJSON(response);
    if(resp.status=="done"){
      showToast("Registered successful.");
      setTimeout(function() {location.reload()}, 1000);
    }
   }
   })
   .done(function() {
     console.log("success");
   });
   }
  });
  /*FLATPIKER*/
  $(".datepicker").datepicker({
format: 'dd-mm-yyyy' ,
autoclose: true,
  });
$(document).on('click', '#btnAdd', function(event) {
  event.preventDefault();
  wrpper=$("#AssWrapr").clone();
   wrpper.find(".btnDel").show();
wrpper.removeClass('hidewrp');
wrpper.removeAttr('id');
wrpper.find('.selcrs').val("");
  cnt=$(".assWrp")
 //cnt.appendTo(wrp);
 // wrpper.appendTo("#assbdy");
 console.log(wrpper);
$(wrpper).appendTo("#assbdy");
$(".datepicker").datepicker({
format: 'dd-mm-yyyy' ,
autoclose: true,
  });
 
});




  $(document).on("click","#btnAssign",function(event) {
    event.preventDefault();
    valid=true;
$("#frmAssign .has-error").removeClass("has-error");
$("#frmAssign .form-control").each(function(index,fld){console.log($(this).val());
if(!$(this).val()){
$(this).addClass('has-error');
valid=false;  
} 
});
if(valid){
   $.ajax({
     url: '<?php echo BASE?>/ajax/user-ajax.php',
     type: 'POST',
     data: $("#frmAssign").serialize()+"&delids="+delids,
     success:function(response){
    console.log(response);
    resp=$.parseJSON(response);
    if(resp.status=="done"){
      showToast("Assigned successful.");
      setTimeout(function() {location.reload()}, 1000);
    }
   }
   })
   .done(function() {
     console.log("success");
   });
   }
  });
  $('#assignModal').on('show.bs.modal', function (e) {
    //$('#assignModal').find(".appwrp").remove();
   $("#uid").val($(e.relatedTarget).data('id'));
   
   $("#usrnm").text($(e.relatedTarget).data('name'));
  cnt=$("#assbdy").html("")

   $.ajax({
       url: '<?php echo BASE?>/ajax/user-ajax.php',
     type: 'POST',
     data: {"uid":$(e.relatedTarget).data('id'),"action":"getUsrSbj"},
     success:function(response){
    console.log(response);
    resp=$.parseJSON(response);
    if(resp[0]){
      
     $.each(resp,function(index,res){
      console.log(index);
      wrpper=$("#AssWrapr").clone();
   wrpper.find(".ucid").val(res.uc_id);
wrpper.removeClass('hidewrp');
wrpper.removeAttr('id');
wrpper.find('.selcrs').val(res.csid);
  cnt=$(".assWrp")
   wrpper.find(".btnDel").hide();
  if(index>0){
   wrpper.find(".btnDel").show();

wrpper.find(".btnDel").attr("data-id",res.uc_id);

  }
$(wrpper).appendTo("#assbdy");

    });
    }
    else{
      wrpper=$("#AssWrapr").clone();
wrpper.removeClass('hidewrp');
     
$(wrpper).appendTo("#assbdy");
   wrpper.find(".btnDel").hide();
      
    }
   }
    })
    .done(function() {
      console.log("success");
    });
 });
   var delids=[];

  $(document).on('click', '.btnDel', function(event) {
    event.preventDefault();
    delid=$(this).data("id")>0?$(this).data("id"):0;
      $(this).closest(".form-group").fadeOut(function(){
    $(this).remove();
    if(delid>0){
delids.push(delid);
console.log(delids);
}
   
  });
    
  });
  });
  function random_strings(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}
</script>
<div class="form-group row hidewrp appwrp" id="AssWrapr" >
  <input type="hidden" name="ucid[]" class="ucid">

<div class="col-sm-10 mb-3 mb-sm-0">
<select class="form-control selcrs" name="selcrs[]">
  <option value="">Select</option>
  <?php 
foreach ($crsarr as $key => $crs) {
 echo "<option value='$crs[course_id]'>$crs[course_name]</option>";
}
  ?>
</select>
</div>
<div class="col-sm-2 mb-3 mb-sm-0">
  <a data-id="" href="#"  class="btn btn-danger btnDel"><i class="fa fa-trash"> </i></a>
</div>
<!-- <div class="col-sm-3" style="padding-left: 0px;">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user datepicker txtsdate" placeholder="Start Date" name="txtsdate[]">
</div>
<div class="col-sm-3" style="padding-left: 0px;">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user datepicker txtedate" placeholder="End Date" name="txtedate[]">
</div> -->

</div>