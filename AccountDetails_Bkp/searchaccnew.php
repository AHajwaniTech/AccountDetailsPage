<?php 
error_reporting(0);
session_start();
if(!isset($_SESSION['email']))
{
  header('Location: logout.php');
  exit();
  }
include('config.php');

/*/*for name and account search */

if($_SESSION['advancenameacc']!=''){
$_SESSION['val1']=1;
unset($_SESSION['name']);
unset($_SESSION['accountno']);
unset($_SESSION['val2']);
unset($_SESSION['val3']);
unset($_SESSION['pagename']);

}

if($_SESSION['name']!=''){
$_SESSION['val2']=2;
unset($_SESSION['advancenameacc']);
unset($_SESSION['accountno']);
unset($_SESSION['val1']);
unset($_SESSION['val3']);
unset($_SESSION['pagename']);
}

if($_SESSION['accountno']!=''){//print_r($_SESSION);exit;
$_SESSION['val3']=3;
unset($_SESSION['name']);
unset($_SESSION['advancenameacc']);
unset($_SESSION['val1']);
unset($_SESSION['val2']);
unset($_SESSION['rmsfilenumenc']);
}
/*/*for name and account search ends here */
// $accountNo =$_GET['accNo'];
// $acc=str_replace("a","#",$accountNo );
//$acc =base64_decode($_GET['filenum']);
if($_SESSION['accountno']!='')
{
  $acc =base64_decode($_SESSION['rmsfilenumenc']);
  $accountno=$_SESSION['accountno'];
}
else
{
  $accountno = $_SESSION['statusupdateaccno'];
}


  $atty_cde     =$_SESSION['firmCode'];
  $client_cde   =$_SESSION['clientCode'];
  $newattycode  =$_SESSION['newattycode'];
  $newclientcode=$_SESSION['newclientcode'];
  $portfoliocode=$_SESSION['portfolioCode'];
  $productCode  =$_SESSION['productCode'];
  $statecode    =$_SESSION['state'];

if(($_SESSION['newuserType']==2 || $_SESSION['newuserType']==4) && $_SESSION['userType']==1){
 $atty_cdenews    = explode(",", $_SESSION['newattycode']);
  $atty_cdenew1    = "'" . implode("', '", $atty_cdenews) ."'";
  $atty_cdenew     ='ATTORNEY IN ('.$atty_cdenew1.')';
  $attymaster      ='ATTY_CDE IN ('.$atty_cdenew1.')';
}else{
  if($atty_cde=='ALL' || $atty_cde=='' ){
  $atty_cdenew='ATTORNEY=ATTORNEY';
  $attymaster ='ATTY_CDE=ATTY_CDE';

}else{
  $atty_cdenews    = explode(",", $_SESSION['firmCode']);
  $atty_cdenew1    = "'" . implode("', '", $atty_cdenews) ."'";
  $atty_cdenew     ='ATTORNEY IN ('.$atty_cdenew1.')';
  $attymaster      ='ATTY_CDE IN ('.$atty_cdenew1.')';
}
}
if($_SESSION['newuserType']==3 && $_SESSION['userType']==1){
  $client_cdes       = explode(",", $_SESSION['newclientcode']);
  $client_cdes1      = "'" . implode("', '", $client_cdes) ."'";
  $client_cdenew     ='CLIENT_CDE IN ('.$client_cdes1.')';
}else{
  if($client_cde=='ALL' || $client_cde==''){
  $client_cdenew='CLIENT_CDE=CLIENT_CDE';
}else{
  $client_cdes       = explode(",", $_SESSION['clientCode']);
  $client_cdes1      = "'" . implode("', '", $client_cdes) ."'";
  $client_cdenew     ='CLIENT_CDE IN ('.$client_cdes1.')';
}
}
if($portfoliocode=='ALL' || $portfoliocode==''){
  $portfoliocodenew ='PORTFOLIO_CODE=PORTFOLIO_CODE';
  $portmaster       ='PORTFOLIO_CDE=PORTFOLIO_CDE';
}else{
  $portfoliocodes       = explode(",", $_SESSION['portfolioCode']);
  $portfoliocodes1      = "'" . implode("', '", $portfoliocodes) ."'";
  $portfoliocodenew     ='PORTFOLIO_CODE IN ('.$portfoliocodes1.')';
  $portmaster           ='PORTFOLIO_CDE IN ('.$portfoliocodes1.')';
}

if($productCode=='ALL' || $productCode==''){
  $productCodenew='PRODUCT_CDE=PRODUCT_CDE';
}else{
  $productCodenews       = explode(",", $_SESSION['portfolioCode']);
  $productCodenew1       = "'" . implode("', '", $productCodenews) ."'";
  $productCodenew        ='PRODUCT_CDE IN ('.$productCodenew1.')';
}
if($statecode=='ALL' || $statecode==''){
  $statecodenew='State=State';
  $statemaster  ='DEBTR_STATE_AD =DEBTR_STATE_AD';
}else{
  $statecodenews       = explode(",", $_SESSION['state']);
  $statecodenew1       = "'" . implode("', '", $statecodenews) ."'";
  $statecodenew        ='State IN ('.$statecodenew1.')';
  $statemaster         ='DEBTR_STATE_AD IN ('.$statecodenew1.')';
}

$NewQuerymaster="AND ".$attymaster." AND ".$client_cdenew." AND ".$portmaster." AND ".$productCodenew." AND ".$statemaster;
if($acc==''){
  $que1="A.RMSACCTNUM ='".$accountno."'";
  $accountnoget=$accountno;
}else{
  $que1="A.RMSFILENUM ='".$acc."'";
   $accountnoget=$acc;

}

if($_SESSION['userType']==2 || $_SESSION['userType']==4){
$query="AND A.ATTY_CDE='".$atty_cde."' ";
}else if($_SESSION['userType']==3){
$query="AND A.CLIENT_CDE='".$client_cde."' ";
}else if($_SESSION['newuserType']==2 || $_SESSION['newuserType']==4){
$query="AND A.ATTY_CDE='".$newattycode."' ";
}else if($_SESSION['newuserType']==3){
 $query="AND A.CLIENT_CDE='".$newclientcode."' ";
}else{
  $query = " ";
}
$query1="SELECT DISTINCT CONCAT(A.DEBTOR_LAST_NME,', ',A.DEBTOR_FIRST_NME) AS FULL_NAME, A.RMSACCTNUM AS ACCOUNT_NUMBER, CONCAT(A.DEBTOR_LAST_NME,' ',A.DEBTOR_FIRST_NME) AS 'NAME',

CONCAT(A.CUR_STATUS_CDE,' ',A.CUR_STATUS_CDE_DESC) AS STATUS_DESCRIPTION, CONCAT('$',COALESCE(A.TOT_COLL_AACA ,0)) AS TOTAL_RECOVERIES,

CONCAT('$',COALESCE(A.TOT_COST_AACA,0)) AS TOTAL_COURT_COST, A.PORTFOLIO_CDE AS PORTFOLIO_CODE, A.PORTFOLIO_DESC AS 'CLIENT', A.ATTY_CDE AS ATTORNEY, A.CLIENT_CDE,

A.ATTY_FILE_NO AS ATTORNEY_FILE_NUMBER, A.SSN AS Social_Security_Number, COALESCE(A.INT_RATE,0) AS Contract_Interest, A.ACCT_OPEN_DT AS Open_Date, A.CHRGOFF_DT AS Charge_Off_Date,

A.LAST_PAY_DT AS Last_Pymt_Prior_to_Placement, A.LSTCOMMNT2 AS Original_Current_Creditor, A.ALT_ACCT_NO AS Other_Acct_Number, A.CUR_STATUS_DT AS Status_Date,

B.RMSTRANDTE AS Last_Activity_Date, A.PIPE_COLOR AS Pipe_Status, COALESCE(ROUND(A.AVG_DAYS_PLCD_SUIT_IN_STATE_CLNT_AACA,0),0) AS Avg_Days_to_Suit_in_State_for_Client,

COALESCE(ROUND(A.AVG_DAYS_PLCD_JUDG_IN_STATE_CLNT_AACA,0),0) AS Avg_Days_to_Judg_in_State_for_Client, A.PORTFOLIO_CDE AS Portfolio, A.DEBTR_STATE_AD AS State, A.ATTY_NAME,

CONCAT('$',COALESCE(TRIM(LEADING '0' FROM A.PLACEMENT_AMT),0)) AS Original_Placement_Amount,

(CASE WHEN A.ASSIGNED_DT IS NOT NULL THEN A.ASSIGNED_DT ELSE A.AACA_RCVD_DT END) AS Date_Placed, A.PLC_OF_EMPLMNT AS Place_of_Employment,

(CASE WHEN C.RMSACCTNUM IS NOT NULL THEN 'Co-Debtor Information' ELSE 'Co-Debtor Information: N/A' END) AS Co_Debtor,

(CASE WHEN D.RMSACCTNUM IS NOT NULL THEN 'YES' ELSE 'NO' END) AS Defendant_Attorney, A.LAST_PYMNT_DT_AACA AS Last_Transaction_Date,

CONCAT('$',COALESCE(A.LAST_AMT,0)) AS Last_Received_Payment, COALESCE(A.PLCD_TO_FIRST_PYMNT_DAYS_AACA,0) AS Days_to_First_Payment,

DATEDIFF(CURRENT_DATE(), A.ASSIGNED_DT) AS Days_at_Firm, A.JUDG_DT_AACA_RMSPMSTR AS Judgment_Date, CONCAT('$',COALESCE(A.JUDG_AMNT,0)) AS Judgment_Amount,

COALESCE(A.INTRST_RATE,0) AS Interest_Rate, A.CASENMBR AS CaseNo, A.RENEWAL_DT AS Renewal_Date, CONCAT(A.CUR_STATUS_CDE,' ',A.CUR_STATUS_CDE_DESC) AS Notes_History,

A.DEBTOR_LAST_NME, A.RMSFILENUM, A.PIE_DESC, CONCAT('$',COALESCE(A.TOT_COLL_FIRM,0)) AS TOTAL_RECOV_FIRM, CONCAT('$',COALESCE(A.TOT_COST_FIRM,0)) AS TOTAL_COURT_COST_FIRM,

IFNULL(A.JDGCOSTS1,0) AS JDGCOSTS1, IFNULL(A.JDGCOSTS2,0) AS JDGCOSTS2, IFNULL(A.JDGRECVRYS,0) AS JDGRECVRYS, IFNULL(A.JDGRCVINTS,0) AS JDGRCVINTS,

IFNULL(A.JUDG_DUE_PRINCPL,0) AS JUDG_DUE_PRINCPL, IFNULL(A.JUDG_DUE_INTRST,0) AS JUDG_DUE_INTRST, IFNULL(A.JUDG_DUE_COSTS,0) AS JUDG_DUE_COSTS,

IFNULL(A.JDGXCSRCVS,0) AS JDGXCSRCVS, IFNULL(A.JDGRCVCSTS,0) AS JDGRCVCSTS, IFNULL(A.JDGRECCS1,0) AS JDGRECCS1, IFNULL(A.JDGRECCS2,0) AS JDGRECCS2,

A.PLC_OF_EMPLMNT_ADD

FROM MASTER_DATA_DB A

LEFT JOIN LST_ACTVTY_HISTRY B ON A.RMSFILENUM = B.RMSFILENUM

LEFT JOIN RMSPCOMKR_BRWR C ON A.RMSACCTNUM = C.RMSACCTNUM

LEFT JOIN RMSPCOMKR_DFNDATTTY D ON A.RMSACCTNUM = D.RMSACCTNUM where ".$que1.$query." ".$NewQuerymaster."";
//echo $query1;exit;
  $result             = mysqli_query($conn,$query1);
  $row                =mysqli_fetch_array($result);
  $CLIENT_CDEenc      =$row['CLIENT_CDE'];
  $portcode           =$row['PORTFOLIO_CODE'];
  $_SESSION['CLI_CDE']= base64_encode($CLIENT_CDEenc);
  $_SESSION['port']   =base64_encode(1);
  $_SESSION['portcode']=$portcode;
  $ACCOUNT_NUMBER=$row['ACCOUNT_NUMBER'];
  $siffilenum    =$row['RMSFILENUM'];
  $checkfileno_code = "SELECT RMSFILENUM, CLIENT_CDE FROM MASTER_DATA_DB WHERE RMSFILENUM = '". $siffilenum."'";

  $resultcheckfileno = mysqli_query($conn,$checkfileno_code);
  $rowfilenum        = mysqli_fetch_array($resultcheckfileno);

  //print_r($rowfilenum['CLIENT_CDE']); exit();
  //echo $checkfileno_code; exit();

  $check_code = $rowfilenum['CLIENT_CDE'];

  // echo $check_code; exit;

  $match_code = "SELECT ORGCODE FROM SIFPIFPARMTRS WHERE ORGCODE='".$check_code."'";

  $resultmatch_code = mysqli_query($conn,$match_code);
  $rowmatch_code        = mysqli_fetch_array($resultmatch_code);

  $sif_code  = $rowmatch_code['ORGCODE'];

  // echo $sif_code; exit;

/*to send user name*/
if($_SESSION['role']==4){
  $role="Manager";  
}else if($_SESSION['role']==5){
    $role="User";
}else if($_SESSION['role']==6){
    $role="Administrator";
}else if($_SESSION['role']==7){
    $role="Accounting User";
}else if($_SESSION['role']==8){
    $role="Executive";
}else if($_SESSION['role']==9){
    $role="Collector";
}
if($_SESSION['userType']==1){
  $usertype='AACA';
  $code    ="RFCODE='' and ROCODE='' and RGROLE='".$role."'";
}if($_SESSION['userType']==2){
    $usertype='Firm';
    $code    ="RFCODE='".$_SESSION['firmCode']."' and RGROLE='".$role."'";
  
}if($_SESSION['userType']==3){
  $usertype='Organization';
  // $code    ="ORGCODE='".$_SESSION['clientCode']."' and ROLE='".$role."'";
  $code    ="ROCODE='".$_SESSION['clientCode']."' and RGROLE='".$role."'";
}if($_SESSION['userType']==4){
  $usertype='Agency';
  $code    ="RFCODE='".$_SESSION['firmCode']."' and RGROLE='".$role."'";
}
/*echo "<pre>";
print_r($_SESSION); 
echo "</pre>"; exit;*/
// $nameQuery="SELECT USERNAME FROM USER_LOGIN_CNTRL WHERE USERTYPE='".$usertype."' and ".$code." and RGEMAIL='".$_SESSION['email']."'";//echo $nameQuery;exit;
$nameQuery="SELECT RGUSER FROM WSREGUSR WHERE RUTYPE='".$usertype."' and ".$code." and PWAY2MAIL='".$_SESSION['email']."'"; //echo $nameQuery;exit;

$resultname=mysqli_query($conn,$nameQuery);
$fetchname=mysqli_fetch_assoc($resultname);

$emailencode=base64_encode($fetchname['RGUSER']);
  /*to send user name*/

  $filenum    =base64_encode($row['RMSFILENUM']);
  
  // $emailencode=base64_encode("tomf");
  // $emailencode=base64_encode("tom");
  // $emailencode=base64_encode("tombatz");

  // $emailencode=base64_encode("ahussain");

// $emailencode=base64_encode($_SESSION['email']);


$documentationquery="WITH CTE AS

(

SELECT RACTNM, SUM(CASE WHEN UPPER(DOCTYPE) LIKE '%JUDG%' OR UPPER(DOCTYPE) LIKE '%JDMT%' THEN 1 ELSE 0 END) AS JudgDocFlag,

SUM(CASE WHEN UPPER(DOCTYPE) LIKE '%PLACEMENT%' THEN 1 ELSE 0 END) AS PlacementDocFlag

FROM WFAACAIMG

WHERE RACTNM = '".$ACCOUNT_NUMBER."'

GROUP BY RACTNM

)

SELECT (CASE WHEN JudgDocFlag > 0 AND PlacementDocFlag > 0 THEN 'JudgmentPlacmentDoc'

             WHEN JudgDocFlag > 0 AND PlacementDocFlag = 0 THEN 'JudgmentDoc'

             WHEN PlacementDocFlag > 0 AND JudgDocFlag = 0 THEN 'PlacementDoc'

             WHEN PlacementDocFlag = 0 AND JudgDocFlag = 0 THEN 'No,PlacmentJudgmentDoc' ELSE NULL END) AS Final

            

FROM CTE";
  $resultdoc      = mysqli_query($conn,$documentationquery);
  $rowdoc          =mysqli_fetch_array($resultdoc);

  $address="SELECT  CONCAT_WS(' ',RMSADDR1, RMSADDR2, RMSCITY, RMSCOUNTY, RMSSTATECD, RMSZIPCODE) AS DBTR_ADD FROM RMSPMASTER WHERE RMSACCTNUM = '".$ACCOUNT_NUMBER."'";
  $resultaddress    = mysqli_query($conn,$address);
  $rowaddress       =mysqli_fetch_array($resultaddress);
  $getrecordccregstr="SELECT TOLL_FREE_NO,MAIN_PHONE,ADDR1,ADDR2,COUNTRY,STATE,CITY,ZIP_CODE from CC_REGSTR where CCODE='".$row["ATTORNEY"]."' ";//echo $getrecordccregstr;
$resgetrecordccregstr=mysqli_query($conn,$getrecordccregstr);
$fetchgetrecordccregstr=mysqli_fetch_assoc($resgetrecordccregstr);
$TOLL_FREE_NO=$fetchgetrecordccregstr['TOLL_FREE_NO'];
$MAIN_PHONE=$fetchgetrecordccregstr['MAIN_PHONE'];
$ADDR1=$fetchgetrecordccregstr['ADDR1'];
$ADDR2=$fetchgetrecordccregstr['ADDR2'];
$COUNTRY=$fetchgetrecordccregstr['COUNTRY'];
$STATE=$fetchgetrecordccregstr['STATE'];
$CITY=$fetchgetrecordccregstr['CITY'];
$ZIP_CODE=$fetchgetrecordccregstr['ZIP_CODE'];
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
      <link rel="stylesheet" href="css/PSnnect.min.css">
      <link rel="stylesheet" href="css/PSdataTables.min.css">
      <link rel="stylesheet" href="css/PSPanel.css">
      <link rel="stylesheet" href="css/PSdaterangepicker.css">
      <link rel="stylesheet" href="INV/graph.css">
      <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
      <link rel="apple-touch-icon-precomposed" sizes="114x114" href="img/fevicon.ico">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="img/fevicon.ico">
      <link rel="apple-touch-icon-precomposed" href="img/fevicon.ico">
      <link rel="shortcut icon" href="img/fevicon.ico">
      <script src="js/PSjquery.min.js"></script>
      <script src="js/PSnnect.min.js"></script>
      <script src="js/PSslimscroll.js"></script>
      <script src="js/PSnnectPanel.js"></script>
      <script src="js/sweetalert.min.js"></script>
       <link rel="stylesheet" href="css/sweetalert.css">
      <style>
        
          #currentstatusdesc {text-align: right;background: #fff;}
     .p0{padding:0px;}
         .text-white{color:#fff;}
         .pl0{padding-left:0px;}
     .pr0{padding-right:0px!important;}
         .colo-blue{color:#dde9f5;}
    .Loader {
    background:#9e9e9e26 url('/bi/dist/img/loader.gif') no-repeat 50% 50%;
    -webkit-transition: background-color 0;
    transition: background-color 0;
    opacity: 0;
    -webkit-transition: opacity 0;
    transition: opacity 0;
    height:50px;
  }
  .addrs{position: absolute;
    top: -50px;
    transform: translateY(70%) scale(0);
    transition: transform 0.1s ease-in;
    transform-origin: left;
    /*display: inline;*/
    background: #002e5b;
    z-index: 20;
    width: 180px;
    padding: 10px 10px;
    border-radius: 2px;
	text-align:left;}
    .addrs span{color:#fff;cursor:default;display:inline-block;}
    .has-details {position: relative;}
    .has-details:hover p a{color:#fff;}
    .has-details:hover p {transform: translateY(26%) scale(1);}
	.downloaddoc{position: relative;top: 4px;}
	.fa-edit{font-size:19px;}
      </style>
   </head>
   <body  class="hold-transition skin-yellow sidebar-mini fixed Loader">
       <div class="">
          <div>
             <?php if(mysqli_num_rows($result)){
      $color=$row['Pipe_Status'];
      if($color=='RED'){
        $class1="style='background-color:#F45B5B !important;color:white !important'";
 
      }else if($color=='BLUE'){
        $class1="style='background-color:#7CB5EC !important;color:white !important'";
        
      }else if($color=='GREEN'){
        $class1="style='background-color:#2B908F !important;color:white !important'";
        
      }else if($color=='ORANGE'){
        $class1="style='background-color:#F7A35C !important;color:white !important'";
        
      }else if($color=='WHITE'){
        $class1="style='background-color:#2196f314 !important;color:black !important'";
        
      }else if($color=='PURPLE'){
        $class1="style='background-color:#A891C2 !important;color:white !important'";
      
      }
         $AccountNo=str_replace("#","a",base64_encode($row['ACCOUNT_NUMBER']));
         $_SESSION['accNo']=$AccountNo;



      ?>      
            <div class="card-header account">
               <p id="accdetail" style="color:white;"><b>ACCOUNT DETAIL</b></p>
            </div>
      
      <!---Start Header--->
            <div class="row" id="accdetails">
               <div class="col-sm-12" style="padding-top: 10px;padding-bottom: 10px;">
                  <div class="col-sm-3">
                     <div>
                        <p style="color:white;border-right: 1px solid white;cursor: pointer;margin-bottom:0px!important;"><b title="<?php echo $rowaddress['DBTR_ADD'];?>"><?php echo $row['NAME'];?></b></p>
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div>
                        <p style="color:white;border-right: 1px solid white;margin-bottom:0px!important;"><b>Acct #: <?php echo $row['ACCOUNT_NUMBER'];?></b></p>
                     </div>
                  </div>
                  <div class="col-sm-3">
                     <div>
                        <p style="color:white;border-right: 1px solid white;margin-bottom:0px!important;"><b><a href="/bi/dist/CLIENTGUIDE/ViewClientGuide?CLI_CDE=<?php echo strtr(base64_encode($CLIENT_CDEenc), '+/=', '-_,');?>" style="color:white;"><?php echo $row['CLIENT'];?></a></b></p>
                     </div>
                  </div>
                  <div class="col-sm-2">
                     <div>
                        <p style="color:white;border-right: 1px solid white;margin-bottom:0px!important;"><b>Portfolio: <a href="/bi/dist/CLIENTGUIDE/ViewClientGuidesearchacc" style="color:white;" target="_blank"><?php echo $row['PORTFOLIO_CODE'];?></a></b></p>
                     </div>
                  </div>
                  <div class="col-sm-1 p0">
                     <div>
                        <p style="color:white;margin-bottom:0px!important;"><b>State: <a href="/bi/dist/State_issues/state-info?state_name=<?php echo $row['State'];?>" style="color:white;"><?php echo $row['State'];?></a></b></p>
                     </div>
                  </div>
               </div>
            </div>
      <!---END Header--->
      
      <!---Start DEBTOR INFORMATION--->
            <div class="row">
               <div class="col-sm-12 col-xs-12" id="heading1">
                  <div class="col-sm-6 col-xs-6"><b>DEBTOR INFORMATION</b></div>
                  <div class="col-sm-6 col-xs-6" style="padding-left:10px;"><b>FIRM INFORMATION</b></div>
               </div>
               <div class="col-sm-12 col-xs-12">
                  <div class="col-sm-6 col-xs-6 p0">
                     <div class="col-sm-6 col-xs-6" id="debtor">
                        <b>
                           <div class="col-sm-12 col-xs-12 p0">Social Security Number:</div>
                           <div class="col-sm-12 col-xs-12 p0">Contract Interest %:</div>
                           <div class="col-sm-12 col-xs-12 p0">Open Date:</div>
                           <div class="col-sm-12 col-xs-12 p0">Charge Off Date:</div>
                           <div class="col-sm-12 col-xs-12 p0">Last Pymt. Prior to Placement:</div>
                           <div class="col-sm-12 col-xs-12 p0">Original/Current Creditor:</div>
                           <div class="col-sm-12 col-xs-12 p0">Other Acct. Number:</div>
                        </b>
                     </div>
                     <div class="col-sm-6 col-xs-6" id="debtordesc">
                        <div class="col-sm-12 col-xs-12 p0"><?php if($row['Social_Security_Number']!=''){echo $row['Social_Security_Number'];}else{echo "--";}?></div>
                        <div class="col-sm-12 col-xs-12 p0"> <?php if($row['Contract_Interest']!=''){echo $row['Contract_Interest'];}else{echo "--";}?></div>
                        <div class="col-sm-12 col-xs-12 p0"> <?php  if(($row['Open_Date']=='0000-00-00') || ($row['Open_Date']=='')){echo "--";}else{ echo date('M d, Y', strtotime($row['Open_Date']));}?></div>
                        <div class="col-sm-12 col-xs-12 p0"> <?php  if(($row['Charge_Off_Date']=='0000-00-00') || ($row['Charge_Off_Date']=='')){echo "--";}else{ echo date('M d, Y', strtotime($row['Charge_Off_Date']));}?></div>
                        <div class="col-sm-12 col-xs-12 p0">  <?php  if(($row['Last_Pymt_Prior_to_Placement']=='0000-00-00') || ($row['Last_Pymt_Prior_to_Placement']=='')){echo "--";}else{ echo date('M d, Y', strtotime($row['Last_Pymt_Prior_to_Placement']));}?></div>
                        <div class="col-sm-12 col-xs-12 p0"> <?php if($row['Original_Current_Creditor']!=''){echo $row['Original_Current_Creditor'];}else{echo "--";}?></div>
                        <div class="col-sm-12 col-xs-12 p0">  <?php if($row['Other_Acct_Number']!=''){echo $row['Other_Acct_Number'];}else{echo "--";}?></div>
                     </div>
                  </div>
                  <div class="col-sm-6 col-xs-6 pl0">
                     <div class="col-sm-6 col-xs-6" id="firm">
                        <b>
                           <div class="col-sm-12 col-xs-12 p0"> Attorney Name:</div>
                           <div class="col-sm-12 col-xs-12 p0"> Firm File Number:</div>
                           <div class="col-sm-12 col-xs-12 p0"> Original Placement Amount:</div>
                           <div class="col-sm-12 col-xs-12 p0"> Date Placed:</div>
                           <div class="col-sm-12 col-xs-12 p0">Place of Employment:</div>
                           <div class="col-sm-12 col-xs-12 p0"> Defendant Attorney:</div>
                           <!-- Defendant Attorney:</br> -->
                           <!--  Co-Debtor Information:</br> -->
                           <div class="col-sm-12 col-xs-12 p0"> Documentation:</div>
                        </b>
                     </div>
                     <div class="col-sm-6 col-xs-6 p0" id="firmdesc">
                        <div class="col-sm-12 col-xs-12 p0 has-details"> <?php if($row['ATTY_NAME']!=''){echo $row['ATTY_NAME'];}else{echo "--";}?>
                             <p class="addrs">
                               <span class="">Toll free no: <?php echo $TOLL_FREE_NO;?></span><br>
                              <span class="">Phone: <?php echo substr($MAIN_PHONE, 0, 3).'-'.substr($MAIN_PHONE, 3, 3).'-'.substr($MAIN_PHONE,6);?> </span><br>
                              <span class="">Address1: <?php echo $ADDR1;?></span><br>
                              <span class="">Address2:<?php echo $ADDR1;?></span><br>
                              <span class="">City: <?php echo $CITY;?> </span><br>
                              <span class="">State: <?php echo $STATE;?> </span><br>
                              <span class="">Zip code: <?php echo $ZIP_CODE;?> </span> 
                             </span>
                            </p>
                        </div>
                        <div class="col-sm-12 col-xs-12 p0"><?php if($row['ATTORNEY_FILE_NUMBER']!=''){echo $row['ATTORNEY_FILE_NUMBER'];}else{echo "--";}?></div>
                        <div class="col-sm-12 col-xs-12 p0"><?php if($row['Original_Placement_Amount']!=''){echo $row['Original_Placement_Amount'];}else{echo "--";}?></div>
                        <div class="col-sm-12 col-xs-12 p0"> <?php  if(($row['Date_Placed']=='0000-00-00') || ($row['Date_Placed']=='')){echo "--";}else{ echo date('M d, Y', strtotime($row['Date_Placed']));}?></div>
                        <div class="col-sm-12 col-xs-12 p0"> <?php if($row['Place_of_Employment']==''){echo '--';}
                        else {?>
                         <a href="PlcmntEmp?acc=<?php echo urlencode($AccountNo); ?>" target="_blank"><u><?php echo $row['Place_of_Employment'];?></u></a>
                         <?php } ?> </div>
                        <div class="col-sm-12 col-xs-12 p0"><?php if($row['Defendant_Attorney']=='YES'){?>
                           <a href="AttorneyInfo?acc=<?php echo urlencode($AccountNo); ?>" target="_blank"><u>Defendant Attorney</u></a>
                           <?php }else {echo "--";}?></div>
                        <div class="col-sm-12 col-xs-12 p0">
                           <b>
                              <select style="width:130px;" id="Documentview">
                                  <?php if($rowdoc['Final']=='No,PlacmentJudgmentDoc'){?>
                                 <option value="3">Portfolio Docs</option>
                                <?php } else if($rowdoc['Final']=='PlacementDoc'){?>
                                 <option value="1">Placement Docs</option>
                                 <option value="3">Portfolio Docs</option>
                               <?php }else if($rowdoc['Final']=='JudgmentDoc'){?>
                                  <option value="2">Judgement Docs</option>
                                  <option value="3">Portfolio Docs</option>
                                  <?php } else if($rowdoc['Final']=='JudgmentPlacmentDoc'){?>
                                  <option value="1">Placement Docs</option>
                                  <option value="2">Judgement Docs</option>
                                  <option value="3">Portfolio Docs</option>
                                  <?php }else{?>
                                    <option value="3">Portfolio Docs</option>
                                  <?php } ?>

                                   <option value="4">Settlement Form</option> 

                              </select>
                              <input type="submit" name="submit" value="View" id="Documenationview">
                           </b>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
      <!---END DEBTOR INFORMATION--->
  
          <!---Start CURRENT STATUS--->
         <div class="row">
            <div class="col-sm-12 col-xs-12" id="heading1">
               <div class="col-sm-6 col-xs-6">
                  <b>CURRENT STATUS</b>
               </div>
               <div class="col-sm-6 col-xs-6" style="padding-left:10px;">
                  <b>PAYMENTS AND EXPENSES</b>
               </div>
            </div>
            <div class="col-sm-12 col-xs-12">
               <div class="col-sm-6 col-xs-6 p0">
                  <div class="col-sm-6 col-xs-6" id="currentstatus">
                     <b>
                        <div class="col-sm-12 col-xs-12 p0">Status Date:</div>
                        <div class="col-sm-12 col-xs-12 p0">Notes and History: </div>
                        <div class="col-sm-12 col-xs-12 p0">
                           <select id="NotesHistory" style="width:130px;">
                              <option value="1">Combined History</option>
                              <option value="2">Post-Placement Notes</option>
                              <option value="3">Pre-Placement Notes</option>
                              <option value="4">Status and Demographics</option>
                           </select>
                           <input type="submit" name="submit" value="View" id="NotesHistoryview" style="font-size:10px;padding:0px 2px;">
                            <a href="#" data-toggle="modal" data-target="#commentModal" class="downloaddoc" data-account='<?php echo $row['ACCOUNT_NUMBER'];?>'><i class="fa fa-edit" aria-hidden="true"></i></a>
                        </div>
                        <div class="col-sm-12 col-xs-12 p0">Last Activity Date:</div>
                        <div class="col-sm-12 col-xs-12 p0">Update Status:</div>
                        <div class="col-sm-12 col-xs-12 p0">Settlement Status:</div>
                        <div class="col-sm-12 col-xs-12 p0">Pipe Status:</div>
                      
                     </b>
                  </div>
                  <div class="col-sm-6 col-xs-6" id="currentstatusdesc">
                     <div class="col-sm-12 col-xs-12 p0"><?php  if(($row['Status_Date']=='0000-00-00') || ($row['Status_Date']=='')){echo "--";}else{ echo date('M d, Y', strtotime($row['Status_Date']));}?></div>
                     <div class="col-sm-12 col-xs-12 p0"> <?php if($row['Notes_History']!=''){echo $row['Notes_History'];}else{echo '--';}?> </div>
                     <div class="col-sm-12 col-xs-12 p0 text-white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                     <div class="col-sm-12 col-xs-12 p0"> <?php  if(($row['Last_Activity_Date']=='0000-00-00') || ($row['Last_Activity_Date']=='')){echo "--";}else{ echo date('M d, Y', strtotime($row['Last_Activity_Date']));}?></div>

                     <div class="col-sm-12 col-xs-12 p0"> <a href="StatusUpdate?acc=<?php echo urlencode($AccountNo); ?>" target="_blank">Click Here</a></div>
                     <!-- Settlement Form URL -->  
                     <div class="col-sm-12 col-xs-12 p0">
                              <?php 

                              if($check_code ==  $sif_code ){ ?>

                                <a href="Settlement_Form/settlement-request?acc=<?php echo urlencode($AccountNo); ?>" target="_blank">Click Here</a></br>
                              <?php  }
                                else{
                                  echo "<br>";
                                }
                              
                              ?>
                     </div>
                     <div class="col-sm-12 col-xs-12 p0"> <span <?php echo $class1;?>><?php if($row['PIE_DESC']!=''){echo $row['PIE_DESC'];}else{echo '--';}?></span></div>
                  </div>
               </div>
               <div class="col-sm-6 col-xs-6 pl0">
                  <div class="col-sm-6 col-xs-6" id="Paymexp">
                     <b>
                        <div class="col-sm-12 col-xs-12 p0">Last Transaction Date:</div>
                        <div class="col-sm-12 col-xs-12 p0">Last Received Payment:</div>
                        <div class="col-sm-12 col-xs-12 p0">Days to First Payment:</div>
                        <div class="col-sm-12 col-xs-12 p0">Days at Firm:</div>
                        <div class="col-sm-12 col-xs-12 p0">Total Recoveries:</div>
                        <div class="col-sm-12 col-xs-12 p0">Total Court Cost:</div>
                        <div class="col-sm-12 col-xs-12 p0 colo-blue">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                     </b>
                  </div>
                  <div class="col-sm-6 col-xs-6 p0" id="Paymexpdesc">
                     <div class="col-sm-12 col-xs-12 p0"> <?php  if(($row['Last_Transaction_Date']=='0000-00-00') || ($row['Last_Transaction_Date']=='')){echo "--";}else{ echo date('M d, Y', strtotime($row['Last_Transaction_Date']));}?></div>
                     <div class="col-sm-12 col-xs-12 p0"> <?php if($row['Last_Received_Payment']!=''){echo $row['Last_Received_Payment'];}else{echo '--';}?></div>
                     <div class="col-sm-12 col-xs-12 p0"><?php if($row['Days_to_First_Payment']!=''){echo $row['Days_to_First_Payment'];}else{echo '--';}?></div>
                     <div class="col-sm-12 col-xs-12 p0"><?php if($row['Days_at_Firm']!=''){echo $row['Days_at_Firm'];}else{echo '--';}?></div>
                     <b>
                        <div class="col-sm-12 col-xs-12 p0">
                           <select id="FirmClientView">
                              <option value="1">Firm View</option>
                              <option value="2">Client View</option>
                           </select>
                          <?php if($row['TOTAL_RECOVERIES']=='' || $row['TOTAL_RECOVERIES']=='$0.00' ){echo $row['TOTAL_RECOVERIES'];}
                                   else {?>
                                  <span id="Totalrecoveries" style="color:#94b8d4;cursor: pointer;"><u><?php echo $row['TOTAL_RECOVERIES'];?></u></a></span>
                                   
                                 <?php } ?> 
                        </div>
                        <div class="col-sm-12 col-xs-12 p0">
                           <span id="courtcost" style="color:#94b8d4;cursor: pointer;">  <?php if($row['TOTAL_COURT_COST']=='' || $row['TOTAL_COURT_COST']=='$0.00' ){echo $row['TOTAL_COURT_COST'];}else {?>
                                  <span id="courtcost" style="color:#94b8d4;cursor: pointer;"><u><?php echo $row['TOTAL_COURT_COST'];?></u></a></span>
                                   
                                 <?php } ?> </span>
                        </div>
                        <div class="col-sm-12 col-xs-12 p0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
                     </b>
                  </div>
               </div>
            </div>
            <b>
            </b>
         </div>
     <!---END CURRENT STATUS--->
         <!---START STATISTICS STATUS--->
            <div class="row" >
               <div class="col-sm-12 col-xs-12"id="heading1">
                  <div class="col-sm-6 col-xs-6">
                        <b>STATISTICS</b>
                  </div>
                  <div class="col-sm-6 col-xs-6" style="padding-left:10px;">
                        <b>JUDGMENT INFORMATION</b>
                  </div>
               </div>
         
            <div class="col-sm-12 col-xs-12 p0">
      <div class="col-sm-4 col-xs-4 pr0">
               <div class="col-sm-9 col-xs-9" id="statistic">
                  <b>
                  <div class="col-sm-12 col-xs-12 p0">Avg. Days to Suit in State for Client: </div>
                  <div class="col-sm-12 col-xs-12 p0"> Avg. Days to Judg. in State for Client:</div>
                  </b>
               </div>
               <div class="col-sm-3 col-xs-3 pr0" id="statisticdesc"> 
                   <?php if($row['Avg_Days_to_Suit_in_State_for_Client']!=''){echo $row['Avg_Days_to_Suit_in_State_for_Client'];}else{echo '--';}?><br>
                  <?php if($row['Avg_Days_to_Judg_in_State_for_Client']!=''){echo $row['Avg_Days_to_Judg_in_State_for_Client'];}else{echo '--';}?><br>
               </div></div>
         <div class="col-sm-4 col-xs-4 pr0">
               <div class="col-sm-9 col-xs-9 pr0" id="judgement">
                  <b>
                  <div class="col-sm-12 col-xs-12 p0">Judgment Date</div>
                  <div class="col-sm-12 col-xs-12 p0">Judgment Amount:</div>
                  </b>
               </div>
               <div class="col-sm-3 col-xs-3 pr0" id="judgementdesc">
                  <div class="col-sm-12 col-xs-12 p0"> <?php  if(($row['Judgment_Date']=='0000-00-00') || ($row['Judgment_Date']=='')){echo "--";}else{ echo date('M d, Y', strtotime($row['Judgment_Date']));}?></div> 
                  <div class="col-sm-12 col-xs-12 p0"> <?php if($row['Judgment_Amount']=='$0.00'){echo $row['Judgment_Amount'];}
                  else {?>
                    <a href="jugBreakdown?acc=<?php echo urlencode($AccountNo); ?>" target="_blank"><u><?php echo $row['Judgment_Amount'];?></u></a>
                    <?php } ?> </div>
               </div>
         </div>
         <div class="col-sm-4 col-xs-4">
               <div class="col-sm-9 col-xs-9" id="interestrate">
                  <b>
                  <div class="col-sm-12 col-xs-12 p0">Interest Rate:</div>
                  <div class="col-sm-12 col-xs-12 p0">Case #:       </div>
                  <div class="col-sm-12 col-xs-12 p0">Renewal Date:</div>
                  </b>
               </div>
               <div class="col-sm-3 col-xs-3" id="interestratedesc">
                 <div class="col-sm-12 col-xs-12 p0"><?php if($row['Interest_Rate']!=''){echo $row['Interest_Rate'];}else{echo '--';}?></div>
                 <div class="col-sm-12 col-xs-12 p0"><?php if($row['CaseNo']!=''){echo $row['CaseNo'];}else{echo '--';}?></div> 
                 <div class="col-sm-12 col-xs-12 p0"> <?php  if(($row['Renewal_Date']=='0000-00-00') || ($row['Renewal_Date']=='')){echo "--";}else{ echo date('M d, Y', strtotime($row['Renewal_Date']));}?></div>
               </div>
         </div>

            </div>
      </div>
       <!---END STATISTICS STATUS--->
        </div>
         <?php } else{
                         $msg="No record found";
                        echo "<h1 style='color: red;text-align: center; margin-top: 173px;margin-left: 355px;}'>" . $msg . "</h1>";

                      }?>
      </div>
      </div>
      </div>
      </div>
      <!--main content ends here -------------------------------------------------->
    
      <div class="control-sidebar-bg"></div>
      </div>
       <!--Edit Modal -->
<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-center" id="lineModalLabel">
                    Notes
                </h3>
            </div>
            <div id="modal-body">
                   
                  <div class="form-group text-center clearfix" style="padding: 50px;" >
             
                  <textarea  id="Notesshare" name="Notesshare" class="form-control" placeholder="Notes"></textarea>
                   <div id="addidaccount"></div> 
                    <span class="error" id="notesError"></span> 
                </div>
               
                         
            </div>
            <div class="modal-footer">
                    <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                        <div class="btn-group" role="group">
                            <button type="submit" name ="btnNotesshare" id="btnNotesshare"  class="btn btn-default btn-hover-green btm-right-radius" role="button">Submit</button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-default btm-left-radius" data-dismiss="modal" role="button">Close</button>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
     <!--end mpodal------------------------>
   </body>
   <script type="text/javascript">
      var body = document.getElementsByTagName('body')[0];
      var removeLoading = function() {
        setTimeout(function() {
          body.className = body.className.replace(/Loader/, '');
        }, 300);
      };
      removeLoading();
  </script> 
    <script>

$(document).ready(function(){
    $('[data-toggle="popover"]').popover();
     $('.downloaddoc').click(function(){
    $("#accountid").remove();
   var account    =$(this).data('account');
   $('#addidaccount').append('<input type="hidden" id="accountid" value="'+account+'">');
  
  });
});
  function validate1(){

  var flag = true;
  $('#notesError').css('display', 'none');
  var Notesshare  =$('#Notesshare').val();

  if(Notesshare=='') {
    $('#notesError').text('Notes can not be left blank');
    $('#notesError').css('display', 'block');
        flag = false;
    }
    

 if (flag) {
    return true;
 }else{
    return false;
 }
}
  $('#btnNotesshare').on('click', function(e, params) {
    var spinner = $('#loader');
    var localParams = params || {};
    if (!localParams.send) {
        e.preventDefault();
        
  

    }
if(validate1()){
spinner.show();
  var accountid  =$('#accountid').val();
  var Notesshare =$('#Notesshare').val();
    $.ajax({
      url: 'notesAjax.php',
      type: 'post',
      data:{accountid:accountid,Notesshare:Notesshare},
      
    success: function(response){ 
      if(response==1){
     
         $('#commentModal').modal('hide');
                spinner.hide();
    
            swal({title: "",
                text: "Notes added successfully",
                timer: 4000,
                showConfirmButton: false,
                type: 'success'
              },
             function(){ 
                location.reload();
             });

     } 

    }
  });
}

});
</script> 
   <script>
  $(document).ready(function(){
    $('#NotesHistoryview').on('click', function() {
     var id= $('#NotesHistory').val();
     var accNo='<?php echo $AccountNo;?>';
// AH18052026 - Added account parameter for multi-tab support
if(id==1){
 window.open('combinedHist?acc=' + encodeURIComponent(accNo),'_blank');
}
if(id==2){
 window.open('postPlacement?acc=' + encodeURIComponent(accNo),'_blank');
}
if(id==3){
 window.open('prePlacement?acc=' + encodeURIComponent(accNo),'_blank');
}
if(id==4){
 window.open('statusDemo?acc=' + encodeURIComponent(accNo),'_blank');
}
  });
});
  $(document).ready(function(){
    $('#Documenationview').on('click', function() {
     var id= $('#Documentview').val();
     var accNo='<?php echo $AccountNo;?>';
// AH18052026 - Added account parameter for multi-tab support
if(id==1){
 window.open('PlcmntDocs?acc=' + encodeURIComponent(accNo),'_blank');
}
if(id==2){
 window.open('JudgmentDocs?acc=' + encodeURIComponent(accNo),'_blank');
}
if(id==3){
 window.open('/bi/dist/CLIENTGUIDE/ViewClientGuidesearchacc?acc=' + encodeURIComponent(accNo),'_blank');
}
if(id==4){
 window.open('Settlement_Form/settlement-request?acc=' + encodeURIComponent(accNo),'_blank');
}
  });


   $('#courtcost').on('click', function() {  
      var id= $('#FirmClientView').val();
      var accNo='<?php echo $AccountNo;?>';
      var CourtCost='<?php echo $row['TOTAL_COURT_COST'];?>';
// AH18052026 - Added account parameter for multi-tab support
if(id==1 && CourtCost >'$0.00'){
 window.open('FirmCost?acc=' + encodeURIComponent(accNo),'_blank');
}
if(id==2 && CourtCost >'$0.00'){
 window.open('ClientCost?acc=' + encodeURIComponent(accNo),'_blank');
}

     });

     $('#Totalrecoveries').on('click', function() {  
      var id= $('#FirmClientView').val();
      var accNo='<?php echo $AccountNo;?>';
      var recoveries='<?php echo $row['TOTAL_RECOVERIES'];?>';
// AH18052026 - Added account parameter for multi-tab support
if(id==1 && recoveries >'$0.00'){
 window.open('FirmRecoveries?acc=' + encodeURIComponent(accNo),'_blank');
}
if(id==2 && recoveries >'$0.00'){
 window.open('ClientRecoveries?acc=' + encodeURIComponent(accNo),'_blank');
}

     });
});

  </script>
  <script type="text/javascript">
    let autoAccept = sessionStorage.getItem("autoAccept");
   
    if(autoAccept != null)
    {
     swal({title: "Submitted!",
                text: "AUTO ACCEPTED : The offer you have submitted is auto accepted.",
                timer: 5000,
                showConfirmButton: false,
                type: 'success'
              });
    }
    sessionStorage.clear();
   </script>
</html>