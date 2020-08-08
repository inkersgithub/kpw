<?php
include 'inc/config.php';
include 'inc/MysqliDb.php';
include 'inc/session.php';
include 'inc/template.php';
include 'inc/functions.php';

headBlock("Notifications");
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

<div style="position:fixed; bottom:30px; right:30px; z-index:99999"> 
<button class="btn btn-primary btn-circle btn-lg" data-toggle="modal" data-target="#CoursModal">
<i class="fas fa-fw fa-share"></i>
</button>


</div>

<!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->

<!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
</div>

<?php 
 
?>

<div class="card shadow mb-4" style="width: 100%;">
<div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Notifications</h6></div>
<div class="card-body">
<?php
$crsobj=new MysqliDb(HOST,USER,PWD,DB);
$crsobj->orderBy("an.not_id","DESC");
$crsobj->join("ace_course cs","cs.course_id=an.course_id AND course_status=1","LEFT");
$courarr=$crsobj->get("ace_notification an",null,"an.not_id,an.not_title,an.not_content,an.not_type,an.course_id,an.not_date,cs.course_name");
if($crsobj->count>0){
?>
<table class="table table-ordered" id="dataTable">
<thead>
<tr>
<th style="width:10px;"></th>
<th style="width:200px;">Title</th>
<th>Content</th>
<th style="width:100px;">Sub Division</th>
<th style="width:100px;">Date</th>
</tr>
</thead>
<tbody>
<?php

foreach ($courarr as $key => $crs) {
?>
<tr>
<td><?php echo $key+1;?></td>
<td><?php echo $crs["not_title"]?></td>
<td><?php echo $crs["not_content"]?></td>
<td><?php echo $crs['not_type']==1?"Global":$crs["course_name"]?></td>
<td><?php echo date("d-m-Y  ",strtotime($crs["not_date"]))?></td>


</tr>
<?php

}

?>

</tbody>
</table>
<?php  }else{
echo "<tr><span class='text-danger'>No Notifications Found </span></tr>";
}?>
</div></div>
<!-- Modal -->






<!-- /.container-fluid -->
<!-------------------------------------------------------------------------------------------------------------------------------------->

</div>
<!-- End of Main Content -->


<!-- Modal -->
<div id="CoursModal" class="modal fade" role="dialog">
<div class="modal-dialog" >

<div class="modal-content" >
 <div style=" width:100%; border-radius:4px 4px 0 0;" class="bg-gradient-primary">
        <button class="btn text-white" disabled>Send new notification</button>
         <button style="float:right;" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#assignModal">
<span class="icon text-white-50">
<i class="fas fa-fw fa-times"></i>
</span>
</button>
      </div>

<div class="modal-body">
<div class="">


<form class="user" id="frmCours" method="POST" >
<input type="hidden" name="action" value="addNoty">


<div class="form-group">
<input type="text" class="form-control required" id="txthead" placeholder="Notification Title" name="txthead">
</div>
<div class="form-group">
<input type="text" class="form-control required" id="txtcont" placeholder="Notification Content" name="txtcont">
</div>
<?php 
$assobj=new MysqliDb(HOST,USER,PWD,DB);
$assobj->where("course_status",1);
$crsarr=$assobj->get("ace_course",null,"course_id,course_name");
?>
<div class="form-group hidewrp" id="crswrp">
<select class="form-control selcrs " name="selcrs">
<option value="">Select</option>
<?php 
foreach ($crsarr as $key => $crs) {
echo "<option value='$crs[course_id]'>$crs[course_name]</option>";
}
?>
</select>
</div>
<div class="form-group" style="margin-left:3px; float:left; min-width:100px;">
Global &nbsp;&nbsp;&nbsp;<input checked="checked" type="checkbox" class="" id="txtchck" name="chknot" placeholder="Notification Content" value="1">
</div>
<a style="float:right; min-width:100px;" href="#"  type="button" class="btn btn-primary btn-sm" id="btnRegister">
Send 
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
  $('#dataTable').DataTable();
$('#CoursModal').on('show.bs.modal', function (e) {
$(e.relatedTarget).data('id')?$("#csid").val($(e.relatedTarget).data('id')):$("#csid").val('');
$(e.relatedTarget).data('crs')?$("#txtcrs").val($(e.relatedTarget).data('crs')):$("#txtcrs").val('');
$(e.relatedTarget).data('code')?$("#txtcode").val($(e.relatedTarget).data('code')):$("#txtcode").val('');

})
jQuery(document).ready(function($) {

$(document).on("click","#txtchck ",function(){ 
    sts=$(this).is(":checked"); 
    if(sts){
       $(this).val("1");
    $("#crswrp").addClass('hidewrp');
    $(".selcrs").removeClass('required').val("");
     }
     else{
     $("#crswrp").removeClass('hidewrp');
    $(".selcrs").addClass('required');
    $(this).val("0");
     }
  });

$(document).on("click","#btnRegister",function(event) {
event.preventDefault();
//$('#btnRegister').addClass('disabled').attr("disabled","disabled");
 valid=true;
        $(".has-error").removeClass("has-error");
$('#frmCours .required').each(function () {
       if(!$(this).val()){
        valid=false;
        $(this).addClass('has-error');
       }
        });

if(valid){
$.ajax({
url: '<?php echo BASE?>/ajax/user-ajax.php',
type: 'POST',
data: $("#frmCours").serialize(),
success:function(response){
console.log(response);
resp=$.parseJSON(response);

showToast("Notificatins Send  .");
setTimeout(function() {location.reload()}, 1000);

}
})
.done(function() {
console.log("success");
});
}
});  


});



</script>


