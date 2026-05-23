<!-- /*
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
 */ -->

<!DOCTYPE>
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
      <script src="js/autologout.js"></script>
       <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="Stylesheet" type="text/css" />

       <script src="js/PSjquery.min.js"></script>
  <!--     <script type="text/javascript" src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script> -->
      <link rel="stylesheet" href="css/sweetalert.css">
      <script src="js/sweetalert.min.js"></script>
      <style>
         .container{width:100%;margin:0 auto;}
         .ui-widget-header {border: 0px solid #9e9e9e!important;background: lightgrey!important;}
         .ui-datepicker-calendar {display: block; }
         .makeRed{border: 1px solid red !important;}
         .main-footer{

         margin-left: 0px;
         }
      </style>
   </head>
   <body class="hold-transition skin-yellow sidebar-mini fixed">
      <?php
         error_reporting(1);
         session_start();
      
         if(!isset($_SESSION['email']))
         {
         header('Location: logout.php');
         exit();
         }
         require_once('config.php');
         //print_r($_SESSION);

         //AH18052026 : Starts
         //$acc =strtoupper($_SESSION['statusupdateaccno']);
         $acc = '';
         if(isset($_GET['acc']) && $_GET['acc'] != '')
         {
            $acc = strtoupper(base64_decode($_GET['acc']));
         }
         $acc = trim($accountNo);
         //AH18052026

      
         $query = "SELECT DEBTOR_FIRST_NME,DEBTOR_LAST_NME,CLIENT_NME,PORTFOLIO_DESC,ATTY_NAME,RMSACCTNUM ,ASSIGNED_DT,CUR_STATUS_DT,CUR_STATUS_CDE,CUR_STATUS_CDE_DESC,DATEDIFF(CURRENT_DATE(),CUR_STATUS_DT) as daysinStatus FROM MASTER_DATA_DB WHERE RMSACCTNUM='".$acc."'";//echo $query ;exit;
          $result = mysqli_query($conn,$query);
          $row=mysqli_fetch_array($result);
         
         //dropdown Query===================================================
         if($_SESSION['userType']=='1'){
          $dropQuery = "SELECT DISTINCT CONCAT(A.SYSCODETYP,' - ',B.SYSDESC) AS STATUS, A.STSLEVEL

          FROM AACASTS A

          LEFT JOIN RMSPSYSCDE B ON TRIM(A.SYSCODETYP) = TRIM(B.SYSCODETYP)

          WHERE A.STSLEVEL > (SELECT distinct STSLEVEL FROM AACASTS WHERE SYSCODETYP = '".$row['CUR_STATUS_CDE']."')

          AND B.SYSDESC IS NOT NULL

          AND LENGTH(TRIM(A.SYSCODETYP)) = 3

          ORDER BY A.STSLEVEL";//echo $dropQuery;exit;
      }else{
        $dropQuery = "SELECT DISTINCT CONCAT(A.SYSCODETYP,' - ',B.SYSDESC) AS STATUS, A.STSLEVEL

          FROM AACASTS A

          LEFT JOIN RMSPSYSCDE B ON TRIM(A.SYSCODETYP) = TRIM(B.SYSCODETYP)

          WHERE A.STSLEVEL > (SELECT distinct STSLEVEL FROM AACASTS WHERE SYSCODETYP = '".$row['CUR_STATUS_CDE']."')

          AND B.SYSDESC IS NOT NULL

          AND LENGTH(TRIM(A.SYSCODETYP)) = 3

          AND A.STSLEVEL NOT LIKE 'A%'

          ORDER BY A.STSLEVEL";
      }
		  $resultdrop = mysqli_query($conn,$dropQuery);
          

          $query_login = "SELECT * from tbl_login WHERE email='".$_SESSION['email']."'";
          $result_login = mysqli_query($conn,$query_login);
          $row1=mysqli_fetch_array($result_login);
          $WFUSER =$row1['fullName'].' '.$row1['LastName'];
          $WFUTYPE=$row1['userType'];
          if($WFUTYPE==1){
           $user='Admin';
          }else if($WFUTYPE==2){
           $user='Firm';
          }else if($WFUTYPE==3){
            $user='Client';
          }else if($WFUTYPE==4) {
            $user='Agency';
          }
          $date = date('m/d/Y');

          if(isset($_POST['submit'])){//print_r($_POST);
              $status=$_POST['Status'];
              $Effectivedate=DATE("Y-m-d",strtotime($_POST['datepicker-1']));
            
               $query1 = "INSERT INTO WFSTSUP
                         SELECT '".$Effectivedate."', RMSACCTNUM, RMSFILENUM, '".$status."', CONCAT_WS(' ',CUR_STATUS_CDE,CUR_STATUS_CDE_DESC), CURRENT_TIMESTAMP(), PORTFOLIO_CDE, '". $WFUSER."', '".$user."', CLIENT_CDE,ATTY_CDE, PORTFOLIO_CDE FROM MASTER_DATA_DB WHERE RMSACCTNUM ='".$acc."'";//echo $query1;exit;
     
               $result1 = mysqli_query($conn,$query1);

             

        ?>
         <script>setTimeout(function() {
           swal({title: "",
                text: "Record updated successfully",
                timer: 3000,
                showConfirmButton: false,
                type: 'success'
              },function() {
              	  window.location = window.close();
              	   });
          }, 1000);
         </script>
        <?php  }
                 
         ?>
      <div class="wrapper">
      
<div class="">
  <!--    <section class="content-header">
         <ol class="breadcrumb">
            <li><a href="inventory_layout"><i class="fa fa-home"></i> Home</a></li>
            <li class="active"><a href="searchacc"><i class="fa fa-file-text-o" aria-hidden="true"></i>StatusUpdate</a></i></li>
         </ol>
      </section> -->

      <section class="content">
      <div class="row">
      <div class="col-xs-12">
      <div class="box p0">
         <div class="box-body">
            <div class="col-md-12 p0">
               <form id="form1" runat="server" method="POST" >
               <section class="content">
                  <div class="row">
                     <div class="container">
                           <div class="col-md-12 p0">
                              <div align="center" colspan="5" class="p15_0">
                                 <img src="img/aaca-net.png" id="Image2" style="width:182px;">
                                 <br>

                                 <h3>STATUS UPDATE</h3>
                              </div>
                              <div class="col-md-12">
                                 <div class="col-md-3">
                                    <strong><span style="font-size: 10pt; font-family: Arial">Name</span></strong>
                                 </div>
                                 <div class="col-md-3">
                                    <span><?php echo $row['DEBTOR_FIRST_NME'].' '.$row['DEBTOR_LAST_NME'];?></span>
                                 </div>
                                 <div class="col-md-3">
                                    <strong><span style="font-size: 10pt; font-family: Arial;">Assigned Date</span></strong>
                                 </div>
                                 <div class="col-md-3">
                                    <span><?php echo date("m/d/Y",strtotime($row['ASSIGNED_DT']));?></span>
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <div class="col-md-3">
                                    <strong><span style="font-size: 10pt; font-family: Arial">Organization</span></strong>
                                 </div>
                                 <div class="col-md-3">
                                    <span><?php echo strtoupper($row['CLIENT_NME']);?></span>
                                 </div>
                                 <div class="col-md-3">
                                    <strong><span style="font-size: 10pt; font-family: Arial;">Days in Status</span></strong>
                                 </div>
                                 <div class="col-md-3"><span><?php echo $row['daysinStatus'];?></span></div>
                              </div>
                              <div class="col-md-12">
                                 <div class="col-md-3">
                                    <strong><span style="font-size: 10pt; font-family: Arial">Portfolio</span></strong>
                                 </div>
                                 <div class="col-md-3">
                                    <span><?php echo strtoupper($row['PORTFOLIO_DESC']);?></span>
                                 </div>
                                 <div class="col-md-3">
                                    <strong><span style="font-size: 10pt; font-family: Arial;">Last Activity</span></strong>
                                 </div>
                                 <div class="col-md-3">
                                    <span><?php echo date("m/d/Y",strtotime($row['CUR_STATUS_DT']));?></span>
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <div class="col-md-3">
                                    <strong><span style="font-size: 10pt; font-family: Arial">Attorney</span></strong>
                                 </div>
                                 <div class="col-md-3">
                                    <span><?php echo strtoupper($row['ATTY_NAME']);?></span>
                                 </div>
                                 <div class="col-md-3">
                                    <strong><span style="font-size: 10pt; font-family: Arial;">Current Status</span></strong>
                                 </div>
                                 <div class="col-md-3">
                                    <span><?php echo $row['CUR_STATUS_CDE'].' - '.$row['CUR_STATUS_CDE_DESC'];?></span>
                                 </div>
                              </div>
                              <tr>
                                 <td style="width: 95px; height: 21px">
                                    <strong><span style="font-size: 10pt; font-family: Arial"></span></strong>
                                 </td>
                                 <td style="width: 175px; height: 21px"></td>
                                 <td style="width: 25px; height: 21px"></td>
                                 <td style="width: 95px; height: 21px"></td>
                                 <td style="width: 200px; height: 21px"></td>
                              </tr>
                              <div class="col-md-12">
                                 <div class="col-md-3">
                                    <span style="font-size: 10pt; font-family: Arial"><strong>Account #</strong></span>
                                 </div>
                                 <div class="col-md-3">
                                    <span><?php echo strtoupper($row['RMSACCTNUM']);?></span>
                                 </div>
                              </div>
                              <div class="col-md-12">
                                 <div class="col-md-3">
                                    <span style="font-size: 10pt; font-family: Arial;"><strong>Effective Date</strong></span>
                                 </div>
                                 <div class="col-md-3">
                                    <span>
                                       <span style="display: inline-block; overflow: hidden;">
                                          <!--    <div class="input-group date" id="datePicker">
                                             <input type="text" class="form-control" id="Effectivedate" name="Effectivedate" placeholder="MM-DD-YYYY"  autocomplete="off">
                                             <span class="input-group-addon add-on">
                                                 <img class="list" src="img/calendar.png" alt="userw">
                                             </span>
                                             </div> --> 
                                          <!--   <input id="datepicker" type="text" onchange="Checkdate()" > -->
                                          <input class="form-control" type = "text" id="datepicker-1" name="datepicker-1" placeholder="MM-DD-YYYY"  autocomplete="off" class="cal_fix" onkeydown="return false"/>
                                          <span class="cal_fix_span">     
                                          <img class="list" src="img/calendar.png" alt="userw" style="margin-top: -3px;">
                                          </span>
                                       </span>
                                        <span class="error" id="dateError"></span>
                                    </span>
                                 </div>
                                 <div class="col-md-3"><strong><span style="font-size: 10pt; font-family: Arial;">Status</span></strong></div>
                                 <div class="col-md-3">
                                    <span>
                                      <?php if($_SESSION['userType']!=3){?>
                                       <select class="form-control" name="Status" id="Status" class="drop col-md-12">
                                       	<?php 
                                       	while($rowdrop=mysqli_fetch_array($resultdrop)){
                                       	$STATUS=$rowdrop['STATUS'];
                                       	?>
                                          <option value="<?php echo $STATUS;?>"><?php echo $STATUS;?></option>
                                      <?php }?>
                                          
                                       </select>
                                     <?php } else{?>
                                      <select class="form-control" name="Status" id="Status" class="drop col-md-12">
                                       
                                       
                                        <option value="<?php echo $row['CUR_STATUS_CDE'].' - '.$row['CUR_STATUS_CDE_DESC'];?>"><?php echo $row['CUR_STATUS_CDE'].' - '.$row['CUR_STATUS_CDE_DESC'];?></option>
                                    
                                          
                                       </select>
                                     <?php } ?>
                                        <span class="error" id="statusError"></span>
                                    </span>
                                 </div> 
                              </div>
                              <tr>
                                 <td style="width: 95px">&nbsp;</td>  
                                 <td style="width: 25px"></td>
                                 <td style="width: 95px"></td>
                                 <td style="width: 200px"></td>
                              </tr>
                              <tr>
                                 <td style="width: 95px"></td>
                                 <td colspan="3"></td>
                                 <td style="width: 200px"></td>
                              </tr>
                              <div class="row mb15">
                                 <div class="col-md-12 mt50">
                                    <div class="col-md-4">&nbsp;</div>
                                    <div class="col-md-2">
                                       <!--  onclick="return confirm('Are you sure to submit the record?')" -->
                                       <!---<button class="btn btn-lg btn-primary" type="button">Submit</button>--->
                                       <!---<input type="submit" name="submit" value="Submit">-->
                                       <?php if($_SESSION['userType']!=3){?>
                                       <button type="submit" class="btn btn-primary btn-xs-100 col-md-12" name="submit" id="scrubsubmit" style="margin-left: 88px;">Submit</button>
                                     <?php } ?>
                                    </div>
                                    
                                    <div class="col-md-4"></div>
                                 </div>
                              </div>
                           </div>
                       </div>
                  </div>
               </div>
            </section> 
            <!-- Report -->
         </div>
         <!--main content ends here -------------------------------------------------->
         <?php include('footer.php');?>
         <div class="control-sidebar-bg"></div>
     </div>
   </body>

   <script type="text/javascript">

           
            $(function() {
             var today = new Date();
                 $( "#datepicker-1" ).datepicker({
                     endDate: "today"
                    // minDate: 0
                 });
      
                 $("#datepicker-1").on('change',function(){
                   var dateString = document.getElementById("datepicker-1").value;
                   var UserDate = new Date(dateString);
                   var ToDate = new Date();
                  if(UserDate==''){
                     $('#dateError').text('Effective date cannot be left blank');
                     $('#dateError').css('display', 'block');
                     $("#datepicker-1").addClass("makeRed");

                   }else{
                     $('#dateError').css('display', 'none');
                     $("#datepicker-1").removeClass("makeRed");
                   }
                    if (UserDate > ToDate) {
                     $('#dateError').text('Effective Date should not be greater than todays date');
                     $('#dateError').css('display', 'block');
                     $("#datepicker-1").addClass("makeRed");

                   }else{
                     $('#dateError').css('display', 'none');
                     $("#datepicker-1").removeClass("makeRed");
                   }

                 });
      $('#reset').click(function(){
       
          window.open('searchacc' );
      })
    });   
      function validateForm() {
       var check = true;
       var flag = true;
       $('input').removeClass('makeRed');
       $('select').removeClass('makeRed');
       $('#dateError').css('display', 'none');
       $('#statusError').css('display', 'none');
       var dateval= $("#datepicker-1").val();
       var currentStatus='<?php echo $row['CUR_STATUS_CDE'];?>';
       var status=$('#Status').val();
       var firstWord = status.replace(/ .*/,'');

       var dateString = document.getElementById("datepicker-1").value;
       var UserDate  = new Date(dateString);
       var ToDate    = new Date();
    
       if(dateval==''){
          $('#dateError').text('Effective date cannot be left blank');
          $('#dateError').css('display', 'block');
          $("#datepicker-1").addClass("makeRed");
         flag = false;
       }
       if (UserDate > ToDate) {
         $('#dateError').text('Effective Date should not be greater than todays date');
         $('#dateError').css('display', 'block');
         $("#datepicker-1").addClass("makeRed");
         flag = false;

       }
       if(currentStatus==firstWord){
         // alert('Please submit the file with new status');
         $('#statusError').text('Please submit the file with new status');
         $('#statusError').css('display', 'block');
         $("#Status").addClass("makeRed");
        flag = false;
       }

           
   if(flag){
      return true;
   }else {
      return false; 
   }  
}
$('#scrubsubmit').on('click', function(e, params) {


        var localParams = params || {};

        if (!localParams.send) {
            e.preventDefault();
        }

        if(validateForm()){
   
        
        swal({
              title: "Confirm Entry",
              text: "Are you sure you want to add the record?",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#6A9944",
              confirmButtonText: "Confirm",
              cancelButtonText: "Cancel",
              closeOnConfirm: true
            }, function(isConfirm){
              
         if (isConfirm) {
                    $(e.currentTarget).trigger(e.type, { 'send': true });
                } else {}
    });

     }
});
   </script>
</html>