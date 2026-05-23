<?php
error_reporting(0);
session_start();

/*
 * Author: KEANT Technologies              Date: 18 MAY 2026
 *
 * Purpose:
 * This logic handles account number retrieval for Account Detail navigation
 * using URL request parameters instead of shared PHP SESSION storage.
 *
 * Previous Behavior:
 * - Account number fetched from PHP SESSION
 * - Shared session caused account overwrite across multiple browser tabs
 *
 * New Behavior:
 * - Account number fetched from URL parameter ($_GET['acc'])
 * - Base64 decoded before processing
 * - Supports independent multi-tab account navigation
 *
 * Key Changes:
 * - Removed dependency on $_SESSION['accNo']
 * - Added URL parameter-based account handling
 * - Preserved existing account trimming/format logic
 *
 * Change Tag:
 * ----------------------------------------------------------------------------
 * AH18052026 | Replaced session-based account handling with URL parameter
 * ----------------------------------------------------------------------------
 * ============================================================================
 */

if(!isset($_SESSION['email']))
{
  header('Location: logout.php');
  exit();
  }
include('config.php');
//$accountNo =base64_decode($_GET['accNo']);

//AH18052026 : Starts
// $accountNo =base64_decode($_SESSION['accNo']);
$accountNo = '';
if(isset($_GET['acc']) && $_GET['acc'] != '')
{
    $accountNo = base64_decode($_GET['acc']);
}
// $acc=str_replace("a","#",$accountNo );
$acc = trim($accountNo);
//AH18052026 End 

$query="SELECT FULL_NAME ,ACCOUNT_NUMBER ,State FROM SEARCH_QUERY_DATA where  ACCOUNT_NUMBER='".$acc."'";
$result = mysqli_query($conn,$query);
$row=mysqli_fetch_assoc($result);

$query1="SELECT A.VENDORNUM, A.LVL2, A.ROFFCD, A.LVL1, CAST(NULLIF(A.RMSTRANDTE,'') AS DATE) as Date, A.RMSTRANDSC, A.COLLAM, A.FEES, A.SETASIDES, (A.COLLAM-A.FEES) as remit ,
(CASE WHEN A.VENDORNUM <> B.ATTY_CDE THEN 'N/A' WHEN A.BLINFRIN LIKE '9%' THEN A.BLINFRIN ELSE A.BLPYFRCK END) as Number,
C.TRAN_AMNT AS 'Amount',
(CASE WHEN A.VENDORNUM <> B.ATTY_CDE THEN 'N/A'
WHEN A.BLINFRIN LIKE '9%' AND A.BLINFRDT <> '' THEN CAST(A.BLINFRDT AS DATE)
WHEN A.BLPYFRDT <> '' THEN CAST(A.BLPYFRDT AS DATE) ELSE NULL END) AS Date1,
(CASE WHEN (A.BLAPDATE = '' AND A.DTPRLT = '') THEN 'Open'
WHEN (A.BLAPDATE = '' AND A.DTPRLT <> '') THEN 'Closed'
WHEN (A.BLPYFRDT = '' AND A.BLINFRDT = '') THEN 'Pending'
WHEN (A.BLPYFRDT = '' AND A.BLINFRDT <> '') THEN 'Invoiced'
WHEN (A.BLPYFRDT <> '' AND A.BLINFRDT <> '') THEN 'Invoiced/Paid'
WHEN A.BLPYFRDT <> '' THEN 'Paid' ELSE 'UNKNOWN' END) as Status,
A.BLAAINNM
FROM RMAACABHS A
LEFT JOIN MASTER_DATA_DB B ON A.RMSFILENUM = B.RMSFILENUM
LEFT JOIN PYALLATRPF_INVOICE_COST C ON A.VENDORNUM = C.PYALFIRM AND A.BLPYFRCK = C.PYALCLCKNO AND A.BLPYFRDT = C.PYALTRDT
WHERE A.RMSTRANCDE IN ('50','51','58','59')
AND A.RMSACCTNUM = '".$acc."' ORDER BY A.RMSTRANDTE DESC";
//echo $query1;exit;
$result1 = mysqli_query($conn,$query1);
//echo $query;exit;
?>

<!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Pipeway | Total Recoveries Firm</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=11"/>
  <meta http-equiv="X-UA-Compatible" content="IE=10"/>
  <meta http-equiv="X-UA-Compatible" content="IE=9"/>
  <meta http-equiv="X-UA-Compatible" content="IE=8"/>
  <link rel="stylesheet" href="css/PSnnect.min.css">
  <link rel="stylesheet" href="css/PSdataTables.min.css">
  <link rel="stylesheet" href="css/PSPanel.css">
  <link rel="stylesheet" href="css/PSdaterangepicker.css">
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/gallery/fevicon.ico">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/gallery/fevicon.ico">
  <link rel="apple-touch-icon-precomposed" href="img/fevicon.ico">
  <link rel="shortcut icon" href="img/fevicon.ico">
</head>

<body class="hold-transition skin-yellow sidebar-mini fixed">
    
<div class="wrapper">
  <section class="content">
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                    <div class="box-body">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table id="table1" class="table table-bordered table-striped center-content">
                                    <thead>
                                        <tr class="grey-bg">
                                            <th class="text-center" colspan="15" style="font-size: 15px; background: #28648A !important; color: #ffffff;">TOTAL RECOVERIES</th>
                                        </tr>
                                        <tr class="grey-bg">
                                            <th class="text-center" colspan="15" style="font-size: 15px; background: #28648A !important; color: #ffffff;"><?php echo $row['FULL_NAME'].'&nbsp&nbsp&nbsp&nbsp&nbsp'.$row['ACCOUNT_NUMBER'];?></th>
                                        </tr>
                                        <tr class="grey-bg">
                                            <th class="text-center" colspan="2" style="font-size: 14px; background: #28648A !important; color: #ffffff;">Firm</th>
                                            <th class="text-center" colspan="2" style="font-size: 14px; background: #28648A !important; color: #ffffff;">Client</th>
                                            <th class="text-center" colspan="3" style="font-size: 14px; background: #28648A !important; color: #ffffff;">Transaction Detail</th>
                                            <th class="text-center" colspan="4" style="font-size: 14px; background: #28648A !important; color: #ffffff;">Recovery Distribution</th>
                                            <th class="text-center" colspan="4" style="font-size: 14px; background: #28648A !important; color: #ffffff;">AACA Reimbursement</th>
                                        </tr>
                                    </thead>
                                    <tbody class="counter-reset" id="">
                                       
                                        <tr>
                                            <td class="text-center"><strong style="font-size: 14px;">Code</strong></td>
                                            <td class="text-left"><strong style="font-size: 14px;">Description</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Portfolio</strong></td>
                                            <td class="text-left"><strong style="font-size: 14px;">Description</strong></td>
                                            <!-- <td class="text-center"><strong style="font-size: 14px;">Invoice #</strong></td> -->
                                            <td class="text-center"><strong style="font-size: 14px;">Date</strong></td>
                                            <td class="text-left"><strong style="font-size: 14px;">Description</strong></td>
                                             <td class="text-center"><strong style="font-size: 14px;">Number</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Recoveries</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Fees</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Set Asides</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Remit</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Number</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Amount</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Date</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Status</strong></td>
                                        </tr>
                                         <?php
                                        if(mysqli_num_rows($result1)){
                                             $recoveries = 0;
                                             $fess = 0;
                                             $setaside = 0;
                                             $remmit = 0;
                                        while($row1=mysqli_fetch_assoc($result1)){ //print_r($row1);exit;
                                        	 $invno=$row1['BLAAINNM'];
                                             $DOCTYPESUB=base64_encode('Remittance');
                                             $file="Report/downloadFirmClientcostrecoveries?invno=".base64_encode($invno)."&& DOCTYPESUB=".$DOCTYPESUB;
                                             $filenew="invno=".base64_encode($invno)."&& DOCTYPESUB=".$DOCTYPESUB;
                                             if (strpos($row1['COLLAM'], '-') !== false) {
                                                 $COLLAM=str_replace("-"," ",$row1['COLLAM']);
                                                 $COLLAM="-$".$COLLAM;
                                              }
                                         else{
                                                $COLLAM="$".$row1['COLLAM'];
                                              }
                                              if (strpos($row1['Recoveries'], '-') !== false) {
                                                 $Recoveries=str_replace("-"," ",$row1['Recoveries']);
                                                 $Recoveries="-$".$Recoveries;
                                              }
                                         else{
                                                $Recoveries="$".$row1['Recoveries'];
                                              }
                                              if (strpos($row1['FEES'], '-') !== false) {
                                                 $FEES=str_replace("-"," ",$row1['FEES']);
                                                 $FEES="-$".$FEES;
                                              }
                                         else{
                                                $FEES="$".$row1['FEES'];
                                              }
                                              if (strpos($row1['SETASIDES'], '-') !== false) {
                                                 $SETASIDES=str_replace("-"," ",$row1['SETASIDES']);
                                                 $SETASIDES="-$".$SETASIDES;
                                              }
                                         else{
                                                $SETASIDES="$".$row1['SETASIDES'];
                                              }
                                              if (strpos($row1['remit'], '-') !== false) {
                                                 $remit=str_replace("-"," ",$row1['remit']);
                                                 $remit="-$".$remit;
                                              }
                                         else{
                                                $remit="$".$row1['remit'];
                                              }
                                              if (strpos($row1['Amount'], '-') !== false) {
                                                 $Amount=str_replace("-"," ",$row1['Amount']);
                                                 $Amount="-$".$Amount;
                                              }
                                         else{
                                                $Amount="$".$row1['Amount'];
                                              }
                                             ?>
                                        <tr>
                                            <td class="text-center"><?php echo $row1['VENDORNUM'];?></td>
                                            <td class="text-left"><?php echo $row1['LVL2'];?></td>
                                            <td class="text-center"><?php echo $row1['ROFFCD'];?></td>
                                            <td class="text-left"><?php echo $row1['LVL1'];?></td>
                                           <!--  <td class="text-center"><?php echo $row1['INVOICENO'];?></td> -->
                                            <td class="text-center"><?php echo $row1['Date'];?></td>
                                            <td class="text-left"><?php echo $row1['RMSTRANDSC'];?></td>
                                          <?php if(($_SESSION['userType']==1) ||($_SESSION['userType']==3)){?>
                                          <!-- <td class="text-center"><a href="<?php echo $file;?>"><?php echo $row1['BLAAINNM'];?></a></td>  --> 
                                           <td class="text-center"><?php echo $row1['BLAAINNM'];?></td> 
                                        <?php } else {?>
                                            <td class="text-center"><?php echo $row1['BLAAINNM'];?></td> 
                                          <?php } ?>
                                            <td class="text-center"><?php echo $COLLAM;?></td>
                                            <td class="text-center"><?php echo $FEES;?></td>
                                            <td class="text-center"><?php echo $SETASIDES;?></td>
                                            <td class="text-center"><?php echo $remit;?></td>
                                            <td class="text-center"><?php echo $row1['Number'];?></td>
                                            <td class="text-center"><?php echo $Amount;?></td>
                                            <td class="text-center"><?php echo $row1['Date1'];?></td>
                                            <td class="text-center"><?php echo $row1['Status'];?></td>
                                        </tr>
                                       <?php 

                                          $recoveries+=$row1['COLLAM'];
                                          $fess+=$row1['FEES']; 
                                          $setaside+=$row1['SETASIDES'];
                                          $remmit+=$row1['remit'];
                                     } }
                                     if (strpos($recoveries, '-') !== false) {
                                                 $recoveries=str_replace("-"," ",$recoveries);
                                                 $recoveries="-$".$recoveries;
                                              }
                                         else{
                                                $recoveries="$".$recoveries;
                                              }
                                                 if (strpos($fess, '-') !== false) {
                                                 $fess=str_replace("-"," ",$fess);
                                                 $fess="-$".$fess;
                                              }
                                         else{
                                                $fess="$".$fess;
                                              }
                                        if (strpos($setaside, '-') !== false) {
                                                 $setaside=str_replace("-"," ",$setaside);
                                                 $setaside="-$".$setaside;
                                              }
                                         else{
                                                $setaside="$".$setaside;
                                              }
                                          if (strpos($remmit, '-') !== false) {
                                                 $remmit=str_replace("-"," ",$remmit);
                                                 $remmit="-$".$remmit;
                                              }
                                         else{
                                                $remmit="$".$remmit;
                                              }
                                     ?>
                                    
                                  
                                    
                                   
                                        <tr>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Totals</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"><?php echo $recoveries;?></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"><?php echo $fess;?></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"><?php echo $setaside;?></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"><?php echo $remmit;?></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

</body>
 <script src="js/PSjquery.min.js"></script>
<script>
 /* $(document).ready(function() {
  $('.docview').click(function(){
   var filename = $(this).data('name');
   $.ajax({
    url: 'Report/downloadFirmClientcostrecoveries',
    type: 'get',
    data: {filename: filename},
    success: function(response){ 
     //location.reload();

    }
    });
  });
 });*/
</script>
</html>
