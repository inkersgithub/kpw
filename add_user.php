<?php
include 'inc/config.php';
include 'inc/MysqliDb.php';
include 'inc/session.php';
include 'inc/template.php';
include 'inc/functions.php';

headBlock("Transactions");
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
              <h6 class="m-0 font-weight-bold text-primary">Transactions</h6>
              

            </div>
            <div class="card-body">
                
<?php
$cnt=0;
$db = new MysqliDb (HOST,USER,PSD,DB);
$cols = Array ("rpt_id", "rpt_pid", "rpt_rrn","rpt_tstamp","rpt_amt","rpt_stsdes","rpt_stscod");
if(isset($_GET['sdate']) && isset($_GET['edate']))
{
$db->where('rpt_tstamp', Array ($_GET['sdate'].' 00:00:00.000000',$_GET['edate'].' 23:59:00.000000'), 'BETWEEN');
// echo'
// <script>
// $("#daterange").val("'.date($_GET['sdate']).','.date($_GET['edate']).'");
// </script>
// ';
}
else
{$db->where("rpt_tstamp >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY");
$db->where("rpt_tstamp < curdate() - INTERVAL DAYOFWEEK(curdate())-1 DAY");}
$db->where("rpt_sts",0);
$db->orderBy("rpt_id","Desc");
$txns = $db->get ("rp_transactions", null, $cols);
if ($db->count > 0)
{
?><input class="form-control" id="daterange" type="text" style="width:250px; float:right;" name="daterange" value="<?=date("Y/m/d")?>" />
<br><br>
        
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    
                  <thead>
                    <tr>
                      <th style="width:60px;">Sl. No.:</th>
                      <th>RedPay ID</th>
                      <th>Txn RRN</th>
                      <th>Date & time</th>
                      <th>Amount</th>
                      <th>Refund</th>
                      <th>Status</th>
                      <th style="width:100px; text-align:center;">Action</th>
                    </tr>
                  </thead>
                 
                  <tbody>
                      <?php
            foreach ($txns as $txn) { $cnt++; 
            $rrn = (strlen($txn['rpt_rrn'])>0)?$txn['rpt_rrn']:"Nil";
            $stsclr =($txn["rpt_stscod"]=="00")?"green":"red";
            $rfdbtn =($txn["rpt_stscod"]=="00")?"":"disabled";
            if($txn["rpt_stscod"]=="00")
            {$stsbox='<b class="btn btn-success  btn-sm">Success</b>';}
            else if($txn["rpt_stscod"]=="05")
            {$stsbox='<b class="btn btn-warning  btn-sm">Pending</b>';}
            else{$stsbox='<b class="btn btn-danger  btn-sm">Failed</b>';}
            $db->where("rpr_rpid",$txn['rpt_pid']);
            $scssrfd = $db->getOne ("rp_refund", "sum(rpr_refamt) as ttl, count(*) as cnt");
            
            ?>

                    <tr>
                      <td><?=$cnt?></td>
                      <td><?=$txn['rpt_pid']?></td>
                      <td><?=$rrn?></td>
                      <td><?=$txn['rpt_tstamp']?></td>
                      <td><?=$txn['rpt_amt']?></td>
                      <td><?=$scssrfd['ttl']?></td>
                      <td ><?=$stsbox?></td>
                      <td style="text-align:center;">
                          <button id="" onclick="RefundBox('<?=$txn['rpt_id']?>','<?=$txn['rpt_pid']?>','<?=$txn['rpt_amt']?>','<?=$txn['rpt_tstamp']?>')"
                          class="refBtn btn btn-warning btn-icon-split btn-sm btnprint" data-toggle="modal" data-target="#myModal" <?=$rfdbtn?>> <span class="icon text-white-50"><i class="fas fa-redo-alt"></i></span><span class="text"> Refund</span></button>
                      
                        <!--<button id="" class="detBtn btn btn-info btn-icon-split btn-sm btnprint"><span class="icon text-white-50"><i class="fas fa-info"></i></span><span class="text"> Details</span></button></td>-->
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
      <form id="refform" method="post">
      <tr>
        <td style="width:180px;"><b>Refund Amount</b></td>
        <td></td>
        <td> 
        <input type="text"  name="refuser" value="<?=$_SESSION['sessionuser']?>" style="display:none;">
    <input type="text" id="htxnamt" name="txnamt" style="display:none;">
    <input type="text" id="htxnid" name="txntbid" style="display:none;">
    <input type="number" class="form-control" id="refamt" name="refamt">
</td>
      </tr>
      
      
    </tbody>
  </table>
</div>
       
       
      </div>
<div>
          <button id="refbtn" style="border-radius:0; width:50%; float:left;"  class="btn btn-success btn-icon-split">
         <span id="spbtn" class="text">Initiate Refund</span>
         </button>
          </form>
          <button style="border-radius:0; width:50%;  float:left;" data-dismiss="modal" class="btn btn-danger btn-icon-split">
         <span class="text">Cancel</span>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

<script>


    
function RefundBox(tid,txnid,txnamt,txndt)
{

    $("#tdate").html(txndt);
    $("#rid").html(txnid);
    $("#tamt").html(txnamt);
    $("#htxnid").val(tid);
    $("#htxnamt").val(txnamt);
   
}    

//regForm
$(document).on("submit","#refform",function(evt){
evt.preventDefault();

if($('#refamt').val().length>0){
var refamtval=$('#refamt').val();

$("#refbtn").attr("disabled","disabled");
$("#spbtn").html("Please wait...");


$.ajax({
url: 'ajax/refund',
type: 'POST',
data:$("#refform").serialize(),
success: function(response, textStatus, xhr) {
console.log(response);
$("#regBtn").html("Register").removeAttr("disabled");
try{
var jresp=$.parseJSON(JSON.stringify(response));
if(jresp.sts==="01"){
$('#myModal').modal('toggle');
$("#refbtn").removeAttr("disabled");
$("#spbtn").html("Initiate Refund");

showToast(jresp.msg);
}
else
{
showToast(jresp.msg);
    $("#refbtn").removeAttr("disabled");
$("#spbtn").html("Initiate Refund");
}
}catch(exp){
showToast("Something went wrong, Please try again");
   $("#refbtn").removeAttr("disabled");
$("#spbtn").html("Initiate Refund");
}
//called when successful
},
error: function(xhr, textStatus, errorThrown) {
//called when there is an error
}
});
}
else{
    showToast("Refund amount is too less or empty!");

}   
}); 


$(function() {
  $('input[name="daterange"]').daterangepicker({
    opens: 'left'
  }, function(start, end, label) {
    console.log("A new date selection was made: " + start+ ' to ' + end.format('YYYY/MM/DD'));
    window.location = '<?=BASE?>/transactions?sdate='+start.format('YYYY/MM/DD')+'&edate='+end.format('YYYY/MM/DD');

  });
});
</script>