<?php
unset($_SESSION['ATTY_CDE']);
unset($_SESSION['CLIENT_CDE']);
unset($_SESSION['PRODUCT_CDE']);
unset($_SESSION['PORTFOLIO_CDE']);
unset($_SESSION['DEBTR_STATE_AD']);
unset($_SESSION['AGENCY_FLAG']);
unset($_SESSION['ACCOUNT_TYPE']);


$url = $_SERVER['REQUEST_URI'];
$positionslash = strrpos($url, "/");
$filename = substr($url, $positionslash + 1);
if ($filename == 'register') {
    $id = "register";
}  if ($filename == 'view') {
    $id = "view";
}  if ($filename == 'registerall') {
    $id = "registerall";
}  if ($_GET['GetID'] != '') {
    $id = "edit";
}  if ($_GET['GetIDS'] != '') {
    $id = "editall";
}
if ($_GET['GetID'] != '') {
    $scheduler_edit = "edit_scheduler_report";
}
if ($_GET['STATE_CDE'] != '') {
    $viewStateIssue="Stateuinew";
}
if ($_GET['id'] != '') {
    $viewStateIssue="compareState";
}

if ($_GET['STATE_CODE'] != '') {
    $viewStateIssue="stateView";
}

if ($_GET['StateCde'] != '') {
    $viewStateIssue="approvedStateView";
}

if ($_GET['StateTitle'] != '') {
    $viewStateIssue="stateCompare";
}

//echo $filename ;
if($filename == 'mydownload'){
    $mydownload = "mydownload";
}  if ($filename == 'myuploadreport') {
    $mydownload = "myuploadreport";
}  if ($filename == 'addfolder') {
    $addfolder = "addfolder";
}  if ($filename == 'myupload') {
    $myupload = "myupload";
}  if ($filename == 'viewmyupload') {
    $myupload = "viewmyupload";
}
if ($filename == 'sharefile') {
    $sharefile = "sharefile";
}
if ($filename == 'viewsharefile') {
    $sharefile = "viewsharefile";
}
if ($filename == 'archivesharefile') {
    $sharefile = "archivesharefile";
}
if ($filename == 'myuploadhistory') {
    $myuploadhistory = "myuploadhistory";
}  if ($_GET['uploadid'] != '') {
    $myuploadhistory = "myuploadhistory";
}
if ($_GET['CLIENT_CODEACD'] != '') {
    $viewlistingguideadmin = "listingguideaaca";
}
if ($filename == 'archivedoc') {
    $myupload = "archivedoc";
}
if($_GET['selval']!='' && $_GET['folder']!='' && $filename != 'mydownload'){
$mydownload="sharedoc";
}if($_GET['selval']=='' && $_GET['folder']=='' && $filename != 'mydownload'){
$mydownload="sharedoc";
}
if($filename=='mydownloadarchive'){
$mydownload="mydownloadarchive";
}if($filename=='myupshare'){
$myupload="myupshare";
}if($filename=='mydwnldshare'){
$myupload="mydwnldshare";
}

if($filename=='state-issues'){
$stateissues="state-issues";
}

if($filename=='scheduler_01'){
$view_report="scheduler_01";
}

if($filename=='ViewReport'){
$view_report="ViewReport";
}

if($filename=='Scheduler_Edit'){
$scheduler_edit="Scheduler_Edit";
}

if($filename=='add_reports_type'){
$scheduler_edit="add_reports_type";
}

if($filename=='SFTP'){
$sftp_details="SFTP";
}if($_GET['invoicing']!=''){
$sftp_details="SFTP";
}

if($_GET['state_name']!='' ){
$stateissues="state-issues";
}if($filename=='viewlistingguide'){
 $viewlistingguide="viewlistingguide";
}if($filename=='viewStateIssue'){
  $viewStateIssue="viewStateIssue";
}
if($filename=='viewApprovedIssue'){
  $viewStateIssue="viewApprovedIssue";
}
if($filename=='Stateuinew'){
  $stateEdit="Stateuinew";
}
if($_GET['CLI_CDE']!=''){
 $viewlistingguide="ViewClientGuide";
}
 if($_GET['CLIENT_CODE']!=''){
 $viewlistingguide="editguide";
}
 if($_GET['CLIENT_CODEA']!=''){
 $viewGui="AdminlistingGuide";
}
if($_GET['PREV_CDE']!=''){
 $viewGui="editPreview";
}
if($_GET['ADCLI_CDE']!=''){
 $viewGui="Admineditedview";
}
if($_GET['code']!=''){
 $viewGui="AdminlistingGuide";
}
if($_GET['COMP_CLI_CDE']!=''){
 $viewGui="Admincomparetable";
}

if($_GET['CLIENT_CODEREJ']!=''){
 $viewlistingguide="editRejected";
}
if($_GET['CLIENT_CODECC']!=''){
 $viewGui="editguidecli";
}
if($filename=='viewlistingguideadmin'){
 $viewlistingguideadmin="viewlistingguideadmin";
}
if($_GET['PREV_CDEAD']!=''){
 $viewlistingguideadmin="editPreviewadmin";
}
if($_GET['CLIENT_CODEACD']!=''){
 $viewlistingguideadmin="listingguideaaca";
}if($_GET['CLIENT_CODEAD']!=''){
 $viewlistingguideadmin="editguideadmin";
}if($_GET['CLIENT_CODEADD']!=''){
 $viewGui="AdminlistingGuide";
}
if($_SESSION['userType']==3){
 if($_GET['CLIENT_CODEA']!=''){
 $viewlistingguide="AdminlistingGuide";
}
}if($filename=='AdminlistingGuide'){
 $viewGui="AdminlistingGuide";
}if($_GET['CLIENT_CODEStatus']!=''){
 $viewlistingguide="getstatus";
}
if ($filename == 'addFaq') {
  $idf = "addFaq";
} else if ($filename == 'viewFaq') {
  $idf = "viewFaq";
} else if ($filename == 'archiveFaq') {
  $idf = "archiveFaq";
} else if ($_GET['GetID'] != '') {
  $idf = "editFaq";
} else if ($_GET['id'] != '') {
  $idf = "viewFaq";
}else if($_GET['GetID']!=''){
$idf="editFaq";
}else if($filename=='addNotice'){
$id1="addNotice";
}else if($filename=='viewNotice'){
$id1="viewNotice";
}else if($_GET['GetNoticeID']!=''){
$id1="editNotice";
}
else if($_GET['id']!=''){
  $id1="viewNotice";
}else if($filename=='archiveNotice'){
$id1="archiveNotice";
}if($filename=='viewBos'){
 $viewBos="viewBos";
}if($filename=='addBos'){
 $viewBos="addBos";
}if($filename=='editBos'){
 $viewBos="editBos";
}if($_GET['clientcode']!=''){
 $viewBos="viewBos";
}if($_GET['BosID']!=''){
 $viewBos="editBos";
}if($filename=='viewLicenseMatrix'){
 $viewLicenseMatrix="viewLicenseMatrix";
}if($filename=='addLicenseMatrix'){
  $viewLicenseMatrix="addLicenseMatrix";
 }if($_GET['LM_ID']!=''){
$viewLicenseMatrix="editLicenseMatrix";
 }if($_GET['clientcode']!='' && $_GET['type']!=''){
$viewLicenseMatrix="viewLicenseMatrix";
 }

 if($filename=='WSCLTCNT'){
$WSCLTCNT="WSCLTCNT";
}if($_GET['ORGCODE']!=''){
$WSCLTCNT="settlement-control-settings";
} if($filename=='sif-insert'){
 $WSCLTCNT="sif-insert";
 }if($filename=='WSEMAILCNT'){
$WSEMAILCNT="WSEMAILCNT";
}
 if($_GET['EMAILCODE']!=''){
 $WSEMAILCNT="email-record-control";
}if($filename=='viewSuitAuth'){
$SuitAuth="viewSuitAuth";
}if($_GET['tablename']!=''){
 $SuitAuth="viewSuitAuth";
}if($_GET['tablenameedit']!=''){
 $SuitAuth="editSuitAuth";
}if($_GET['dropval']!=''){
 $SuitAuth="SuitAuth";
}if($_GET['dropval']!=''){
 $SuitAuth="SuitAuth";
}
if($filename=='companyregister'){
  $companyregister='companyregister';
}else if($filename=='viewcomregsaved'){
    $companyregister='viewcomregsaved';
}else if($filename=='viewcomreg'){
    $companyregister='viewcomreg';
}else if($_GET['submittedID']!=''){
    $companyregister='editexistingnew';
}
// else if($_GET['savedID']!=''){
//     $companyregister='editsavedview';
// }
else if($_GET['logcode']!='' && $_GET['Type']!=''){
$companyregister="viewcclogs";
}else if($_GET['enccompcode']!='' && $_GET['enctype']!=''){
$companyregister="newcopreg";
}else if($_GET['submittedIDNEW']!='' ){
$companyregister="viewcomregnew";
}else if($_GET['savedID']!='' ){
$companyregister="editsavedviewnew";
}

else if($filename=='Media_Scrub'){
  $Media_Scrub='Media_Scrub';
}else if($filename=='viewmedia'){
  $Media_Scrub='viewmedia';
}else if($_GET['flag']!=''){
  $Media_Scrub='editmedia';
}else if($filename=='archivedmedia'){
  $Media_Scrub='archivedmedia';
}else if($filename=='Client'){
  $clisetup='Client';
}else if($_GET['clisetupid']!=''){
  $clisetup='Client';
}else if($filename=='docreq'){
  $docreq='docreq';
}else if($filename=='viewdocreq'){
  $docreq='viewdocreq';
}else if($filename=='viewcompliance'){
  $docreq='viewcompliance';
}else if($filename=='batchfileupload'){
  $docreq='batchfileupload';
}else if($filename=='Approve'){//
  $approve ='Approve';
}
else if($filename=='Invoicing_UI'){
  $invoice_ui ='Invoicing_UI';
}else if($filename=='manageSettlement'){
  $managesettlement="manageSettlement";
}
else if($filename=='uploaddownloadlog'){
  $uploaddownloadlog ='uploaddownloadlog';
}else if($filename=='downloadlog'){
  $uploaddownloadlog ='downloadlog';
}
else if($filename=='docreqlog'){
  $docreqlog ='docreqlog';
}

 if($filename=='viewcontact'){
  $viewcontact ='viewcontact';
}else if($filename=='contactregister'){
  $contactregister ='contactregister';
}else if($_GET['submittedID']!=''){
$viewcontact="editcontact";
}else if($_GET['submittedIDNEW']!=''){
$viewcontact="viewcontactnew";
}else if($filename=='companyregisternew'){
  $companyregisternew ='companyregisternew';
}
else if($filename=='schedularlogs'){
  $schedularlogs ='schedularlogs';
}

if($filename=='viewclient'){
  $clientonboarding="viewclient";
}else if($filename=='clientadd'){
  $clientonboarding="clientadd";
}else if($_GET['GetName']!=''){
$clientonboarding="clientadd";
}


if($filename=='Reportpdf'){
$Reportpdf="Reportpdf";
}


/* query to show lonk based on assigned roles*/
if($_SESSION['userType']==1 && $_SESSION['newuserType']==''){
  $usertype=$_SESSION['userType'];
}else if($_SESSION['userType']==1 && $_SESSION['newuserType']==1){
   $usertype=$_SESSION['newuserType'];
}else if($_SESSION['userType']==2 && $_SESSION['newuserType']==''){
    $usertype=$_SESSION['userType'];
}else if($_SESSION['userType']==1 && $_SESSION['newuserType']==2){
   $usertype=$_SESSION['newuserType'];
} else if($_SESSION['userType']==3 && $_SESSION['newuserType']==''){
    $usertype=$_SESSION['userType'];
}else if($_SESSION['userType']==1 && $_SESSION['newuserType']==3){
   $usertype=$_SESSION['newuserType'];
}else if($_SESSION['userType']==4 && $_SESSION['newuserType']==''){
    $usertype=$_SESSION['userType'];
}else if($_SESSION['userType']==1 && $_SESSION['newuserType']==4){
   $usertype=$_SESSION['newuserType'];
}  
if($_SESSION['role']==6){
    $roleque="WHERE ADMIN='Y' AND USERCODE='".$usertype."'";
}else if($_SESSION['role']==8){
    $roleque="WHERE EXECUTIVE='Y' AND USERCODE='".$usertype."'";
}else if($_SESSION['role']==4){
    $roleque="WHERE MANAGER='Y' AND USERCODE='".$usertype."'";
}else if($_SESSION['role']==7){
    $roleque="WHERE ACCT_USER='Y' AND USERCODE='".$usertype."'";
}else if($_SESSION['role']==5){
    $roleque="WHERE USER='Y' AND USERCODE='".$usertype."'";
}else if($_SESSION['role']==9){
    $roleque="WHERE COLLECTOR='Y' AND USERCODE='".$usertype."'";
}
include "config.php";  
$menuQuery="SELECT DISTINCT MENU_ITEM FROM USER_CONTROL_FILE ".$roleque."";
 // echo $menuQuery;
 // die;
$resmenu=mysqli_query($conn,$menuQuery);
$resmenucli=mysqli_query($conn,$menuQuery);
$resmenudoct=mysqli_query($conn,$menuQuery);
$resmenubatchreport=mysqli_query($conn,$menuQuery);
$resmenuinvoicing=mysqli_query($conn,$menuQuery);

$submenuQuery="SELECT DISTINCT SUB_MENU_ITEM FROM USER_CONTROL_FILE ".$roleque." order by SUB_MENU_ITEM Asc";//echo $submenuQuery;die;
$ressubmenu=mysqli_query($conn,$submenuQuery);
$ressubmenucli=mysqli_query($conn,$submenuQuery); 
$ressubmenudoct=mysqli_query($conn,$submenuQuery);  
$ressubmenumanage=mysqli_query($conn,$submenuQuery);   
$ressubmenucontact=mysqli_query($conn,$submenuQuery); 
$ressubmenucompany=mysqli_query($conn,$submenuQuery);         
$ressubmenucontrolfile=mysqli_query($conn,$submenuQuery); 
$ressubmenutext=mysqli_query($conn,$submenuQuery); 
$ressubmenucontat=mysqli_query($conn,$submenuQuery); 
$ressubmenumediascrub=mysqli_query($conn,$submenuQuery); 
$ressubmenureports=mysqli_query($conn,$submenuQuery);
$ressubmenustate=mysqli_query($conn,$submenuQuery);
$ressubmenubatchreport=mysqli_query($conn,$submenuQuery);
$ressubmenuinvoicing=mysqli_query($conn,$submenuQuery);

while($contact               =mysqli_fetch_assoc($ressubmenucontat)){
$contactarray[]              =$contact['SUB_MENU_ITEM'] ;
// print_r($contactarray); 
}

?>
  <script src="/bi/dist/js/sweetalert.min.js"></script>
  <!-- <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script> -->
  <!---<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>---->
  <link rel="stylesheet" href="/bi/dist/css/sweetalert.css">
  <style>
  #block{display:block!important;margin-bottom: 15px;}
  #clientnone{display:block!important;}
  </style>
<section class="sidebar">
    <ul class="sidebar-menu menu">
     <?php while($fetchmenu=mysqli_fetch_assoc($resmenu)){
           
        if($fetchmenu['MENU_ITEM']=='DASHBOARD'){
           
     ?>
        <li class="treeview">
            <a href="#"><i class="fa fa-tachometer"></i> <span>Dashboard</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                 <?php  
                 while($fetchsubmenu=mysqli_fetch_assoc($ressubmenu)){ 
                if($fetchsubmenu['SUB_MENU_ITEM']=='Inventory'){?>
                <li><a href="/bi/dist/inventory_layout" id="inventory_layout"><i class="fa fa-bar-chart"></i> <span>Inventory</span></a></li>
                  <?php } if($fetchsubmenu['SUB_MENU_ITEM']=='InvChartBatches'){?>
                <li><a href="/bi/dist/chart_layout" id="chart_layout"><i class="fa fa-pie-chart"></i> <span>Inv Chart Batches</span></a></li>
                   <?php } if($fetchsubmenu['SUB_MENU_ITEM']=='Dollars'){?>
                 <li><a href="/bi/dist/dollars_layout_dash" id="dollars_layout_dash"><i class="fa fa-usd"></i> <span>Dollars</span></a></li>
                  <li><a href="/bi/dist/dollars_layout_liq" id="dollars_layout_liq"><i class="fa fa-money" aria-hidden="true"></i> <span>Liquidation</span></a></li>
                  
                 <?php } if($fetchsubmenu['SUB_MENU_ITEM']=='JudgPerf'){?>
                <li><a href="/bi/dist/judgment_layout" id="judgment_layout"><i class="fa fa-exclamation-circle"></i> <span>Judgment Performance</span></a></li>
                  <?php } if($fetchsubmenu['SUB_MENU_ITEM']=='HeatMaps'){?>
                <li><a href="/bi/dist/heatmap_layout" id="heatmap_layout"><i class="fa fa-map"></i> <span>Heat-Maps</span></a></li>
                  <?php } if($fetchsubmenu['SUB_MENU_ITEM']=='Timelines'){?>
                <li><a href="/bi/dist/timeline_layout" id="timeline_layout"><i class="fa fa-line-chart"></i> <span>Timeline</span></a></li>
                 <?php } } ?>
            </ul>
        </li>
         <?php }  //end of dashboard if
          if($fetchmenu['MENU_ITEM']=='REPORTS'){?>
        <li><a href="/bi/dist/reports" id="reports"><i class="fa fa-file-text-o" aria-hidden="true"></i> <span>Reports</span></a></li>
        <?php  }  
        if($fetchmenu['MENU_ITEM']=='FAQ'){ ?>
        <li><a href="/bi/dist/FAQ/FAQ" id="FAQ"><i class="fa fa-question-circle"></i> <span>FAQ</span></a></li>
          <?php  } if($fetchmenu['MENU_ITEM']=='NOTICES'){ ?>
        <li><a href="/bi/dist/NOTICE/NOTICE" id="NOTICE"><i class="fa fa-pencil-square"></i> <span>Notices</span></a></li>
         <?php  } if($fetchmenu['MENU_ITEM']=='STATE_ISSUES'){?>
        <li class="treeview"><a href="/bi/dist/State_issues/state-issues"><i class="fa fa-map-marker"></i> <span>State Issues</span>
             <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
          <ul class="treeview-menu">
            <?php while($fetchsubmenustate=mysqli_fetch_assoc($ressubmenustate)){ 
              if($fetchsubmenustate['SUB_MENU_ITEM']=='Viewstateissues'){?>
              <li><a href="/bi/dist/State_issues/state-issues" id="<?php echo $stateissues;?>">View State</a></li>
            <?php } if($fetchsubmenustate['SUB_MENU_ITEM']=='EditStateIssues'){ ?>
              <li><a href="/bi/dist/State_issues/Stateuinew" id="<?php echo $stateEdit; ?>">State Edit</a></li>
            <?php } } ?>
            </ul>

        </li>
          <?php } }?>

       <?php /*for client guide======================================================================*/?>
         <?php while($fetchmenucli=mysqli_fetch_assoc($resmenucli)){ 
        if($fetchmenucli['MENU_ITEM']=='CLIENT_GUIDE'){?>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-list"></i>
                <span> Client Guide</span>
                <span class="pull-right-container">  
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                 <?php  
                 while($fetchsubmenucli=mysqli_fetch_assoc($ressubmenucli)){ 
                  if($fetchsubmenucli['SUB_MENU_ITEM']=='Edit'){  ?>
                <li>
                    <a href="/bi/dist/CLIENTGUIDE/AdminlistingGuide" id="<?php echo  $viewGui;?>">View Edits</a>
                </li>
                 <?php }  if($fetchsubmenucli['SUB_MENU_ITEM']=='View'){ ?>
                <li>
                    <a href="/bi/dist/CLIENTGUIDE/viewlistingguide" id="<?php echo $viewlistingguide; ?>">View Client Guide</a>
                </li>
                 <?php } }?>
                 
            </ul>
        </li>
         <?php } } ?>

    <?php while($fetchmenubatchreport=mysqli_fetch_assoc($resmenubatchreport)){
          
         if($fetchmenubatchreport['MENU_ITEM']=='SCHEDULE_BATCH_REPORT'){?>
            <li class="treeview">
            <a href="#">
                <i class="fa fa-clock-o"></i>
                <span>Schedule Batch Report</span>
                <span class="pull-right-container">  
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
            <?php  while($fetchsubmenbatchrep=mysqli_fetch_assoc($ressubmenubatchreport)){ //print_r($fetchsubmenbatchrep);
                  if($fetchsubmenbatchrep['SUB_MENU_ITEM']=='ViewScheduledReport'){  ?>  
                <li>
                    <a href="/bi/dist/Report/ViewReport" id="<?php echo $view_report;?>">View Scheduled Report</a>
                </li>
                 <?php }if($fetchsubmenbatchrep['SUB_MENU_ITEM']=='ViewQueryReport'){  ?>  
                <li>
                    <a href="/bi/dist/Report/Scheduler_Edit" id="<?php echo $scheduler_edit; ?>">View Query Report</a>
                </li>

                <?php }if($fetchsubmenbatchrep['SUB_MENU_ITEM']=='RunManualReport'){?>
                <li>
                    <a href="/bi/dist/Report/RunManualReport" id="<?php echo $manual_report; ?>">Manual Report Run</a>
                </li>
              <?php } if($fetchsubmenbatchrep['SUB_MENU_ITEM']=='SFTPDetails'){  ?> 
                 <li>
                    <a href="/bi/dist/Report/SFTP" id="<?php echo $sftp_details; ?>">SFTP Details</a>
                </li>
              <?php } }?>
                <li>
                    <a href="/bi/dist/Report/schedularlogs" id="<?php echo $schedularlogs; ?>">Schedular Logs</a>
                </li>
            </ul>
        </li>
        <?php } } ?>
    
       <?php /*for document transfer======================================================================   */?>
        <?php 
        while($fetchmenudoct=mysqli_fetch_assoc($resmenudoct)){ 
        if($fetchmenudoct['MENU_ITEM']=='DOC_TRANSFER'){?>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-files-o"></i>
                <span>Document Transfer </span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                 <?php  
                  while($fetchsubmenudoct=mysqli_fetch_assoc($ressubmenudoct)){ 
                   if($fetchsubmenudoct['SUB_MENU_ITEM']=='MyUploads'){?>
                <li>
                    <a href="/bi/dist/mydownload" id="<?php echo $mydownload; ?>"><img src="/bi/dist/img/file-download.png" alt="My Downloads" style="width: 13px; margin-right: 7px;">My Downloads</a>
                </li>
                  <?php }  if($fetchsubmenudoct['SUB_MENU_ITEM']=='MyDownloads'){ ?>
                <li><a href="/bi/dist/myupload" id="<?php echo $myupload; ?>"><img src="/bi/dist/img/file-upload.png" alt="My Uploads" style="width: 13px; margin-right: 7px;">My Uploads</a></li>
                  <?php } if($fetchsubmenudoct['SUB_MENU_ITEM']=='DocumentRequest'){ ?>
				     <!--  <li><a href="/bi/dist/MediaManagement/viewdocreq" alt="Document Request" id="<?php echo $docreq; ?>"><i class="fa fa-file-o"  aria-hidden="true"></i>Document Request </a></li> -->
               <?php } if($fetchsubmenudoct['SUB_MENU_ITEM']=='UploadDownloadLog'){ ?>
				      <li><a href="/bi/dist/uploaddownloadlog" alt="Upload Download Log" id="<?php echo $uploaddownloadlog; ?>"><i class="fa fa-file-o"  aria-hidden="true"></i>Company Upload/Download</a></li>
               <?php }
              }?>
            </ul>
        </li>
        <?php } } 
        /*for document transfer ends here ======================================================================*/?>
       
 

        <!----------Admin tab starts here------------------------------------------------------------>
         <?php if ((in_array("Managefaq", $contactarray)) || (in_array("Managenotice", $contactarray)) || (in_array("MangeUsers", $contactarray)) || (in_array("CompanyRegistry", $contactarray)) || (in_array("ManageBOS", $contactarray)) || (in_array("ManageLicense", $contactarray)) || (in_array("SetUpParameters", $contactarray)) || (in_array("ManageEmail", $contactarray)) || (in_array("ManageText", $contactarray)) || (in_array("Media_scrub", $contactarray)) || (in_array("ManageSettlement",$contactarray)) ||  (in_array("ManageDocumentLog",$contactarray))) {?>
           <li class="treeview">
                <a href="#"><i class="fa fa-user-circle"></i> <span>Administration</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>

                <ul class="treeview-menu">
                     <?php while($fetchsubmenumanage=mysqli_fetch_assoc($ressubmenumanage)){ 
                        if($fetchsubmenumanage['SUB_MENU_ITEM']=='Managefaq'){  ?>
                        <li><a href="/bi/dist/FAQ/viewFaq" id="<?php echo $idf; ?>"><i class="fa fa-question-circle"></i> <span>Manage FAQ</span></a></li>
                    <?php } if($fetchsubmenumanage['SUB_MENU_ITEM']=='Managenotice'){ ?>
                        <li><a href="/bi/dist/NOTICE/viewNotice" id="<?php echo $id1;?>"><i class="fa fa-pencil-square"></i> <span>Manage Notice</span></a></li>
                      <?php }   ?>
                      <?php if($fetchsubmenumanage['SUB_MENU_ITEM']=='Manage'){ ?>
                      <li><a href="/bi/dist/State_issues/viewStateIssue" id="<?php echo $viewStateIssue; ?>" ><i class="fa fa-gavel "></i> <span>Manage State Issues</span></a></li>
                    <?php }
                    if($fetchsubmenumanage['SUB_MENU_ITEM']=='ManageSettlement'){ ?>
                      <li><a href="/bi/dist/manageSettlement" id="<?php echo $managesettlement; ?>" ><i class="fa fa-handshake-o"></i> <span>Manage Settlements</span></a></li>
                    <?php }  if($fetchsubmenumanage['SUB_MENU_ITEM']=='ManageDocumentLog'){ ?>
                      <li><a href="/bi/dist/MediaManagement/docreqlog" id="<?php echo $docreqlog; ?>" ><i class="fa fa-file-o"></i> <span>Document Request Log</span></a></li>
                    <?php }

                 }?>

                 <?php 
                  
                   if ((in_array("MangeUsers", $contactarray)) || (in_array("CompanyRegistry", $contactarray)) || (in_array("ManageBOS", $contactarray)) || (in_array("ManageLicense", $contactarray))) {?>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-address-book"></i>
                            <span>Manage Company</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                             <?php 
                             while($fetchsubmenucontact=mysqli_fetch_assoc($ressubmenucontact)){ 
                             if($fetchsubmenucontact['SUB_MENU_ITEM']=='MangeUsers'){?>
                              <li><a href="/bi/dist/companyregisternew" id="<?php echo $companyregisternew; ?>"><i class="fa fa-circle-o"></i> Add Company</a></li>
                           <?php } if($fetchsubmenucontact['SUB_MENU_ITEM']=='CompanyRegistry'){ ?>
                             <li><a href="/bi/dist/viewcomreg" id="<?php echo $companyregister;?>"><i class="fa fa-circle-o"></i> Company Register</a></li>

                        <?php } 
                        if($fetchsubmenucontact['SUB_MENU_ITEM']=='ManageBOS'){?>
                          <li>
                           <a href="/bi/dist/CLIENTGUIDE/viewBos" id="<?php echo $viewBos;?>"><i class="fa fa-circle-o"></i>View Bill Of Sale</a>
                         </li>
                           <?php }  if($fetchsubmenucontact['SUB_MENU_ITEM']=='ManageLicense'){?>
                         <li>
                           <a href="/bi/dist/CLIENTGUIDE/viewLicenseMatrix" id="<?php echo $viewLicenseMatrix;?>"><i class="fa fa-circle-o"></i>View License Matrix</a>
                          </li>
                            <?php } }?>

                        </ul>
                    </li>
                     <?php }?>
                     <?php 
                  
                   if ((in_array("MangeUsers", $contactarray)) || (in_array("CompanyRegistry", $contactarray)) || (in_array("ManageBOS", $contactarray)) || (in_array("ManageLicense", $contactarray))) {?>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-address-book"></i>
                            <span>Manage Contact</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                             <?php 
                             while($fetchsubmenucompany=mysqli_fetch_assoc($ressubmenucompany)){ 

                                if($fetchsubmenucompany['SUB_MENU_ITEM']=='MangeUsers'){?>
                              <li><a href="/bi/dist/view" id="<?php echo $id; ?>"><i class="fa fa-circle-o"></i> Manage User</a></li>
                           <?php }
                             if($fetchsubmenucompany['SUB_MENU_ITEM']=='ViewContact'){?>
                              <li><a href="/bi/dist/viewcontact" id="<?php echo $viewcontact; ?>"><i class="fa fa-circle-o"></i>View Contact</a></li>
                           <?php } if($fetchsubmenucompany['SUB_MENU_ITEM']=='AddContact'){ ?>
                             <li><a href="/bi/dist/contactregister" id="<?php echo $contactregister;?>"><i class="fa fa-circle-o"></i>Add Contact</a></li>

                        <?php }  }?>

                        </ul>
                    </li>
                     <?php }?>
                        <?php 
               if ((in_array("ManageParameters", $contactarray)) || (in_array("ManageEmail", $contactarray)) || (in_array("ManageText", $contactarray))|| (in_array("Media_scrub", $contactarray))) {?>
                <li class="treeview">
                  <a href="#"><i class="fa fa-envelope"></i> <span>Control File</span>
                 <span class="pull-right-container">
                 <i class="fa fa-angle-left pull-right"></i>
                 </span>
                  </a>
                    <ul class="treeview-menu">
                <?php
               if ((in_array("ManageParameters", $contactarray)) || (in_array("ManageEmail", $contactarray))) {?>
                     <li class="treeview">
                     <a href="#"> SIF Form Control 
                       <span class="pull-right-container">
                       <i class="fa fa-angle-left pull-right"></i>
                     </span>
                   </a>
                    <ul class="treeview-menu">
                       <?php  
                      while($fetchsubmenucontrolfile=mysqli_fetch_assoc($ressubmenucontrolfile)){ 
                       if($fetchsubmenucontrolfile['SUB_MENU_ITEM']=='ManageParameters' ){?>
                       <li class="active"><a href="/bi/dist/Email_control/WSCLTCNT" id="<?php echo $WSCLTCNT;?>"><i class="fa fa-circle-o"></i> Manage SIF Parameters</a></li>
                     <?php }  if($fetchsubmenucontrolfile['SUB_MENU_ITEM']=='ManageEmail'){?>
                      <li><a href="/bi/dist/Email_control/WSEMAILCNT" id="<?php echo $WSEMAILCNT;?>"><i class="fa fa-circle-o"></i> Manage Email Content</a></li>
                    <?php } } ?>

                   </ul>
                 </li>
                  
                <?php } if ((in_array("ManageText", $contactarray))) {
                  while($fetchsubmenutext=mysqli_fetch_assoc($ressubmenutext)){
                 if($fetchsubmenutext['SUB_MENU_ITEM']=='ManageText'){?>
                <li class="treeview">
                <a href="/bi/dist/Email_control/viewSuitAuth" id="<?php echo $SuitAuth;?>"> Client Guide Text  </a> </li>
              <?php } } ?>

              <?php }  if ((in_array("Media_scrub", $contactarray))) {
                  while($fetchsubmenumediascrub=mysqli_fetch_assoc($ressubmenumediascrub)){
                 if($fetchsubmenumediascrub['SUB_MENU_ITEM']=='Media_scrub'){?>
                <li class="treeview">
                <a href="/bi/dist/Report/viewmedia" id="<?php echo $Media_Scrub;?>">Media Scrub </a> </li>
              <?php } } } ?> 

                </ul>
                </li>
              <?php }  ?>

                <li class="treeview">
                  <a href="#"><i class="fa fa-envelope"></i> <span>Client Onboarding</span>
                 <span class="pull-right-container">
                 <i class="fa fa-angle-left pull-right"></i>
                 </span>
                  </a>
                 
                    <ul class="treeview-menu">
                     
                       <li class="active"><a href="/bi/dist/CLIENT_ONBOARDING/viewclient" id="<?php echo $clientonboarding;?>"><i class="fa fa-circle-o"></i>Client Status</a></li>
                   

                   </ul>

            </li>

         <?php }?>

     

            <!----------Admin tab ends here------------------------------------------------------------>
             <!----Start Invoicing Tool---------------->
            <?php if($_SESSION['userType']==1){?>
      <li class="treeview">
            <a href="#">
            <i class="fa fa-file-code-o"></i>
            <!-- <img src="/bi/dist/img/Invoice icon.png" style="width: 15px;height: 15px;"> -->
            <span>Invoicing Tool</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
           
                <li class="treeview"><a href="/bi/dist/Invoicing/Reportpdf" id="<?php echo $Reportpdf; ?>">Report</a></li>
                
             
            </ul>
        </li>
      <?php } ?>
        <!----End Invoicing Tool---------------->

    </ul>

                <div class="lft-login col-xs-12">

                  <div class="login-each">
                    <ul class="nav nav-tabs" id="clientnone">
                      <?php 
                      if($_SESSION['val1']!=''){
                        $advancenameacc="active";
                        $value=$_SESSION['advancenameacc'];
                      }if($_SESSION['val2']!=''){
                        $name="active";
                        $value=$_SESSION['name'];
                      }if($_SESSION['val3']!=''){
                        $account="active";
                        $value=$_SESSION['accountno'];
                      }if($_SESSION['val1']=='' && $_SESSION['val2']=='' && $_SESSION['val3']==''){
                        $advancenameacc="active";
                        $value='';
                      }
                      ?>
                      <li class="<?php echo $advancenameacc;?>" id="advanceid"><a href="#lg-advance" data-toggle="tab">Advance</a></li>
                      <li class="<?php echo $name;?>" id="nameid"><a href="#lg-name" data-toggle="tab">Name</a></li>
                      <li class="<?php echo $account;?>" id="accountid"><a href="#lg-account" data-toggle="tab">Account</a></li>
                    </ul>

               
                    <div id="myTabContent" class="tab-content">
                        <div class="tab-pane <?php echo $advancenameacc;?> in" id="lg-advance">
                          <form id="advanceForm" class="form-signin" action="" method="post">
                                <div class="form-group">
                                    <input type="text" name="advancenameacc" class="form-control" placeholder="" autofocus="" value="<?php echo $value;?>" id="advancenameacc" required maxlength="25">
                                    <div style="color:red" id="advanceError"></div>
                                    <p class="last-first-option"></p>
                                </div>
                                 <button name="advancesubmit" id="advancesubmit" class="btn btn-lg btn-primary btn-block pw-btn-signin" type="button">Submit</button>
                              <!--  <input name="advancesubmit" id="advancesubmit" class="btn btn-lg btn-primary btn-block pw-btn-signin" type="submit"> -->
                            </form>
                      </div>
                    <!--  /bi/dist/search -->
                     <div class="tab-pane <?php echo $name;?>" id="lg-name">
                          <form id="nameForm" class="form-signin" action="" method="post">
                                <div class="form-group">
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Last Name, First Name" autofocus="" value="<?php echo $value;?>" required maxlength="25">
                                    <div style="color:red" id="nameError"></div>
                                    <p class="last-first-option">Last Name, First Name (Optional)</p>
                                </div>
                                <!-- <input name="namesubmit" class="btn btn-lg btn-primary btn-block pw-btn-signin" type="submit" > -->
                                <button name="namesubmit" id="namesubmit" class="btn btn-lg btn-primary btn-block pw-btn-signin" type="button">Submit</button>
                            </form>
                      </div> 
                    <!--    /bi/dist/searchacc -->
                      <div class="tab-pane <?php echo $account;?>" id="lg-account">
                        <form id="accountForm" class="form-signin" method="post" action="/bi/dist/searchacc">
                                <div class="form-group">
                                    <input type="text" name="accountno" id="accountno" class="form-control"  value="<?php echo $value;?>" required maxlength="20">
                                    <div style="color:red" id="accountError"></div>
                                </div>

                                
                                
                                
                                    <select class="form-control" id="block" style="display: inherit!important;">
                                    <?php
                                     if ($_SESSION['userType']==1) {?>
                                       <option value="accountdetail" <?=$_SESSION['pagename'] == 'accountdetail' ? ' selected="selected"' : '';?>>Account Detail</option>
                                    <option value="documentation" <?=$_SESSION['pagename'] == 'documentation' ? ' selected="selected"' : '';?>>Documentation</option>
                                    <option value="portfoliodocs" <?=$_SESSION['pagename'] == 'portfoliodocs' ? ' selected="selected"' : '';?>>Portfolio Docs</option>
                                    <option value="settlementform" <?=$_SESSION['pagename'] == 'settlementform' ? ' selected="selected"' : '';?>>Settlement Form</option>
                                    <option value="statusupdate" <?=$_SESSION['pagename'] == 'statusupdate' ? ' selected="selected"' : '';?>>Status Update</option>
                                    <?php } else if($_SESSION['userType']==2){?>
                                    <option value="accountdetail" <?=$_SESSION['pagename'] == 'accountdetail' ? ' selected="selected"' : '';?>>Account Detail</option>
                                    <option value="documentation" <?=$_SESSION['pagename'] == 'documentation' ? ' selected="selected"' : '';?>>Documentation</option>
                                    <option value="portfoliodocs" <?=$_SESSION['pagename'] == 'portfoliodocs' ? ' selected="selected"' : '';?>>Portfolio Docs</option>
                                    <option value="settlementform" <?=$_SESSION['pagename'] == 'settlementform' ? ' selected="selected"' : '';?>>Settlement Form</option>
                                    <option value="statusupdate" <?=$_SESSION['pagename'] == 'statusupdate' ? ' selected="selected"' : '';?>>Status Update</option>

                                    <?php } else if($_SESSION['userType']==3){?>
                                    <option value="accountdetail" <?=$_SESSION['pagename'] == 'accountdetail' ? ' selected="selected"' : '';?>>Account Detail</option>
                                    <option value="documentation" <?=$_SESSION['pagename'] == 'documentation' ? ' selected="selected"' : '';?>>Documentation</option>
                                    <option value="portfoliodocs" <?=$_SESSION['pagename'] == 'portfoliodocs' ? ' selected="selected"' : '';?>>Portfolio Docs</option>
                                    <option value="settlementform" <?=$_SESSION['pagename'] == 'settlementform' ? ' selected="selected"' : '';?>>Settlement Form</option>
                                    <option value="statusupdate" <?=$_SESSION['pagename'] == 'statusupdate' ? ' selected="selected"' : '';?>>Status Update</option>

                                    <?php }
                                       else{?>
                                     <option value="accountdetail" <?=$_SESSION['pagename'] == 'accountdetail' ? ' selected="selected"' : '';?>>Account Detail</option>
                                    <option value="documentation" <?=$_SESSION['pagename'] == 'documentation' ? ' selected="selected"' : '';?>>Documentation</option>
                                    <option value="portfoliodocs" <?=$_SESSION['pagename'] == 'portfoliodocs' ? ' selected="selected"' : '';?>>Portfolio Docs</option>
                                    
                                    <option value="statusupdate" <?=$_SESSION['pagename'] == 'statusupdate' ? ' selected="selected"' : '';?>>Status Update</option>
                                      <?php }
                                    ?>
                                  
                                </select>
                                
                               

                               
                          
                              <button name="accsubmit" id="accsubmit" class="btn btn-lg btn-primary btn-block pw-btn-signin" type="button">Submit</button>
                        </form>
                      </div>
                      
                  </div>

               </div>

    </div>
</section>

<script>
    $(document).ready(function() {

        var url = window.location.href;

        url = url.substring(0, (url.indexOf("#") == -1) ? url.length : url.indexOf("#"));

        url = url.substring(0, (url.indexOf("?") == -1) ? url.length : url.indexOf("?"));

        url = url.substr(url.lastIndexOf("/") + 1);
        //url = url.substring(0, url.lastIndexOf('.'));
        //alert(url);
        if (url == '') {
            url = 'index.html';
        }

        $('.menu li').each(function() {
            // var href = $(this).find('a').attr('href');
            // var split_url = href.split("/");
            // var after_slash = split_url[1];
            var getid = $(this).find('a').attr('id');
            //alert(url+'==='+getid);
            if (url == getid) {
                //alert(url == after_slash && getid);
                $(this).addClass('active');
                $(this).parents().addClass('active');
            }
        });
$('#advanceid').click(function(){
    $('#accountid').removeClass('active');
    $('#nameid').removeClass('active')
    $('#advanceid').addClass('active')
    $('#lg-advance').addClass('active')
    $('#lg-name').removeClass('active')
    $('#lg-account').removeClass('active')
    $('#advancenameacc').val('');
})
$('#nameid').click(function(){
    $('#accountid').removeClass('active');
    $('#nameid').addClass('active')
    $('#advanceid').removeClass('active')
    $('#lg-advance').removeClass('active')
    $('#lg-name').addClass('active')
    $('#lg-account').removeClass('active')

   $('#name').val('');
})
$('#accountid').click(function(){
    $('#accountid').addClass('active');
    $('#nameid').removeClass('active')
    $('#advanceid').removeClass('active')
    $('#lg-advance').removeClass('active')
    $('#lg-name').removeClass('active')
    $('#lg-account').addClass('active')
    $('#accountno').val('');
    $('#block').val('accountdetail');
})

/* for advance search*/
$('#advancenameacc').on('keyup',function(){
  if ($('#advancenameacc').val() == '') {
      $('#advanceError').css('display', 'block');
      $('#advanceError').text('This field is required'); 
     }else{
       $('#advanceError').css('display', 'none');
     }
})
function validateadvance() {
    var flag = true; 
    if ($('#advancenameacc').val() == '') {
      $('#advanceError').css('display', 'block');
      $('#advanceError').text('This field is required');
      $("#advancesubmit").show();
      flag = false;
     }
     if (flag) {
        return true;
    } else {
        return false;
    }
  }
   $('#advancesubmit').on('click', function(e, params) {
      $("#advancesubmit").hide();
        var localParams = params || {};
		// console.log(e);
        if (!localParams.send) {
            e.preventDefault();
        }
        if(validateadvance()){
       var advancenameacc      =$('#advancenameacc').val();
      $.ajax({
        type: 'POST',
        url: '/bi/dist/countsearch.php',
        data: {advancenameacc:advancenameacc},
        beforeSend: function() {
              $("#loading-image").show();
           },
        success: function (data) {
       $("#loading-image").hide();
      if(data ==0){
         swal("Error!", "Your search returned no record.  Please check your search and try again.", "error");
          $("#advancesubmit").show();
        }
       if(data <= 1000 && data > 0){
          window.location.href = "/bi/dist/advanceSearch";  
        }if(data >1000 && data <= 5000){
        swal({
          title: "Confirm Entry",
          text: "Your search contained "+ data+" records and may take time to populate.  Please confirm if you wish to continue or cancel to refine your search.",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#6A9944",
          confirmButtonText: "Confirm",
          cancelButtonText: "Cancel",
          closeOnConfirm: true
        }, function(isConfirm){ 
        if (isConfirm) {
         window.location.href = "/bi/dist/advanceSearch";
        } else {
          $("#advancesubmit").show();
        }
        });

         }if(data>5000){
           swal("Error!", "Your search will return "+data+ " records.  Please refine your search.", "error");
           $("#advancesubmit").show();
         }
        }
   }); 
   }     
});

	
	document.body.onkeydown=function(evt){
        var keyCode = evt ? (evt.which ? evt.which : evt.keyCode) : event.keyCode;
        if(keyCode == 13)
        {
			$("#advancesubmit").hide();
			
			if(validateadvance()){
			   var advancenameacc = $('#advancenameacc').val();
				$.ajax({
					type: 'POST',
					url: '/bi/dist/countsearch.php',
					data: {advancenameacc:advancenameacc},
					beforeSend: function() {
						  $("#loading-image").show();
					   },
					success: function (data){
						$("#loading-image").hide();
						if(data ==0){
						 swal("Error!", "Your search returned no record.  Please check your search and try again.", "error");
						  $("#advancesubmit").show();
						}
						if(data <= 1000 && data > 0){
						  window.location.href = "/bi/dist/advanceSearch";  
						}if(data >1000 && data <= 5000){
							swal({
							  title: "Confirm Entry",
							  text: "Your search contained "+ data+" records and may take time to populate.  Please confirm if you wish to continue or cancel to refine your search.",
							  type: "warning",
							  showCancelButton: true,
							  confirmButtonColor: "#6A9944",
							  confirmButtonText: "Confirm",
							  cancelButtonText: "Cancel",
							  closeOnConfirm: true
							}, function(isConfirm){ 
								if (isConfirm) {
									window.location.href = "/bi/dist/advanceSearch";
								} else {
									$("#advancesubmit").show();
								}
							});
						}if(data>5000){
							swal("Error!", "Your search will return "+data+ " records.  Please refine your search.", "error");
							$("#advancesubmit").show();
						}	
					}
				}); 
			}
        }
    }

		// });
	

/* for name search*/
$('#name').on('keyup',function(){
  if ($('#name').val() == '') {
      $('#nameError').css('display', 'block');
      $('#nameError').text('This field is required'); 
     }else{
       $('#nameError').css('display', 'none');
     }
})
  function validatename() {
    var flag = true; 
    if ($('#name').val() == '') {
      $('#nameError').css('display', 'block');
      $('#nameError').text('This field is required');
       $("#namesubmit").show();
      flag = false;
     }
     if (flag) {
        return true;
    } else {
        return false;
    }
  }
   $('#namesubmit').on('click', function(e, params) {
    $("#namesubmit").hide();
        var localParams = params || {};
        if (!localParams.send) {
            e.preventDefault();
        }
        if(validatename()){
       var name               =$('#name').val();
      $.ajax({
        type: 'POST',
        url: '/bi/dist/countsearch.php',
        data: {namesearch:name},
         beforeSend: function() {
              $("#loading-image").show();
           },
        success: function (data) {
         $("#loading-image").hide();
        if(data ==0){
          swal("Error!", "Your search returned no record.  Please check your search and try again.", "error");
           $("#namesubmit").show();
        }
       if(data <= 1000 && data > 0){
          window.location.href = "/bi/dist/search";  
        }if(data >1000 && data <= 5000){
          swal({
            title: "Confirm Entry",
            text: "Your search contained "+ data+" records and may take time to populate.  Please confirm if you wish to continue or cancel to refine your search.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#6A9944",
            confirmButtonText: "Confirm",
            cancelButtonText: "Cancel",
            closeOnConfirm: true
          }, function(isConfirm){ 
          if (isConfirm) {
            window.location.href = "/bi/dist/search";
          } else {
            $("#namesubmit").show();
          }
          });

         }if(data>5000){
         swal("Error!", "Your search will return "+data+ " records.  Please refine your search.", "error");
            $("#namesubmit").show();
         }
        }
   }); 
   }     
 });
/* for account search*/
$('#accountno').on('keyup',function(){
  if ($('#accountno').val() == '') {
      $('#accountError').css('display', 'block');
      $('#accountError').text('This field is required'); 
     }else{
       $('#accountError').css('display', 'none');
     }
})
  function validateacc() {
    var flag = true; 
    if ($('#accountno').val() == '') {
      $('#accountError').css('display', 'block');
      $('#accountError').text('This field is required');
      flag = false;
       $("#accsubmit").show();
     }
     if (flag) {
        return true;
    } else {
        return false;
    }
  }
   $('#accsubmit').on('click', function(e, params) {
        $("#accsubmit").hide();
        var localParams = params || {};
        if (!localParams.send) {
            e.preventDefault();
        }
        if(validateacc()){
       var accountno               =$('#accountno').val();
       var page                     =$('#block').val();
      $.ajax({
        type: 'POST',
        url: '/bi/dist/countsearch.php',
        data: {accountnosearch:accountno,page:page},
         beforeSend: function() {
              $("#loading-image").show();
           },
        success: function (data) {
           $("#loading-image").hide();
           $("#accsubmit").show();
          if(data ==0){
           swal("Error!", "Your search returned no record.  Please check your search and try again.", "error");
          $("#accsubmit").show();
           } if(data ==1){
             if(page=='accountdetail'){
             window.location.href ="/bi/dist/searchacc";
            }if(page=='documentation'){
               window.location.href ="/bi/dist/plcmntjudgmntdocs";
            }if(page=='portfoliodocs'){
              window.open("/bi/dist/CLIENTGUIDE/ViewClientGuidesearchacc", '_blank');
            }if(page=='settlementform'){
             window.open("/bi/dist/Settlement_Form/settlement-request", '_blank');
            }if(page=='statusupdate'){
              window.open("/bi/dist/StatusUpdate", '_blank');
             //window.location.href ="/bi/dist/StatusUpdate";
            }
           }
            if(data > 1){
           if(page=='accountdetail'){
             window.location.href ="/bi/dist/searchacc";
            }if(page=='documentation'){
               window.location.href ="/bi/dist/plcmntjudgmntdocs";
            }if(page=='portfoliodocs'){
              window.open("/bi/dist/CLIENTGUIDE/ViewClientGuidesearchacc", '_blank');
            }if(page=='settlementform'){
             window.open("/bi/dist/Settlement_Form/settlement-request", '_blank');
            }if(page=='statusupdate'){
             //window.location.href ="/bi/dist/StatusUpdate";
             window.open("/bi/dist/StatusUpdate", '_blank');
            }
 
          }
        }
   }); 
   }     
 });
});

</script>

    <!-- <script src="js/automatic_logout.js"></script> -->
<script src="<?php echo "https://" . $_SERVER['SERVER_NAME'] ?>/bi/dist/js/search_validation.js"></script>

<script>
    $('.input').keypress(function (e) {
  if (e.which == 13) {
    $('form #advanceForm').submit();
    return false;    //<---- Add this line
  }
});
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