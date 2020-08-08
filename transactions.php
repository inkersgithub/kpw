<?php
include 'inc/config.php';
include 'inc/MysqliDb.php';
include 'inc/session.php';
include 'inc/template.php';
include 'inc/functions.php';

headBlock("Transactions");
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
<div> 



</div>

<!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->

<!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
</div>



<div class="card shadow mb-4" style="width: 100%;">
<div class="card-header py-3">
<h6 class="m-0 font-weight-bold text-primary">Transactions</h6>


</div>
<div class="card-body">
<?php
$usrobj=new MysqliDb(HOST,USER,PWD,DB);
$usrobj->orderBy("at.at_id","DESC");
$usrobj->join("ace_user au","au.u_id=at.u_id");
$usrobj->join("ace_course cs","cs.course_id=at.course_id");
$crsarr=$usrobj->get("ace_transactions at",null,"cs.course_name,au.u_fname,au.u_mobile,au.u_email,at.at_date,at.at_status");
?>
<table class="table table-ordered" id="dataTable">
  <thead>
    <tr>
      <th>Si no.</th>
      <th>Name</th>
      <th>Email</th>
      <th>Mobile</th>    
      <th>Course</th>
      <th>Date</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($crsarr as $key => $crs) {
      ?>
      <tr>
        <td><?php echo $key+1;?></td>
        <td><?php echo $crs["u_fname"]?></td>
        <td><?php echo $crs["u_email"]?></td>
        <td><?php echo $crs["u_mobile"]?></td>
        <td><?php echo $crs["course_name"]?></td>
        <td><?php echo date("d-m-Y h:i",strtotime($crs["at_date"]))?></td>

        
          <?php
          if($crs['at_status']==0){
            $sts="<span class='text-warning'>Processing</span>";
          }
           else if($crs['at_status']==1){
            $sts="<span class='text-success'>Success</span>";
          }
          else if($crs['at_status']==9){
             $sts="<span class='text-danger'>Failed</span>";
          }
          ?>
          <td><?php echo $sts?></td>
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

  });
 
</script>
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