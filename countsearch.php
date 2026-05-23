 <?php
include('config.php');
session_start();
/* ============================================================================
 * ACCOUNT SEARCH (COUNTSEARCH.php)
 * ============================================================================
 * Author: KEANT Technologies              Date: 18 MAY 2026
 *
 * Purpose:
 * This module validates account search requests and controls account
 * search result handling before Account Detail navigation.
 *
 * Previous Behavior:
 * - Current account stored globally in PHP SESSION
 * - Shared session caused cross-tab account overwrite
 *
 * New Behavior:
 * - Session overwrite logic removed
 * - Account context now handled through request parameters
 * - Supports independent multi-tab account handling
 *
 * Key Changes:
 * - Removed $_SESSION['accountno'] overwrite
 * - Removed $_SESSION['statusupdateaccno'] overwrite
 * - Preserved existing validation and filtering logic
 *
 * Change Tag:
 * ----------------------------------------------------------------------------
 * AH18052026 | Removed shared session account overwrite
 * ----------------------------------------------------------------------------
 * ============================================================================ */

  $atty_cde     =$_SESSION['firmCode'];
  $client_cde   =$_SESSION['clientCode'];

  //AH18052026 : Start
  $newattycode   = $_SESSION['newattycode'] ?? '';
  $newclientcode = $_SESSION['newclientcode'] ?? '';
  $newusertype   = $_SESSION['newuserType'] ?? '';
  //AH18052026 : End
  
  $portfoliocode=$_SESSION['portfolioCode'];
  $productCode  =$_SESSION['productCode'];
  $statecode    =$_SESSION['state'];

  //AH18052026 : Start
 if(($newusertype==2 || $newusertype==4) && $_SESSION['userType']==1){
  //AH18052026 : End

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

//AH18052026 : Start
 if($newusertype==3 && $_SESSION['userType']==1){
//AH18052026 : End

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
$NewQuery="AND ".$atty_cdenew." AND ".$client_cdenew." AND ".$portfoliocodenew." AND ".$statecodenew." AND ".$productCodenew;
$NewQuerymaster="AND ".$attymaster." AND ".$client_cdenew." AND ".$portmaster." AND ".$productCodenew." AND ".$statemaster;

//AH18052026 : Start
  if($_SESSION['userType'] == 1 && $newusertype == 1){
//AH18052026 : End
    $table ="SEARCH_QUERY_DATA";
  }else if($_SESSION['userType'] == 1 && $newusertype == ''){  //AH18052026
    $table ="SEARCH_QUERY_DATA";
  }else if(($_SESSION['userType'] == 2) || ($_SESSION['userType'] == 4) ){
    $table ="SEARCH_QUERY_DATA_F";
  }else if($_SESSION['userType'] == 3){
    $table="SEARCH_QUERY_DATA";
  }else if($newusertype == 2 || $newusertype == 4 && $_SESSION['userType'] == 1){  //AH18052026
    $table="SEARCH_QUERY_DATA_F";
}else if($newusertype == 3 && $_SESSION['userType'] == 1){   //AH18052026
  $table="SEARCH_QUERY_DATA";
  }
/*advance search starts here=======================================================*/
if (($_POST['advancenameacc'] ?? '') != ''){
$name1         =strtoupper($_POST['advancenameacc']);
$name          =strtoupper(trim(str_replace(",","",$name1)));
if($_SESSION['userType']==2){
$count_query = "SELECT count(1) as allcount FROM ".$table." WHERE  (ATTORNEY='".$atty_cde."') AND (NAME REGEXP '".$name."'OR ACCOUNT_NUMBER REGEXP '".$name."' OR ATTORNEY_FILE_NUMBER REGEXP '".$name."') ".$NewQuery."";

}else if($_SESSION['userType']==3){
  $count_query = "SELECT count(1) as allcount FROM ".$table." WHERE (CLIENT_CDE='".$client_cde."') AND (NAME REGEXP '".$name."'OR ACCOUNT_NUMBER REGEXP '".$name."' OR ATTORNEY_FILE_NUMBER REGEXP '".$name."') ".$NewQuery."";
}else if($newusertype==2 && $_SESSION['userType']==1){  //AH18052026

 $count_query = "SELECT count(1) as allcount  FROM ".$table." WHERE (ATTORNEY='".$newattycode."') AND  (NAME REGEXP '".$name."'OR ACCOUNT_NUMBER REGEXP '".$name."' OR ATTORNEY_FILE_NUMBER REGEXP '".$name."') ".$NewQuery."";
 }else if($newusertype==3 && $_SESSION['userType']==1){  //AH18052026
 
 $count_query = "SELECT count(1) as allcount  FROM ".$table."  WHERE (CLIENT_CDE='".$newclientcode."') AND (NAME REGEXP '".$name."'OR ACCOUNT_NUMBER REGEXP '".$name."' OR ATTORNEY_FILE_NUMBER REGEXP '".$name."') ".$NewQuery."";

}else if($_SESSION['userType']==4){

$count_query = "SELECT count(1) as allcount  FROM ".$table." WHERE (ATTORNEY='".$atty_cde."') AND (NAME REGEXP '".$name."'OR ACCOUNT_NUMBER REGEXP '".$name."' OR ATTORNEY_FILE_NUMBER REGEXP '".$name."') ".$NewQuery."";
}else if($newusertype==4 && $_SESSION['userType']==1){  //AH18052026
 
  $count_query = "SELECT count(1) as allcount  FROM ".$table." WHERE  (ATTORNEY='".$newattycode."') AND (NAME REGEXP '".$name."'OR ACCOUNT_NUMBER REGEXP '".$name."' OR ATTORNEY_FILE_NUMBER REGEXP '".$name."') ".$NewQuery."";
 
}else{
  
  $count_query = "SELECT count(1) as allcount FROM ".$table." WHERE NAME REGEXP '".$name."'OR ACCOUNT_NUMBER REGEXP '".$name."' OR ATTORNEY_FILE_NUMBER REGEXP '".$name."'"; 
 
}
//echo $count_query;exit;
$count_result = mysqli_query($conn,$count_query);
$count_fetch = mysqli_fetch_array($count_result);
$postCount = $count_fetch['allcount'];
echo $postCount;
$_SESSION['advancenameacc']=$name;
$_SESSION['name']='';
$_SESSION['accountno']='';
$_SESSION['statusupdateaccno']=$name;
}
/*advance search ends here=======================================================*/


/*name search starts here=======================================================*/
// if ($_POST['namesearch']!=''){
if (($_POST['namesearch'] ?? '') != ''){
$name1         =strtoupper($_POST['namesearch']);
$name          =trim(str_replace(",","",$name1));
if($_SESSION['userType']==2 || $_SESSION['userType']==4){
$query="AND ATTY_CDE='".$atty_cde."' ";
}else if($_SESSION['userType']==3){
$query="AND CLIENT_CDE='".$client_cde."' ";
}else if($newusertype==2 || $newusertype==4){ //AH18052026
$query="AND ATTY_CDE='".$newattycode."' ";
}else if($newusertype==3){  //AH18052026
 $query="AND CLIENT_CDE='".$newclientcode."' ";
}else{
  $query = " ";
}
 if($_SESSION['userType']==2){
$count_query = "SELECT count(1) as allcount FROM ".$table." WHERE NAME LIKE '%".$name."%' AND ATTORNEY='".$atty_cde."' ".$NewQuery."";

}else if($_SESSION['userType']==3){
  $count_query = "SELECT count(1) as allcount FROM ".$table." WHERE NAME LIKE '%".$name."%' AND CLIENT_CDE='".$client_cde."' ".$NewQuery."";

}else if($newusertype==2){  //AH18052026
 $count_query = "SELECT count(1) as allcount FROM ".$table." WHERE NAME LIKE '%".$name."%' AND ATTORNEY='".$newattycode."' ".$NewQuery."";

}else if($newusertype==3){  //AH18052026
 $count_query = "SELECT count(1) as allcount FROM ".$table." WHERE NAME LIKE '%".$name."%' AND CLIENT_CDE='".$newclientcode."' ".$NewQuery."";

}else if($_SESSION['userType']==4){
$count_query = "SELECT count(1) as allcount FROM ".$table." WHERE NAME LIKE '%".$name."%' AND ATTORNEY='".$atty_cde."' ".$NewQuery." ";

}else if($newusertype==4){ //AH18052026
  $count_query = "SELECT count(1) as allcount FROM ".$table." WHERE NAME LIKE '%".$name."%' AND ATTORNEY='".$newattycode."' ".$NewQuery."";

}else{
  $count_query = "SELECT count(1) as allcount FROM ".$table." WHERE NAME LIKE '%".$name."%' ";

} 
$count_result = mysqli_query($conn,$count_query);
$count_fetch = mysqli_fetch_array($count_result);
$postCount = $count_fetch['allcount'];
echo $postCount;
$_SESSION['advancenameacc']='';
$_SESSION['name']=$name;
$_SESSION['accountno']='';
}

/*name search ends here=======================================================*/


/*account search starts here=======================================================*/
// if ($_POST['accountnosearch']!=''){
if (($_POST['accountnosearch'] ?? '') != ''){  //AH18052026
$accountno=$_POST['accountnosearch'];
$acntnoupper=strtoupper($accountno);
$que1     ="UPPER(RMSACCTNUM) ='".$acntnoupper."'";
if($_SESSION['userType']==2 || $_SESSION['userType']==4){
$query="AND ATTY_CDE='".$atty_cde."' ";
}else if($_SESSION['userType']==3){
$query="AND CLIENT_CDE='".$client_cde."' ";
// }else if($_SESSION['newuserType']==2 || $_SESSION['newuserType']==4){
}else if($newusertype==2 || $newusertype==4){  //AH18052026
$query="AND ATTY_CDE='".$newattycode."' ";
// }else if($_SESSION['newuserType']==3){ AH18052026
}else if($newusertype==3){
 $query="AND CLIENT_CDE='".$newclientcode."' ";
}else{
  $query = " ";
}
$count_query="SELECT count(1) as allcount FROM MASTER_DATA_DB  where ".$que1.$query." ".$NewQuerymaster." ";
// echo $count_query;exit;
$count_result = mysqli_query($conn,$count_query);
$count_fetch = mysqli_fetch_array($count_result);
$postCount = $count_fetch['allcount'];
echo $postCount;
$_SESSION['advancenameacc']=''; 
$_SESSION['name']='';
$_SESSION['accountno']=$accountno;
$_SESSION['pagename']=$_POST['page'];
$_SESSION['statusupdateaccno']=$accountno;
}
exit;

/*account search ends here=======================================================*/