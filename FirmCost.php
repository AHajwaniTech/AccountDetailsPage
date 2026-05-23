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

  $query1="SELECT A.VENDORNUM, A.LVL2, A.ROFFCD, A.LVL1, CAST(NULLIF(A.RMSTRANDTE,'') AS DATE) as Date, A.RMSTRANDSC, A.INVCLIENT, A.BLAAINNM, C.COST AS Amount,
      CAST(NULLIF(A.BILLDATE,'') AS DATE) as date1,
      (CASE WHEN A.VENDORNUM = B.ATTY_CDE THEN A.INVOICENO ELSE 'N/A' END) AS INVOICENO,
      (CASE WHEN A.VENDORNUM <> B.ATTY_CDE THEN 'N/A' ELSE CAST(NULLIF(A.BLPYFRDT,'') AS DATE) END) as date2,
      (CASE WHEN A.VENDORNUM = B.ATTY_CDE THEN A.BLPYFRCK ELSE 'N/A' END) AS 'Check #',
      D.TRAN_AMNT AS Amount1
     FROM RMAACABHS A
     LEFT JOIN MASTER_DATA_DB B ON A.RMSFILENUM = B.RMSFILENUM
     LEFT JOIN RMAACABHS_VENDORNUM_COST C ON A.BLAAINNM = C.BLAAINNM AND A.VENDORNUM = C.VENDORNUM
     LEFT JOIN PYALLATRPF_INVOICE_COST D  ON A.VENDORNUM = D.PYALFIRM AND A.BLPYFRCK = D.PYALCLCKNO AND A.BLPYFRDT = D.PYALTRDT
     WHERE A.RMSTRANCDE = '1A'
     AND A.RMSACCTNUM = '".$acc."' ORDER BY A.RMSTRANDTE DESC";//echo $query1;exit;
$result1 = mysqli_query($conn,$query1);
//echo $query;exit;
?>

<!DOCTYPE html>

<html oncontextmenu="return false">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Pipeway | Total Cost Firm</title>
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
                                            <th class="text-center" colspan="15" style="font-size: 15px; background: #28648A !important; color: #ffffff;">TOTAL COURT COST</th>
                                        </tr>
                                        <tr class="grey-bg">
                                            <th class="text-center" colspan="15" style="font-size: 15px; background: #28648A !important; color: #ffffff;"><?php echo $row['FULL_NAME'].'&nbsp&nbsp&nbsp&nbsp&nbsp'.$row['ACCOUNT_NUMBER'];?></th>
                                        </tr>
                                        <tr class="grey-bg">
                                            <th class="text-center" colspan="2" style="font-size: 14px; background: #28648A !important; color: #ffffff;">Firm</th>
                                            <th class="text-center" colspan="2" style="font-size: 14px; background: #28648A !important; color: #ffffff;">Client</th>
                                            <th class="text-center" colspan="3" style="font-size: 14px; background: #28648A !important; color: #ffffff;">Transaction Detail</th>
                                            <th class="text-center" colspan="3" style="font-size: 14px; background: #28648A !important; color: #ffffff;">AACA Invoice</th>
                                            <th class="text-center" colspan="5" style="font-size: 14px; background: #28648A !important; color: #ffffff;">Payment History</th>
                                        </tr>
                                    </thead>
                                    <tbody class="counter-reset" id="">
                                       
                                        <tr>
                                            <td class="text-center"><strong style="font-size: 14px;">Code</strong></td>
                                            <td class="text-left"><strong style="font-size: 14px;">Description</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Code</strong></td>
                                            <td class="text-left"><strong style="font-size: 14px;">Description</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Date</strong></td>
                                            <td class="text-left"><strong style="font-size: 14px;">Description</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Amount</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Number</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Amount</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Date</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Invoice #</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Pay Date</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Check #</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Amount</strong></td>
                                           <!--  <td class="text-center"><strong style="font-size: 14px;">Status</strong></td> -->
                                        </tr>
                                         <?php
                                        if(mysqli_num_rows($result1)){
                                          $sumAmount = 0;
                                        while($row1=mysqli_fetch_assoc($result1)){ //print_r($row1);exit;
                                          $invno=$row1['BLAAINNM'];
                                          $DOCTYPESUB=base64_encode('Court Cost');
                                          $file="Report/downloadFirmClientcostrecoveries?invno=".base64_encode($invno)."&& DOCTYPESUB=".$DOCTYPESUB;
                                          //  $path="//coho/AACA/PDF Archive/".$row1['BLAAINNM'].'.pdf';
                                          // $file="Report/downloadfile?nama=".base64_encode($row1['BLAAINNM'])."&&remote=".base64_encode($path);
                                          ?>
                                        <tr>
                                            <td class="text-center"><?php echo $row1['VENDORNUM'];?></td>
                                            <td class="text-left"><?php echo $row1['LVL2'];?></td>
                                            <td class="text-center"><?php echo $row1['ROFFCD'];?></td>
                                            <td class="text-left"><?php echo $row1['LVL1'];?></td>
                                            <td class="text-center"><?php echo $row1['Date'];?></td>
                                            <td class="text-left"><?php echo $row1['RMSTRANDSC'];?></td>
                                            <td class="text-center"><?php echo $row1['INVCLIENT'];?></td>
                                            <!--commenting line 123 to remove hyperlink from UI ON CLIENT escalation-->
                                            <?php if(($_SESSION['userType']==1) ||($_SESSION['userType']==3)){?>
                                         <!--  <td class="text-center"><a href="<?php echo $file;?>"><?php echo $row1['BLAAINNM'];?></a></td>  --> 
                                         <td class="text-center"><?php echo $row1['BLAAINNM'];?></td> 
                                        <?php } else {?>
                                            <td class="text-center"><?php echo $row1['BLAAINNM'];?></td> 
                                          <?php } ?>
                                            <td class="text-center"><?php echo $row1['Amount'];?></td>
                                            <td class="text-center"><?php echo $row1['date1'];?></td>
                                            <td class="text-center"><?php echo $row1['INVOICENO'];?></td>
                                            <td class="text-center"><?php echo $row1['date2'];?></td>
                                            <td class="text-center"><?php echo $row1['Check #'];?></td>
                                            <td class="text-center"><?php echo $row1['Amount1'];?></td>
                                            <!-- <td class="text-center"><?php //echo $row1['Ststus'];?></td> -->
                                        </tr>
                                       <?php $sumAmount += $row1['INVCLIENT'];}}?>
                                   
                                  
                                    
                                   
                                        <tr>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Totals</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"><?php echo '$'.$sumAmount;?> </strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"></strong></td>
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
</html>
