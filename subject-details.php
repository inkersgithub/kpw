<?php
include 'inc/config.php';
include 'inc/MysqliDb.php';
include 'inc/session.php';
include 'inc/template.php';
include 'inc/functions.php';

headBlock("Courses");
/*$toptr=strstr($_GET["subid"],".",true);
$sbcs=explode("-", $toptr);*/
//print_r($sbcs);
//$subid=$_GET["subid"];//$sbcs[0];
$csbid=$_GET["csid"];//$sbcs[1];
$crsobj=new MysqliDb(HOST,USER,PWD,DB);
$crsobj->where("cs_id",$csbid);
$crsobj->where("cs_status",0);
$cssbs=$crsobj->getOne("course_subject cs","course_id,sub_id");
$subid=$cssbs["sub_id"];
$csid=$cssbs["course_id"];
$crsobj->where("course_status",1);
$crsobj->where("course_id",$csid);
$crs=$crsobj->getOne("ace_course","course_id,course_name,course_sname,course_status,course_sdate,course_edate");

$subobj=new MysqliDb(HOST,USER,PWD,DB);
$subobj->where("sub_id",$subid);
$sub=$subobj->getOne("ace_subject sb","sb.sub_id,sb.sub_name,sb.sub_sname,sb.sub_status");

?>  
<style type="text/css">
  .select2-container{
    width: 100%!important;
  }
</style>

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
<h1 class="h3 mb-0 text-gray-800"> <?php echo $crs["course_name"]. " - ".$sub["sub_name"]?></h1>

<div> 
<button class="btn btn-primary btn-sm btn-icon-split" data-toggle="modal" data-target="#topModal">
<span class="icon text-white-50">
<i class="fas fa-fw fa-users"></i>
</span>
<span class="text">Add Topics</span>
</button>


</div>

<!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->

<!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
</div>



<div class="card shadow mb-4" style="width: 100%;">
<div class="card-header py-3">
<!-- <h6 class="m-0 font-weight-bold text-primary">Courses</h6> -->

<h6 class="m-0 font-weight-bold text-primary">Topics</h6>
</div>
<div class="card-body">
  

<div>
  <table class="table table-ordered">
    <thead>
     <tr>
       <th>Si no.</th>
       <th>Topic</th>
       <th>Decription</th>
       <th>Materials</th>
       <th>Action</th>
     </tr>
    </thead>
    <tbody>
<?php
$topobj=new MysqliDb(HOST,USER,PWD,DB);
$topobj->where("sub_id",$subid);
$topobj->where("cs_id",$csbid);
$toarr=$topobj->get("ace_topic",null,"top_id,top_title,top_description,top_img");
 foreach ($toarr as $key => $top) {
  $title=strlen($top["top_title"])>30?substr($top["top_title"],0,40)."..":$top["top_title"];
  $descr=strlen($top["top_description"])>30?substr($top["top_description"],0,40)."..":$top["top_description"];
 ?>
  <tr>
       <td><?php echo $key+1;?></td> 
       <td><?php echo $title;?></td> 
       <td><?php echo $descr;?></td> 
       <td><button class="btn btn-primary" data-target="#matModal"  data-toggle="modal" data-id="<?php echo $top["top_id"]?>" data-title="<?php echo $top["top_title"]?>">Videos</button>&nbsp;
<button class="btn btn-primary" data-target="#notModal"  data-toggle="modal" data-id="<?php echo $top["top_id"]?>" data-title="<?php echo $top["top_title"]?>">Notes</button>
       </td>
       <td><a href="#" data-toggle="modal" data-target="#topModal" data-id="<?php echo $top["top_id"]?>" data-title="<?php echo $top["top_title"]?>" data-desc="<?php echo $top["top_description"]?>" data-img="<?php echo $top["top_img"]?>"><i  class="fa fa-edit"></i></a>&nbsp;<a  class="text-danger" href="#" data-toggle="modal" data-target="#delModal" data-id="<?php echo $top["top_id"]?>"><i class="fa fa-trash"></i></a></td>
      </tr>
 <?php
      } ?>
     
    </tbody>
  </table>
</div>
</div>
</div>
</div>
<!-- Modal -->






<!-- /.container-fluid -->
<!-------------------------------------------------------------------------------------------------------------------------------------->

</div>
<!-- End of Main Content -->


<!-- Modal -->
<div id="topModal" class="modal fade" role="dialog">
<div class="modal-dialog" >

<!-- Modal content-->
<div class="modal-content" style="border-radius:0;">

<div class="modal-body">
<h4 class="modal-title" style="padding-bottom: 10px;
text-align: center;font-size: 20px;">Add/Edit Topics</h4>
<div class="">


<form class="user" id="frmTopics" method="POST" method="POST" enctype="multipart/form-data">
<input type="hidden" name="action" value="addTopic">
<input type="hidden" name="courseid" value="<?php echo $csid?>">
<input type="hidden" name="csid" value="<?php echo $csbid?>">
<input type="hidden" name="subid" value="<?php echo $subid?>">
<input type="hidden" name="topid" value="" id="top-id">



<div class="form-group">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user required" id="txtTitle" placeholder="Topic Title" name="txtTitle">
</div>
<div class="form-group">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user required" id="txtdescr" placeholder="Topic Decription" name="txtdescr">
</div>
<div class="form-group">
<div id="msg" style="color: #f00;"></div>
<div id="imgwrp"></div>
<input style="border-radius: 0px;
padding: 20px 10px;" type="file" class="txtFile" id="txtfile" placeholder="image" name="txtFile">
</div>

<a style="border-radius: 0px;
padding:  10px;" href="#"  type="button" class="btn btn-primary btn-user btn-block" id="btnAdd">
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


<div id="delModal" class="modal fade" role="dialog">
<div class="modal-dialog modal-danger" >

<!-- Modal content-->
<div class="modal-content" style="border-radius:0;">

<div class="modal-body">
<h4 class="modal-title" style="padding-bottom: 10px;
text-align: center;font-size: 20px;">Delete Topics</h4>
<div class="">

<form class="user" id="frmdelet" method="POST" >
<input type="hidden" name="action" value="DelTopics">
<input type="hidden" name="delid" value="" id="delid">


<div class="form-group">

 <span> Do you Really want to Delete this Topic</span>

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

<div id="matModal" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg" >

<!-- Modal content-->
<div class="modal-content" style="border-radius:0;">

<div class="modal-body">
<h4 class="modal-title" style="padding-bottom: 10px;
text-align: center;font-size: 20px;">Add Materials</h4>
<div class="">


<form class="user" id="frmmaterials" method="POST" >
<input type="hidden" name="topid" id="topid" value="">
<input type="hidden" name="action" 
value="AddMaterials">
<div class="form-group row">
  <div class="col-sm-12"><h4><span id="topnm">dd</span></h4></div>
  
</div>

<div id="topbdy">
<div class="form-group row topWrp" >
<div class="col-sm-2 mb-3 mb-sm-0">
<select class="form-control seltyp" name="seltyp[]">
  <option value="1">Videos</option>
  <!-- <option value="0">Notes</option> -->
  <option value="2">Exam</option>
 
</select>
</div>
<div class="col-sm-4 mb-3 mb-sm-0">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user txthead" placeholder="Title" name="txthead[]">
</div>
<div class="col-sm-5 mb-3 mb-sm-0">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user txturl" placeholder="Url" name="txturl[]">
</div>
<div class="col-sm-1 mb-3 mb-sm-0"></div>


</div>
</div>
<div class="form-group row">

<div class="col-sm-10 "></div>
<div class="col-sm-2 pull-right" style="padding-left: 40px;">
<button type="button" class="btn btn-sm btn-primary" id="btnPlus"><i class="fa fa-plus" ></i> </button>
</div>
</div>

<a style="border-radius: 0px;
padding:  10px;" href="#"  type="button" class="btn btn-primary btn-user btn-block" id="btnMaterls">
Add materials 
</a>


</form>
</div>

</div>
<div>


</div>

</div>

</div>
</div>
<div id="notModal" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg" >

<!-- Modal content-->
<div class="modal-content" style="border-radius:0;">

<div class="modal-body">
<h4 class="modal-title" style="padding-bottom: 10px;
text-align: center;font-size: 20px;">Add Materials</h4>
<div class="">


<form class="user" id="frmNotes" method="POST" enctype="multipart/form-data">
<input type="hidden" name="topid" id="topcid" value="">
<input type="hidden" name="delids[]" id="delids" value="">
<input type="hidden" name="action" 
value="AddNotes">
<div class="form-group row">
  <div class="col-sm-12"><h4><span id="topnme"></span></h4></div>
  
</div>

<div id="notbdy">
<div class="form-group row topWrp" >

<div class="col-sm-5 mb-3 mb-sm-0">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user txthead" placeholder="Title" name="txthead[]">
</div>
<div class="col-sm-1 mb-3 mb-sm-0"></div>
<div class="col-sm-5 mb-3 mb-sm-0">
<input type="file" name="flenots[]" class="flenots">
</div>
<div class="col-sm-1 mb-3 mb-sm-0"></div>


</div>
</div>
<div class="form-group row">

<div class="col-sm-10 "></div>
<div class="col-sm-2 pull-right" style="padding-left: 40px;">
<button type="button" class="btn btn-sm btn-primary" id="notPlus"><i class="fa fa-plus" ></i> </button>
</div>
</div>

<a style="border-radius: 0px;
padding:  10px;" href="#"  type="button" class="btn btn-primary btn-user btn-block" id="btnNotes">
Add Notes
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
      showToast("Topic successfully Deleted  .");
      setTimeout(function() {location.reload()}, 1000);
    }
   }
   })
   .done(function() {
     console.log("success");
   });
 
  });


  $('#topModal').on('show.bs.modal', function (e) {
        $(".has-error").removeClass("has-error");
$("#imgwrp").html("");
$(e.relatedTarget).data('id')?$("#top-id").val($(e.relatedTarget).data('id')):$("#top-id").val("");
    $("#txtTitle").val($(e.relatedTarget).data('title'));
    $(e.relatedTarget).data('id')?$("#txtdescr").val($(e.relatedTarget).data('desc')):$("#txtdescr").val("");
    if($(e.relatedTarget).data('img')){
mgs="<?php echo BASE.'/assets/top-img/'?>"+$(e.relatedTarget).data('img'); 
$("#imgwrp").html("<img src='"+mgs+"' height='90' width='90'>")
}
  });
  $('#matModal').on('show.bs.modal', function (e) {
    $("#topnm").text($(e.relatedTarget).data('title'))
    $("#topid").val($(e.relatedTarget).data('id'));
  cnt=$("#topbdy").html("")
      $.ajax({
       url: '<?php echo BASE?>/ajax/user-ajax.php',
     type: 'POST',
     data: {"topid":$(e.relatedTarget).data('id'),"action":"getMaterials"},
     success:function(response){
    console.log(response);
    resp=$.parseJSON(response);
    if(resp[0]){
      
     $.each(resp,function(index,res){
      console.log(res);
      wrpper=$("#topWrapr").clone();
wrpper.removeClass('hidewrp');
wrpper.removeAttr('id');
wrpper.find(".matid").val(res.am_id);
wrpper.find(".seltyp").val(res.am_type);
wrpper.find(".txthead").val(res.am_title);
wrpper.find(".txturl").val(res.am_url);
wrpper.find(".btnDel").attr("data-id",res.am_id);
$(wrpper).appendTo("#topbdy");
    });
    }
    else{
      wrpper=$("#topWrapr").clone();
wrpper.removeClass('hidewrp');
      $(wrpper).appendTo("#topbdy");
wrpper.find(".btnDel").hide();
      
    }
   }
    })
    .done(function() {
      console.log("success");
    });
    
});
  $(document).on('click',"#btnPlus", function(event) {
     event.preventDefault();
     wrpper=$("#topWrapr").clone();
wrpper.find(".btnDel").show();
wrpper.find(".txthead").val("");
wrpper.find(".txturl").val("");
wrpper.find(".seltyp").val("1");
wrpper.removeClass('hidewrp');
wrpper.removeAttr('id');

  cnt=$(".topWrp")
 console.log(wrpper);
$(wrpper).appendTo("#topbdy");
   });

  $(document).on('click',"#notPlus", function(event) {
     event.preventDefault();
wrpper=$("#notWrapr").clone();
wrpper.find(".btnDel").show();
wrpper.find(".txthead").val("");

wrpper.removeClass('hidewrp');
wrpper.removeAttr('id');

cnt=$(".notWrp")
 
$(wrpper).appendTo("#notbdy");
   });
  
  $(document).on("click","#btnAdd",function(event) {  
    event.preventDefault();
     valid=true;
        $(".has-error").removeClass("has-error");
     var postData = new FormData($("#frmTopics")[0]);
     $("#msg").text('');
        
        /*if(!$(".txtFile").val()){
        valid=false;
        $("#msg").text('upload image');
       }*/
$('#frmTopics .required').each(function () {
       if(!$(this).val()){
        valid=false;
        $(this).addClass('has-error');
       }
        });

if(valid){
    $('#btnAdd').addClass('disabled').attr("disabled","disabled");
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
      showToast("Topic added successfully .");
      setTimeout(function() {location.reload()}, 1000);
    }
   }
   })
   .done(function() {
     console.log("success");
   });
 }
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

$(document).on("click","#btnMaterls",function(event) {  
    event.preventDefault();
    $('#btnMaterls').addClass('disabled').attr("disabled","disabled");
   $.ajax({
     url: '<?php echo BASE?>/ajax/user-ajax.php',
     type: 'POST',
     data: $("#frmmaterials").serialize()+"&delids="+delids,
     success:function(response){
    console.log(response);
    resp=$.parseJSON(response);
    if(resp.status=="done"){
      showToast("Materials added successfully .");
      setTimeout(function() {location.reload()}, 1000);
    }
   }
   })
   .done(function() {
     console.log("success");
   });
  });  

$(document).on("click","#btnNotes",function(event) {  
    event.preventDefault();
    $('#btnNotes').addClass('disabled').attr("disabled","disabled");
     $("#delids").val(delids);
    var postData = new FormData($("#frmNotes")[0]);

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
      showToast("Materials added successfully .");
      setTimeout(function() {location.reload()}, 1000);
    }
   }
   })
   .done(function() {
     console.log("success");
   });
  });  
$('#notModal').on('show.bs.modal', function (e) {
    $("#topnme").text($(e.relatedTarget).data('title'))
    $("#topcid").val($(e.relatedTarget).data('id'));
  cnt=$("#notbdy").html("")
      $.ajax({
       url: '<?php echo BASE?>/ajax/user-ajax.php',
     type: 'POST',
     data: {"topid":$(e.relatedTarget).data('id'),"action":"getNotes"},
     success:function(response){
    console.log(response);
    resp=$.parseJSON(response);
    if(resp[0]){
      
     $.each(resp,function(index,res){
      console.log(res);
      wrpper=$("#notWrapr").clone();
wrpper.removeClass('hidewrp');
wrpper.removeAttr('id');
wrpper.find(".matids").val(res.am_id);
wrpper.find(".txthead").val(res.am_title);
wrpper.find(".flpdf").val(res.am_url);
wrpper.find("#pdfwrp").html("<img width='30' height='30' src='<?php echo BASE?>/img/pdf.png'>");
wrpper.find(".btnDel").attr("data-id",res.am_id);
$(wrpper).appendTo("#notbdy");
    });
    }
    else{
wrpper=$("#notWrapr").clone();
wrpper.removeClass('hidewrp');
      $(wrpper).appendTo("#notbdy");
wrpper.find(".btnDel").hide();
      
    }
   }
    })
    .done(function() {
      console.log("success");
    });
    
});
 });
</script>
<div class="form-group row hidewrp appwrp" id="topWrapr" >
  <input type="hidden" name="matid[]" class="matid">
<div class="col-sm-2 mb-3 mb-sm-0">
<select class="form-control seltyp" name="seltyp[]">
  <option value="1">Videos</option>
 <!--  <option value="0">Notes</option> -->
  <option value="2">Exam</option>
 
</select>
</div>
<div class="col-sm-4 mb-3 mb-sm-0">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user txthead" placeholder="Title" name="txthead[]">
</div>
<div class="col-sm-5 mb-3 mb-sm-0">
  <div class ="filfld">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user txturl" placeholder="Url" name="txturl[]"></div>
</div>
<div class="col-sm-1 mb-3 mb-sm-0">
  <a data-id="" href="#"  class="text-danger btnDel"><i class="fa fa-trash"> </i></a>
</div>


</div>

<div class="form-group row notWrp hidewrp" id="notWrapr">
  <input type="hidden" name="matid[]" class="matids">

<div class="col-sm-5 mb-3 mb-sm-0">
<input style="border-radius: 0px;
padding: 20px 10px;" type="text" class="form-control form-control-user txthead" placeholder="Title" name="txthead[]">
</div>
<input type="hidden" name="flpdf[]" class="flpdf">
<div class="col-sm-1 mb-3 mb-sm-0"><div id="pdfwrp"></div></div>
<div class="col-sm-5 mb-3 mb-sm-0">
  
<input type="file" name="flenots[]" class="flenots">
</div>
<div class="col-sm-1 mb-3 mb-sm-0">
  <a data-id="" href="#"  class="text-danger btnDel"><i class="fa fa-trash"> </i></a>
</div>


</div>

</div>