    <?php
    session_start();
    error_reporting(0);
    include '../config.php';
     if(!isset($_SESSION['email']))
    {
      header('Location: ../logout.php');
      exit();
      }
    function client_code($conn)
    {
    $output = '';
    $sql = "SELECT * FROM CG_Data";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $output .= '<option value="' . $row["client_code"] . '">' . $row["client_code"] . '</option>';
    }
    return $output;
    }   

$acc =$_SESSION['rmsfilenumenc'];
$accountno=$_SESSION['statusupdateaccno'];


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
  $productCodenew1       = "'" . implode("', '", $productCodenew) ."'";
  $productCodenew        ='PRODUCT_CDE IN ('.$productCodenew1.')';
}
if($statecode=='ALL' || $statecode==''){
  $statecodenew='State=State';
  $statemaster  ='DEBTR_STATE_AD =DEBTR_STATE_AD';
}else{
  $statecodenews       = explode(",", $_SESSION['state']);
  $statecodenew1       = "'" . implode("', '", $statecodenew) ."'";
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
$query1="SELECT DISTINCT  A.CLIENT_CDE,A.PORTFOLIO_CDE AS PORTFOLIO_CODE
         FROM MASTER_DATA_DB A
         where ".$que1.$query." ".$NewQuerymaster."";
//echo $query1;exit;
  $result             = mysqli_query($conn,$query1);
  $row                =mysqli_fetch_array($result);
  $clicde             =base64_encode($row['CLIENT_CDE']);
  $portcode           =$row['PORTFOLIO_CODE'];

  $_SESSION['port']   =base64_encode(1);
  $_SESSION['portcode']=base64_encode($portcode);

    $_SESSION['CLI_CDE']=  $clicde;
    $getclicode=base64_decode(strtr($clicde, '-_,', '+/='));
    //$getport=base64_decode($_GET['portcode']);
      $getport=base64_decode($_SESSION['portcode']);



          $sql = "SELECT A.*,
  
    B.Suit_Authority_Text, C.Document_Text,D.TextDescription AS handling_sol,
                    D1.TextDescription as prior_authority_text,D2.TextDescription as handling_bankruptcy,D3.TextDescription AS handling_disability,D4.TextDescription AS handling_probate,D5.TextDescription AS handling_special_procedures,D6.TextDescription AS handling_counterclaims,D7.TextDescription AS handling_conflict,D8.TextDescription AS handling_military,D9.TextDescription AS handling_complaints,GC.communication_text,D10.TextDescription as handling_relocation,D11.TextDescription as handling_closures,D12.TextDescription as reporting_requirements,D13.TextDescription as application_of_funds_code,D14.TextDescription as mediarequest,D15.TextDescription as witnesstext,GC1.communication_text as documentTransfer,IAF.Text_Heading,IAF.Text_Description,
                   
                    (CASE WHEN A.Client_Guide = 'AACANet Preferred' THEN 'Client Guide' ELSE '' END) AS Client_Guide,
                    (CASE WHEN A.Portfolio_Specific_Information = 'AACANet Preferred' THEN 'Portfolio Specific Information' ELSE '' END) AS Portfolio_Specific_Information,
                    (CASE WHEN A.Allowcation_of_Funds = 'AACANet Preferred' THEN 'Allocation of Funds' ELSE '' END) AS Allowcation_of_Funds,
                    (CASE WHEN A.Skip_Tracing_Assistance = 'AACANet Preferred' THEN 'Skip Tracing Assistance' ELSE '' END) AS Skip_Tracing_Assistance,
                     (CASE WHEN A.Consumer_Re_Locates = 'AACANet Preferred' THEN 'Consumer Re-Locates' ELSE '' END) AS Consumer_Re_Locates,
                     (CASE WHEN A.Account_Closures_Re_Open_Requests = 'AACANet Preferred' THEN 'Consumer Closures/Re-Open Requestes' ELSE '' END) AS Account_Closures_Re_Open_Requests,
                    (CASE WHEN A.Monthly_Reporting_Requirements = 'AACANet Preferred' THEN 'Monthly Reporting Requirements' ELSE '' END) AS Monthly_Reporting_Requirements,
                    (CASE WHEN A.Client_Set_Up = 'AACANet Preferred' THEN 'Client Setup' ELSE '' END) AS Client_Set_Up,
                    (CASE WHEN A.Conflicts_of_Interest = 'AACANet Preferred' THEN 'Conflict of Interest' ELSE '' END) AS Client_Set_Up,
                    (CASE WHEN A.AACA_Contract_Issues = 'AACANet Preferred' THEN 'AACA Contract Issues' ELSE '' END) AS AACA_Contract_Issues,
                    (CASE WHEN A.Authentication_Documents = 'My Uploads' THEN 'Authentication Documents' ELSE '' END) AS Authentication_Documents,
                   
                    (CASE WHEN A.Probate_Handling = 'My Uploads' THEN 'Probate Handling' ELSE '' END) AS Probate_Handling,
                    (CASE WHEN A.Dispute_Handling = 'My Uploads' THEN 'Dispute Handling' ELSE '' END) AS Dispute_Handling,
                    (CASE WHEN A.Answers_or_Counterclaims = 'My Uploads' THEN 'Answer Or Counterclaims' ELSE '' END) AS Answers_or_Counterclaims,
                    (CASE WHEN A.Client_authorization = 'My Uploads' THEN 'Client Authorization' ELSE '' END) AS Client_authorization,
                    (CASE WHEN A.Settlement_Proposals_and_Arrangements = 'My Uploads' THEN 'Settlement Proposals And Arrangements' ELSE '' END) AS Settlement_Proposals_and_Arrangements,

                    A.suit_notification_flag,A.judgment_notification_flag,A.inactive_status_notice_flag,A.acknowledgement_type,A.remittance_cycle,A.remittance_type,A.cost_rebill,A.move_out_of_state_flag,A.funds_hold_permitted,A.set_aside_flag,A.interest_pre_suit,A.service_failure_flag,E.Affidavit_Requirement_Options,

                        SP.SIF_PIF_Text as stipulation_text,SP.Test_Desctiption as stipulation_desc,
                        SP1.SIF_PIF_Text as garnishment_text,SP1.Test_Desctiption as garnishment_desc,
                        SP2.SIF_PIF_Text as interest_text,SP2.Test_Desctiption as interest_desc,
                        SP3.SIF_PIF_Text as portal_text,SP3.Test_Desctiption as portal_desc,
                        SP4.SIF_PIF_Text as Portfolio_text,SP4.Test_Desctiption as portfolio_desc,SP5.SIF_PIF_Text as sipiftext_desc

                    FROM CG_Data A

                    LEFT JOIN CG_SIF_PIF_Text SP ON SP.Test_Desctiption = CONCAT('Stip_',A.stipulation_flag)
                    LEFT JOIN CG_SIF_PIF_Text SP1 ON SP1.Test_Desctiption = CONCAT('Garn_',A.garnishment_flag)
                    LEFT JOIN CG_SIF_PIF_Text SP2 ON SP2.Test_Desctiption = CONCAT('Interest_',A.interest_flag)
                    LEFT JOIN CG_SIF_PIF_Text SP3 ON SP3.Test_Desctiption = CONCAT('Portal_',A.Portal_flag)
                    LEFT JOIN CG_SIF_PIF_Text SP4 ON SP4.Test_Desctiption = CONCAT('Portfolio_',A.Sif_Pif_alt_by_portfolio_flag)
                    LEFT JOIN CG_SIF_PIF_Text SP5 ON SP5.Test_Desctiption = CONCAT('SIF/PIF_',A.SIFPIFYN)

                    LEFT JOIN CG_Special_text D ON D.TextTitle = A.handling_sol
                    LEFT JOIN CG_Special_text D1 ON D1.TextTitle = A.prior_authority_text
                    LEFT JOIN CG_Special_text D2 ON D2.TextTitle = A.handling_bankruptcy
                    LEFT JOIN CG_Special_text D3 ON D3.TextTitle = A.handling_disability
                    LEFT JOIN CG_Special_text D4 ON D4.TextTitle = A.handling_probate
                    LEFT JOIN CG_Special_text D5 ON D5.TextTitle = A.handling_special_procedures
                    LEFT JOIN CG_Special_text D6 ON D6.TextTitle = A.handling_counterclaims
                    LEFT JOIN CG_Special_text D7 ON D7.TextTitle = A.handling_conflict
                    LEFT JOIN CG_Special_text D8 ON D8.TextTitle = A.handling_military
                    LEFT JOIN CG_Special_text D9 ON D9.TextTitle = A.handling_complaints
                    LEFT JOIN CG_Special_text D10 ON D10.TextTitle = A.handling_relocation
                    LEFT JOIN CG_Special_text D11 ON D11.TextTitle = A.handling_closures
                    LEFT JOIN CG_Special_text D12 ON D12.TextTitle = A.reporting_requirements
                    LEFT JOIN CG_Special_text D13 ON D13.TextTitle = A.application_of_funds_code
                    LEFT JOIN CG_Communication_Text GC ON GC.communication_request = A.Communication_Request
                    LEFT JOIN CG_Communication_Text GC1 ON GC1.communication_request = A.Document_Transfer
                    LEFT JOIN CG_Special_text D14 ON D14.TextTitle = A.media_request_text
                    LEFT JOIN CG_Special_text D15 ON D15.TextTitle = A.witness_text
                    LEFT JOIN CG_Suit_Authority B ON A.Suit_authority_option = B.Description
                    LEFT JOIN CG_Document_Text C ON A.Placement_Document_Text_Options = C.Description
                    LEFT JOIN CG_Affidavit_Text E ON A.Affidavit_Requirement_Options=E.Text_Desctiption
                    LEFT JOIN CG_Interest_attorney_fees IAF ON IAF.Text_Heading=A.INTEREST_ATTORNEY_FESS
                    WHERE A.client_code = '".$getclicode."'";//echo $sql;exit;
                $result              = mysqli_query($conn, $sql); 
                $row                 = mysqli_fetch_array($result);
                $prior_authority_text=$row['prior_authority_text'];
                $media_request_text  =$row['mediarequest'];
                $witness_text        =$row['witnesstext'];
                $editeddate          =date('Y-m-d',strtotime($row['editeddate']));
     /*to find debt buyer list*/
     $bebtquery="SELECT DISTINCT RMSBRGLVL2
                FROM RMSPSYSASN
                WHERE RMSBRGLVL3 = 'BTP'";  
     $debtresult =mysqli_query($conn,$bebtquery); 
    
   /* to find out bos for client guide exist or not*/
   $checkbosquery="SELECT count(1) as total from CG_BOS_Matrix WHERE CLIENT_CDE = '".$getclicode."'";  
   $checkbosresult =mysqli_query($conn,$checkbosquery); 
   $rescheckbosresult=mysqli_fetch_assoc($checkbosresult);
/* for client contact information=============================================================================*/
$selectccregQuery="SELECT CCODE,TAXID,CNAME,RFNAME,RLNAME,RANAME,REGEMAIL,ADDR1,ADDR2,COUNTRY,STATE,CITY,ZIP_CODE,MAIN_PHONE,TOLL_FREE_NO,FAX_NUM,WEBADDR from  Client_Contact_Information where CCODE='".$getclicode."'";
$resccregQuery=  mysqli_query($conn, $selectccregQuery); 
if(mysqli_num_rows($resccregQuery)>0){
  $selectccregQuery1="SELECT CCODE,TAXID,CNAME,RFNAME,RLNAME,RANAME,REGEMAIL,ADDR1,ADDR2,COUNTRY,STATE,CITY,ZIP_CODE,MAIN_PHONE,TOLL_FREE_NO,FAX_NUM,WEBADDR from  Client_Contact_Information where CCODE='".$getclicode."'";
   
}else{
   $selectccregQuery1="SELECT CCODE,TAXID,CNAME,RFNAME,RLNAME,RANAME,REGEMAIL,ADDR1,ADDR2,COUNTRY,STATE,CITY,ZIP_CODE,MAIN_PHONE,TOLL_FREE_NO,FAX_NUM,WEBADDR from  CC_REGSTR where CCODE='".$getclicode."'";
}
$resccregQuery1=  mysqli_query($conn, $selectccregQuery1);
/* for privilege notice=============================================================================*/
$privnoticeQuery="select Notice from CG_Privilege_Notice";
$resprivnoticeQuery= mysqli_query($conn, $privnoticeQuery);
$rowprivnoticeQuery=mysqli_fetch_assoc($resprivnoticeQuery);
      ?>
    <!DOCTYPE html>

    <html>

    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pipeway | View Client Guide</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=11" />
    <meta http-equiv="X-UA-Compatible" content="IE=10" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <meta http-equiv="X-UA-Compatible" content="IE=8" />
    <link rel="stylesheet" href="../css/PSnnect.min.css">
    <link rel="stylesheet" href="../css/PSdataTables.min.css">
    <link rel="stylesheet" href="../css/PSPanel.css">
    <link rel="stylesheet" href="../css/PSdatepicker.min.css">
    <link rel="stylesheet" href="../css/Multiselect.css">

    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../img/fevicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../img/fevicon.ico">
    <link rel="apple-touch-icon-precomposed" href="../img/fevicon.ico">
    <link rel="shortcut icon" href="../img/fevicon.ico">

    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css"> -->
    <!---<link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">---->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="../js/PSnnect.min.js"></script>
    <script src="../js/PSslimscroll.js"></script>
    <script src="../js/PSnnectValidator.min.js"></script>
    <script src="../js/PSnnectPanel.js"></script>
    <script src="../js/PSdatepicker.min.js"></script>
    <script src="../js/autologout1.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js"></script>
    <style>
.clt-addr {
    margin: 20px 0;
    padding: 20px 0;
    border: 1px solid #cccccc;
}
          /* Load more */
.load-more,.load-more1{
    width: 10%;
    background: #15a9ce;
    text-align: center;
    color: white;
    padding: 10px 0px;
    font-family: sans-serif;
}

.load-more,.load-more1:hover{
    cursor: pointer;
}
.p5_8{padding:5px 8px!important;}

.main-footer{

      margin-left: 0px;
}
    </style>
    </head>

    <body class="hold-transition skin-yellow sidebar-mini fixed">

    <div class="wrapper">
   
  <div class="">
<section class="content">
<div class="row">
<div class="col-xs-12">
<div class="box">

<form method="post" id="formid" enctype="multipart/formdata">
<div class="box-body">
<div class="tab-content">
<?php if(mysqli_num_rows($result) > 0 && $rescheckbosresult['total'] > 0){?>
<div class="col-md-12" id="demo">
    <div class="nav-tabs-custom clearfix">
        <div class="tab-pane" id="">

            <div class="col-md-12 text-center">
                <h4 class="clt-title"><?php echo $row["client_name"]?> <br>
                  <?php echo $row["client_code"];?>
                </h4>
                <div class="col-md-12 clt-addr">
                    <div class="col-md-4">
                        <p><strong>Address:</strong></p>
                        <p><?php echo $row["street_address1"];?></p>
                        <p><?php echo $row["city"].', '.$row["state"].' '.$row["zip_code"];?> </p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Primary Client Contact:</strong></p>
                        <p><?php echo $row["contact_first_name"].' '.$row["contact_last_name"];?></p>
                        <?php if($row['Contact_Title']!=''){?>
                        <p>Title:<?php echo $row['Contact_Title'];?>
                        <?php } ?>
                        <p><?php echo $row["contact_email_address"];?></p>
                        <p><?php echo $row["contact_phone_number"];?></p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Phone:</strong></p>
                        <p><?php echo $row["main_office_number"];?></p>
                         <?php if($row['toll_free_number']!=''){?>
                        <p>Toll Free Number:<?php echo $row['toll_free_number'];?>
                        <?php } ?>
                        <?php if($row['fax_number']!=''){?>
                        <p>Fax:<?php echo $row['fax_number'];?>
                        <?php } ?>
                        
                       <!--  <p>(Please put Attn Seleste)</p> -->
                    </div>
                </div>
                <div><strong><h5>Date Updated : <?php echo $editeddate;?></h5> </strong></div>
                <p>
                    <strong>Privilege Notice:</strong><?php echo $rowprivnoticeQuery['Notice'];?>
                </p>
                <hr>
            </div>


            <div class="col-md-12">
                <div class="col-md-12 text-right mar10B">
                    <a href="#" class="btn btn-primary openall">Expand All</a>
                    <a href="#" class="btn btn-primary closeall">Collapse All</a>
                </div>
                
                <div class="col-sm-12 accor-label">
                    <div class="panel-group" id="accordion">
                        
                        <div class="panel panel-default showportfoliodiv">
                            
                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse01">
                                <h4 class="panel-title">
                                    <a>
                                        <i class="fa fa-angle-right"></i> CLIENT OVERVIEW:
                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                </h4>
                            </div>
                            
                            <div id="collapse01" class="panel-collapse collapse <?php echo isset($_SESSION['port'])?"in":'';?>">
                                
                                <div class="panel-body">
                                    <div class="panel-group" id="AccordionInner01">
                                        
                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner01" href="#InnerCollapse01">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Description
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse01" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                        <?php echo $row["company_overview_description"];?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner01" href="#InnerCollapse02">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Legal Name for Suit
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse02" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                        <?php echo $row["legal_name_for_suit"];?>
                                                    </p>
                                                    <p><?php echo $row["Add_Text_Legal_Name"];?></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner01" href="#InnerCollapse03">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Portfolio Information
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse03" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                            AACA will place Clients accounts with Law Firms each week during the normal placement process. AACA will be identifying accounts by portfolio code as follows:
                                               <span>
                                            <?php 
                                           

                                            $queryport = "SELECT PORTFOLIO_CDE, PORTFOLIO_DESC, LEGAL_SUIT_NME, PRODUCT_CDE, PRDCT_DESC
                                                          FROM CG_Portfolio_code_new
                                                          WHERE CLIENT_CDE='".$getclicode."'
                                                          GROUP BY PORTFOLIO_CDE
                                                          ORDER BY PORTFOLIO_CDE";

                                            $result1 = mysqli_query($conn,$queryport);?>
                                             <table class="table">
                                            <thead>
                                            <tr>
                                            <th class="col-md-2">Portfolio Code</th>
                                            <th class="col-md-2">Portfolio Description</th>
                                            <th class="col-md-2">Legal Name For Suit</th>
                                            <th class="col-md-2">Product Code</th>
                                            <th class="col-md-2">Product Description</th>
                                            
                                            </tr>
                                          </thead>
                                            <tbody>
                                            <?php 
                                            $count=0;
                                            while($resrow = mysqli_fetch_array($result1)){
                                             $count++;
                                              $portcode        = $resrow['PORTFOLIO_CDE'];
                                              $portdesc        = $resrow['PORTFOLIO_DESC'];
                                              $suitname        = $resrow['LEGAL_SUIT_NME']; 
                                              $productcde      = $resrow['PRODUCT_CDE'];
                                              $productdesc     = $resrow['PRDCT_DESC'];

                                            ?>
                                          
                                            <tr class="sub-container">
                                              <td class="col-md-2"><?php echo $portcode;?></td>
                                              <td class="col-md-2"><?php echo $portdesc;?></td>
                                              <td class="col-md-2"><?php echo $suitname;?></td>
                                              <td class="col-md-2"><?php echo $productcde;?></td>
                                              <td class="col-md-2"><?php echo $productdesc;?></td>
                                              <td class="col-md-2 text-center">
                                                <?php 
                                                $queryportdupcheck = "SELECT DISTINCT PORTFOLIO_CDE FROM CG_Portfolio_code_new WHERE CLIENT_CDE='".$getclicode."'";
                                            $resultdupcheck = mysqli_query($conn,$queryportdupcheck);
                                              while($resrowdupcheck = mysqli_fetch_assoc($resultdupcheck)){
                                                $PORTFOLIO_CDE=$resrowdupcheck['PORTFOLIO_CDE'];
              
                                                $countport="Select count(PORTFOLIO_CDE) as count FROM CG_Portfolio_code_new WHERE CLIENT_CDE='".$getclicode."' and PORTFOLIO_CDE='".$PORTFOLIO_CDE."'";//echo $countport;
                                                $resultportfolio = mysqli_query($conn,$countport);
                                                $fetchportfolio  = mysqli_fetch_assoc($resultportfolio);
    
                                                if(($fetchportfolio['count']) > 1 && $PORTFOLIO_CDE==$portcode){
                                             ?>
                                                <button type="button" class="exploder" id="exploder" portcode="<?php echo $portcode;?>" portdesc="<?php echo $portdesc;?>" suitname="<?php echo $suitname;?>" productcde="<?php echo $productcde;?>" productdesc="<?php echo $productdesc;?>" allportfolio="<?php echo $portcode;?>">
                                              <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        <?php } }?>
                                        </td>
                                          
                                         </tr>
                                          <tr class="explode hide">
                                           <td colspan="6"  class="sub_t" style="display: none;padding: 5px 0px;border-top:none;">
                                           <table class="table table-condensed bod" style="margin-bottom:0px;">
                                           <!--<thead>
                                            <tr>
                                            <th class="col-md-2 p5_8">Portfolio Code</th>
                                            <th class="col-md-2 p5_8">Portfolio Description</th>
                                            <th class="col-md-2 p5_8">Legal Name for Suit</th>
                                            <th class="col-md-2 p5_8">Product Code</th>
                                            <th class="col-md-2 p5_8">Product Description</th>
                                            </tr> 
                                        </thead>----> 
                                           <tbody >

                                            </tbody>
                                            </table>
                                            </td>
                                           </tr>
                                          <?php } ?>
                                          
                                        </tbody>
                                        </table>
                                       </span>
                                            </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner01" href="#InnerCollapse04">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Suit Authority
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse04" class="panel-collapse collapse">
                                                <div class="panel-body">
                                           <!--  <p><?php echo $row['Suit_authority_option'];?></p> -->
                                            <p>
                                            <?php echo htmlspecialchars_decode($row['Suit_Authority_Text']);?>
                                            </p>
                                                </div>
                                            </div>
                                        </div>
           <!-----------------------------------Bill of sale starts ----------------------------------------------------------------------->                          <?php //while($debtlist=mysqli_fetch_assoc($debtresult)){
                                          //if(trim($debtlist['RMSBRGLVL2'])==trim($getclicode))  {  ?>
                                         <?php 

                                        $countbosQuery="SELECT COUNT(*) as count FROM CG_BOS_Matrix WHERE CLIENT_CDE='".$getclicode."' and BIT_DELETED_FLAG=0 and ARCHIVED_FLAG=0";//echo $countbosQuery;exit;
                                        $rescountbosQuery=mysqli_query($conn,$countbosQuery);
                                        $fetchbos1       =mysqli_fetch_assoc($rescountbosQuery);
                                        if($fetchbos1['count'] > 0 ){
                                        ?>
                                         <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner01" href="#InnerCollapse05">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Bills of Sales
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse05" class="panel-collapse collapse <?php echo isset($_SESSION['port'])?"in":'';?>">
                                            <div class="panel-body">
                                       
                                        <?php  
                                            $queryportbos = "SELECT PORTFOLIO_CDE,PORTFOLIO_DESC ,BUYER ,SELLER ,DATE_OF_SALE ,TYPE,BOS_COPY,EXHIBIT_A_UPLOAD FROM CG_BOS_Matrix WHERE CLIENT_CDE='".$getclicode."' and BIT_DELETED_FLAG=0 and ARCHIVED_FLAG=0 GROUP BY PORTFOLIO_CDE  order by PORTFOLIO_CDE asc";//echo   $queryportbos;
                                            $result1bos = mysqli_query($conn,$queryportbos);
                                            if(mysqli_num_rows($result1bos)>0){?>
                                            <span >
                                            <table class="table">
                                            <thead>
                                            <tr>
                                            <th class="col-md-2">Portfolio Code</th>
                                            <th class="col-md-2">Portfolio Description</th>
                                            <th class="col-md-1">Buyer</th>
                                            <th class="col-md-1">Seller</th>
                                            <th class="col-md-1">Dt of sale</th>
                                            <th class="col-md-1">Type</th>
                                            <th class="col-md-1">BOS Copy</th>
                                            <th class="col-md-2">Exhibit a upload</th>
                                            
                                            </tr>
                                          </thead>
                                            <tbody>
                                            <?php 
                                            $count=0;
                                            while($resrowbos = mysqli_fetch_array($result1bos)){
                                             $count++;
                                              $portcodebos     = $resrowbos['PORTFOLIO_CDE'];
                                              $portdescbos     = $resrowbos['PORTFOLIO_DESC'];
                                              $BUYER           = $resrowbos['BUYER']; 
                                              $SELLER          = $resrowbos['SELLER'];
                                              $DATE_OF_SALE    = date('m-d-Y',strtotime($resrowbos['DATE_OF_SALE']));
                                              $TYPE            = $resrowbos['TYPE'];
                                              $BOS_COPY        = $resrowbos['BOS_COPY'];
                                              $EXHIBIT_A_UPLOAD= $resrowbos['EXHIBIT_A_UPLOAD'];
                                              $boscopyPath     ="BOS/".$resrowbos['BOS_COPY'];
                                              $exhibituploadPath    ="BOS/".$resrowbos['EXHIBIT_A_UPLOAD'];
                                            ?>
                                          
                                            <tr class="sub-container">
                                              <td class="col-md-2"><?php echo $portcodebos;?></td>
                                              <td class="col-md-2"><?php echo $portdescbos;?></td>
                                              <td class="col-md-1"><?php echo $BUYER;?></td>
                                              <td class="col-md-1"><?php echo $SELLER;?></td>
                                              <td class="col-md-2"><?php echo $DATE_OF_SALE;?></td>
                                              <td class="col-md-1"><?php echo $TYPE;?></td>
                                              <td class="col-md-1"> <?php if($BOS_COPY!=''){?>
                                                  <a href="<?php echo $boscopyPath;?>" target="_blank" title="Bos Copy"><i class="fa fa-file file" aria-hidden="true"></i></a>
                                                <?php } ?></td>
                                              <td class="col-md-2"> <?php if($EXHIBIT_A_UPLOAD!=''){?>
                                                  <a href="<?php echo $exhibituploadPath;?>" target="_blank" title="Exhibit a upload"><i class="fa fa-file file" aria-hidden="true"></i></a>
                                                <?php } ?></td>
                                              <td class="col-md-2">
                                                <?php 
                                                $queryportdupcheckbos = "SELECT DISTINCT PORTFOLIO_CDE FROM CG_BOS_Matrix WHERE CLIENT_CDE='".$getclicode."' and BIT_DELETED_FLAG=0 and ARCHIVED_FLAG=0";
                                            $resultdupcheckbos = mysqli_query($conn,$queryportdupcheckbos);
                                              while($resrowdupcheckbos = mysqli_fetch_assoc($resultdupcheckbos)){
                                                $PORTFOLIO_CDE=$resrowdupcheckbos['PORTFOLIO_CDE'];
              
                                                $countportbos="Select count(PORTFOLIO_CDE) as count FROM CG_BOS_Matrix WHERE CLIENT_CDE='".$getclicode."' and PORTFOLIO_CDE='".$PORTFOLIO_CDE."' and BIT_DELETED_FLAG=0 and ARCHIVED_FLAG=0";//echo $countport;
                                                $resultportfoliobos = mysqli_query($conn,$countportbos);
                                                $fetchportfoliobos  = mysqli_fetch_assoc($resultportfoliobos);
    
                                                if(($fetchportfoliobos['count']) > 1 && $PORTFOLIO_CDE==$portcodebos){
                                             ?>
                                                <button type="button" class="exploderbos" id="exploderbos" portcodebos="<?php echo $portcodebos;?>" portdescbos="<?php echo $portdescbos;?>" BUYER="<?php echo $BUYER;?>" SELLER="<?php echo $SELLER;?>" allportfoliobos="<?php echo $portcodebos;?>" bos="<?php echo "bos".$portcodebos;?>">
                                              <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        <?php } }?>
                                        </td>
                                          
                                         </tr>
                                          <tr class="explode hide">
                                           <td colspan="8"  class="sub_t" style="display: none;padding: 5px 0px;border-top:none;">
                                           <table class="table table-condensed bod"  style="margin-bottom:0px;">
                                           <tbody >

                                            </tbody>
                                            </table>
                                            </td>
                                           </tr>
                                          <?php } ?>
                                          
                                        </tbody>
                                        </table>
                                       </span>
                                   <?php } ?>

                                    </div>
                                  </div>
                                 </div>

                                 <?php } ?>
     <!-----------------------------------Bill of sale ends -----------------------------------------------------------------------> 
      <!-----------------------------------License matrix starts ----------------------------------------------------------------------->        
                                         <?php 

                                        $countlmxQuery="SELECT COUNT(*) as count1  FROM CG_License_Matrix WHERE CLIENT_CDE='".$getclicode."' and BIT_DELETED_FLAG=0 and ARCHIVED_FLAG=0";
                                        $rescountlmxQuery=mysqli_query($conn,$countlmxQuery);
                                        $fetchlmx1       =mysqli_fetch_assoc($rescountlmxQuery);
                                        if($fetchlmx1['count1'] > 0 ){
                                        ?>
                                         <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner01" href="#InnerCollapse06">
                                                <h4 class="panel-title">
                                                    <a>
                                                        License Matrix
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse06" class="panel-collapse collapse">
                                            <div class="panel-body">
                                       
                                        <?php  
                                             $queryportlmx = "SELECT PORTFOLIO_CDE,PORTFOLIO_DESC,LICENSEE,JURID_CDE,JURID_NME,TYPE,LICENSEE_NMBR,EXPIRY_DT,LICENSE_COPY FROM CG_License_Matrix WHERE CLIENT_CDE='".$getclicode."' and BIT_DELETED_FLAG=0 and  ARCHIVED_FLAG=0 GROUP BY PORTFOLIO_CDE order by PORTFOLIO_CDE asc";//echo   $queryportlmx;
                                            $result1lmx = mysqli_query($conn,$queryportlmx);
                                            if(mysqli_num_rows($result1lmx)>0){?>
                                            <span >
                                            <table class="table">
                                            <thead>
                                            <tr>
                                            <th class="col-md-1">Portfolio Code</th>
                                            <th class="col-md-2">Portfolio Description</th>
                                            <th class="col-md-1">Licensee</th>
                                            <th class="col-md-1">Juridiction Code</th>
                                            <th class="col-md-1">Juridiction Name</th>
                                            <th class="col-md-2">Type</th>
                                            <th class="col-md-1">Licensee Number</th>
                                            <th class="col-md-1">Expiry Date</th>
                                            <th class="col-md-1">License Copy</th>
                                            
                                            </tr>
                                          </thead>
                                            <tbody>
                                               <?php 
                                            $count=0;
                                            while($resrowlmx = mysqli_fetch_array($result1lmx)){
                                             $count++;
                                              $portcodelmx        = $resrowlmx['PORTFOLIO_CDE'];
                                              $portdesclmx        = $resrowlmx['PORTFOLIO_DESC'];
                                              $LICENSEE           = $resrowlmx['LICENSEE']; 
                                              $JURID_CDE          = $resrowlmx['JURID_CDE'];
                                              $JURID_NME           = $resrowlmx['JURID_NME'];
                                              $TYPE                = $resrowlmx['TYPE'];
                                              $LICENSEE_NMBR       = $resrowlmx['LICENSEE_NMBR'];
                                              $EXPIRY_DT          = date('m-d-Y',strtotime($resrowlmx['EXPIRY_DT']));
                                               if($EXPIRY_DT=='01-01-1970'){
                                                $EXPIRY_DT  ="No Expiry";
                                              }else{
                                                $EXPIRY_DT=$EXPIRY_DT;
                                              }
                                              $LICENSE_COPY        =$resrowlmx['LICENSE_COPY'];
                                              $LICENSE_COPYPath    ="License_Matrix/".$resrowlmx['LICENSE_COPY'];
                                              if($TYPE =='Coll_Agency'){
                                                $TYPES='Collection Agency License';
                                               }else  if($TYPE =='Finance_Comp'){
                                                $TYPES='Finance Company License';
                                               }else  if($TYPE =='Foreign_Reg'){
                                                $TYPES='Foreign Registration';
                                               }else  if($TYPE =='Debt_Buyer'){
                                                $TYPES='Debt Buyer License';
                                               }
                                            ?>
                                            <tr class="sub-container">
                                              <td class="col-md-1"><?php echo $portcodelmx;?></td>
                                              <td class="col-md-2"><?php echo $portdesclmx;?></td>
                                              <td class="col-md-1"><?php echo $LICENSEE;?></td>
                                              <td class="col-md-1"><?php echo $JURID_CDE;?></td>
                                              <td class="col-md-1"><?php echo $JURID_NME;?></td>
                                              <td class="col-md-2"><?php echo $TYPES;?></td>
                                              <td class="col-md-1"><?php echo $LICENSEE_NMBR;?></td>
                                              <td class="col-md-1"><?php echo $EXPIRY_DT;?></td>
                                              <td class="col-md-1"> <?php if($LICENSE_COPY!=''){?>
                                                  <a href="<?php echo $LICENSE_COPYPath;?>" target="_blank" title="License Copy"><i class="fa fa-file file" aria-hidden="true"></i></a>
                                                <?php } ?></td>
                                            <td class="col-md-1">
                                                <?php 
                                                $queryportdupchecklmx = "SELECT DISTINCT PORTFOLIO_CDE FROM CG_License_Matrix WHERE CLIENT_CDE='".$getclicode."'";
                                            $resultdupchecklmx = mysqli_query($conn,$queryportdupchecklmx);
                                              while($resrowdupchecklmx = mysqli_fetch_assoc($resultdupchecklmx)){
                                                $PORTFOLIO_CDE=$resrowdupchecklmx['PORTFOLIO_CDE'];
              
                                                $countportlmx="Select count(PORTFOLIO_CDE) as count FROM CG_License_Matrix WHERE CLIENT_CDE='".$getclicode."' and PORTFOLIO_CDE='".$PORTFOLIO_CDE."'";//echo $countport;
                                                $resultportfoliolmx = mysqli_query($conn,$countportlmx);
                                                $fetchportfoliolmx  = mysqli_fetch_assoc($resultportfoliolmx);
    
                                                if(($fetchportfoliolmx['count']) > 1 && $PORTFOLIO_CDE==$portcodelmx){
                                             ?>
                                                <button type="button" class="exploderlmx" id="exploderlmx" portcodelmx="<?php echo $portcodelmx;?>" portdesclmx="<?php echo $portdesclmx;?>" LICENSEE="<?php echo $LICENSEE;?>" JURID_CDE="<?php echo $JURID_CDE;?>" allportfoliolmx="<?php echo $portcodelmx;?>" lmx="<?php echo "lmx".$portcodelmx;?>">
                                              <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        <?php } }?>
                                        </td>
                                          
                                         </tr>
                                          <tr class="explode hide">
                                           <td colspan="10"  class="sub_t" style="display: none;padding: 5px 0px;border-top: none;">
                                           <table class="table table-condensed bod" style="margin-bottom:0px;">

                                           <tbody >

                                            </tbody>
                                            </table>
                                            </td>
                                           </tr>
                                          <?php } ?>
                                          
                                        </tbody>
                                        </table>
                                       </span>
                                   <?php } ?>
                                    </div>
                                  </div>
                                 </div>

                                 <?php } ?>
                            <?php //} }?>
     <!-----------------------------------License matrix  ends -----------------------------------------------------------------------> 
                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner01" href="#InnerCollapse07">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Placement Document
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse07" class="panel-collapse collapse">
                                                <div class="panel-body">
                                            <!--   <p>
                                            <span ><?php //echo htmlspecialchars_decode($row['Placement_Document_Text_Options']);?></span>
                                            </p> -->
                                              <p><?php echo htmlspecialchars_decode($row['Document_Text']);?></p>
                                            <p><?php echo  htmlspecialchars_decode($row["placement_documents_list"]);?></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner01" href="#InnerCollapse06">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Matters Requiring Prior Client Authorization
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse06" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                        <?php echo $prior_authority_text ;?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner01" href="#InnerCollapse08">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Settlement and Payment Authority
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse08" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p><?php echo htmlspecialchars_decode($row['sipiftext_desc']);?></p>
                                                    <?php if($row['Percent_PIF']!=''){?>
                                                    <p>Lump sum payment of no less than the following percent of the total balance due = <?php echo $row['Percent_PIF'];?>% </p>
                                                    <?php } ?>
                                                    <?php if($row['payments_pif']!=''){?>
                                                    <p>Payment arrangement on the full balance due of no more the following number of consecutive months =  <?php echo $row['payments_pif'];?> </p>
                                                    <?php } ?>
                                                    <?php if($row['percent_sif']!=''){?>
                                                    <p>Settlements of no less than the following percent of the total balance due =   <?php echo $row['percent_sif'];?>% </p>
                                                    <?php } ?>
                                                     <?php if($row['payments_sif']!=''){?>
                                                    <p>and payment arrangement of no more the following number of consecutive months = <?php echo $row['payments_sif'];?> </p>
                                                    <?php } ?>
                                                    <?php if($row['hardship_percent']!=''){?>
                                                    <p>Hardship offers of no less than the following percent of the total balance due = <?php echo $row['hardship_percent'];?>%</p>
                                                      <p>upon proper showing of proof to include the following:</p>
                                                    <?php } ?>
                                                    <!--static text----------------------->
                                                     <?php if($row['stipulation_flag']=='Yes'){?>
                                                    <p><?php echo $row['stipulation_text'];?></p>
                                                    <?php } ?>
                                                    <?php if($row['stipulation_flag']=='No'){?>
                                                    <p><?php echo $row['stipulation_text'];?></p>
                                                    <?php } ?>
                                                    <?php if($row['garnishment_flag']=='Yes'){?>
                                                    <p><?php echo $row['garnishment_text'];?></p>
                                                    <?php } ?>
                                                    <?php if($row['garnishment_flag']=='No'){?>
                                                   <p><?php echo $row['garnishment_text'];?></p>
                                                    <?php } ?>
                                                    <?php if($row['interest_flag']=='Yes'){?>
                                                    <p><?php echo $row['interest_text'];?></p>
                                                    <?php } ?>
                                                     <?php if($row['interest_flag']=='No'){?>
                                                    <p><?php echo $row['interest_text'];?></p>
                                                    <?php } ?>
                                                    <?php if($row['Portal_flag']=='Yes'){?>
                                                   <p><?php echo $row['portal_text'];?></p>
                                                    <?php } ?>
                                                    <?php if($row['Portal_flag']=='No'){?>
                                                    <p><?php echo $row['portal_text'];?></p>
                                                    <?php } ?>
                                                    <?php if($row['Sif_Pif_alt_by_portfolio_flag']=='Yes'){//Portfolio_Yes?>
                                                   <p><?php echo $row['portfolio_text'];?></p>
                                                    <?php } ?>
                                                       <?php /*main table*/?>
                                                    <?php if($row['Balance_Calculation']!=''){?>
                                                    <p><?php echo $row['Balance_Calculation'];?></p>
                                                    <?php } ?>
                                                    <?php if($row['Settlement_authority_Additional_Text']!=''){?>
                                                    <p><?php echo $row['Settlement_authority_Additional_Text'];?></p>
                                                    <?php } ?>
                                              
                                                </div>
                                            </div>
                                        </div>
                                              <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner01" href="#InnerCollapse09">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Interest and Attorney Fees
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse09" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                <!-- <p><?php echo $row['Text_Heading'];?></p> -->
                                                <p><?php echo htmlspecialchars_decode($row['Text_Description']);?></p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>


                            </div>
                        </div>


                        <div class="panel panel-default">
                            
                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse02">
                                <h4 class="panel-title">
                                    <a>
                                        <i class="fa fa-angle-right"></i> SPECIAL HANDLING INSTRUCTIONS:
                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                </h4>
                            </div>
                            
                            <div id="collapse02" class="panel-collapse collapse">
                                
                                <div class="panel-body">
                                    <div class="panel-group" id="AccordionInner02">
                                
                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner02" href="#InnerCollapse090">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Statute of Limitations
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse090" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                        <?php echo $row["handling_sol"];?>
                                                    </p>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner02" href="#InnerCollapse10">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Consumer Bankruptcies
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse10" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                    <?php echo $row["handling_bankruptcy"];?>
                                                    </p>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner02" href="#InnerCollapse11">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Disability
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse11" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                     <?php echo $row["handling_disability"];?>
                                                    </p>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner02" href="#InnerCollapse12">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Probate
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse12" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                     <?php echo $row["handling_probate"];?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner02" href="#InnerCollapse13">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Substantive Course of Action
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse13" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                      <?php echo htmlspecialchars_decode($row["handling_special_procedures"]);?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner02" href="#InnerCollapse14">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Answers or Counter Claims
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse14" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                     <?php echo $row["handling_counterclaims"];?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner02" href="#InnerCollapse15">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Conflict of Interest
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse15" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                     <?php echo $row["handling_conflict"];?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner02" href="#InnerCollapse16">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Military
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse16" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                     <?php echo $row["handling_military"];?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner02" href="#InnerCollapse17">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Customer Complaints, Claims of fraud and Disputes
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse17" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                      <?php echo $row["handling_complaints"];?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner02" href="#InnerCollapse18">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Disputes
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse18" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                        TEST10
                                                    </p>
                                                </div>
                                            </div>
                                        </div> -->

                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="panel panel-default">
                            
                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse03">
                                <h4 class="panel-title">
                                    <a>
                                        <i class="fa fa-angle-right"></i> COMMUNICATIONS AND REQUESTS:
                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                </h4>
                            </div>

                            <div id="collapse03" class="panel-collapse collapse">
                                
                                <div class="panel-body">
                                    <div class="panel-group" id="AccordionInner03">
                                        
                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner03" href="#InnerCollapse19">
                                                <h4 class="panel-title">
                                                    <a>
                                                        General Communication Instructions
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse19" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                <p><b>General communication Instructions:</b></p>
                                                <span id="hdncommunication"><?php echo $row["communication_text"] ;?></span>
                                                  <ul>
                                                    <?php if($row['Client_Guide']!=''){?>
                                                    <li><p><?php echo $row['Client_Guide'];?> </p></li>
                                                    <?php } ?>
                                                   <?php if($row['Portfolio_Specific_Information']!=''){?>
                                                    <li><p><?php echo $row['Portfolio_Specific_Information'];?> </p></li>
                                                    <?php } ?>
                                                 <?php if($row['Allowcation_of_Funds']!=''){?>
                                                    <li><p><?php echo $row['Allowcation_of_Funds'];?> </p></li>
                                                    <?php } ?>
                                                <?php if($row['Skip_Tracing_Assistance']!=''){?>
                                                    <li><p><?php echo $row['Skip_Tracing_Assistance'];?> </p></li>
                                                <?php } ?>
                                                 <?php if($row['Consumer_Re_Locates']!=''){?>
                                                    <li><p><?php echo $row['Consumer_Re_Locates'];?> </p></li>
                                                <?php } ?>
                                                 <?php if($row['Account_Closures_Re_Open_Requests']!=''){?>
                                                    <li><p><?php echo $row['Account_Closures_Re_Open_Requests'];?> </p></li>
                                                <?php } ?>
                                                  <?php if($row['Monthly_Reporting_Requirements']!=''){?>
                                                    <li><p><?php echo $row['Monthly_Reporting_Requirements'];?> </p></li>
                                                <?php } ?>
                                                    <?php if($row['Client_Set_Up']!=''){?>
                                                    <li><p><?php echo $row['Client_Set_Up'];?> </p></li>
                                                <?php } ?>
                                                      <?php if($row['Conflicts_of_Interest']!=''){?>
                                                    <li><p><?php echo $row['Conflicts_of_Interest'];?> </p></li>
                                                <?php } ?>
                                            <?php if($row['AACA_Contract_Issues']!=''){?>
                                                <li><p><?php echo $row['AACA_Contract_Issues'];?> </p></li>
                                            <?php } ?>
                                        </ul>
                                            <p><b>Document Transfer:</b></p>
                                            <p id="hdndoctransfer"><?php echo $row["documentTransfer"] ;?></p>
                                            <ul>
                                            <?php if($row['Authentication_Documents']!=''){?>
                                                <li><p><?php echo $row['Authentication_Documents'];?> </p></li>
                                            <?php } ?>
                                            <?php if($row['Media_Requests']!=''){?>
                                                <li><p><?php echo $row['Media_Requests'];?> </p></li>
                                            <?php } ?>
                                             <?php if($row['Witness_Requests']!=''){?>
                                                <li><p><?php echo $row['Witness_Requests'];?> </p></li>
                                            <?php } ?>
                                             <?php if($row['Probate_Handling']!=''){?>
                                                <li><p><?php echo $row['Probate_Handling'];?> </p></li>
                                            <?php } ?>
                                               <?php if($row['Dispute_Handling']!=''){?>
                                                <li><p><?php echo $row['Dispute_Handling'];?> </p></li>
                                            <?php } ?>
                                               <?php if($row['Answers_or_Counterclaims']!=''){?>
                                                <li><p><?php echo $row['Answers_or_Counterclaims'];?> </p></li>
                                            <?php } ?>
                                               <?php if($row['Client_authorization']!=''){?>
                                                <li><p><?php echo $row['Client_authorization'];?> </p></li>
                                            <?php } ?>
                                               <?php if($row['Settlement_Proposals_and_Arrangements']!=''){?>
                                                <li><p><?php echo $row['Settlement_Proposals_and_Arrangements'];?> </p></li>
                                            <?php } ?>
                                        </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner03" href="#InnerCollapse20">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Client Contact Information
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse20" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <?php while($getallval=mysqli_fetch_assoc($resccregQuery1)){?>
                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                          <span><strong>Company name : </strong><?php echo $getallval['CNAME'];?></span>
                                                        </div>
                                                        <div class="col-md-6">
                                                          <span><strong>Alternative name : </strong><?php echo $getallval['RANAME'];?></span><br>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                    <span><strong>Address 1 : </strong><?php echo $getallval['ADDR1'];?></span>
                                                        </div>
                                                        <div class="col-md-6">
                                                    <span><strong>Address 2 : </strong><?php echo $getallval['ADDR2'];?></span><br>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                    <span><strong>Tax Id : </strong><?php echo $getallval['TAXID'];?></span>
                                                        </div>
                                                        <div class="col-md-6">
                                                    <span><strong>First Name : </strong><?php echo $getallval['RFNAME'];?></span><br>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                    <span><strong>Last Name : </strong><?php echo $getallval['RLNAME'];?></span>
                                                        </div>
                                                        <div class="col-md-6">
                                                    <span><strong>Country : </strong><?php echo $getallval['COUNTRY'];?></span><br>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                    <span><strong>State : </strong><?php echo $getallval['STATE'];?></span>
                                                        </div>
                                                        <div class="col-md-6">
                                                    <span><strong>City : </strong><?php echo $getallval['CITY'];?></span><br>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                    <span><strong>Zip Code : </strong><?php echo $getallval['ZIP_CODE'];?></span>
                                                        </div>
                                                        <div class="col-md-6">
                                                    <span><strong>Main Phone No : </strong><?php echo $getallval['MAIN_PHONE'];?></span><br>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                    <span><strong>Fax No : </strong><?php echo $getallval['FAX_NUM'];?></span>
                                                        </div>
                                                        <div class="col-md-6">
                                                    <span><strong>Toll Free No : </strong><?php echo $getallval['TOLL_FREE_NO'];?></span><br>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="col-md-6">
                                                    <span><strong>Website Address : </strong><?php echo $getallval['WEBADDR'];?></span>
                                                        </div>
                                                         <div class="col-md-6">
                                                    <span><strong>Email Id: </strong><?php echo $getallval['REGEMAIL'];?></span><br>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner03" href="#InnerCollapse21">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Affidavits and Verification Documents
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse21" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                      <?php echo $row['Affidavit_Requirement_Options'];?>
                                                    </p>

                                            <?php if($row['Affiant_Signor']!=''){?>
                                            <p>Affifavits will be signed by:<?php echo $row['Affiant_Signor'];?></p>
                                            <?php } ?>
                                             <?php if($row['Affiant_title']!=''){?>
                                            <p>Title:<?php echo $row['Affiant_title'];?></p>
                                            <?php } ?>
                                             <p>The legal address if needed for affidavits is:</p>
                                             <?php if($row['affiant_street_address1']!=''){?>
                                            <p><?php echo $row['affiant_street_address1'];?></p>
                                            <?php } ?>
                                            <?php if($row['affiant_street_address2']!=''){?>
                                            <p><?php echo $row['affiant_street_address2'];?></p>
                                            <?php } ?>
                                             <?php if($row['affiant_city']!=''){?>
                                            <p><?php echo $row['affiant_city'];?></p>
                                            <?php } ?>
                                             <?php if($row['affiant_state']!=''){?>
                                            <p><?php echo $row['affiant_state'];?></p>
                                            <?php } ?>
                                            <p>Affidavits,Declarations,verifications,etc.("Authentication Documents") should be requested from client al least <?php echo $row['Days_prior_'];?> days prior to the date on which documentation may be required and should be sent to</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner03" href="#InnerCollapse22">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Media Request
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse22" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                     <?php echo $media_request_text;?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner03" href="#InnerCollapse23">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Witnesses Request
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse23" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                       <?php echo $witness_text;?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>


                        <div class="panel panel-default">
                            
                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse04">
                                <h4 class="panel-title">
                                    <a>
                                        <i class="fa fa-angle-right"></i> REPORTING INSTRUCTIONS:
                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                </h4>
                            </div>

                            <div id="collapse04" class="panel-collapse collapse">
                                
                                <div class="panel-body">    
                                    <div class="panel-group" id="AccordionInner04">
                                        
                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner04" href="#InnerCollapse24">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Consumer Moving out of state
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse24" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                    <?php echo $row['handling_relocation'];?>
                                                    </p>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner04" href="#InnerCollapse25">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Account Closures/Status Codes
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse25" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                        <?php echo $row['handling_closures'];?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner04" href="#InnerCollapse26">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Law Firm Monthly Reporting Requirements
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse26" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                        <?php echo $row['reporting_requirements'];?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="panel panel-default">
                                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#AccordionInner04" href="#InnerCollapse27">
                                                <h4 class="panel-title">
                                                    <a>
                                                        Calculation of Collections
                                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                                </h4>
                                            </div>
                                            <div id="InnerCollapse27" class="panel-collapse collapse">
                                                <div class="panel-body">
                                                    <p>
                                                         <?php echo $row['application_of_funds_code'];?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="panel panel-default">
                            
                            <div class="panel-heading accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse05">
                                <h4 class="panel-title">
                                    <a>
                                        <i class="fa fa-angle-right"></i> CLIENT SET-UP:
                                    </a><i class="indicator fa fa-chevron-up pull-right"></i>
                                </h4>
                            </div>

                            <div id="collapse05" class="panel-collapse collapse">
                                
                                <div class="panel-body">    
                                    <div class="panel-group" id="AccordionInner04">
                                <div class="panel-body">
                                <p><b>Client:</b></p>
                                <p><?php echo $row['client_name'];?></p>
                               <p><?php echo $row["street_address1"];?></p>
                               <p><?php echo $row["city"].', '.$row["state"].' '.$row["zip_code"];?> </p>
                               <p><b>Primary Contact:</b></p>
                               <p><?php echo $row["contact_first_name"].' '.$row["contact_last_name"];?></p>
                                <?php if($row['Contact_Title']!=''){?>
                                <p>Title:<?php echo $row['Contact_Title'];?>
                                <?php } ?>
                                <p><?php echo $row["contact_email_address"];?></p>
                                <p><?php echo $row["contact_phone_number"];?></p>
                                <p><b>Client Code : </b><?php echo $row["client_code"];?></p>
                                 <p>
                                         
                                <span >
                                            <?php
                                            $queryport1 = "SELECT PORTFOLIO_CDE, PORTFOLIO_DESC, LEGAL_SUIT_NME, PRODUCT_CDE, PRDCT_DESC
                                                          FROM CG_Portfolio_code_new
                                                          WHERE CLIENT_CDE='".$getclicode."'
                                                          GROUP BY PORTFOLIO_CDE
                                                          ORDER BY PORTFOLIO_CDE";//echo  $queryport;
                                            $result11 = mysqli_query($conn,$queryport1);?>
                                             <table class="table">
                                            <thead>
                                            <tr>
                                            <th class="col-md-2">Portfolio Code</th>
                                            <th class="col-md-2">Portfolio Description</th>
                                            <th class="col-md-2">Legal Name For Suit</th>
                                            <th class="col-md-2">Product Code</th>
                                            <th class="col-md-2">Product Description</th>
                                            <th class="col-md-2">Action</th>
                                            </tr>
                                          </thead>
                                            <tbody>
                                            <?php 
                                            $count=0;
                                            while($resrow1 = mysqli_fetch_array($result11)){
                                             $count++;
                                              $portcode        = $resrow1['PORTFOLIO_CDE'];
                                              $portdesc        = $resrow1['PORTFOLIO_DESC'];
                                              $suitname        = $resrow1['LEGAL_SUIT_NME']; 
                                              $productcde      = $resrow1['PRODUCT_CDE'];
                                              $productdesc     = $resrow1['PRDCT_DESC'];

                                            ?>
                                          
                                            <tr class="sub-container">
                                              <td class="col-md-2"><?php echo $portcode;?></td>
                                              <td class="col-md-2"><?php echo $portdesc;?></td>
                                              <td class="col-md-2"><?php echo $suitname;?></td>
                                              <td class="col-md-2"><?php echo $productcde;?></td>
                                              <td class="col-md-2"><?php echo $productdesc;?></td>
                                              <td class="col-md-2">
                                                <?php 
                                                $queryportdupcheck = "SELECT DISTINCT PORTFOLIO_CDE FROM CG_Portfolio_code_new WHERE CLIENT_CDE='".$getclicode."'";
                                            $resultdupcheck = mysqli_query($conn,$queryportdupcheck);
                                              while($resrowdupcheck = mysqli_fetch_assoc($resultdupcheck)){
                                                $PORTFOLIO_CDE=$resrowdupcheck['PORTFOLIO_CDE'];
              
                                                $countport="Select count(PORTFOLIO_CDE) as count FROM CG_Portfolio_code_new WHERE CLIENT_CDE='".$getclicode."' and PORTFOLIO_CDE='".$PORTFOLIO_CDE."'";//echo $countport;
                                                $resultportfolio = mysqli_query($conn,$countport);
                                                $fetchportfolio  = mysqli_fetch_assoc($resultportfolio);
    
                                                if(($fetchportfolio['count']) > 1 && $PORTFOLIO_CDE==$portcode){
                                             ?>
                                                <button type="button" class="exploder" id="exploder" portcode="<?php echo $portcode;?>" portdesc="<?php echo $portdesc;?>" suitname="<?php echo $suitname;?>" productcde="<?php echo $productcde;?>" productdesc="<?php echo $productdesc;?>" allportfolio="<?php echo $portcode;?>">
                                              <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        <?php } }?>
                                        </td>
                                          
                                         </tr>
                                          <tr class="explode hide">
                                           <td colspan="6"  class="sub_t" style="display: none;border-top:none;padding:0px;">
                                           <table class="table table-condensed bod" style="margin-bottom:5px;">
                                           <!---<thead>
                                            <tr>
                                            <th class="p0">Portfolio Code</th>
                                            <th class="p0">Portfolio Description</th>
                                            <th class="p0">Legal Name for Suit</th>
                                            <th class="p0">Product Code</th>
                                            <th class="p0">Product Description</th>
                                            </tr> 
                                        </thead> ---->
                                           <tbody >

                                            </tbody>
                                            </table>
                                            </td>
                                           </tr>
                                          <?php } ?>
                                          
                                        </tbody>
                                        </table>
                                       
                                        
                                          </span>
                                </p>
                                 <b>Is suit notification required:</b><?php echo $row['suit_notification_flag'];?>
                                </p>
                                <p>
                                 <b>Is judgment notification required:</b> <?php echo $row['judgment_notification_flag'];?>
                                </p>
                                <p><b>Is inactive status notification required:</b><?php echo $row['inactive_status_notice_flag'];?></p>
                                <p><b>Acknowledgment required:</b><?php echo $row['acknowledgement_type'];?></p>
                                <p><b>Remittance Cycle:</b><?php echo $row['remittance_cycle'];?></p>
                                <p><b>Client remittance checks (gross/net):</b><?php echo $row['remittance_type'];?></p>
                                <p><b>Fees/Commissions are to be calculated net of Court Costs and Expenses:</b><?php echo $row['set_aside_flag'];?></p>
                                <p><b>Number of days to hold receipt before remitting:</b><?php echo $row['funds_hold_permitted'];?></p>
                                <p><b>Number of days to re-bill costs (i.e. authorization of billing):</b><?php echo $row['cost_rebill'];?></p>
                                <p><b>Is interest accruing on accounts:</b><?php echo $row['interest_pre_suit'];?></p>
                                <p><b>Contact AACANet when consumer moves out of state:</b><?php echo $row['move_out_of_state_flag'];?></p>
                                <p><b>Cannot Serve:</b><?php echo $row['service_failure_flag'];?></p>  
                                <p><b>Attorney Fees Allowed:</b><?php echo $row['ATT_FEES_ALLOWED'];?></p>  
                                <p>
                                <strong>Notes:</strong><span><?php echo htmlspecialchars_decode($row['NOTES']);?></span>
                               
                              </p>               
                                </div>  
                                       
                                    </div>
                                </div>

                            </div>
                        </div>


                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php } else {?>

    <p style="color:red;text-align: center;"> <b>This feature is not available for portfolio <?php echo $getport;?> - for necessary documentation please proceed to the</b><span style="color:#002e5b;cursor: pointer;"><u> Client Guide.</u></span>
    <br>
    <br>
    <br>
    <br>
    <br>
    <p style="color:red;text-align: center;"><b> Disclaimer: <span style="color: black;"> *Bill of Sale and Sample Cardholder Agreements provided by client. Please verify for accuracy.
    <span></span><br>For account specific Terms & Conditions please contact Original Creditor</span></b>
        
<?php } ?>


</div>
</form>

</div>
</div>
</div>
</section>
</div>
            
       <?php include('../footer.php'); ?>

        <div class="control-sidebar-bg"></div>

    </div>


   <script>
        $(document).ready(function() {
            $('.closeall').click(function() {
                $('.show')
                    .collapse('hide');
            });
            $('.openall').click(function() {
                $('.panel-collapse:not(".in")')
                    .collapse('show');
            });

        });
    </script>
    <script>
        function toggleChevron(e) {
            $(e.target)
                .prev('.panel-heading')
                .find("i.indicator")
                .toggleClass('fa fa-chevron-down fa fa-chevron-up');
        }
        $('#accordion').on('hidden.bs.collapse', toggleChevron);
        $('#accordion').on('shown.bs.collapse', toggleChevron);

$(".exploder").click(function(){
  var allportfolio=$(this).attr('allportfolio');
  $(this).children("span").toggleClass("glyphicon-minus");  
  
  $(this).closest("tr").next("tr").toggleClass("hide");
  
  if($(this).closest("tr").next("tr").hasClass("hide")){
    $(this).closest("tr").next("tr").children("td").slideUp();
  }
  else{

    $(this).closest("tr").next("tr").children("td").slideDown(350);
    $(this).closest("tr").next("tr").children("td").find('tbody').addClass(allportfolio);
  }
}); 

$(".exploderbos").click(function(){
  var bos=$(this).attr('bos');
  $(this).children("span").toggleClass("glyphicon-minus");  
  
  $(this).closest("tr").next("tr").toggleClass("hide");
  
  if($(this).closest("tr").next("tr").hasClass("hide")){
    $(this).closest("tr").next("tr").children("td").slideUp();
  }
  else{

    $(this).closest("tr").next("tr").children("td").slideDown(350);
    $(this).closest("tr").next("tr").children("td").find('tbody').addClass(bos);
  }
}); 

$(".exploderlmx").click(function(){
  var lmx=$(this).attr('lmx');
  $(this).children("span").toggleClass("glyphicon-minus");  
  
  $(this).closest("tr").next("tr").toggleClass("hide");
  
  if($(this).closest("tr").next("tr").hasClass("hide")){
    $(this).closest("tr").next("tr").children("td").slideUp();
  }
  else{

    $(this).closest("tr").next("tr").children("td").slideDown(350);
    $(this).closest("tr").next("tr").children("td").find('tbody').addClass(lmx);
  }
}); 
$(document).ready(function(){
 $('.exploder').click(function(){
 var allportfolio=$(this).attr('allportfolio');
  var portcode=   $(this).attr("portcode");
  var portdesc=   $(this).attr("portdesc");
  var suitname=   $(this).attr("suitname");
  var productcde= $(this).attr("productcde");
  var productdesc= $(this).attr("productdesc");
  $.ajax({
            url: 'clientportfolio.php',
            type: "POST",
            data: {portcode:portcode,portdesc:portdesc,suitname:suitname,productcde:productcde,productdesc:productdesc
               
            },
            success: function (response) {
                $('.'+allportfolio).html(response);
            }
        });
}) 
 $('.exploderbos').click(function(){
  var allportfoliobos=$(this).attr('allportfoliobos');
  var bos=$(this).attr('bos');
  var clicode='<?php echo $getclicode;?>';
  $.ajax({
            url: 'clientportfolio.php',
            type: "POST",
            data: {allportfoliobos:allportfoliobos,clicode:clicode},
            success: function (response) {
               // $('.'+allportfoliobos).html(response);
                 $('.'+bos).html(response);
            }
        });
}) 
 $('.exploderlmx').click(function(){
  var allportfoliolmx=$(this).attr('allportfoliolmx');
   var lmx=$(this).attr('lmx');
   var clicode='<?php echo $getclicode;?>';
  $.ajax({
            url: 'clientportfolio.php',
            type: "POST",
            data: {allportfoliolmx:allportfoliolmx,clicode:clicode},
            success: function (response) {
               // $('.'+allportfoliolmx).html(response);
                 $('.'+lmx).html(response);
            }
        });
})     
})
    </script>


  <!--for every 30 sec page logout automatically-->
<!-- <script>
(function() {
const idleDurationSecs = 600; // X number of seconds
const redirectUrl = '/bi/dist/logout'; // Redirect idle users to this URL
let idleTimeout; // variable to hold the timeout, do not modify
const resetIdleTimeout = function() {
// Clears the existing timeout
if(idleTimeout) clearTimeout(idleTimeout);
// Set a new idle timeout to load the redirectUrl after idleDurationSecs
idleTimeout = setTimeout(() => location.href = redirectUrl, idleDurationSecs * 1000);
};
// Init on page load
resetIdleTimeout();
// Reset the idle timeout on any of the events listed below
['click', 'touchstart', 'mousemove', 'mousedown', 'keypress', 'onscroll'].forEach(evt =>
document.addEventListener(evt, resetIdleTimeout, false)
);

})();
</script> -->
<!--for every 30 sec page logout automatically end-->







    </body>

    </html>