<?php
error_reporting(0);
session_start();
if(!isset($_SESSION['email']))
{
  header('Location: logout.php');
  exit();
  }
include('config.php');
$accountNo =base64_decode($_SESSION['accNo']);
$acc=str_replace("a","#",$accountNo );
$query="SELECT FULL_NAME ,ACCOUNT_NUMBER ,State FROM SEARCH_QUERY_DATA where  ACCOUNT_NUMBER='".$acc."'";
$result = mysqli_query($conn,$query);
$row=mysqli_fetch_assoc($result);
$query1="SELECT  JUDG_DUE_PRINCPL,JUDG_DUE_INTRST,(JUDG_DUE_COSTS + JDGCOSTS1 + JDGCOSTS2) AS JUDG_AWARDED_COST,(JUDG_DUE_PRINCPL - JDGRECVRYS) AS JUDG_DUE_PRINCPL,(JUDG_DUE_INTRST - JDGRCVINTS) AS JUDG_DUE_INTRST,JDGXCSRCVS * ( -1 ) AS JUDG_DUE_EXCESS ,((JUDG_DUE_COSTS + JDGCOSTS1 + JDGCOSTS2) -        JDGRCVCSTS - JDGRECCS1 - JDGRECCS2) AS JUDG_DUE_COST FROM SEARCH_QUERY_DATA WHERE ACCOUNT_NUMBER = '".$acc."'";
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
  <p id="accdetail" style="color:white;"><b style="font-size:19px;">Judgement Breakdown</b></p>
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
<th>Judgement Awarded Principal</th>
<th>Judgement Awarded Interest</th>
<th>Judgement Awarded Costs</th>
<th>Judgement Due Principal</th>
<th >Judgement Due Interest</th>
<th >Judgement Due Excess</th>
<th >Judgement Due Cost</th>
</tr>
</thead>
<tbody>
  <?php
  if(mysqli_num_rows($result1)){
 while($row1=mysqli_fetch_assoc($result1)){?>
<tr>
	<td><?php echo '$'.' '.$row1['JUDG_DUE_PRINCPL'];?></td>
	<td><?php echo '$'.' '.$row1['JUDG_DUE_INTRST'];?></td>
	<td><?php echo '$'.' '.$row1['JUDG_AWARDED_COST'];?></td>
	<td><?php echo '$'.' '.$row1['JUDG_DUE_PRINCPL'];?></td>
	<td><?php echo '$'.' '.$row1['JUDG_DUE_INTRST'];?></td>
  <td><?php echo '$'.' '.$row1['JUDG_DUE_EXCESS'];?></td>
  <td><?php echo '$'.' '.$row1['JUDG_DUE_COST'];?></td>
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