<?php
include 'inc/config.php';
include 'inc/MysqliDb.php';
include 'inc/session.php';
include 'inc/template.php';
include 'inc/functions.php';

headBlock("Activity Logs");
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






          <!-- DataTales Example -->
          <div class="card shadow mb-4" style="width: 100%;">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">User Activity Logs</h6>
            </div>
            <div class="card-body">
                
<?php
$cnt=0;
$db = new MysqliDb (HOST,USER,PSD,DB);
$cols = Array ("rps_lastlogin", "rps_browser", "rps_ip");
$db->where("rps_sts",0);
$db->where("rps_admin",$_SESSION['sessionuserid']);
$db->orderBy("rps_id","Desc");
$scns = $db->get ("rp_session", null, $cols);
if ($db->count > 0)
{
?>
        
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th style="width:60px;">Sl. No.:</th>
                      <th>Login Timings</th>
                      <th>IP Address</th>
                      <th>Device</th>
                      <th style="width:80px; text-align:center;">Action</th>
                    </tr>
                  </thead>
                 
                  <tbody>
                      <?php
            foreach ($scns as $scn) { $cnt++; 

            ?>

                    <tr>
                      <td><?=$cnt?></td>
                      <td><?=$scn['rps_lastlogin']?></td>
                      <td><?=$scn['rps_ip']?></td>
                      <td><?=$scn['rps_browser']?></td>
                      <td style="text-align:center;">

                        <button id="" class="detBtn btn btn-success btn-icon-split btn-sm btnprint"><span class="icon text-white-50"><i class="fas fa-info"></i></span><span class="text"> Details</span></button></td>
                    </tr>
                    
                    <?php } ?>


                  </tbody>
                </table>
              </div>
              
<?php } ?>                 
            </div>
        </div>
        
        
        <!-- /.container-fluid -->
<!-------------------------------------------------------------------------------------------------------------------------------------->

      </div>
      <!-- End of Main Content -->

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