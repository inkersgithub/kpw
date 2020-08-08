<?php
include 'inc/config.php';
include 'inc/MysqliDb.php';
include 'inc/session.php';
include 'inc/template.php';
include 'inc/functions.php';

headBlock("Pending Transactions");
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
              <h6 class="m-0 font-weight-bold text-primary">Pending Transactions</h6>
            </div>
            <div class="card-body">
                
<?php
$cnt=0;
$db = new MysqliDb (HOST,USER,PSD,DB);
$cols = Array ("rpt_id", "rpt_pid", "rpt_rrn","rpt_tstamp","rpt_amt","rpt_stsdes","rpt_stscod");
$db->where("rpt_stscod","01");
$db->orWhere("rpt_stscod","05");
$db->where("rpt_sts",0);
$db->orderBy("rpt_id","Desc");
$txns = $db->get ("rp_transactions", null, $cols);
if ($db->count > 0)
{
?>
        
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th style="width:60px;">Sl. No.:</th>
                      <th>RedPay ID</th>
                      <th>Txn RRN</th>
                      <th>Time</th>
                      <th>Amount</th>
                      <th>Status</th>
                      <th style="width:80px; text-align:center;">Action</th>
                    </tr>
                  </thead>
                 
                  <tbody>
                      <?php
            foreach ($txns as $txn) { $cnt++; 
            $rrn = (strlen($txn['rpt_rrn'])>0)?$txn['rpt_rrn']:"Nil";
            $stsclr =($txn["rpt_stscod"]=="00")?"green":"red";
            $rfdbtn =($txn["rpt_stscod"]=="00")?"":"style='display:none;'";
            ?>

                    <tr>
                      <td><?=$cnt?></td>
                      <td><?=$txn['rpt_pid']?></td>
                      <td><?=$rrn?></td>
                      <td><?=$txn['rpt_tstamp']?></td>
                      <td><?=$txn['rpt_amt']?></td>
                      <td style="color:<?=$stsclr?>"><?=$txn['rpt_stsdes']?></td>
                      <td style="text-align:center;">
                          <!--<button id="" class="refBtn btn btn-danger btn-icon-split btn-sm btnprint" <?=$rfdbtn?>> <span class="icon text-white-50"><i class="fas fa-redo-alt"></i></span><span class="text"> Refund</span></button>-->
                      
                        <button id="" data-toggle="modal" data-target="#myModal" onclick="RefundBox('<?=$txn['rpt_id']?>','<?=$txn['rpt_pid']?>','<?=$txn['rpt_amt']?>','<?=$txn['rpt_tstamp']?>')" class="detBtn btn btn-success btn-icon-split btn-sm btnprint"><span class="icon text-white-50"><i class="fas fa-info"></i></span><span class="text"> Details</span></button></td>
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
      
            <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog" >

    <!-- Modal content-->
    <div class="modal-content" style="border-radius:0;">
      
      <div class="modal-body">
       
       
       <div class="container">
  <p>Transactions Details</p> 
  <hr>
  <table class="table table-striped">
    
    <tbody style="border:2px solid #eee;">
      <tr>
        <td><b>Tranctions Date</b></td>
        <td></td>
        <td id=tdate></td>
      </tr>
      <tr>
        <td><b>RedPay ID</b></td>
        <td></td>
        <td id=rid></td>
      </tr>
      <tr>
        <td><b>Txn Amount</b></td>
        <td></td>
        <td id=tamt></td>
      </tr>
      
      
    </tbody>
  </table>
</div>
       
       
      </div>
<div>
         <!-- <button id="refbtn" style="border-radius:0; width:50%; float:left;"  class="btn btn-success btn-icon-split">-->
         <!--<span class="text">Initiate Refund</span>-->
         <!--</button>-->

          <button style="border-radius:0; width:100%;  float:left;" data-dismiss="modal" class="btn btn-warning btn-icon-split">
         <span class="text">Close</span>
         </button>
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


<script>
    
function RefundBox(tid,txnid,txnamt,txndt)
{

    $("#tdate").html(txndt);
    $("#rid").html(txnid);
    $("#tamt").html(txnamt);
   
}    
</script>