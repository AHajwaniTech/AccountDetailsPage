<?php
error_reporting(0);
session_start();
if(!isset($_SESSION['email']))
{
  header('Location: logout.php');
  exit();
  }
include('config.php');
setlocale(LC_MONETARY, 'en_US');
//$accountNo =base64_decode($_GET['accNo']);
$accountNo =base64_decode($_SESSION['accNo']);
$acc=str_replace("a","#",$accountNo );
$query="SELECT FULL_NAME ,ACCOUNT_NUMBER ,State FROM SEARCH_QUERY_DATA where  ACCOUNT_NUMBER='".$acc."'";
$result = mysqli_query($conn,$query);
$row=mysqli_fetch_assoc($result);

$query1="SELECT A.ROFFCD AS Portfolio, A.LVL1 AS Description, A.VENDORNUM AS Code, A.LVL2 AS Description1, CAST(NULLIF(A.RMSTRANDTE,'') AS DATE) AS Date, A.RMSTRANDSC AS Description2,
	(CASE WHEN A.BLAAINNM LIKE 'R%' THEN A.COLLAM ELSE 0 END) AS Recoveries,
	(CASE WHEN (A.BLAAINNM LIKE 'R%' OR A.BLAAINNM LIKE 'D%') THEN FEEST
      	      WHEN A.AOFPYC = 'GR' THEN 0 ELSE A.FEEST END) AS Fees,
	(CASE WHEN A.BLAAINNM LIKE 'R%' THEN A.SETASIDES ELSE 0 END) AS SetAsides,
	(CASE WHEN A.AOFPYC = 'GR' THEN A.COLLAM ELSE (A.COLLAM - A.FEEST) END) AS Remit,
	A.BLAAINNM AS Number, B.COLL AS Amount, CAST(NULLIF(A.BILLDATE,'') AS DATE) AS Date1,
	(CASE WHEN A.BLAAINNM LIKE 'R%' THEN 'Paid'
              WHEN (A.BLAAINNM LIKE 'L%' OR A.BLAAINNM LIKE 'D%') AND A.BLAPDATE = '' THEN 'Due'
              WHEN (A.BLAAINNM LIKE 'L%' OR A.BLAAINNM LIKE 'D%') AND A.BLAPDATE <> '' AND (A.BLPYFRPP <> 0 OR A.BLFRPYAMPP <> 0) THEN 'Pending'
              ELSE 'Paid' END) AS Status
FROM RMAACABHS A
LEFT JOIN RMAACABHS_ROFFCD_COLL B  ON A.BLAAINNM = B.BLAAINNM AND A.ROFFCD = B.ROFFCD 
WHERE A.RMSTRANCDE IN ('50','51','58','59')
AND RMSACCTNUM = '".$acc."' ORDER BY RMSTRANDTE DESC";
//echo $query1;exit;
$result1 = mysqli_query($conn,$query1);
//echo $query;exit;
?>

<!DOCTYPE html>

<html oncontextmenu="return false">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Pipeway | Total Recoveries Client</title>
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
                                            <th class="text-center" colspan="2" style="font-size: 14px; background: #28648A !important; color: #ffffff;">Client</th>
                                            <th class="text-center" colspan="2" style="font-size: 14px; background: #28648A !important; color: #ffffff;">Firm</th>
                                            <th class="text-center" colspan="2" style="font-size: 14px; background: #28648A !important; color: #ffffff;">Transaction Detail</th>
                                            <th class="text-center" colspan="4" style="font-size: 14px; background: #28648A !important; color: #ffffff;">Recovery Distribution</th>
                                            <th class="text-center" colspan="5" style="font-size: 14px; background: #28648A !important; color: #ffffff;">AACA Reimbursement</th>
                                        </tr>
                                    </thead>
                                    <tbody class="counter-reset" id="">
                                       
                                        <tr>
                                            <td class="text-center"><strong style="font-size: 14px;">Portfolio</strong></td>
                                            <td class="text-left"><strong style="font-size: 14px;">Description</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Code</strong></td>
                                            <td class="text-left"><strong style="font-size: 14px;">Description</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;">Date</strong></td>
                                            <td class="text-left"><strong style="font-size: 14px;">Description</strong></td>
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
                                          $invno=$row1['Number'];
                                          $DOCTYPESUB=base64_encode('Remittance');
                                          $file="Report/downloadFirmClientcostrecoveries?invno=".base64_encode($invno)."&& DOCTYPESUB=".$DOCTYPESUB;
                                          
                                          if (strpos($row1['Fees'], '-') !== false) {
                                                 $Fees=str_replace("-"," ",$row1['Fees']);
                                                 $Fees="-$".$Fees;
                                              }
                                         else{
                                                $Fees="$".$row1['Fees'];
                                              }
                                              if (strpos($row1['Recoveries'], '-') !== false) {
                                                 $Recoveries=str_replace("-"," ",$row1['Recoveries']);
                                                 $Recoveries="-$".$Recoveries;
                                              }
                                         else{
                                                $Recoveries="$".$row1['Recoveries'];
                                              }
                                              if (strpos($row1['SetAsides'], '-') !== false) {
                                                 $SetAsides=str_replace("-"," ",$row1['SetAsides']);
                                                 $SetAsides="-$".$SetAsides;
                                              }
                                         else{
                                                $SetAsides="$".$row1['SetAsides'];
                                              }
                                              if (strpos($row1['Remit'], '-') !== false) {
                                                 $Remit=str_replace("-"," ",$row1['Remit']);
                                                 $Remit="-$".$Remit;
                                              }
                                         else{
                                                $Remit="$".$row1['Remit'];
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
                                            <td class="text-center"><?php echo $row1['Portfolio'];?></td>
                                            <td class="text-left"><?php echo $row1['Description'];?></td>
                                            <td class="text-center"><?php echo $row1['Code'];?></td>
                                            <td class="text-left"><?php echo $row1['Description1'];?></td>
                                            <td class="text-center"><?php echo $row1['Date'];?></td>
                                            <td class="text-left"><?php echo $row1['Description2'];?></td>
                                            <td class="text-center"><?php echo $Recoveries;?></td>
                                            <td class="text-center"><?php echo $Fees;?></td>
                                            <td class="text-center"><?php echo $SetAsides;?></td>
                                            <td class="text-center"><?php echo $Remit;?></td>
                                            <?php if(($_SESSION['userType']==1) ||($_SESSION['userType']==3)){?>
                                         <!--  <td class="text-center"><a href="<?php echo $file;?>"><?php echo $row1['Number'];?></a></td>  --> 
                                          <td class="text-center"><?php echo $row1['Number'];?></td> 
                                        <?php } else {?>
                                            <td class="text-center"><?php echo $row1['Number'];?></td> 
                                          <?php } ?>
                                            
                                            <td class="text-center"><?php echo $Amount;?></td>
                                            <td class="text-center"><?php echo $row1['Date1'];?></td>
                                            <td class="text-center"><?php echo $row1['Status'];?></td>
                                        </tr>
                                       <?php 

                                          $recoveries+=$row1['Recoveries'];
                                          $fess+=$row1['Fees'];
                                          $setaside+=$row1['SetAsides'];
                                          $remmit+=$row1['Remit'];

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
                                            <td class="text-center"><strong style="font-size: 14px;">Totals</strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"><?php echo $recoveries;?></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"><?php echo $fess;?></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"><?php echo $setaside;?></strong></td>
                                            <td class="text-center"><strong style="font-size: 14px;"><?php echo $remmit;?></strong></td>
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
