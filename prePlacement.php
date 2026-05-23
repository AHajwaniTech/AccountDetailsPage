<?php
error_reporting(1);
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


$query="SELECT FULL_NAME ,ACCOUNT_NUMBER , Notes_History,Status_Date,Last_Activity_Date FROM SEARCH_QUERY_DATA where    ACCOUNT_NUMBER='".$acc."'";
$result = mysqli_query($conn,$query);
$row=mysqli_fetch_assoc($result);
$query1="SELECT CAST(A.RMSTRANDTE AS DATE) AS RMSTRANDTE, TRANSCDE1,
        B.SYSDESC AS SYSDESC, A.HSTTRANDSC, A.RMSAUDITID
        FROM RMSPHISTFL A
        LEFT JOIN RMSPSYSCDE B ON A.TRANSCDE2 = B.SYSCODETYP
        LEFT JOIN RMSPMASTER C ON A.RMSFILENUM = C.RMSFILENUM
        WHERE A.RMSFILENUM = (SELECT RMSFILENUM FROM SEARCH_QUERY_DATA WHERE ACCOUNT_NUMBER ='".$acc."')
        AND A.TRANSCDE1 NOT IN ('M1','M2','MR')
        AND A.RMSTRANDTE <= C.RMSDATERCV
        ORDER BY A.RMSTRANDTE DESC";


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
  <p id="accdetail" style="color:white;"><b>COMBINED HISTORY</b></p>
</div>
<div class="card-header account" style="width:90%;margin-left:64px;background-color: #28648ad6;height: 31px;">
  <p id="accdetail" style="color:white;"><b>
  <?php 
  $Name=str_replace(",","",$row['FULL_NAME'] );
  echo $Name.'   '.$row['ACCOUNT_NUMBER'];?>
  </b></p>
</div>
<div class="card-header account" style="width:90%;margin-left:64px;background-color:white;height: 31px;">
  <p id="accdetail" style="color:#28648a;"><b>CURRENT STATUS & DESCRIPTION:<?php echo $row['Notes_History'].' - ';?>STATUS DATE:<?php echo DATE("Y/m/d",strtotime($row['Status_Date'])).' - ';?>STATUS RECEIVED BY AACA:<?php echo  DATE("Y/m/d",strtotime($row['Last_Activity_Date']));?></b></p>
</div>
<thead>
<tr>
<th>Date of Entry</th>
<th>Code</th>
<th>Status/Note Type</th>
<th>Detailed Description</th>
<th >User</th>
</tr>
</thead>
<tbody>
		<?php
  if(mysqli_num_rows($result1)){
 while($row1=mysqli_fetch_assoc($result1)){?>
<tr>
	<td><?php echo  DATE("Y/m/d",strtotime($row1['RMSTRANDTE']));?></td>
	<td><?php echo $row1['TRANSCDE1'];?></td>
	<td><?php echo $row1['SYSDESC'];?></td>
	<td><?php echo $row1['HSTTRANDSC'];?></td>
	<td><?php echo $row1['RMSAUDITID'];?></td>

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