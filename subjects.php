<?php
include 'inc/config.php';
include 'inc/MysqliDb.php';
include 'inc/session.php';
include 'inc/template.php';
include 'inc/functions.php';

headBlock("Subjects");
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
<div class="d-sm-flex align-items-center justify-content-between mb-4">
<h1 class="h4 mb-0 text-gray-800">Subjects</h1>

<div> 
<button class="btn btn-primary btn-sm btn-icon-split" data-toggle="modal" data-target="#SubjModal">
<span class="icon text-white-50">
<i class="fas fa-fw fa-plus"></i>
</span>
<span class="text">ADD  SUBJECT</span>
</button>


</div>

<!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->

<!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
</div>



<div class="card shadow mb-4" style="width: 100%;">
<div class="card-body">
<?php
$subobj=new MysqliDb(HOST,USER,PWD,DB);
$subobj->groupBy("sb.sub_id");
$subobj->join("course_subject cs","cs.sub_id=sb.sub_id AND cs.cs_status=0","LEFT");
$subarr=$subobj->get("ace_subject sb",null,"sb.sub_id,sb.sub_name,sb.sub_sname,sb.sub_status,cs.cs_id,sb.sub_img");
//echo $subobj->getLastQuery();
if($subobj->count>0){
?>
<table class="table table-ordered" id="dataTable">
<thead>
<tr>
<th>Si no.</th>
<th>Subject</th>
<th>Code</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php

foreach ($subarr as $key => $sub) {
?>
<tr>
<td><?php echo $key+1;?></td>
<td><?php echo $sub["sub_name"]?></td>
<td><?php echo $sub["sub_sname"]?></td>


<td><a href="#" data-toggle="modal" data-target="#SubjModal" data-id='<?php echo $sub["sub_id"]?>' data-sub='<?php echo $sub["sub_name"]?>' data-code='<?php echo $sub["sub_sname"]?>' data-img='<?php echo $sub["sub_img"]?>'><i class="fa fa-edit"></i>
<?php 
$mdl=$sub["cs_id"]>0?"":"data-toggle='modal' data-target='#delModal'";
$cls=$sub["cs_id"]>0?"style='cursor:not-allowed'":"";
?>
</a>&nbsp;<a href="#" <?php echo $mdl ."". $cls;?> class="text-danger" data-id=<?php echo $sub["sub_id"]?>><i class="fa fa-trash"></i></a> </td>
</tr>
<?php

}

?>

</tbody>
</table>
<?php  }else{
echo "<tr><span class='text-danger'>No Subjects Found </span></tr>";
}?>
</div></div>
<!-- Modal -->






<!-- /.container-fluid -->
<!-------------------------------------------------------------------------------------------------------------------------------------->

</div>
<!-- End of Main Content -->

<div id="delModal" class="modal fade" role="dialog">
<div class="modal-dialog modal-danger" >

<!-- Modal content-->
<div class="modal-content" >
 <div style=" width:100%; border-radius:4px 4px 0 0;" class="bg-gradient-primary">
        <button class="btn text-white" disabledDelete Subjects</button>
         <button style="float:right;" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#delModal">
<span class="icon text-white-50">
<i class="fas fa-fw fa-times"></i>
</span>
</button>
      </div>

<div class="modal-body">
<div class="">

<form class="user" id="frmdelet" method="POST" >
<input type="hidden" name="action" value="DelSubjects">
<input type="hidden" name="delid" value="" id="delid">


<div class="form-group">

<span> Do you Really want to Delete this Subject</span>

</div>
<a style="padding:  10px; float:right; min-width:100px;" href="#"  type="button" class="btn btn-danger btn-sm" id="btnDel">
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
<!-- Modal -->
<div id="SubjModal" class="modal fade" role="dialog">
<div class="modal-dialog">

<!-- Modal content-->
<div class="modal-content" >
 <div style=" width:100%; border-radius:4px 4px 0 0;" class="bg-gradient-primary">
        <button class="btn text-white" disabled>Add a subject</button>
         <button style="float:right;" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#SubjModal">
<span class="icon text-white-50">
<i class="fas fa-fw fa-times"></i>
</span>
</button>
      </div>

<div class="modal-body">



<form class="user" id="frmSubj" method="POST" enctype="multipart/form-data">
<input type="hidden" name="action" value="addSubject">
<input type="hidden" name="subid" id="subid" value="">


<div class="form-group">
<input style="padding: 20px 10px;" type="text" class="form-control required" id="txtSubj" placeholder="Subject name" name="txtSubj">
</div>
<div class="form-group">
<input style="padding: 20px 10px;" type="text" class="form-control required" id="txtsubcode" placeholder="Subject Code" name="txtsubcode">
</div>
<div class="form-group">
<div id="msg" style="color: #f00;"></div>
<div id="imgwrp"></div>
<input style="padding: 20px 10px;" type="file" class="txtFile" id="txtfile" placeholder="image" name="txtFile">
</div>
<a style="float:right; min-width:100px;" href="#"  type="button" class="btn btn-primary btn-sm" id="btnRegister">
Save 
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
  $('#dataTable').DataTable({
"paging": true,
"lengthChange": false,
"searching": true,
"ordering": true,
"info": true,
"autoWidth": false,
"pageLength":25
});
$('#SubjModal').on('show.bs.modal', function (e) {
$(e.relatedTarget).data('id')?$("#subid").val($(e.relatedTarget).data('id')):$("#subid").val('');
$(e.relatedTarget).data('sub')?$("#txtSubj").val($(e.relatedTarget).data('sub')):$("#txtSubj").val('');
$(e.relatedTarget).data('code')?$("#txtsubcode").val($(e.relatedTarget).data('code')):$("#txtsubcode").val('');
if($(e.relatedTarget).data('img')){
mgs="<?php echo BASE.'/assets/sb-img/'?>"+$(e.relatedTarget).data('img');	
$("#imgwrp").html("<img src='"+mgs+"' height='90' width='90'>")
}
})
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
showToast("Subject successfully Deleted  .");
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
var postData = new FormData($("#frmSubj")[0]);
$(".has-error").removeClass("has-error");
$("#msg").text('');
        
        if(!$(".txtFile").val()){
        valid=false;
        $("#msg").text('upload image');
       }
$('#frmSubj .required').each(function () {
if(!$(this).val()){
valid=false;
$(this).addClass('has-error');
}
});

if(valid){
$('#btnRegister').addClass('disabled').attr("disabled","disabled");
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
showToast("Subject added successfully .");
setTimeout(function() {location.reload()}, 1000);
}
}
})
.done(function() {
console.log("success");
});
}
});  
});

</script>
