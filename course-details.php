<?php
include 'inc/config.php';
include 'inc/MysqliDb.php';
include 'inc/session.php';
include 'inc/template.php';
include 'inc/functions.php';

headBlock("Courses");
$csid=$_GET["csid"];//strstr($_GET["csid"],".",true);
//print_r($csid);
$crsobj=new MysqliDb(HOST,USER,PWD,DB);
$crsobj->where("course_status",9,"<>");
$crsobj->where("course_id",$csid);
$crs=$crsobj->getOne("ace_course","course_id,course_name,course_sname,course_status,course_sdate,course_edate,course_fee,course_live,live_link,course_img,course_description");

$subobj=new MysqliDb(HOST,USER,PWD,DB);
$subobj->join("course_subject cs","cs.sub_id=sb.sub_id AND cs.course_id=$csid AND cs.cs_status=0","INNER");
$subAss=$subobj->get("ace_subject sb",null,"sb.sub_id,sb.sub_name,sb.sub_sname,sb.sub_status,cs.cs_id");

?>  
<style type="text/css">
  .select2-container{
    width: 100%!important;
  }
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
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
<h1 class="h3 mb-0 text-gray-800">Course Details - <?php echo $crs["course_name"]?></h1>

<div> 
<button class="btn btn-primary btn-sm btn-icon-split" data-toggle="modal" data-target="#CoursModal">
<span class="icon text-white-50">
<i class="fas fa-fw fa-users"></i>
</span>
<span class="text">Assign Subject</span>
</button>


</div>

<!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->

<!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
</div>



<div class="card shadow mb-4" style="width: 100%;">
<div class="card-header py-3">
<!-- <h6 class="m-0 font-weight-bold text-primary">Courses</h6> -->


</div>
<div class="card-body">
  <div class="col-sm-12 row">
  

<div class="col-sm-5">
<h6 class="m-0 font-weight-bold text-primary">Courses</h6>
<form class="user" id="frmCours" method="POST" style="margin-top: 10px;" enctype="multipart/form-data">
  <input type="hidden" name="action" value="addcourse">
  <input type="hidden" name="csid" id="csid" value="<?php echo $csid?>">


<div class="form-group">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user required" id="txtcrs" placeholder="course name" name="txtcrs" value="<?php echo $crs['course_name']?>">
</div>
<div class="form-group">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user required" id="txtcode" placeholder="course Code" name="txtcode" value="<?php echo $crs['course_sname']?>">
</div>
<div class="form-group">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user required" id="txtfee" placeholder="Course Fee" name="txtfee" value="<?php echo $crs['course_fee']?>">
</div>
<div class="form-group">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user " id="txtdescri" placeholder="Course description" name="txtdescri" value="<?php echo $crs['course_description']?>">
</div>
<div class="form-group row">
  <div class="col-sm-6" style="">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user datepicker txtsdate required" placeholder="Start Date " name="txtsdate" value="<?php echo date("d-m-Y",strtotime($crs['course_sdate']))?>">
</div>
<div class="col-sm-6" style="">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user datepicker txtedate required" placeholder="End Date" name="txtedate" value="<?php echo date("d-m-Y",strtotime($crs['course_edate']))?>">
</div>
</div>
  <?php 
$chk=$crs['course_live']==1?"checked='checked'":"";
$chkval=$crs['course_live']==1?"1":"0";
$hidden=$crs['course_live']==1?"":"hidewrp";
   ?>
<div class="form-group ">

Live

<input <?php echo $chk?> type="checkbox" name="chklive" class="chklive " value="">

</div>
<div class="form-group <?php echo $hidden?>" id="linkwrp">
  <input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user " id="txtlink" placeholder="Live Url" name="txtlink" value="<?php echo $crs['live_link']?>">
</div>
<div class="form-group">
  <div id="msg" style="color: #f00;"></div>
    <img width="90" height="90" src="<?php echo BASE."/assets/cs-img/".$crs['course_img']?>"  >&nbsp;
<input style="border-radius: 0px;
padding: 20px 10px;" type="file" class="txtFile" id="txtfile" placeholder="image" value="" name="txtFile">
</div>
<a style="border-radius: 0px;
padding:  10px;" href="#" type="button" class="btn btn-primary btn-user btn-block" id="btnRegister">
Save 
</a>


</form>
</div>
<div class="col-sm-1">
</div>
<div class="col-sm-6">
<h6 class="m-0 font-weight-bold text-primary">Subjects</h6>
<div>
  <table class="table table-ordered" id="subTable">
    <thead>
     <tr>
       <th>Si no.</th>
       <th>Subject</th>
       <th>Code</th>
     </tr>
    </thead>
    <tbody>
      <?php foreach ($subAss as $key => $sbs) {
 ?>
  <tr>
       <td><?php echo $key+1;?></td> 
       <td><a href="<?php echo BASE?>/subject-details?csid=<?php echo $sbs["cs_id"]?>"><?php echo $sbs["sub_name"]?></a></td> 
       <td><?php echo $sbs["sub_sname"]?></td> 
      </tr>
 <?php
      } ?>
     
    </tbody>
  </table>
</div>
</div>
</div>
</div></div>
<!-- Modal -->






<!-- /.container-fluid -->
<!-------------------------------------------------------------------------------------------------------------------------------------->

</div>
<!-- End of Main Content -->


<!-- Modal -->
<div id="CoursModal" class="modal fade" role="dialog">
<div class="modal-dialog" >

<!-- Modal content-->
<div class="modal-content" style="border-radius:0;">

<div class="modal-body">
<h4 class="modal-title" style="padding-bottom: 10px;
text-align: center;font-size: 20px;">Assign Subjects</h4>
<div class="">


<form class="user" id="frmSubj" method="POST" >
  <input type="hidden" name="action" value="subAssgn">
  <input type="hidden" name="csid" id="csid" value="<?php echo $csid?>">
<?php
$subobj->join("course_subject cs","cs.sub_id=sb.sub_id AND cs.course_id=$csid AND cs.cs_status=0","LEFT");
$subarr=$subobj->get("ace_subject sb",null,"sb.sub_id,sb.sub_name,sb.sub_sname,sb.sub_status,cs.cs_id");
//echo $subobj->getLastQuery();
?>

<div class="form-group col-sm-12 col-md-12">
<select class="js-example-basic-multiple " name="subj[]" multiple="multiple">
  <option value="">select</option>
 <?php 
foreach ($subarr as $key => $sub) {
  $csbid= $sub["cs_id"]?$sub["cs_id"]:NULL;
  $selct= $sub["cs_id"]?"selected='selected'":"";
echo "<option $selct value='$sub[sub_id]|$csbid'>$sub[sub_sname]</option>";
}
 ?>
  
</select>
</div>



<a style="border-radius: 0px;
padding:  10px;" href="#"  type="button" class="btn btn-primary btn-user btn-block" id="btnAssgn">
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
  $('#subTable').DataTable({
"paging": true,
"lengthChange": false,
"searching": true,
"ordering": true,
"info": true,
"autoWidth": false
});
  $('#CoursModal').on('show.bs.modal', function (e) {
})
  jQuery(document).ready(function($) {
 $(".datepicker").datepicker({
format: 'dd-mm-yyyy' ,
autoclose: true,
  });
  $('.js-example-basic-multiple').select2();
  $(document).on("click","#btnRegister",function(event) {
    event.preventDefault();
    var postData = new FormData($("#frmCours")[0]);
     valid=true;
        $(".has-error").removeClass("has-error");
$('#frmCours .required').each(function () {
       if(!$(this).val()){
        valid=false;
        $(this).addClass('has-error');
       }
        });

if(valid){
    //$('#btnRegister').addClass('disabled').attr("disabled","disabled");
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
      showToast("course added successfully .");
      setTimeout(function() {location.reload()}, 1000);
    }
   }
   })
   .done(function() {
     console.log("success");
   });
   }
  });  
  $(document).on('click', '#btnAssgn', function(event) {
    event.preventDefault();
   $.ajax({
     url: '<?php echo BASE?>/ajax/user-ajax.php',
     type: 'POST',
     data: $("#frmSubj").serialize(),
     success:function(response){
     console.log(response);
     jsn=$.parseJSON(response);
     if(jsn.status=="done"){
      location.reload();
     }
     }
   })
   .done(function() {
     console.log("success");
   });
   
  });

   $(document).on("click",".chklive ",function(){ 
    sts=$(this).is(":checked"); 
    if(sts){
    $("#linkwrp").removeClass('hidewrp');
    $("#txtlink").addClass('required').val("");
    $(this).val("1");
     }
     else{
    $(this).val("0");
    $("#linkwrp").addClass('hidewrp');
    $("#txtlink").removeClass('required');
     }
  });

  });
 
</script>
