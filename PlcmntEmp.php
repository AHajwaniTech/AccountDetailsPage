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
// $accountNo =$_GET['accNo'];
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

$query1="SELECT distinct A.PLC_OF_EMPLMNT AS 'Employer Name', A.PLC_OF_EMPLMNT_ADD AS 'Employer Address',

(CASE WHEN B.RECORDTYPE = 'E' THEN CONCAT_WS(' ',B.RMSALPHLNM, B.RMSALPHFNM) ELSE 'NA' END) AS 'Full Employment Name',

(CASE WHEN B.RECORDTYPE = 'E' THEN RMSADDR1 ELSE 'NA' END) AS 'Full Employment Address 1',

(CASE WHEN B.RECORDTYPE = 'E' THEN RMSADDR2 ELSE 'NA' END) AS 'Full Employment Address 2',

(CASE WHEN B.RECORDTYPE = 'E' THEN RMSCITY ELSE 'NA' END) AS 'Full Employment City',

(CASE WHEN B.RECORDTYPE = 'E' THEN RMSSTATECD ELSE 'NA' END) AS 'Full Employment State',

(CASE WHEN B.RECORDTYPE = 'E' THEN RMSZIPCODE ELSE 'NA' END) AS 'Full Employment Zip'

FROM MASTER_DATA_DB A

LEFT JOIN RMSPCOMKR_DATA B ON A.RMSACCTNUM = B.RMSACCTNUM AND B.RECORDTYPE IN ('E','C')

WHERE A.RMSACCTNUM = '".$acc."'";
//echo $query1;exit;
$result1 = mysqli_query($conn,$query1);
//echo $query;exit;
?>
<!DOCTYPE html>

<html>
<head>
   <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Pipeway | BI Dashboard</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=11"/>
  <meta http-equiv="X-UA-Compatible" content="IE=10"/>
  <meta http-equiv="X-UA-Compatible" content="IE=9"/>
  <meta http-equiv="X-UA-Compatible" content="IE=8"/>
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../img/fevicon.ico">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../img/fevicon.ico">
  <link rel="apple-touch-icon-precomposed" href="img/fevicon.ico">
  <link rel="shortcut icon" href="img/fevicon.ico">
  <link rel="stylesheet" href="css/dataTables.min.css">
  <link rel="stylesheet" href="css/PSnnect.min.css">
  <link rel="stylesheet" href="css/PSdataTables.min.css">
  <link rel="stylesheet" href="css/PSPanel.css">
  <link rel="stylesheet" href="css/PSdaterangepicker.css">
  </head>
  <style>
thead{background-color:#28648a;color:white;}
#radiobutton{font-weight: bold;}
.dataTables_filter {
display: none;
}
td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}
th, td { white-space: nowrap; font-weight:bold;}
.alignment{text-align:right;}
  th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        margin: 0 auto;
    }
 
    div.container {
        width: 80%;
    }
  .dt-buttons{
  margin-left: 362px;
  margin-top: -35px;
}
.dataTables_info{display:none;}
  </style>

<body>
<table id="example" class="stripe row-border order-column" style="width:90%;margin-left:64px;" border="1">
<div class="card-header account" style="width:90%;margin-left:64px;background-color: #28648a;">
  <p id="accdetail" style="color:white;"><b style="font-size:19px;">Employment Information</b></p>
</div>
<div class="card-header account" style="width:90%;margin-left:64px;background-color: #28648ad6;height: 37px;">
<div class="col-sm-4">
<div >
 <p style="color:white;border-right:1px solid white;margin-top: 7px;"><b>
  <?php 
  $Name=str_replace(",","",$row['FULL_NAME'] );
  echo $Name;?> 
  </b></p>
</div>
</div>
<div class="col-sm-5">
<div >
 <p style="color:white;border-right:1px solid white;margin-top: 7px;text-align: center;"><b><?php echo $row['ACCOUNT_NUMBER'];?></b></p>
</div>
</div>
<div class="col-sm-3">
<div >
 <p style="color:white;margin-top: 7px;text-align: right;"><b><?php echo $row['State'];?></b></p>
</div>
</div>
</div>
<thead>
<tr>
<th>Employer Name</th>
<th>Employer Address</th>
<th>Full Employment Name</th>
<th>Full Employment Address 1</th>
<th >Full Employment Address 2</th>
<th >Full Employment City</th>
<th >Full Employment State</th>
<th >Full Employment Zip</th>
</tr>
</thead>
<tbody>
  <?php
  if(mysqli_num_rows($result1)){
 while($row1=mysqli_fetch_assoc($result1)){?>
<tr>
	<td><?php echo $row1['Employer Name'];?></td>
	<td><?php echo $row1['Employer Address'];?></td>
	<td><?php echo $row1['Full Employment Name'];?></td>
	<td><?php echo $row1['Full Employment Address 1'];?></td>
	<td><?php echo $row1['Full Employment Address 2'];?></td>
  <td><?php echo $row1['Full Employment City'];?></td>
  <td><?php echo $row1['Full Employment State'];?></td>
   <td><?php echo $row1['Full Employment Zip'];?></td>
</tr>
<?php } } else{
   $msg="No record found";
   echo "<p style='color:red;text-align:center'>" . $msg . "</p>";
}?>
</tbody>
</table>
</body>
 <script src="js/PSjquery.min.js"></script>
  <script src="js/PSnnect.min.js"></script>
  <script src="js/PSslimscroll.js"></script>
  <script src="js/PSnnectPanel.js"></script>

  <script src="js/autologout.js"></script>
 <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script> -->
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
</html>