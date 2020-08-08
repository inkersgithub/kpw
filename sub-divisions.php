<?php
include 'inc/config.php';
include 'inc/MysqliDb.php';
include 'inc/session.php';
include 'inc/template.php';
include 'inc/functions.php';

headBlock("Courses");
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
<button class="btn btn-primary btn-circle btn-lg" data-toggle="modal" data-target="#corsModal">
<i class="fas fa-fw fa-plus"></i>
</button>


</div>

<!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->

<!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
</div>



<div class="card shadow mb-4" style="width: 100%;">
<div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Sub Division</h6></div>
<div class="card-body">
<?php
$usrobj=new MysqliDb(HOST,USER,PWD,DB);
$usrobj->where("course_status",9,"<>");
$crsarr=$usrobj->get("ace_course au",null,"course_id,course_name,course_description,course_sdate,course_edate,course_status,course_fee");
?>
<table class="table table-ordered" id="dataTable">
  <thead>
    <tr>
      <th style="width:20px;">Sl.</th>
      <th>Sub Division</th>
      <th>Division</th>
      <th>Code</th>    
      <th>Status</th>
  
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($crsarr as $key => $crs) {
      ?>
      <tr>
        <td><?php echo $key+1;?></td>
        <!-- <a href="<?php echo BASE?>/course-details?csid=<?php echo $crs["course_id"]?>"> -->
        <td><?php echo $crs["course_name"]?> <b><?php echo $crs['course_status']==0?"<span class='text-danger'>(Inactive)</span>":"<span class='text-success'>(Active)</span."?></b></td>
        <td><?php echo $crs["course_description"]?></td>
        <td><?php echo $crs["course_fee"]?></td>

          <?php
$clsbtn=$crs['course_status']==0?"danger":"primary";
$actxt=$crs['course_status']==0?"Activate":"Inactive";
$sts=$crs['course_status']==0?"1":"0";
          ?>
          <td><button id="btnActive" data-sts="<?php echo $sts?>" type="button" data-id="<?php echo $crs["course_id"]?>" class="btn btn-sm btn-<?php echo $clsbtn?>"><?php echo $actxt?></button></td>
          
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
<div id="corsModal" class="modal fade" role="dialog">
<div class="modal-dialog" >

<div class="modal-content" >
 <div style=" width:100%; border-radius:4px 4px 0 0;" class="bg-gradient-primary">
        <button class="btn text-white" disabled>Add a sub division</button>
         <button style="float:right;" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#corsModal">
<span class="icon text-white-50">
<i class="fas fa-fw fa-times"></i>
</span>
</button>
      </div>

<div class="modal-body">
<div class="">


<form class="user" id="frmCourses" method="POST" enctype="multipart/form-data">
<input type="hidden" name="action" value="addcourse">



<div class="form-group">
<select class="form-control" id="txtdescri" name="txtdescri">
<option selected disabled>Division</option>
</select>
</div>
<div class="form-group">

<input type="text" class="form-control" id="txtfee" placeholder="Code" name="txtfee">
</div>
<div class="form-group">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user required" id="txtfee" placeholder="Course Fee" name="txtfee">
</div>
<div class="form-group">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user " id="txtdescri" placeholder="Course description" name="txtdescri">
</div>
<div class="form-group row">
  <div class="col-sm-6" style="">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user datepicker txtsdate required" placeholder="Start Date" name="txtsdate">
</div>
<div class="col-sm-6" style="">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user datepicker txtedate required" placeholder="End Date" name="txtedate">
</div>
</div>
<div class="form-group">
  <div id="msg" style="color: #f00;"></div>
<input style="border-radius: 0px;
padding: 20px 10px;" type="file" class="txtFile" id="txtfile" placeholder="image" name="txtFile">
</div>
<button style="border-radius: 0px;
padding:  10px;"  type="submit" class="btn btn-primary btn-user btn-block" id="btnRegister">
Register 
</button>


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
  $('#dataTable').DataTable();
  $('#corsModal').on('show.bs.modal', function (e) {
        $(".has-error").removeClass("has-error");
  });
  jQuery(document).ready(function($) {
$('#delModal').on('show.bs.modal', function (e) {
$("#delid").val($(e.relatedTarget).data('id'));
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
showToast("Course successfully Deleted  .");
setTimeout(function() {location.reload()}, 1000);
}
}
})
.done(function() {
console.log("success");
});

});
 $(document).submit("#frmCourses",function(event) {
    event.preventDefault();
     valid=true;
     var postData = new FormData($("#frmCourses")[0]);
        $(".has-error").removeClass("has-error");
         $("#msg").text('');
        
        if(!$(".txtFile").val()){
        valid=false;
        $("#msg").text('upload image');
       }
$('#frmCourses .required').each(function () {
       if(!$(this).val()){
        valid=false;
        $(this).addClass('has-error');
       }
        });

if(valid){
   $.ajax({
     url: '<?php echo BASE?>/ajax/user-ajax.php',
     type: 'POST',
     data: postData,
     processData: false,
         contentType: false,
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
$(document).on('click', '#btnActive', function(event) {
  event.preventDefault();
 crsid=$(this).data("id");
 sts=$(this).data("sts");
$.ajax({
  url: '<?php echo BASE?>/ajax/user-ajax.php',
  type: 'POST',
  data: {"crsid": crsid,"sts":sts,"action":"activeCourse"},
  success:function(resp){
    jsn=$.parseJSON(resp);
    if(jsn.status=="done"){
      location.reload();
    }
  }
})
.done(function() {
  console.log("success");
})
.fail(function() {
  console.log("error");
})
.always(function() {
  console.log("complete");
});

});
  });
 
</script>
<div id="delModal" class="modal fade" role="dialog">
<div class="modal-dialog modal-danger" >

<!-- Modal content-->
<div class="modal-content" style="border-radius:0;">

<div class="modal-body">
<h4 class="modal-title" style="padding-bottom: 10px;
text-align: center;font-size: 20px;">Delete Courses</h4>
<div class="">

<form class="user" id="frmdelet" method="POST" >
<input type="hidden" name="action" value="DelCourse">
<input type="hidden" name="delid" value="" id="delid">


<div class="form-group">

<span> Do you Really want to Delete this Course</span>

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
<div class="form-group row hidewrp appwrp" id="AssWrapr" >
<div class="col-sm-6 mb-3 mb-sm-0">
<select class="form-control selcrs" name="selcrs[]">
  <option value="">Select</option>
  <?php 
foreach ($crsarr as $key => $crs) {
 echo "<option value='$crs[course_id]'>$crs[course_name]</option>";
}
  ?>
</select>
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