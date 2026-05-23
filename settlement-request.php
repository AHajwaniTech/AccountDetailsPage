<?php
   session_start();
//print_r($_SESSION);exit;
   date_default_timezone_set("America/New_York");
   
  // echo "<pre>";print_r($_SESSION);echo "</pre>";
   require_once("../config.php");
   //include_once('../mydownloadconn.php');
    $QuesTitleErr=$QuesErr= $AnsErr= $UrlErr=$fileErr=$roleErr=$AssignErr="";
    $flag=0; 

  
   if(!isset($_SESSION['email']))
   {
    $_SESSION['settlement_number'] = $_GET['number'];
   header('Location: ../login.php');
     exit();
  }



   //  if(!isset($_SESSION['accountno']) && !isset($_SESSION['settlement_number']))
   //  {
   //    $_SESSION['accountnoNew'] = $_SESSION['rmsfilenumenc'];
   //  }

 
   // if(isset($_SESSION['accountno']))
   // {
   // $FILNUM= substr_replace($_SESSION['accountno'], "", -1);
   // $_SESSION['rmsfilenumenc']=  $FILNUM;

   // }
   // else if(isset($_SESSION['settlement_number']))
   // {
   //  $FILNUM = base64_decode($_SESSION['settlement_number']);
   //  $_SESSION['rmsfilenumenc']=  $FILNUM;
   // }
   // else if($_SESSION['accountnoNew']!='')
   // {
   //  $FILNUM = $_SESSION['accountnoNew'];
   //  $_SESSION['rmsfilenumenc']=  $FILNUM;

   //  $filenumQuery = "SELECT ACCT_NUM FROM HSFLCLNTWF_bk WHERE FILNUM= '".$FILNUM."'";
   //  $fetch=mysqli_query($conn,$filenumQuery);
   //  $fetchAccNo = mysqli_fetch_assoc($fetch);

   //  $ACCT_NUM = $fetchAccNo['ACCT_NUM'];
   //  unset($_SESSION['accountno']);
   //  $_SESSION['accountno'] = $ACCT_NUM;


   // }else if($_GET['number']){
   //  $FILNUM = base64_decode($_GET['number']);
   //  $_SESSION['rmsfilenumenc']=  $FILNUM;
   // }else{
   
    $filenumQuerynew='SELECT FILNUM
    FROM HSFLCLNTWF_bk
    WHERE ACCT_NUM = "'.$_SESSION['statusupdateaccno'].'"';//echo $filenumQuerynew;exit;
    $resfilenum=mysqli_query($conn,$filenumQuerynew);
    $fetchfilenum = mysqli_fetch_assoc($resfilenum);
    $FILNUM=$fetchfilenum['FILNUM'];
    $_SESSION['rmsfilenumenc']=  $FILNUM;
    // if($FILNUM==''){
    //   $filenumQuerynew='SELECT FILNUM
    // FROM SETTFORM
    // WHERE RACTNM = "'.$_SESSION['statusupdateaccno'].'"';//echo $filenumQuerynew;exit;
    // $resfilenum=mysqli_query($conn,$filenumQuerynew);
    // $fetchfilenum = mysqli_fetch_assoc($resfilenum);
    // $FILNUM=$fetchfilenum['FILNUM'];
    // $_SESSION['rmsfilenumenc']=  $FILNUM;
    // }
    
    //}
    include_once 'settlement_filter.php';

    if(mysqli_num_rows($resultData)){
      $resultData01 = mysqli_fetch_assoc($resultData);
      if($resultData01['COUNT(1)'] == 0)
      { 
     echo 'Please change your input parameters and run the report again.';
     echo '<br>';
     echo 'If you feel you received this message in error, please contact Compliance@aacanet.org.';
     exit();
   }
    }
  $counteracceptrejectQuery="SELECT CounterAceeptReject,LSTUPDATE FROM SETTFORM WHERE FILNUM ='".$FILNUM."'  ORDER BY CAST(TRANSNUM AS UNSIGNED) DESC LIMIT 1";
  $resultcounteracceptrejectQuery=mysqli_query($conn,$counteracceptrejectQuery);
  $fetchcounteracceptrejectQuery=mysqli_fetch_assoc($resultcounteracceptrejectQuery);
  $counteracceptrejectvalue= $fetchcounteracceptrejectQuery['CounterAceeptReject'];
 
  $LSTUPDATE=strtotime(date('Y-m-d',strtotime($fetchcounteracceptrejectQuery['LSTUPDATE'])));
  $currentDate=strtotime(date('Y-m-d'));
  $days       = floor($diff / (3600 * 24));
  if($days>30){
    $selecttransnum="SELECT max(CAST(TRANSNUM AS UNSIGNED))  as TRANSNUM from SETTFORM where FILNUM ='".$FILNUM."'  ORDER BY CAST(TRANSNUM AS UNSIGNED) DESC LIMIT 1";
    $resselecttransnum=mysqli_query($conn,$selecttransnum);
    $fetchselecttransnum=mysqli_fetch_assoc($resselecttransnum);
    $transnumget=$fetchselecttransnum['TRANSNUM'];
    $updateofferexpire="Update SETTFORM  CounterAceeptReject=4 ,Additionalinfo=4 where FILNUM ='".$FILNUM."' and TRANSNUM='".$transnumget."' ";
    mysqli_query($conn,$updateofferexpire);
  }
  if( ($counteracceptrejectvalue==0) && $days<=30){
   $query=  "SELECT SIFFCNAME, SIFFCPHONE, SIFFCEMAIL, PERCOFFER, FILNUM, TRANSNUM, RACTNM, WFORGNAME, ORGCODE, ROFFCD, COSTPROC, WFNAME, RMSASNDE01, RACTST, SYSDESC, RAATTY, OTHACCTNUM, OFFERDATE, TOTBALDUE, MRTYRS, MRTPAY, MRTYRSLEFT, FREASON, FSOURCE, FREPRESENT, RENTOWN, VERIFIED, REFI, TOTDEBT, RBLREP, SIFFIRMBAL, FIRMINTAMT, SIFFRMCOST, ADDITCOST, CHKBXPAYPL, PAYPLANDET, CKBXLUMSUM, PPGROSSAMT, LUMSUMAMNT, LUMPAYDATE, LASTPAYDT, FIRSTPAYDT, PPFSTPAYAM, PPTOTDUE, PPNUMMONTH, PPMTHPYMT, PPLSTPAYAM, PPINTAMNT, CBXINT, PRTAMNT, ADDEDINT, CBXMTHPAY, CBXMRTYRS, CBXYRSLEFT, CBXTOTDEBT, DOADATE, RJDDT, FRMJUGAMNT, FIRMTERMS, FRMADDCOMM, LSTUPDATE, STIPJUDG, COMPREV, DDBFUNDS, CBXTOTMORT, CBXTOTAUTO, CBXTOTSTUD, TOTMRTDEBT, TOTAUTODBT, TOTSTUDDBT, REFIDATE, SIFFCEXT, DBTREMPLYD,WFDTCHGDT,HADATEF, AACAREVNAM, AACAEMAIL, AACAADDCOM, AACAACCEPT, AACAREJECT, AACACNTR, AACAADDINF, SYSDESC, AACACNTOFF, CLNTREVNAM, HARDSHIPCLAIM, CLNTEMAIL, HARDSHIPCOPY, AUTOACCEPT, CLNTREJECT, CLNTACCEPT, CBXCLNTINF, CLNTCNTR, CLNTCNINST, CLNTREAS, CLNTADDINF, FIRMUSERID, ADDINFREAS, SPR_BALTYPE, PNDJUDGEXE, PNDLIENS, PNDBNKLEVY, PNDGARN, RBLREP_DESC, AACARFRTOCLNT,CounterAceeptReject,Additionalinfo,Additionalinfocomments,Additionalinfodocuments FROM SETTFORM WHERE FILNUM ='".$FILNUM."'  ORDER BY CAST(TRANSNUM AS UNSIGNED) DESC LIMIT 1"; 
   $query01="SELECT SIFFCNAME, SIFFCPHONE, SIFFCEMAIL, PERCOFFER, FILNUM, TRANSNUM, RACTNM, WFORGNAME, ORGCODE, ROFFCD, WFNAME, RMSASNDE01, RACTST, SYSDESC, RAATTY, OTHACCTNUM, OFFERDATE, TOTBALDUE, MRTYRS, MRTPAY, MRTYRSLEFT, FREASON, FSOURCE, FREPRESENT, RENTOWN, VERIFIED, REFI, TOTDEBT, RBLREP, SIFFIRMBAL, FIRMINTAMT, SIFFRMCOST, ADDITCOST, CHKBXPAYPL, PAYPLANDET, CKBXLUMSUM, PPGROSSAMT, LUMSUMAMNT, LUMPAYDATE, LASTPAYDT, FIRSTPAYDT, PPFSTPAYAM, PPTOTDUE, PPNUMMONTH, PPMTHPYMT, PPLSTPAYAM, PPINTAMNT, CBXINT, PRTAMNT, ADDEDINT, CBXMTHPAY, CBXMRTYRS, CBXYRSLEFT, CBXTOTDEBT, DOADATE, RJDDT, FRMJUGAMNT, FIRMTERMS, FRMADDCOMM, LSTUPDATE, STIPJUDG, COMPREV, DDBFUNDS, CBXTOTMORT, CBXTOTAUTO, CBXTOTSTUD, TOTMRTDEBT, TOTAUTODBT, TOTSTUDDBT, REFIDATE, SIFFCEXT, DBTREMPLYD,WFDTCHGDT,HADATEF, AACAREVNAM, AACAEMAIL, AACAADDCOM, AACAACCEPT, AACAREJECT, AACADENYR, AACACNTR, AACAADDINF, SYSDESC, AACACNTOFF, CLNTREVNAM, HARDSHIPCLAIM, CLNTEMAIL, HARDSHIPCOPY, AUTOACCEPT, CLNTREJECT, CLNTDENYR, CLNTACCEPT, CBXCLNTINF, CLNTCNTR, CLNTCNINST, CLNTREAS, CLNTADDINF, FIRMUSERID, ADDINFREAS, SPR_BALTYPE,CounterAceeptReject,Additionalinfo,Additionalinfocomments,Additionalinfodocuments FROM SETTFORM WHERE FILNUM ='".$FILNUM."' ORDER BY CAST(TRANSNUM AS UNSIGNED) DESC ";
 }

 else{
  $query=   "SELECT SIFFCNAME, SIFFCPHONE, SIFFCEMAIL, PERCOFFER, FILNUM, TRANSNUM, RACTNM, WFORGNAME, ORGCODE, ROFFCD, COSTPROC, WFNAME, RMSASNDE01, RACTST, SYSDESC, RAATTY, OTHACCTNUM, OFFERDATE, TOTBALDUE, MRTYRS, MRTPAY, MRTYRSLEFT, FREASON, FSOURCE, FREPRESENT, RENTOWN, VERIFIED, REFI, TOTDEBT, RBLREP, SIFFIRMBAL, FIRMINTAMT, SIFFRMCOST, ADDITCOST, CHKBXPAYPL, PAYPLANDET, CKBXLUMSUM, PPGROSSAMT, LUMSUMAMNT, LUMPAYDATE, LASTPAYDT, FIRSTPAYDT, PPFSTPAYAM, PPTOTDUE, PPNUMMONTH, PPMTHPYMT, PPLSTPAYAM, PPINTAMNT, CBXINT, PRTAMNT, ADDEDINT, CBXMTHPAY, CBXMRTYRS, CBXYRSLEFT, CBXTOTDEBT, DOADATE, RJDDT, FRMJUGAMNT, FIRMTERMS, FRMADDCOMM, LSTUPDATE, STIPJUDG, COMPREV, DDBFUNDS, CBXTOTMORT, CBXTOTAUTO, CBXTOTSTUD, TOTMRTDEBT, TOTAUTODBT, TOTSTUDDBT, REFIDATE, SIFFCEXT, DBTREMPLYD,WFDTCHGDT,HADATEF, AACAREVNAM, AACAEMAIL, AACAADDCOM, AACAACCEPT, AACAREJECT, AACACNTR, AACAADDINF, SYSDESC, AACACNTOFF, CLNTREVNAM, HARDSHIPCLAIM, CLNTEMAIL, HARDSHIPCOPY, AUTOACCEPT, CLNTREJECT, CLNTACCEPT, CBXCLNTINF, CLNTCNTR, CLNTCNINST, CLNTREAS, CLNTADDINF, FIRMUSERID, ADDINFREAS, SPR_BALTYPE, PNDJUDGEXE, PNDLIENS, PNDBNKLEVY, PNDGARN, RBLREP_DESC,AACARFRTOCLNT,CounterAceeptReject,Additionalinfo,Additionalinfocomments,Additionalinfodocuments FROM SETTFORM WHERE FILNUM ='12345678910'  ORDER BY CAST(TRANSNUM AS UNSIGNED) DESC LIMIT 1"; 
   $query01="SELECT SIFFCNAME, SIFFCPHONE, SIFFCEMAIL, PERCOFFER, FILNUM, TRANSNUM, RACTNM, WFORGNAME, ORGCODE, ROFFCD, WFNAME, RMSASNDE01, RACTST, SYSDESC, RAATTY, OTHACCTNUM, OFFERDATE, TOTBALDUE, MRTYRS, MRTPAY, MRTYRSLEFT, FREASON, FSOURCE, FREPRESENT, RENTOWN, VERIFIED, REFI, TOTDEBT, RBLREP, SIFFIRMBAL, FIRMINTAMT, SIFFRMCOST, ADDITCOST, CHKBXPAYPL, PAYPLANDET, CKBXLUMSUM, PPGROSSAMT, LUMSUMAMNT, LUMPAYDATE, LASTPAYDT, FIRSTPAYDT, PPFSTPAYAM, PPTOTDUE, PPNUMMONTH, PPMTHPYMT, PPLSTPAYAM, PPINTAMNT, CBXINT, PRTAMNT, ADDEDINT, CBXMTHPAY, CBXMRTYRS, CBXYRSLEFT, CBXTOTDEBT, DOADATE, RJDDT, FRMJUGAMNT, FIRMTERMS, FRMADDCOMM, LSTUPDATE, STIPJUDG, COMPREV, DDBFUNDS, CBXTOTMORT, CBXTOTAUTO, CBXTOTSTUD, TOTMRTDEBT, TOTAUTODBT, TOTSTUDDBT, REFIDATE, SIFFCEXT, DBTREMPLYD,WFDTCHGDT,HADATEF, AACAREVNAM, AACAEMAIL, AACAADDCOM, AACAACCEPT, AACAREJECT, AACADENYR, AACACNTR, AACAADDINF, SYSDESC, AACACNTOFF, CLNTREVNAM, HARDSHIPCLAIM, CLNTEMAIL, HARDSHIPCOPY, AUTOACCEPT, CLNTREJECT, CLNTDENYR, CLNTACCEPT, CBXCLNTINF, CLNTCNTR, CLNTCNINST, CLNTREAS, CLNTADDINF, FIRMUSERID, ADDINFREAS, SPR_BALTYPE,CounterAceeptReject,Additionalinfo,Additionalinfocomments,Additionalinfodocuments FROM SETTFORM WHERE FILNUM ='".$FILNUM."' ORDER BY CAST(TRANSNUM AS UNSIGNED) DESC  ";
 }

  $getcount="SELECT count(1) as totaldocview from SETTFORM where  FILNUM ='".$FILNUM."' and Additionalinfo=1";
  $resgetcount=mysqli_query($conn,$getcount);
  $fetchgetcount=mysqli_fetch_assoc($resgetcount);



//echo $query01; exit;

 

 




   
   $query1 = "SELECT ORG_PL_AMT AS ORPLAM, ORGCODE, WFNAME, ACCT_NUM as RACTNM, WFDTCHGDT, WFORGNM, CURR_ATTY_NME as RMSASNDE01, WFDTCHGDT, CURR_STS_DESC as SYSDESC, HADATEF, RJDDT, JGMT_AMT as JUDGAMT, WFORGPRBAL, JGMT_INT_RTE as JINTRT, DBT_ST_RES as RMSSTATECD, 
     CURR_STS_CD as RACTST, TTL_CC as RMSTRANA01, CLT_CDE as ROFFCD, CURR_ATTY_CD as RAATTY, DBT_LST_NME as RCORN1, FILNUM, WFOTHACCT, NTE_DESC,
     WFFIRMFILE, JGMT_DTE, (CASE WHEN JGMT_DTE = '0001-01-01' THEN 'N' ELSE 'Y' END) JDGMT_FLAG FROM HSFLCLNTWF_bk WHERE FILNUM= '".$FILNUM."' ";
  //echo    $query1;exit;

   if($_SESSION['userType'] == 1)
   {
   $userQuery = "SELECT fullName, LastName, email, phoneNo FROM tbl_login WHERE email = '".$_SESSION['email']."' AND userType = '".$_SESSION['userType']."' ";
   }
   else if($_SESSION['userType'] == 2)
   {
    $userQuery = "SELECT fullName, LastName, email, phoneNo FROM tbl_login WHERE email = '".$_SESSION['email']."' AND userType = '".$_SESSION['userType']."' AND firmCode = '".$_SESSION['firmCode']."'";
   }
   else if ($_SESSION['userType'] == 3)
   {
    $userQuery = "SELECT fullName, LastName, email, phoneNo FROM tbl_login WHERE email = '".$_SESSION['email']."' AND userType = '".$_SESSION['userType']."' AND clientCode = '".$_SESSION['clientCode']."'";
   }
   
   $result01=mysqli_query($conn,$query01);//echo $query01;exit;
   $resultnote=mysqli_query($conn,$query01);
   $resultcounteroffer=mysqli_query($conn,$query01);
   $resultaddinfo=mysqli_query($conn,$query01);
   $resultviewtabdoc=mysqli_query($conn,$query01);


   $result=mysqli_query($conn,$query);
   $rowjugcount = mysqli_num_rows($result);
   $result1=mysqli_query($conn,$query1);
   
   $result3=mysqli_query($conn,$userQuery);
   
   $fetchres=mysqli_fetch_assoc($result);
   $resquerycounter=mysqli_query($conn,$query01);
   $fetchquerycounter=mysqli_fetch_assoc($resquerycounter);
   //print_r($fetchres);exit;
   //$modal = $fetchres;
   $fetchres1=mysqli_fetch_assoc($result1);
   $JDGMTFLAG= $fetchres1['JDGMT_FLAG'];
   $firmnamelog=$fetchres1['RMSASNDE01'];
   $clientnamelog=$fetchres1['WFORGNM'];
   $_SESSION['firmnamelog']=$firmnamelog;
   $_SESSION['clientnamelog']=$clientnamelog;
   // print_r($fetchres1);
   // die;
   
   $fetchres3=mysqli_fetch_assoc($result3);

   $checkCurStatus = "SELECT COUNT(1) FROM `HSFLCLNTWF_bk` WHERE FILNUM = '".$FILNUM."' AND CURR_STS_CD LIKE '9%'";
   $resultCurStatus = mysqli_query($conn,$checkCurStatus);
   $fetchCurStatus=mysqli_fetch_assoc($resultCurStatus);

   if($fetchCurStatus['COUNT(1)'] == 1)
   {
    $currentStatus = 1;
   }
   else
   {
    $currentStatus = 2;
   }
/*to check last value is similar with new value*/
  $querychecklastvalue="SELECT SIFFCNAME, SIFFCPHONE, SIFFCEMAIL, PERCOFFER, FILNUM, TRANSNUM, RACTNM, WFORGNAME, ORGCODE, ROFFCD, COSTPROC, WFNAME, RMSASNDE01, RACTST, SYSDESC, RAATTY, OTHACCTNUM, OFFERDATE, TOTBALDUE, MRTYRS, MRTPAY, MRTYRSLEFT, FREASON, FSOURCE, FREPRESENT, RENTOWN, VERIFIED, REFI, TOTDEBT, RBLREP, SIFFIRMBAL, FIRMINTAMT, SIFFRMCOST, ADDITCOST, CHKBXPAYPL, PAYPLANDET, CKBXLUMSUM, PPGROSSAMT, LUMSUMAMNT, LUMPAYDATE, LASTPAYDT, FIRSTPAYDT, PPFSTPAYAM, PPTOTDUE, PPNUMMONTH, PPMTHPYMT, PPLSTPAYAM, PPINTAMNT, CBXINT, PRTAMNT, ADDEDINT, CBXMTHPAY, CBXMRTYRS, CBXYRSLEFT, CBXTOTDEBT, DOADATE, RJDDT, FRMJUGAMNT, FIRMTERMS, FRMADDCOMM, LSTUPDATE, STIPJUDG, COMPREV, DDBFUNDS, CBXTOTMORT, CBXTOTAUTO, CBXTOTSTUD, TOTMRTDEBT, TOTAUTODBT, TOTSTUDDBT, REFIDATE, SIFFCEXT, DBTREMPLYD,WFDTCHGDT,HADATEF, AACAREVNAM, AACAEMAIL, AACAADDCOM, AACAACCEPT, AACAREJECT, AACACNTR, AACAADDINF, SYSDESC, AACACNTOFF, CLNTREVNAM, HARDSHIPCLAIM, CLNTEMAIL, HARDSHIPCOPY, AUTOACCEPT, CLNTREJECT, CLNTACCEPT, CBXCLNTINF, CLNTCNTR, CLNTCNINST, CLNTREAS, CLNTADDINF, FIRMUSERID, ADDINFREAS, SPR_BALTYPE, PNDJUDGEXE, PNDLIENS, PNDBNKLEVY, PNDGARN, RBLREP_DESC,AACARFRTOCLNT,CounterAceeptReject,Additionalinfo,Additionalinfocomments,Additionalinfodocuments FROM SETTFORM WHERE FILNUM ='".$FILNUM."' and TRANSNUM='".$fetchquerycounter['TRANSNUM']."'  ORDER BY CAST(TRANSNUM AS UNSIGNED) DESC LIMIT 1"; 
   $resquerychecklastvalue            = mysqli_query($conn,$querychecklastvalue);
   $fetchresquerychecklastvalue       =mysqli_fetch_assoc($resquerychecklastvalue);


   $hardshipDocQuery = "SELECT HARDSHIPCOPY,TRANSNUM FROM SETTFORM WHERE FILNUM = '".$FILNUM."' AND HARDSHIPCOPY != '' ORDER BY CAST(TRANSNUM AS UNSIGNED) DESC LIMIT 1";
   $hardshipResult = mysqli_query($conn,$hardshipDocQuery);
   $hardshipRowCount=mysqli_num_rows($hardshipResult);
   $fetchHardshipDoc=mysqli_fetch_assoc($hardshipResult);



   $totalBalanceDue = $fetchres['SIFFIRMBAL']+$fetchres['SIFFRMCOST']+$fetchres['FIRMINTAMT']+$fetchres1['RMSTRANA01']+$fetchres['ADDITCOST'];
   $totalBalanceDue = number_format((float)$totalBalanceDue, 2, '.', '');  

   $orgCode = $fetchres['ORGCODE'];
   // print_r($orgCode);
   // die();
   $portfolioCode = $fetchres['ROFFCD'];
   $autoAccept = $fetchres['AUTOACCEPT'];

   if($orgCode == '')
   {
    $orgCode = $fetchres1['ORGCODE'];
   }
  
  /*------*/
  // $setform = "SELECT * FROM  SIFPIFPARMTRS";
  // $setresult01=mysqli_query($conn,$setform);
  // $getroffcd = '';
  
  // while($fetchroffcd= mysqli_fetch_assoc($setresult01)){
    // $getroffcd = $fetchroffcd['ROFFCD'];
  // }
  
  // if($portfolioCode == '')
  // {
    // $portfolioCode = $fetchres1['ROFFCD'];
  // }
  
  // print_r($getroffcd);
  // die();
  
  
  // if($portfolioCode == $getroffcd){
    // $query2 = "SELECT LUMPPERC FROM  SIFPIFPARMTRS WHERE ORGCODE = '".$orgCode."' AND ROFFCD = '".$portfolioCode."' AND SIFFORMOK = 'Y'";
  // }else{
    // $query2 = "SELECT LUMPPERC FROM  SIFPIFPARMTRS WHERE ORGCODE = '".$orgCode."' AND SIFFORMOK = 'Y'";
  // }
  
  // print_r($query2);
  // die();
  // $query2 = "SELECT LUMPPERC FROM  SIFPIFPARMTRS";
  // $result2=mysqli_query($conn,$query2);
  // $fetchres2=mysqli_fetch_assoc($result2);
  /*----*/
 
 
 
   $newQuery = "SELECT * FROM SIFPIFPARMTRS WHERE ORGCODE = '".$orgCode."' AND ROFFCD = '".$portfolioCode."' AND SIFFORMOK = 'Y'";
   $result3=mysqli_query($conn,$newQuery);

    $rowcount=mysqli_num_rows($result3);


   if($rowcount != 0)
   {
      $newQuery1 = "SELECT * FROM SIFPIFPARMTRS WHERE ORGCODE = '".$orgCode."' AND ROFFCD = '".$portfolioCode."' AND SIFFORMOK = 'Y'";
     $result4=mysqli_query($conn,$newQuery1);
     $fetchres4=mysqli_fetch_assoc($result4);
     $pifMonths = $fetchres4['PIFMONTHS'];
     $sifMonths = $fetchres4['SIFMONTHS'];
     $pifMonthPymt = $fetchres4['PIFMTHPYMT'];
     $pPlanPerc = $fetchres4['PPLANPERC'];
     $stipReq = $fetchres4['STIPREQ'];
     $DATERANGE = $fetchres4['DATERANGE'];
     $BALRANGE = $fetchres4['BALRANGE'];
     $USSTATE = $fetchres4['USSTATE'];
     $STAGECODE = $fetchres4['STAGECODE'];
     $FILELOC = $fetchres4['FILELOC'];
     $STRACCTNUM = $fetchres4['STRACCTNUM'];
     $ENDACCTNUM = $fetchres4['ENDACCTNUM'];
     $FIRMCODE = $fetchres4['FIRMCODE'];
     $OFFERRANGE = $fetchres4['OFFERRANGE'];
     $DAYSCODE = $fetchres4['DAYSCODE'];
     $PIFPPLANOK = $fetchres4['PIFPPLANOK'];
     $PIFMTHPERC = $fetchres4['PIFMTHPERC'];
     $PIFMINVAL = $fetchres4['PIFMINVAL'];
     $PIFDWNPERC = $fetchres4['PIFDWNPERC'];
     $LUMPPERC = $fetchres4['LUMPPERC'];
   $SIFPPLANOK = $fetchres4['SIFPPLANOK'];
     $SIFMTHPYMT = $fetchres4['SIFMTHPYMT'];
     $SIFMTHPERC = $fetchres4['SIFMTHPERC'];
     $SIFMINVAL = $fetchres4['SIFMINVAL'];
     $SIFDWNPERC = $fetchres4['SIFDWNPERC'];
     $SPECCOND = $fetchres4['SPECCOND'];
     $ADDEDNOTES = $fetchres4['ADDEDNOTES'];
     $CONFEMAIL = $fetchres4['CONFEMAIL'];
     $SIFNAME1 = $fetchres4['SIFNAME1'];
     $SIFEMAIL1 = $fetchres4['SIFEMAIL1'];
     $SIFCOND1 = $fetchres4['SIFCOND1'];
     $SIFNAME2 = $fetchres4['SIFNAME2'];
     $SIFEMAIL2 = $fetchres4['SIFEMAIL2'];
     $SIFCOND2 = $fetchres4['SIFCOND2'];
     $SIFNAME3 = $fetchres4['SIFNAME3'];
     $SIFEMAIL3 = $fetchres4['SIFEMAIL3'];
     $SIFCOND3 = $fetchres4['SIFCOND3'];
     $SIFNAME4 = $fetchres4['SIFNAME4'];
     $SIFEMAIL4 = $fetchres4['SIFEMAIL4'];
     $SIFCOND4 = $fetchres4['SIFCOND4'];
     $SIFFORMOK = $fetchres4['SIFFORMOK'];
     $CLNTGUIDE = $fetchres4['CLNTGUIDE'];
     $HARDSHIPOFF = $fetchres4['HARDSHIPOFF'];
     $STPPENEXE = $fetchres4['STPPENEXE'];
     $STPINT = $fetchres4['STPINT'];
     $Default_Record = $fetchres4['Default_Record'];
     $BALTYPE = $fetchres4['BALTYPE'];

   }
   else
   {
    
      $newQuery1 = "SELECT * FROM SIFPIFPARMTRS WHERE ORGCODE = '".$orgCode."' AND SIFFORMOK = 'Y' AND Default_Record = 'Y' ORDER BY SIFID DESC LIMIT 1";
      $result4=mysqli_query($conn,$newQuery1);

      $rowcount2=mysqli_num_rows($result4);

      if($rowcount2 != 0)
      {
        
       $fetchres4=mysqli_fetch_assoc($result4);
       $pifMonths = $fetchres4['PIFMONTHS'];
       $sifMonths = $fetchres4['SIFMONTHS'];
       $pifMonthPymt = $fetchres4['PIFMTHPYMT'];
       $pPlanPerc = $fetchres4['PPLANPERC'];
       $stipReq = $fetchres4['STIPREQ'];
       $DATERANGE = $fetchres4['DATERANGE'];
       $BALRANGE = $fetchres4['BALRANGE'];
       $USSTATE = $fetchres4['USSTATE'];
       $STAGECODE = $fetchres4['STAGECODE'];
       $FILELOC = $fetchres4['FILELOC'];
       $STRACCTNUM = $fetchres4['STRACCTNUM'];
       $ENDACCTNUM = $fetchres4['ENDACCTNUM'];
       $FIRMCODE = $fetchres4['FIRMCODE'];
       $OFFERRANGE = $fetchres4['OFFERRANGE'];
       $DAYSCODE = $fetchres4['DAYSCODE'];
       $PIFPPLANOK = $fetchres4['PIFPPLANOK'];
       $PIFMTHPERC = $fetchres4['PIFMTHPERC'];
       $PIFMINVAL = $fetchres4['PIFMINVAL'];
       $PIFDWNPERC = $fetchres4['PIFDWNPERC'];
       $LUMPPERC = $fetchres4['LUMPPERC'];
     // print_r($LUMPPERC);
   // die();
       $SIFPPLANOK = $fetchres4['SIFPPLANOK'];
       $SIFMTHPYMT = $fetchres4['SIFMTHPYMT'];
       $SIFMTHPERC = $fetchres4['SIFMTHPERC'];
       $SIFMINVAL = $fetchres4['SIFMINVAL'];
       $SIFDWNPERC = $fetchres4['SIFDWNPERC'];
       $SPECCOND = $fetchres4['SPECCOND'];
       $ADDEDNOTES = $fetchres4['ADDEDNOTES'];
       $CONFEMAIL = $fetchres4['CONFEMAIL'];
       $SIFNAME1 = $fetchres4['SIFNAME1'];
       $SIFEMAIL1 = $fetchres4['SIFEMAIL1'];
       $SIFCOND1 = $fetchres4['SIFCOND1'];
       $SIFNAME2 = $fetchres4['SIFNAME2'];
       $SIFEMAIL2 = $fetchres4['SIFEMAIL2'];
       $SIFCOND2 = $fetchres4['SIFCOND2'];
       $SIFNAME3 = $fetchres4['SIFNAME3'];
       $SIFEMAIL3 = $fetchres4['SIFEMAIL3'];
       $SIFCOND3 = $fetchres4['SIFCOND3'];
       $SIFNAME4 = $fetchres4['SIFNAME4'];
       $SIFEMAIL4 = $fetchres4['SIFEMAIL4'];
       $SIFCOND4 = $fetchres4['SIFCOND4'];
       $SIFFORMOK = $fetchres4['SIFFORMOK'];
       $CLNTGUIDE = $fetchres4['CLNTGUIDE'];
       $HARDSHIPOFF = $fetchres4['HARDSHIPOFF'];
       $STPPENEXE = $fetchres4['STPPENEXE'];
       $STPINT = $fetchres4['STPINT'];
       $Default_Record = $fetchres4['Default_Record'];
       $BALTYPE = $fetchres4['BALTYPE'];
      }
      else
      {
       $pifMonths = '';
       $sifMonths = '';
       $pifMonthPymt = '';
       $pPlanPerc = '';
       $stipReq = '';
       $DATERANGE = '';
       $BALRANGE = '';
       $USSTATE = '';
       $STAGECODE = '';
       $FILELOC = '';
       $STRACCTNUM = '';
       $ENDACCTNUM = '';
       $FIRMCODE = '';
       $OFFERRANGE = '';
       $DAYSCODE = '';
       $PIFPPLANOK = '';
       $PIFMTHPERC = '';
       $PIFMINVAL = '';
       $PIFDWNPERC = '';
       $LUMPPERC = '';
       $SIFPPLANOK = '';
       $SIFMTHPYMT = '';
       $SIFMTHPERC = '';
       $SIFMINVAL = '';
       $SIFDWNPERC = '';
       $SPECCOND = '';
       $ADDEDNOTES = '';
       $CONFEMAIL = '';
       $SIFNAME1 = '';
       $SIFEMAIL1 = '';
       $SIFCOND1 = '';
       $SIFNAME2 = '';
       $SIFEMAIL2 = '';
       $SIFCOND2 = '';
       $SIFNAME3 = '';
       $SIFEMAIL3 = '';
       $SIFCOND3 = '';
       $SIFNAME4 = '';
       $SIFEMAIL4 = '';
       $SIFCOND4 = '';
       $SIFFORMOK = '';
       $CLNTGUIDE = '';
       $HARDSHIPOFF = '';
       $STPPENEXE = '';
       $STPINT = '';
       $Default_Record = '';
       $BALTYPE = '';
      }
   }

  

   $balanceTypeQuery1 = "SELECT * FROM SIFPIFPARMTRS WHERE ORGCODE = '".$orgCode."'  AND ROFFCD = '".$portfolioCode."' AND SIFFORMOK = 'Y'";

    $runQuery1=mysqli_query($conn,$balanceTypeQuery1);

    $numRows1=mysqli_num_rows($runQuery1);

    if($numRows1 == 0)
    {
      
      $balanceTypeQuery2 = "SELECT * FROM SIFPIFPARMTRS WHERE ORGCODE = '".$orgCode."' AND SIFFORMOK = 'Y' AND Default_Record = 'Y' ";

      $runQuery2=mysqli_query($conn,$balanceTypeQuery2);

      $numRows2=mysqli_num_rows($runQuery2);

      if($numRows2 != 0)
      {
        $fetchData1=mysqli_fetch_assoc($runQuery2);
        $balanceType = $fetchData1['BALTYPE'];
      }

    }
    else
    {
     
      $fetchData2=mysqli_fetch_assoc($runQuery1);
      $balanceType = $fetchData2['BALTYPE'];
    }


   // echo '<pre>';
   // print_r($fetchres);
   // echo '</pre>';

   // echo '<pre>';
   // print_r($fetchres1);
   // echo '</pre>';

   // echo '<pre>';
   // print_r($fetchres2);
   // echo '</pre>';

   // exit();
   
   /* tab1 ends here*/
   /* page loading adding these logs to logfile*/
    $myfilelogname  ="settlement_log/settlementlog".date('mdY').'.txt';
    $myfilelogname = trim($myfilelogname);
    //if (!file_exists($myfilelogname)) {
    //  $myfilelogname    =fopen($myfilelogname,"w") or die("Unable to open file!");
      //$myfilelog        = fopen($myfilelogname,"a");
     //}
    $myfilelog    = fopen($myfilelogname,"a");
    $datetime=date("m/d/Y h:i:s a");
    $userName=$_SESSION['LastName'];
    $action = "ACTION: Loaded Settlement Form.";
    $fileno ="File Number: ".$FILNUM;
    $clientnamelog="Client:".$_SESSION['clientnamelog'];
    $firmnamelog="Firm:".$_SESSION['firmnamelog'];
    $fulltext=$datetime.' '.$userName.' '.$action.' '.$fileno.' '.$clientnamelog.' '.$firmnamelog;
    
    fwrite($myfilelog, "\n". $fulltext);
 
    /* page loading adding these logs to logfile ends here*/
   ?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Pipeway | Settlement Request</title>
     <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=11" />
    <meta http-equiv="X-UA-Compatible" content="IE=10" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <meta http-equiv="X-UA-Compatible" content="IE=8" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../img/fevicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../img/fevicon.ico">
    <link rel="apple-touch-icon-precomposed" href="../img/fevicon.ico">
    <link rel="shortcut icon" href="../img/fevicon.ico">
    <link rel="stylesheet" href="../css/PSnnect.min.css">
    <link rel="stylesheet" href="../css/PSdataTables.min.css">
    <link rel="stylesheet" href="../css/PSPanel.css">
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="Stylesheet"
        type="text/css" />
    <!--for date and time selection--->
    <link rel="stylesheet" media="all" type="text/css"
        href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
    <link rel="stylesheet" media="all" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
    <link href="http://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css"
        rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../css/sweetalert.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js">
    </script>
    <script src="../js/jquery.are-you-sure.js"></script>
    <script src="../js/PSnnect.min.js"></script>
    <script src="../js/PSslimscroll.js"></script>
    <script src="../js/PSnnectPanel.js"></script>
    <script src="../ckeditor/ckeditor.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js">
    </script>
   <!-- <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>  -->
    <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="Stylesheet"
        type="text/css" />
    <script src="../js/sweetalert.min.js"></script>
       <script src="../js/PSnnectValidator.min.js"></script>
       <script src=" https://printjs-4de6.kxcdn.com/print.min.js"></script>
       <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
      <style>
        @media screen and (max-width: 1300px) and (min-width: 400px){.ml5 {margin-left: 2px!important;}.bot60{bottom:0px!important;}.text{font-size:9px;}label{font-size:10px!important;}
    .font{font-size:9px;white-space:nowrap;}.bot25{bottom:0px!important;}}
     .datepicker.dropdown-menu{display: block;top: 320.8px;left: 1181.15px;z-index: 810;width: 15%;}
     .checkround{height: 15px;width: 15px;}
         .radio .checkround:after{left: 2px;top: 2px;width: 7px;height: 7px;}
         .radio{padding-left:15px!important;}
         .form-control{padding: 0px 5px;}
         label{margin-bottom:0px!important;}
    .bot25{bottom:25px;}
    .bot60{bottom:60px;}
    .file-upload {height: 32px;text-align: center;overflow: hidden;position: relative;padding: 5px 0px;border-radius: 4px;box-shadow: 2px 2px 4px lightgrey;}
    .FileUpload {position: relative;height: 30px;width: 100%;background: transparent;opacity: 1;padding: 34px 0px;top: -29px;border-radius: 4px;}
       input[type="radio"].disabled, input[type="radio"][disabled]{cursor: not-allowed!important;}
     <!---@media screen and (max-width: 1300px) and (min-width: 400px){.ml5 {margin-left: 2px!important;}.font{font-size:8px;}
       .checkround {width:10px!important;height: 10px!important;}.radio .checkround:after {left: 1px!important;top: 1px!important;width: 4px!important;height: 4px!important;}
     label{font-size:9px!important;}span{font-size:9px;}.btn{font-size:9px;}.nav-tabs > li > a{font-size: 9px;}.bot60{bottom:0px!important;}}---->
         <!--.datepicker.dropdown-menu{display: block;top: 320.8px;left: 1181.15px;z-index: 810;width: 15%;}-->
     .ui-datepicker-calendar {display: block;}
     .ui-datepicker{width: 14em;}

      #loader {
         position: fixed;
         width: 100%;
         height: 100vh;
         background: url('../img/loader.gif') no-repeat center center;
         z-index: 999999;
       }

       .solid1 {
          padding: 20px 20px;
            border-style: groove;
            margin-top: 5px;
            border-radius:5px;
        } 
        .solid2 {
    padding: 15px 40px;
    border-style: groove;
    margin-top: 5px;
    border-radius: 5px;
}


.nav li {
    background: #002e5b;
    /* padding: 10px; */
    color:  #fff;
    /* border: 1px solid #ddd; */
    border-radius: 5px;
    /* margin-left: -1px; */
    position: relative;
    font-weight: bold;
    left: 1px;
}

.nav-tabs-custom > .nav-tabs > li > a {
    color: #fff;
    border-radius: 0;
}

.nav-tabs-custom > .nav-tabs > li.active {
    border-top-color: #002e5b;
}
.mb10 {
    margin-bottom: 10px!important;
}
.nav-tabs-custom {
    margin-bottom: 1px;
    background: #fff;
    box-shadow: 0 0px 0px rgb(0 0 0 / 0%);
    border-radius: 1px;
}
.fa-close {
      position: absolute;
      top: 5px;
      right: 12px;
      z-index: 9999;
      font-size: 17px;
      cursor: pointer;
    }
    p {
    margin: 0px 0px 2px;
}
.modal-header {
    background: #fff;
    color: black;
    /* border-bottom: 2px solid #dd4b39; */
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    padding: 5px;
}
.modal-title {
   color: #002e5b;
}
.modal-header {
    border-bottom-color: #fff;
}
.lft-login .btn, .btn1:hover {
    background: #BB133E;
    color: #ffffff !important;
    font-size: 12px;
}
.lft-login .btn, .btn2:hover {
    background: #38a832;
    color: #ffffff !important;
    font-size: 12px;
}
.modal-footer {
    border-top-color: #fff;
}
/* .printicon {
    font-size: 33px;
    margin-left: 20px;
    position: relative;
    top: 8px;
} */
.p0{padding:0px!important;}

@media (min-width: 992px){
.nav > li > a {
    position: relative;
    display: block;
    padding: 10px 7px!important;
}
}

.main-footer{margin-left: 0px;}


.myhide{   
  position: absolute;
    left: 100%;
    background-color: #fff;
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 10px;
  width: 100%;
    top: -28px;}
  
.tdata{
      padding: 3px 5px;
}

.tablealign{
  text-align: end;
  padding: 3px 5px;
}

.highchartmanage{
    
    display: flex;
    justify-content: center;
    align-items: end;
    flex-direction: column;
}
.addMore, .removeimage {
    font-size: 20px;
    margin-right: 10px;
}
</style>
   </head>
   <body class="hold-transition skin-yellow sidebar-mini fixed AcaReq">
    <div id="loader" style="display: none;"></div>
      <div class="wrapper">
      <!-- By sujata -->
      <input type="hidden" id="filenumadvance" name="" value="<?php echo $FILNUM; ?>">
         <!-- end sujata -->
         <div class="">
            <?php if($_SESSION['userType']==1){
                      $activeTabAaca= 'class="active"';
                      $activeStatusAaca = 'active';
                     
                      }else if($_SESSION['userType']==2){
                      $activeTabGen= 'class="active"';
                      $activeStatusGen = 'active';
                      $tabhide = 'none';
                      }else if($_SESSION['userType']==3){
                      $activeTabClient= 'class="active"';
                      $activeStatusClient = 'active';

            }?>
            <section class="content">
               <div class="row">
                  <div class="col-xs-12">
                     <div class="box">
                        <div class="box-body">
                           <div class="col-md-12">
               <h4 class="text-left mb10">SETTLEMENT REQUEST</h4>
         <div class="nav-tabs-custom clearfix ">
                   <ul class="nav nav-tabs pull-left col-md-8">
                                    <li id="gentab"<?php echo $activeTabGen; ?>><a href="#Guide01" data-toggle="tab">General Information</a></li>
                                    <li><a href="#Guide02" data-toggle="tab" class="Guide02">Consumer Section</a></li>
                                    <li id="termsofoffer"><a href="#Guide03" data-toggle="tab" class="Guide03">Terms Of Offer</a></li>
                                    <?php if($_SESSION['userType']!=3){?>
                                    <li <?php echo $activeTabAaca; ?> style = "display : <?php echo $tabhide; ?>" ><a href="#Guide04" data-toggle="tab">AACANet Comments</a></li>
                                    <?php } ?>
                                    <li <?php echo $activeTabClient; ?> style = "display : <?php echo $tabhide; ?>"><a href="#Guide05" data-toggle="tab">Client Comments</a></li>
                                    <?php 
                                    if($fetchgetcount['totaldocview']>0){?>
                                    <li><a href="#Guide06" data-toggle="tab" class="Guide06">Document View</a></li>
                                  <?php  }?>
                                 </ul>
                 <ul class="col-md-4 text-right pr0">
                 <a href="../inventory_layout" style="font-size: 20px;color: #808487;font-weight: bold;">Proceed To</a>
                 <img src="/bi/dist/img/pw-icon.gif" class="img-resposnive login-logo" alt="aacanet">
                 </ul>
            </div>
                              <div class="col-md-10 pl0">
                               
                                 <div class="tab-content p0">
                                    <div class="tab-pane <?php echo $activeStatusGen; ?>" id="Guide01">
                                       <?php if($_SESSION['userType']==1){
                                            $tabgen="disabled";
                      $tabgen1="disabled";
                                            $tabgeninfo = "disabled";
                      
                          $SIFFIRMBAL = "$".number_format($fetchres['SIFFIRMBAL'], 2);
                          $SIFFRMCOST = "$".number_format($fetchres['SIFFRMCOST'], 2);
                          $FIRMINTAMT = "$".number_format($fetchres['FIRMINTAMT'], 2);
                          // $RMSTRANA01 = "$".number_format($fetchres1['RMSTRANA01'], 2);
                          $ADDITCOST = "$".number_format($fetchres['ADDITCOST'], 2);
                          $TOTBAL = "$".number_format($totalBalanceDue, 2);
                        
                        
                                            }else if($_SESSION['userType']==2){
                        $tabgen="enabled";
                        $tabgen1="disabled";
                        $tabgeninfo = "";
                        
                        // if($fetchres['AACAACCEPT'] == 'True' || $fetchres['AACAREJECT'] == 'True' ||  $fetchres['CLNTACCEPT'] == 'True' || $fetchres['CLNTREJECT'] == 'True' || $fetchres['AUTOACCEPT'] == 'Y'){
                        
                        $SIFFIRMBAL = "$".number_format($fetchres['SIFFIRMBAL'], 2);
                          $SIFFRMCOST = "$".number_format($fetchres['SIFFRMCOST'], 2);
                          $FIRMINTAMT = "$".number_format($fetchres['FIRMINTAMT'], 2);
                          // $RMSTRANA01 = "$".number_format($fetchres1['RMSTRANA01'], 2);
                          $ADDITCOST = "$".number_format($fetchres['ADDITCOST'], 2);
                          $TOTBAL = "$".number_format($totalBalanceDue, 2);
                        // }else{
                          
                          // $SIFFIRMBAL = '$0.00';
                          // $SIFFRMCOST = '$0.00';
                          // $FIRMINTAMT = '$0.00';
                          $RMSTRANA01 = '$0.00';
                          // $ADDITCOST = '$0.00';
                          // $TOTBAL = "$".number_format($fetchres1['RMSTRANA01'], 2);
                        // }
                                            }else if($_SESSION['userType']==3){
                                            $tabgen="disabled";
                      $tabgen1="disabled";
                                            $tabgeninfo = "disabled";
                      $SIFFIRMBAL = "$".number_format($fetchres['SIFFIRMBAL'], 2);
                          $SIFFRMCOST = "$".number_format($fetchres['SIFFRMCOST'], 2);
                          $FIRMINTAMT = "$".number_format($fetchres['FIRMINTAMT'], 2);
                          // $RMSTRANA01 = "$".number_format($fetchres1['RMSTRANA01'], 2);
                          $ADDITCOST = "$".number_format($fetchres['ADDITCOST'], 2);
                          $TOTBAL = "$".number_format($totalBalanceDue, 2);
                        
                                      }?>
                                       <form action="#" id="TabFirstForm" novalidate="novalidate">
                                              <!-- By sujata -->
      <input type="hidden" id="filenumadvance" name="" value="<?php echo $FILNUM; ?>">
         <!-- end sujata -->
                                          <div class="col-sm-11 solid1 mrg30B mt50">
                                             <div class="">
                                                <div class="col-sm-12 col-md-3 p0">
                                                   <div class="col-sm-12 col-md-12 p0">
                                                      <div class="form-group clearfix">
                                                         <label>Name: <span class="mandatory">*</span></label>
                                                         <input type="text" class="form-control" id="textName" name="Name"  value="<?php if($_SESSION['userType']==2) {echo $fetchres3['fullName'].' '.$fetchres3['LastName']; }else {echo $fetchres['SIFFCNAME'];} ?>" disabled maxlength="40">
                                                         <span class="error " id="textNameError"></span>
                                                      </div>
                                                   </div>
                                                   <div class="col-sm-12 col-md-12 p0">
                                                      <div class="form-group clearfix">
                                                         <label>Email Address: <span class="mandatory">*</span></label>
                                                         <input type="text" class="form-control" id="textEmailAddress" name="EmailAddress" value="<?php if($_SESSION['userType']==2){ echo $fetchres3['email']; }else { echo $fetchres['FIRMUSERID']; }?>" disabled maxlength="50">
                                                         <span class="error " id="textEmailAddressError"></span>
                                                      </div>
                                                   </div>
                                                   <div class="col-sm-12 col-md-12 p0" style="display:none;">
                                                      <div class="form-group clearfix">
                                                         <label>Verify Email Address: <span class="mandatory">*</span></label>
                                                         <input type="text" class="form-control" id="textVerifyEmailAddress" name="VerifyEmailAddress"  value="<?php if($_SESSION['userType']==2){ echo $fetchres3['email']; }else { echo $fetchres['FIRMUSERID']; }?>" <?php echo $tabgeninfo;?> maxlength="50">
                                                         <span class="error " id="textVerifyEmailAddressError"></span>
                                                      </div>
                                                   </div>
                                                   <div class="col-sm-12 col-md-12 p0">
                                                      <div class="form-group clearfix">
                                                         <label>Phone: <span class="mandatory"></span></label>
                                                         <input type="text" class="form-control" id="textPhone" name="txtPhoneNumber" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" value="<?php if($_SESSION['firmCode'] ==2) {echo $_SESSION['phoneNo']; }else { echo $fetchres3['SIFFCPHONE']; }?>" <?php echo $tabgeninfo;?> maxlength="10">
                                                         <span class="error " id="txtPhoneNumberError"></span>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 p0">
                                                    <?php if(($fetchquerycounter['CounterAceeptReject']==1) || ($fetchquerycounter['CounterAceeptReject']==2) ){?>
                                                     <div class="col-md-6 p0">
                                                      <div class="form-group clearfix">
                                                      <button type="button" id="Historybtn" class="btn btn-primary" style="font-size: 11px;padding: 6px 6px;width: 95%;">View History</button>
                                                      </div>
                                                      </div>
                                                    <?php } ?>

                                                       

                                                      <div class="col-md-6 p0">
                                                      <div class="form-group clearfix">
                                                      <button type="button" id="btnUpdate" class="btn btn-primary pushme2" style="font-size: 11px;padding: 6px 14px;width: 95%;float: right;" enabled="">Update</button>
                                                      </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-sm-12 col-md-3 p0">
                                                 <div class="col-sm-12 col-md-12 pr0"><p class="f11 font" style="font-weight: 500;margin-top: -16px;margin-bottom: 0px;white-space:nowrap;">Firms must enter amounts shown on their system:<span class="mandatory">*</span></p></div>
                                                   <div class="col-sm-12 col-md-12">
                                                      <div class="form-group clearfix">
                                                        <label>Firm Principle Balance: <span class="mandatory">*</span></label>
                            
                                                         <input type="text" class="form-control text-right" maxlength="13" id="txtFirmBal" name="txtFirmBal" value="<?php //echo "$".number_format($fetchres['SIFFIRMBAL'], 2);  ?><?php echo $SIFFIRMBAL?>" <?php echo $tabgeninfo;?> onchange="calculateTotalBal();"  >

                                                         <input type="hidden" id="hdntxtFirmBal">
                                                         <span class="error " id="txtFirmBalError"></span>
                                                      </div>
                                                   </div>
                                                   <div class="col-sm-12 col-md-12">
                                                      <div class="form-group clearfix">
                                                         <label>Awarded Attorney Fees: </label>
                                                         <input type="text" class="form-control text-right" name="txtAttorneyFees" id="txtAttorneyFees" value="<?php //echo "$".number_format($fetchres['SIFFRMCOST'], 2);?><?php echo $SIFFRMCOST?>" <?php echo $tabgeninfo;?> onchange="calculateTotalBal();" maxlength="13">
                                                      </div>
                                                   </div>
                                                   <div class="col-sm-12 col-md-12">
                                                      <div class="form-group clearfix">
                                                         <label>Interest Amount: <span class="mandatory">*</span></label>
                                                         <input type="text" class="form-control text-right" value="<?php //echo "$".number_format($fetchres['FIRMINTAMT'], 2);?><?php echo $FIRMINTAMT?>" name="txtFIRMINTAMNT" id="txtFIRMINTAMNT" <?php echo $tabgeninfo;?> maxlength="13" onchange="calculateTotalBal();">
                                                         <span class="error " id="txtFIRMINTAMNTError"></span>
                                                      </div>
                                                   </div>
                                                   <div class="col-sm-12 col-md-12">
                                                      <div class="form-group clearfix">
                                                         <label style="white-space:nowrap">Cost Processed By AACANet: </label>
                                                         <input type="text" class="form-control text-right" name="lblAACACosts" id="lblAACACosts" value="<?php //echo "$".number_format($fetchres1['RMSTRANA01'], 2);?><?php echo "$".number_format($fetchres1['RMSTRANA01'], 2); ?>"  disabled>
                                                      </div>
                                                   </div>
                                                   <div class="col-sm-12 col-md-12">
                                                      <div class="form-group clearfix">
                                                         <label>Additional Costs: </label>
                                                         <input type="text" class="form-control text-right" name="txtAdditionalCosts" id="txtAdditionalCosts" onchange="calculateTotalBal();" value="<?php //echo "$".number_format($fetchres['ADDITCOST'], 2);?><?php echo $ADDITCOST?>" <?php echo $tabgeninfo;?> maxlength="13">
                                                      </div>
                                                   </div>
                                                   <div class="col-sm-12 col-md-12">
                                                      <div class="form-group clearfix">
                                                         <label>Total Balance Due: </label>
                                                         <input type="text" class="form-control text-right" name="total_balance_due" id="total_balance_due" value="<?php echo $TOTBAL ;?>" disabled>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6" style="padding-left: 40px;">
                                                   <div class="row">
                                                      <div class="col-sm-5 col-md-5">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Client: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right" id="clientName"><?php echo $fetchres1['WFORGNM'];?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-sm-5 col-md-5">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Date: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo date('m/d/Y');?></span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-sm-5 col-md-5">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Firm Name: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right" id="RMSASNDE01"><?php echo $fetchres1['RMSASNDE01'];?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-sm-5 col-md-5">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Consumer's Name: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right" id="WFNAME"><?php echo $fetchres1['WFNAME'];?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-sm-5 col-md-5">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Account Number: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right" id="RACTNM"><?php echo $fetchres1['RACTNM'];?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-sm-5 col-md-5">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Original Placement Amount: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo "$".number_format($fetchres1['ORPLAM'], 2);?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-sm-5 col-md-5">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Placement Date: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php if($fetchres1['HADATEF'] != 00000000){ echo date("m/d/Y", strtotime($fetchres1['HADATEF'])); }?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-sm-5 col-md-5">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Charge-off Date: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right" id="WFDTCHGDT"><?php if($fetchres1['WFDTCHGDT'] != 00000000){ echo date("m/d/Y", strtotime($fetchres1['WFDTCHGDT'])); }?>  </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-sm-5 col-md-5">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Firm File Number: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $fetchres1['WFFIRMFILE'];?>  </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-sm-5 col-md-5">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Current Status Code: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $fetchres1['RACTST']; echo "</br>";  
                                                                  echo $fetchres1['SYSDESC']; ?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                            <?php
  if($rowjugcount != 0){
    if($_SESSION['userType']==1 || $_SESSION['userType']==3 ){
      
      $deadacceptance = strtotime($fetchres['DOADATE']);

      $startmonthdate1 = date('m/d/Y');
      $startmonthdate = strtotime($startmonthdate1);

      $next_due_date = date('m/d/Y', strtotime($startmonthdate1. ' +30 days'));

      $endmonthdate = strtotime($next_due_date);
      if($fetchres['AACAACCEPT'] == 'False' || $fetchres['AACAREJECT'] == 'False'){
        
        if($deadacceptance <= $endmonthdate){
          $REJUDGDATE = date('Y-m-d',strtotime($fetchres['RJDDT']));
          $JUDJRJDDT = $REJUDGDATE;
          $JUDGAMTDEP = $fetchres['FRMJUGAMNT'];
        }else{
          if($fetchres1['RJDDT'] != ''){
            $REJUDGDATE1 = date('Y-m-d',strtotime($fetchres1['RJDDT']));
            $JUDJRJDDT = $REJUDGDATE1;
            $JUDGAMTDEP = $fetchres1['JUDGAMT'];
          }else{
            $JUDJRJDDT = 'mm/dd/yyyy';
          }

          
        }
      }
      // else {
        // echo "Testing Data";
        // die;
      // }


      
    }


    if($_SESSION['userType']==2){
       // $tabterms="enabled";
       // $tabtermsinfo = "";
       // $tabtermsradio = "";
       
       $deadacceptance = strtotime($fetchres['DOADATE']);
      
      $startmonthdate1 = date('m/d/Y');
       $startmonthdate = strtotime($startmonthdate1);
      
      $next_due_date = date('m/d/Y', strtotime($startmonthdate1. ' +30 days'));
      
      $endmonthdate = strtotime($next_due_date);
      // echo $endmonthdate;
      
      if($fetchres['AACAACCEPT'] == 'True' || $fetchres['AACAREJECT'] == 'True'){
          if(trim($fetchres1['RJDDT']) != ''){
            $REJUDGDATE1 = date('Y-m-d',strtotime($fetchres1['RJDDT']));
            $JUDJRJDDT = $REJUDGDATE1;
            $JUDGAMTDEP = $fetchres1['JUDGAMT'];
          }else{
            $JUDJRJDDT = '';
          }
        
      }else{
         
        if($deadacceptance <= $endmonthdate){
          if(trim($fetchres['RJDDT']) != ''){
            $REJUDGDATE = date('Y-m-d',strtotime($fetchres['RJDDT']));
            $JUDJRJDDT = $REJUDGDATE;
            
            $JUDGAMTDEP = $fetchres['FRMJUGAMNT'];
          }else{
            $JUDJRJDDT = '';
            
          }
          
        }else{
          if(trim($fetchres1['RJDDT']) != ''){
            $REJUDGDATE1 = date('Y-m-d',strtotime($fetchres1['RJDDT']));
            $JUDJRJDDT = $REJUDGDATE1;
            $JUDGAMTDEP = $fetchres1['JUDGAMT'];
          }else{
            $JUDJRJDDT = '';
          }
        }
        
      }
    }
  }else{
    if(trim($fetchres1['RJDDT']) != '' ){
      $REJUDGDATE1 = date('Y-m-d',strtotime($fetchres1['RJDDT']));
      $JUDJRJDDT = $REJUDGDATE1;
      $JUDGAMTDEP = $fetchres1['JUDGAMT'];
    }else{
      $JUDJRJDDT = '';
    }
  }
  // die();
                           ?>
                                                   <div class="row">
                                                      <div class="col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mt10">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Judgment Amount: <br>(If applicable) </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-7 col-md-7">
                                                         <div class="form-group clearfix mt10">
                                                            <div class="row">
                                                               <div class="col-sm-7">
                                                                  <input type="text" class="form-control" id="textJudgmentAmount" name="JudgmentAmount" placeholder="Judgment Amount" maxlength="13" value="<?php echo "$". number_format($JUDGAMTDEP, 2);?>" <?php echo  $tabgen;?> onchange="calculateTotalBal();"> 
                                                               <span class="error" id="judgmenterror" style="display: none; margin-top:10px;">This field is required</span>
                                 </div>
                                                               <div class="">
                                                                  <button type="button" id="btnUpdateJAmount" class="btn btn-primary pushme2 col-md-5" style="font-size: 11px;padding: 7px;" <?php echo  $tabgen1; ?> >
                                                                  Update
                                                                  </button>
                                                               </div>
                                 
                                                            </div>
                                                         </div>
                                                      </div>
                            
                                                   </div>
                          
                                                      <div class="row">
                                                      <div class="col-sm-5 col-md-5">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Judgment Date: <br>(If applicable) </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-7 col-md-7">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <div class="col-sm-7">
                                                                  <!--<input type="text" class="form-control" id="textJudgmentDate" name="JudgmentDate" placeholder="Judgment Date" value="<?php //echo $fetchres1['RJDDT'];?>" disabled> --->
                                  <!--<input type="text" class="form-control" id="dtofformation" name="JudgmentDate" placeholder="Judgment Date" onkeydown="return false" value="<?php //if($fetchres1['RJDDT'] != ''){ echo date("m/d/Y", strtotime($fetchres1['RJDDT'])); }?>" disabled > -->
                  <input type='date' class="form-control" id="dtofformation" onchange="datejudg()" name="JudgmentDate"  placeholder="mm-dd-yyyy" 
         value="<?php echo $JUDJRJDDT;?>" disabled > 
                  
                                 </div>
                                                               <div class="">
                                                                  <button type="button" id="btnUpdateJDate" class="btn btn-primary pushme2 col-md-5" style="font-size: 11px;padding: 7px;" <?php echo  $tabgen;?>>
                                                                  Update
                                                                  </button>
                                  <button type="button" id="btnUpdateJDatelock" class="btn btn-primary pushme2 col-md-5" style="font-size: 11px;padding: 7px;display:none;">
                                                                  Lock
                                                                  </button>
                                                               </div>
                                                            </div>
                                                         </div>
                            <span class="error" id="judgmentdateerror" style="display: none; margin-top: 10px;">Future date invalid</span>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 clearfix btn-sec">
                                                   <div class="col-xs-12 col-sm-4">
                                                   </div>
                                                   <div class="col-xs-12 col-sm-4 text-center">
                                                      <!-- <button type="submit" id="btnSubmit" class="btn btn-primary btn-xs-100 mrg0 mrg20R submitGen" <?php //echo  $tabgen;?>>Save</button> -->
                                                      <button type="button" id="btnCancel" onclick="CancelEdit();" class="btn btn-primary btn-xs-100 mrg0 mrg20R" style="display:none;">Cancel</button>
                                                   </div>
                                                   <div class="col-xs-12 col-sm-4">
                                                      <a class="btn btn-next btnNext pull-right genNxt">Next <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </form>
                                    </div>
                                    <?php 
                                       $totalUnsecuredDebt = '';
                                       $totalMortgages = '';
                                       $totalAutoLoan = '';
                                       $totalStudentLoan = '';
                                       if($_SESSION['userType']==1){
                                       $tabdebt="disabled";
                                       $tabdebtinfo="disabled";
                                       $tabdebtradio = "style='cursor: not-allowed !important'";
                                       }else if($_SESSION['userType']==2){
                                       $tabdebt="enabled";
                                       $tabdebtinfo="enabled";
                                       $tabdebtradio = "";
                                       if($fetchres['CBXTOTDEBT'] == 'Y' || $fetchres['CBXTOTDEBT'] == 1)
                                       {
                                        $totalUnsecuredDebt="readonly";
                                       }
                                       if($fetchres['CBXTOTMORT'] == 'A' || $fetchres['CBXTOTMORT'] == 'B' || $fetchres['CBXTOTMORT'] == 'C' || $fetchres['CBXTOTMORT'] == 'D')
                                       {
                                        $totalMortgages="readonly";
                                       }
                                       if($fetchres['CBXTOTAUTO'] == 'A' || $fetchres['CBXTOTAUTO'] == 'B' || $fetchres['CBXTOTAUTO'] == 'C' || $fetchres['CBXTOTAUTO'] == 'D')
                                       {
                                        $totalAutoLoan="readonly";
                                       }
                                       if($fetchres['CBXTOTSTUD'] == 'A' || $fetchres['CBXTOTSTUD'] == 'B' || $fetchres['CBXTOTSTUD'] == 'C' || $fetchres['CBXTOTSTUD'] == 'D')
                                       {
                                        $totalStudentLoan="readonly";
                                       }

                                       if($fetchres['CBXMTHPAY'] == 'Y' || $fetchres['CBXMTHPAY'] == '1')
                                       {
                                        $mnthlyPaycbx="readonly";
                                       }
                                       if($fetchres['CBXMRTYRS'] == 'Y' || $fetchres['CBXMRTYRS'] == '1')
                                       {
                                        $mortgagecbx="readonly";
                                       }
                                       if($fetchres['CBXYRSLEFT'] == 'Y' || $fetchres['CBXYRSLEFT'] == '1')
                                       {
                                        $leftMortgagecbx="readonly";
                                       }
                                       if($fetchres['REFI'] == 'Y' || $fetchres['REFI'] == '1')
                                       {
                                        $reficbx="readonly";
                                       }
                                       }else if($_SESSION['userType']==3){
                                       $tabdebt="disabled";
                                       $tabdebtinfo="disabled";
                                       $tabdebtinfo7="disabled";
                                       $tabdebtradio = "style='cursor: not-allowed !important'";
                                       }

                                       ?>
                                    <div class="tab-pane" id="Guide02">
                                       <form action="#" id="TabFirstForm" novalidate="novalidate">
                                          <div class="col-sm-11 solid2 mrg30B mt50">
                                             <div class="row">
                                                <div class="col-sm-12 col-md-6 p0">
                                                   <div class="col-md-12 col-sm-12 p0">
                                                      <div class="col-sm-6 col-md-5 p0">
                                                         <div class="form-group clearfix">
                                                            <label>Is Consumer Employed?</label>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-6 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                            <label class="radio">
                                                            <input type="radio" name="rblDebtorEmployed" class="visible-hidden AssigneeCheckRadio" value="0" <?php echo ($fetchres['DBTREMPLYD']=='0')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                            <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                            </label><label class="ml5 mr5 pl0"> Yes</label>
                                                            <label class="radio">
                                                            <input type="radio" name="rblDebtorEmployed" class="visible-hidden AssigneeCheckRadio" value="1" <?php echo ($fetchres['DBTREMPLYD']=='1')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                            <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                            </label><label class="ml5 mr5 pl0"> No</label>
                                                            <label class="radio">
                                                            <input type="radio" name="rblDebtorEmployed" class="visible-hidden AssigneeCheckRadio" value="2" <?php echo ($fetchres['DBTREMPLYD']=='2')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                            <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                            </label><label class="ml5 mr5 pl0"> SSI/Disability</label>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 col-sm-12 p0">
                                                      <div class="col-sm-6 col-md-5 p0">
                                                         <div class="form-group clearfix">
                                                               <label>Will Consumer Sign Stipulation?</label>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-6 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                               <label class="radio">
                                                               <input type="radio" name="rblStipJudg" class="visible-hidden AssigneeCheckRadio" value="Y" <?php echo ($fetchres['STIPJUDG']=='Y')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> Yes</label>
                                                               <label class="radio">
                                                               <input type="radio" name="rblStipJudg" class="visible-hidden AssigneeCheckRadio" value="N" <?php echo ($fetchres['STIPJUDG']=='N')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> No</label>
                                                               <label class="radio">
                                                               <input type="radio" name="rblStipJudg" class="visible-hidden AssigneeCheckRadio" value="P" <?php echo ($fetchres['STIPJUDG']=='P')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> N/A</label>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 col-sm-12 p0">
                                                      <div class="col-sm-6 col-md-5 p0">
                                                         <div class="form-group clearfix">
                                                               <label>Is Consumer Represented?</label>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-6 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                               <label class="radio">
                                                               <input type="radio" name="rblRep" class="visible-hidden AssigneeCheckRadio" value="Y"  onclick="show2();" <?php echo ($fetchres['RBLREP']=='Y')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> Yes</label>
                                                               <label class="radio">
                                                               <input type="radio" name="rblRep" class="visible-hidden AssigneeCheckRadio" value="N" onclick="show1();" <?php echo ($fetchres['RBLREP']=='N')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> No</label>
                                                         </div>
                                                      </div>
                                                   
                                                   </div>
                                                   <div class="col-md-12 col-sm-12 p0" id="rblRepDesc2" style="display: none;">
                                                    
                                                      <div class="col-sm-12 col-md-12 p0">
                                                         <div class="form-group clearfix">
                                                               <label class="radio">
                                                               <input type="radio" name="rblRepDesc" class="visible-hidden AssigneeCheckRadio" value="ATT" <?php echo ($fetchres['RBLREP_DESC']=='ATT')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0">Attorney</label>
                                                               <label class="radio">
                                                               <input type="radio" name="rblRepDesc" class="visible-hidden AssigneeCheckRadio" value="DMC" <?php echo ($fetchres['RBLREP_DESC']=='DMC')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0">Debt Mgmt Company</label>
                                                               <label class="radio">
                                                               <input type="radio" name="rblRepDesc" class="visible-hidden AssigneeCheckRadio" value="SLF" <?php echo ($fetchres['RBLREP_DESC']=='SLF')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0">Self</label>
                                <span class="error" id="conreperror" style="display: none;">This field is required</span>
                                                         </div>
                                                      </div>
                                                   </div>

                                                   <div class="col-md-12 col-sm-12 p0">
                                                      <div class="col-sm-6 col-md-5 p0">
                                                         <div class="form-group clearfix">
                                                               <label>Is Consumer Claiming Hardship?</label>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-6 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                               <div class="col-md-4 col-sm-4 p0" style="white-space: nowrap;"><label class="radio">
                                                               <input type="radio" name="hrdshp" id="hrdshp" class="visible-hidden AssigneeCheckRadio" value="Yes" <?php echo ($fetchres['HARDSHIPCLAIM']=='Yes')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> Yes</label>
                                                                <label class="radio">
                                                               <input type="radio" name="hrdshp" id="hrdshp" class="visible-hidden AssigneeCheckRadio" value="No" <?php echo ($fetchres['HARDSHIPCLAIM']=='No' || $fetchres['HARDSHIPCLAIM']=='')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> No</label></div>
                                                               <div class="col-md-4 col-sm-4" id="enableFileUpload" style="display: none;position: relative;top: -8px;bottom: 0px;">
                                                           <div class="file-upload">
                                                      <!--place upload image/icon first !-->
                                                      <i class="fa fa-paperclip" aria-hidden="true" style="font-size: 18px;position: relative;top: 5px;"></i>
                                                      <!--place input file last !-->
                                                      <input type="file" id="FileUpload" onchange="ValidateSingleInput(this);" name="FileUpload" <?php echo $tabdebtinfo; ?> class="files FileUpload" placeholder="" style="">
                                                   </div></div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 col-sm-12 p0">
                                                      <div class="col-sm-6 col-md-5 p0">
                                                         <div class="form-group clearfix">
                                                               <label>Status of Consumer's Residence:</label>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-6 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                               <label class="radio">
                                                               <input type="radio" name="rblDebtorResidence" class="visible-hidden AssigneeCheckRadio" id="DebtorRent" onclick="debtorRent();" value="R" <?php echo ($fetchres['RENTOWN']=='R')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> Rent</label>
                                                               <label class="radio">
                                                               <input type="radio" name="rblDebtorResidence" class="visible-hidden AssigneeCheckRadio" id="DebtorOwn" onclick="debtorOwn();" value="O" <?php echo ($fetchres['RENTOWN']=='O')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> Own</label>
                                                               <!--<label class="radio">
                                                               <input type="radio" name="rblDebtorResidence" class="visible-hidden AssigneeCheckRadio" id="DebtorUnknown" onclick="debtorUnknown();" value="U" <?php //echo ($fetchres['RENTOWN']=='U')?'checked':'' ?> <?php //echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php //echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> Unknown</label>-->
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 col-sm-12 p0" id="Debtor_Rent" style="display: none;">
                                                   <!-- <div class="row" id="Debtor_Rent" style="display: none;"> -->
                                                      <!----<div class="col-sm-12">
                                                         <div class="form-group text-center clearfix ErecordTitle">
                                                             <p style="text-align: center;"><b>Debtor Section</b></p>
                                                         </div>
                                                         </div>----->
                                                
                                                         <div class="col-sm-6 col-md-5 p0">
                                                            <div class="form-group clearfix">
                                                                  <label class="mt10">Monthly Payment Amount: </label>
                                                            </div>
                                                         </div>
                                                         <div class="col-sm-6 col-md-7 p0">
                                                            <div class="form-group clearfix">
                                                               <div class="">
                                                                  <div class="col-sm-6 pl0">
                                                                     <input type="text" class="form-control" id="MonthlyPaymentAmount01" name="MonthlyPaymentAmount01" placeholder="Monthly Payment Amount" value="<?php echo '$'.$fetchres['MRTPAY'];?>" <?php echo $tabdebtinfo; ?> <?php echo $mnthlyPaycbx; ?>> 
                                                                  </div>
                                                                  <div class="col-sm-6 mt10 p0 text">
                                                                     <input type="checkbox" name="CBXMTHPAY" id="CBXMTHPAY01" value="Y" <?php echo ($fetchres['CBXMTHPAY']=='Y')?'checked':'' ?>> Unknown &nbsp;&nbsp; <input type="checkbox" name="CBXMTHPAY" id="CBXMTHPAY02" value="1" <?php echo ($fetchres['CBXMTHPAY']=='1')?'checked':'' ?> <?php echo $tabdebtinfo; ?>> N/A
                                                                  </div>
                                                               </div>
                                                            </div>
                                                         </div>
                                                   </div>

                                                   <div class="col-md-12 col-sm-12 p0" id="Debtor_Own" style="display: none;">
                                                      <!-----<div class="col-sm-12">
                                                         <div class="form-group text-center clearfix ErecordTitle">
                                                             <p style="text-align: center;"><b>Debtor Section</b></p>
                                                         </div>
                                                         </div>----->
                                                      
                                                         <div class="col-sm-6 col-md-5 p0">
                                                            <div class="form-group clearfix">
                                                                  <label class="mt10">Monthly Payment Amount: </label>
                                                            </div>
                                                         </div>

                                                         <div class="col-sm-6 col-md-7 p0">
                                                            <div class="form-group clearfix">
                                                                  <div class="col-sm-6 pl0">
                                                                     <input type="text" class="form-control" id="MonthlyPaymentAmount02" maxlength="13" name="MonthlyPaymentAmount" placeholder="Monthly Payment Amount" <?php echo $tabdebtinfo; ?> value="<?php echo '$'.$fetchres['MRTPAY'];?>" <?php echo $mnthlyPaycbx; ?> <?php echo $tabdebtinfo; ?>> 
                                                                  </div>
                                                                  <div class="col-sm-6 mt10 p0 text">
                                                                     <input type="checkbox" name="CBXMTHPAY2" id="CBXMTHPAY21" value="Y" <?php echo ($fetchres['CBXMTHPAY']=='Y')?'checked':'' ?>> Unknown &nbsp;&nbsp;&nbsp;<input type="checkbox" name="CBXMTHPAY2" id="CBXMTHPAY22" value="1" <?php echo ($fetchres['CBXMTHPAY']=='1')?'checked':'' ?> <?php echo $tabdebtinfo; ?>> N/A
                                                                  </div>
                                                            </div>
                                                         </div>
                                                      

                                                   
                                                         <div class="col-sm-6 col-md-5 p0">
                                                            <div class="form-group clearfix">
                                                                  <label class="mt10">Original Term of Mortgage: </label>
                                                            </div>
                                                         </div>
                                                         <div class="col-sm-6 col-md-7 p0">
                                                            <div class="form-group clearfix">
                                                                  <div class="col-sm-6 pl0">
                                                                     <input type="text" class="form-control" id="OriginalTermofMortgage" name="OriginalTermofMortgage" maxlength="2" placeholder="Original Term of Mortgage"  value="<?php echo $fetchres['MRTYRS'];?>" <?php echo $mortgagecbx; ?> <?php echo $tabdebtinfo; ?>> 
                                                                  </div>
                                                                  <div class="col-sm-6 mt10 p0 text">
                                                                     <input type="checkbox" name="CBXMRTYRS" id="CBXMRTYRS01" value="Y" <?php echo ($fetchres['CBXMRTYRS']=='Y')?'checked':'' ?>> Unknown &nbsp;&nbsp;&nbsp;<input type="checkbox" id="CBXMRTYRS02" name="CBXMRTYRS" value="1" <?php echo ($fetchres['CBXMRTYRS']=='1')?'checked':'' ?> <?php echo $tabdebtinfo; ?>> N/A
                                                                  </div>
                                                            </div>
                                                         </div>
                                          
                                                      
                                                         <div class="col-sm-6 col-md-5 p0">
                                                            <div class="form-group clearfix">
                                                                  <label class="mt10">Years Left on the Mortgage: </label>
                                                            </div>
                                                         </div>
                                                         <div class="col-sm-6 col-md-7 p0">
                                                            <div class="form-group clearfix">
                                             
                                                                  <div class="col-sm-6 pl0">
                                                                     <input type="text" class="form-control" id="YearsLeftontheMortgage" name="YearsLeftontheMortgage" maxlength="2" placeholder="Years Left on the Mortgage" value="<?php echo $fetchres['MRTYRSLEFT'];?>" <?php echo $leftMortgagecbx; ?> <?php echo $tabdebtinfo; ?>> 
                                                                  </div>
                                                                  <div class="col-sm-6 mt10 p0 text">
                                                                     <input type="checkbox" name="CBXYRSLEFT" id="CBXYRSLEFT01" value="Y" <?php echo ($fetchres['CBXYRSLEFT']=='Y')?'checked':'' ?>> Unknown &nbsp;&nbsp;&nbsp;<input type="checkbox" id="CBXYRSLEFT02" name="CBXYRSLEFT" value="1" <?php echo ($fetchres['CBXYRSLEFT']=='1')?'checked':'' ?> <?php echo $tabdebtinfo; ?>> N/A
                                                                  </div>
                                          
                                                            </div>
                                                         </div>
                                             
                                                     
                                                         <div class="col-sm-6 col-md-5 p0">
                                                            <div class="form-group clearfix">
                                                                  <label class="mt10">Date of Last Refinancing: </label>
                                                            </div>
                                                         </div>
                                                         <div class="col-sm-6 col-md-7 p0">
                                                            <div class="form-group clearfix">
                                                      
                                                                  <div class="col-sm-6 pl0">
                                                                     <input type="text" class="form-control" id="DateofLastRefinancing" name="DateofLastRefinancing" placeholder="Date of Last Refinancing" onkeydown="return false" value="<?php if($fetchres['REFIDATE'] != ''){ echo date("m/d/Y", strtotime($fetchres['REFIDATE'])); }?>" <?php echo $reficbx; ?> <?php echo $tabdebtinfo; ?>> 
                                                                  </div>
                                                                  <div class="col-sm-6 mt10 p0 text">
                                                                     <input type="checkbox" name="REFI" value="Y" id="REFI01" <?php echo ($fetchres['REFI']=='Y')?'checked':'' ?>> Unknown &nbsp;&nbsp;&nbsp;<input type="checkbox" name="REFI" id="REFI02" value="1" <?php echo ($fetchres['REFI']=='1')?'checked':'' ?> <?php echo $tabdebtinfo; ?>> N/A
                                                                  </div>
                                                   
                                                            </div>
                                                         </div>
                                                   

                                                   </div>

                                                   <div class="col-md-12 col-sm-12 p0" id="Debtor_Unknown">
                                                      <div class="col-sm-6 col-md-5 p0">
                                                         <div class="form-group clearfix">
                                                            <label class="mt10">Source of Funds: </label>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-4 col-md-4 pl0">
                                                         <div class="form-group clearfix">
                                                            <select class="form-control" <?php echo $tabdebtinfo; ?> id="DDBFUNDS">
                                                               <option value="0" <?php echo ($fetchres['DDBFUNDS']=='0')?'selected':'' ?>>Wages</option>
                                                               <option value="1" <?php echo ($fetchres['DDBFUNDS']=='1')?'selected':'' ?>>Family</option>
                                                               <option value="2" <?php echo ($fetchres['DDBFUNDS']=='2')?'selected':'' ?>>Tax Return</option>
                                                               <option value="3" <?php echo ($fetchres['DDBFUNDS']=='3')?'selected':'' ?>>Loan</option>
                                                               <option value="4" <?php echo ($fetchres['DDBFUNDS']=='4')?'selected':'' ?>>Sale of Real Estate</option>
                                                               <option value="5" <?php echo ($fetchres['DDBFUNDS']=='5')?'selected':'' ?>>Sale of Assets</option>
                                                               <option value="6" <?php echo ($fetchres['DDBFUNDS']=='6')?'selected':'' ?>>Lottery</option>
                                                               <option value="7" <?php echo ($fetchres['DDBFUNDS']=='7')?'selected':'' ?>>Inheritance</option>
                                                               <option value="8" <?php echo ($fetchres['DDBFUNDS']=='8')?'selected':'' ?>>Other</option>
                                                               <option value="9" <?php echo ($fetchres['DDBFUNDS']=='9')?'selected':'' ?>>Unknown</option>
                                                               
                                                            </select>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 col-sm-12 p0">
                                                      <div class="col-sm-6 col-md-5 p0">
                                                         <div class="form-group clearfix">
                                                               <label>Has the source of Funds been verified ?</label>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-6 col-md-7 pl0">
                                                         <div class="form-group clearfix">
                                                               <label class="radio">
                                                               <input type="radio" name="Verify" class="visible-hidden AssigneeCheckRadio" value="Y" <?php echo ($fetchres['VERIFIED']=='Y')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> Yes</label>
                                                               <label class="radio">
                                                               <input type="radio" name="Verify" class="visible-hidden AssigneeCheckRadio" value="N" <?php echo ($fetchres['VERIFIED']=='N')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> No</label>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 col-sm-12 p0">
                                                      <div class="col-sm-6 col-md-5 p0">
                                                         <div class="form-group clearfix">
                                                               <label>Are there any Pending Garnishments?</label>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-6 col-md-7 pl0">
                                                         <div class="form-group clearfix">
                                                               <label class="radio">
                                                               <input type="radio" name="Garnish" class="visible-hidden AssigneeCheckRadio" value="Y" <?php echo ($fetchres['PNDGARN']=='Y')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> Yes</label>
                                                               <label class="radio">
                                                               <input type="radio" name="Garnish" class="visible-hidden AssigneeCheckRadio" value="N" <?php echo ($fetchres['PNDGARN']=='N' || $fetchres['PNDGARN']=='')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> No</label>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 col-sm-12 p0">
                                                      <div class="col-sm-6 col-md-5 p0">
                                                         <div class="form-group clearfix">
                                                               <label>Are there any Pending Bank Levies?</label>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-6 col-md-7 pl0">
                                                         <div class="form-group clearfix">
                                                               <label class="radio">
                                                               <input type="radio" name="bank" class="visible-hidden AssigneeCheckRadio" value="Y" <?php echo ($fetchres['PNDBNKLEVY']=='Y')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> Yes</label>
                                                               <label class="radio">
                                                               <input type="radio" name="bank" class="visible-hidden AssigneeCheckRadio" value="N" <?php echo ($fetchres['PNDBNKLEVY']=='N' || $fetchres['PNDBNKLEVY']=='')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> No</label>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 col-sm-12 p0">
                                                      <div class="col-sm-6 col-md-5 p0">
                                                         <div class="form-group clearfix">
                                                               <label>Are there any Pending Liens?</label>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-6 col-md-7 pl0">
                                                         <div class="form-group clearfix">
                                                               <label class="radio">
                                                               <input type="radio" name="liens" class="visible-hidden AssigneeCheckRadio" value="Y" <?php echo ($fetchres['PNDLIENS']=='Y')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> Yes</label>
                                                               <label class="radio">
                                                               <input type="radio" name="liens" class="visible-hidden AssigneeCheckRadio" value="N" <?php echo ($fetchres['PNDLIENS']=='N' || $fetchres['PNDLIENS']=='')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> No</label>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 col-sm-12 p0">
                                                      <div class="col-sm-6 col-md-5 p0">
                                                         <div class="form-group clearfix">
                                                               <label>Will all Pending Judgement Executions be stopped ?</label>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-6 col-md-7 pl0">
                                                         <div class="form-group clearfix">
                                                               <label class="radio">
                                                               <input type="radio" name="Judg" class="visible-hidden AssigneeCheckRadio" value="Y" <?php echo ($fetchres['PNDJUDGEXE']=='Y')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> Yes</label>
                                                               <label class="radio">
                                                               <input type="radio" name="Judg" class="visible-hidden AssigneeCheckRadio" value="N" <?php echo ($fetchres['PNDJUDGEXE']=='N' || $fetchres['PNDJUDGEXE']=='')?'checked':'' ?> <?php echo $tabdebtinfo; ?>>
                                                               <span class="checkround" <?php echo $tabdebtradio; ?>></span>
                                                               </label><label class="ml5 mr5 pl0"> No</label>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6">
                                                   <div class="col-md-12 p0">
                                                      <div class="col-sm-6 col-md-3 p0">
                                                         <div class="form-group clearfix">
                                                               <label class="col-xs-12 mt10 p0">Total Unsecured Debt: </label>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-6 col-md-8 p0">
                                                         <div class="form-group clearfix">
                                                              <div class="row">
                                                               <div class="col-sm-6 col-md-6">
                                                                  <input type="text" class="form-control" id="txtTotDebt" name="txtTotDebt" value="<?php echo $fetchres['TOTDEBT'];?>"  placeholder="Total Unsecured Debt"  <?php echo $tabdebtinfo; ?> <?php echo $totalUnsecuredDebt; ?> > 
                                                                  <span class="error" id="cbxTotDebtErr"></span>
                                                               </div>
                                                               <div class="col-sm-6 col-md-6 p0 mt10 text">
                                                                  <span class="col-md-6 col-sm-6 p0"><input type="checkbox" class="cbxTotDebtNew" id="cbxTotDebt1" name="cbxTotDebt" value="Y" <?php echo $tabdebtinfo; ?> <?php echo ($fetchres['CBXTOTDEBT']=='Y')?'checked':'' ?>> Unknown</span>
                                  <span class="col-md-4 col-sm-4 p0"><input type="checkbox" class="cbxTotDebtNew" id="cbxTotDebt2" name="cbxTotDebt" value="1" <?php echo $tabdebtinfo; ?> <?php echo ($fetchres['CBXTOTDEBT']=='1')?'checked':'' ?>> N/A <span>
                                                               </div>
                                                               
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 p0">
                                                      <div class="col-sm-6 col-md-3 p0">
                                                         <div class="form-group clearfix">
                                                               <label class="col-xs-12 mt10 p0">Total Mortgages: </label>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-6 col-md-8 p0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <div class="col-sm-6">
                                                                  <input type="text" class="form-control" id="txtTotalMorgages" name="txtTotalMorgages" placeholder="Total Mortgages" <?php echo $tabdebtinfo; ?> value="<?php echo $fetchres['TOTMRTDEBT'];?>" <?php echo $totalMortgages; ?>>
                                                                  <span class="error" id="cbxTotMortgagesErr"></span> 
                                                               </div>
                                                               <div class="col-sm-6 col-md-6 p0 mt10 text">
                                                                  <span class="col-md-6 col-sm-6 p0"><input type="checkbox" class="cbxTotMortgagesNew" name="cbxTotMortgages" id="cbxTotMortgages1" value="A" <?php echo $tabdebtinfo; ?> <?php echo ($fetchres['CBXTOTMORT']=='A' || $fetchres['CBXTOTMORT']=='D')?'checked':'' ?>> Unknown</span>
                                  <span class="col-md-4 col-sm-4 p0"><input type="checkbox" class="cbxTotMortgagesNew" name="cbxTotMortgages" id="cbxTotMortgages2" value="C" <?php echo $tabdebtinfo; ?> <?php echo ($fetchres['CBXTOTMORT']=='B' || $fetchres['CBXTOTMORT']=='C')?'checked':'' ?>> N/A <span>
                                                               </div>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 p0">
                                                      <div class="col-sm-6 col-md-3 p0">
                                                         <div class="form-group clearfix">
                                                               <label class="col-xs-12 mt10 p0">Total Auto Loans: </label>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-6 col-md-8 p0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <div class="col-sm-6">
                                                                  <input type="text" class="form-control" id="txtTotalAuto" name="txtTotalAuto" value="<?php echo $fetchres['TOTAUTODBT'];?>" placeholder="Total Auto Loans" <?php echo $tabdebtinfo; ?> <?php echo $totalAutoLoan; ?>> 
                                                                  <span class="error" id="cbxTotAutoErr"></span> 
                                                               </div>
                                                               <div class="col-sm-6 col-md-6 p0 mt10 text">
                                                                  <span class="col-md-6 col-sm-6 p0"><input type="checkbox" class="cbxTotAutoNew" name="cbxTotAuto" id="cbxTotAuto1" value="A" <?php echo $tabdebtinfo; ?> <?php echo ($fetchres['CBXTOTAUTO']=='A' || $fetchres['CBXTOTAUTO']=='D')?'checked':'' ?>> Unknown</span>
                                  <span class="col-md-4 col-sm-4 p0"><input type="checkbox" class="cbxTotAutoNew" name="cbxTotAuto" id="cbxTotAuto2" value="C" <?php echo $tabdebtinfo; ?> <?php echo ($fetchres['CBXTOTAUTO']=='B' || $fetchres['CBXTOTAUTO']=='C')?'checked':'' ?>> N/A <span>
                                                               </div>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 p0">
                                                      <div class="col-sm-6 col-md-3 p0">
                                                         <div class="form-group clearfix">
                                                               <label class="col-xs-12 mt10 p0">Total Student Loans: </label>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-6 col-md-8 p0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <div class="col-sm-6">
                                                                  <input type="text" class="form-control" id="txtStudentLoans" name="txtStudentLoans" value="<?php echo $fetchres['TOTSTUDDBT'];?>" placeholder="Total Student Loans" <?php echo $tabdebtinfo; ?> <?php echo $totalStudentLoan; ?>> 
                                                                  <span class="error" id="cbsTotStudentErr"></span>
                                                               </div>
                                                               <div class="col-sm-6 col-md-6 p0 mt10 text">
                                                                  <span class="col-md-6 col-sm-6 p0"><input type="checkbox" class="cbsTotStudentNew" name="cbsTotStudent" id="cbsTotStudent1" value="A" <?php echo $tabdebtinfo; ?> <?php echo ($fetchres['CBXTOTSTUD']=='A' || $fetchres['CBXTOTSTUD']=='D')?'checked':'' ?>> Unknown</span>
                                  <span class="col-md-4 col-sm-4 p0"><input type="checkbox" class="cbsTotStudentNew" name="cbsTotStudent" id="cbsTotStudent2" value="C" <?php echo $tabdebtinfo; ?> <?php echo ($fetchres['CBXTOTSTUD']=='B' || $fetchres['CBXTOTSTUD']=='C')?'checked':'' ?>> N/A <span>
                                                               </div>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-sm-12 col-md-12 p0">
                                                         <div class="form-group clearfix">
                               <label>Notes concerning Consumer's ability to pay: </label>
                                                            <textarea maxlength="1000" rows="10" class="form-control" <?php echo $tabdebtinfo; ?> id="consumerNotes"><?php echo $fetchres['FREPRESENT'];  ?></textarea>
                                                         </div>
                                                   </div>
                           <div class="consumber-view-history col-md-4">
                            <button type="button" id="PriorHistorybtn" class="btn btn-primary pull-right col-md-12" style="font-size: 11px; padding: 5px 0px; margin-bottom:10px;" <?php echo $tabdebtinfo7; ?>>
                                                               Prior Comments
                                                               </button>
                           </div>
                                                </div>
                                                
                                                <div class="col-md-12 col-sm-12 clearfix btn-sec">
                                                   <div class="col-xs-12 col-sm-4">
                                                      <a class="btn btn-next btnPrevious pull-left" >Previous</a>
                                                   </div>
                                                   <div class="col-xs-12 col-sm-4 text-center">
                                                      <!-- <button type="submit" id="btnSubmit" class="btn btn-primary btn-xs-100 mrg0 mrg20R submit" <?php //echo $tabdebt;?>>Save</button> -->
                                                      <button type="button" id="btnCancel" onclick="CancelEdit();" class="btn btn-primary btn-xs-100 mrg0 mrg20R" style="display:none;">Cancel</button>
                                                   </div>
                                                   <div class="col-xs-12 col-sm-4">
                                                      <a class="btn btn-next btnNext pull-right">Next <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </form>
                                    </div>
                                    <?php if($_SESSION['userType']==1){
                                       // $tabterms="disabled";
                                       $tabterms="enabled";
                                       $tabtermsinfo = "disabled";
                                       $tabtermsradio = "style='cursor: not-allowed !important'";
                     $DOADATE  = $fetchres['DOADATE'];
                                       }else if($_SESSION['userType']==2){
                                       $tabterms="enabled";
                                       $tabtermsinfo = "";
                                       $tabtermsradio = "";
                     
                     $deadacceptance = strtotime($fetchres['DOADATE']);
                    
                    $startmonthdate1 = date('m/d/Y');
                     $startmonthdate = strtotime($startmonthdate1);
                    
                    // $old_date = $fetchres['DOADATE'];
                    $next_due_date = date('m/d/Y', strtotime($startmonthdate1. ' +30 days'));
                    
                    $endmonthdate = strtotime($next_due_date);
                    
                    
                    if($fetchres['AACAACCEPT'] == 'True' || $fetchres['AACAREJECT'] == 'True' || $fetchres['CLNTACCEPT'] == 'True' || $fetchres['CLNTREJECT'] == 'True' || $fetchres['AUTOACCEPT'] == 'Y'){
                      
                      if(($deadacceptance >= $startmonthdate) && ($deadacceptance <= $endmonthdate)){
                        $DOADATE = $fetchres['DOADATE'];
                         
                      }else{
                        $DOADATE = date("m/d/Y", time()+86400);
                         
                      }
                    }else{
                      $DOADATE = date("m/d/Y", time()+86400);
                         
                    }
                                       }else if($_SESSION['userType']==3){
                                       $tabterms="disabled";
                                       $tabtermsinfo = "disabled";
                                       $tabtermsradio = "style='cursor: not-allowed !important'";$DOADATE  = $fetchres['DOADATE'];
                                       }
                                       if($fetchres['PPTOTDUE'] !='' && $fetchres['PPTOTDUE'] != 0.00 && $fetchres['PPMTHPYMT'] !='' && $fetchres['PPMTHPYMT'] !=0.00 && $fetchres['PPNUMMONTH'] !='' && $fetchres['PPNUMMONTH'] !=0 && $fetchres['LASTPAYDT'] != '' && $fetchres['LASTPAYDT'] != 00000000 && $fetchres['PRTAMNT'] !='' && $fetchres['PRTAMNT'] != 0.00 && $fetchres['PPLSTPAYAM'] !='' && $fetchres['PPLSTPAYAM'] !=0.00){
                                           $grayBox = 'disabled';
                                          // $clearBoxMsg = 'Please use Clear button, if you wish to submit new offer';
                                       }
                                       else
                                       {
                                         $grayBox = '';
                                         $clearBoxMsg = '';
                                       }

                                       ?>
                                    <div class="tab-pane "  id="Guide03">
                                       <form action="#" id="TabFirstForm" novalidate="novalidate">
                                          <div class="col-sm-11 mrg30B mt50 solid2">
                                             <div class="row">
                                                <div class="col-sm-12 col-md-7">
                                                   <div class="row">
                                                      <div class="col-sm-6 col-md-6 p0">
                                                         <div class="mt10 col-md-6 col-sm-4 p0">
                                 <label>Deadline of Acceptance: </label>
                                                         </div>
                            
                              <div class="form-group clearfix col-md-6 col-sm-8 pl0">
                                                               <input type="text" class="form-control" id="deadofaccpt" name="AwardedAttorneyFees" placeholder="Deadline of Acceptance" value="<?php echo $DOADATE;?>" <?php echo $tabtermsinfo; ?> onkeydown="return false">
                                                               <input type="hidden" name="currentDate" id="currentDate" value="<?php echo date("m/d/Y", time()+86400);?>">
                                                         </div>

                            
                                                      </div>
                                                  
                                                      <div class="col-sm-6 col-md-6 p0 ">
                                                         <div class="mt10 col-md-5 col-sm-6 p0">
                                 <label>Total Balance Due: </label>
                                                         </div>
                                                     
                                                         <div class="mt10 col-md-7 col-sm-6 p0 Myshow">
                                                      
                                                               <span class="col-xs-12 p0" id="totalBalDue" style="width:40%"> 
                                                               $<?php $fetchTotal = $fetchres['SIFFIRMBAL'] + $fetchres['FIRMINTAMT'] + $fetchres['SIFFRMCOST'] + $fetchres['ADDITCOST']+$fetchres1['RMSTRANA01']; echo number_format($fetchTotal,2); ?>
                                                               <!-- <?php //echo "$". $fetchres['SIFFIRMBAL'] + $fetchres['FIRMINTAMT'] + $fetchres['SIFFRMCOST'] + $fetchres['RMSTRANA01'] +$fetchres['ADDITCOST'];?> --></span>
                                 <span  id="Myshow" class="d-flex align-items-center">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</svg>
                                 </span>
                                                          
                                                         </div>
                             
                                                      </div>
                            <div class="col-md-6">
                            <div class="myhide" id="showdetail" style="display:none; box-shadow:rgb(204 204 204) 2px 4px 8px 2px; z-index:999" >
                              <table cellspacing='0;' cellpadding = '10px;' width="100%;" border="1px solid #000;">
                                <thead>
                                  <tr>
                                    <th class="tdata">Type</th>
                                    <th class="tdata">Amount</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td class="tdata">Firm Principle Balance:</td>
                                    <td class="tablealign"><?php echo '$'.$fetchres['SIFFIRMBAL']; ?></td>
                                  </tr>
                                  <tr>
                                    <td class="tdata">Awarded Attorney Fees:</td>
                                    <td class="tablealign"><?php echo '$'.$fetchres['FIRMINTAMT']; ?></td>
                                  </tr>
                                  <tr>
                                    <td class="tdata">Interest Amount:</td>
                                    <td class="tablealign"><?php echo '$'.$fetchres['SIFFRMCOST']; ?></td>
                                  </tr>
                                  <tr>
                                    <td class="tdata">Cost Processed By AACANet:</td>
                                    <td class="tablealign"><?php echo '$'.$fetchres1['RMSTRANA01']; ?></td>
                                  </tr>
                                  <tr>
                                    <td class="tdata">Additional Costs:</td>
                                    <td class="tablealign"><?php echo '$'.$fetchres['ADDITCOST']; ?></td>
                                  </tr>
                                </tbody>
                              </table>
                             </div>
                            </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-sm-12 col-md-12 p0">
                                                         <div class="form-group clearfix text-center">
                                                               <label class="radio">
                                                               <input type="radio" name="terms_offer" class="visible-hidden AssigneeCheckRadio" id="LumpSum" onclick="lumpSum();" value="<?php if(isset($fetchres['CKBXLUMSUM']) && !empty($fetchres['CKBXLUMSUM'])){ echo $fetchres['CKBXLUMSUM'];} else { echo 'LT';} ?>" <?php echo ($fetchres['CKBXLUMSUM']=='True')?'checked':'' ?> <?php echo $tabtermsinfo; ?>>
                                                               <span class="checkround" <?php echo $tabtermsradio; ?>></span>
                                                               </label><label class="ml5 mr10 pl0"> Lump Sum</label>
                                                               <label class="radio">
                                                               <input type="radio" name="terms_offer" class="visible-hidden AssigneeCheckRadio" id="PaymentPlan" onclick="paymentPlan();" value="<?php if(isset($fetchres['CHKBXPAYPL']) && !empty($fetchres['CHKBXPAYPL'])) {echo $fetchres['CHKBXPAYPL'];} else { echo 'PT'; }?>" <?php echo ($fetchres['CHKBXPAYPL']=='True')?'checked':'' ?> <?php echo $tabtermsinfo; ?>>
                                                               <span class="checkround" <?php echo $tabtermsradio; ?>></span>
                                                               </label><label class="ml5 mr10 pl0"> Payment Plan</label>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row" id="lump_sum" style="display: none;">
                                                      <div class="col-sm-12">
                                                         <div class="form-group text-center clearfix ErecordTitle">
                                                            <p style="text-align: center;"><b>Terms of Offers</b></p>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6 pl0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Enter the Amount: <span class="mandatory">*</span></label>
                                                            </div>
                                                            <input type="text" class="form-control" id="txtLumSumAmt" maxlength="13" name="txtLumSumAmt" placeholder="Enter the Amount" value="<?php echo "$".$fetchres['LUMSUMAMNT'] ?>" <?php echo $tabtermsinfo; ?> >
                                                            <span class="error " id="txtLumSumAmtError"></span>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6 pl0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Payment Date: <span class="mandatory">*</span></label>
                                                            </div>
                                                            <input type="text" class="form-control" id="PaymentDate" onkeydown="return false" name="PaymentDate" placeholder="Payment Date" value="<?php if($fetchres['LUMPAYDATE'] != 00000000){ echo date("m/d/Y", strtotime($fetchres['LUMPAYDATE'])); }?>" <?php echo $tabtermsinfo; ?> autocomplete="off">
                                                            <span class="error " id="PaymentDateError"></span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                <div class="row" id="payment_plan" style="display: none;">
                           <div id="form1" style="display: block;">
                                                      <div class="col-sm-12">
                                                         <div class="form-group text-center clearfix">
                                                            <p style="text-align: center;"><b>Terms of Offers</b></p>
                                                         </div>
                                                      </div>
                                                      <div class="col-md-12 col-sm-12 p0"><div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Total Amount Of Offer:<span class="mandatory">*</span></label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="TotalAmountOfOffer" name="TotalAmountOfOffer" placeholder="Total Amount Of Offer" value="<?php if(isset($fetchres['PPTOTDUE']) && !empty($fetchres['PPTOTDUE'])){echo "$".number_format($fetchres['PPTOTDUE'], 2);}else {echo "$"."0.00";} ?>" <?php echo $tabtermsinfo; ?> <?php echo $grayBox; ?>>
                                                            <span class="error" id="TotalAmountOfOfferErr"></span>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Monthly Payment Amount:<span class="mandatory">*</span></label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="monthlyPaymentAmt" name="monthlyPaymentAmt" placeholder="Monthly Payment Amount" value="<?php if(isset($fetchres['PPMTHPYMT']) && !empty($fetchres['PPMTHPYMT'])){ echo "$".number_format($fetchres['PPMTHPYMT'], 2);} else { echo "$"."0.00";} ?>" <?php echo $tabtermsinfo; ?> <?php echo $grayBox; ?>>
                                                         </div>
                                                      </div></div>
                            <div class="col-md-12 col-sm-12 p0">
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Number of Installments:<span class="mandatory">*</span></label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="NumberofInstallments" maxlength="3" name="NumberofInstallments" placeholder="Number of Installments" value="<?php if(isset($fetchres['PPNUMMONTH']) && !empty($fetchres['PPNUMMONTH'])){ echo $fetchres['PPNUMMONTH']; }else { echo 0;} ?>" onkeypress="return validateFloatKeyPress(event);" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" <?php echo $tabtermsinfo; ?> <?php echo $grayBox; ?>>
                                                            <span class="error" id="NumberofInstallmentsErr"></span>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Enter Interest Rate:</label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="EnterInterestRate" onkeypress="return ispercentage(this, event, true, false);" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" name="EnterInterestRate"  placeholder="Enter Interest Rate" value="<?php if(isset($fetchres['PPINTAMNT']) && !empty($fetchres['PPINTAMNT'])) {echo $fetchres['PPINTAMNT']; }else { echo '0.00';} ?>" <?php echo $tabtermsinfo; ?> <?php echo $grayBox; ?>>
                                                           
                                                         </div>
                                                      </div></div>
                            <div class="col-md-12 col-sm-12 p0">
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Initial Payment Amount:</label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="InitialPaymentAmount" name="InitialPaymentAmount" placeholder="Initial Payment Amount" value="<?php if(isset($fetchres['PPFSTPAYAM']) && !empty($fetchres['PPFSTPAYAM'])){ echo "$".number_format($fetchres['PPFSTPAYAM'], 2);}else {echo "$"."0.00";} ?>" <?php echo $tabtermsinfo; ?> <?php echo $grayBox; ?>>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Initial Installment Date:</label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="InitialInstallmentDate" name="InitialInstallmentDate" placeholder="Initial Installment Date" onkeydown="return false" value= "<?php if($fetchres['FIRSTPAYDT'] != 00000000){ echo date("m/d/Y", strtotime($fetchres['FIRSTPAYDT'])); }?>" <?php echo $tabtermsinfo; ?> <?php echo $grayBox; ?>>
                                                         </div>
                                                      </div>
                            </div>
                            <div class="col-md-12 col-sm-12 p0">
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Final Payment Amount:</label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="FinalPaymentAmount" name="FinalPaymentAmount" placeholder="Final Payment Amount" value="<?php if(isset($fetchres['PPLSTPAYAM']) && !empty($fetchres['PPLSTPAYAM'])) { echo "$".number_format($fetchres['PPLSTPAYAM'], 2); }else { echo '0.00'; } ?>" disabled>
                                                             <input type="hidden" class="form-control text-right" id="FinalPaymentAmountnotdisabled" name="FinalPaymentAmountnotdisabled" value="<?php if(isset($fetchres['PPLSTPAYAM']) && !empty($fetchres['PPLSTPAYAM'])) { echo "$".number_format($fetchres['PPLSTPAYAM'], 2); }else { echo '0.00'; } ?>" >
                                                             <span class="error " id="FinalPaymentAmountError" ></span>

                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Final Payment Date:</label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="FinalPaymentDate" name="FinalPaymentDate" placeholder="Final Payment Date" value="<?php if($fetchres['LASTPAYDT'] != 00000000){ echo date("m/d/Y", strtotime($fetchres['LASTPAYDT'])); }?>" disabled>
                                                         </div>
                                                      </div>
                            </div>
                            <div class="col-md-12 col-sm-12 p0">
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Added Interest:</label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="AddedInterest" name="AddedInterest" placeholder="Added Interest" value="<?php if(isset($fetchres['ADDEDINT']) && !empty($fetchres['ADDEDINT'])){ echo number_format($fetchres['ADDEDINT'], 2); }else {echo '0.00';} ?>" disabled>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Grand Total:</label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="GrandTotal" name="GrandTotal" placeholder="Grand Total" value="<?php if(isset($fetchres['PRTAMNT']) && !empty($fetchres['PRTAMNT'])){ echo "$".number_format($fetchres['PRTAMNT'], 2); }else {echo '0.00';} ?>" disabled>
                                                         </div>
                                                      </div></div>
                            <div class="col-md-12 p0">
                                                         <div class="col-md-6 col-md-offset-6 col-sm-6 col-sm-offset-6 clearfix p0">
                                                             <div class="col-md-6"><button type="submit" id="btnSubmit02" class="col-md-12 col-sm-6 btn btn-primary btn-xs-100 mrg0 submit" name="calcbtn" style="padding:6px 0px" <?php echo $tabtermsinfo; ?>>Calculate</button></div>
                                                             <div class="col-md-6"><button type="reset" id="btnClear" class="col-md-12 col-sm-6 btn btn-warning btn-xs-100 mrg0" style="padding:6px 0px" <?php echo $tabtermsinfo; ?>>Clear</button></div>
                                                             <!-- <span style="color: black;position: relative;left: 250px;bottom: 30px;"><?php echo $clearBoxMsg; ?></span> -->

                                                             <span class="error col-md-12" id="CalcErr"></span>
                                                         </div>
                                                      </div>
                                                   </div>
                           </div>
                                                   <div class="row">
                                                      <div class="col-sm-12 col-md-12 pl0">
                                                        
                                                         <div class="form-group clearfix">
                                                            
                                                               <label class="">Notes: </label><br/>
                                                               <span>
                                                               The values entered for a Lump Sum or Payment Plan offer are the terms upon which the offer is being reviewd and accepted. The firm's notes are only for a clarification of the offer and shall not change the terms entered.
                                                               </span>
                                 
                                                               <textarea  maxlength="500" rows="10" class="form-control" <?php echo $tabtermsinfo; ?> id="PAYPLANDET"><?php echo trim(stripslashes(htmlentities($fetchres['FREASON']))); ?><?php echo trim(stripslashes(htmlentities($fetchres['FIRMTERMS']))); ?><?php echo trim(stripslashes(htmlentities($fetchres['FRMADDCOMM']))); ?><?php echo trim(stripslashes(htmlentities($fetchres['PAYPLANDET']))); ?></textarea>
                                                            
                                                         </div>
                              
                             <div class="form-group clearfix" style="margin-top: 10px;">
                                   <div class="col-md-6"></div>
                                 
                                 <div class="col-md-6 pr0">
                                                                <div class="col-md-6" style="visibility:hidden;"><button type="button" id="btnUpdatepopup" class="btn btn-primary pull-right col-md-12" data-toggle="modal" style="font-size: 11px; padding: 4px 18px;" >
                                                               View History
                                                               </button></div>
                                                                <?php if(($fetchquerycounter['CounterAceeptReject']==0) || ($fetchquerycounter['CounterAceeptReject']==3)){?>
                                  <div class="col-md-6 p0"><button type="button" id="Historybtn" class="btn btn-primary pull-right col-md-12" <?php echo $tabtermsinfo; ?> style="font-size: 11px; padding: 5px 0px;" >
                                                               View History
                                                               </button></div>
                                                             <?php } ?>
                                 </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>





<!-- Start EXTRA Code -->
                                                <!--<div class="col-md-5 col-sm-12 highchartmanage" style="position:relative">
                                              <div id="totalbalance-chart"></div>
                                              <div id="intrest-chart" style="overflow:overlay"></div>
                                            </div>-->
                                                <div class="row" style="display: none;">
                                                      <div class="col-sm-6 col-md-6 p0">
                                                         <div class="mt10 col-md-6 col-sm-4 p0">
                                 <label>Deadline of Acceptance: </label>
                                                         </div>
                                                     
                                                         <div class="form-group clearfix col-md-6 col-sm-8 pl0">
                                                               <input type="text" class="form-control" id="deadofaccpt" name="AwardedAttorneyFees" placeholder="Deadline of Acceptance" value="<?php if(isset($fetchres['DOADATE']) && !empty($fetchres['DOADATE'])){ echo $fetchres['DOADATE']; } else {echo date("m/d/Y", time()+86400);} ?>" <?php echo $tabtermsinfo; ?> onkeydown="return false">
                                                               <input type="hidden" name="currentDate" id="currentDate" value="<?php echo date("m/d/Y", time()+86400);  ?>">
                                                         </div>
                                                      </div>
                                                  
                                                      <div class="col-sm-6 col-md-6 p0">
                                                         <div class="mt10 col-md-5 col-sm-6 p0">
                                 <label>Total Balance Due: </label>
                                                         </div>
                                                     
                                                         <div class="mt10 col-md-7 col-sm-6 p0">
                                                      
                                                         </div>
                                                      </div>
                                                   </div>

                                                   <div class="row" style="display: none;">
                                                      <div class="col-sm-12 col-md-12 p0">
                                                         <div class="form-group clearfix text-center">
                                                               <label class="radio">
                                                               <input type="radio" name="terms_offer" class="visible-hidden AssigneeCheckRadio">
                                                               <span class="checkround"></span>
                                                               </label><label class="ml5 mr10 pl0"> Lump Sum</label>
                                                               <label class="radio">
                                                               <input type="radio" name="terms_offer" class="visible-hidden AssigneeCheckRadio">
                                                               <span class="checkround"></span>
                                                               </label><label class="ml5 mr10 pl0"> Payment Plan</label>
                                                         </div>
                                                      </div>
                                                   </div>

                                                   <div class="row" style="display: none;">
                                                      <div class="col-sm-12">
                                                         <div class="form-group text-center clearfix ErecordTitle">
                                                            <p style="text-align: center;"><b>Terms of Offers</b></p>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6 pl0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Enter the Amount: <span class="mandatory">*</span></label>
                                                            </div>
                                                            <input type="text" class="form-control" id="txtLumSumAmt" maxlength="13" name="txtLumSumAmt" placeholder="Enter the Amount" value="<?php echo "$".$fetchres['LUMSUMAMNT'] ?>" <?php echo $tabtermsinfo; ?> >
                                                            <span class="error " id="txtLumSumAmtError"></span>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6 pl0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Payment Date: <span class="mandatory">*</span></label>
                                                            </div>
                                                            <input type="text" class="form-control" id="PaymentDate" onkeydown="return false" name="PaymentDate" placeholder="Payment Date" value="<?php if($fetchres['LUMPAYDATE'] != 00000000){ echo date("m/d/Y", strtotime($fetchres['LUMPAYDATE'])); }?>" <?php echo $tabtermsinfo; ?>>
                                                            <span class="error " id="PaymentDateError"></span>
                                                         </div>
                                                      </div>
                                                   </div>

                                                <div class="col-md-12 col-sm-12 p0" style="display: none;">
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Added Interest:</label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="AddedInterest" name="AddedInterest" placeholder="Added Interest" value="<?php if(isset($fetchres['ADDEDINT']) && !empty($fetchres['ADDEDINT'])){ echo number_format($fetchres['ADDEDINT'], 2); }else {echo '0.00';} ?>" disabled>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Grand Total:</label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="GrandTotal" name="GrandTotal" placeholder="Grand Total" value="<?php if(isset($fetchres['PRTAMNT']) && !empty($fetchres['PRTAMNT'])){ echo "$".number_format($fetchres['PRTAMNT'], 2); }else {echo '0.00';} ?>" disabled>
                                                         </div>
                                                      </div>
                                                   </div>

                                                   <div class="col-md-12 col-sm-12 p0" style="display: none;">
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Added Interest:</label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="AddedInterest" name="AddedInterest" placeholder="Added Interest" value="<?php if(isset($fetchres['ADDEDINT']) && !empty($fetchres['ADDEDINT'])){ echo number_format($fetchres['ADDEDINT'], 2); }else {echo '0.00';} ?>" disabled>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Grand Total:</label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="GrandTotal" name="GrandTotal" placeholder="Grand Total" value="<?php if(isset($fetchres['PRTAMNT']) && !empty($fetchres['PRTAMNT'])){ echo "$".number_format($fetchres['PRTAMNT'], 2); }else {echo '0.00';} ?>" disabled>
                                                         </div>
                                                      </div>
                                                   </div>

                                                   <div class="col-md-12 col-sm-12 p0" style="display: none;">
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Added Interest:</label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="AddedInterest" name="AddedInterest" placeholder="Added Interest" value="<?php if(isset($fetchres['ADDEDINT']) && !empty($fetchres['ADDEDINT'])){ echo number_format($fetchres['ADDEDINT'], 2); }else {echo '0.00';} ?>" disabled>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Grand Total:</label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="GrandTotal" name="GrandTotal" placeholder="Grand Total" value="<?php if(isset($fetchres['PRTAMNT']) && !empty($fetchres['PRTAMNT'])){ echo "$".number_format($fetchres['PRTAMNT'], 2); }else {echo '0.00';} ?>" disabled>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="col-md-12 col-sm-12 p0" style="display: none;">
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Added Interest:</label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="AddedInterest" name="AddedInterest" placeholder="Added Interest" value="<?php if(isset($fetchres['ADDEDINT']) && !empty($fetchres['ADDEDINT'])){ echo number_format($fetchres['ADDEDINT'], 2); }else {echo '0.00';} ?>" disabled>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Grand Total:</label>
                                                            </div>
                                                            <input type="text" class="form-control text-right" id="GrandTotal" name="GrandTotal" placeholder="Grand Total" value="<?php if(isset($fetchres['PRTAMNT']) && !empty($fetchres['PRTAMNT'])){ echo "$".number_format($fetchres['PRTAMNT'], 2); }else {echo '0.00';} ?>" disabled>
                                                         </div>
                                                      </div>
                                                   </div>

                                                   <div class="col-md-12 col-sm-12 p0" id="clearBoxMsg">
                                                   <span style="position: relative;top: 9px;"><?php echo $clearBoxMsg; ?></span>
                                                   </div>
                          
                          



<!-- end EXTRA Code -->




                                                
                         <div class="col-sm-12 col-md-12 btn-sec">
                                                   <!---<div class="col-xs-12 col-sm-4">
                                                      <a class="btn btn-next btnPrevious pull-left" >Previous</a>
                                                   </div>---->
                                                   <div class="col-xs-12 col-sm-offset-2 col-sm-8 text-center">
                                                      <!-- <button type="submit" id="btnSubmit" class="btn btn-primary btn-xs-100 mrg0 mrg20R submit terms_of_offer" <?php //echo $tabtermsinfo; ?>>Save</button> -->
                                                      <button type="submit" id="btnSubmit" class="btn btn-primary btn-xs-100 mrg0 mrg20R submit sbtSettlement" <?php echo $tabtermsinfo; ?>>Submit Settlement Offer</button>
                                                      <!--<button type="button" id="btnCancel" onclick="CancelEdit();" class="btn btn-primary btn-xs-100 mrg0 mrg20R" style="display:none;">Cancel</button>---->
                                                   </div>
                                                   <!----<div class="col-xs-12 col-sm-4">
                                                      <a class="btn btn-next btnNext pull-right">Next <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                                                   </div>---->
                                                </div>
                                               
                                       <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
                                          <div class="modal-dialog modal-md">
                                          
                                          <i class="fa fa-close close" data-dismiss="modal"></i>
                                             <div class="modal-content" style="padding: 25px 15px 15px 15px;">
                                            <div id="printJS-form">
                                             <div class="modal-title">
                                                      <div class="col-md-4 col-sm-4">
                                                      <img src="../img/aaca-net.png" id="Image2" style="width:124px;">
                                                      </div>

                                                      <div class="col-md-4 col-sm-4">
                                                          <center><h4><b>Settlement Confirmation</b></h4></center>
                                                      </div>
                                             </div>

                                                <center>
                                                  
                                                   <div class="modal-header pt30">
                                                
                                                      <div class="col-md-6 col-sm-6" style="padding-top: 20px;">
                                                         <p>Date: <?php echo date('m/d/Y');?></p>
                                                         <p>Client :<?php echo $fetchres1['WFORGNM'];?></p>
                                                         <p>Firm : <?php echo $fetchres1['RMSASNDE01'];?> </p>
                                                      </div>
                                                      <div class="col-md-6 col-sm-6" style="padding-top: 20px;">
                                                         <p>Debtor: <?php echo $fetchres1['WFNAME'];?></p>
                                                         <p>Account No: <?php echo $fetchres1['RACTNM'];?></p>
                                                         <p>Firm File No: <?php echo $fetchres1['WFFIRMFILE'];?></p>
                                                      </div>
                                                   </div>

                                                   <div class="modal-body sett_off p30_0">
                                                  
                                                      <h3><b>SETTLEMENT OFFER</b></h3>
                                                        
                                                      <b class="showPaymentPlan"><p>Payment Plan in the Amount of <span id="pymtPlnPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Initial Payment Date: <span id="intPmtDatePopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Monthly Payment: <span id="mthlyPmtPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Final Payment Date: <span id="finalPmtDatePopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Total Number of Payments: <span id="totlNoPymtPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Down Payment Amount: <span id="dwnPmtPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Final Payment Amount: <span id="finlPmtPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Added interest: <span id="addedIntrPopup"></span></b>
                                                       
                                                        
                                                          <b class="showLumSum"><p>Lum Sum in the amount of <span id="lumbSumAmtPopup"></span></b>
                                                          <b class="showLumSum"><p>paid in full on <span id="paidFullPopup"></span></b>
                                                        
                                                      <p>
                                                     

                                                      <div class="sections" style="padding-top: 20px;">
                                                      <p>Total Repayment Amount: <span id="totalRePmtPopup"></span>
                                                <p><span id="balanceType"></span>: <span id="totalBalancePopup" ></span> </p>
                                                <p>Percentage of Offer: <span id="percntOffer"></span></p>
                                                      </div>
                                                      
                                                   </div>
                                               </center>
                                                </div>
                                                <center>
                                                   <div class="modal-footer txt_cntr p20_0">
                                                      <button type="button" class="btn btn2 btn-sub" id="btnSubmitFrm">Submit Settlement Offer</button>
                                                      <button type="button" class="btn btn1 btn-sub" data-dismiss="modal">Return To Form</button>
                                                      <button type="button" class="btn btn btn-sub" onclick="printJS('printJS-form', 'html')">Print</button>
                                                      
                                                   </div>
                                                </center>
                                             </div>

                                          </div>
                                       </div>

                                       <!-- END Submit Settlement Offer Modal -->

                                      
                                       <!-- History Modal for Terms of Offer -->
                                       
                                       <!-- END History Modal for Terms of Offer -->
                                    </div></div></div>
                                    <?php if($_SESSION['userType']==1){
                                       $tabaaca="enabled";
                                       }else if($_SESSION['userType']==2){
                                       $tabaaca="disabled";
                                       }else if($_SESSION['userType']==3){
                                       $tabaaca="disabled";
                                       }
                                       if($_SESSION['userType']!=3){?>
                                    <div class="tab-pane <?php echo $activeStatusAaca; ?>" id="Guide04">
                                       <form action="#" id="TabFirstForm" novalidate="novalidate">
                                          <div class="col-sm-11 solid2 mrg30B mt50">
                                             <div class="row">
                                                <div class="col-sm-12 col-md-6 p0">
                                                   <div class="row">
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Reviewer Name: <span class="mandatory">*</span></label>
                                                            </div>
                                                            <input type="text" class="form-control" id="ReviewerName" name="ReviewerName" placeholder="Reviewer Name" value="<?php if($_SESSION['userType'] == 1){ echo $fetchres3['fullName'].' '.$fetchres3['LastName']; } else { echo $fetchres['AACAREVNAM']; }?>" disabled>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Reviewer Email: <span class="mandatory">*</span></label>
                                                            </div>
                                                            <input type="text" class="form-control" id="ReviewerEmail" name="ReviewerEmail" placeholder="Reviewer Email" value="<?php if($_SESSION['userType'] == 1) {echo $fetchres3['email']; } else { echo $fetchres['AACAEMAIL']; }?>" disabled>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-sm-12 col-md-6" style="display:none;">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Verify Email: <span class="mandatory">*</span></label>
                                                            </div>
                                                            <input type="text" class="form-control" id="textPhone2" name="Verify Email" placeholder="Verify Email"  value="<?php if($_SESSION['userType'] == 1) {echo $fetchres3['email']; } else { echo $fetchres['AACAEMAIL']; }?>" <?php echo $tabaaca; ?>>
                                                            <span class="error" id="verifyEmailError"></span>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Responses:</label>
                                                            </div>
                                                            <?php if($currentStatus == 2){ ?>
                                                            <p class="mb0"><input type="checkbox" class="cbxAACA" id="AACAACCEPT" value="True" <?php echo ($fetchres['AACAACCEPT']=='True')?'checked':'' ?> <?php echo $tabaaca; ?>> Accept Offer </p>
                                                            <?php } ?>
                                                            <?php if($currentStatus == 1){ ?>
                                                            <p class="mb0 wh_wrap"><input type="checkbox" value="True" class="cbxAACA" id="AACAACCEPT" <?php echo ($fetchres['AACAACCEPT']=='True')?'checked':'' ?> <?php echo $tabaaca; ?>> Reopen to Accept Offer </p>
                                                            <?php } ?>
                                                            <p class="mb0"><input type="checkbox" id="AACAREJECT" class="cbxAACA" value="True" <?php echo ($fetchres['AACAREJECT']=='True')?'checked':'' ?> <?php echo $tabaaca; ?>> Deny Offer</p>
                                                            <p class="mb0"><input type="checkbox" class="cbxAACA" id="REFERCLNT" value="True" <?php echo ($fetchres['AACARFRTOCLNT']=='True')?'checked':'' ?> <?php echo $tabaaca; ?> <?php echo $tabaaca; ?>> Refer To Client </p>
                                                            <p class="mb0 wh_wrap"><input type="checkbox" id="SubmitCounterchk" class="cbxAACA" value="True" <?php echo ($fetchres['AACACNTR']=='True')?'checked':'' ?> <?php echo $tabaaca; ?>> Counter Offer </p>
                                                            <p class="mb0"><input type="checkbox" value="True" class="cbxAACA" id="AACAADDINF" <?php echo ($fetchres['AACAADDINF']=='True')?'checked':'' ?> <?php echo $tabaaca; ?>> Request More Info </p>
                                                            

                                                            <?php if(isset($fetchHardshipDoc['HARDSHIPCOPY']) && $fetchHardshipDoc['HARDSHIPCOPY'] != '' ) {
                                                              $file_path = 'http://'.$_SERVER['SERVER_NAME'].'/bi/dist/Mako/HARDSHIPDOCS/'.$FILNUM.'/'.$fetchHardshipDoc['HARDSHIPCOPY']; ?>
                                                            <p class="mb0 wh_wrap" style="padding-left: 15px;"><a href="<?php echo $file_path;?>" download>Click here to download hardship</a></p>
                                                            <?php } ?>
                                                         </div>
                                                         <span class="error " id="cbxAACAError"></span>
                                                      </div>
                                                   </div>
            
          <div class="modal fade" id="modalDenyPopup" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-color">
                  <h3 class="modal-title text-center" id="lineModalLabel">
                    <b>Reason for denial</b>
                  </h3>
                </div>
                <div class="modal-body clearfix" style="padding-top: 2px;">
                  <div class="row">
                    <div class="col-md-12" style="padding: 12px 20px 0px 20px;">
                      <span class="error" id="AacaDenyCmtError"></span>
                    <div class="panel-group" style="margin-bottom: 0px;">
                      <div class="panel">
                        <textarea class="form-control" placeholder="Add Additional Comments Here" id="AACADENYR" rows="10" cols="50"></textarea>
                      </div>
                    </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                    <div class="btn-group" role="group">
                      <button type="submit" id="denysubmitInfoBtn" name="UpdateBtn" class="btn btn-default btn-hover-green btm-right-radius" role="button">Submit</button>
                    </div>
                    <div class="btn-group" role="group">
                      <button type="button" id="closedenyofferAca" class="btn btn-default btm-left-radius" data-dismiss="modal" role="button">Close</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

                      <div class="modal fade" id="modalPopup" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header bg-color">
                                  <h3 class="modal-title text-center" id="lineModalLabel">
                                  <b>Request More Info</b>
                                  </h3>
                              </div>
                              <div class="modal-body clearfix" style="padding-top: 2px;">
                              <div class="row">
                                  <div class="col-md-12" style="padding: 12px 20px 0px 20px;">
                                    <span class="error" id="ADDINFREASError"></span>
                                 <div class="panel-group" style="margin-bottom: 0px;">
                                      <div class="panel">
                                      <textarea class="form-control" placeholder="Add Additional Comments Here" id="ADDINFREAS" rows="10" cols="50"></textarea>
                                      </div>
                                 </div>
                                 </div>
                              </div>
                      </div>
                      <div class="modal-footer">
                                <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                                    <div class="btn-group" role="group">
                                        <button type="submit" id="submitInfoBtn" name="UpdateBtn" class="btn btn-default btn-hover-green btm-right-radius">Submit</button>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <button type="button" id="closesubmitInfoBtn" class="btn btn-default btm-left-radius" data-dismiss="modal" role="button">Close</button>
                                    </div>
                                  </div>
                               </div>
                       
                              </div>
                            </div>
                          </div>
                                                      <div class="col-sm-12 col-md-12">
                                                         
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                              <div style="margin-bottom:5px">
                               <label class="">Comments: </label>
                              </div>
                                                               <textarea maxlength="1000" rows="15" id="AACAADDCOM" class="form-control" <?php echo $tabaaca; ?>><?php echo $fetchres['AACAADDCOM'] ?></textarea>
                                <span class="error" id="cbxAACAcommentError"></span>
                                                            </div>
                                                         </div>
                             <div class="form-group clearfix">
                                                            <div class="row">
                                                              
                                                               <button type="button" id="Historybtn01" class="btn btn-primary pull-right" style="font-size: 11px; padding: 4px 18px;" <?php echo $tabaaca;?> >
                                                               View History
                                                               </button>
                                                            </div>
                                                         </div>
                                                      </div>
                                                  
                                                </div>
                                                <div class="col-sm-12 col-md-6" style="padding-left: 40px;">
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Client: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $fetchres1['WFORGNM'];?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Firm Name: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $fetchres1['RMSASNDE01'];?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Consumer Name: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $fetchres1['WFNAME'];?>  </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Account Number: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $fetchres1['RACTNM'];?>  </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Placement Amount: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo "$".number_format($fetchres1['ORPLAM'], 2);?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Judgment Amount: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo "$".number_format($fetchres['FRMJUGAMNT'], 2);?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Total Balance Due: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right">
                                                               $<?php echo number_format($fetchres['SIFFIRMBAL'] + $fetchres['FIRMINTAMT'] + $fetchres['SIFFRMCOST'] + $fetchres['COSTPROC'] + $fetchres['ADDITCOST'], 2);?>
                                                               </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Blanket Authority: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <!--<span class="col-xs-12 text-right"><?php echo $fetchres2['LUMPPERC']. "%";?> </span>-->
                                 <span class="col-xs-12 text-right"><?php echo $LUMPPERC . "%";?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Gross Settlement Offer: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                             <?php
                              if($fetchres['CKBXLUMSUM'] == 'True'){
                                $grosseAmt = $fetchres['LUMSUMAMNT'];
                              }elseif($fetchres['CHKBXPAYPL'] == 'True'){
                                $grosseAmt = $fetchres['PRTAMNT'] - $fetchres['ADDEDINT'];
                              }
                             ?>
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo '$'.number_format($grosseAmt, 2) ?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Added Interest: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $fetchres['PPINTAMNT'].'%';?></span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Grand Total: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                              <?php
                                if($fetchres['CKBXLUMSUM'] == 'True'){
                                  $grandTotal = $fetchres['LUMSUMAMNT'];
                                }elseif($fetchres['CHKBXPAYPL'] == 'True'){
                                  $grandTotal = $fetchres['PRTAMNT'];
                                }
                              ?>
                             
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo  '$'.number_format($grandTotal, 2); ?></span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Number of Payments: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                             <?php
                              if($fetchres['CKBXLUMSUM'] == 'True'){
                                $numofpayment = 1;
                              }elseif($fetchres['CHKBXPAYPL'] == 'True'){
                                $numofpayment = $fetchres['PPNUMMONTH'];
                              }
                             ?>
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $numofpayment; ?>  </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Percentage of Offer: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right" id="PERCOFFER"><?php echo $fetchres['PERCOFFER'] . "%";?></span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Deadline of Acceptance: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $fetchres['DOADATE'];?></span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <!-- Submit Counter Offer AACA -->
                                                   <?php if($fetchres['AACACNTR'] == 'True'){
                                                      $aacaCounterOffr = $fetchres['AACACNTOFF'];
                                                      $aacaCounterOffr = substr($aacaCounterOffr, 5);

                                                      $aacaCounterOffr = explode(",",$aacaCounterOffr);

                                                      $amtOffr = $aacaCounterOffr[0];
                                                      $amtDown = $aacaCounterOffr[1];
                                                      $mthlyPmt = $aacaCounterOffr[2];
                                                      $noOfPmt = $aacaCounterOffr[3];
                                                      $finalPmt = $aacaCounterOffr[4];
                                                      $deadlineOfAccept = $aacaCounterOffr[5];
                                                      $firstPymt = $aacaCounterOffr[6];
                                                   } ?>
                                                   <div class="row" id="SubmitCounterdiv" style="display: none;">
                                                      <div class="row">
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                              <label class="col-xs-12 text-right mt10">Amount of CounterOffer : </label>
                                                            </div>
                                                         </div>
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <input type="text" maxlength="12" class="form-control text-right" id="AACACounterOfferAmount" name="AACACounterOfferAmount" placeholder="Amount of Counter Offer" value="<?php if(isset($amtOffr) && !empty($amtOffr)){echo $amtOffr;}else{ echo '$0.00'; }?>" onchange="calculateTotalBal();">
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="row">
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <label class="col-xs-12 text-right mt10">Initial Payment Amount: </label>
                                                            </div>
                                                         </div>
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <input type="text" maxlength="12" class="form-control text-right" id="AACACounterOfferAmountDown" name="AACACounterOfferAmountDown"placeholder="Initial payment amount" value="<?php if(isset($amtDown) && !empty($amtDown)){echo $amtDown;}else{ echo '$0.00'; }?>" onchange="calculateTotalBal();">
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="row">
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <label class="col-xs-12 text-right mt10">Monthly Payment Amount : </label>
                                                            </div>
                                                         </div>
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <input type="text" class="form-control text-right" maxlength="12" id="AACACounterOfferMonthlyPayment" name="AACACounterOfferMonthlyPayment" placeholder="Monthly Payment Amount" value="<?php if(isset($mthlyPmt) && !empty($mthlyPmt)){echo $mthlyPmt;}else{ echo '$0.00'; }?>" onchange="calculateTotalBal();">
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="row">
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <label class="col-xs-12 text-right mt10">Number of Payments: </label>
                                                            </div>
                                                         </div>
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <input type="text" class="form-control text-right" id="AACACounterOfferNumberOfPayments" name="AACACounterOfferNumberOfPayments" maxlength="3" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" onkeypress="return validateFloatKeyPress(event);" placeholder="Number of Payments" value="<?php if(isset($noOfPmt) && !empty($noOfPmt)){echo $noOfPmt;}else{ echo '0'; }?>" onchange="calculateTotalBal();">
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="row">
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <label class="col-xs-12 text-right mt10">Final Payment Amount : </label>
                                                            </div>
                                                         </div>
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <input type="text" class="form-control text-right" maxlength="12" id="AACACounterOfferFinalPayment" name="AACACounterOfferFinalPayment" placeholder="Final Payment Amount" value="<?php if(isset($finalPmt) && !empty($finalPmt)){echo $finalPmt;}else{ echo '$0.00'; }?>" onchange="calculateTotalBal();">
                                                                <span class="error " id="AACACounterOfferFinalPaymentError"></span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      
                                                      <div class="row">
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <label class="col-xs-12 text-right mt10">Deadline of Acceptance : </label>
                                                            </div>
                                                         </div>
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <input type="text" class="form-control text-right" id="AACACounterOfferDeadlineDate" name="AACACounterOfferDeadlineDate" onkeydown="return false" placeholder="Deadline of Acceptance" value="<?php if(isset($deadlineOfAccept) && !empty($deadlineOfAccept)){echo $deadlineOfAccept;} ?>">
                                                            </div>
                                                         </div>
                                                      </div>
                                                     <div class="row">
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix">
                                                               <label class="col-xs-12 text-right mt10">First Payment Due : </label>
                                                            </div>
                                                         </div>
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix">
                                                               <input type="text" class="form-control text-right" id="AACACounterOfferFirstPaymentDate" name="AACACounterOfferFirstPaymentDate" onkeydown="return false" value="<?php if(isset($firstPymt) && !empty($firstPymt)){echo $firstPymt;} ?>" >
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-md-12 p0">
                                                         <div class="col-md-6 col-md-offset-6 col-sm-6 col-sm-offset-6 clearfix p0">
                                                             <div class="col-md-6 pr0"><button type="submit" id="btnSubmit01" class="col-md-12 col-sm-6 btn btn-primary btn-xs-100 mrg0 submit" name="calcbtn" style="padding:6px 0px">Calculate</button></div>
                                                             <div class="col-md-6 pr0"><button type="submit" id="btnReset01" class="col-md-12 col-sm-6 btn btn-warning btn-xs-100 mrg0" style="padding:6px 0px">Clear</button></div>

                                                             <span class="error col-md-12" id="warningErr"></span>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-xs-12col-md-12 col-sm-12 btn-sec">
                                                   <div class="col-xs-12 col-sm-4">
                                                      <a class="btn btn-next btnPrevious pull-left" >Previous</a>
                                                   </div>
                                                   <div class="col-xs-12 col-sm-4 text-center">
                                                      <button type="submit" id="btnSubmitReply" class="btn btn-primary btn-xs-100 mrg0 mrg20R submit" <?php echo $tabaaca;?>>Submit Reply</button>
                                                      <button type="button" id="btnCancel" onclick="CancelEdit();" class="btn btn-primary btn-xs-100 mrg0 mrg20R" style="display:none;">Cancel</button>
                                                   </div>
                                                   <div class="col-xs-12 col-sm-4">
                                                      <a class="btn btn-next btnNext pull-right">Next <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </form>
                                       <!-- History Modal AACA -->
                                       <div class="modal fade" id="myHistoryModal" role="dialog">
                                          <div class="modal-dialog modal-md">
                                             <div class="modal-content">
                                                <center>
                                                   <div class="col-md-12 p0 pr5">
                                                      <button type="button" class="close close_top" data-dismiss="modal">&times;</button>
                                                      <button class="float_r print" type="button">print</button>
                                                   </div>
                                                   <div class="col-md-12">
                                                      <h4><b>History</b></h4>
                                                      <div class="col-md-12 head_top">
                                                         <p><b>Date: <?php echo date('m/d/Y');?></b></p>
                                                         <p><b>Debtor: <?php echo $fetchres1['WFNAME'];?></b></p>
                                                         <p><b>Account No: <?php echo $fetchres1['RACTNM'];?></b></p>
                                                      </div>
                                                      <!--<div class="col-md-6  head_top align_2">
                                                         <p>Debtor: AUDREY F SMITH</p>
                                                         <p>Account No: 5155970007503200</p>
                                                         <p>Firm File No: 28003579</p>
                                                         <p>Client : MARQUEE</p>
                                                         <p>Firm : MCLEMORE & EDINGTON,PLLC </p></div>--->
                                                   </div>
                                                   <!-- <?php //while($mod=mysqli_fetch_assoc($result)){
                                                      ?> -->
                                                   <div class="modal-body col-md-12  sett_off p30_0">
                                                      <h5><b>FIRM SETTLEMENT SUBMISSION</b> </h5>
                                                      <p><b>Submission Response: <?php echo $fetchres1['RACTST'] .' '. $fetchres1['SYSDESC']; ?></b></p>
                                                      <p><b> <?php echo date('m/d/Y h:i:s A');?></b> Submit By: <?php echo $fetchres['SIFFCNAME'];?> </p>
                                                      <p>Payment Plan in the amount of <span style="text-decoration:underline;"><?php echo "$".$fetchres['LUMSUMAMNT'] ?></span> </p>
                                                   </div>
                                                   <div class="modal-content box col-md-12 sett_off p30_0">
                                                      <p>Payment Plan Details:</br> </p>
                                                      <p>Total Balance Due:$<?php echo $fetchres['SIFFIRMBAL'] + $fetchres['FIRMINTAMT'] + $fetchres['SIFFRMCOST'] + $fetchres['ADDITCOST'];?> </p>
                                                      <p>Initial Payment Date:<?php echo $fetchres['FIRSTPAYDT'] ?> </p>
                                                      <p>Monthly Payment: <?php echo "$".$fetchres['PPMTHPYMT'] ?> </p>
                                                      <p>Final Payment Date:<b> <?php echo $fetchres['LASTPAYDT'] ?> </b> </p>
                                                      <p>Total Number of Payments: <?php echo $fetchres['PPNUMMONTH'] ?> </p>
                                                      <p>Down Payment Amount: <?php echo "$".$fetchres['PPFSTPAYAM'] ?> </p>
                                                      <p>Final Payment Amount:<b> <?php echo "$".$fetchres['PPLSTPAYAM'] ?> </b></p>
                                                      <p>Added Interest: <?php echo $fetchres['ADDEDINT'] ?> </p>
                                                      <p>Total Repayment Amount: <b><?php echo "$".$fetchres['PRTAMNT'] ?> </b> </p>
                                                      </br>
                                                      <p>Firm Contact Name: <span id="SIFFCNAME"><?php echo $fetchres['SIFFCNAME'];?></span> </p>
                                                      <p>Firm Contact Phone: <span id="SIFFCPHONE"><?php echo $fetchres['SIFFCPHONE'];?> </span></p>
                                                      <p>Firm Contact Email: <span id="SIFFCEMAIL"><?php echo $fetchres['SIFFCEMAIL'];?> </span></p>
                                                      <p>Firm Principle Balance : <?php echo "$". $fetchres['SIFFIRMBAL'];?> </p>
                                                      <p>Interest Amount: <?php echo "$". $fetchres['FIRMINTAMT'];?> </p>
                                                      <p>Costs Processed(AACA): <?php echo "$". $fetchres1['RMSTRANA01'];?> </p>
                                                      <p>Additional Costs: <?php echo "$". $fetchres['ADDITCOST'];?> </p>
                                                      <p>Firm Judgment Amount: <?php echo "$". $fetchres1['JUDGAMT'];?> </p>
                                                      <p>Judgment Date: <?php echo $fetchres1['RJDDT'];?> </p>
                                                      <p>Deadline of Acceptance: <?php echo $fetchres['DOADATE'];?> </p>
                                                      <p>Firm Notes:<b> <?php echo $fetchres['FREASON'] . ' ' . $fetchres['FIRMTERMS'] . ' ' . $fetchres['FRMADDCOMM'] . ' ' . $fetchres['PAYPLANDET'] ?> </b> </p>
                                                   </div>
                                                   <!-- AACA -->
                                                   <div class="modal-body col-md-12  sett_off p30_0">
                                                      <h5><b>AACA SETTLEMENT UPDATE</b> </h5>
                                                      <p> AACA Action: Countered </p>
                                                      <p><b>Submission Response: <?php echo $fetchres1['RACTST'] .' '. $fetchres1['SYSDESC']; ?></b></p>
                                                      <p><b> <?php echo $fetchres['AACADATE'];?> </b> Submit By: <?php echo $fetchres['SIFFCNAME'];?> </p>
                                                      </br>
                                                      <p>AACA Reviewer: <b><?php echo $fetchres['AACAREVNAM'];?> </b></p>
                                                      <p>AACA Email Address: <b><?php echo $fetchres['AACAEMAIL'];?> </b></p>
                                                   </div>
                                                   <div class="modal-content box col-md-12 sett_off p30_0">
                                                      <p>CounterOffer: </p>
                                                      </br>
                                                      <p>Amount of Offer: <?php echo "$".$fetchres['AACACNTOFF'];?> </p>
                                                      <p>Initial payment amount: <?php echo "$".$fetchres['AACACNTOFF'];?> </p>
                                                      <p>Monthly Payment: <?php echo "$".$fetchres['AACACNTOFF'];?> </p>
                                                      <p>Number of Payments: <?php echo "$".$fetchres['AACACNTOFF'];?> </p>
                                                      <p>Final Payment: <?php echo "$".$fetchres['AACACNTOFF'];?> </p>
                                                      <p>First Payment Date: <?php echo $fetchres['AACACNTOFF'];?> </p>
                                                      <p>Deadline Date: <?php echo $fetchres['AACACNTOFF'];?> </p>
                                                      <p>AACA Comments:  <b><?php echo $fetchres['AACAADDCOM'] ?> </b></p>
                                                      <p>AACA Add Info Reason: <?php echo $fetchres['ADDINFREAS'] ?></p>
                                                      <p>Response from Firm: <?php echo $fetchres['Additionalinfocomments'] ?></p>
                                                       <?php if($fetchres['CounterAceeptReject']==1){
                                                        $action="Accepted";?>
                                                        <p> <b>Firm Action on CounterOffer:</b> <?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres['CounterAceeptReject']==2){
                                                        $action="Rejected";?>
                                                         <p> <b>Firm Action on CounterOffer:</b> <?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres['CounterAceeptReject']==4){
                                                        $action="Expired";?>
                                                         <p> <b>Firm Action on CounterOffer:</b> <?php echo $action; ?> </b></p>
                                                     <?php }?>
                                                     <?php if($fetchres['Additionalinfo']==1){
                                                        $action="Provided";?>
                                                       <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres['Additionalinfo']==2){
                                                        $action="Unavailable";?>
                                                         <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres['Additionalinfo']==3){
                                                        $action="Need More Info";?>
                                                        <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres['Additionalinfo']==4){
                                                        $action="Expired";?>
                                                        <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }?>

                                                   </div>
                                                   <!-- Client -->
                                                   <div class="modal-body col-md-12  sett_off p30_0">
                                                      <h5><b>CLIENT SETTLEMENT UPDATE</b> </h5>
                                                      <p> Client Action: Countered </p>
                                                      <p><b>Submission Response: <?php echo $fetchres1['RACTST'] .' '. $fetchres1['SYSDESC']; ?></b></p>
                                                      <p><b> <?php echo $fetchres['CLIENTDATE'];?> </b> Submit By: <?php echo $fetchres['SIFFCNAME'];?> </p>
                                                      </br>
                                                      <p>Client Reviewer: <b><?php echo $fetchres['CLNTREVNAM'];?> </b></p>
                                                      <p>Client Email Address: <b><?php echo $fetchres['CLNTEMAIL'];?> </b></p>
                                                      <p>Additional Client Notes: <?php echo $fetchres['CLNTREAS'];?> </p>
                                                   </div>
                                                   <div class="modal-content box col-md-12 sett_off p30_0">
                                                      <p>CounterOffer: </p>
                                                      </br>
                                                      <p>Amount of Offer: <?php echo "$".$fetchres['CLNTCNINST'];?> </p>
                                                      <p>Initial payment amount: <?php echo "$".$fetchres['CLNTCNINST'];?> </p>
                                                      <p>Monthly Payment: <?php echo "$".$fetchres['CLNTCNINST'];?> </p>
                                                      <p>Number of Payments: <?php echo "$".$fetchres['CLNTCNINST'];?> </p>
                                                      <p>Final Payment: <?php echo "$".$fetchres['CLNTCNINST'];?> </p>
                                                      <p>First Payment Date: <?php echo $fetchres['CLNTCNINST'];?> </p>
                                                      <p>Deadline Date: <?php echo $fetchres['CLNTCNINST'];?> </p>
                                                      <p>Client Notes:</p>
                                                   </div>
                                                   <!-- <?php // } ?> -->
                                                </center>
                                                <div class="modal-footer no_bor_t"></div>
                                             </div>
                                          </div>
                                       </div>
                                       <!-- END History Modal AACA -->
                                    </div>
                                    <!-- Submit AACA Settlement Offer Modal -->
                                    <div class="modal fade" id="myAacaModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
                                          <div class="modal-dialog modal-md">
                                          
                                          <i class="fa fa-close close" data-dismiss="modal"></i>
                                             <div class="modal-content" style="padding: 25px 15px 15px 15px;">
                                            <div id="printJS-form">
                                             <div class="modal-title">
                                                      <div class="col-md-4 col-sm-4">
                                                      <img src="../img/aaca-net.png" id="Image2" style="width:124px;">
                                                      </div>

                                                      <div class="col-md-4 col-sm-4">
                                                          <center><h4><b>Settlement Confirmation</b></h4></center>
                                                      </div>
                                             </div>

                                                <center>
                                                  
                                                   <div class="modal-header pt30">
                                                
                                                      <div class="col-md-6 col-sm-6" style="padding-top: 20px;">
                                                         <p>Date: <?php echo date('m/d/Y');?></p>
                                                         <p>Client :<?php echo $fetchres1['WFORGNM'];?></p>
                                                         <p>Firm : <?php echo $fetchres1['RMSASNDE01'];?> </p>
                                                      </div>
                                                      <div class="col-md-6 col-sm-6" style="padding-top: 20px;">
                                                         <p>Debtor: <?php echo $fetchres1['WFNAME'];?></p>
                                                         <p>Account No: <?php echo $fetchres1['RACTNM'];?></p>
                                                         <p>Firm File No: <?php echo $fetchres1['WFFIRMFILE'];?></p>
                                                      </div>
                                                   </div>

                                                   <div class="modal-body sett_off p30_0">
                                                  
                                                      <h3 class="showPaymentPlan"><b>SETTLEMENT OFFER</b></h3>
                                                        
                                                      <b class="showPaymentPlan"><p>Payment Plan in the Amount of <span id="pymtPlnAacaPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Initial Payment Date: <span id="intPmtDateAacaPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Monthly Payment: <span id="mthlyPmtAacaPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Final Payment Date: <span id="finalPmtDateAacaPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Total Number of Payments: <span id="totlNoPymtAacaPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Down Payment Amount: <span id="dwnPmtAacaPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Final Payment Amount: <span id="finlPmtAacaPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Added interest: <span id="addedIntrAacaPopup"></span></b>


                                                      <h3 class="showCounterPlan"><b>COUNTER OFFER</b></h3>
                                                        
                                                      <b class="showCounterPlan"><p>Amount of Offer: <span id="conterOfferPopup"></span></b>
                                                      <b class="showCounterPlan"><p>Initial payment amount: <span id="amountDownPopup"></span></b>
                                                      <b class="showCounterPlan"><p>Monthly Payment: <span id="monthlyPaymentPopup"></span></b>
                                                      <b class="showCounterPlan"><p>Number of Payments: <span id="noOfPmtPopup"></span></b>
                                                      <b class="showCounterPlan"><p>Final Payment: <span id="finalPmtPopup"></span></b>
                                                      <b class="showCounterPlan"><p>First Payment Date: <span id="firstPymtPopup"></span></b>
                                                      <b class="showCounterPlan"><p>Deadline Date: <span id="deadlineDatePopup"></span></b>
                                                      
                                                       
                                                        
                                                          <b class="showLumSum"><p>Lum Sum in the amount of <span id="aacalumbSumAmtPopup"></span></b>
                                                          <b class="showLumSum"><p>paid in full on <span id="aacapaidFullPopup"></span></b>
                                                        
                                                      <p>
                                                     

                                                      <div class="sections" style="padding-top: 20px;">
                                                      <p>Total Repayment Amount: <span id="aacatotalRePmtPopup"></span>
                                                        <p><span id="balanceType01"></span>: <span id="totalBalancePopup1" ></span></p> 
                                                
                                                <p>Percentage of Offer: <span id="aacapercntOffer"></span></p>
                                                      </div>

                                                      <p style="font-weight: 500;font-size: 15px;" id="innerTextData"></p>
                                                      
                                                   </div>
                                               </center>
                                                </div>
                                                <center>
                                                   <div class="modal-footer txt_cntr p20_0">
                                                      <button type="button" class="btn btn2 btn-sub" id="btnSubmitAaca" style="padding: 6px 62px;">Yes, Submit</button>
                                                      <button type="button" class="btn btn1 btn-sub" data-dismiss="modal">Return To Form</button>
                                                      <!----<button type="button" class="btn btn btn-sub" onclick="printJS('printJS-form', 'html')">Print</button>---->
                                                      
                                                   </div>
                                                </center>
                                             </div>

                                          </div>
                                       </div>

                                       <!-- END Submit AACA Settlement Offer Modal -->
                                    <?php } ?>
                                    <?php if($_SESSION['userType']==1){
                                       $tabclient="disabled";
                                       }else if($_SESSION['userType']==2){
                                       $tabclient="disabled";
                                       }else if($_SESSION['userType']==3){
                                       $tabclient="enabled";
                                       }?>
                                    <div class="tab-pane <?php echo $activeStatusClient; ?>" id="Guide05">
                                       <form action="#" id="TabFirstForm" novalidate="novalidate">
                                          <div class="col-sm-11 mrg30B mt50 solid2">
                                             <div class="row">
                                                <div class="col-sm-12 col-md-6 p0">
                                                   <div class="row">
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Reviewer Name: <span class="mandatory">*</span></label>
                                                            </div>
                                                            <input type="text" class="form-control" id="textName2" name="Name" placeholder="Reviewer Name" value="<?php if($_SESSION['userType'] == 3){ echo $fetchres3['fullName'].' '.$fetchres3['LastName']; } else { echo $fetchres['CLNTREVNAM'];}?>" disabled>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Additional Email Recipient:</label>
                                                            </div>
                                                            <input type="text" class="form-control" id="ReviewerEmail2" name="Reviewer Email" placeholder="Additional Email Recipient" value="<?php if($_SESSION['userType'] == 3){ echo $fetchres3['email']; } else { echo $fetchres['CLNTEMAIL']; }?>" disabled>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-sm-12 col-md-6" style="display:none;">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Verify Email Recipient: <span class="mandatory">*</span></label>
                                                            </div>
                                                            <input type="text" class="form-control" id="textPhone4" name="Verify Email" placeholder="Verify Email Recipient" value="<?php if($_SESSION['userType'] == 3){ echo $fetchres3['email']; } else { echo $fetchres['CLNTEMAIL']; }?>" <?php echo $tabclient; ?>>

                                                            <span class="error" id="verifyEmail2Error"></span>
                                                         </div>
                                                      </div>
                                                      <div class="col-sm-12 col-md-6">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12">Responses:</label>
                                                            </div>
                                                           <?php if($currentStatus == 2){ ?>
                                                            <p class="mb0"><input type="checkbox" class="cbxClient" id="CLNTACCEPT" value="True" <?php echo ($fetchres['CLNTACCEPT']=='True')?'checked':'' ?> <?php echo $tabclient; ?>> Accept Offer </p>
                                                           <?php } ?>
                                                           <?php if($currentStatus == 1){ ?>
                                                            <p class="mb0 wh_wrap"><input type="checkbox" value="True" class="cbxClient" id="CLNTACCEPT" <?php echo ($fetchres['CLNTACCEPT']=='True')?'checked':'' ?> <?php echo $tabclient; ?>> Reopen to Accept Offer </p>
                                                           <?php } ?>
                                                            <p class="mb0"><input type="checkbox" value="True" class="cbxClient" id="CLNTREJECT" <?php echo ($fetchres['CLNTREJECT']=='True')?'checked':'' ?> <?php echo $tabclient; ?>> Deny Offer </p>
                                                            <p class="mb0 wh_wrap"><input type="checkbox" id="SubmitCounterchkclient" class="cbxClient" value="True" <?php echo ($fetchres['CLNTCNTR']=='True')?'checked':'' ?> <?php echo $tabclient; ?>> Counter Offer </p>
                                                            <p class="mb0"><input type="checkbox" id="CBXCLNTINF" class="cbxClient" value="True" <?php echo ($fetchres['CBXCLNTINF']=='True')?'checked':'' ?> <?php echo $tabclient; ?>> Request More Info </p>
                                                           

                                                            <?php if(isset($fetchHardshipDoc['HARDSHIPCOPY']) && $fetchHardshipDoc['HARDSHIPCOPY'] != '' ) {
                                                              $file_path = 'http://'.$_SERVER['SERVER_NAME'].'/bi/dist/HARDSHIPDOCS/'.$FILNUM.'/'.$fetchHardshipDoc['HARDSHIPCOPY']; ?>
                                                            <p class="mb0 wh_wrap" style="padding-left: 15px;"><a href="<?php echo $file_path;?>" download>Click here to download hardship</a></p>
                                                            <?php } ?>
                                                         </div>
                                                         <span class="error " id="cbxClientError"></span>
                                                      </div>
                                                   </div>

                           
                          <div class="modal fade" id="modalDenyPopup01" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header bg-color">
                                  <h3 class="modal-title text-center" id="lineModalLabel">
                                  <b>Reason for denial</b>
                                  </h3>
                              </div>
                              <div class="modal-body clearfix" style="padding-top: 2px;">
                              <div class="row">
                                  <div class="col-md-12" style="padding: 12px 20px 0px 20px;">
                                    <span class="error" id="ClientDenyCmtError"></span>
                                 <div class="panel-group" style="margin-bottom: 0px;">
                                      <div class="panel">
                                      <textarea class="form-control" id="CLNTDENYR" placeholder="Add Additional Comments Here" rows="10" cols="50"></textarea>
                                      </div>
                                 </div>
                                 </div>
                              </div>
                      </div>
                              
                      <div class="modal-footer">
                                      <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                                          <div class="btn-group" role="group">
                                              <button type="submit" id="ClntDenysubmitInfoBtn2" name="UpdateBtn" class="btn btn-default btn-hover-green btm-right-radius" role="button">Submit</button>
                                          </div>
                                          <div class="btn-group" role="group">
                                              <button type="button" id="CloseClntDenysubmitInfoBtn2" class="btn btn-default btm-left-radius" data-dismiss="modal" role="button">Close</button>
                                          </div>
                                      </div>
                                  </div>
                          </div>
                      </div>
                  </div>

                              <div class="modal fade" id="modalPopup01" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
                      <div class="modal-dialog">
                          <div class="modal-content">
                              <div class="modal-header bg-color">
                                  <h3 class="modal-title text-center" id="lineModalLabel">
                                  <b>Request More Info</b>
                                  </h3>
                              </div>
                              <div class="modal-body clearfix" style="padding-top: 2px;">
                              <div class="row">
                                  <div class="col-md-12" style="padding: 12px 20px 0px 20px;">
                                    <span class="error" id="CLNTREASError"></span>
                                 <div class="panel-group" style="margin-bottom: 0px;">
                                      <div class="panel">
                                      <textarea class="form-control" id="CLNTREAS" placeholder="Add Additional Comments Here" rows="10" cols="50"></textarea>
                                      </div>
                                 </div>
                                 </div>
                              </div>
                      </div>
                              
                      <div class="modal-footer">
                                      <div class="btn-group btn-group-justified" role="group" aria-label="group button">
                                          <div class="btn-group" role="group">
                                              <button type="submit" id="submitInfoBtn2" name="UpdateBtn" class="btn btn-default btn-hover-green btm-right-radius" role="button">Submit</button>
                                          </div>
                                          <div class="btn-group" role="group">
                                              <button type="button" id="ClosesubmitInfoBtn2" class="btn btn-default btm-left-radius" data-dismiss="modal" role="button">Close</button>
                                          </div>
                                      </div>
                                  </div>
                          </div>
                      </div>
                  </div>

                                                   <div class="col-sm-12 col-md-12">
                                                         
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                              <div style="margin-bottom:5px;">
                                <label class="">Comments: </label>
                              </div>
                                                               <textarea maxlength="1000" rows="17" class="form-control" id="CLNTADDINF" <?php echo $tabclient; ?>><?php echo $fetchres['CLNTADDINF'] ?></textarea>
                                                            </div>
                                                         </div>
                             <div class="form-group clearfix">
                                                            <div class="row">
                                                               
                                                               <button type="button" id="Historybtn02" class="btn btn-primary pull-right" style="font-size: 11px; padding: 4px 18px;" <?php echo $tabclient;?>>
                                                               View History
                                                               </button>
                                                            </div>
                                                         </div>
                                                   </div>
                                                </div>
                                                <div class="col-sm-12 col-md-6" style="padding-left: 40px;">
                                                   <div class="row">
                                                      <div class="col-xs-4 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Client: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $fetchres1['WFORGNM'];?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Firm Name: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $fetchres1['RMSASNDE01'];?>  </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Consumer Name: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $fetchres1['WFNAME'];?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Account Number: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $fetchres1['RACTNM'];?>  </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Placement Amount: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo "$".number_format($fetchres1['ORPLAM'], 2);?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Judgment Amount: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo "$".number_format($fetchres['FRMJUGAMNT'], 2);?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Total Balance Due: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"> $<?php echo number_format($fetchres['SIFFIRMBAL'] + $fetchres['FIRMINTAMT'] + $fetchres['SIFFRMCOST'] + $fetchres['COSTPROC'] + $fetchres['ADDITCOST'], 2);?>
                                                               </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Blanket Authority: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix">
                                                            <div class="row">
                              <!--<span class="col-xs-12 text-right"><?php //echo $fetchres2['LUMPPERC'] . "%";?>  </span>-->
                                 <span class="col-xs-12 text-right"><?php echo $LUMPPERC . "%";?>  </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Gross Settlement Offer: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                             <?php
                              if($fetchres['CKBXLUMSUM'] == 'True'){
                                $grosseAmt = $fetchres['LUMSUMAMNT'];
                              }elseif($fetchres['CHKBXPAYPL'] == 'True'){
                                $grosseAmt = $fetchres['PRTAMNT'] - $fetchres['ADDEDINT'];
                              }
                             ?>

                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo '$'.number_format($grosseAmt, 2);?> </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Added Interest: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $fetchres['PPINTAMNT'].'%';?></span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Grand Total: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                             <?php
                                if($fetchres['CKBXLUMSUM'] == 'True'){
                                  $grandTotal = $fetchres['LUMSUMAMNT'];
                                }elseif($fetchres['CHKBXPAYPL'] == 'True'){
                                  $grandTotal = $fetchres['PRTAMNT'];
                                }
                              ?>
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo '$'.number_format($grandTotal, 2);?></span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Number of Payments: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                             <?php
                              if($fetchres['CKBXLUMSUM'] == 'True'){
                                $numofpayment = 1;
                              }elseif($fetchres['CHKBXPAYPL'] == 'True'){
                                $numofpayment = $fetchres['PPNUMMONTH'];
                              }
                             ?>
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $numofpayment;?>  </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Percentage of Offer: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $fetchres['PERCOFFER'] . "%";?>  </span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <div class="row">
                                                      <div class="col-xs-5 col-sm-5 col-md-5">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <label class="col-xs-12 text-right">Deadline of Acceptance: </label>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="col-xs-7 col-sm-7 col-md-7 p0">
                                                         <div class="form-group clearfix mb5">
                                                            <div class="row">
                                                               <span class="col-xs-12 text-right"><?php echo $fetchres['DOADATE'];?></span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <!----<div class="row">
                                                      <div class="col-xs-6 col-sm-6 col-md-6">
                                                          <div class="form-group clearfix">
                                                            <div class="row">
                                                                <label class="col-xs-12 text-right">Original Account Number: </label>
                                                            </div>
                                                        </div>
                                                      </div>
                                                      <div class="col-xs-6 col-sm-6 col-md-6">
                                                          <div class="form-group clearfix">
                                                            <div class="row">
                                                                <span class="col-xs-12"><?php echo $fetchres1['WFOTHACCT'];?>  </span>
                                                            </div>
                                                        </div>
                                                      </div>
                                                      </div>------->
                                                   <!-- Submit Counter Offer Client-->
                                                   <?php if($fetchres['CLNTCNTR'] == 'True'){
                                                      $clientCounterOffr = $fetchres['CLNTCNINST'];
                                                      $clientCounterOffr = substr($clientCounterOffr, 5);

                                                      $clientCounterOffr = explode(",",$clientCounterOffr);

                                                      $clientAmtOffr = $clientCounterOffr[0];
                                                      $clientAmtDown = $clientCounterOffr[1];
                                                      $clientMthlyPmt = $clientCounterOffr[2];
                                                      $clientNoOfPmt = $clientCounterOffr[3];
                                                      $clientFinalPmt = $clientCounterOffr[4];
                                                      $clientDeadlineOfAccept = $clientCounterOffr[5];
                                                      $clientFirstPymt = $clientCounterOffr[6];
                                                   } ?>
                                                   <div class="row" id="SubmitCounterclientdiv" style="display: none;">
                                                      <div class="row">
                                                         <div class="col-xs-6 col-sm-6 col-md-6" style="padding-right: 33px;">
                                                            <div class="form-group clearfix mb0">
                                                              <label class="col-xs-12 text-right mt10">Amount of CounterOffer:</label>
                                                            </div>
                                                         </div>
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                                <input type="text" maxlength="12" class="form-control text-right" id="AACACounterOfferAmount" name="AACACounterOfferAmount" placeholder="Amount of Counter Offer" value="<?php if(isset($clientAmtOffr) && !empty($clientAmtOffr)){echo $clientAmtOffr;}else{ echo '$0.00'; }?>" onchange="calculateTotalBal();">
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="row">
                                                         <div class="col-xs-6 col-sm-6 col-md-6" style="padding-right: 33px;">
                                                            <div class="form-group clearfix mb0">
                                                               <label class="col-xs-12 text-right mt10">Initial Payment Amount::</label>
                                                            </div>
                                                         </div>
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <input type="text" maxlength="12" class="form-control text-right" id="AACACounterOfferAmountDown" name="AACACounterOfferAmountDown"placeholder="Initial payment amount" value="<?php if(isset($clientAmtDown) && !empty($clientAmtDown)){echo $clientAmtDown;}else{ echo '$0.00'; }?>" onchange="calculateTotalBal();">
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="row">
                                                         <div class="col-xs-6 col-sm-6 col-md-6" style="padding-right: 33px;">
                                                            <div class="form-group clearfix mb0">
                                                               <label class="col-xs-12 text-right mt10">Monthly Payment Amount: </label>
                                                            </div>
                                                         </div>
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <input type="text" class="form-control text-right" maxlength="12" id="AACACounterOfferMonthlyPayment" name="AACACounterOfferMonthlyPayment" placeholder="Monthly Payment Amount" value="<?php if(isset($clientMthlyPmt) && !empty($clientMthlyPmt)){echo $clientMthlyPmt;}else{ echo '$0.00'; }?>" onchange="calculateTotalBal();">
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="row">
                                                         <div class="col-xs-6 col-sm-6 col-md-6" style="padding-right: 33px;">
                                                            <div class="form-group clearfix mb0">
                                                               <label class="col-xs-12 text-right mt10">Number of Payments: </label>
                                                            </div>
                                                         </div>
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <input type="text" class="form-control text-right" id="AACACounterOfferNumberOfPayments" name="AACACounterOfferNumberOfPayments" maxlength="3" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" onkeypress="return validateFloatKeyPress(event);" placeholder="Number of Payments" value="<?php if(isset($clientNoOfPmt) && !empty($clientNoOfPmt)){echo $clientNoOfPmt;}else{ echo '0'; }?>" onchange="calculateTotalBal();">
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="row">
                                                         <div class="col-xs-6 col-sm-6 col-md-6" style="padding-right: 33px;">
                                                            <div class="form-group clearfix mb0">
                                                               <label class="col-xs-12 text-right mt10">Final Payment Amount: </label>
                                                            </div>
                                                         </div>
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <input type="text" class="form-control text-right" maxlength="12" id="AACACounterOfferFinalPayment" name="AACACounterOfferFinalPayment" placeholder="Final Payment Amount" value="<?php if(isset($clientFinalPmt) && !empty($clientFinalPmt)){echo $clientFinalPmt;}else{ echo '$0.00'; }?>" onchange="calculateTotalBal();">
                                                               <span class="error " id="AACACounterOfferFinalPaymentError"></span>
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="row">
                                                         <div class="col-xs-6 col-sm-6 col-md-6" style="padding-right: 33px;">
                                                            <div class="form-group clearfix mb0">
                                                               <label class="col-xs-12 text-right mt10">Deadline of Acceptance: </label>
                                                            </div>
                                                         </div>
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <input type="text" class="form-control text-right" id="AACACounterOfferDeadlineDate" name="AACACounterOfferDeadlineDate" onkeydown="return false" placeholder="Deadline of Acceptance" value="<?php if(isset($clientDeadlineOfAccept) && !empty($clientDeadlineOfAccept)){echo $clientDeadlineOfAccept;} ?>">
                                                            </div>
                                                         </div>
                                                      </div>
                                                      <div class="row">
                                                         <div class="col-xs-6 col-sm-6 col-md-6" style="padding-right: 33px;">
                                                            <div class="form-group clearfix mb0">
                                                               <label class="col-xs-12 text-right mt10">First Payment Due: </label>
                                                            </div>
                                                         </div>
                                                         <div class="col-xs-6 col-sm-6 col-md-6">
                                                            <div class="form-group clearfix mb0">
                                                               <input type="text" class="form-control text-right" id="AACACounterOfferFirstPaymentDate" name="AACACounterOfferFirstPaymentDate" onkeydown="return false" value="<?php if(isset($clientFirstPymt) && !empty($clientFirstPymt)){echo $clientFirstPymt;} ?>" >
                                                            </div>
                                                         </div>
                                                      </div>
                                                      
                                                      
                                                      <!----<div class="col-md-12 p0">
                                                         <div class="col-md-6 col-md-offset-6 col-sm-6 col-sm-offset-6 clearfix">
                                                         <div class="col-md-6"><button type="submit" id="btnSubmit" class="btn btn-primary btn-xs-100 mrg0 mrg20R submit" name="calcbtn">Calculate</button></div>
                                                         <div class="col-md-6"><button type="reset" id="btnReset" class="btn btn-warning btn-xs-100 mrg0">Clear</button></div>
                                                         </div>
                                                      </div>---->
                                                        <!-- <div class="col-md-12 p0" style="margin-top: 15px;">
                                                         <div class="col-md-6 col-md-offset-6 col-sm-6 col-sm-offset-6 clearfix p0">
                                                             <div class="col-md-6 pr0"><button type="submit" id="btnSubmit03" class="col-md-12 col-sm-6 btn btn-primary btn-xs-100 mrg0 submit" name="calcbtn" style="padding:6px 0px">Calculate</button></div>
                                                             <div class="col-md-6 pr0"><button type="submit" id="btnReset02" class="col-md-12 col-sm-6 btn btn-warning btn-xs-100 mrg0" style="padding:6px 0px">Clear</button></div>
                                                             <span class="error col-md-12" id="warningErr02"></span>
                                                         </div>
                                                      </div> -->
                                                      <div class="col-md-6 col-md-offset-6 col-sm-6 col-sm-offset-6 clearfix p0">
                                                             <div class="col-md-6 pr0"><button type="submit" id="btnSubmit03" class="col-md-12 col-sm-6 btn btn-primary btn-xs-100 mrg0 submit" name="calcbtn" style="padding:6px 0px">Calculate</button></div>
                                                             <div class="col-md-6 pr0"><button type="submit" id="btnReset01" class="col-md-12 col-sm-6 btn btn-warning btn-xs-100 mrg0" style="padding:6px 0px">Clear</button></div>

                                                             <span class="error col-md-12" id="warningErr"></span>
                                                         </div>
                                                   </div>



                                                </div>
                                                <div class="col-xs-12 col-md-12 col-sm-12 btn-sec">
                                                   <div class="col-xs-12 col-sm-4">
                                                      <a class="btn btn-next btnPrevious pull-left" >Previous</a>
                                                   </div>
                                                   <div class="col-xs-12 col-sm-4 text-center">
                                                      <button type="submit" id="btnSubmitClient" class="btn btn-primary btn-xs-100 mrg0 mrg20R submit" <?php echo $tabclient;?>>Submit Reply</button>
                                                      <button type="button" id="btnCancel" onclick="CancelEdit();" class="btn btn-primary btn-xs-100 mrg0 mrg20R" style="display:none;">Cancel</button>
                                                   </div>
                                                   <!-- <div class="col-xs-12 col-sm-4">
                                                      <a class="btn btn-next btnNext pull-right">Next <i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                                                      </div> -->
                                                </div>
                                             </div>
                                          </div>
                                       </form>
                                    </div>
                                    <div class="tab-pane" id="Guide06">
                                      <?php 
                                      while($resultaddinfodoc=mysqli_fetch_assoc($resultaddinfo)){
                                      $documentname=$resultaddinfodoc['Additionalinfodocuments'];
                                      $doc_arr1 = explode (",", $documentname);
                                      $doc_arr=array_filter(array_map("trim",$doc_arr1));
                                      $counttercount=0;
                                      foreach ($doc_arr as $value) { 
                                        $counttercount++;
                                        $DocPath='../Mako/MYUPLOADS/STLMNTS/STLMNT_DOC/'.$value;
                                        ?>
                                       
                                         <a class="DocPath" title='<?php echo $value;?>' data-id="<?php echo  $DocPath?>" href="#" ><?php echo $value;?></a></br>

                                          
                                       <?php  } } ?>
                                   
                                     </div>
                                    <!-- Submit Client Settlement Offer Modal -->
                                    <div class="modal fade" id="myClientModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static">
                                          <div class="modal-dialog modal-md">
                                          
                                          <i class="fa fa-close close" data-dismiss="modal"></i>
                                             <div class="modal-content" style="padding: 25px 15px 15px 15px;">
                                            <div id="printJS-form">
                                             <div class="modal-title">
                                                      <div class="col-md-4 col-sm-4">
                                                      <img src="../img/aaca-net.png" id="Image2" style="width:124px;">
                                                      </div>

                                                      <div class="col-md-4 col-sm-4">
                                                          <center><h4><b>Settlement Confirmation</b></h4></center>
                                                      </div>
                                             </div>

                                                <center>
                                                  
                                                   <div class="modal-header pt30">
                                                
                                                      <div class="col-md-6 col-sm-6" style="padding-top: 20px;">
                                                         <p>Date: <?php echo date('m/d/Y');?></p>
                                                         <p>Client :<?php echo $fetchres1['WFORGNM'];?></p>
                                                         <p>Firm : <?php echo $fetchres1['RMSASNDE01'];?> </p>
                                                      </div>
                                                      <div class="col-md-6 col-sm-6" style="padding-top: 20px;">
                                                         <p>Debtor: <?php echo $fetchres1['WFNAME'];?></p>
                                                         <p>Account No: <?php echo $fetchres1['RACTNM'];?></p>
                                                         <p>Firm File No: <?php echo $fetchres1['WFFIRMFILE'];?></p>
                                                      </div>
                                                   </div>

                                                   <div class="modal-body sett_off p30_0">
                                                  
                                                      <h3 class="showPaymentPlan"><b>SETTLEMENT OFFER</b></h3>
                                                        
                                                      <b class="showPaymentPlan"><p>Payment Plan in the Amount of <span id="pymtPlnClntPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Initial Payment Date: <span id="intPmtDateClntPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Monthly Payment: <span id="mthlyPmtClntPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Final Payment Date: <span id="finalPmtDateClntPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Total Number of Payments: <span id="totlNoPymtClntPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Down Payment Amount: <span id="dwnPmtClntPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Final Payment Amount: <span id="finlPmtClntPopup"></span></b>
                                                      <b class="showPaymentPlan"><p>Added interest: <span id="addedIntrClntPopup"></span></b>


                                                      <h3 class="showClntCounterPlan"><b>COUNTER OFFER</b></h3>
                                                        
                                                      <b class="showClntCounterPlan"><p>Amount of Offer: <span id="conterOfferClntPopup"></span></b>
                                                      <b class="showClntCounterPlan"><p>Initial payment amount: <span id="amountDownClntPopup"></span></b>
                                                      <b class="showClntCounterPlan"><p>Monthly Payment: <span id="monthlyPaymentClntPopup"></span></b>
                                                      <b class="showClntCounterPlan"><p>Number of Payments: <span id="noOfPmtClntPopup"></span></b>
                                                      <b class="showClntCounterPlan"><p>Final Payment: <span id="finalPmtClntPopup"></span></b>
                                                      <b class="showClntCounterPlan"><p>First Payment Date: <span id="firstPymtClntPopup"></span></b>
                                                      <b class="showClntCounterPlan"><p>Deadline Date: <span id="deadlineDateClntPopup"></span></b>
                                                      
                                                       
                                                        
                                                          <b class="showLumSum"><p>Lum Sum in the amount of <span id="clientlumbSumAmtPopup"></span></b>
                                                          <b class="showLumSum"><p>paid in full on <span id="clientpaidFullPopup"></span></b>
                                                        
                                                      <p>
                                                     

                                                      <div class="sections" style="padding-top: 20px;">
                                                      <p>Total Repayment Amount: <span id="clienttotalRePmtPopup"></span>
                                                <p><span id="balanceType02"></span>: <span id="totalBalancePopup2" ></span> </p>
                                                <p>Percentage of Offer: <span id="clientpercntOffer"></span></p>
                                                      </div>

                                                      <p style="font-weight: 500;font-size: 15px;" id="innerTextDataClnt"></p>
                                                      
                                                   </div>
                                               </center>
                                                </div>
                                                 
                                                <center>
                                                   <div class="modal-footer txt_cntr p20_0">
                                                      <button type="button" class="btn btn2 btn-sub" id="btnSubmitClient01" style="padding: 6px 62px;">Yes, Submit</button>
                                                      <button type="button" class="btn btn1 btn-sub" data-dismiss="modal">Return To Form</button>
                                                      <!-- <button type="button" class="btn btn btn-sub" onclick="printJS('printJS-form', 'html')">Print</button> -->
                                                      
                                                   </div>
                                                </center>
                                             </div>

                                          </div>
                                       </div>

                                       <!-- END Submit Client Settlement Offer Modal -->
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
         </div>
         <div class="control-sidebar-bg"></div>
      </div>
           <!-- History Modal Start -->
      <div class="modal fade" id="offerHistoryModal" role="dialog">
                                          <div class="modal-dialog modal-md">
                                             <div class="modal-content">
                                                <center>
                                                   <div class="col-md-12 p0 pr5">
                                                      <!-- <button type="button" class="close close_top" data-dismiss="modal">&times;</button> -->
                                                       <button style="padding-right: 20px;
                                                        padding-top: 10px;" type="button" class="close close_top" data-dismiss="modal">&times;</button>

                                                     <?php if(mysqli_num_rows($result01)==0) {
                                                     }
                                                     else {?>
                                                      <div align="right"><button style="margin-top: 14px; padding: 5px 12px 5px 12px;
    margin-right: 10px;" type="button" class="btn btn btn-sub" onclick="printJS('printJS-form1', 'html')">Print</button></div>
                                                  <!--    <button class="float_r print" type="button">print</button> -->
                                                   <?php } ?>

                                                   </div>
                                                     <div id="printJS-form1">
                                                      <center>

                                                        <div class="col-md-12">

                                                           <?php if(mysqli_num_rows($result01)==0) {?>
                                                              <h4><b>No History Found</b> </h4><br>
                                                                 <?php } else{?>
                                                             <h4><b>History</b> </h4>

                                                           <?php }?>
                                                         </div>
 
                                                   <?php
                                                    while($fetchres01=mysqli_fetch_assoc($result01)){
                                                    if ($fetchres01['AACAACCEPT']=='True' || $fetchres01['AACAREJECT']=='True' || $fetchres01['AACACNTR']=='True'|| $fetchres01['AACAADDINF']=='True' ){ ?>

                                                         <!-- AACA start -->
                                                          <div class="col-md-12 head_top">

                                                     <!--<p><b>Date: <?php // echo date("m/d/Y", strtotime($fetchres01['LSTUPDATE']));?></b></p> -->
                                                    <p><b>Debtor: <?php echo $fetchres01['WFNAME'];?></b></p>
                                                    <p><b>Account No: <?php echo $fetchres01['RACTNM'];?></b></p>
                                                      </div>
                                                    <div class="modal-body col-md-12  sett_off p30_0">
                                                    <h5><b>AACA SETTLEMENT UPDATE</b> </h5>
                                                    <?php if ($fetchres01['AACAACCEPT']=='True') {?>
                                                    <p> AACA Action: Accepted </p>
                                                     <?php }
                                                     else if ($fetchres01['AACAREJECT']=='True') {?>
                                                     <p> AACA Action: Rejected </p>
                                                     <?php }
                                                     else if ($fetchres01['AACACNTR']=='True') {?>
                                                     <p> AACA Action: Countered </p>
                                                     <?php }
                                                     else if ($fetchres01['AACAADDINF']=='True') {?>
                                                     <p> AACA Action: Request More Info </p>
                                                     <?php }
                                                     ?>

                                                     <p><b>Submission Response: <?php echo $fetchres01['RACTST'] .' '. $fetchres01['SYSDESC']; ?></b></p>
                                                      <!-- <p><b> <?php //echo $fetchres01['AACADATE'];?> </b> </p> -->
                                                      <p><b> Submission Date: <?php echo date("m/d/Y h:i:s A", strtotime($fetchres01['LSTUPDATE']));?></b></p>
                                                      <p>Submit By: <?php echo $fetchres01['SIFFCNAME'];?> </p>
                                                      </br>
                                                      <p>AACA Reviewer: <b><?php echo $fetchres01['AACAREVNAM'];?> </b></p>
                                                      <p>AACA Email Address: <b><?php echo $fetchres01['AACAEMAIL'];?> </b></p>
                                                   </div>


                                                    <?php if ($fetchres01['AACACNTR']=='True') {?>

                                                   <div class="modal-content box col-md-12 sett_off p30_0">
                                                      <p><b>CounterOffer:</b></p>
                                                     
                                                      <?php if($fetchres01['AACACNTR'] == 'True'){
                                                      $aacaCounterOffr = $fetchres01['AACACNTOFF'];
                                                      $aacaCounterOffr = substr($aacaCounterOffr, 5);
                                                      $aacaCounterOffr = explode(",",$aacaCounterOffr);

                                                      $amtOffr = $aacaCounterOffr[0];
                                                      $amtDown = $aacaCounterOffr[1];
                                                      $mthlyPmt = $aacaCounterOffr[2];
                                                      $noOfPmt = $aacaCounterOffr[3];
                                                      $finalPmt = $aacaCounterOffr[4];
                                                      $deadlineOfAccept = $aacaCounterOffr[5];
                                                      $firstPymt = $aacaCounterOffr[6];
                                                   } ?>
                                                      <p>Amount of Offer: <?php echo $amtOffr;?> </p>
                                                      <p>Initial payment amount: <?php echo $amtDown?> </p>
                                                      <p>Monthly Payment: <?php echo $mthlyPmt;?> </p>
                                                      <p>Number of Payments: <?php echo $noOfPmt;?> </p>
                                                      <p>Final Payment: <?php echo $finalPmt;?> </p>
                                                      <p>First Payment Date: <?php echo $firstPymt;?> </p>
                                                      <p>Deadline Date: <?php echo $deadlineOfAccept;?> </p>
                                                      <p><b>AACA Comments: </b><?php echo $fetchres01['AACAADDCOM'] ?></p>
                                                      <?php if($fetchres01['CounterAceeptReject']==1){
                                                        $action="Accepted";?>
                                                        <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==2){
                                                        $action="Rejected";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==4){
                                                        $action="Expired";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }?>
                                                      
                                                      <!-- <p>AACA Add Info Reason: <?php //echo $fetchres01['ADDINFREAS'] ?></p> -->
                                                      <br>
                                                   </div>

                                                 <?php }
                                                   else if ($fetchres01['AACAADDINF']=='True') {
                                                     if ($fetchres01['CKBXLUMSUM']=='True' ) {
                                                      //   echo "LUMb sum testing";
                                                      // }
                                                       ?>
                                                       <div class="modal-content box col-md-12 sett_off p30_0">
                                                     <p><b>Payment Details:</b></br> </p>
                           <?php
                              if($fetchres01['AUTOACCEPT'] == 'Y'){
                            ?>
                              <p>Settlement Response: Auto Accepted</p>
                            <?php   
                              }
                            ?>
                                                     <p>Deadline of Acceptance: <?php echo $fetchres01['DOADATE'];?> </p>
                                                     
                                                     <?php
                                                      if ($fetchres01['SPR_BALTYPE']=='prin') {?>
                                                     <p>Firm Principal Bal: $<?php echo $fetchres01['SIFFIRMBAL'];?> </p>
                                                      <?PHP }
                                                      else{ ?>
                                                            <p>Total Balance Due: $<?php echo $fetchres01['TOTBALDUE'];?> </p>
                                                      <?php }
                                                      ?>
                                                     <p>Lump sum Amount: <?php echo "$" . $fetchres01['LUMSUMAMNT'];?> </p>
                                                     <p>Percentage of Offer: <?php echo $fetchres01['PERCOFFER'].'%'; ?></p>

                                                     <p>Lump sum Payment Date: <?php echo date("m/d/Y", strtotime($fetchres01['LUMPAYDATE']));?> </p>
                                                    <!--       <p>Firm Notes:<b> <?php //echo $fetchres01['PAYPLANDET'] ?> </b> </p><br> -->
                                                     <p>AACA Comments: <b><?php echo $fetchres01['AACAADDCOM'] ?> </b></p>
                                                       <?php if($fetchres01['CounterAceeptReject']==1){
                                                        $action="Accepted";?>
                                                        <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==2){
                                                        $action="Rejected";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==4){
                                                        $action="Expired";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }?>
                                                     <p><b>AACA Add Info Reason:</b> <?php echo $fetchres01['ADDINFREAS'] ?></p>
                                                     <p><b>Response from Firm:</b> <?php echo $fetchres01['Additionalinfocomments'] ?></p>
                                                     <?php if($fetchres01['Additionalinfo']==1){
                                                        $action="Provided";?>
                                                       <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['Additionalinfo']==2){
                                                        $action="Unavailable";?>
                                                         <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['Additionalinfo']==3){
                                                        $action="Need More Info";?>
                                                        <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['Additionalinfo']==4){
                                                        $action="Expired";?>
                                                        <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }?>
                                                       <p><b>Percentage of Offer:</b><?php echo $fetchres01['PERCOFFER'].'%'; ?></p>      
                                                     <br>
                                                     </div>
                                                     <?php  }?>
                                                      <?php
                                                       if ($fetchres01['CHKBXPAYPL']=='True') {
                                                      ?>
                                                   <div class="modal-content box col-md-12 sett_off p30_0">
                                                      <p><b>Payment Details:</b></br> </p>
                            <?php
                              if($fetchres01['AUTOACCEPT'] == 'Y'){
                            ?>
                              <p>Settlement Response: Auto Accepted</p>
                            <?php   
                              }
                            ?>
                                                      <p>Deadline of Acceptance: <?php echo $fetchres01['DOADATE'];?> </p>
                                                      <?php
                                                      if ($fetchres01['SPR_BALTYPE']=='prin') {?>
                                                     <p>Firm Principal Bal: $<?php echo $fetchres01['SIFFIRMBAL'];?> </p>
                                                      <?PHP }
                                                      else{ ?>
                                                            <p>Total Balance Due: $<?php echo $fetchres01['TOTBALDUE'];?> </p>
                                                      <?php }
                                                      ?>
                                                      <p>Total Amount of offer: <?php echo "$" .$fetchres01['PPTOTDUE'] ?> </p>
                                                      <p>Monthly Payment: <?php echo "$".$fetchres01['PPMTHPYMT'] ?> </p>
                                                      <p>Number of Installments: <?php echo $fetchres01['PPNUMMONTH'] ?> </p>
                                                      <p>Interest Rate: <?php echo $fetchres01['PPINTAMNT'] ?> </p>
                                                      <p>Initial Payment Amount: <?php echo "$" . $fetchres01['PPFSTPAYAM'] ?> </p>
                                                      <p>Initial Installment Date: <?php echo date("m/d/Y", strtotime($fetchres01['FIRSTPAYDT']));?></p>
                                                      <p>Final Payment Amount:<b> <?php echo "$".$fetchres01['PPLSTPAYAM'] ?> </b></p>
                                                      <p>Final Payment Date:<b> <?php echo date("m/d/Y ", strtotime($fetchres01['LASTPAYDT'])); ?> </b> </p>
                                                      <p>Added Interest: <?php echo $fetchres01['ADDEDINT'] ?> </p>
                                                      <p>Grand Total: <?php echo "$" .$fetchres01['PRTAMNT'] ?> </p>
                                                      <p>Percentage of Offer: <?php echo $fetchres01['PERCOFFER'].'%'; ?></p>

                                                      <p>AACA Comments: <b><?php echo $fetchres01['AACAADDCOM'] ?> </b></p>
                                                       <?php if($fetchres01['CounterAceeptReject']==1){
                                                        $action="Accepted";?>
                                                        <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==2){
                                                        $action="Rejected";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==4){
                                                        $action="Expired";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }?>
                                                       <p><b>AACA Add Info Reason:</b> <?php echo $fetchres01['ADDINFREAS'] ?></p>
                                                        <p><b>Response from Firm:</b> <?php echo $fetchres01['Additionalinfocomments'] ?></p>
                                                               <?php if($fetchres01['Additionalinfo']==1){
                                                        $action="Provided";?>
                                                       <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['Additionalinfo']==2){
                                                        $action="Unavailable";?>
                                                         <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['Additionalinfo']==3){
                                                        $action="Need More Info";?>
                                                        <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['Additionalinfo']==4){
                                                        $action="Expired";?>
                                                        <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }?>
                            <p><b>Percentage of Offer:</b><?php echo $fetchres01['PERCOFFER'].'%'; ?></p>
                                                     <br>
                                                     </div>
                                                 <?php }
                                                  ?>
        
                                                   <?php }
                                                    else if ($fetchres01['AACAACCEPT']=='True') {
                                                       if ($fetchres01['CKBXLUMSUM']=='True' ) {
                                                      //   echo "LUMb sum testing";
                                                      // }
                                                       ?>
                                                       <div class="modal-content box col-md-12 sett_off p30_0">
                                                     <p><b>Payment Details:</b></br> </p>
                           <?php
                              if($fetchres01['AUTOACCEPT'] == 'Y'){
                            ?>
                              <p>Settlement Response: Auto Accepted</p>
                            <?php   
                              }
                            ?>
                                                     <p>Deadline of Acceptance: <?php echo $fetchres01['DOADATE'];?> </p>
                                                     <?php
                                                      if ($fetchres01['SPR_BALTYPE']=='prin') {?>
                                                     <p>Firm Principal Bal: $<?php echo $fetchres01['SIFFIRMBAL'];?> </p>
                                                      <?PHP }
                                                      else{ ?>
                                                      <p>Total Balance Due: $<?php echo $fetchres01['TOTBALDUE'];?> </p>
                                                      <?php }
                                                      ?>
                                                     <p>Lump sum Amount: <?php echo "$" . $fetchres01['LUMSUMAMNT'];?> </p>
                                                     <p>Percentage of Offer: <?php echo $fetchres01['PERCOFFER'].'%'; ?></p>

                                                     <p>Lump sum Payment Date: <?php echo date("m/d/Y", strtotime($fetchres01['LUMPAYDATE']));?> </p>
                                                    <!--       <p>Firm Notes:<b> <?php //echo $fetchres01['PAYPLANDET'] ?> </b> </p><br> -->
                                                     <p><b>AACA Comments: </b><?php echo $fetchres01['AACAADDCOM'] ?></p>
                                                       <?php if($fetchres01['CounterAceeptReject']==1){
                                                        $action="Accepted";?>
                                                        <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==2){
                                                        $action="Rejected";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==4){
                                                        $action="Expired";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }?>

                                                     <br>
                                                     </div>
                                                     <?php  }?>

                                                     <?php
                                                       if ($fetchres01['CHKBXPAYPL']=='True') {
                                                      ?>
                                                   <div class="modal-content box col-md-12 sett_off p30_0">
                                                      <p><b>Payment Details:</b></br> </p>
                            <?php
                              if($fetchres01['AUTOACCEPT'] == 'Y'){
                            ?>
                              <p>Settlement Response: Auto Accepted</p>
                            <?php   
                              }
                            ?>
                                                      <p>Deadline of Acceptance: <?php echo $fetchres01['DOADATE'];?> </p>
                                                      <?php
                                                      if ($fetchres01['SPR_BALTYPE']=='prin') {?>
                                                     <p>Firm Principal Bal: $<?php echo $fetchres01['SIFFIRMBAL'];?> </p>
                                                      <?PHP }
                                                      else{ ?>
                                                            <p>Total Balance Due: $<?php echo $fetchres01['TOTBALDUE'];?> </p>
                                                      <?php }
                                                      ?>
                                                      <p>Total Amount of offer: <?php echo "$" .$fetchres01['PPTOTDUE'] ?> </p>
                                                      <p>Monthly Payment: <?php echo "$".$fetchres01['PPMTHPYMT'] ?> </p>
                                                      <p>Number of Installments: <?php echo $fetchres01['PPNUMMONTH'] ?> </p>
                                                      <p>Interest Rate: <?php echo $fetchres01['PPINTAMNT'] ?> </p>
                                                      <p>Initial Payment Amount: <?php echo "$" . $fetchres01['PPFSTPAYAM'] ?> </p>
                                                      <p>Initial Installment Date: <?php echo date("m/d/Y", strtotime($fetchres01['FIRSTPAYDT']));?></p>
                                                      <p>Final Payment Amount:<b> <?php echo "$".$fetchres01['PPLSTPAYAM'] ?> </b></p>
                                                      <p>Final Payment Date:<b> <?php echo date("m/d/Y ", strtotime($fetchres01['LASTPAYDT'])); ?> </b> </p>
                                                      <p>Added Interest: <?php echo $fetchres01['ADDEDINT'] ?> </p>
                                                      <p>Grand Total: <?php echo "$" .$fetchres01['PRTAMNT'] ?> </p>
                                                       <p>Percentage of Offer: <?php echo $fetchres01['PERCOFFER'].'%'; ?></p>

                                                      <p><b>AACA Comments: </b><?php echo $fetchres01['AACAADDCOM'] ?></p>
                                                       <?php if($fetchres01['CounterAceeptReject']==1){
                                                        $action="Accepted";?>
                                                        <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==2){
                                                        $action="Rejected";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==4){
                                                        $action="Expired";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }?>
                                                     <br>
                                                     </div>
                                                 <?php }
                                                  ?>


                                                   <?php }
                                                     else if ($fetchres01['AACAREJECT']=='True') {
                                                       if ($fetchres01['CKBXLUMSUM']=='True' ) {
                                                      //   echo "LUMb sum testing";
                                                      // }
                                                       ?>
                                                       <div class="modal-content box col-md-12 sett_off p30_0">
                                                     <p><b>Payment Details:</b></br> </p>
                           <?php
                              if($fetchres01['AUTOACCEPT'] == 'Y'){
                            ?>
                              <p>Settlement Response: Auto Accepted</p>
                            <?php   
                              }
                            ?>
                                                     <p>Deadline of Acceptance: <?php echo $fetchres01['DOADATE'];?> </p>
                                                    <?php
                                                      if ($fetchres01['SPR_BALTYPE']=='prin') {?>
                                                     <p>Firm Principal Bal: $<?php echo $fetchres01['SIFFIRMBAL'];?> </p>
                                                      <?PHP }
                                                      else{ ?>
                                                            <p>Total Balance Due: $<?php echo $fetchres01['TOTBALDUE'];?> </p>
                                                      <?php }
                                                      ?>
                                                     <p>Lump sum Amount: <?php echo "$" . $fetchres01['LUMSUMAMNT'];?> </p>
                                                     <p>Percentage of Offer: <?php echo $fetchres01['PERCOFFER'].'%'; ?></p>

                                                     <p>Lump sum Payment Date: <?php echo date("m/d/Y", strtotime($fetchres01['LUMPAYDATE']));?> </p>
                                                    <!--       <p>Firm Notes:<b> <?php //echo $fetchres01['PAYPLANDET'] ?> </b> </p><br> -->
                                                     <p><b>AACA Comments: </b><?php echo $fetchres01['AACAADDCOM'] ?> </p>
                                                       <?php if($fetchres01['CounterAceeptReject']==1){
                                                        $action="Accepted";?>
                                                        <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==2){
                                                        $action="Rejected";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==4){
                                                        $action="Expired";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }?>
                           <p><b>AACA Add Denial Reason: </b><?php echo $fetchres01['AACADENYR'] ?> </p>

                                                     <br>
                                                     </div>
                                                     <?php  }?>
                                                        <?php
                                                       if ($fetchres01['CHKBXPAYPL']=='True') {
                                                      ?>
                                                   <div class="modal-content box col-md-12 sett_off p30_0">
                                                      <p><b>Payment Details:</b></br> </p>
                            <?php
                              if($fetchres01['AUTOACCEPT'] == 'Y'){
                            ?>
                              <p>Settlement Response: Auto Accepted</p>
                            <?php   
                              }
                            ?>
                                                      <p>Deadline of Acceptance: <?php echo $fetchres01['DOADATE'];?> </p>
                                                    <?php
                                                      if ($fetchres01['SPR_BALTYPE']=='prin') {?>
                                                     <p>Firm Principal Bal: $<?php echo $fetchres01['SIFFIRMBAL'];?> </p>
                                                      <?PHP }
                                                      else{ ?>
                                                            <p>Total Balance Due: $<?php echo $fetchres01['TOTBALDUE'];?> </p>
                                                      <?php }
                                                      ?>
                                                      <p>Total Amount of offer: <?php echo "$" .$fetchres01['PPTOTDUE'] ?> </p>
                                                      <p>Monthly Payment: <?php echo "$".$fetchres01['PPMTHPYMT'] ?> </p>
                                                      <p>Number of Installments: <?php echo $fetchres01['PPNUMMONTH'] ?> </p>
                                                      <p>Interest Rate: <?php echo $fetchres01['PPINTAMNT'] ?> </p>
                                                      <p>Initial Payment Amount: <?php echo "$" . $fetchres01['PPFSTPAYAM'] ?> </p>
                                                      <p>Initial Installment Date: <?php echo date("m/d/Y", strtotime($fetchres01['FIRSTPAYDT']));?></p>
                                                      <p>Final Payment Amount:<b> <?php echo "$".$fetchres01['PPLSTPAYAM'] ?> </b></p>
                                                      <p>Final Payment Date:<b> <?php echo date("m/d/Y ", strtotime($fetchres01['LASTPAYDT'])); ?> </b> </p>
                                                      <p>Added Interest: <?php echo $fetchres01['ADDEDINT'] ?> </p>
                                                      <p>Grand Total: <?php echo "$" .$fetchres01['PRTAMNT'] ?> </p>
                                                      <p>Percentage of Offer: <?php echo $fetchres01['PERCOFFER'].'%'; ?></p>

                                                      <p><b>AACA Comments: </b><?php echo $fetchres01['AACAADDCOM'] ?> </p>
                                                       <?php if($fetchres01['CounterAceeptReject']==1){
                                                        $action="Accepted";?>
                                                        <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==2){
                                                        $action="Rejected";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==4){
                                                        $action="Expired";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }?>
                            <p><b>AACA Add Denial Reason: </b><?php echo $fetchres01['AACADENYR'] ?> </p>
                                                     
                                                     <br>
                                                     </div>
                                                 <?php }
                                                  ?>
                                                 <?php }?>
                                                 <!--aaca end-->
                                                     <?php }

                                                     else if($fetchres01['CLNTACCEPT']=='True' || $fetchres01['CLNTREJECT']=='True' || $fetchres01['CLNTCNTR']=='True'|| $fetchres01['CBXCLNTINF']=='True' ){ ?>


                                                   
                                                   <!-- Client -->
                                                   <div class="col-md-12 head_top">

                                                         <!-- <p><b>Date: <?php //echo date("m/d/Y", strtotime($fetchres01['LSTUPDATE']));?></b></p> -->
                                                    <p><b>Debtor: <?php echo $fetchres['WFNAME'];?></b></p>
                                                    <p><b>Account No: <?php echo $fetchres['RACTNM'];?></b></p>
                                                      </div>
                                                   <div class="modal-body col-md-12  sett_off p30_0">
                                                      <h5><b>CLIENT SETTLEMENT UPDATE</b> </h5>
                                                     <?php if ($fetchres01['CLNTACCEPT']=='True') {?>
                                                        <p> Client Action: Accepted </p>
                                                     <?php }
                                                     else if ($fetchres01['CLNTREJECT']=='True') {?>
                                                     <p> Client Action: Rejected </p>
                                                     <?php }
                                                     else if ($fetchres01['CLNTCNTR']=='True') {?>
                                                     <p> Client Action: Countered </p>
                                                     <?php }
                                                     else if ($fetchres01['CBXCLNTINF']=='True') {?>
                                                     <p> Client Action: Request More Info </p>
                                                     <?php }
                                                     ?>
                                                      <p><b>Submission Response: <?php echo $fetchres01['RACTST'] .' '. $fetchres01['SYSDESC']; ?></b></p>
                                                      <!-- <p><b> <?php // echo $fetchres01['CLIENTDATE'];?> </b></p> -->
                                                      <p><b> Submission Date: <?php echo date("m/d/Y h:i:s A", strtotime($fetchres01['LSTUPDATE']));?></b></p>

                                                      <p> Submit By: <?php echo $fetchres01['SIFFCNAME'];?> </p>
                                                      </br>
                                                      <p>Client Reviewer: <b><?php echo $fetchres01['CLNTREVNAM'];?> </b></p>
                                                      <p>Client Email Address: <b><?php echo $fetchres01['CLNTEMAIL'];?> </b></p>
                                                      <!-- <p>Additional Client Notes: <?php //echo $fetchres01['CLNTADDINF'];?> </p> -->
                                                   </div>

                                                   <?php if ($fetchres01['CLNTCNTR']=='True') {?>
                                                   <div class="modal-content box col-md-12 sett_off p30_0">
                                                         <p><b>CounterOffer:</b></p>
                                                     
                                                      <?php if($fetchres01['CLNTCNTR'] == 'True'){
                                                      $clientCounterOffr = $fetchres01['CLNTCNINST'];
                                                      $clientCounterOffr = substr($clientCounterOffr, 5);

                                                      $clientCounterOffr = explode(",",$clientCounterOffr);
                                                      $clientAmtOffr = $clientCounterOffr[0];
                                                      $clientAmtDown = $clientCounterOffr[1];
                                                      $clientMthlyPmt = $clientCounterOffr[2];
                                                      $clientNoOfPmt = $clientCounterOffr[3];
                                                      $clientFinalPmt = $clientCounterOffr[4];
                                                      $clientDeadlineOfAccept = $clientCounterOffr[5];
                                                      $clientFirstPymt = $clientCounterOffr[6];
                                                   } ?>

                                                      <p>Amount of Offer: <?php echo $clientAmtOffr;?> </p>
                                                      <p>Initial payment amount: <?php echo $clientAmtDown;?> </p>
                                                      <p>Monthly Payment: <?php echo $clientMthlyPmt;?> </p>
                                                      <p>Number of Payments: <?php echo $clientNoOfPmt;?> </p>
                                                      <p>Final Payment: <?php echo $clientFinalPmt;?> </p>
                                                      <p>First Payment Date: <?php echo $clientFirstPymt;?> </p>
                                                      <p>Deadline Date: <?php echo $clientDeadlineOfAccept;?> </p>
                                                      <p><b>Client Comments:</b> <?php echo $fetchres01['CLNTADDINF']?></p>
                                                       <?php if($fetchres01['CounterAceeptReject']==1){
                                                        $action="Accepted";?>
                                                        <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==2){
                                                        $action="Rejected";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==4){
                                                        $action="Expired";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }?>
                                                      <br>
                                                   
                                                   </div>
                                                    <?php }

                                                     else if ($fetchres01['CBXCLNTINF']=='True') {
                                                        if ($fetchres01['CKBXLUMSUM']=='True' ) {
                                                      //   echo "LUMb sum testing";
                                                      // }
                                                       ?>
                                                       <div class="modal-content box col-md-12 sett_off p30_0">
                                                     <p><b>Payment Details:</b></br> </p>
                           <?php
                              if($fetchres01['AUTOACCEPT'] == 'Y'){
                            ?>
                              <p>Settlement Response: Auto Accepted</p>
                            <?php   
                              }
                            ?>
                                                     <p>Deadline of Acceptance: <?php echo $fetchres01['DOADATE'];?> </p>
                                                     <?php
                                                      if ($fetchres01['SPR_BALTYPE']=='prin') {?>
                                                     <p>Firm Principal Bal: $<?php echo $fetchres01['SIFFIRMBAL'];?> </p>
                                                      <?PHP }
                                                      else{ ?>
                                                            <p>Total Balance Due: $<?php echo $fetchres01['TOTBALDUE'];?> </p>
                                                      <?php }
                                                      ?>
                                                     <p>Lump sum Amount: <?php echo "$" . $fetchres01['LUMSUMAMNT'];?> </p>
                                                     <p>Percentage of Offer: <?php echo $fetchres01['PERCOFFER'].'%'; ?></p>

                                                     <p>Lump sum Payment Date: <?php echo date("m/d/Y", strtotime($fetchres01['LUMPAYDATE']));?> </p>
                                                    <!--       <p>Firm Notes:<b> <?php //echo $fetchres01['PAYPLANDET'] ?> </b> </p><br> -->
                                                     <p><b>Client Comments:</b> <?php echo $fetchres01['CLNTADDINF']?></p>
                                                     <?php if($fetchres01['CounterAceeptReject']==1){
                                                        $action="Accepted";?>
                                                        <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==2){
                                                        $action="Rejected";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==4){
                                                        $action="Expired";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }?>
                                                     <p><b>Client Add Info Reason:</b> <?php echo $fetchres01['CLNTREAS'] ?></p>
                                                      <p><b>Response from Firm:</b> <?php echo $fetchres01['Additionalinfocomments'] ?></p>
                                                            <?php if($fetchres01['Additionalinfo']==1){
                                                        $action="Provided";?>
                                                       <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['Additionalinfo']==2){
                                                        $action="Unavailable";?>
                                                         <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['Additionalinfo']==3){
                                                        $action="Need More Info";?>
                                                        <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }?>

                                                     <br>
                                                     </div>
                                                     <?php  }?>

                                                       <?php
                                                       if ($fetchres01['CHKBXPAYPL']=='True') {
                                                      ?>
                                                   <div class="modal-content box col-md-12 sett_off p30_0">
                                                      <p><b>Payment Details:</b></br> </p>
                            <?php
                              if($fetchres01['AUTOACCEPT'] == 'Y'){
                            ?>
                              <p>Settlement Response: Auto Accepted</p>
                            <?php   
                              }
                            ?>
                                                      <p>Deadline of Acceptance: <?php echo $fetchres01['DOADATE'];?> </p>
                                                      <?php
                                                      if ($fetchres01['SPR_BALTYPE']=='prin') {?>
                                                     <p>Firm Principal Bal: $<?php echo $fetchres01['SIFFIRMBAL'];?> </p>
                                                      <?PHP }
                                                      else{ ?>
                                                            <p>Total Balance Due: $<?php echo $fetchres01['TOTBALDUE'];?> </p>
                                                      <?php }
                                                      ?>
                                                      <p>Total Amount of offer: <?php echo "$" .$fetchres01['PPTOTDUE'] ?> </p>
                                                      <p>Monthly Payment: <?php echo "$".$fetchres01['PPMTHPYMT'] ?> </p>
                                                      <p>Number of Installments: <?php echo $fetchres01['PPNUMMONTH'] ?> </p>
                                                      <p>Interest Rate: <?php echo $fetchres01['PPINTAMNT'] ?> </p>
                                                      <p>Initial Payment Amount: <?php echo "$" . $fetchres01['PPFSTPAYAM'] ?> </p>
                                                      <p>Initial Installment Date: <?php echo date("m/d/Y", strtotime($fetchres01['FIRSTPAYDT']));?></p>
                                                      <p>Final Payment Amount:<b> <?php echo "$".$fetchres01['PPLSTPAYAM'] ?> </b></p>
                                                      <p>Final Payment Date:<b> <?php echo date("m/d/Y ", strtotime($fetchres01['LASTPAYDT'])); ?> </b> </p>
                                                      <p>Added Interest: <?php echo $fetchres01['ADDEDINT'] ?> </p>
                                                      <p>Grand Total: <?php echo "$" .$fetchres01['PRTAMNT'] ?> </p>
                                                       <p>Percentage of Offer: <?php echo $fetchres01['PERCOFFER'].'%'; ?></p>

                                                     
                                                     <p><b>Client Comments:</b> <?php echo $fetchres01['CLNTADDINF']?></p>
                                                    <?php if($fetchres01['CounterAceeptReject']==1){
                                                        $action="Accepted";?>
                                                        <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==2){
                                                        $action="Rejected";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==4){
                                                        $action="Expired";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }?>
                                                     <p><b>Client Add Info Reason:</b> <?php echo $fetchres01['CLNTREAS'] ?></p>
                                                      <p><b>Response from Firm:</b> <?php echo $fetchres01['Additionalinfocomments'] ?></p>
                                                            <?php if($fetchres01['Additionalinfo']==1){
                                                        $action="Provided";?>
                                                       <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['Additionalinfo']==2){
                                                        $action="Unavailable";?>
                                                         <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['Additionalinfo']==3){
                                                        $action="Need More Info";?>
                                                        <p> <b>Firm Action on Need More Info:</b> <?php echo $action; ?> </b></p>
                                                     <?php }?>
                                                     <br>
                                                     </div>
                                                 <?php }
                                                  ?>
                                                 <?php }
                                                   else if ($fetchres01['CLNTACCEPT']=='True') {
                                                     if ($fetchres01['CKBXLUMSUM']=='True' ) {
                                                      //   echo "LUMb sum testing";
                                                      // }
                                                       ?>
                                                     <div class="modal-content box col-md-12 sett_off p30_0">
                                                     <p><b>Payment Details:</b></br> </p>
                           <?php
                              if($fetchres01['AUTOACCEPT'] == 'Y'){
                            ?>
                              <p>Settlement Response: Auto Accepted</p>
                            <?php   
                              }
                            ?>
                                                     <p>Deadline of Acceptance: <?php echo $fetchres01['DOADATE'];?> </p>
                                                     <?php
                                                      if ($fetchres01['SPR_BALTYPE']=='prin') {?>
                                                     <p>Firm Principal Bal: $<?php echo $fetchres01['SIFFIRMBAL'];?> </p>
                                                      <?PHP }
                                                      else{ ?>
                                                            <p>Total Balance Due: $<?php echo $fetchres01['TOTBALDUE'];?> </p>
                                                      <?php }
                                                      ?>
                                                     <p>Lump sum Amount: <?php echo "$" . $fetchres01['LUMSUMAMNT'];?> </p>
                                                     <p>Percentage of Offer: <?php echo $fetchres01['PERCOFFER'].'%'; ?></p>

                                                     <p>Lump sum Payment Date: <?php echo date("m/d/Y", strtotime($fetchres01['LUMPAYDATE']));?> </p>
                                                    <!--       <p>Firm Notes:<b> <?php //echo $fetchres01['PAYPLANDET'] ?> </b> </p><br> -->
                                                     <p><b>Client Comments:</b> <?php echo $fetchres01['CLNTADDINF']?></p>
                                                       <?php if($fetchres01['CounterAceeptReject']==1){
                                                        $action="Accepted";?>
                                                        <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==2){
                                                        $action="Rejected";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==4){
                                                        $action="Expired";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }?>

                                                     <br>
                                                     </div>
                                                     <?php  }?>
                                                        <?php
                                                       if ($fetchres01['CHKBXPAYPL']=='True') {
                                                      ?>
                                                   <div class="modal-content box col-md-12 sett_off p30_0">
                                                      <p><b>Payment Details:</b></br> </p>
                                                      <p>Deadline of Acceptance: <?php echo $fetchres01['DOADATE'];?> </p>
                                                     <?php
                                                      if ($fetchres01['SPR_BALTYPE']=='prin') {?>
                                                     <p>Firm Principal Bal: $<?php echo $fetchres01['SIFFIRMBAL'];?> </p>
                                                      <?PHP }
                                                      else{ ?>
                                                            <p>Total Balance Due: $<?php echo $fetchres01['TOTBALDUE'];?> </p>
                                                      <?php }
                                                      ?>
                                                      <p>Total Amount of offer: <?php echo "$" .$fetchres01['PPTOTDUE'] ?> </p>
                                                      <p>Monthly Payment: <?php echo "$".$fetchres01['PPMTHPYMT'] ?> </p>
                                                      <p>Number of Installments: <?php echo $fetchres01['PPNUMMONTH'] ?> </p>
                                                      <p>Interest Rate: <?php echo $fetchres01['PPINTAMNT'] ?> </p>
                                                      <p>Initial Payment Amount: <?php echo "$" . $fetchres01['PPFSTPAYAM'] ?> </p>
                                                      <p>Initial Installment Date: <?php echo date("m/d/Y", strtotime($fetchres01['FIRSTPAYDT']));?></p>
                                                      <p>Final Payment Amount:<b> <?php echo "$".$fetchres01['PPLSTPAYAM'] ?> </b></p>
                                                      <p>Final Payment Date:<b> <?php echo date("m/d/Y ", strtotime($fetchres01['LASTPAYDT'])); ?> </b> </p>
                                                      <p>Added Interest: <?php echo $fetchres01['ADDEDINT'] ?> </p>
                                                      <p>Grand Total: <?php echo "$" .$fetchres01['PRTAMNT'] ?> </p>
                                                       <p>Percentage of Offer: <?php echo $fetchres01['PERCOFFER'].'%'; ?></p>

                                                     <!--  <p>Firm Notes:<b> <?php //echo $fetchres01['PAYPLANDET'] ?> </b> </p><br> -->
                                                     <p><b>Client Comments:</b> <?php echo $fetchres01['CLNTADDINF']?></p>
                                                      <?php if($fetchres01['CounterAceeptReject']==1){
                                                        $action="Accepted";?>
                                                        <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==2){
                                                        $action="Rejected";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==4){
                                                        $action="Expired";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }?>
                                                     </div>
                                                 <?php }
                                                  ?>
                                                   <?php }
                                                   else if ($fetchres01['CLNTREJECT']=='True') {
                                                    if ($fetchres01['CKBXLUMSUM']=='True' ) {
                                                      //   echo "LUMb sum testing";
                                                      // }
                                                       ?>
                                                       <div class="modal-content box col-md-12 sett_off p30_0">
                                                     <p><b>Payment Details:</b></br> </p>
                           <?php
                              if($fetchres01['AUTOACCEPT'] == 'Y'){
                            ?>
                              <p>Settlement Response: Auto Accepted</p>
                            <?php   
                              }
                            ?>
                                                     <p>Deadline of Acceptance: <?php echo $fetchres01['DOADATE'];?> </p>
                                                     <?php
                                                      if ($fetchres01['SPR_BALTYPE']=='prin') {?>
                                                     <p>Firm Principal Bal: $<?php echo $fetchres01['SIFFIRMBAL'];?> </p>
                                                      <?PHP }
                                                      else{ ?>
                                                            <p>Total Balance Due: $<?php echo $fetchres01['TOTBALDUE'];?> </p>
                                                      <?php }
                                                      ?>
                                                     <p>Lump sum Amount: <?php echo "$" . $fetchres01['LUMSUMAMNT'];?> </p>
                                                     <p>Percentage of Offer: <?php echo $fetchres01['PERCOFFER'].'%'; ?></p>

                                                     <p>Lump sum Payment Date: <?php echo date("m/d/Y", strtotime($fetchres01['LUMPAYDATE']));?> </p>
                                              <!--       <p>Firm Notes:<b> <?php //echo $fetchres01['PAYPLANDET'] ?> </b> </p><br> -->
                                              <p><b>Client Comments:</b> <?php echo $fetchres01['CLNTADDINF']?></p>
                                                   <?php if($fetchres01['CounterAceeptReject']==1){
                                                        $action="Accepted";?>
                                                        <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==2){
                                                        $action="Rejected";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==4){
                                                        $action="Expired";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }?>
                        <p><b>Client Add Denial Reason:</b> <?php echo $fetchres01['CLNTDENYR']?></p>

                                                     <br>
                                                     </div>
                                                     <?php  }?>

                                                     <?php
                                                       if ($fetchres01['CHKBXPAYPL']=='True') {
                                                      ?>
                                                   
                                                    
                                                   <div class="modal-content box col-md-12 sett_off p30_0">
                                                      <p><b>Payment Details:</b></br> </p>
                            <?php
                              if($fetchres01['AUTOACCEPT'] == 'Y'){
                            ?>
                              <p>Settlement Response: Auto Accepted</p>
                            <?php   
                              }
                            ?>
                                                      <p>Deadline of Acceptance: <?php echo $fetchres01['DOADATE'];?> </p>
                                                      <?php
                                                      if ($fetchres01['SPR_BALTYPE']=='prin') {?>
                                                     <p>Firm Principal Bal: $<?php echo $fetchres01['SIFFIRMBAL'];?> </p>
                                                      <?PHP }
                                                      else{ ?>
                                                            <p>Total Balance Due: $<?php echo $fetchres01['TOTBALDUE'];?> </p>
                                                      <?php }
                                                      ?>
                                                      <p>Total Amount of offer: <?php echo "$" .$fetchres01['PPTOTDUE'] ?> </p>
                                                      <p>Monthly Payment: <?php echo "$".$fetchres01['PPMTHPYMT'] ?> </p>
                                                      <p>Number of Installments: <?php echo $fetchres01['PPNUMMONTH'] ?> </p>
                                                      <p>Interest Rate: <?php echo $fetchres01['PPINTAMNT'] ?> </p>
                                                      <p>Initial Payment Amount: <?php echo "$" . $fetchres01['PPFSTPAYAM'] ?> </p>
                                                      <p>Initial Installment Date: <?php echo date("m/d/Y", strtotime($fetchres01['FIRSTPAYDT']));?></p>
                                                      <p>Final Payment Amount:<b> <?php echo "$".$fetchres01['PPLSTPAYAM'] ?> </b></p>
                                                      <p>Final Payment Date:<b> <?php echo date("m/d/Y ", strtotime($fetchres01['LASTPAYDT'])); ?> </b> </p>
                                                      <p>Added Interest: <?php echo $fetchres01['ADDEDINT'] ?> </p>
                                                      <p>Grand Total: <?php echo "$" .$fetchres01['PRTAMNT'] ?> </p>
                                                                                <p>Percentage of Offer: <?php echo $fetchres01['PERCOFFER'].'%'; ?></p>
                                                     <!--  <p>Firm Notes:<b> <?php //echo $fetchres01['PAYPLANDET'] ?> </b> </p><br> -->
                                                     <p><b>Client Comments:</b> <?php echo $fetchres01['CLNTADDINF']?></p>
                                                       <?php if($fetchres01['CounterAceeptReject']==1){
                                                        $action="Accepted";?>
                                                        <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==2){
                                                        $action="Rejected";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }else if($fetchres01['CounterAceeptReject']==4){
                                                        $action="Expired";?>
                                                         <p>Firm Action on CounterOffer:  <b><?php echo $action; ?> </b></p>
                                                     <?php }?>
                           <p><b>Client Add Denial Reason:</b> <?php echo $fetchres01['CLNTDENYR']?></p>
                                                     
 
                                                   </div>
                                                 <?php }
                                                  ?>
                                                  <?php } ?>
                                                   <!--client end-->
                                                     <?php }  
                                                      else { ?>
                                                  <!--firm start-->
                                                      <?php
                                                       
                                                      if ($fetchres01['CKBXLUMSUM']=='True' ) {
                                                      //   echo "LUMb sum testing";
                                                      // }
                                                       ?>
                                                        <div class="col-md-12 head_top">

                                                       <!--   <p><b>Date: <?php // echo date("m/d/Y", strtotime($fetchres01['LSTUPDATE']));?></b></p> -->
                                                         <p><b>Debtor: <?php echo $fetchres['WFNAME'];?></b></p>
                                                         <p><b>Account No: <?php echo $fetchres['RACTNM'];?></b></p>
                                                      </div>
                                                     <div class="modal-body col-md-12  sett_off p30_0">
                                                    <h5><b>FIRM SETTLEMENT SUBMISSION</b> </h5>
                                                      <p><b>Submission Response: <?php echo $fetchres01['RACTST'] .' '. $fetchres01['SYSDESC']; ?></b></p>
                                                     
                                                      <p><b> Submission Date: <?php echo date("m/d/Y h:i:s A", strtotime($fetchres01['LSTUPDATE']));?></b></p>

                                                      <p> Submit By: <?php echo $fetchres01['SIFFCNAME'];?> </p>
                                                      <p>Payment Plan in the amount of <span style="text-decoration:underline;"><?php echo "$".$fetchres01['LUMSUMAMNT'] ?></span> </p>
                                                   </div>
                                                   <div class="modal-content box col-md-12 sett_off p30_0">
                                                     <p><b>Payment Details:</b></br> </p>
                           <?php
                              if($fetchres01['AUTOACCEPT'] == 'Y'){
                            ?>
                              <p>Settlement Response: Auto Accepted</p>
                            <?php   
                              }
                            ?>
                                                     <p>Deadline of Acceptance: <?php echo $fetchres01['DOADATE'];?> </p>
                                                   <?php
                                                      if ($fetchres01['SPR_BALTYPE']=='prin') {?>
                                                     <p>Firm Principal Bal: $<?php echo $fetchres01['SIFFIRMBAL'];?> </p>
                                                      <?PHP }
                                                      else{ ?>
                                                            <p>Total Balance Due: $<?php echo $fetchres01['TOTBALDUE'];?> </p>
                                                      <?php }
                                                      ?>
                                                     <p>Lump sum Amount: <?php echo "$" . $fetchres01['LUMSUMAMNT'];?> </p>
                           <p>Percentage of Offer: <?php echo $fetchres01['PERCOFFER'].'%'; ?></p>
                                                     <p>Lump sum Payment Date: <?php echo date("m/d/Y", strtotime($fetchres01['LUMPAYDATE']));?> </p>
                                                    <p><b>Firm Notes:</b> <?php echo $fetchres01['PAYPLANDET'] ?> </p><br>
                                                     <br>
                           
                                                     </div>
                                                     <?php  }?>

                                                      <?php
                                                       if ($fetchres01['CHKBXPAYPL']=='True') {
                                                      ?>
                                                      <div class="col-md-12 head_top">

                                                         <!-- <p><b>Date: <?php //echo date("m/d/Y", strtotime($fetchres01['LSTUPDATE']));?></b></p> -->
                                                         <p><b>Debtor: <?php echo $fetchres['WFNAME'];?></b></p>
                                                         <p><b>Account No: <?php echo $fetchres['RACTNM'];?></b></p>
                                                      </div>
                                                       <div class="modal-body col-md-12  sett_off p30_0">
                                                      <h5><b>FIRM SETTLEMENT SUBMISSION</b> </h5>
                                                      <p><b>Submission Response: <?php echo $fetchres01['RACTST'] .' '. $fetchres01['SYSDESC']; ?></b></p>
                                                      <p><b> Submission Date: <?php echo date("m/d/Y h:i:s A", strtotime($fetchres01['LSTUPDATE']));?></b></p><p> Submit By: <?php echo $fetchres01['SIFFCNAME'];?> </p>
                                                   
                                                   </div>
                                                   <div class="modal-content box col-md-12 sett_off p30_0">
                                                      <p><b>Payment Details:</b></br> </p>
                            <?php
                              if($fetchres01['AUTOACCEPT'] == 'Y'){
                            ?>
                              <p>Settlement Response: Auto Accept</p>
                            <?php   
                              }
                            ?>
                            
                                                      <p>Deadline of Acceptance: <?php echo $fetchres01['DOADATE'];?> </p>
                                                      <?php
                                                      if ($fetchres01['SPR_BALTYPE']=='prin') {?>
                                                     <p>Firm Principal Bal: $<?php echo $fetchres01['SIFFIRMBAL'];?> </p>
                                                      <?PHP }
                                                      else{ ?>
                                                            <p>Total Balance Due: $<?php echo $fetchres01['TOTBALDUE'];?> </p>
                                                      <?php }
                                                      ?>
                                                      <p>Total Amount of offer: <?php echo "$" .$fetchres01['PPTOTDUE'] ?> </p>
                                                      <p>Monthly Payment: <?php echo "$".$fetchres01['PPMTHPYMT'] ?> </p>
                                                      <p>Number of Installments: <?php echo $fetchres01['PPNUMMONTH'] ?> </p>
                                                      <p>Interest Rate: <?php echo $fetchres01['PPINTAMNT'] ?> </p>
                                                      <p>Initial Payment Amount: <?php echo "$" . $fetchres01['PPFSTPAYAM'] ?> </p>
                                                      <p>Initial Installment Date: <?php echo date("m/d/Y", strtotime($fetchres01['FIRSTPAYDT']));?></p>
                                                      <p>Final Payment Amount:<b> <?php echo "$".$fetchres01['PPLSTPAYAM'] ?> </b></p>
                                                      <p>Final Payment Date:<b> <?php echo date("m/d/Y ", strtotime($fetchres01['LASTPAYDT'])); ?> </b> </p>
                                                      <p>Added Interest: <?php echo $fetchres01['ADDEDINT'] ?> </p>
                                                      <p>Grand Total: <?php echo "$" .$fetchres01['PRTAMNT'] ?> </p>
                                                     <!--  <p>Firm Notes:<b> <?php// echo $fetchres01['FREASON'] . ' ' . $fetchres01['FIRMTERMS'] . ' ' . $fetchres01['FRMADDCOMM'] . ' ' . $fetchres01['PAYPLANDET'] ?> </b> </p> -->
                                                                               <p>Percentage of Offer: <?php echo $fetchres01['PERCOFFER'].'%'; ?></p>
                                                     <p><b>Firm Notes:</b> <?php echo $fetchres01['PAYPLANDET'] ?> </p><br>

                                                     
 
                                                   </div>
                                                 <?php }
                                                  ?>
                                               
                                                <!--firm end-->

                                                  <?php  } ?>
                                                 
                                                    <?php  } ?>

                                                    <?php if(mysqli_num_rows($result01)==0) {

                                                    } else {?>
                                                   <p> ****************************** END ******************************</p>
                                                 <?php } ?><br>
                                                </center>
                                                <div class="modal-footer no_bor_t"></div>
                                             </div>
                                          </div>
                                       </div></center>
                                     </div>
                  
                  
                  <div class="modal fade" id="PriorHistoryModal" role="dialog">
                                          <div class="modal-dialog modal-md">
                                             <div class="modal-content">
                                                <center>
                                                   <div class="">
                                                       <button style="padding-right: 20px;
                                                        padding-top: 10px;" type="button" class="close close_top" data-dismiss="modal">&times;</button>

                                                     <?php if(mysqli_num_rows($resultnote)==0) {
                                                     }
                                                     else {?>
                                                      <div align="right"><button style="margin-top: 14px; padding: 5px 12px 5px 12px;
    margin-right: 10px;" type="button" class="btn btn btn-sub" onclick="printJS('printJS-form2', 'html')">Print</button></div>
                                                  <!--    <button class="float_r print" type="button">print</button> -->
                                                   <?php } ?>

                                                   </div>
                                                     <div id="printJS-form2">
                                                      <center>

                                                        <div class="">

                                                           <?php if(mysqli_num_rows($resultnote)==0) {?>
                                                              <h4><b>No Prior Comments Found</b> </h4><br>
                                                                 <?php } else{?>
                                                             <h4><b>Prior Comments</b> </h4>

                                                           <?php }?>
                                                         </div>
 
                                                   <?php
                                                    while($fetchres012=mysqli_fetch_assoc($resultnote)){
                            ?>
                            <div style="margin-bottom:20px" class="modal-content box">
                              <p><b>Submission Date: <?php echo date("m/d/Y h:i:s A", strtotime($fetchres012['LSTUPDATE']));?></b></p>
                                <label><b>Notes:</b></label>
                              <div>
                                <textarea rows="10" maxlength="800" style="width:100%; max-width:60%;" disabled >
                                  <?php echo $fetchres012['FREPRESENT']; ?>
                                </textarea>
                              </div>
<!--                              <p><b>Notes:</b> </p>-->
                            </div>
                            
                                                    <?php  } ?>

                                                    <?php if(mysqli_num_rows($resultnote)==0) {

                                                    } else {?>
                                                   <p> ****************************** END ******************************</p>
                                                 <?php } ?><br>
                                                </center>
                                                <div class="modal-footer no_bor_t"></div>
                                             </div>
                                          </center>
                                          </div>
                                       </div>
                                     </div>
                               <!-- Modal for counter offer developed by Puja kumari on dt:09-05-2022------------------------------>
                                      <div class="modal fade" id="MyCounterOffer" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static" style="overflow-x:hidden;overflow-y:auto">
                                          <div class="modal-dialog modal-md">
                                          
                                         <!--  <i class="fa fa-close close" data-dismiss="modal"></i> -->
                                             <div class="modal-content" style="padding: 25px 15px 15px 15px;">
                                            <div id="printJS-form-counteoffer">
                                             <div class="modal-title">
                                                      <div class="col-md-4 col-sm-4">
                                                      <img src="../img/aaca-net.png" id="Image2" style="width:124px;">
                                                      </div>

                                                      <div class="col-md-4 col-sm-4">
                                                          <center><h4><b>Pending Counter</b></h4></center>
                                                      </div>
                                             </div>

                                                <center>
                                                  
                                                   <div class="modal-header pt30">
                                                
                                                      <div class="col-md-6 col-sm-6" style="padding-top: 20px;margin-left: 91px">
                                                         <p>Date: <?php echo date('m/d/Y');?></p>
                                                         <p>Client :<?php echo $fetchres1['WFORGNM'];?></p>
                                                         <p>Firm : <?php echo $fetchres1['RMSASNDE01'];?> </p>
                                                      </div>
                                                      <div class="col-md-6 col-sm-6" style="padding-top: 20px;margin-top: -80px">
                                                         <p>Debtor: <?php echo $fetchres1['WFNAME'];?></p>
                                                         <p>Account No: <?php echo $fetchres1['RACTNM'];?></p>
                                                         <p>Firm File No: <?php echo $fetchres1['WFFIRMFILE'];?></p>
                                                      </div>
                                                   </div>

                                                   <div class="modal-body sett_off p30_0">
                                                  
                                                      <h3 class="showPaymentPlan"><b>COUNTER OFFER</b></h3>
                                                     
                                                        <?php 
                                                       // print_r($result01);
                                                        $fetchrescounter=mysqli_fetch_assoc($resultcounteroffer);
                                                       
                                                      if($fetchrescounter['AACACNTR'] == 'True'){
                                                      $aacaCounterOffr = $fetchrescounter['AACACNTOFF'];
                                                      $aacaCounterOffr = substr($aacaCounterOffr, 5);
                                                      $aacaCounterOffr = explode(",",$aacaCounterOffr);

                                                      $amtOffr = $aacaCounterOffr[0];
                                                      $amtDown = $aacaCounterOffr[1];
                                                      $mthlyPmt = $aacaCounterOffr[2];
                                                      $noOfPmt = $aacaCounterOffr[3];
                                                      $finalPmt = $aacaCounterOffr[4];
                                                      $deadlineOfAccept = $aacaCounterOffr[5];
                                                      $firstPymt = $aacaCounterOffr[6];
                                                      $comments=$fetchrescounter['AACAADDCOM'];
                                                      $msgname="AACA Comments:";
                                                    }
                                                     else if($fetchrescounter['CLNTCNTR'] == 'True'){
                                                      $clientCounterOffr = $fetchrescounter['CLNTCNINST'];
                                                      $clientCounterOffr = substr($clientCounterOffr, 5);
                                                      $clientCounterOffr = explode(",",$clientCounterOffr);

                                                      $amtOffr = $clientCounterOffr[0];
                                                      $amtDown = $clientCounterOffr[1];
                                                      $mthlyPmt = $clientCounterOffr[2];
                                                      $noOfPmt = $clientCounterOffr[3];
                                                      $finalPmt = $clientCounterOffr[4];
                                                      $deadlineOfAccept = $clientCounterOffr[5];
                                                      $firstPymt = $clientCounterOffr[6];
                                                      $comments=$fetchrescounter['CLNTADDINF'];
                                                      $msgname="Client Comments:";
                                                   }
                                                    ?>
                                                    

                                                      <p>Amount of Offer: <?php echo $amtOffr;?> </p>
                                                      <p>Initial payment amount: <?php echo $amtDown?> </p>
                                                      <p>Monthly Payment: <?php echo $mthlyPmt;?> </p>
                                                      <p>Number of Payments: <?php echo $noOfPmt;?> </p>
                                                      <p>Final Payment: <?php echo $finalPmt;?> </p>
                                                      <p>First Payment Date: <?php echo $firstPymt;?> </p>
                                                      <p>Deadline Date: <?php echo $deadlineOfAccept;?> </p>
                                                      <p><b><?php echo $msgname;?></b><?php echo $comments; ?></p> 
                                                      
                                                   </div>
                                               </center>
                                                </div>
                                                 
                                                <center>
                                                   <div class="modal-footer txt_cntr p20_0">
                                                      <button type="button" class="btn btn2 btn-sub" id="counteraccepted" style="padding: 6px 30px;">Accept Offer</button>
                                                      <button type="button" class="btn btn2 btn-sub" id="counterrejected" >Reject Offer</button>
                                                      <button type="button" class="btn btn2 btn-sub" id="counternewsubmit"  style="padding: 6px 30px;">Submit New Offer</button>
                                                      <button type="button" class="btn btn1 btn-sub" id="counteprint" data-dismiss="modal"  onclick="printJS('printJS-form-counteoffer', 'html')">Print</button>
                                                      
                                                   </div>
                                                </center>
                                             </div>

                                          </div>
                                       </div>
                                        <!-- Modal for counter offer developed by Puja kumari on dt:09-05-2022------------------------------>
                                      <div class="modal fade" id="Myadditionalinfomodal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" data-backdrop="static" style="overflow-x:hidden;overflow-y:auto">
                                          <div class="modal-dialog modal-md">
                                          
                                         <!--  <i class="fa fa-close close" data-dismiss="modal"></i> -->
                                             <div class="modal-content" style="padding: 25px 15px 15px 15px;">
                                            <div id="printJS-form-addmoreinfo">
                                             <div class="modal-title">
                                                      <div class="col-md-4 col-sm-4">
                                                      <img src="../img/aaca-net.png" id="Image2" style="width:124px;">
                                                      </div>

                                                      <div class="col-md-5 col-sm-6">
                                                          <center><h4><b>Additional information Request</b></h4></center>
                                                      </div>
                                             </div>

                                           
                                                   <div class="modal-header pt30">
                                                      <div class="row text-center">
                                                         <div class="col-md-6 col-sm-6" style="padding-top: 20px;margin-top: 0px;">
                                                            <p>Date: <?php echo date('m/d/Y');?></p>
                                                            <p>Client :<?php echo $fetchres1['WFORGNM'];?></p>
                                                            <p>Firm : <?php echo $fetchres1['RMSASNDE01'];?> </p>
                                                            <p>Total Balance Due : <?php echo "$".$fetchres['TOTBALDUE'];?> </p>
                                                            <p>Percentage of Offer : <?php echo $fetchres['PERCOFFER']."%";?> </p>
                                                         </div>
                                                         <div class="col-md-6 col-sm-6" style="padding-top: 20px;margin-top: 0px;">
                                                            <p>Debtor: <?php echo $fetchres1['WFNAME'];?></p>
                                                            <p>Account No: <?php echo $fetchres1['RACTNM'];?></p>
                                                            <p>Firm File No: <?php echo $fetchres1['WFFIRMFILE'];?></p>
                                                            <?php
                                                            if($fetchres['CKBXLUMSUM']=='True'){
                                                            $repayamount=$fetchres['LUMSUMAMNT'];
                                                            }else if($fetchres['CHKBXPAYPL']=='True'){
                                                            $repayamount=$fetchres['PPGROSSAMT'];

                                                            }?>
                                                            <p>Total Repayment Amount : <?php echo "$".$repayamount;?> </p>
                                                            <p>Offer Deadline :<?php echo $fetchres['DOADATE'];?> </p>
                                                         </div>
                                                      </div>
                                                   </div>

                                                   <div class="modal-body sett_off p30_0">
                                                      <?php
                                                      if($fetchres['AACAADDINF']=='True'){
                                                         $addinfomsg=$fetchres['ADDINFREAS'];
                                                      }else if($fetchres['CBXCLNTINF']=='True'){
                                                         $addinfomsg=$fetchres['CLNTREAS'];
                                                      }?>
                                                      <form method="post" action="" enctype="multipart/form-data" id="myform" class="form-horizontal">
                                                         <div class="form-group col-md-12">
                                                            <label style="font-size:16px;padding-top: 0;text-align: left;">Information Requested:</label>
                                                            <p><?php echo $addinfomsg;?></p>
                                                         </div>     
                                                         <div class="form-group">
                                                            <label style="font-size:16px;padding-top: 0;text-align: left;" class="control-label col-md-4">Response from Firm:</label>
                                                            <div class="col-md-8">
                                                               <textarea name="Additionalinfocomments" id="Additionalinfocomments" class="form-control" rows="5"></textarea>
                                                            </div>
                                                         </div>
                                                         <div class="form-group fieldGroupCopy">
                                                            <label style="font-size:16px;padding-top: 0;text-align: left;" class="control-label col-md-4">Upload documents:</label>
                                                            <div class="col-md-5"> 
                                                               <div class="file-upload" >
                                                                 <!--  <i class="fa fa-paperclip" aria-hidden="true" style="font-size: 18px;"></i> -->
                                                                  <input type="file" name="Additionaldocuments[]" id="Additionaldocuments" class="project_image Additionaldocuments files FileUpload">
                                                               </div>
                                                               <span class="error " id="docError"></span>
                                                            </div>
                                                            <div class="col-md-3">
                                                               <a href="javascript:void(0)" class="addMore" id="addMore1"  style="pointer-events: none;">
                                                                     <i class="fa fa-plus-circle" aria-hidden="true" ></i>
                                                               </a>
                                                            </div>
                                                         </div>
                                                   </div>
                                                </div>
                                                 
                                                <center>
                                                   <div class="modal-footer txt_cntr p20_0">
                                                      <button type="button" class="btn btn2 btn-sub" id="moreinfoprovided" style="padding: 6px 30px;">Provided</button>
                                                      <button type="button" class="btn btn2 btn-sub" id="moreinfounavailable" >Unavailable</button>
                                                      <button type="button" class="btn btn2 btn-sub" id="needmoreinfo" style="padding: 6px 30px;">Need More Info</button>
                                                      <button type="button" class="btn btn1 btn-sub" id="moreinfoprint"   onclick="printJS('printJS-form-addmoreinfo', 'html')">Print</button>
                                                      
                                                   </div>
                                                 </center>
                                                </center>
                                             </div>

                                          </div>
                                       </div>
                                     <?php include('../footer.php');?>

                                       <!-- History Modal End -->
                                  
      <!-- Div Hide shows Radio Buttons -->
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script> --> 
<script>
 window.onload = () => {
 const txtFirmBal = document.getElementById('txtFirmBal');
 txtFirmBal.onpaste = e => e.preventDefault();

 const txtAttorneyFees = document.getElementById('txtAttorneyFees');
 txtAttorneyFees.onpaste = e => e.preventDefault();

 const txtFIRMINTAMNT = document.getElementById('txtFIRMINTAMNT');
 txtFIRMINTAMNT.onpaste = e => e.preventDefault();

 const txtAdditionalCosts = document.getElementById('txtAdditionalCosts');
 txtAdditionalCosts.onpaste = e => e.preventDefault();

 const textJudgmentAmount = document.getElementById('textJudgmentAmount');
 textJudgmentAmount.onpaste = e => e.preventDefault();

 const txtTotDebt = document.getElementById('txtTotDebt');
 txtTotDebt.onpaste = e => e.preventDefault();

 const txtTotalMorgages = document.getElementById('txtTotalMorgages');
 txtTotalMorgages.onpaste = e => e.preventDefault();

 const txtTotalAuto = document.getElementById('txtTotalAuto');
 txtTotalAuto.onpaste = e => e.preventDefault();

 const txtStudentLoans = document.getElementById('txtStudentLoans');
 txtStudentLoans.onpaste = e => e.preventDefault();

 const TotalAmountOfOffer = document.getElementById('TotalAmountOfOffer');
 TotalAmountOfOffer.onpaste = e => e.preventDefault();

 const monthlyPaymentAmt = document.getElementById('monthlyPaymentAmt');
 monthlyPaymentAmt.onpaste = e => e.preventDefault();

 const InitialPaymentAmount = document.getElementById('InitialPaymentAmount');
 InitialPaymentAmount.onpaste = e => e.preventDefault();

 const txtLumSumAmt = document.getElementById('txtLumSumAmt');
 txtLumSumAmt.onpaste = e => e.preventDefault();

 const AACACounterOfferAmount = document.getElementById('AACACounterOfferAmount');
 AACACounterOfferAmount.onpaste = e => e.preventDefault();

 const AACACounterOfferAmountDown = document.getElementById('AACACounterOfferAmountDown');
 AACACounterOfferAmountDown.onpaste = e => e.preventDefault();

 const AACACounterOfferMonthlyPayment = document.getElementById('AACACounterOfferMonthlyPayment');
 AACACounterOfferMonthlyPayment.onpaste = e => e.preventDefault();

 const AACACounterOfferFinalPayment = document.getElementById('AACACounterOfferFinalPayment');
 AACACounterOfferFinalPayment.onpaste = e => e.preventDefault();

 const AmountofCounterOffer = document.getElementById('AmountofCounterOffer');
 AmountofCounterOffer.onpaste = e => e.preventDefault();

 const AmountDown = document.getElementById('AmountDown');
 AmountDown.onpaste = e => e.preventDefault();

 const MonthlyPaymentAmount = document.getElementById('MonthlyPaymentAmount');
 MonthlyPaymentAmount.onpaste = e => e.preventDefault();

 const FinalPaymentAmountCntr = document.getElementById('FinalPaymentAmountCntr');
 FinalPaymentAmountCntr.onpaste = e => e.preventDefault();

 const MonthlyPaymentAmount02 = document.getElementById('MonthlyPaymentAmount02');
 MonthlyPaymentAmount02.onpaste = e => e.preventDefault();

 const MonthlyPaymentAmount01 = document.getElementById('MonthlyPaymentAmount01');
 MonthlyPaymentAmount01.onpaste = e => e.preventDefault();

 const OriginalTermofMortgage = document.getElementById('OriginalTermofMortgage');
 OriginalTermofMortgage.onpaste = e => e.preventDefault();

 const YearsLeftontheMortgage = document.getElementById('YearsLeftontheMortgage');
 YearsLeftontheMortgage.onpaste = e => e.preventDefault();

 
}
  $(function () {
    /* for document view of question doc*/
  $('.DocPath').click(function() {
    var link   =$(this).data('id');
    var extension=link.split('.').pop().toLowerCase();
        $.ajax({
        url: 'sessionstoresettlement',
        type: 'post',
        data: {link: link},
        success: function(response){ 
        window.open("viewdocsettlement", '_blank');
        }
        });  
  })
})
$(document).ready(function(){``
    var maxField = 10; //Input fields increment limitation
    var addButton = $('.addMore'); //Add button selector
    var wrapper = $('.fieldGroupCopy'); //Input field wrapper
    var x = 1; //Initial field counter is 1
    var countdiv=1;
    //Once add button is clicked
   $(document).delegate('.addMore', 'click', function(){
    if(countdiv>=10){
        swal("Missing Information Alert", 'More than 10 entries can not be added', "error");
        return false;
      }
     x++;
    countdiv++;

    $(wrapper).append('<div class="fieldGroup"><div class="col-md-offset-4 col-md-5"><div class="file-upload" style="margin-top:10px;"><input type="file" name="Additionaldocuments[]" id="" class="fieldGroupCopy Additionaldocuments project_image files FileUpload" data-id="'+x+'"></div><div class ="error" id="docError'+x+'"></div></div><div class="col-md-3"><div style="margin-top:10px;"><a href="javascript:void(0)" class="addMore" id="addMore1"><i class="fa fa-plus-circle" aria-hidden="true"></i></a><a href="javascript:void(0)" class="removeimage" id="removeimage"><i class="fa fa-minus-circle text-danger" aria-hidden="true"></i></a></div></div>');
  
    $('.addMore').css('pointer-events','none');
    /* to remove row```````````````````*/
    $('.removeimage').unbind('click').bind('click', function (e) {
        e.preventDefault();
        $(this).parents(".fieldGroup").remove();
        $('#moreinfoprovided').prop('disabled', false);
        $('#moreinfounavailable').prop('disabled', false);
        $('#needmoreinfo').prop('disabled', false);
        $('.addMore').prop('disabled', false);
        $('.addMore').css('pointer-events','inherit');
        countdiv--;
        
       
      
    });


    /*================upload and view doc for need info******************************/
   $(".Additionaldocuments").on("change", function (e) {
        e.preventDefault(); 
        var fd = new FormData();
        var Additionaldocuments = $(this)[0].files[0]; //$('#Additionaldocuments')[0].files[0];
        var docupload="docupload";
        fd.append('Additionaldocuments',Additionaldocuments);
        fd.append('docupload',docupload);
        var extension= $(this).val();
        var filesize=$(this)[0].files[0].size/(1024*1024);
        var fileExtension = ['pdf', 'tif', 'txt', 'zip', 'jpeg','xls','xlsx','doc','docx'];
        var classincreas   =$(this).data('id');
       
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            $('#docError'+classincreas+'').css('display', 'block');
            $('#docError'+classincreas+'').text("Only formats are allowed : "+fileExtension.join(', '));
            $('#moreinfoprovided').prop('disabled', true);
            $('#moreinfounavailable').prop('disabled', true);
            $('#needmoreinfo').prop('disabled', true);
            $('.addMore').prop('disabled', true);
            return false;

        }else if(filesize>5 && extension!=''){
            $('#docError'+classincreas+'').css('display', 'block');
            $('#docError'+classincreas+'').text("File size can not be greater than 5MB");
            $('#moreinfoprovided').prop('disabled', true);
            $('#moreinfounavailable').prop('disabled', true);
            $('#needmoreinfo').prop('disabled', true);
            $('.addMore').prop('disabled', true);
             return false;
        }else{
           // $('#loader').show();
            $('#docError'+classincreas+'').css('display', 'none');
            $('#moreinfoprovided').prop('disabled', false);
            $('#moreinfounavailable').prop('disabled', false);
            $('#needmoreinfo').prop('disabled', false);
            $('.addMore').prop('disabled', false);
            $('.addMore').css('pointer-events','inherit');
        

    } 
  });
   
   })

  /*================upload and view doc for need info******************************/
   $(".Additionaldocuments").on("change", function (e) {
        e.preventDefault(); 
        var fd = new FormData();
        var Additionaldocuments = $(this)[0].files[0]; //$('#Additionaldocuments')[0].files[0];
        var docupload="docupload";
        fd.append('Additionaldocuments',Additionaldocuments);
        fd.append('docupload',docupload);
        var extension= $(this).val();
        var filesize=$(this)[0].files[0].size/(1024*1024);
        var fileExtension = ['pdf', 'tif', 'txt', 'zip', 'jpeg','xls','xlsx','doc','docx'];
         if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            $('#docError').css('display', 'block');
            $('#docError').text("Only formats are allowed : "+fileExtension.join(', '));
            $('#moreinfoprovided').prop('disabled', true);
            $('#moreinfounavailable').prop('disabled', true);
            $('#needmoreinfo').prop('disabled', true);
            $('.addMore').prop('disabled', true);
            return false;

        }else if(filesize>5 && extension!=''){
            $('#docError').css('display', 'block');
            $('#docError').text("File size can not be greater than 5MB");
            $('#moreinfoprovided').prop('disabled', true);
            $('#moreinfounavailable').prop('disabled', true);
            $('#needmoreinfo').prop('disabled', true);
            $('.addMore').prop('disabled', true);
            return false;
        }else{
            //$('#loader').show();
            $('#docError').css('display', 'none');
            $('#moreinfoprovided').prop('disabled', false);
            $('#moreinfounavailable').prop('disabled', false);
            $('#needmoreinfo').prop('disabled', false);
            $('.addMore').prop('disabled', false);
            $('.addMore').css('pointer-events','inherit');
        
     /*$.ajax({
      type: "POST",
      url: "ajaxSubmitaddmoreinfo.php",
      data: fd,
     contentType: false,
     processData: false,
    success: function(data){ 
    if(data!='')
    { $('#loader').hide();
      var path=data;
     
     $("#showdocumetsofmoreinfo").append(`<a href="${data}" target="_blank">view doc</a>`);
    }
      },
      error: function(){
             $('#loader').hide();
               $('#moreinfoprovided').prop('disabled', false);
               
        alert("Something went wrong. There is an error with your submission.");
      } 
    });*/
    } 
  });


     })
  //function for counter offer developed by Puja kumari on dt:09-05-2022
     $(document).ready(function () {
    
     var userType='<?php echo ($_SESSION['userType']);?>';
     var dbfilenum='<?php echo $fetchres['FILNUM'];?>';
     var loginfilenum='<?php echo ($_SESSION['rmsfilenumenc']);?>';
     var counteroffercheckedaaca='<?php echo $fetchres['AACACNTR'];?>';
     var counteroffercheckedclient='<?php echo $fetchres['CLNTCNTR'];?>';
     var CounterAceeptReject  ='<?php echo $fetchres['CounterAceeptReject'];?>';
     var Additionalinfo      ='<?php echo $fetchres['Additionalinfo'];?>';
     var aacaAdditionalinfo      ='<?php echo $fetchres['AACAADDINF'];?>';
     var clientAdditionalinfo      ='<?php echo $fetchres['CBXCLNTINF'];?>';
     var acceptcounter             ='<?php echo $fetchquerycounter['CounterAceeptReject'];?>';
      if(userType==2 && (dbfilenum==loginfilenum) &&  (counteroffercheckedaaca=='True' || counteroffercheckedclient=='True') && (CounterAceeptReject ==0 || CounterAceeptReject ==3)){
       $('#MyCounterOffer').modal('show');
      }
   if(userType==2 && (dbfilenum==loginfilenum) &&  (aacaAdditionalinfo=='True' || clientAdditionalinfo=='True') && Additionalinfo ==0){
      $('#Myadditionalinfomodal').modal('show');

     }
     if(acceptcounter==1 && userType==2){
         swal({
                title: "There is already an accepted offer on this account. Submitting a new offer or response will invalidate the previous acceptance. Do you wish to proceed?",
                // text: "Where do yo want to redirect?",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel",
                }, 
                function (isConfirm) {
              if (isConfirm) {
                 swal.close();
                } else {
                   window.location = '../searchacc';
                  
                }
           });
       }
        
/* counter reject==========================*/
      $('#counterrejected').on('click',function(){
          swal({
                title: "You have indicated that the counteroffer has been rejected, do you wish to proceed?",
                // text: "Where do yo want to redirect?",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel",
                }, 
                function (isConfirm) {
              if (isConfirm) {
                  $('#counterrejected').prop('disabled', true);
                  $('#loader').show();
                  $(".sweet-alert").css("display", "none");
                  var Reject="Reject";
                     $.ajax({
                    type: "POST",
                    url: "ajaxSubmitcounter.php",
                    data:{Reject:Reject},
                    success: function(data){
                      if(data == 1){
                       $('#MyCounterOffer').modal('hide');
                       $('#counterrejected').prop('disabled', false);
                       $('#loader').hide();
                       setTimeout(function() {
                       swal({title: "",
                            text: "Counteroffer rejected succesfully.",
                            timer: 3000,
                            showConfirmButton: false,
                            type: 'success'
                          },function() {
                              window.location = '../searchacc';
                               });
                      }, 1000);
                  
                     }
                    }
                  });
                } else {
                  
                }
      });
       

    }); 

    /* counter accepted==========================*/  
    $('#counteraccepted').on('click',function(){
          swal({
                title: "You have indicated that the counteroffer has been accepted, do you wish to proceed?",
                // text: "Where do yo want to redirect?",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel",
                }, 
                function (isConfirm) {
              if (isConfirm) {
                  $('#counteraccepted').prop('disabled', true);
                  $('#loader').show();
                   $(".sweet-alert").css("display", "none");
                  var Accept="Accept";
                     $.ajax({
                    type: "POST",
                    url: "ajaxSubmitcounter.php",
                    data:{Accept:Accept},
                    success: function(data){
                      if(data == 1){
                       $('#MyCounterOffer').modal('hide');
                       $('#counteraccepted').prop('disabled', false);
                       $('#loader').hide();
                       setTimeout(function() {
                       swal({title: "",
                            text: "Counteroffer accepted succesfully.",
                            timer: 3000,
                            showConfirmButton: false,
                            type: 'success'
                          },function() {
                               window.location = '../searchacc';
                               });
                      }, 1000);
                  
                     }
                    }
                  });
                } else {
                  
                }
      });
       

    }); 
  /*submit new offerr for counter modal**************************************************/
 
        $('#counternewsubmit').on('click',function(){
          swal({
                title: "You have selected New Offer, this means the counteroffer will be rejected upon submission of the new offer, do you wish to proceed?",
                // text: "Where do yo want to redirect?",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel",
                }, 
                function (isConfirm) {
              if (isConfirm) {
                 // $('#counternewsubmit').prop('disabled', true);
                 // $('#loader').show();
                  // $(".sweet-alert").css("display", "none");
                  // $(".sweet-overlay").css("display", "none");
                   swal.close();
                   $('#MyCounterOffer').modal('hide');
                   $("#gentab").removeClass("active");
                   $("#termsofoffer").addClass("active");
                   $("#Guide01").removeClass("active");
                   $("#Guide03").addClass("active");
                   
                //  var submitnewoffer="submitnewoffer";
                  //    $.ajax({
                  //   type: "POST",
                  //   url: "ajaxSubmitcounter.php",
                  //   data:{submitnewoffer:submitnewoffer},
                  //   success: function(data){
                  //     if(data == 1){
                  //     $('#MyCounterOffer').modal('hide');
                  //      $('#counternewsubmit').prop('disabled', false);
                  //      $('#loader').hide();
                  //       location.reload();
        
                  
                  //    }
                  //   }
                  // });
                } else {
                  
                }
      });
       

    });
        /*more info provided=================================*/
     $('#moreinfoprovided').on('click',function(){
          swal({
                title: "Are you sure you want to provide the document?",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel",
                }, 
                function (isConfirm) {
              if (isConfirm) {
                  $('#moreinfoprovided').prop('disabled', true);
                  $('#loader').show();
                  $(".sweet-alert").css("display", "none");
                   var fd = new FormData($("#myform")[0]);
                   fd.append('moreinfoprovided',moreinfoprovided);
     
                     $.ajax({
                      type: "POST",
                      url: "ajaxSubmitaddmoreinfo.php",
                      data: fd,
                     contentType: false,
                     processData: false,
                      success: function(data){    
                      if(data == 1){
                       $('#Myadditionalinfomodal').modal('hide');
                       $('#moreinfoprovided').prop('disabled', false);
                       $('#loader').hide();
                       setTimeout(function() {
                       swal({title: "",
                            text: "Document provided successfully.",
                            timer: 3000,
                            showConfirmButton: false,
                            type: 'success'
                          },function() {
                              window.location = '../searchacc';
                               });
                      }, 1000);
                  
                     }
                    }
                  });
                } else {
                  
                }
      });
       

    }); 
/* add more info unavailable=========================*/
   $('#moreinfounavailable').on('click',function(){
          swal({
                title: "Please confirm that there are no supporting documents available?",
                // text: "Where do yo want to redirect?",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel",
                }, 
                function (isConfirm) {
              if (isConfirm) {
                  $('#moreinfounavailable').prop('disabled', true);
                  $('#loader').show();
                   $(".sweet-alert").css("display", "none");
                 // var moreinfounavailable="moreinfounavailable";
                 // var Additionalinfocomments=$('#Additionalinfocomments').val();
                   var fd = new FormData($("#myform")[0]);
                   fd.append('moreinfounavailable',moreinfounavailable);
                       $.ajax({
                        type: "POST",
                        url: "ajaxSubmitaddmoreinfo.php",
                        data: fd,
                        contentType: false,
                        processData: false,
                     
                      success: function(data){    
                      if(data == 1){
                      $('#Myadditionalinfomodal').modal('hide');
                       $('#moreinfounavailable').prop('disabled', false);
                       $('#loader').hide();
                       setTimeout(function() {
                       swal({title: "",
                            text: "Documents unavailable",
                            timer: 3000,
                            showConfirmButton: false,
                            type: 'success'
                          },function() {
                               window.location = '../searchacc';
                               });
                      }, 1000);
                  
                     }
                    }
                  });
                } else {
                  
                }
      });
       

    });


 
  /*need more info =================================*/
   $('#needmoreinfo').on('click',function(){
          swal({
                title: "Please confirm that you need additional information to respond to the client's request?",
                // text: "Where do yo want to redirect?",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "Yes",
                cancelButtonText: "Cancel",
                }, 
                function (isConfirm) {
              if (isConfirm) {
                  $('#moreinfoprovided').prop('disabled', true);
                  $('#loader').show();
                   $(".sweet-alert").css("display", "none");
                   var fd = new FormData($("#myform")[0]);
                   fd.append('needmoreinfo',needmoreinfo);
                       $.ajax({
                        type: "POST",
                        url: "ajaxSubmitaddmoreinfo.php",
                        data: fd,
                        contentType: false,
                        processData: false,
                      success: function(data){    
                      if(data == 1){
                      $('#Myadditionalinfomodal').modal('hide');
                       $('#needmoreinfo').prop('disabled', false);
                       $('#loader').hide();
                       setTimeout(function() {
                       swal({title: "",
                            text: "Your request for more information has been successfully submitted",
                            timer: 3000,
                            showConfirmButton: false,
                            type: 'success'
                          },function() {
                               window.location = '../searchacc';
                               });
                      }, 1000);
                  
                     }
                    }
                  });
                } else {
                  
                }
      });
       

    }); 
      }); 
/*function to check aaca and client response button enable disable by puja kumari on dt:17052022*/
$(document).ready(function () {
    var aacaaccept='<?php echo $fetchres['AACAACCEPT'];?>'; 
    var aacareject='<?php echo $fetchres['AACAREJECT'];?>'; 
    var aacarfrtoclient='<?php echo $fetchres['AACARFRTOCLNT'];?>'; 
    var aacaacounter='<?php echo $fetchres['AACACNTR'];?>'; 
    var aacaaddinfo='<?php echo $fetchres['AACAADDINF'];?>'; 
    var clientaccept='<?php echo $fetchres['CLNTACCEPT'];?>'; 
    var clientreject='<?php echo $fetchres['CLNTREJECT'];?>'; 
    var clientcounter='<?php echo $fetchres['CLNTCNTR'];?>'; 
    var clientaddinfo='<?php echo $fetchres['CBXCLNTINF'];?>';
    if(aacaaccept=='True' || aacareject=='True' || aacarfrtoclient=='True' || aacaacounter=='True' || aacaaddinfo=='True' || clientaccept=='True' || clientreject=='True' || clientcounter=='True' || clientaddinfo=='True'){
      $('#btnSubmitReply').prop("disabled",true);
      $('#btnSubmitClient').prop("disabled",true);
    }
    $('#Historybtn01').on('click',function(){
      $('#btnSubmitReply').prop("disabled",false);
    })
    $('#Historybtn02').on('click',function(){
      $('#btnSubmitClient').prop("disabled",false);
    })
  })

$(document).ready(function () {
  
    $(".confirm").click(function(){
         $('#modalbox').modal('show');
    });

    $("#Historybtn").click(function(){
      // by sujata
      filenumadvance = $("#filenumadvance").val();
      //alert(filenumadvance+'1');
      var action = "settlementHistory";
      $.ajax({
          url:"settlementHistory.php",
          type:"POST",          
          data:{filenumadvance:filenumadvance, action:action},
        success:function(data)
        {       
          $("#offerHistoryModal").html('');
          $("#offerHistoryModal").html(data);   
        }
      }); 
      // end sujata
       $('#offerHistoryModal').modal('show');
   });
    $("#Historybtn01").click(function(){
            // by sujata
      filenumadvance = $("#filenumadvance").val();
     // alert(filenumadvance+'2');
      var action = "settlementHistory";
      $.ajax({
          url:"settlementHistory.php",
          type:"POST",          
          data:{filenumadvance:filenumadvance, action:action},
        success:function(data)
        { 
          $("#offerHistoryModal").html('');
          $("#offerHistoryModal").html(data);   
        }
      }); 
      // end sujata
       $('#offerHistoryModal').modal('show');
   });

    $("#Historybtn02").click(function(){
            // by sujata
            filenumadvance = $("#filenumadvance").val();
           // alert(filenumadvance+'3');
      var action = "settlementHistory";
      $.ajax({
          url:"settlementHistory.php",
          type:"POST",          
          data:{filenumadvance:filenumadvance, action:action},
        success:function(data)
        {     
         $("#offerHistoryModal").html('');
         $("#offerHistoryModal").html(data);   
        }
      }); 
      // end sujata
       $('#offerHistoryModal').modal('show');
   });
   
   $("#PriorHistorybtn").click(function(){
    $('#PriorHistoryModal').modal('show'); 
   });

    var hardshipClaim = '<?php echo $fetchres['HARDSHIPCLAIM']; ?>';
    if(hardshipClaim == 'Yes')
      {
        document.getElementById("enableFileUpload").style.display = "block";
      }

    var rentown = '<?php echo $fetchres['RENTOWN']; ?>';
    if(rentown == 'R')
    {
       document.getElementById("Debtor_Rent").style.display = "block";
    }
    else if(rentown == 'O')
    {
       document.getElementById("Debtor_Own").style.display = "block";
    }

  var conrep = $("input[type='radio'][name='rblRep']:checked").val();
  var conrepdesc = $("input[type='radio'][name='rblRepDesc']:checked").val();
  
  if(conrep == 'Y'){
    if(conrepdesc != undefined){
      document.getElementById("conreperror").style.display = "none";
    }else{
      document.getElementById("conreperror").style.display = "block";
    }
  }

  
   
    var rblRepDesc1 = '<?php echo $fetchres['RBLREP']; ?>';
    if(rblRepDesc1 == 'Y')
    {
       document.getElementById("rblRepDesc2").style.display = "block";
    }

    var cBXTOTDEBT = '<?php echo $fetchres['CBXTOTDEBT']; ?>';
    var cBXTOTMORT = '<?php echo $fetchres['CBXTOTMORT']; ?>';
    var cBXTOTAUTO = '<?php echo $fetchres['CBXTOTAUTO']; ?>';
    var cBXTOTSTUD = '<?php echo $fetchres['CBXTOTSTUD']; ?>';

    if(cBXTOTDEBT == 'Y' || cBXTOTDEBT == '1')
    {
      document.getElementById("txtTotDebt").value = "";
    }

    if(cBXTOTMORT == 'A' || cBXTOTMORT == 'B' || cBXTOTMORT == 'C' || cBXTOTMORT == 'D')
    {
      document.getElementById("txtTotalMorgages").value = "";
    }

    if(cBXTOTAUTO == 'A' || cBXTOTAUTO == 'B' || cBXTOTAUTO == 'C' || cBXTOTAUTO == 'D')
    {
      document.getElementById("txtTotalAuto").value = "";
    }

    if(cBXTOTSTUD == 'A' || cBXTOTSTUD == 'B' || cBXTOTSTUD == 'C' || cBXTOTSTUD == 'D')
    {
      document.getElementById("txtStudentLoans").value = "";
    }

    var cBXMTHPAY = '<?php echo $fetchres['CBXMTHPAY']; ?>';
    var cBXMRTYRS = '<?php echo $fetchres['CBXMRTYRS']; ?>';
    var cBXYRSLEFT = '<?php echo $fetchres['CBXYRSLEFT']; ?>';
    var rEFI = '<?php echo $fetchres['REFI']; ?>';

    if(cBXMTHPAY == 'Y' || cBXMTHPAY == '1')
    {
      document.getElementById("MonthlyPaymentAmount02").value = "";
      document.getElementById("MonthlyPaymentAmount01").value = "";
    }

    if(cBXMRTYRS == 'Y' || cBXMRTYRS == '1')
    {
      document.getElementById("OriginalTermofMortgage").value = "";
    }

    if(cBXYRSLEFT == 'Y' || cBXYRSLEFT == '1')
    {
      document.getElementById("YearsLeftontheMortgage").value = "";
    }

    if(rEFI == 'Y' || rEFI == '1')
    {
      document.getElementById("DateofLastRefinancing").value = "";
    }

    var phoneNumber = document.querySelector('#textPhone');

    phoneNumber.addEventListener('input', restrictNumber);
    function restrictNumber (e) {  
      var newValue = this.value.replace(new RegExp(/[^\d]/,'ig'), "");
      this.value = newValue;
    }
});

  $("#Myshow" ).hover(function() {
      $("#showdetail").css('display', 'block');
    },function(){
      $("#showdetail").css('display', 'none');
  });

</script>

<script>
      $('input[type=radio][name=hrdshp]').change(function() {
            if(this.value == 'Yes')
            {
             document.getElementById("enableFileUpload").style.display = "block";
            } 
            else
            {
              document.getElementById("enableFileUpload").style.display = "none";
            }
      })

      $('input[type=checkbox][name=cbxTotDebt]').change(function() {
        if($(this).is(":checked")) {
          if(this.value == 'Y')
          {
             document.getElementById("cbxTotDebt2").checked = false;
             document.getElementById('txtTotDebt').readOnly = true;
             document.getElementById('txtTotDebt').value = '';            
          }
          else
          {
            document.getElementById("cbxTotDebt1").checked = false;
            document.getElementById('txtTotDebt').readOnly = true;
            document.getElementById('txtTotDebt').value = '';
          }  
        }
        else
        {
          document.getElementById('txtTotDebt').readOnly = false;
        }

      })


      $('input[type=checkbox][name=cbxTotMortgages]').change(function() {
        if($(this).is(":checked")) {
          if(this.value == 'A')
          {
             document.getElementById("cbxTotMortgages2").checked = false;
             document.getElementById('txtTotalMorgages').readOnly = true;
             document.getElementById('txtTotalMorgages').value = '';
          }
          else
          {
            document.getElementById("cbxTotMortgages1").checked = false;
            document.getElementById('txtTotalMorgages').readOnly = true;
            document.getElementById('txtTotalMorgages').value = '';
          }  
        }
        else
        {
          document.getElementById('txtTotalMorgages').readOnly = false;
        }

      })


      $('input[type=checkbox][name=cbxTotAuto]').change(function() {
        if($(this).is(":checked")) {
          if(this.value == 'A')
          {
             document.getElementById("cbxTotAuto2").checked = false;
             document.getElementById('txtTotalAuto').readOnly = true;
             document.getElementById('txtTotalAuto').value = '';
          }
          else
          {
            document.getElementById("cbxTotAuto1").checked = false;
            document.getElementById('txtTotalAuto').readOnly = true;
            document.getElementById('txtTotalAuto').value = '';
          }  
        }
        else
        {
          document.getElementById('txtTotalAuto').readOnly = false;
        }

      })


      $('input[type=checkbox][name=cbsTotStudent]').change(function() {
        if($(this).is(":checked")) {
          if(this.value == 'A')
          {
             document.getElementById("cbsTotStudent2").checked = false;
             document.getElementById('txtStudentLoans').readOnly = true;
             document.getElementById('txtStudentLoans').value = '';
          }
          else
          {
            document.getElementById("cbsTotStudent1").checked = false;
            document.getElementById('txtStudentLoans').readOnly = true;
            document.getElementById('txtStudentLoans').value = '';
          }  
        }
        else
        {
          document.getElementById('txtStudentLoans').readOnly = false;
        }

      })

      $('input[type=checkbox][name=CBXMTHPAY2]').change(function() {
        if($(this).is(":checked")) {
          if(this.value == 'Y')
          {
             document.getElementById("CBXMTHPAY22").checked = false;
             document.getElementById('MonthlyPaymentAmount02').readOnly = true;
             document.getElementById('MonthlyPaymentAmount02').value = '';
          }
          else
          {
            document.getElementById("CBXMTHPAY21").checked = false;
            document.getElementById('MonthlyPaymentAmount02').readOnly = true;
            document.getElementById('MonthlyPaymentAmount02').value = '';
          }  
        }
        else
        {
          document.getElementById('MonthlyPaymentAmount02').readOnly = false;
        }

      })



      $('input[type=checkbox][name=CBXMRTYRS]').change(function() {
        if($(this).is(":checked")) {
          if(this.value == 'Y')
          {
             document.getElementById("CBXMRTYRS02").checked = false;
             document.getElementById('OriginalTermofMortgage').readOnly = true;
             document.getElementById('OriginalTermofMortgage').value = '';
          }
          else
          {
            document.getElementById("CBXMRTYRS01").checked = false;
            document.getElementById('OriginalTermofMortgage').readOnly = true;
            document.getElementById('OriginalTermofMortgage').value = '';
          }  
        }
        else
        {
          document.getElementById('OriginalTermofMortgage').readOnly = false;
        }

      })

      $('input[type=checkbox][name=CBXYRSLEFT]').change(function() {
        if($(this).is(":checked")) {
          if(this.value == 'Y')
          {
             document.getElementById("CBXYRSLEFT02").checked = false;
             document.getElementById('YearsLeftontheMortgage').readOnly = true;
             document.getElementById('YearsLeftontheMortgage').value = '';
          }
          else
          {
            document.getElementById("CBXYRSLEFT01").checked = false;
            document.getElementById('YearsLeftontheMortgage').readOnly = true;
            document.getElementById('YearsLeftontheMortgage').value = '';
          }  
        }
        else
        {
          document.getElementById('YearsLeftontheMortgage').readOnly = false;
        }

      })


      $('input[type=checkbox][name=REFI]').change(function() {
        if($(this).is(":checked")) {
          if(this.value == 'Y')
          {
             document.getElementById("REFI02").checked = false;
             document.getElementById('DateofLastRefinancing').disabled = true;
             document.getElementById('DateofLastRefinancing').value = '';
          }
          else
          {
            document.getElementById("REFI01").checked = false;
            document.getElementById('DateofLastRefinancing').disabled  = true;
            document.getElementById('DateofLastRefinancing').value = '';
          }  
        }
        else
        {
          document.getElementById('DateofLastRefinancing').disabled  = false;
        }

      })


      $('input[type=checkbox][name=CBXMTHPAY]').change(function() {
        if($(this).is(":checked")) {
          if(this.value == 'Y')
          {
             document.getElementById("CBXMTHPAY02").checked = false;
             document.getElementById('MonthlyPaymentAmount01').readOnly = true;
             document.getElementById('MonthlyPaymentAmount01').value = '';
          }
          else
          {
            document.getElementById("CBXMTHPAY01").checked = false;
            document.getElementById('MonthlyPaymentAmount01').readOnly = true;
            document.getElementById('MonthlyPaymentAmount01').value = '';
          }  
        }
        else
        {
          document.getElementById('MonthlyPaymentAmount01').readOnly = false;
        }

      })


      function consumerChkBox()
      {
        var flag = true;
        var unsecuredCbx = $('.cbxTotDebtNew:checkbox:checked');
        var totalCbx = $('.cbxTotMortgagesNew:checkbox:checked');
        var autoCbx = $('.cbxTotAutoNew:checkbox:checked');
        var studentCbx = $('.cbsTotStudentNew:checkbox:checked');
        var hardshipDocs = $('#hrdshp').val();
        
    var conrep = $("input[type='radio'][name='rblRep']:checked").val();
    var conrepdesc = $("input[type='radio'][name='rblRepDesc']:checked").val();

    if(conrep == 'Y'){
      if(conrepdesc == undefined){
        document.getElementById("conreperror").style.display = "block";
        flag = false;
      }else{
        document.getElementById("conreperror").style.display = "none";
      }
    }
    
    
        if(unsecuredCbx.length == 0)
        {
          if($('#txtTotDebt').val() == '')
          {
            $('#cbxTotDebtErr').css('display', 'block');
            $('#cbxTotDebtErr').text('This field is required');
           flag = false;
         }
         else
         {
            $('#cbxTotDebtErr').css('display', 'none');
         }
        }
        else
        {
          $('#cbxTotDebtErr').css('display', 'none');
        }

        if(totalCbx.length == 0)
        {
          if($('#txtTotalMorgages').val() == '')
          {
            $('#cbxTotMortgagesErr').css('display', 'block');
            $('#cbxTotMortgagesErr').text('This field is required');
           flag = false;
         }
         else
         {
            $('#cbxTotMortgagesErr').css('display', 'none');
         }
        }
        else
        {
          $('#cbxTotMortgagesErr').css('display', 'none');
        }

        if(autoCbx.length == 0)
        {
          if($('#txtTotalAuto').val() == '')
          {
            $('#cbxTotAutoErr').css('display', 'block');
            $('#cbxTotAutoErr').text('This field is required');
           flag = false;
         }
         else
         {
            $('#cbxTotAutoErr').css('display', 'none');
         }
        }
        else
        {
           $('#cbxTotAutoErr').css('display', 'none');
        }

        if(studentCbx.length == 0)
        {
          if($('#txtStudentLoans').val() == '')
          {
            $('#cbsTotStudentErr').css('display', 'block');
            $('#cbsTotStudentErr').text('This field is required');
           flag = false;
         }
         else
         {
            $('#cbsTotStudentErr').css('display', 'none');
         }
        }
        else
        {
            $('#cbsTotStudentErr').css('display', 'none');
        }
    

        if(flag)
        {
          return true;
        }
        else
        {
          return false;
        }
      }


  
     $(function () {
              /* To select date*/
         var date = new Date();
         var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
         $('#deadofaccpt').datepicker({ 
         dateFormat: "mm/dd/yy",
         minDate: +1
        // maxDate: date
    });
    });



     var newMinDate = 1;
     $('#InitialInstallmentDate').datepicker({ 
         dateFormat: "mm/dd/yy",
          minDate: +newMinDate
        // maxDate: date
    });
     $("#deadofaccpt").change(function () {
      var date1 = new Date($('#currentDate').val());
      var date2 = new Date($(this).val());

      var Difference_In_Time1 = date2.getTime() - date1.getTime();
  
        var Difference_In_Days1 = Difference_In_Time1 / (1000 * 3600 * 24);

        $("#InitialInstallmentDate").datepicker("destroy");

        if(Difference_In_Days1 == 0)
        {
          var newMinDate = 1;
        }
        else
        {
          var newMinDate = Difference_In_Days1 + 1;   
        }


        $('#InitialInstallmentDate').datepicker({ 
         dateFormat: "mm/dd/yy",
          minDate: +newMinDate
        // maxDate: date
    });


     })



     $(function () {
              /* To select date*/
         var date = new Date();
         var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
         $('#FinalPaymentDate').datepicker({ 
         dateFormat: "mm/dd/yy"
        //maxDate: date
    });
    });

    $(function () {
              /* To select date*/
         var date = new Date();
         var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
         $('#DeadlineofAcceptance').datepicker({ 
         dateFormat: "mm/dd/yy"
        // maxDate: date
    });
    });
    $(function () {
              /* To select date*/
         var date = new Date();
         var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
         $('#DeadlineofAcceptance1').datepicker({ 
         dateFormat: "mm/dd/yy",
         minDate: +1
        // maxDate: date
    });
    });
   $(function () {
              /* To select date*/
         var date = new Date();
         var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
         $('#PaymentDate').datepicker({ 
         dateFormat: "mm/dd/yy",
         minDate: +1,
         maxDate: '+60d'
    });
    });
   $(function () {
              /* To select date*/
         var date = new Date();
         var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
         $('#AACACounterOfferFirstPaymentDate').datepicker({ 
         dateFormat: "mm/dd/yy",
         minDate: +1
        // maxDate: date
    });
    });
   $(function () {
              /* To select date*/
         var date = new Date();
         var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
         $('#AACACounterOfferDeadlineDate').datepicker({ 
         dateFormat: "mm/dd/yy",
        minDate: +1
    });
    });

   $(function () {
              /* To select date*/
         var date = new Date();
         var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
         $('#DateofLastRefinancing').datepicker({ 
         dateFormat: "mm/dd/yy"
        // maxDate: date
    });
    });

    $("#AACACounterOfferDeadlineDate").change(function () {
      var date1 = new Date($('#currentDate').val());
      var date2 = new Date($(this).val());

      var Difference_In_Time1 = date2.getTime() - date1.getTime();
  
        var Difference_In_Days1 = Difference_In_Time1 / (1000 * 3600 * 24);

        $("#AACACounterOfferFirstPaymentDate").datepicker("destroy");

        if(Difference_In_Days1 == 0)
        {
          var newMinDate = 1;
        }
        else
        {
          var newMinDate = Difference_In_Days1 + 1;   
        }


        $('#AACACounterOfferFirstPaymentDate').datepicker({ 
         dateFormat: "mm/dd/yy",
          minDate: +newMinDate
        // maxDate: date
    });


     })
  
   $(document).ready(function () {
     $(".fold").click(function () {
     $(".fold_p").fadeOut(function () {
            $(".fold_p").text(($(".fold_p").text() == 'Update') ? 'Lock' : 'Update').fadeIn();})
       })
       });
    </script>
    <script>

    </script>
      <script type="text/javascript">
   
         $(document).ready(function(){
           var radio_value ='<?php echo $fetchres['RENTOWN']?>';
           if(radio_value == 'R'){
           
             document.getElementById('Debtor_Rent').style.display ='block';
             document.getElementById('Debtor_Own').style.display ='none';
             document.getElementById('Debtor_Unknown').style.display ='none';
           }
           else if(radio_value == 'O'){
           
             document.getElementById('Debtor_Rent').style.display ='none';
             document.getElementById('Debtor_Own').style.display ='block';
             document.getElementById('Debtor_Unknown').style.display ='none';
           }
           else if(radio_value == 'U'){
            
             document.getElementById('Debtor_Rent').style.display ='none';
             document.getElementById('Debtor_Own').style.display ='none';
             document.getElementById('Debtor_Unknown').style.display ='block';
           }
         
           var Lump_Sum ='<?php echo $fetchres['CKBXLUMSUM']?>';
         
           if(Lump_Sum == 'True'){
             document.getElementById('lump_sum').style.display ='block';
             document.getElementById('clearBoxMsg').style.display = 'none';
           }
           else if(Lump_Sum == 'False'){
             document.getElementById('lump_sum').style.display ='none';
             document.getElementById('clearBoxMsg').style.display = 'block';
           }
         
           var Payment_Plan ='<?php echo $fetchres['CHKBXPAYPL']?>';
          
             if(Payment_Plan == 'True'){
               document.getElementById('payment_plan').style.display ='block';
               document.getElementById('clearBoxMsg').style.display = 'block';
             }
             else if(Payment_Plan == 'False'){
               document.getElementById('payment_plan').style.display ='none';
               document.getElementById('clearBoxMsg').style.display = 'none';
             }
         
      
     
     });
    
    
    
    
    function lumpSum(){
      $("#PaymentPlan").prop('checked', true);
      
      if($("#LumpSum").is(":checked")){
        $("#LumpSum").prop('disabled', true);
      }else{
        $("#LumpSum").prop('disabled', false);
      }
      
      var TotalAmountOfOffer = Number($('#TotalAmountOfOffer').val().replace("$", ""));
      var monthlyPaymentAmt = Number($('#monthlyPaymentAmt').val().replace("$", ""));
      var NumberofInstallments = Number($('#NumberofInstallments').val());
      var InitialPaymentAmount = Number($('#InitialPaymentAmount').val().replace("$", ""));

      if((TotalAmountOfOffer != '0' && monthlyPaymentAmt == '0' && NumberofInstallments == '0') || (TotalAmountOfOffer == '0' && monthlyPaymentAmt != '0' && NumberofInstallments == '0') || (TotalAmountOfOffer == '0' && monthlyPaymentAmt == '0' && NumberofInstallments != '0') || (TotalAmountOfOffer != '0' && monthlyPaymentAmt != '0' && NumberofInstallments != '0') || (TotalAmountOfOffer == '0' && monthlyPaymentAmt != '0' && NumberofInstallments != '0') || (TotalAmountOfOffer != '0' && monthlyPaymentAmt == '0' && NumberofInstallments != '0') || (TotalAmountOfOffer != '0' && monthlyPaymentAmt != '0' && NumberofInstallments == '0') )
      {
        swal({
            title: "Warning",
            text: "Do you want to proceed to another way?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#6A9944",
            confirmButtonText: "Proceed",
            cancelButtonText: "Cancel",
            closeOnConfirm: true
          }, function(isConfirm){         
            if(isConfirm){
              document.getElementById('lump_sum').style.display ='block';
              document.getElementById('payment_plan').style.display ='none';
              document.getElementById('clearBoxMsg').style.display = 'none';
              $("#LumpSum").prop('checked', true);
              $("#LumpSum").prop('disabled', true);
              $("#PaymentPlan").prop('disabled', false);
              $("#btnClear").trigger( "click" );
            }else{
              $("#PaymentPlan").prop('checked', true);
            }
          });
      }else{
        document.getElementById('lump_sum').style.display ='block';
        document.getElementById('payment_plan').style.display ='none';
        document.getElementById('clearBoxMsg').style.display = 'none';
        $("#LumpSum").prop('checked', true);
        $("#PaymentPlan").prop('checked', false);
        $("#PaymentPlan").prop('disabled', false);
        
      }
      
         
    }
         
    function paymentPlan(){
      $("#LumpSum").prop('checked', true);
      
      if($("#PaymentPlan").is(":checked")){
        $("#PaymentPlan").prop('disabled', true);
      }else{
        $("#PaymentPlan").prop('disabled', false);
      }
      
      var enterAmount = $('#txtLumSumAmt').val();
      var paymentDate = $('#PaymentDate').val();

      if(enterAmount == '' || enterAmount == '$NaN' || enterAmount == 'NaN' || enterAmount == '$0.00' || enterAmount == '0' || enterAmount == '0.00' || enterAmount == '$')
      {
        document.getElementById('payment_plan').style.display = 'block';
        document.getElementById('lump_sum').style.display = 'none';
        document.getElementById('clearBoxMsg').style.display = 'block';
        $("#LumpSum").prop('checked', false);
        $("#LumpSum").prop('disabled', false);
        $("#PaymentPlan").prop('checked', true);
      }
      else{
        swal({
            title: "Warning",
            text: "Do you want to proceed to another way?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#6A9944",
            confirmButtonText: "Proceed",
            cancelButtonText: "Cancel",
            closeOnConfirm: true
          }, function(isConfirm){         
            if(isConfirm){
              document.getElementById('payment_plan').style.display = 'block';
              document.getElementById('lump_sum').style.display = 'none';
              document.getElementById('clearBoxMsg').style.display = 'block';
              $("#PaymentPlan").prop('checked', true);
              $("#PaymentPlan").prop('disabled', true);
              $("#LumpSum").prop('disabled', false);
              $("#txtLumSumAmt").val('$' + '0.00');
              $("#PaymentDate").val(null);
            }else{
              $("#LumpSum").prop('checked', true);
            }
          });
      }
      
     
    }
    
    
    
    
    
     
     
    
         
      
         function debtorRent(){
           document.getElementById('Debtor_Rent').style.display ='block';
           document.getElementById('Debtor_Own').style.display ='none';
          
         }
         function debtorOwn(){
           document.getElementById('Debtor_Rent').style.display ='none';
           document.getElementById('Debtor_Own').style.display ='block';
          
         }
        
         
      
         $(document).ready(function(){
          var checkAACAOffr = '<?php echo $fetchres['AACACNTR']; ?>';


          if(checkAACAOffr == 'True')
          {
            $("#SubmitCounterdiv").toggle();
          }
           $("#SubmitCounterchk").click(function(){
              $("#SubmitCounterdiv").toggle();
           });
         });
         
      </script>
      <script type="text/javascript">
         $(document).ready(function(){
          var checkClientOffr = '<?php echo $fetchres['CLNTCNTR']; ?>';

          if(checkClientOffr == 'True')
          {
            $("#SubmitCounterclientdiv").toggle();
          }

           $("#SubmitCounterchkclient").click(function(){
              $("#SubmitCounterclientdiv").toggle();
           });
         });
         
   
         $(document).ready(function(){
     
      
      
      $('#btnUpdateJAmount').click(function(){
        if($('#textJudgmentAmount').prop('disabled'))
                 {
                  $('#textJudgmentAmount').prop('disabled', false)
          $('#btnUpdateJAmount').text('Lock');
                 }
                 else{
                      $('#textJudgmentAmount').prop('disabled', true)
            $('#btnUpdateJAmount').text('Update');
                   }
      });
      
      
      
      
      
      $('#btnUpdateJDatelock').click(function(){
        $('#dtofformation').prop('disabled', true);
        $('#btnUpdateJDatelock').css('display', 'none');
        $('#btnUpdateJDate').css('display', 'block');
      });
             $('#btnUpdateJDate').click(function(){ 
        
        swal({
          title: "Confirm Entry",
          text: "You are changing the judgment date (amount) from what was previously reported, do  you wish to proceed",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#6A9944",
          confirmButtonText: "Yes",
          cancelButtonText: "No",
          closeOnConfirm: true
        }, function(isConfirm){
          if (isConfirm){
            $('#btnUpdateJDatelock').css('display','block');
            $('#btnUpdateJDate').css('display','none');
            $('#dtofformation').prop('disabled', false);
            $('#textJudgmentAmount').prop('disabled', false);
            $('#btnUpdateJAmount').text('Lock');
          }else {
            $('#dtofformation').prop('disabled', true);
           
            $('#btnUpdateJDate').css('display','block');
           
          }
        }); 
        
        
             });
       
       $('#textPhone').prop('disabled', true);
             $('#btnUpdate').click(function(){
                 
        if($('#textPhone').prop('disabled'))
        {
          $('#textPhone').prop('disabled', false);
          $('#btnUpdate').text('Lock');
        }
        else{
          $('#textPhone').prop('disabled', true)
          $('#btnUpdate').text('Update');
        }
                 
                 
                 
                
                 
                 if($('#textFirmPrincipalBalance').prop('disabled'))
                 {
                  $('#textFirmPrincipalBalance').prop('disabled', false)
                 }
                 else{
                      $('#textFirmPrincipalBalance').prop('disabled', true)
                   }
                 
                 if($('#textInterestAmount').prop('disabled'))
                 {
                  $('#textInterestAmount').prop('disabled', false)
                 }
                 else{
                      $('#textInterestAmount').prop('disabled', true)
                   }
                if($('#AwardedAttorneyFees').prop('disabled'))
                 {
                  $('#AwardedAttorneyFees').prop('disabled', false)
                 }
                 else{
                      $('#AwardedAttorneyFees').prop('disabled', true)
                   }
              
                if($('#AdditionalCosts').prop('disabled'))
                 {
                  $('#AdditionalCosts').prop('disabled', false)
                 }
                 else{
                      $('#AdditionalCosts').prop('disabled', true)
                   }  
               
                 });
             });
      </script>
      <script>
         $('.btnNext').click(function(){
           $('.nav-tabs > .active').next('li').find('a').trigger('click');
         });
         
         $('.btnPrevious').click(function(){
            $('.nav-tabs > .active').prev('li').find('a').trigger('click');
         });
      </script>
      <script>
         $(function () {
             $('#datepicker').datepicker({
                 format: "mm/dd/yyyy",
                 todayHighlight: true,
                 autoclose: true
           });
         });
      </script>
      <script>
         $(document).ready(function() {
             $('#TabFirstForm').bootstrapValidator({
         
                 message: 'This value is not valid',
         
                 fields: {
                     Name: {
                         validators: {
                             notEmpty: {
                                 message: 'This field is required'
                             }
                         }
                     },
                     Phone: {
                         validators: {
                             notEmpty: {
                                 message: 'This field is required'
                             }
                         }
                     },
                     EmailAddress: {
                         validators: {
                             notEmpty: {
                                 message: 'This field is required'
                             }
                         }
                     },
                     FirmPrincipalBalance: {
                         validators: {
                             notEmpty: {
                                 message: 'This field is required'
                             }
                         }
                     },
                     InterestAmount: {
                         validators: {
                             notEmpty: {
                                 message: 'This field is required'
                             }
                         }
                     }
                 }
             });
         });

   
$(document).ready(function(){

 $("#txtFirmBal").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

  $("#txtAttorneyFees").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

  $("#txtFIRMINTAMNT").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

  $("#txtAdditionalCosts").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

 $("#textJudgmentAmount").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

 $("#txtTotDebt").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

 $("#txtTotalMorgages").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

 $("#txtTotalAuto").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

 $("#txtStudentLoans").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});



  $("#EnterInterestRate").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});


$("#TotalAmountOfOffer").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

$("#monthlyPaymentAmt").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

$("#InitialPaymentAmount").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

$("#txtLumSumAmt").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

$("#AACACounterOfferAmount").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

$("#AACACounterOfferAmountDown").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

$("#AACACounterOfferMonthlyPayment").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

$("#AACACounterOfferFinalPayment").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

$("#AmountofCounterOffer").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

$("#AmountDown").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

$("#MonthlyPaymentAmount").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

$("#FinalPaymentAmountCntr").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

$("#MonthlyPaymentAmount02").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

$("#MonthlyPaymentAmount01").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

$("#YearsLeftontheMortgage").keypress(function (e) {
    if(e.which == 46){
        if($(this).val().indexOf('.') != -1) {
            return false;
        }
    }

    if (e.which != 8 && e.which != 0 && e.which != 46 && (e.which < 48 || e.which > 57)) {
        return false;
    }
});

});

  

  $('#txtFirmBal, #txtAttorneyFees, #txtFIRMINTAMNT, #lblAACACosts, #txtAdditionalCosts, #MonthlyPaymentAmount01, #MonthlyPaymentAmount02, #AACACounterOfferAmount,#AACACounterOfferAmountDown,#AACACounterOfferMonthlyPayment,#AACACounterOfferNumberOfPayments,#AACACounterOfferFinalPayment,#textJudgmentAmount').on('click', function(){
    let count = this.value;

    if(count != ''){
      var newcount = count.replace(count, '');
      $(this).val(newcount);

      if(newcount == ''){
        this.placeholder = "$0.00";
      }
    }
    calculateTotalBal();
  });

  $('#txtLumSumAmt, #TotalAmountOfOffer, #monthlyPaymentAmt, #EnterInterestRate, #NumberofInstallments, #InitialPaymentAmount, #FinalPaymentAmount, #AddedInterest, #GrandTotal,#textJudgmentAmount').on('click', function(){
    let count1 = this.value;


    if(count1 != ''){
      var newcount = count1.replace(count1, '');
      $(this).val(newcount);

      if(newcount == ''){
        // this.placeholder = "$0.00";
        document.getElementById("txtLumSumAmt").placeholder = "$0.00";
        document.getElementById("TotalAmountOfOffer").placeholder = "$0.00";
        document.getElementById("monthlyPaymentAmt").placeholder = "$0.00";
        document.getElementById("NumberofInstallments").placeholder = "0";
        document.getElementById("EnterInterestRate").placeholder = "0.00";
        document.getElementById("InitialPaymentAmount").placeholder = "$0.00";
        document.getElementById("textJudgmentAmount").placeholder = "$0.00";
      }
    }
  });


  $('#txtFirmBal').on('change', function(){
    let text = this.value; 
   
  if (text.indexOf('$') > -1)
  {
    
    var num = text.replace("$", "");

    if(num == '')
    {
      
      $(this).val('$' + '0.00');
    }
  else
    {
        var $this = $(this);
        num = num.replace(/,/g, '');   
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 
        $this.val('$' + num);
    }
  }
  else
  {
       if(text == '')
       {
         $(this).val('$' + '0.00');
       }
       else
       {
         var $this = $(this);    
         $this.val('$' + parseFloat($this.val()).toFixed(2));
         $this.val(formatNumber($this.val()));  
       }
  }
  
       
});

$('#txtAttorneyFees').on('change', function(){
  let text = this.value; 

  if (text.indexOf('$') > -1)
  {
    
    var num = text.replace("$", "");

    if(num == '')
    {
      
      $(this).val('$' + '0.00');
    }
    else
    {
        var $this = $(this);
        num = num.replace(/,/g, '');   
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num);

        $this.val('$' + num);
    }
  }
  else
  {
       if(text == '')
       {
         $(this).val('$' + '0.00');
       }
       else
       {
         var $this = $(this);    
         $this.val('$' + parseFloat($this.val()).toFixed(2));
         $this.val(formatNumber($this.val()));  
       }
  }
       
 });

$("#txtFIRMINTAMNT").change(function() {
  let text = this.value; 

  if (text.indexOf('$') > -1)
  {
    
    var num = text.replace("$", "");

    if(num == '')
    {
      
      $(this).val('$' + '0.00');
    }
    else
    {
        var $this = $(this);
        num = num.replace(/,/g, '');   
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 

        $this.val('$' + num);
    }
  }
  else
  {
       if(text == '')
       {
         $(this).val('$' + '0.00');
       }
       else
       {
         var $this = $(this);    
         $this.val('$' + parseFloat($this.val()).toFixed(2));
         $this.val(formatNumber($this.val()));  
       }
  }
       
 });

$("#txtAdditionalCosts").change(function() {
  let text = this.value; 

  if (text.indexOf('$') > -1)
  {
    
    var num = text.replace("$", "");

    if(num == '')
    {
      
      $(this).val('$' + '0.00');
    }
    else
    {
        var $this = $(this);
        num = num.replace(/,/g, '');   
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num);

        $this.val('$' + num);
    }
  }
  else
  {
       if(text == '')
       {
         $(this).val('$' + '0.00');
       }
       else
       {
         var $this = $(this);    
         $this.val('$' + parseFloat($this.val()).toFixed(2));
         $this.val(formatNumber($this.val()));
       }
  }
       
 });

 
 $("#textJudgmentAmount").change(function() {
  let text = this.value; 

  if (text.indexOf('$') > -1)
  {
    
    var num = text.replace("$", "");

    if(num == '')
    {
      
      $(this).val('$' + '0.00');
    }
    else
    {
        var $this = $(this);
        num = num.replace(/,/g, '');   
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 

        $this.val('$' + num);
    }
  }
  else
  {
       if(text == '')
       {
         $(this).val('$' + '0.00');
       }
       else
       {
         var $this = $(this);    
         $this.val('$' + parseFloat($this.val()).toFixed(2));
         $this.val(formatNumber($this.val()));
       }
  }
       
 });


$("#txtLumSumAmt").change(function() {
    let text = this.value; 

  if (text.indexOf('$') > -1)
  {
    
    var num = text.replace("$", "");

    if(num == '')
    {
      
      $(this).val('$' + '0.00');
    }
    else
    {
        var $this = $(this); 
        num = num.replace(/,/g, '');  
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 

        $this.val('$' + num);
    }
  }
  else
  {
       if(text == '')
       {
         $(this).val('$' + '0.00');
       }
       else
       {
         var $this = $(this);    
         $this.val('$' + parseFloat($this.val()).toFixed(2));
         $this.val(formatNumber($this.val()));
       }
  }

   var totalLumSumAmt = $("#txtLumSumAmt").val().replace("$", "");
   totalLumSumAmt = totalLumSumAmt.replace(/\,/g,'');
   totalLumSumAmt = Number(totalLumSumAmt);

   var lblAACACosts = Number($('#lblAACACosts').val().replace("$", ""));
   

   var txtFirmBal = $('#txtFirmBal').val().replace("$", "");
   txtFirmBal = txtFirmBal.replace(/\,/g,'');
   txtFirmBal = Number(txtFirmBal);

   var total_balance_due = $('#total_balance_due').val().replace("$", "");
   total_balance_due = total_balance_due.replace(/\,/g,'');
   total_balance_due = Number(total_balance_due);

  

   if(totalLumSumAmt <= lblAACACosts)
    {
      swal("Warning!", "The amount entered is less than or equal to the cost processed by AACA.", "warning");
    }
 
  
   else if(totalLumSumAmt > total_balance_due)
   {
    swal("Warning!", "The amount you entered is greater than the Total Balance due.", "warning");
   }
   

  var paymentdatelumsum =  $('#PaymentDate').val();
  var deadtime = $('#deadofaccpt').val();
  
  var d = new Date();
  
  var date = new Date();
  date.setDate(date.getDate() + 60)
 
  var nowdate = (date.getMonth() + 1) + "/" + date.getDate() + "/" + date.getFullYear();    
  
  

  
  var startdate = (d.getMonth() + 1) + "/" + d.getDate() + "/" + d.getFullYear();   
  
  
  var lumpaydate = new Date(paymentdatelumsum); 
  var lumdeadtime = new Date(nowdate); 
  var lumpaymentdate = lumpaydate.getTime(); 
  var fnllumdate = lumdeadtime.getTime();

  

  if(fnllumdate > lumpaymentdate){
   
  }else{
    $('#PaymentDate').val('');
  }

  
 });
   
   
  function totalbalancechart()
    { 
      
      var gentxtFirmBal = $('#txtFirmBal').val().replace("$", "");
      gentxtFirmBal = gentxtFirmBal.replace(/\,/g,'');
      gentxtFirmBal = Number(gentxtFirmBal);
      
      
      
      var gentxtAttorneyFees = $('#txtAttorneyFees').val().replace("$", "");
      gentxtAttorneyFees = gentxtAttorneyFees.replace(/\,/g,'');
      gentxtAttorneyFees = Number(gentxtAttorneyFees);
      
      var gentxtFIRMINTAMNT = $('#txtFIRMINTAMNT').val().replace("$", "");
      gentxtFIRMINTAMNT = gentxtFIRMINTAMNT.replace(/\,/g,'');
      gentxtFIRMINTAMNT = Number(gentxtFIRMINTAMNT);
      
      var genlblAACACosts = $('#lblAACACosts').val().replace("$", "");
      genlblAACACosts = genlblAACACosts.replace(/\,/g,'');
      genlblAACACosts = Number(genlblAACACosts);
      
      var gentxtAdditionalCosts = $('#txtAdditionalCosts').val().replace("$", "");
      gentxtAdditionalCosts = gentxtAdditionalCosts.replace(/\,/g,'');
      gentxtAdditionalCosts = Number(gentxtAdditionalCosts);
      
      
      
      
      /*Highcharts.chart('totalbalance-chart', {
        navigation: {
          buttonOptions: {
            enabled: false
          }
        },
        chart: {
          width: 370,
          // height: 300,
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
          type: 'pie'
        },
        credits:{
          enabled:false,
        },
        title: {
          text: '',
        },
        tooltip: {
          pointFormat: '{series.name}: <b>{point.y}</b>'
        },
        
        accessibility: {
          point: {
            valueSuffix: '%'
          }
        },
        plotOptions: {
          series: {
            dataLabels: {
              enabled: true,
              format: '<b>{point.name}</b>'
            }
          }
        },
          
        series: [{
          
          name: 'Balance',
          colorByPoint: true,
          data: [{
            name: 'Firm Principle Balance',
            y: gentxtFirmBal,
            // sliced: true,
            // selected: true
          }, {
            name: 'Awarded Attorney Fees',
            y: gentxtAttorneyFees
          }, {
            name: 'Interest Amount',
            y: gentxtFIRMINTAMNT
          }, {
            name: 'Cost Processed By AACANet',
            y: genlblAACACosts
          }, {
            name: 'Additional Costs',
            y: gentxtAdditionalCosts
          }],
          exporting: {
            enabled: true
          }
        }]
        
      });*/
    }
  
  //use for highcart of totalBalanceDue
  var txtFirmBal1 = $("#txtFirmBal").val();
  var txtFirmBal = txtFirmBal1.replace("$", "");
  txtFirmBal = txtFirmBal.replace(/\,/g,'');


  var txtAttorneyFees1 = $("#txtAttorneyFees").val();
  var txtAttorneyFees = txtAttorneyFees1.replace("$", "");
  txtAttorneyFees = txtAttorneyFees.replace(/\,/g,'');


  var txtFIRMINTAMNT1 = $("#txtFIRMINTAMNT").val();
  var txtFIRMINTAMNT = txtFIRMINTAMNT1.replace("$", "");
  txtFIRMINTAMNT = txtFIRMINTAMNT.replace(/\,/g,'');


  var txtAdditionalCosts1 = $("#txtAdditionalCosts").val();
  var txtAdditionalCosts = txtAdditionalCosts1.replace("$", "");
  txtAdditionalCosts = txtAdditionalCosts.replace(/\,/g,'');


  var lblAACACosts1 = $("#lblAACACosts").val();
  var lblAACACosts = lblAACACosts1.replace("$", "");
  if(txtFirmBal != 0.00 || txtAttorneyFees != 0.00 || txtFIRMINTAMNT != 0.00 || txtAdditionalCosts != 0.00 || lblAACACosts != 0.00){
     totalbalancechart();
  }
  
  


function calculateTotalBal()
{
  var txtFirmBal1 = $("#txtFirmBal").val();
  var txtFirmBal = txtFirmBal1.replace("$", "");
  txtFirmBal = txtFirmBal.replace(/\,/g,'');
 

  var txtAttorneyFees1 = $("#txtAttorneyFees").val();
  var txtAttorneyFees = txtAttorneyFees1.replace("$", "");
  txtAttorneyFees = txtAttorneyFees.replace(/\,/g,'');
 

  var txtFIRMINTAMNT1 = $("#txtFIRMINTAMNT").val();
  var txtFIRMINTAMNT = txtFIRMINTAMNT1.replace("$", "");
  txtFIRMINTAMNT = txtFIRMINTAMNT.replace(/\,/g,'');
 

  var txtAdditionalCosts1 = $("#txtAdditionalCosts").val();
  var txtAdditionalCosts = txtAdditionalCosts1.replace("$", "");
  txtAdditionalCosts = txtAdditionalCosts.replace(/\,/g,'');
 

  var lblAACACosts1 = $("#lblAACACosts").val();
  var lblAACACosts = lblAACACosts1.replace("$", "");

  totalbalancechart();

 

  let total = Number(txtFirmBal)+Number(txtAttorneyFees)+Number(txtFIRMINTAMNT)+Number(txtAdditionalCosts)+Number(lblAACACosts);



  $("#total_balance_due").val('$' +parseFloat(total).toFixed(2));
  $("#total_balance_due").val(formatNumber($("#total_balance_due").val()));
  $('#totalBalDue').text('$' + parseFloat(total).toFixed(2));

  $("#totalBalDue").text(formatNumber($("#totalBalDue").text()));

  $("#totalBalancePopup").text('$' + parseFloat(total).toFixed(2));
  $("#totalBalancePopup").text(formatNumber($("#totalBalDue").text()));

}

$('.terms_of_offer').on('click', function(e) { 
  e.preventDefault();

  if(!validateCalculation())
    {
        return false;
    }

    return true;
})

function validateCalculation()
{
  var flag = true;
  var terms_of_offer_checkbox = document.getElementById('LumpSum').value;

  if(terms_of_offer_checkbox == 'True'){
    // Lump Sum validation
    var test = $('#totalBalDue').text().replace(/\s+/g, "");
    var test2 = test.replace("$", "");
    var test3 = 2*Number(test2);

    var check1 = $('#txtLumSumAmt').val().replace("$", "");
    var check = Number(check1);


  if(check > test3)
  {
       $('#txtLumSumAmtError').css('display', 'block');
       $('#txtLumSumAmtError').text("You can't submit the offer that is 200 % the amount due on account");
       flag = false;
  }
  else
  {
      $('#txtLumSumAmtError').css('display', 'none');
  }

  }
 

  if(flag)
   {
    return true;
   }else{
    return false;
   }
}


  var userType = <?php echo $_SESSION['userType'] ?>;
  if(userType == 2){
    $jugDate1 = $('#dtofformation').val();
    if($jugDate1 != ''){
      $('#textJudgmentAmount').prop('disabled', true);
      $('#txtAttorneyFees').prop('disabled', false);
      $('#btnUpdateJAmount').prop('disabled', false);
      $('#btnUpdateJAmount').text('Update');
    }else{
      $('#textJudgmentAmount').prop('disabled', true);
      $('#txtAttorneyFees').prop('disabled', true);
      $('#btnUpdateJAmount').prop('disabled', true);
      $('#btnUpdateJAmount').text('Update');
    }
  }

  

  $("#dtofformation").datepicker({
    dateFormat: "yy-mm-dd",
      maxDate: -1,
    onSelect: function(dateText, inst) {
      $(inst).val(dateText); 
      var sd = $(inst).val(dateText);
      var d = new Date();
      var strDate = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
      
      var datejudg1 = new Date(sd); 
      var datejudgcheck = new Date(strDate); 
      var mnljudgdate = datejudg1.getTime(); 
      var judgcheckdate = datejudgcheck.getTime();
      
      
      if(judgcheckdate < mnljudgdate){
        $('#judgmentdateerror').css('display', 'block')
        // /*flag = false;*/
      }else{
        $('#judgmentdateerror').css('display', 'none')
      }
      
      var UIjudgdate = $('#dtofformation').val();
      var jugAmt = $('#textJudgmentAmount').val();

      if(UIjudgdate != ''){
        if(jugAmt == '' || jugAmt == '$0.00'){
         
          $('#textJudgmentAmount').prop('disabled', false);
          $('#btnUpdateJAmount').prop('disabled', false);
          $('#btnUpdateJAmount').text('Lock');
          // flag false;
        }else{
          $('#judgmenterror').css('display', 'none');
        }
          $('#txtAttorneyFees').prop('disabled', false);
      }
    }
  });
  //Code below to avoid the classic date-picker
  $("#dtofformation").on('click', function() {
    return false;
  });
  
  
    function datejudg(){
    var flag = true;
    var UIjudgdate = $('#dtofformation').val();

    var jugAmt = $('#textJudgmentAmount').val();
    var d = new Date();
    var strDate = d.getFullYear() + "/" + (d.getMonth()+1) + "/" + d.getDate();
    
    var datejudg1 = new Date(UIjudgdate); 
    var datejudgcheck = new Date(strDate); 
    var mnljudgdate = datejudg1.getTime(); 
    var judgcheckdate = datejudgcheck.getTime();
    
    
    if(judgcheckdate < mnljudgdate){
      $('#judgmentdateerror').css('display', 'block')
      flag = false;
    }else{
      $('#judgmentdateerror').css('display', 'none')
    }
    
    if(UIjudgdate != ''){
      if(jugAmt == '' || jugAmt == '$0.00'){
        
        $('#textJudgmentAmount').prop('disabled', false);
        $('#btnUpdateJAmount').prop('disabled', false);
        $('#btnUpdateJAmount').text('Lock');
        flag = false;
      }else{
        $('#judgmenterror').css('display', 'none');
      }
        $('#txtAttorneyFees').prop('disabled', false);
    }
    
    if(flag)
    {
      return true;
    }else{
      return false;
    }
    
  };
    
    
    


function validateGeneralTab()
{
  var flag = true;
  var userType = <?php echo $_SESSION['userType']; ?>

  if(userType == 2){
   var txtFirmBal=$('#txtFirmBal').val();
   var hdntxtFirmBal=$('#hdntxtFirmBal').val();
  if(txtFirmBal=='$0.00' && hdntxtFirmBal==''){
      $('#txtFirmBal').focus();
      $('#txtFirmBal').val('');
      flag = false;
      
    }
    if(txtFirmBal=='' && hdntxtFirmBal==''){
      $('#txtFirmBal').focus();
      flag = false;
    }
    $('#txtFirmBal').on('keyup', function(){
      var txtFirmBal=this.value;
      var  hdntxtFirmBal=$('#hdntxtFirmBal').val(txtFirmBal);
      
    })
 
    if($('#textName').val() == '')
    {
       $('#textNameError').css('display', 'block');
       $('#textNameError').text("This field is required");
       flag = false;
    }
    else
    {
    $('#textNameError').css('display', 'none');
    }

    $('#textName').on('keyup', function(){
     if(this.value == '')
       {
        $('#textNameError').css('display', 'block');
        $('#textNameError').text("This field is required"); 
       }
       else
       {
        $('#textNameError').css('display', 'none');
       }

    });

    if($('#textEmailAddress').val() == '')
    {
     $('#textEmailAddressError').css('display', 'block');
       $('#textEmailAddressError').text("This field is required");
       flag = false;
    }
    else
    {
     $('#textEmailAddressError').css('display', 'none');
    }

    $('#textEmailAddress').on('keyup', function(){
     if(this.value == '')
       {
        $('#textEmailAddressError').css('display', 'block');
        $('#textEmailAddressError').text("This field is required"); 
       }
       else
       {
        $('#textEmailAddressError').css('display', 'none');
       }

    });

    if($('#textVerifyEmailAddress').val() != $('#textEmailAddress').val())
    {
     $('#textVerifyEmailAddressError').css('display', 'block');
       $('#textVerifyEmailAddressError').text("Please verify email address");
       flag = false;
    }
    else
    {
     $('#textVerifyEmailAddressError').css('display', 'none');  
    }

    $('#textVerifyEmailAddress').on('keyup', function(){
     if(this.value != $('#textEmailAddress').val())
       {
        $('#textVerifyEmailAddressError').css('display', 'block');
        $('#textVerifyEmailAddressError').text("Please verify email address"); 
       }
       else
       {
        $('#textVerifyEmailAddressError').css('display', 'none');
       }

    });

    if($('#txtPhoneNumber').val() == '')
    {
     $('#txtPhoneNumberError').css('display', 'block');
       $('#txtPhoneNumberError').text("This field is required");
       flag = false;
    }
    else
    {
     $('#txtPhoneNumberError').css('display', 'none');
    }

    $('#txtPhoneNumber').on('keyup', function(){
      if(this.value == '')
      {
        $('#txtPhoneNumberError').css('display', 'block');
        $('#txtPhoneNumberError').text("This field is required"); 
      }
      else
      {
        $('#txtPhoneNumberError').css('display', 'none');
      }
    });
    
   
    var jugAmt = $('#textJudgmentAmount').val();
   
    
    
    
    
    var UIjug = $('#dtofformation').val();
    if(UIjug != ''){
      if(jugAmt == '' || jugAmt == '$0.00'){
        $('#judgmenterror').css('display', 'block');
        $('#judgmenterror').text("This field is required");
        $('#textJudgmentAmount').prop('disabled', false);
        $('#btnUpdateJAmount').text('Lock');
        flag = false;
      }else{
        $('#judgmenterror').css('display', 'none');
      }
      $('#txtAttorneyFees').prop('disabled', false);
    }
    
    $('#textJudgmentAmount').on('keyup', function(){
      if(this.value == '')
      {
        $('#judgmenterror').css('display', 'block');
        $('#judgmenterror').text("This field is required");
      }
      else
      {
        $('#judgmenterror').css('display', 'none');
      }
    });
    

  }



  
  if(flag)
   {
    return true;
   }else{
    return false;
   }
}

var userType = <?php echo $_SESSION['userType']; ?>

if(userType == 2){
$('.Guide02').on('click', function() { 
  if(!validateGeneralTab())
    {
        return false;
    }
  
  if(!datejudg())
  {
    return false;
  }

    return true;
})

$('.Guide03').on('click', function() { 
  if(!validateGeneralTab())
    {
        return false;
    }

  if(!datejudg())
  {
    return false;
  }
  
  if(!consumerChkBox())
  {
    return false;
  }

    return true;
})

$('.genNxt').on('click', function() { 
  if(!validateGeneralTab())
    {
        return false;
    }

    return true;
})

$('.submitGen').on('click', function(e) { 
  e.preventDefault();
  if(!validateGeneralTab())
    {
        return false;
    }

    return true;
})
}
       
      var a = 1;

      $('#btnClear').click(function(e){  
       e.preventDefault();
       $('#TotalAmountOfOffer').val('0.00');
       $('#monthlyPaymentAmt').val('0.00');
       $('#EnterInterestRate').val('0.00');
       $('#NumberofInstallments').val('0');
       $('#InitialPaymentAmount').val('0.00');
       $('#FinalPaymentAmount').val('0.00');
       $('#AddedInterest').val('0.00');
       $('#GrandTotal').val('0.00');
       $('#InitialInstallmentDate').val('');
       $('#FinalPaymentDate').val('');
      

       $('#btnSubmit02').prop('disabled', false);
       $('#CalcErr').css('display', 'none');

       $('#NumberofInstallments').prop('readonly', false);
       $('#EnterInterestRate').prop('readonly', false);
       $('#InitialPaymentAmount').prop('readonly', false);
       $('#InitialInstallmentDate').prop('readonly', false);
       $('#monthlyPaymentAmt').prop('readonly', false);
       $('#TotalAmountOfOffer').prop('readonly', false);
    
    $('#TotalAmountOfOffer').prop('disabled', false);
      $('#monthlyPaymentAmt').prop('disabled', false);
      $('#NumberofInstallments').prop('disabled', false);
      $('#EnterInterestRate').prop('disabled', false);
      $('#InitialPaymentAmount').prop('disabled', false);
      $('#InitialInstallmentDate').prop('disabled', false);
      $('#FinalPaymentAmountError').css('display', 'none');

     // intrestchart();

     
        if(a > 1)
        {
        a = 1;
        }

      
      });

       var b = 1;
      
      $("#btnReset01").click(function (e) {
         e.preventDefault();
         $('#AACACounterOfferAmount').val('$0.00');
         $('#AACACounterOfferAmountDown').val('$0.00');
         $('#AACACounterOfferMonthlyPayment').val('$0.00');
         $('#AACACounterOfferNumberOfPayments').val('0');
         $('#AACACounterOfferFinalPayment').val('$0.00');
         $('#AACACounterOfferDeadlineDate').val('');
         $('#AACACounterOfferFirstPaymentDate').val('');
         $('#btnSubmit03').prop('disabled', false);
         $('#btnSubmit01').prop('disabled', false);
          $('#AACACounterOfferFinalPaymentError').css('display', 'none');



         
         $('#warningErr').css('display', 'none');

         if(b > 1)
         {
          b = 1;
         }
      })
      
       var c = 1;

      $("#btnReset02").click(function (e) {
        e.preventDefault();
         // $('#SubmitCounterchkclient').prop('checked', true);
         $('#AmountofCounterOffer').val('0.00');
         $('#AmountDown').val('0.00');
         $('#MonthlyPaymentAmount').val('0.00');
         $('#NumberofPayments').val('0');
         $('#FinalPaymentAmountCntr').val('0.00');
         $('#DeadlineofAcceptance1').val('');
         $('#FirstPaymentDue').val('');
         $('#btnSubmit03').prop('disabled', false);
         $('#warningErr02').css('display', 'none');

         if(c > 1)
         {
          c = 1;
         }
      })

      $("#AACACounterOfferAmount").change(function() {
        let text = this.value;
        if (text.indexOf('$') > -1){
    
    var num = text.replace("$", "");

    if(num == '')
    {
      
      $(this).val('$' + '0.00');
    }
    else
    {
        var $this = $(this); 
        num = num.replace(/,/g, '');  
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 

        $this.val('$' + num);
    }
  }
  else
  {
       if(text == '')
       {
         $(this).val('$' + '0.00');
       }
       else
       {
         var $this = $(this);    
         $this.val('$' + parseFloat($this.val()).toFixed(2));
         $this.val(formatNumber($this.val()));
       }
  }
      });

      $("#AACACounterOfferAmountDown").change(function() {
        let text = this.value;
        if (text.indexOf('$') > -1){
    
    var num = text.replace("$", "");

    if(num == '')
    {
      
      $(this).val('$' + '0.00');
    }
    else
    {
        var $this = $(this); 
        num = num.replace(/,/g, '');  
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 

        $this.val('$' + num);
    }
  }
  else
  {
       if(text == '')
       {
         $(this).val('$' + '0.00');
       }
       else
       {
         var $this = $(this);    
         $this.val('$' + parseFloat($this.val()).toFixed(2));
         $this.val(formatNumber($this.val()));
       }
  }

      
      });

      $("#AACACounterOfferMonthlyPayment").change(function() {
        let text = this.value;
        if (text.indexOf('$') > -1){
    
    var num = text.replace("$", "");

    if(num == '')
    {
      
      $(this).val('$' + '0.00');
    }
    else
    {
        var $this = $(this); 
        num = num.replace(/,/g, '');  
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 

        $this.val('$' + num);
    }
  }
  else
  {
       if(text == '')
       {
         $(this).val('$' + '0.00');
       }
       else
       {
         var $this = $(this);    
         $this.val('$' + parseFloat($this.val()).toFixed(2));
         $this.val(formatNumber($this.val()));
       }
  }

        
      });

      

      $("#AACACounterOfferFinalPayment").change(function() {
        let text = this.value;
        if (text.indexOf('$') > -1){
    
    var num = text.replace("$", "");

    if(num == '')
    {
      
      $(this).val('$' + '0.00');
    }
    else
    {
        var $this = $(this); 
        num = num.replace(/,/g, '');  
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 

        $this.val('$' + num);
    }
  }
  else
  {
       if(text == '')
       {
         $(this).val('$' + '0.00');
       }
       else
       {
         var $this = $(this);    
         $this.val('$' + parseFloat($this.val()).toFixed(2));
         $this.val(formatNumber($this.val()));
       }
  }

        
      });

      $("#btnSubmit01").click(function (e) {
        // alert("Hello");
        e.preventDefault();
         calcCalculation();
      })






    function validateAACAcounterCalc()
     {
        var flag = true;
        
       var AACACounterOfferAmount = Number($('#TotalAmountOfOffer').val());
        var AACACounterOfferMonthlyPayment = Number($('#monthlyPaymentAmt').val());
        var AACACounterOfferNumberOfPayments = Number($('#NumberofInstallments').val());
        var AACACounterOfferAmountDown = Number($('#InitialPaymentAmount').val());

    var AACAOfferAmount = $('#TotalAmountOfOffer').val();
    AACAOfferAmount = AACAOfferAmount.replace(/\,/g,'');
    AACAOfferAmount = parseInt(AACAOfferAmount,10);
    
    var AACAOfferMonthlyPayment = $('#monthlyPaymentAmt').val();
    AACAOfferMonthlyPayment = AACAOfferMonthlyPayment.replace(/\,/g,'');
    AACAOfferMonthlyPayment = parseInt(AACAOfferMonthlyPayment,10);
    

        if((AACACounterOfferAmount == '0' && AACACounterOfferMonthlyPayment == '0' && AACACounterOfferNumberOfPayments == '0') || (AACACounterOfferAmount != '0' && AACACounterOfferMonthlyPayment == '0' && AACACounterOfferNumberOfPayments == '0') || (AACACounterOfferAmount == '0' && AACACounterOfferMonthlyPayment != '0' && AACACounterOfferNumberOfPayments == '0') || (AACACounterOfferAmount == '0' && AACACounterOfferMonthlyPayment != '0' && AACACounterOfferNumberOfPayments == '0') || (AACACounterOfferAmount == '0' && AACACounterOfferMonthlyPayment == '0' && AACACounterOfferNumberOfPayments != '0') || (AACACounterOfferAmount == '' && AACACounterOfferMonthlyPayment == '' && AACACounterOfferNumberOfPayments == '') || (AACACounterOfferAmount != '' && AACACounterOfferMonthlyPayment == '' && AACACounterOfferNumberOfPayments == '') || (AACACounterOfferAmount == '' && AACACounterOfferMonthlyPayment != '' && AACACounterOfferNumberOfPayments == '') || (AACACounterOfferAmount == '' && AACACounterOfferMonthlyPayment == '' && AACACounterOfferNumberOfPayments != ''))
        {
          swal("Error!", "You must complete the terms of the offer and calculate in the section below to the right in order to submit this counter.", "error");
          flag = false;
        }

        
          if(AACAOfferAmount < AACAOfferMonthlyPayment)
          {
            swal("Error!", "Unable to calculate, the Total Amount of Offer is less than the sum to all installment payments.", "error");
            flag = false;
          }
        


        if(flag)
         {
          return true;
         }else{
          return false;
         }
      }

      function validateAACAcounterCalc2()
     {
        var flag = true;
        
        var AACACounterOfferAmount = Number($('#AACACounterOfferAmount').val());
        var AACACounterOfferMonthlyPayment = Number($('#AACACounterOfferMonthlyPayment').val());
        var AACACounterOfferNumberOfPayments = Number($('#AACACounterOfferNumberOfPayments').val());
        var AACACounterOfferAmountDown = Number($('#AACACounterOfferAmountDown').val());

    var AACAOfferAmount = $('#AACACounterOfferAmount').val();
    AACAOfferAmount = AACAOfferAmount.replace(/\,/g,'');
    AACAOfferAmount = parseInt(AACAOfferAmount,10);
    
    var AACAOfferMonthlyPayment = $('#AACACounterOfferMonthlyPayment').val();
    AACAOfferMonthlyPayment = AACAOfferMonthlyPayment.replace(/\,/g,'');
    AACAOfferMonthlyPayment = parseInt(AACAOfferMonthlyPayment,10);
    

        if((AACACounterOfferAmount == '0' && AACACounterOfferMonthlyPayment == '0' && AACACounterOfferNumberOfPayments == '0') || (AACACounterOfferAmount != '0' && AACACounterOfferMonthlyPayment == '0' && AACACounterOfferNumberOfPayments == '0') || (AACACounterOfferAmount == '0' && AACACounterOfferMonthlyPayment != '0' && AACACounterOfferNumberOfPayments == '0') || (AACACounterOfferAmount == '0' && AACACounterOfferMonthlyPayment != '0' && AACACounterOfferNumberOfPayments == '0') || (AACACounterOfferAmount == '0' && AACACounterOfferMonthlyPayment == '0' && AACACounterOfferNumberOfPayments != '0') || (AACACounterOfferAmount == '' && AACACounterOfferMonthlyPayment == '' && AACACounterOfferNumberOfPayments == '') || (AACACounterOfferAmount != '' && AACACounterOfferMonthlyPayment == '' && AACACounterOfferNumberOfPayments == '') || (AACACounterOfferAmount == '' && AACACounterOfferMonthlyPayment != '' && AACACounterOfferNumberOfPayments == '') || (AACACounterOfferAmount == '' && AACACounterOfferMonthlyPayment == '' && AACACounterOfferNumberOfPayments != ''))
        {
          swal("Error!", "You must complete the terms of the offer and calculate in the section below to the right in order to submit this counter.", "error");
          flag = false;
        }

        
          if(AACAOfferAmount < AACAOfferMonthlyPayment)
          {
            swal("Error!", "Unable to calculate, the Total Amount of Offer is less than the sum to all installment payments.", "error");
            flag = false;
          }
        


        if(flag)
         {
          return true;
         }else{
          return false;
         }
      }


    

      $("#AmountofCounterOffer").change(function() {
        let text = this.value;

        if(text == '')
        {
         $(this).val('0.00');
        }else
        {
           var $this = $(this); 
           text = text.replace(/,/g, '');    
         text = Number(text);
         text = text.toFixed(2);
         text = formatNumber(text); 

         $this.val(text);
        }
      });

      $("#AmountDown").change(function() {
        let text = this.value;

        if(text == '')
        {
         $(this).val('0.00');
        }else
        {
           var $this = $(this); 
           text = text.replace(/,/g, '');    
         text = Number(text);
         text = text.toFixed(2);
         text = formatNumber(text); 

         $this.val(text);
        }
      });

      $("#MonthlyPaymentAmount").change(function() {
        let text = this.value;

        if(text == '')
        {
         $(this).val('0.00');
        }else
        {
           var $this = $(this); 
           text = text.replace(/,/g, '');    
         text = Number(text);
         text = text.toFixed(2);
         text = formatNumber(text); 

         $this.val(text);
        }
      });

      
      $("#FinalPaymentAmountCntr").change(function() {
        let text = this.value;

        if(text == '')
        {
         $(this).val('0.00');
        }else
        {
           var $this = $(this); 
           text = text.replace(/,/g, '');    
         text = Number(text);
         text = text.toFixed(2);
         text = formatNumber(text); 

         $this.val(text);
        }
      });

      $("#btnSubmit03").click(function (e) {
        // alert("Hello");
        e.preventDefault();
         calcCalculation();
      })





      

 //On change of Total Amount Offer 
  $('#TotalAmountOfOffer').on('change', function(){
      let text = this.value; 

    if (text.indexOf('$') > -1)
    {
      
      var num = text.replace("$", "");

      if(num == '')
      {
        
        $(this).val('$' + '0.00');
      }
      else
      {
        var $this = $(this);   
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 

          $this.val('$' + num);

          
          
      }
    }
    else
    {
         if(text == '')
         {
           $(this).val('$' + '0.00');
         }
         else
         {
           var $this = $(this);    
           $this.val('$' + parseFloat($this.val()).toFixed(2));

           $this.val(formatNumber($this.val()));
         }
    }

      var amountOffer = this.value.replace("$", "");
      amountOffer = amountOffer.replace(/\,/g,'');
      amountOffer = Number(amountOffer);

      var pPlanPerc = '<?php echo $pPlanPerc; ?>';
      
      var txtFirmBal = $('#total_balance_due').val().replace("$", "");
      txtFirmBal = txtFirmBal.replace(/\,/g,'');
      txtFirmBal = Number(txtFirmBal);
 

        if(pPlanPerc != '')
        {
           var percent = amountOffer/txtFirmBal*100;

           if(percent <= pPlanPerc)
           {
              swal("Warning!", "The offer is not within the blanket authority and will be sent to the client for further review.", "warning");
           }
        }
         
  });


  //On change of Monthly Payment Amount 
  $('#monthlyPaymentAmt').on('change', function(){
      let text = this.value; 
      

    if (text.indexOf('$') > -1)
    {
      
      var num = text.replace("$", "");

      if(num == '')
      {
        
        $(this).val('$' + '0.00');
      }
      else
      {
        var $this = $(this);    
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 

        $this.val('$' + num);
      }
    }
    else
    {
         if(text == '')
         {
           $(this).val('$' + '0.00');
         }
         else
         {
           var $this = $(this);    
           $this.val('$' + parseFloat($this.val()).toFixed(2));
           $this.val(formatNumber($this.val()));
         }
    }

      var monthlyPayment = this.value.replace("$", "");
      monthlyPayment = monthlyPayment.replace(/\,/g,'');
      monthlyPayment = Number(monthlyPayment);
         
      var pifMonthPymt = '<?php echo $pifMonthPymt; ?>';
     
      var txtFirmBal = $('#total_balance_due').val().replace("$", "");
      txtFirmBal = txtFirmBal.replace(/\,/g,'');
      txtFirmBal = Number(txtFirmBal);


      var amountOffer = $('#TotalAmountOfOffer').val().replace("$", "");
      amountOffer = amountOffer.replace(/\,/g,'');
      amountOffer = Number(amountOffer);

      if(amountOffer != '')
      {
      if(amountOffer >= txtFirmBal)
      {
      if(pifMonthPymt != '')
      {
        if(monthlyPayment < pifMonthPymt)
       {
        swal("Warning!", "The number of payments exceeds the blanket authority and will be sent to the client for further review.", "warning");
       }
      }
      }
    
      }
         
  });



  //On change of Initial Payment Amount 
  $('#InitialPaymentAmount').on('change', function(){
      let text = this.value; 

    if (text.indexOf('$') > -1)
    {
      
      var num = text.replace("$", "");

      if(num == '')
      {
        
        $(this).val('$' + '0.00');
      }
      else
      {
        var $this = $(this);    
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 

          $this.val('$' + num);
      }
    }
    else
    {
         if(text == '')
         {
           $(this).val('$' + '0.00');
         }
         else
         {
           var $this = $(this);    
           $this.val('$' + parseFloat($this.val()).toFixed(2));
           $this.val(formatNumber($this.val()));  
         }
    }
         
  });



  //On change of Total Unsecured Debt
  $('#txtTotDebt').on('change', function(){
      let text = this.value; 

    if (text.indexOf('$') > -1)
    {
      
      var num = text.replace("$", "");

      if(num == '')
      {
        
        $(this).val('0.00');
      }
      else
      {
        var $this = $(this);
        num = num.replace(/,/g, '');    
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 

          $this.val(num);
      }
    }
    else
    {
         if(text == '')
         {
           $(this).val('0.00');
         }
         else
         {
           var $this = $(this); 
           text = text.replace(/,/g, '');    
         text = Number(text);
         text = text.toFixed(2);
         text = formatNumber(text); 

          $this.val(text);
         }
    }
         
  });


  //On change of Total Mortgages
  $('#txtTotalMorgages').on('change', function(){
      let text = this.value; 

    if (text.indexOf('$') > -1)
    {
      
      var num = text.replace("$", "");

      if(num == '')
      {
        
        $(this).val('0.00');
      }
      else
      {
        var $this = $(this);
        num = num.replace(/,/g, '');    
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 

          $this.val(num);
      }
    }
    else
    {
         if(text == '')
         {
           $(this).val('0.00');
         }
         else
         {
           var $this = $(this);    
           $this.val(parseFloat($this.val()).toFixed(2));
           $this.val(formatNumber($this.val()));  
         }
    }
         
  });


  //On change of Total Auto Loans
  $('#txtTotalAuto').on('change', function(){
      let text = this.value; 

    if (text.indexOf('$') > -1)
    {
      
      var num = text.replace("$", "");

      if(num == '')
      {
        
        $(this).val('0.00');
      }
      else
      {
        var $this = $(this);
        num = num.replace(/,/g, '');    
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 

          $this.val(num);
      }
    }
    else
    {
         if(text == '')
         {
           $(this).val('0.00');
         }
         else
         {
           var $this = $(this);    
           $this.val(parseFloat($this.val()).toFixed(2));
           $this.val(formatNumber($this.val()));  
         }
    }
         
  });



  //On change of Total Student Loans
  $('#txtStudentLoans').on('change', function(){
      let text = this.value; 

    if (text.indexOf('$') > -1)
    {
      
      var num = text.replace("$", "");

      if(num == '')
      {
        
        $(this).val('0.00');
      }
      else
      {
        var $this = $(this);  
        num = num.replace(/,/g, '');  
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 

          $this.val(num);
      }
    }
    else
    {
         if(text == '')
         {
           $(this).val('0.00');
         }
         else
         {
           var $this = $(this);    
           $this.val(parseFloat($this.val()).toFixed(2));
           $this.val(formatNumber($this.val()));  
         }
    }
         
  });


  //On change of Monthly Payment Amount {Status of Consumer's Residence for Rent}
  $('#MonthlyPaymentAmount01').on('change', function(){
      let text = this.value; 

    if (text.indexOf('$') > -1)
    {
      
      var num = text.replace("$", "");

      if(num == '')
      {
        
        $(this).val('$' + '0.00');
      }
      else
      {
        var $this = $(this);
        num = num.replace(/,/g, '');      
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 

        $this.val('$' + num);
      }
    }
    else
    {
         if(text == '')
         {
           $(this).val('$' + '0.00');
         }
         else
         {
           var $this = $(this);    
           $this.val('$' + parseFloat($this.val()).toFixed(2));
           $this.val(formatNumber($this.val()));
         }
    }
         
  });


  //On change of Monthly Payment Amount {Status of Consumer's Residence for Own}
  $('#MonthlyPaymentAmount02').on('change', function(){
      let text = this.value; 

    if (text.indexOf('$') > -1)
    {
      
      var num = text.replace("$", "");

      if(num == '')
      { 
        
        $(this).val('$' + '0.00');
      }
      else
      {
        var $this = $(this); 
        num = num.replace(/,/g, '');     
        num = Number(num);
        num = num.toFixed(2);
        num = formatNumber(num); 

        $this.val('$' + num);
      }
    }
    else
    {
         if(text == '')
         {
           $(this).val('$' + '0.00');
         }
         else
         {
           var $this = $(this);    
           $this.val('$' + parseFloat($this.val()).toFixed(2));
           $this.val(formatNumber($this.val()));
         }
    }
         
  });


 

      function add_months(dt, n) 
      {
       return new Date(dt.setMonth(dt.getMonth() + n));      
      }

      function formatNumber (num) {
       return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
      }
    
    $(document).ready(function(){
      var totalchx = $('#TotalAmountOfOffer').val();
      var monthlychx = $('#TotalAmountOfOffer').val();
      var numinstallmentchx = $('#TotalAmountOfOffer').val();
      
      if($('#PaymentPlan').is(':checked')){
        if(totalchx != '' && monthlychx != '' && numinstallmentchx != '')
        {
          $('#btnSubmit02').prop('disabled', true);
        }else{
          $('#btnSubmit02').prop('disabled', false);
        } 
      }
    });
    
  
      
      $("#btnSubmit02").click(function (e) {
        e.preventDefault();
         calcPaymentPlan();
      })

   
    function emi_calculator(p, r, t)
    {
      let emi;
      
      r = r / (12 * 100);
      t = t; 
      emi = (p * r * Math.pow(1 + r, t)) / (Math.pow(1 + r, t) - 1);
      
      return (emi);
    }
    
    
    function tenure_calculator(m, p, r)
    {
      let tenure;
      
      m = m;
      r = r / (12 * 100);
      // console.log(r);
      tenure = (Math.log(m) - Math.log(m - (p * r))) / Math.log(1 + r)

      return (tenure);
    }
    
    
    function loan_amount_calculator(m, r, t)
    {
      let loanamount;
      
      r = r / (12 * 100);
      t = t;
      loanamount = m * (Math.pow(1 + r, t) - 1) / (Math.pow(1 + r, t) * r);

      return (loanamount);
    }
    
    
    function finalpayment_date(i, t)
    {
      let initialInstallmentDate;
      
      initialInstallmentDate = i;
      time = t;
      if(initialInstallmentDate != '')
      {
        var initialDate = $('#InitialInstallmentDate').val();

        var dt = new Date(initialDate);
        
        var m = Number(time);
        // console.log(m);
        if(m == '')
        {
        m = 0;
        }
        else
        {
        m = m - 1;
        }
        
        

        dt.setMonth( dt.getMonth() + m );

        var today = new Date(dt);
        
       

        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
      
       
        
        var nDate = mm + '/' + dd + '/' + yyyy;
        $('#FinalPaymentDate').val(nDate);
      }
    }
    
    
    
    
    
    
    
    
    function calcPaymentPlan()
        {
         

         if(!validateCalcPaymentPlan())
         {
          // alert("te");
         return false;
         }

         if(!validateNumberCount())
         {
          // alert("ui");
          return false;
         }

         var amount = $('#TotalAmountOfOffer').val().replace("$", "");
        
         amount = amount.replace(/\,/g,'');
         amount = Number(amount);
          
         var interest = Number($('#EnterInterestRate').val());
         var rate = interest / 100;
         var monthly = $('#monthlyPaymentAmt').val().replace("$", "");

         monthly = monthly.replace(/\,/g,'');
         monthly = Number(monthly);

         var installments = Number($('#NumberofInstallments').val());
         var InitialPaymentAmount = $('#InitialPaymentAmount').val().replace("$", "");

         InitialPaymentAmount = InitialPaymentAmount.replace(/\,/g,'');
         InitialPaymentAmount = Number(InitialPaymentAmount);

         if(interest == '')
         {
          interest = 0;
         }
         if(installments == '')
         {
          installments = 0;
         }
         

          var initialInstallmentDate = $('#InitialInstallmentDate').val();

          if(initialInstallmentDate == '')
          {
            var deadofaccpt = $('#deadofaccpt').val();
            
            
            
            var dt = new Date(deadofaccpt);
            

            dt.setMonth( dt.getMonth() + 1 );
            
            var today = new Date(dt);
            
            // alert(newDate+'---'+today+'---'+dt);

            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            var nDate = mm + '/' + dd + '/' + yyyy;


            $('#InitialInstallmentDate').val(nDate);  

              var initialDate = $('#InitialInstallmentDate').val();

              var dt = new Date(initialDate);
              
              var m = Number($('#NumberofInstallments').val());

              var initial = InitialPaymentAmount;

              

              if(m == 0 || m == '')
              {
                if(initial == '' || initial == 0){
                var x = Number($('#TotalAmountOfOffer').val().replace("$", ""))/Number($('#monthlyPaymentAmt').val().replace("$", ""));

                var x = Math.round(x);
                $('#NumberofInstallments').val(x)
               }
               else
               {
                   var subtract = amount - initial;
                   
                   var divide = subtract/monthly;
                   var reminder = subtract%monthly;
                   var roundValue = Math.round(divide);
                   

                   $('#NumberofInstallments').val(roundValue);

               }

              }

              

              m = Number($('#NumberofInstallments').val()) - 1;


                dt.setMonth( dt.getMonth() + m );
            
              var today = new Date(dt);
              
              // alert(newDate+'---'+today+'---'+dt);

              var dd = String(today.getDate()).padStart(2, '0');
              var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
              var yyyy = today.getFullYear();

              var nDate = mm + '/' + dd + '/' + yyyy;
                $('#FinalPaymentDate').val(nDate);

          }

      

          if(amount != 0 && interest  != 0 && monthly != 0 &&  installments != 0 && InitialPaymentAmount != 0)
          {
            
             amount = amount - InitialPaymentAmount;
             let interestCount = interestRateCalc(amount, rate, monthly, installments);

             var multiplication = monthly * installments;
             var subraction = amount - multiplication;
             var total = subraction + monthly;

             var finalPayment0 = Number(total) + Math.round(interestCount * 100) / 100;
             // finalPayment0 = finalPayment0.toFixed(2);
             var grandTotal0 = Number(amount) + Math.round(interestCount * 100) / 100 + InitialPaymentAmount;
             
              
              document.getElementById('FinalPaymentAmount').value = '$'+formatNumber(finalPayment0.toFixed(2));
              document.getElementById('GrandTotal').value = '$'+formatNumber(grandTotal0.toFixed(2));
              document.getElementById('AddedInterest').value = '$'+formatNumber(interestCount.toFixed(2));
              document.getElementById('FinalPaymentAmountnotdisabled').value = '$'+formatNumber(finalPayment0.toFixed(2));
             
              var txtFirmBal = Number($('#txtFirmBal').val().replace("$", ""));

              var balType = '<?php echo $balanceType; ?>';

              if(balType == 'prin')
              {
                var txtFirmBal = txtFirmBal;
                var txt = 'priciple';
              }
              else
              {
                var txtFirmBal = Number($('#total_balance_due').val().replace("$", ""));
                var txt = 'total';
              }

             
              

              if(Number($('#AddedInterest').val().replace("$", "")) > txtFirmBal)
              {
                  swal("Warning!", "The amount of interest accumulated is more than the "+ txt +" balance.", "warning");
              }
          }



          if(amount != 0 && interest  != 0 && monthly != 0 &&  installments != 0 && InitialPaymentAmount == 0)
          {
              
             let interestCount = interestRateCalc(amount, rate, monthly, installments);

             var multiplication = monthly * installments;
             var subraction = amount - multiplication;
             var total = subraction + monthly;

            var finalPayment1 = Number(total) + Math.round(interestCount * 100) / 100;
            // finalPayment1 = finalPayment1.toFixed(2);
            var grandTotal1 = Number(amount) + Math.round(interestCount * 100) / 100;
            grandTotal1 = grandTotal1.toFixed(2);
            
 
             
              document.getElementById('FinalPaymentAmount').value = '$'+formatNumber(finalPayment1.toFixed(2));
              document.getElementById('GrandTotal').value = '$'+formatNumber(grandTotal1);
              document.getElementById('AddedInterest').value = "$"+formatNumber(interestCount.toFixed(2));
              document.getElementById('FinalPaymentAmountnotdisabled').value = '$'+formatNumber(finalPayment1.toFixed(2));

              var txtFirmBal = Number($('#txtFirmBal').val().replace("$", ""));
              var balType = '<?php echo $balanceType; ?>';

              if(balType == 'prin')
              {
                var txtFirmBal = txtFirmBal;
                var txt = 'priciple';
              }
              else
              {
                var txtFirmBal = Number($('#total_balance_due').val().replace("$", ""));
                var txt = 'total';
              }

             
              

              if(Number($('#AddedInterest').val().replace("$", "")) > txtFirmBal)
              {
                  swal("Warning!", "The amount of interest accumulated is more than the "+ txt +" balance.", "warning");
              }

          }



          if(amount != 0 && interest  == 0 && monthly != 0 &&  installments != 0 && InitialPaymentAmount != 0)
          {

            var subraction = amount - InitialPaymentAmount;
            var multiplication = monthly*installments;

            var total = subraction - multiplication + monthly;
            $('#FinalPaymentAmount').val('$'+formatNumber(total.toFixed(2)));
            $('#GrandTotal').val('$'+formatNumber(amount.toFixed(2)));
            
            $('#AddedInterest').val('$'+'0.00');
            $('#FinalPaymentAmountnotdisabled').val('$'+formatNumber(total.toFixed(2)));
          }



          if(amount != 0 && interest  == 0 && monthly != 0 &&  installments != 0 && InitialPaymentAmount == 0)
          {
            
             var multiplication = monthly * installments;
             var subraction = amount - multiplication;
             var total = subraction + monthly;


             $('#FinalPaymentAmount').val('$'+formatNumber(total.toFixed(2)));
             $('#GrandTotal').val('$'+formatNumber(amount.toFixed(2)));
             $('#AddedInterest').val('$'+'0.00');
             $('#FinalPaymentAmountnotdisabled').val('$'+formatNumber(total.toFixed(2)));
              
          }



          
      
      
      if(amount == 0 && interest  != 0 && monthly != 0 &&  installments != 0 && InitialPaymentAmount != 0)
      {
        if(InitialPaymentAmount != 0 || InitialPaymentAmount != ''){
          var adddown = InitialPaymentAmount;
        }else{
          var adddown = InitialPaymentAmount;
        }
        
        
        let Irate, time, totalloanamount;
        Irate = interest;
        time = installments;
        
        totalloanamount = loan_amount_calculator(monthly, Irate, time);
        var newloanamount = totalloanamount + adddown;
        
        
        var QueGrandTotal = time * monthly;
        var GrandTotal1 = QueGrandTotal;
        var GrandTotal = QueGrandTotal + adddown;


        var payIntrest = GrandTotal1 - totalloanamount;

        var finalpayment = monthly;
        
    
        
        
        document.getElementById('TotalAmountOfOffer').value = '$'+formatNumber(newloanamount.toFixed(2));
        document.getElementById('FinalPaymentAmount').value = '$'+formatNumber(finalpayment.toFixed(2));
        document.getElementById('GrandTotal').value = '$'+formatNumber(GrandTotal.toFixed(2));
        document.getElementById('AddedInterest').value = "$"+formatNumber(payIntrest.toFixed(2));
        document.getElementById('FinalPaymentAmountnotdisabled').value = '$'+formatNumber(finalpayment.toFixed(2));
      } 



        
      
      if(amount == 0 && interest  != 0 && monthly != 0 &&  installments != 0 && InitialPaymentAmount == 0){
       
        
        
        let Irate, time, totalloanamount;
        Irate = interest;
        time = installments;
        
        totalloanamount = loan_amount_calculator(monthly, Irate, time);
        
        var newloanamount = totalloanamount;
        var GrandTotal = time * monthly;

        var payIntrest = GrandTotal - totalloanamount;

        var finalpayment = monthly;
        
        
        document.getElementById('TotalAmountOfOffer').value = '$'+formatNumber(totalloanamount.toFixed(2));
        document.getElementById('FinalPaymentAmount').value = '$'+formatNumber(finalpayment.toFixed(2));
        document.getElementById('GrandTotal').value = '$'+formatNumber(GrandTotal.toFixed(2));
        document.getElementById('AddedInterest').value = "$"+formatNumber(payIntrest.toFixed(2));
         document.getElementById('FinalPaymentAmountnotdisabled').value = '$'+formatNumber(finalpayment.toFixed(2));
      }



          if(amount == 0 && interest  == 0 && monthly != 0 &&  installments != 0 && InitialPaymentAmount != 0)
          {
             var multiplication = monthly * installments + InitialPaymentAmount;

             $('#TotalAmountOfOffer').val(multiplication);

             var subraction = multiplication - multiplication;
             var total = subraction + monthly;
       
        
       
             $('#FinalPaymentAmount').val('$'+formatNumber(total.toFixed(2)));
             $('#GrandTotal').val('$'+formatNumber(multiplication.toFixed(2)));
            
             $('#AddedInterest').val('$'+'0.00');
             $('#FinalPaymentAmountnotdisabled').val('$'+formatNumber(total.toFixed(2)));
          }



          if(amount == 0 && interest  == 0 && monthly != 0 &&  installments != 0 && InitialPaymentAmount == 0)
          {
             var multiplication = monthly * installments;

             $('#TotalAmountOfOffer').val(multiplication);

             var subraction = multiplication - multiplication;
             var total = subraction + monthly;
             $('#FinalPaymentAmount').val('$'+formatNumber(total.toFixed(2)));
             $('#GrandTotal').val('$'+formatNumber(multiplication.toFixed(2)));
            
             $('#AddedInterest').val('$'+'0.00');
             $('#FinalPaymentAmountnotdisabled').val('$'+formatNumber(total.toFixed(2)));
          }



          
      
      
      //Calculate tenure with InitialPaymentAmount
      if(amount != 0 && interest  != 0 && monthly != 0 &&  installments == 0 && InitialPaymentAmount != 0)
      {
        if(InitialPaymentAmount != 0 || InitialPaymentAmount != ''){
          var loanamount = amount - InitialPaymentAmount;
        }else{
          var loanamount = amount;
        }
        
        
        let principal, Irate, tenure, emi, finalpaydate;
        principal = loanamount;
        Irate = interest;
        emi = monthly;
    
        tenure = tenure_calculator(emi, principal, Irate);
        
        
        var nt = Math.round(tenure);
        var time = $('#InitialInstallmentDate').val();
        finalpayment_date(time, nt);
        
        
        var addintrest = emi * tenure;
        var payintrest = addintrest - principal;

        
        var amounttotal = addintrest + InitialPaymentAmount;


        
        var Frfinalpayamounttotal = addintrest;
        var Frfinal = Math.ceil(tenure) - 1;
        var getfinalpay = emi * Frfinal;
        var finalpay = Frfinalpayamounttotal - getfinalpay;
        
      
          
          
              document.getElementById('NumberofInstallments').value = Math.ceil(tenure);    
              document.getElementById('FinalPaymentAmount').value = '$'+formatNumber(finalpay.toFixed(2));
              document.getElementById('GrandTotal').value = '$'+formatNumber(amounttotal.toFixed(2));
              document.getElementById('AddedInterest').value = "$"+formatNumber(payintrest.toFixed(2));
              document.getElementById('FinalPaymentAmountnotdisabled').value = '$'+formatNumber(finalpay.toFixed(2));
      }



         
      
      //calculate tenure without initial payment amount
      if(amount != 0 && interest  != 0 && monthly != 0 &&  installments == 0 && InitialPaymentAmount == 0){
        
        if(InitialPaymentAmount != 0 || InitialPaymentAmount != ''){
          var loanamount = amount - InitialPaymentAmount;
        }else{
          var loanamount = amount;
        }
        
        
        let principal, Irate, tenure, emi, finalpaydate;
        principal = loanamount;
        Irate = interest;
        emi = monthly;
    
        tenure = tenure_calculator(emi, principal, Irate);
        
        var nt = Math.round(tenure);
        var time = $('#InitialInstallmentDate').val();
        finalpayment_date(time, nt);
        
        var addintrest = emi * tenure;
        var payintrest = addintrest - principal;
         
        var amounttotal = addintrest + InitialPaymentAmount;
        var Frfinal = Math.ceil(tenure) - 1;
        var getfinalpay = emi * Frfinal;
        
        var finalpay = amounttotal - getfinalpay;
        
       
        
          
        document.getElementById('NumberofInstallments').value = Math.ceil(tenure);    
              document.getElementById('FinalPaymentAmount').value = '$'+formatNumber(finalpay.toFixed(2));
              document.getElementById('GrandTotal').value = '$'+formatNumber(amounttotal.toFixed(2));
              document.getElementById('AddedInterest').value = "$"+formatNumber(payintrest.toFixed(2));
              document.getElementById('FinalPaymentAmountnotdisabled').value = '$'+formatNumber(finalpay.toFixed(2));
      } 
/*calculation changed by puja kumari on dt:01/05/2023==============================*/

          if(amount != 0 && interest  == 0 && monthly != 0 &&  installments == 0 && InitialPaymentAmount != 0)
          {
                amount = amount - InitialPaymentAmount;
              

                 var installmentsval = Number($('#NumberofInstallments').val());

                 var TotalAmountOfOffer=$('#TotalAmountOfOffer').val();
                 var installmentscal =(amount)/monthly;
                 

                 if(Number.isInteger(installmentscal)==true){//alert('true');
                  var installmentscal = Math.round(installmentscal);
                  var instalments     =Math.round(installmentscal);
                }else{//alert('false');
                   var installmentscal      = (Math.ceil(installmentscal))-1;
                   var installmentscalforno =(amount)/monthly;
                   var instalments          =Math.ceil(installmentscalforno);
                }
              
              
              
               var newGrandTotal = amount+InitialPaymentAmount;
               var multiplication = monthly * installmentscal;
               var total          = amount - multiplication;
               
               $('#FinalPaymentAmount').val('$'+formatNumber(total.toFixed(2)));
               $('#GrandTotal').val('$'+formatNumber(newGrandTotal.toFixed(2)));
              $('#NumberofInstallments').val(instalments);
               $('#AddedInterest').val('$'+'0.00');
               $('#FinalPaymentAmountnotdisabled').val('$'+formatNumber(total.toFixed(2)));
          }

          


          if(amount != 0 && interest  == 0 && monthly != 0 &&  installments == 0 && InitialPaymentAmount == 0)
          {
                   amount = amount - InitialPaymentAmount;

                  var installmentsval = Number($('#NumberofInstallments').val());
                  var TotalAmountOfOffer=$('#TotalAmountOfOffer').val();
                  var installmentscal =(amount)/monthly;
                 
                  if(Number.isInteger(installmentscal)==true){//alert('true');
                  var installmentscal = Math.round(installmentscal);
                  var instalments     =Math.round(installmentscal);
                  var total=monthly;

                }else{//alert('false');
                   var installmentscal      = (Math.ceil(installmentscal))-1;
                   var installmentscalforno =(amount)/monthly;
                   var instalments          =Math.ceil(installmentscalforno);
                   var multiplication       = monthly * installmentscal;
                   var total                = amount - multiplication;
                }

               var newGrandTotal = amount+InitialPaymentAmount;
               
               $('#FinalPaymentAmount').val('$'+formatNumber(total.toFixed(2)));
               $('#GrandTotal').val('$'+formatNumber(newGrandTotal.toFixed(2)));
                $('#NumberofInstallments').val(instalments);
               $('#AddedInterest').val('$'+'0.00');
               $('#FinalPaymentAmountnotdisabled').val('$'+formatNumber(total.toFixed(2)));
          }


          
          

      //Add New Function For EMI Calculator Start
      if(amount != 0 && interest !=0 && monthly == 0 && installments != 0 && InitialPaymentAmount != 0){
        if(InitialPaymentAmount != 0 || InitialPaymentAmount != ''){
          var loanamount = amount - InitialPaymentAmount;
        }else{
          var loanamount = amount;
        }
        
        
        let principal, Irate, time, emi;
        principal = loanamount;
        Irate = interest;
        time = installments;

        emi = emi_calculator(principal, Irate, time);
        var addintrest = emi * time;
        var payintrest = addintrest - principal;
        
        
        var amounttotal = addintrest + InitialPaymentAmount;  
        
       
        
        
        document.getElementById('monthlyPaymentAmt').value = '$'+formatNumber(emi.toFixed(2));
        document.getElementById('FinalPaymentAmount').value = '$'+formatNumber(emi.toFixed(2));
        document.getElementById('GrandTotal').value = '$'+formatNumber(amounttotal.toFixed(2));
        document.getElementById('AddedInterest').value = "$"+formatNumber(payintrest.toFixed(2));
        document.getElementById('FinalPaymentAmountnotdisabled').value = '$'+formatNumber(emi.toFixed(2));
      
        var txtFirmBal = Number($('#txtFirmBal').val().replace("$", ""));
        var balType = '<?php echo $balanceType; ?>';

        if(balType == 'prin')
        {
          var txtFirmBal = txtFirmBal;
          var txt = 'priciple';
        }
        else
        {
          var txtFirmBal = Number($('#total_balance_due').val().replace("$", ""));
          var txt = 'total';
        }

        if(Number($('#AddedInterest').val().replace("$", "")) > txtFirmBal)
        {
          swal("Warning!", "The amount of interest accumulated is more than the "+ txt +" balance.", "warning");
        }
      }

      if(amount != 0 && interest  != 0 && monthly == 0 &&  installments != 0 && InitialPaymentAmount == 0){
        
        if(InitialPaymentAmount != 0 || InitialPaymentAmount != ''){
          var loanamount = amount - InitialPaymentAmount;
        }else{
          var loanamount = amount;
        }
        
        
        let principal, Irate, time, emi;
        principal = loanamount;
        Irate = interest;
        time = installments;

        emi = emi_calculator(principal, Irate, time);
        var addintrest = emi * time;
        var payintrest = addintrest - principal;
        
        
        var amounttotal = addintrest + InitialPaymentAmount;  
        
       
        
        
        document.getElementById('monthlyPaymentAmt').value = '$'+formatNumber(emi.toFixed(2));
        document.getElementById('FinalPaymentAmount').value = '$'+formatNumber(emi.toFixed(2));
        document.getElementById('GrandTotal').value = '$'+formatNumber(amounttotal.toFixed(2));
        document.getElementById('AddedInterest').value = "$"+formatNumber(payintrest.toFixed(2));
        document.getElementById('FinalPaymentAmountnotdisabled').value = '$'+formatNumber(emi.toFixed(2));
      } 

          
          


          if(amount != 0 && interest  == 0 && monthly == 0 &&  installments != 0 && InitialPaymentAmount != 0)
          {
              amount = amount - InitialPaymentAmount;
              var monthlyNew = amount/installments;
              monthly = monthlyNew.toFixed(2);

              $('#monthlyPaymentAmt').val('$'+monthlyNew.toFixed(2));

                 var multiplication = monthly * installments - InitialPaymentAmount;

               var subraction = Number(amount) - Number(multiplication);
               var total = Number(monthly);
               var newAmt = Number(amount) + InitialPaymentAmount;
               $('#FinalPaymentAmount').val('$'+formatNumber(Number(total).toFixed(2)));
               $('#GrandTotal').val('$'+formatNumber(newAmt.toFixed(2)));
              
               $('#AddedInterest').val('$'+'0.00');
          }



          if(amount != 0 && interest  == 0 && monthly == 0 &&  installments != 0 && InitialPaymentAmount == 0)
          {

              var monthlyNew = amount/installments;
              monthly = monthlyNew.toFixed(2);
              amount = amount - InitialPaymentAmount;

              $('#monthlyPaymentAmt').val('$'+monthlyNew.toFixed(2));

                 var multiplication = monthly * installments;

               var subraction = amount - multiplication;
               var total = Number(subraction) + Number(monthly);
               
               $('#FinalPaymentAmount').val('$'+formatNumber(Number(total).toFixed(2)));
               $('#GrandTotal').val('$'+formatNumber(amount.toFixed(2)));
              
               $('#AddedInterest').val('$'+'0.00');
               $('#FinalPaymentAmountnotdisabled').val('$'+formatNumber(Number(total).toFixed(2)));
          }


         initialInstallmentDate = $('#InitialInstallmentDate').val();
         if(initialInstallmentDate != '')
        {
              var initialDate = $('#InitialInstallmentDate').val();

              var dt = new Date(initialDate);
              
              var m = Number($('#NumberofInstallments').val());

              if(m == '')
              {
                m = 0;
              }
              else
              {
                m = m - 1;
              }
              
              

                dt.setMonth( dt.getMonth() + m );
            
              var today = new Date(dt);
              
             

              var dd = String(today.getDate()).padStart(2, '0');
              var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
              var yyyy = today.getFullYear();

              var nDate = mm + '/' + dd + '/' + yyyy;

                $('#FinalPaymentDate').val(nDate);

                         
          }
          
         if(a == 1){
       $('#btnSubmit02').prop('disabled', true);
       $('#CalcErr').css('display', 'block');
     
         }

         // a++;
          if(!validatenegativefinalpayment())
         {
          return false;
         }
         '<?php  if($fetchresquerychecklastvalue ['AACACNTR']=='True' || $fetchresquerychecklastvalue ['CLNTCNTR']=='True'){?>'
           if(!validatepreviousvalues()){
             return false;
          }
          '<?php } ?>';

       }
       /* function to check final payment value is negative by puja kumari on dt:16-05-2023*/
       function validatenegativefinalpayment(){
        var flag = true;
        var FinalPaymentAmountnotdisabled= $('#FinalPaymentAmountnotdisabled').val();
        var getnegativetotal=String(FinalPaymentAmountnotdisabled)[1];
       
          if(getnegativetotal=='-'){
            //setTimeout(function() {
              swal({
                  title: "",
                  text: "Offer results in a negative payment and may not be submitted, please revise and recalculate your offer.",
                  type: "error"
              }, function() {
                    $('#FinalPaymentAmountError').css('display', 'block');
                    $('#FinalPaymentAmountError').text("Please clear values as final payment value is negative.");
              });
        //  }, 1000);
          flag = false;
         }
         if(flag)
         {
          return true;
         }else{
          return false;
         }

       }



       //Vikash Sharma
       function calcCalculation()
        {
          // alert("Hello");
         

         if(!validateCalc())
         {
              // alert("test");

         return false;
         }

         if(!validateNumberCount())
         {
          // alert("de");
          return false;
         }
         // alert("het");

         var amount = $('#AACACounterOfferAmount').val().replace("$", "");

         amount = amount.replace(/\,/g,'');
         amount = Number(amount);
          
         var interest = Number($('#EnterInterestRate').val());
         var rate = interest / 100;
         var monthly = $('#AACACounterOfferMonthlyPayment').val().replace("$", "");


         monthly = monthly.replace(/\,/g,'');
         monthly = Number(monthly);

         var installments = $('#AACACounterOfferNumberOfPayments').val();
                           console.log(installments);

         var InitialPaymentAmount = $('#AACACounterOfferAmountDown').val().replace("$", "");

         InitialPaymentAmount = InitialPaymentAmount.replace(/\,/g,'');
         InitialPaymentAmount = Number(InitialPaymentAmount);

         if(interest == '')
         {
          interest = 0;
         }
         if(installments == '')
         {
          installments = 0;
         }
         

          var initialInstallmentDate = $('#InitialInstallmentDate').val();

          if(initialInstallmentDate == '')
          {
            var deadofaccpt = $('#deadofaccpt').val();
            
            
            
            var dt = new Date(deadofaccpt);
            

            dt.setMonth( dt.getMonth() + 1 );
            
            var today = new Date(dt);
            
            // alert(newDate+'---'+today+'---'+dt);

            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            var nDate = mm + '/' + dd + '/' + yyyy;


            $('#InitialInstallmentDate').val(nDate);  

              var initialDate = $('#InitialInstallmentDate').val();

              var dt = new Date(initialDate);
              
              var m = Number($('#AACACounterOfferNumberOfPayments').val());

              var initial = InitialPaymentAmount;

              

              if(m == 0 || m == '')
              {
                if(initial == '' || initial == 0){
                var x = Number($('#AACACounterOfferAmount').val().replace("$", ""))/Number($('#AACACounterOfferMonthlyPayment').val().replace("$", ""));

                var x = Math.round(x);
                $('#AACACounterOfferNumberOfPayments').val(x)
               }
               else
               {
                   var subtract = amount - initial;
                   
                   var divide = subtract/monthly;
                   var reminder = subtract%monthly;
                   var roundValue = Math.round(divide);
                   

                   $('#AACACounterOfferNumberOfPayments').val(roundValue);

               }

              }

              

              m = Number($('#AACACounterOfferNumberOfPayments').val()) - 1;


                dt.setMonth( dt.getMonth() + m );
            
              var today = new Date(dt);
              
              // alert(newDate+'---'+today+'---'+dt);

              var dd = String(today.getDate()).padStart(2, '0');
              var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
              var yyyy = today.getFullYear();

              var nDate = mm + '/' + dd + '/' + yyyy;
                $('#FinalPaymentDate').val(nDate);

          }

      

          if(amount != 0 && interest  != 0 && monthly != 0 &&  installments != 0 && InitialPaymentAmount != 0)
          {

             amount = amount - InitialPaymentAmount;
             let interestCount = interestRateCalc(amount, rate, monthly, installments);

             var multiplication = monthly * installments;
             var subraction = amount - multiplication;
             var total = subraction + monthly;

             var finalPayment0 = Number(total) + Math.round(interestCount * 100) / 100;
             // finalPayment0 = finalPayment0.toFixed(2);
             var grandTotal0 = Number(amount) + Math.round(interestCount * 100) / 100 + InitialPaymentAmount;
             
       
             
              document.getElementById('AACACounterOfferFinalPayment').value = '$'+formatNumber(finalPayment0.toFixed(2));
              document.getElementById('GrandTotal').value = '$'+formatNumber(grandTotal0.toFixed(2));
              document.getElementById('AddedInterest').value = '$'+formatNumber(interestCount.toFixed(2));

              var txtFirmBal = Number($('#txtFirmBal').val().replace("$", ""));

              var balType = '<?php echo $balanceType; ?>';

              if(balType == 'prin')
              {
                var txtFirmBal = txtFirmBal;
                var txt = 'priciple';
              }
              else
              {
                var txtFirmBal = Number($('#total_balance_due').val().replace("$", ""));
                var txt = 'total';
              }

             
              

              if(Number($('#AddedInterest').val().replace("$", "")) > txtFirmBal)
              {
                  swal("Warning!", "The amount of interest accumulated is more than the "+ txt +" balance.", "warning");
              }
          }



          if(amount != 0 && interest  != 0 && monthly != 0 &&  installments != 0 && InitialPaymentAmount == 0)
          {
              
             let interestCount = interestRateCalc(amount, rate, monthly, installments);

             var multiplication = monthly * installments;
             var subraction = amount - multiplication;
             var total = subraction + monthly;

            var finalPayment1 = Number(total) + Math.round(interestCount * 100) / 100;
            // finalPayment1 = finalPayment1.toFixed(2);
            var grandTotal1 = Number(amount) + Math.round(interestCount * 100) / 100;
            grandTotal1 = grandTotal1.toFixed(2);
            

     
             
              document.getElementById('AACACounterOfferFinalPayment').value = '$'+formatNumber(finalPayment1.toFixed(2));
              document.getElementById('GrandTotal').value = '$'+formatNumber(grandTotal1);
              document.getElementById('AddedInterest').value = "$"+formatNumber(interestCount.toFixed(2));

              var txtFirmBal = Number($('#txtFirmBal').val().replace("$", ""));
              var balType = '<?php echo $balanceType; ?>';

              if(balType == 'prin')
              {
                var txtFirmBal = txtFirmBal;
                var txt = 'priciple';
              }
              else
              {
                var txtFirmBal = Number($('#total_balance_due').val().replace("$", ""));
                var txt = 'total';
              }

             
              

              if(Number($('#AddedInterest').val().replace("$", "")) > txtFirmBal)
              {
                  swal("Warning!", "The amount of interest accumulated is more than the "+ txt +" balance.", "warning");
              }

          }



          if(amount != 0 && interest  == 0 && monthly != 0 &&  installments != 0 && InitialPaymentAmount != 0)
          {

            var subraction = amount - InitialPaymentAmount;
            var multiplication = monthly*installments;


            // $('#TotalAmountOfOffer').val(multiplication);

            // var subraction = amount - multiplication;
            var total = subraction - multiplication + monthly;
            $('#AACACounterOfferFinalPayment').val('$'+formatNumber(total.toFixed(2)));
            $('#GrandTotal').val('$'+formatNumber(amount.toFixed(2)));
            
            $('#AddedInterest').val('$'+'0.00');
          }



          if(amount != 0 && interest  == 0 && monthly != 0 &&  installments != 0 && InitialPaymentAmount == 0)
          {
            
             var multiplication = monthly * installments;
             var subraction = amount - multiplication;
             var total = subraction + monthly;


             $('#AACACounterOfferFinalPayment').val('$'+formatNumber(total.toFixed(2)));
             $('#GrandTotal').val('$'+formatNumber(amount.toFixed(2)));
             $('#AddedInterest').val('$'+'0.00');
          }



      
      
      if(amount == 0 && interest  != 0 && monthly != 0 &&  installments != 0 && InitialPaymentAmount != 0)
      {
        if(InitialPaymentAmount != 0 || InitialPaymentAmount != ''){
          var adddown = InitialPaymentAmount;
        }else{
          var adddown = InitialPaymentAmount;
        }
        
        
        let Irate, time, totalloanamount;
        Irate = interest;
        time = installments;
        
        totalloanamount = loan_amount_calculator(monthly, Irate, time);
        var newloanamount = totalloanamount + adddown;
        
        
        var QueGrandTotal = time * monthly;
        var GrandTotal1 = QueGrandTotal;
        var GrandTotal = QueGrandTotal + adddown;


        var payIntrest = GrandTotal1 - totalloanamount;

        var finalpayment = monthly;
       
        
        document.getElementById('AACACounterOfferAmount').value = '$'+formatNumber(newloanamount.toFixed(2));
        document.getElementById('AACACounterOfferFinalPayment').value = '$'+formatNumber(finalpayment.toFixed(2));
        document.getElementById('GrandTotal').value = '$'+formatNumber(GrandTotal.toFixed(2));
        document.getElementById('AddedInterest').value = "$"+formatNumber(payIntrest.toFixed(2));
      } 



         
      
      if(amount == 0 && interest  != 0 && monthly != 0 &&  installments != 0 && InitialPaymentAmount == 0){
       
        
        
        let Irate, time, totalloanamount;
        Irate = interest;
        time = installments;
        
        totalloanamount = loan_amount_calculator(monthly, Irate, time);
        
        var newloanamount = totalloanamount;
        var GrandTotal = time * monthly;

        var payIntrest = GrandTotal - totalloanamount;

        var finalpayment = monthly;
       
        
        document.getElementById('AACACounterOfferAmount').value = '$'+formatNumber(totalloanamount.toFixed(2));
        document.getElementById('AACACounterOfferFinalPayment').value = '$'+formatNumber(finalpayment.toFixed(2));
        document.getElementById('GrandTotal').value = '$'+formatNumber(GrandTotal.toFixed(2));
        document.getElementById('AddedInterest').value = "$"+formatNumber(payIntrest.toFixed(2));
      }



          if(amount == 0 && interest  == 0 && monthly != 0 &&  installments != 0 && InitialPaymentAmount != 0)
          {
             var multiplication = monthly * installments + InitialPaymentAmount;

             $('#AACACounterOfferAmount').val(multiplication);

             var subraction = multiplication - multiplication;
             var total = subraction + monthly;
       
        
       
             $('#AACACounterOfferFinalPayment').val('$'+formatNumber(total.toFixed(2)));
             $('#GrandTotal').val('$'+formatNumber(multiplication.toFixed(2)));
            
             $('#AddedInterest').val('$'+'0.00');
          }



          if(amount == 0 && interest  == 0 && monthly != 0 &&  installments != 0 && InitialPaymentAmount == 0)
          {
             var multiplication = monthly * installments;

             $('#AACACounterOfferAmount').val(multiplication);

             var subraction = multiplication - multiplication;
             var total = subraction + monthly;
             $('#AACACounterOfferFinalPayment').val('$'+formatNumber(total.toFixed(2)));
             $('#GrandTotal').val('$'+formatNumber(multiplication.toFixed(2)));
            
             $('#AddedInterest').val('$'+'0.00');
          }



         
      
      //Calculate tenure with InitialPaymentAmount
      if(amount != 0 && interest  != 0 && monthly != 0 &&  installments == 0 && InitialPaymentAmount != 0)
      {
        if(InitialPaymentAmount != 0 || InitialPaymentAmount != ''){
          var loanamount = amount - InitialPaymentAmount;
        }else{
          var loanamount = amount;
        }
        
        
        let principal, Irate, tenure, emi, finalpaydate;
        principal = loanamount;
        Irate = interest;
        emi = monthly;
    
        tenure = tenure_calculator(emi, principal, Irate);
        
        
        var nt = Math.round(tenure);
        var time = $('#InitialInstallmentDate').val();
        finalpayment_date(time, nt);
        
        
        var addintrest = emi * tenure;
        var payintrest = addintrest - principal;

        
        var amounttotal = addintrest + InitialPaymentAmount;


        
        var Frfinalpayamounttotal = addintrest;
        var Frfinal = Math.ceil(tenure) - 1;
        var getfinalpay = emi * Frfinal;
        var finalpay = Frfinalpayamounttotal - getfinalpay;
        
        //this is using for chart 
        // intrestchart(amounttotal, payintrest, principal);
          
          
        document.getElementById('AACACounterOfferNumberOfPayments').value = Math.ceil(tenure);    
              document.getElementById('AACACounterOfferFinalPayment').value = '$'+formatNumber(finalpay.toFixed(2));
              document.getElementById('GrandTotal').value = '$'+formatNumber(amounttotal.toFixed(2));
              document.getElementById('AddedInterest').value = "$"+formatNumber(payintrest.toFixed(2));
      }



          
      //calculate tenure without initial payment amount
      if(amount != 0 && interest  != 0 && monthly != 0 &&  installments == 0 && InitialPaymentAmount == 0){
        
        if(InitialPaymentAmount != 0 || InitialPaymentAmount != ''){
          var loanamount = amount - InitialPaymentAmount;
        }else{
          var loanamount = amount;
        }
        
        
        let principal, Irate, tenure, emi, finalpaydate;
        principal = loanamount;
        Irate = interest;
        emi = monthly;
    
        tenure = tenure_calculator(emi, principal, Irate);
        
        var nt = Math.round(tenure);
        var time = $('#InitialInstallmentDate').val();
        finalpayment_date(time, nt);
        
        var addintrest = emi * tenure;
        var payintrest = addintrest - principal;
         
        var amounttotal = addintrest + InitialPaymentAmount;
        var Frfinal = Math.ceil(tenure) - 1;
        var getfinalpay = emi * Frfinal;
        
        var finalpay = amounttotal - getfinalpay;
        
        //this is using for payment chart
        // intrestchart(amounttotal, payintrest, principal);
        
          
        document.getElementById('AACACounterOfferNumberOfPayments').value = Math.ceil(tenure);    
              document.getElementById('AACACounterOfferFinalPayment').value = '$'+formatNumber(finalpay.toFixed(2));
              document.getElementById('GrandTotal').value = '$'+formatNumber(amounttotal.toFixed(2));
              document.getElementById('AddedInterest').value = "$"+formatNumber(payintrest.toFixed(2));
      } 


          if(amount != 0 && interest  == 0 && monthly != 0 &&  installments == 0 && InitialPaymentAmount != 0)
          {
            var noOfMonthlyPymt = amount - InitialPaymentAmount;
                  noOfMonthlyPymt = noOfMonthlyPymt/monthly;

              

              //var noOfPayment = parseFloat(noOfMonthlyPymt).toFixed(2);
              if(Number.isInteger(noOfMonthlyPymt)==true){
              var noOfMonthlyPymt1 = Math.round(noOfMonthlyPymt);
              
             
            }else{
               
              var noOfMonthlyPymt1=Math.ceil(noOfMonthlyPymt);
              var noOfMonthlyPymtcal = noOfMonthlyPymt1-1;
              var multiply=monthly*noOfMonthlyPymtcal;
              var add=multiply+InitialPaymentAmount;
              var monthly=parseFloat(amount-add).toFixed(2);
             
            }

              $('#AACACounterOfferNumberOfPayments').val(noOfMonthlyPymt1);
              $('#AACACounterOfferFinalPayment').val("$"+monthly); 
          
          }

          


          if(amount != 0 && interest  == 0 && monthly != 0 &&  installments == 0 && InitialPaymentAmount == 0)
          {
                amount = amount - InitialPaymentAmount;
                var divide = amount/monthly;
                var reminder = amount%monthly;
                var roundValue = Math.round(divide);

                if(roundValue > divide)
                {
                  
                   $('#AACACounterOfferNumberOfPayments').val(roundValue);
                  
                }
                else
                {

                   $('#AACACounterOfferNumberOfPayments').val(roundValue);
                 
                }

                 installments = Number($('#AACACounterOfferNumberOfPayments').val());

                 var multiplication = monthly * installments;

               var subraction = amount - multiplication;
               var total = subraction + monthly;
               $('#AACACounterOfferFinalPayment').val('$'+formatNumber(total.toFixed(2)));
               $('#GrandTotal').val('$'+formatNumber(amount.toFixed(2)));
              
               $('#AddedInterest').val('$'+'0.00');
          }


          


      //Add New Function For EMI Calculator Start
      if(amount != 0 && interest !=0 && monthly == 0 && installments != 0 && InitialPaymentAmount != 0){
        if(InitialPaymentAmount != 0 || InitialPaymentAmount != ''){
          var loanamount = amount - InitialPaymentAmount;
        }else{
          var loanamount = amount;
        }
        
        
        let principal, Irate, time, emi;
        principal = loanamount;
        Irate = interest;
        time = installments;

        emi = emi_calculator(principal, Irate, time);
        var addintrest = emi * time;
        var payintrest = addintrest - principal;
        
        
        var amounttotal = addintrest + InitialPaymentAmount;  
        
        //this is using for chart
        // intrestchart(amounttotal, payintrest, amount);
        
        
        document.getElementById('AACACounterOfferMonthlyPayment').value = '$'+formatNumber(emi.toFixed(2));
        document.getElementById('AACACounterOfferFinalPayment').value = '$'+formatNumber(emi.toFixed(2));
        document.getElementById('GrandTotal').value = '$'+formatNumber(amounttotal.toFixed(2));
        document.getElementById('AddedInterest').value = "$"+formatNumber(payintrest.toFixed(2));
      
        var txtFirmBal = Number($('#txtFirmBal').val().replace("$", ""));
        var balType = '<?php echo $balanceType; ?>';

        if(balType == 'prin')
        {
          var txtFirmBal = txtFirmBal;
          var txt = 'priciple';
        }
        else
        {
          var txtFirmBal = Number($('#total_balance_due').val().replace("$", ""));
          var txt = 'total';
        }

        if(Number($('#AddedInterest').val().replace("$", "")) > txtFirmBal)
        {
          swal("Warning!", "The amount of interest accumulated is more than the "+ txt +" balance.", "warning");
        }
      }

      if(amount != 0 && interest  != 0 && monthly == 0 &&  installments != 0 && InitialPaymentAmount == 0){
        
        if(InitialPaymentAmount != 0 || InitialPaymentAmount != ''){
          var loanamount = amount - InitialPaymentAmount;
        }else{
          var loanamount = amount;
        }
        
        
        let principal, Irate, time, emi;
        principal = loanamount;
        Irate = interest;
        time = installments;

        emi = emi_calculator(principal, Irate, time);
        var addintrest = emi * time;
        var payintrest = addintrest - principal;
        
        
        var amounttotal = addintrest + InitialPaymentAmount;  
        
        //this is using for chart
        // intrestchart(amounttotal, payintrest, amount);
        
        
        document.getElementById('AACACounterOfferMonthlyPayment').value = '$'+formatNumber(emi.toFixed(2));
        document.getElementById('AACACounterOfferFinalPayment').value = '$'+formatNumber(emi.toFixed(2));
        document.getElementById('GrandTotal').value = '$'+formatNumber(amounttotal.toFixed(2));
        document.getElementById('AddedInterest').value = "$"+formatNumber(payintrest.toFixed(2));
      } 

          
          


          if(amount != 0 && interest  == 0 && monthly == 0 &&  installments != 0 && InitialPaymentAmount != 0)
          {
              amount = amount - InitialPaymentAmount;
              var monthlyNew = amount/installments;
              monthly = monthlyNew.toFixed(2);

              $('#AACACounterOfferMonthlyPayment').val('$'+monthlyNew.toFixed(2));

                 var multiplication = monthly * installments - InitialPaymentAmount;

               var subraction = Number(amount) - Number(multiplication);
               var total = Number(monthly);
               var newAmt = Number(amount) + InitialPaymentAmount;
               $('#AACACounterOfferFinalPayment').val('$'+formatNumber(Number(total).toFixed(2)));
               $('#GrandTotal').val('$'+formatNumber(newAmt.toFixed(2)));
              
               $('#AddedInterest').val('$'+'0.00');
          }



          if(amount != 0 && interest  == 0 && monthly == 0 &&  installments != 0 && InitialPaymentAmount == 0)
          {

              var monthlyNew = amount/installments;
              monthly = monthlyNew.toFixed(2);
              amount = amount - InitialPaymentAmount;

              $('#AACACounterOfferMonthlyPayment').val('$'+monthlyNew.toFixed(2));

                 var multiplication = monthly * installments;

               var subraction = amount - multiplication;
               var total = Number(subraction) + Number(monthly);
               
               $('#AACACounterOfferFinalPayment').val('$'+formatNumber(Number(total).toFixed(2)));
               $('#GrandTotal').val('$'+formatNumber(amount.toFixed(2)));
              
               $('#AddedInterest').val('$'+'0.00');
          }


         initialInstallmentDate = $('#InitialInstallmentDate').val();
         if(initialInstallmentDate != '')
        {
              var initialDate = $('#InitialInstallmentDate').val();

              var dt = new Date(initialDate);
              
              var m = Number($('#AACACounterOfferNumberOfPayments').val());

              if(m == '')
              {
                m = 0;
              }
              else
              {
                m = m - 1;
              }
              
              

                dt.setMonth( dt.getMonth() + m );
            
              var today = new Date(dt);
              
             

              var dd = String(today.getDate()).padStart(2, '0');
              var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
              var yyyy = today.getFullYear();

              var nDate = mm + '/' + dd + '/' + yyyy;

                $('#FinalPaymentDate').val(nDate);

                         
          }
          
         if(a == 1){
       $('#btnSubmit01').prop('disabled', true);
       $('#CalcErr').css('display', 'block');


       // $('#CalcErr').text('Please clear and fill the required fields to calculate');
         }

         // a++;

         var deadlineDate = $('#AACACounterOfferDeadlineDate').val();
          var firstDuePymt = $('#AACACounterOfferFirstPaymentDate').val();
          

          if(deadlineDate == '')
          {
                        
            var today = new Date();
            
            today.setDate(today.getDate() + 1);

            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            var nDate = mm + '/' + dd + '/' + yyyy;


            $('#AACACounterOfferDeadlineDate').val(nDate);  


          }
          else
          {
             
           
                         
          }

          if(firstDuePymt == '')
          {
              var deadlineDate = $('#AACACounterOfferDeadlineDate').val();

              var dt = new Date(deadlineDate);

               var initial_day = dt.getDate();
               dt.setMonth( dt.getMonth() + 1 );
               
              var today = new Date(dt);
            
              var month = today.getMonth() + 1;
              var day = today.getDate() ;
              var year = today.getFullYear();


              if(initial_day == 29 || initial_day == 30 || initial_day == 31)
            {
                if(month == 3)
                {
                    if(year % 4 == 0)
                    {
                       // var newDate1 =  month + "/" + day + "/" + year;
                       if(day == 1 || day == 2)
                       {
                         var newMonth1 = month - 1;
                         var newDay1 = 29;
                         var newYear1 = year;

                         var newDate1 = newMonth1 + "/" + newDay1 + "/" + newYear1;
                         var date1 = new Date(newDate1);
                       }
                    }
                    else
                    {
                      if(day == 1 || day == 2 || day == 3){
                       var newMonth1 = month - 1;
                       var newDay1 = 28;
                       var newYear1 = year;

                       var newDate1 = newMonth1 + "/" + newDay1 + "/" + newYear1;
                       var date1 = new Date(newDate1);
                     }
                    }
                }
            }
            else
            {
                var newDate1 = month + "/" + day + "/" + year;
                var date1 = new Date(newDate1);
            }

            if(initial_day == 31)
            {
                if(month == 5 || month == 7 || month == 10 || month == 12)
                {
                    if(day == 1)
                    {
                       var newMonth1 = month - 1;
                       var newDay1 = 30;
                       var newYear1 = year;

                       var newDate1 = newMonth1 + "/" + newDay1 + "/" + newYear1;
                       var date1 = new Date(newDate1);  
                    }
                }
            }
            else
            {
                var newDate1 = month + "/" + day + "/" + year;
                var date1 = new Date(newDate1);
            }

             var dd = String(date1.getDate()).padStart(2, '0');
             var mm = String(date1.getMonth() + 1).padStart(2, '0'); //January is 0!
             var yyyy = date1.getFullYear();

             var nDate = mm + '/' + dd + '/' + yyyy;

              //var nDate = mm + '/' + dd + '/' + yyyy;

              $('#AACACounterOfferFirstPaymentDate').val(nDate);  
          }
          if(!validatenegativefinalpaymentforaacaclient())
         {
          return false;
         }

       }
       /* function to calculate negativr final payment by puja on dt:16-05-2023*/
       function validatenegativefinalpaymentforaacaclient(){
        var flag = true;
        var AACACounterOfferFinalPayment= $('#AACACounterOfferFinalPayment').val();
        var getnegativetotal=String(AACACounterOfferFinalPayment)[1];
       
          if(getnegativetotal=='-'){
           // setTimeout(function() {
              swal({
                  title: "",
                  text: "Offer results in a negative payment and may not be submitted, please revise and recalculate your offer.",
                  type: "error"
              }, function() {
                    $('#AACACounterOfferFinalPaymentError').css('display', 'block');
                    $('#AACACounterOfferFinalPaymentError').text("Please clear values as final payment value is negative.");
              });
        //  }, 1000);
        
          flag = false;
         }
         if(flag)
         {
          return true;
         }else{
          return false;
         }

       }

      





       function validateCalc()
       {
        var flag = true;
        
        var TotalAmountOfOffer = $('#AACACounterOfferAmount').val().replace("$", "");
    TotalAmountOfOffer = TotalAmountOfOffer.replace(/\,/g,'');
    TotalAmountOfOffer = Number(TotalAmountOfOffer);
    
        var monthlyPaymentAmt = $('#AACACounterOfferMonthlyPayment').val().replace("$", "");
    monthlyPaymentAmt = monthlyPaymentAmt.replace(/\,/g,'');
    monthlyPaymentAmt = Number(monthlyPaymentAmt);
    
        var NumberofInstallments = Number($('#AACACounterOfferNumberOfPayments').val());
        var InitialPaymentAmount = Number($('#AACACounterOfferAmountDown').val().replace("$", ""));
    var Totalbalncdue = Number($('#totalBalDue').val().replace("$", ""));
  
        if((TotalAmountOfOffer == '0' && monthlyPaymentAmt == '0' && NumberofInstallments == '0') || (TotalAmountOfOffer != '0' && monthlyPaymentAmt == '0' && NumberofInstallments == '0') || (TotalAmountOfOffer == '0' && monthlyPaymentAmt != '0' && NumberofInstallments == '0') || (TotalAmountOfOffer == '0' && monthlyPaymentAmt != '0' && NumberofInstallments == '0') || (TotalAmountOfOffer == '0' && monthlyPaymentAmt == '0' && NumberofInstallments != '0') || (TotalAmountOfOffer == '' && monthlyPaymentAmt == '' && NumberofInstallments == '') || (TotalAmountOfOffer != '' && monthlyPaymentAmt == '' && NumberofInstallments == '') || (TotalAmountOfOffer == '' && monthlyPaymentAmt != '' && NumberofInstallments == '') || (TotalAmountOfOffer == '' && monthlyPaymentAmt == '' && NumberofInstallments != ''))
        {
          swal("Error!", "Unable to calculate, you must input at least two (2) of the following: Amount of Offer, Number of Installments or Monthly Payment Amount.", "error");
          flag = false;
        }
    else{
      $('#TotalAmountOfOffer').prop('disabled', true);
      $('#monthlyPaymentAmt').prop('disabled', true);
      $('#NumberofInstallments').prop('disabled', true);
      $('#EnterInterestRate').prop('disabled', true);
      $('#InitialPaymentAmount').prop('disabled', true);
      $('#InitialInstallmentDate').prop('disabled', true);
    }
    
        
        if(TotalAmountOfOffer != '0'){

          if(TotalAmountOfOffer < monthlyPaymentAmt)
          {
            swal("Error!", "If you would like to do a lump sum, please check the lump sum option.", "error");
            flag = false;
          }
        }
    


        if(flag)
         {
          return true;
         }else{
          return false;
         }
       }


       function validateCalcPaymentPlan()
       {
        var flag = true;
        
        var TotalAmountOfOffer = $('#TotalAmountOfOffer').val().replace("$", "");
    TotalAmountOfOffer = TotalAmountOfOffer.replace(/\,/g,'');
    TotalAmountOfOffer = Number(TotalAmountOfOffer);
    
        var monthlyPaymentAmt = $('#monthlyPaymentAmt').val().replace("$", "");
    monthlyPaymentAmt = monthlyPaymentAmt.replace(/\,/g,'');
    monthlyPaymentAmt = Number(monthlyPaymentAmt);
    
        var NumberofInstallments = Number($('#NumberofInstallments').val());
        var InitialPaymentAmount = Number($('#InitialPaymentAmount').val().replace("$", ""));
          var Totalbalncdue = Number($('#totalBalDue').val().replace("$", ""));

         
  
        if((TotalAmountOfOffer == '0' && monthlyPaymentAmt == '0' && NumberofInstallments == '0') || (TotalAmountOfOffer != '0' && monthlyPaymentAmt == '0' && NumberofInstallments == '0') || (TotalAmountOfOffer == '0' && monthlyPaymentAmt != '0' && NumberofInstallments == '0') || (TotalAmountOfOffer == '0' && monthlyPaymentAmt != '0' && NumberofInstallments == '0') || (TotalAmountOfOffer == '0' && monthlyPaymentAmt == '0' && NumberofInstallments != '0') || (TotalAmountOfOffer == '' && monthlyPaymentAmt == '' && NumberofInstallments == '') || (TotalAmountOfOffer != '' && monthlyPaymentAmt == '' && NumberofInstallments == '') || (TotalAmountOfOffer == '' && monthlyPaymentAmt != '' && NumberofInstallments == '') || (TotalAmountOfOffer == '' && monthlyPaymentAmt == '' && NumberofInstallments != ''))
        {
          swal("Error!", "Unable to calculate, you must input at least two (2) of the following: Amount of Offer, Number of Installments or Monthly Payment Amount.", "error");
          flag = false;
        }
        
    else{
      $('#TotalAmountOfOffer').prop('disabled', true);
      $('#monthlyPaymentAmt').prop('disabled', true);
      $('#NumberofInstallments').prop('disabled', true);
      $('#EnterInterestRate').prop('disabled', true);
      $('#InitialPaymentAmount').prop('disabled', true);
      $('#InitialInstallmentDate').prop('disabled', true);
    }
    
        
        if(TotalAmountOfOffer != '0'){

          if(TotalAmountOfOffer < monthlyPaymentAmt)
          {
            swal("Error!", "If you would like to do a lump sum, please check the lump sum option.", "error");
            flag = false;
          }
        }
    


        if(flag)
         {
          return true;
         }else{
          return false;
         }
       }
  
    function daysInMonth(month,year) {
      return new Date(year, month, 0).getDate();
    }
    
       function validateNumberCount()
       {
         var flag = true;
    
         var TotalAmountOfOffer = Number($('#TotalAmountOfOffer').val().replace("$", ""));
         var monthlyPaymentAmt = Number($('#monthlyPaymentAmt').val().replace("$", ""));
         var NumberofInstallments = Number($('#NumberofInstallments').val());

         var multiplication = monthlyPaymentAmt * NumberofInstallments;

         if(TotalAmountOfOffer != '' || TotalAmountOfOffer != '0')
         {
       if(multiplication > TotalAmountOfOffer)
       {
         swal("Error!", "Your total of monthly payment is greater than Total Amount offer, Please recalculate.", "error");
         flag = false;
       }
         }

            var startDate = new Date($('#deadofaccpt').val());
            var initalDate = new Date($('#InitialInstallmentDate').val());
      
      
            if(startDate!= '' && initalDate != ''){
            var Difference_In_Time = initalDate.getTime() - startDate.getTime();
      // To calculate the no. of days between two dates
         var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);

     var crntdate = new Date();
     var month = crntdate.getMonth();
     var ttldays = daysInMonth(month + 1, crntdate.getFullYear());
      
         if(Difference_In_Days > ttldays)
         {
          swal("Error!", "The deadline for acceptance and initial payment cannot be greater than "+ttldays+" days from today�s date.", "error");
          flag = false;
         }

         }

          if($("#NumberofInstallments").val() > 240)
        {
          swal("Error!", "Total number of installments cannot be greater than 240 months", "error");
          flag = false;
        }
         

         if(flag)
         {
          return true;
         }else{
          return false;
         }
       }


       function interestRateCalc(amount, rate, monthly, installments)
       {
        
            var m = installments+1; // number of months
        var sum = 0;
        var p = 0;
        var interest_rate = 0;
        var x;
        var balance;
            var j;
        var startDate = new Date($('#deadofaccpt').val());
            var initalDate = new Date($('#InitialInstallmentDate').val());
            var finalDate = new Date($('#FinalPaymentDate').val());
            var Difference_In_Time = initalDate.getTime() - startDate.getTime();
  
      // To calculate the no. of days between two dates
      var Difference_In_Days = Difference_In_Time / (1000 * 3600 * 24);


        let i = 0;

        while (i <= m) {
            if(i == 1)
            {
              var y = amount*rate;
              var interest_rate = y * Difference_In_Days/365;
              var interest_rate = Math.round(interest_rate * 100) / 100;
              
              var x = amount + interest_rate;
              var x = Math.round(x * 100) / 100;
               balance = x;

               sum += interest_rate;
              
            }

        if(i > 1)
        {

           if(i == 2)
            {
            var dt = new Date(initalDate);

            var initial_day = dt.getDate();

            dt.setMonth( dt.getMonth() + 0 );
            
            // var date1 = new Date(dt);
            }
            else
            {
            j = i -2;
            var dt = new Date(initalDate);

            var initial_day = dt.getDate();

            dt.setMonth( dt.getMonth() + j );
            
            // var date1 = new Date(dt);
            }

            var date1 = new Date(dt);

            var dateobj= new Date(dt) ;
            var month = dateobj.getMonth() + 1;
            var day = dateobj.getDate() ;
            var year = dateobj.getFullYear();

            if(initial_day == 29 || initial_day == 30 || initial_day == 31)
            {
                if(month == 3)
                {
                    if(year % 4 == 0)
                    {
                       // var newDate1 =  month + "/" + day + "/" + year;
                       if(day == 1 || day == 2)
                       {
                         var newMonth1 = month - 1;
                         var newDay1 = 29;
                         var newYear1 = year;

                         var newDate1 = newMonth1 + "/" + newDay1 + "/" + newYear1;
                         var date1 = new Date(newDate1);
                       }
                    }
                    else
                    {
                      if(day == 1 || day == 2 || day == 3){
                       var newMonth1 = month - 1;
                       var newDay1 = 28;
                       var newYear1 = year;

                       var newDate1 = newMonth1 + "/" + newDay1 + "/" + newYear1;
                       var date1 = new Date(newDate1);
                     }
                    }
                }
            }

            if(initial_day == 31)
            {
                if(month == 5 || month == 7 || month == 10 || month == 12)
                {
                    if(day == 1)
                    {
                       var newMonth1 = month - 1;
                       var newDay1 = 30;
                       var newYear1 = year;

                       var newDate1 = newMonth1 + "/" + newDay1 + "/" + newYear1;
                       var date1 = new Date(newDate1);  
                    }
                }
            }
            

            var dt = new Date(initalDate);      
            j = i-1;

            dt.setMonth( dt.getMonth() + j );
            
            var date2 = new Date(dt);

            var dateobj= new Date(dt) ;
            var month = dateobj.getMonth() + 1;
            var day = dateobj.getDate() ;
            var year = dateobj.getFullYear();

            if(initial_day == 29 || initial_day == 30 || initial_day == 31)
            {
                if(month == 3)
                {
                    if(year % 4 == 0)
                    {
                       // var newDate1 =  month + "/" + day + "/" + year;
                       if(day == 1 || day == 2)
                       {
                         var newMonth2 = month - 1;
                         var newDay2 = 29;
                         var newYear2 = year;

                         var newDate2 = newMonth2 + "/" + newDay2 + "/" + newYear2;
                         var date2 = new Date(newDate2);
                       }
                    }
                    else
                    {
                      if(day == 1 || day == 2 || day == 3){
                       var newMonth2 = month - 1;
                       var newDay2 = 28;
                       var newYear2 = year;

                       var newDate2 = newMonth2 + "/" + newDay2 + "/" + newYear2;
                       var date2 = new Date(newDate2);
                      }
                    }
                }
            }

            
            if(initial_day == 31)
            {
                if(month == 5 || month == 7 || month == 10 || month == 12)
                {
                    if(day == 1)
                    {
                       var newMonth2 = month - 1;
                       var newDay2 = 30;
                       var newYear2 = year;

                       var newDate2 = newMonth2 + "/" + newDay2 + "/" + newYear2;
                       var date2 = new Date(newDate2);  
                    }
                }
            }
            

            var Difference_In_Time1 = date2.getTime() - date1.getTime();
  
            var Difference_In_Days1 = Difference_In_Time1 / (1000 * 3600 * 24);
              
              var interest_rate = balance * rate * Difference_In_Days1/365;
              var interest_rate = Math.round(interest_rate * 100) / 100;
              
              var x = balance + interest_rate;
                  var x = x - monthly;
              var x = Math.round(x * 100) / 100;
               balance = x;

                  sum += interest_rate;
            }
           
           

            i++;
        }

             
              // alert(monthly +' '+ installments);
             

              return sum;
       }


      function round(x) {
      return Math.round(x);
    }

    $("#NumberofInstallments").change(function(){
    var installments = Number(this.value);
    var pifMonths = '<?php echo $pifMonths; ?>';
    var sifMonths = '<?php echo $sifMonths; ?>';

    var txtFirmBal = $('#total_balance_due').val().replace("$", "");
    txtFirmBal = txtFirmBal.replace(/\,/g,'');
    txtFirmBal = Number(txtFirmBal);


    var amountOffer = $('#TotalAmountOfOffer').val().replace("$", "");
    amountOffer = amountOffer.replace(/\,/g,'');
    amountOffer = Number(amountOffer);

    if(amountOffer != '')
    {
      if(amountOffer >= txtFirmBal)
      {
        if(pifMonths != '')
        {
          if(installments > pifMonths)
          {
            swal("Warning!", "The number of payments exceeds the blanket authority and will be sent to the client for further review.", "warning");
          }
        }
      }
      else if(amountOffer < txtFirmBal)
      {
        if(pifMonths != '')
        {
          if(installments > sifMonths)
          {
            swal("Warning!", "The number of payments exceeds the blanket authority and will be sent to the client for further review.", "warning");
          }
        }
      }
    }
    
  });


  


    function validateFloatKeyPress(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }

    function ispercentage(obj, e, allowDecimal, allowNegative) {
            var key;
            var isCtrl = false;
            var keychar;
            var reg;
            if (window.event) {
                key = e.keyCode;
                isCtrl = window.event.ctrlKey
            } else if (e.which) {
                key = e.which;
                isCtrl = e.ctrlKey;
            }
            if (isNaN(key)) return true;
            keychar = String.fromCharCode(key);
            // check for backspace or delete, or if Ctrl was pressed
            if (key == 8 || isCtrl) {
                return true;
            }
            ctemp = obj.value;
            var index = ctemp.indexOf(".");
            var length = ctemp.length;
            ctemp = ctemp.substring(index, length);
            if (index < 0 && length > 1 && keychar != '.' && keychar != '0') {
                obj.focus();
                return false;
            }
            if (ctemp.length > 2) {
                obj.focus();
                return false;
            }
            if (keychar == '0' && length >= 2 && keychar != '.' && ctemp != '10') {
                obj.focus();
                return false;
            }
            reg = /\d/;
            var isFirstN = allowNegative ? keychar == '-' && obj.value.indexOf('-') == -1 : false;
            var isFirstD = allowDecimal ? keychar == '.' && obj.value.indexOf('.') == -1 : false;
            return isFirstN || isFirstD || reg.test(keychar);
        }


      </script>

      <script>
         function show1(){
         document.getElementById('rblRepDesc2').style.display ='none';
         }
         function show2(){
         document.getElementById('rblRepDesc2').style.display = 'block';
         }


       $(document).ready(function () {
      
      $(".sbtSettlement").click(function(e){


        e.preventDefault();

        if($("#PaymentPlan").is(":checked")){

      if(!validateCalcPaymentPlan())
      {
        return false;
      }

      if(!validateNumberCount())
      {
        return false;
      }

      if(!validatenegativefinalpayment())
      {
        return false;
      }
      '<?php  if($fetchresquerychecklastvalue ['AACACNTR']=='True' || $fetchresquerychecklastvalue ['CLNTCNTR']=='True'){?>'
           if(!validatepreviousvalues()){
             return false;
          }
       '<?php } ?>';
      swal({
        title: "Confirm Entry",
        text: "This offer was submitted, any changes will require the user to recalculate the amount owed.",
        type: "warning",
        showCancelButton: false,
        confirmButtonColor: "#6A9944",
        confirmButtonText: "OK",
        cancelButtonText: "Cancel",
        closeOnConfirm: true
      },function(isConfirm){
        if(isConfirm){
          var AUTOACCEPT = '<?php echo $autoAccept; ?>';
          if(AUTOACCEPT == 'Y')
          { 
            setTimeout(function(){
              swal({
                title: "Confirm Entry",
                text: "There is already an accepted offer, do you want to submit a new offer?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#6A9944",
                confirmButtonText: "Proceed",
                cancelButtonText: "Cancel",
                closeOnConfirm: true
              }, function(isConfirm){
                if (isConfirm) {
                  submitPopup();
                } else {}
              });
            }, 500);
          }
          else
          {
            submitPopup();
          }
        }else{}
      });
   
           // $('#myModal').modal('show');

           if(a > 1)
          {
          a = 1;
          }
      }
      else
      {
      var AUTOACCEPT = '<?php echo $autoAccept; ?>';

        if(AUTOACCEPT == 'Y')
        {
      
        swal({
          title: "Confirm Entry",
          text: "There is already an accepted offer, do you want to submit a new offer?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#6A9944",
          confirmButtonText: "Proceed",
          cancelButtonText: "Cancel",
          closeOnConfirm: true
        }, function(isConfirm){
          
        if(isConfirm){
          submitPopup();
        }else{}
        });
      
        }
        else
        {
          submitPopup();
        }
      }
         
      });
      

    });
  function validatepreviousvalues(){
    var flag = true;
      var TotalAmountOfOffer=$('#TotalAmountOfOffer').val().replace(/[$,]/g, '');
      var monthlyPaymentAmt =$('#monthlyPaymentAmt').val().replace(/[$,]/g, '');
      var NumberofInstallments=$('#NumberofInstallments').val().replace(/[$,]/g, '');
      var EnterInterestRate   =$('#EnterInterestRate').val().replace(/[$,]/g, '');
      var InitialPaymentAmount=$('#InitialPaymentAmount').val().replace(/[$,]/g, '');
      var InitialInstallmentDate=$('#InitialInstallmentDate').val();
      var PPTOTDUE='<?php echo $fetchresquerychecklastvalue['PPTOTDUE'];?>';
      var PPMTHPYMT='<?php echo $fetchresquerychecklastvalue['PPMTHPYMT'];?>';
      var PPNUMMONTH='<?php echo $fetchresquerychecklastvalue['PPNUMMONTH'];?>';
      var PPINTAMNT='<?php echo $fetchresquerychecklastvalue['PPINTAMNT'];?>';
      var PPFSTPAYAM='<?php echo $fetchresquerychecklastvalue['PPFSTPAYAM'];?>';
      var FIRSTPAYDT='<?php echo date('m/d/Y',strtotime($fetchresquerychecklastvalue['FIRSTPAYDT']));?>';

    //  alert(TotalAmountOfOffer+'=='+PPTOTDUE+'==='+monthlyPaymentAmt+'=='+PPMTHPYMT+'==='+NumberofInstallments+'=='+PPNUMMONTH+'=='+EnterInterestRate+'=='+PPINTAMNT+'=='+InitialPaymentAmount+'=='+PPFSTPAYAM+'=='+InitialInstallmentDate+'=='+FIRSTPAYDT);
     if((TotalAmountOfOffer==PPTOTDUE) && (monthlyPaymentAmt==PPMTHPYMT) && (NumberofInstallments==PPNUMMONTH) && (EnterInterestRate==PPINTAMNT) && (InitialPaymentAmount==PPFSTPAYAM) && (InitialInstallmentDate==FIRSTPAYDT)){
       swal("Error!", "An offer on these terms has previously been submitted and responded to. To proceed, you will need to modify the terms of your offer.", "error");
         flag = false;
     }
     if(flag)
         {
          return true;
         }else{
          return false;
         }
       
  }
  
      function submitPopup()
      {
        var stipReq = '<?php echo $stipReq; ?>'; 
    var judgflag = '<?php echo $JDGMTFLAG; ?>';
    var signstijudg = $("input[type='radio'][name='rblStipJudg']:checked").val();
        if (typeof signstijudg !== 'undefined') {
            signstijudg = signstijudg;
        }
    
        var amountOffer = $('#TotalAmountOfOffer').val().replace("$", "");
        amountOffer = amountOffer.replace(/\,/g,'');
        amountOffer = Number(amountOffer);

        var balType = '<?php echo $balanceType; ?>';

      
        if(balType == 'prin')
        {
           var totalBalDue = $('#txtFirmBal').val().replace("$", "");
           totalBalDue = totalBalDue.replace(/\,/g,'');
           totalBalDue = Number(totalBalDue);
           var balanceType = 'Principle Balance';
           var balanceTypeValue = $('#txtFirmBal').val();
        }
        else
        {
           var totalBalDue = $('#totalBalDue').text().replace("$", "");
           totalBalDue = totalBalDue.replace(/\,/g,'');
           totalBalDue = Number(totalBalDue);
           var balanceType = 'Total Balance Due';
           var balanceTypeValue = $('#totalBalDue').text();
        }

        

        // var totalBalDue = $('#totalBalDue').text().replace("$", "");
        // totalBalDue = totalBalDue.replace(/\,/g,'');
        // totalBalDue = Number(totalBalDue);

        var percent = amountOffer/totalBalDue * 100;
        percent = Math.round(percent * 100) / 100;

        if(percent == 'Infinity')
        {
          percent = '0.00';
        }
       
    if($("#PaymentPlan").is(":checked")){
      
      
      if($('#GrandTotal').val() == 0.00 || $('#GrandTotal').val() == '')
      {
        swal("Error", "If you would like to enter a lump sum, please check the lump sum button.", "error");
        return false;
      }
      if((stipReq == 'N') || (stipReq == 'Y' && judgflag == 'Y') || (stipReq == 'Y' && judgflag == 'N' && signstijudg == 'Y' ))
      {
        $('#myModal').modal('show');
        $('.showPaymentPlan').show();
        $('.showLumSum').hide();

        $('#pymtPlnPopup').text($('#TotalAmountOfOffer').val());
        $('#intPmtDatePopup').text($('#InitialInstallmentDate').val());
        $('#mthlyPmtPopup').text($('#monthlyPaymentAmt').val());
        $('#finalPmtDatePopup').text($('#FinalPaymentDate').val());
        $('#totlNoPymtPopup').text($('#NumberofInstallments').val());
        $('#dwnPmtPopup').text($('#InitialPaymentAmount').val());
        $('#finlPmtPopup').text($('#FinalPaymentAmount').val());
        $('#addedIntrPopup').text($('#EnterInterestRate').val());
        $('#totalRePmtPopup').text($('#GrandTotal').val());
        $('#balanceType').text(balanceType);
        $('#percntOffer').text(percent+'%');
      }
      else
      {
        if(amountOffer <= totalBalDue)
        {
          setTimeout(function(){
            swal({title: "Warning!",
              text: "The offer you are submitting falls outside blanket authority for the reasons(s) indicated below: \n\n The client requires the debtor to sign a stiputlation.",
              showConfirmButton: true,
              type: 'warning'
              }, function(isConfirm){
              if (isConfirm) {
                $('#myModal').modal('show');
              } else {}
            });
          }, 500);
        }
        else
        {
          setTimeout(function(){
            swal({title: "Warning!",
              text: "The offer you are submitting falls outside blanket authority for the reasons(s) indicated below: \n\n The client requires the debtor to sign a stiputlation. \n Percentage of offer exceeds 100 with "+percent+"",
              showConfirmButton: true,
              type: 'warning'
              },function(isConfirm){
              if(isConfirm){
                $('#myModal').modal('show');
              }else {}
            });
          }, 500);
        }
        $('.showPaymentPlan').show();
        $('.showLumSum').hide();
        $('#pymtPlnPopup').text($('#TotalAmountOfOffer').val());
        $('#intPmtDatePopup').text($('#InitialInstallmentDate').val());
        $('#mthlyPmtPopup').text($('#monthlyPaymentAmt').val());
        $('#finalPmtDatePopup').text($('#FinalPaymentDate').val());
        $('#totlNoPymtPopup').text($('#NumberofInstallments').val());
        $('#dwnPmtPopup').text($('#InitialPaymentAmount').val());
        $('#finlPmtPopup').text($('#FinalPaymentAmount').val());
        $('#addedIntrPopup').text($('#EnterInterestRate').val());
        $('#totalRePmtPopup').text($('#GrandTotal').val());
        $('#balanceType').text(balanceType);
        $('#percntOffer').text(percent+'%');
      } 
      $('#totalBalancePopup').text(balanceTypeValue);
    }
      if($("#LumpSum").is(":checked")){
        
        if(!validateLumSum())
        {
         return false;
        }
        

         var lumSumAmt = $('#txtLumSumAmt').val().replace("$", "");
         lumSumAmt = lumSumAmt.replace(/\,/g,'');
         lumSumAmt = Number(lumSumAmt);

         var percent = lumSumAmt/totalBalDue * 100;
         percent = Math.round(percent * 100) / 100;
        if(percent == 'Infinity')
        {
          percent = '0.00';
        }

        $('#myModal').modal('show');
        $('.showPaymentPlan').hide();
        $('.showLumSum').show();
        $('#lumbSumAmtPopup').text($('#txtLumSumAmt').val());
        $('#paidFullPopup').text($('#PaymentDate').val());
        $('#totalRePmtPopup').text($('#txtLumSumAmt').val());
        $('#totalBalancePopup').text(balanceTypeValue);
        $('#balanceType').text(balanceType);
        $('#percntOffer').text(percent+'%');
      }
      }
  


    function validateLumSum() 
    {
      var flag = true;
      var enterAmount = $('#txtLumSumAmt').val();
      var enterAmount1=$('#txtLumSumAmt').val().replace(/[$,]/g, '');
      var paymentDate = $('#PaymentDate').val();
      var enterAmountprevious='<?php echo $fetchresquerychecklastvalue ['LUMSUMAMNT'];?>';
      var paymentDateprevious = '<?php echo date('m/d/Y',strtotime($fetchresquerychecklastvalue ['LUMPAYDATE']));?>';

      if(enterAmount == '' || enterAmount == '$NaN' || enterAmount == 'NaN' || enterAmount == '$0.00' || enterAmount == '0' || enterAmount == '0.00' || enterAmount == '$')
      {
        $('#txtLumSumAmtError').css('display', 'block');
          $('#txtLumSumAmtError').text("This field is required");
          flag = false;
      }
      else
      {
            $('#txtLumSumAmtError').css('display', 'none');
      }

      $('#txtLumSumAmt').on('keyup', function(){       
        if(this.value == '' || this.value == '$NaN' || this.value == 'NaN' || this.value == '$0.00' || this.value == '0' || this.value == '0.00' || this.value == '$')
         {
            $('#txtLumSumAmtError').css('display', 'block');
            $('#txtLumSumAmtError').text("This field is required"); 
         }
         else
         {
            $('#txtLumSumAmtError').css('display', 'none');
         }
      })

      if(paymentDate == '')
      {
         $('#PaymentDateError').css('display', 'block');
         $('#PaymentDateError').text("Date can not more than 60 days");
          flag = false;
      }
      else
      {
        $('#PaymentDateError').css('display', 'none');
      }

      $('#PaymentDate').on('keyup', function(){
        if(this.value == '')
        {
          $('#PaymentDateError').css('display', 'block');
          $('#PaymentDateError').text("Date can not more than 60 days");
        }
        else
        {
          $('#PaymentDateError').css('display', 'none');
        }

        
      })
      '<?php  if($fetchresquerychecklastvalue ['AACACNTR']=='True' || $fetchresquerychecklastvalue ['CLNTCNTR']=='True'){?>'
      if((enterAmountprevious==enterAmount1) && (paymentDate==paymentDateprevious)){
         $('#txtLumSumAmtError').css('display', 'block');
         $('#txtLumSumAmtError').text("An offer on these terms has previously been submitted and responded to. To proceed, you will need to modify the terms of your offer."); 
         flag = false; 
        }else{
          $('#txtLumSumAmtError').css('display', 'none');
        }
        '<?php } ?>';
      if(flag)
      {
        return true;
      }
      else
      {
        return false;
      }
    }

    $("#btnSubmitFrm").click(function(e){
        e.preventDefault();

        $('#btnSubmitFrm').prop('disabled', true);
        $('#loader').show();
        var getData = fetchFormdata();

        $.ajax({
      type: "POST",
      url: "ajaxSubmitSettlement.php",
      data:getData,
      enctype: 'multipart/form-data',
      dataType:"json",
      cache : false,
      processData: false,
      contentType: false,
      success: function(data){
        // alert("hello");
        if(data.status == 4)
        { 
          $('#myModal').modal('hide');
          $('#btnSubmitFrm').prop('disabled', false);
          $('#loader').hide();
          swal("Error", "You already have an accepted offer on the account.", "error");
        }          
        else if(data.status == 3)
        {
          $('#myModal').modal('hide');
          $('#btnSubmitFrm').prop('disabled', false);
          $('#loader').hide();
          
          // setTimeout(function () {
            swal({title: "AUTO ACCEPTED!",
              text: "AUTO ACCEPTED : The offer you submitted has been accepted.  Please proceed with the terms of the offer.",
              timer: 3500,
              showConfirmButton: false,
              type: 'success'
            },function(){
              swal({
                title: "Your offer has been submitted successfully!",
                //text: "Where do yo want to redirect?",
                type: "success",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "Return to dashboard",
                cancelButtonText: "Return to account detail",
              }, function (isConfirm) {
                if (isConfirm) {
                  window.location = '../inventory_layout';
                } else {
                  window.location = '../searchacc';
                }
              });
            });
         
        }
        else if(data.status == 2)
        {
          $('#myModal').modal('hide');
          $('#btnSubmitFrm').prop('disabled', false);
          $('#loader').hide();
        
        
          // Auto Rejected popup is hide according to client requirement!
          setTimeout(function () {
            swal({
              title: "Your offer has been submitted successfully!",
              // text: "Where do yo want to redirect?",
              type: "success",
              showCancelButton: true,
              closeOnConfirm: false,
              confirmButtonText: "Return to dashboard",
              cancelButtonText: "Return to account detail",
            }, function (isConfirm) {
              if (isConfirm) {
                window.location = '../inventory_layout';
              } else {
                 window.location = '../searchacc';
              }
            });
          }, 3000);
        }
        else if(data.status == 1)
        {
          $('#loader').hide();
          $('#myModal').modal('hide');
          $('#btnSubmitFrm').prop('disabled', false);
        
          swal({
            title: "Your offer has been submitted successfully!",
            // text: "Where do yo want to redirect?",
            type: "success",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Return to dashboard",
            cancelButtonText: "Return to account detail",
          }, function (isConfirm) {
            if (isConfirm) {
              window.location = '../inventory_layout';
            } else {
               window.location = '../searchacc';
            }
          });
         
        }
      },
      error: function(){
                // alert("error");

           $('#loader').hide();
           $('#btnSubmitFrm').prop('disabled', false);
           $('#myModal').modal('hide');
        alert("Something went wrong. There is an error with your submission.");
      } 
    }); 
    });


   function fetchFormdata()
  {
    if($("#LumpSum").is(":checked"))
    {
      var CKBXLUMSUM = 'True';
      var CHKBXPAYPL = 'False';
    }
    else if($("#PaymentPlan").is(":checked")){
      var CKBXLUMSUM = 'False';
      var CHKBXPAYPL = 'True';
    }     

    var total_balance_due = $('#total_balance_due').val().replace("$", "");
      total_balance_due = total_balance_due.replace(/\,/g,'');
      total_balance_due = Number(total_balance_due);

    if($("input[type='radio'][name='rblDebtorResidence']:checked").val() == 'R')
    {
      var mRTPAY = $('#MonthlyPaymentAmount01').val().replace("$", "");
        mRTPAY = mRTPAY.replace(/\,/g,'');
        mRTPAY = Number(mRTPAY);
      var CBXMTHPAY = $("input[type='checkbox'][name='CBXMTHPAY']:checked").val();
    }
    else if ($("input[type='radio'][name='rblDebtorResidence']:checked").val() == 'O')
    {
      var mRTPAY = $('#MonthlyPaymentAmount02').val().replace("$", "");
        mRTPAY = mRTPAY.replace(/\,/g,'');
        mRTPAY = Number(mRTPAY);

      var CBXMTHPAY = $("input[type='checkbox'][name='CBXMTHPAY2']:checked").val();

      var mRTYRS = $('#OriginalTermofMortgage').val();
        if (typeof mRTYRS !== 'undefined') {
          mRTYRS = $('#OriginalTermofMortgage').val();
        }

      var rEFI = $("input[type='checkbox'][name='REFI']:checked").val();
        if (typeof rEFI !== 'undefined') {
          rEFI = rEFI;
        }
    }
   

    var txtAdditionalCosts = $('#txtAdditionalCosts').val().replace("$", "");
    txtAdditionalCosts = txtAdditionalCosts.replace(/\,/g,'');
    txtAdditionalCosts = Number(txtAdditionalCosts);

    var txtFIRMINTAMNT = $('#txtFIRMINTAMNT').val().replace("$", "");
    txtFIRMINTAMNT = txtFIRMINTAMNT.replace(/\,/g,'');
    txtFIRMINTAMNT = Number(txtFIRMINTAMNT);

    var txtFirmBal = $('#txtFirmBal').val().replace("$", "");
    txtFirmBal = txtFirmBal.replace(/\,/g,'');
    txtFirmBal = Number(txtFirmBal);

    var lblAACACosts = $("#lblAACACosts").val().replace("$", "");
    lblAACACosts = lblAACACosts.replace(/\,/g,'');


    var txtAttorneyFees = $('#txtAttorneyFees').val().replace("$", "");
    txtAttorneyFees = txtAttorneyFees.replace(/\,/g,'');
    txtAttorneyFees = Number(txtAttorneyFees);

    var txtLumSumAmt = $('#txtLumSumAmt').val().replace("$", "");
    txtLumSumAmt = txtLumSumAmt.replace(/\,/g,'');
    txtLumSumAmt = Number(txtLumSumAmt);

         

        var grandTotal = $('#GrandTotal').val().replace("$", "");
        grandTotal = grandTotal.replace(/\,/g,'');
        grandTotal = Number(grandTotal);

        var totalAmountOfOffer = $('#TotalAmountOfOffer').val().replace("$", "");
        totalAmountOfOffer = totalAmountOfOffer.replace(/\,/g,'');
        totalAmountOfOffer = Number(totalAmountOfOffer);

        var monthlyPaymentAmt = $('#monthlyPaymentAmt').val().replace("$", "");
        monthlyPaymentAmt = monthlyPaymentAmt.replace(/\,/g,'');
        monthlyPaymentAmt = Number(monthlyPaymentAmt);

        var finalPaymentAmount = $('#FinalPaymentAmount').val().replace("$", "");
        finalPaymentAmount = finalPaymentAmount.replace(/\,/g,'');
        finalPaymentAmount = Number(finalPaymentAmount);

        if($('#EnterInterestRate').val() == 0.00 || $('#EnterInterestRate').val() == 0.000 || $('#EnterInterestRate').val() == '' || $('#EnterInterestRate').val() == 0)
        {
          CBXINT = 'False';
        }
        else
        {
          CBXINT = 'True';
        }

        var addedInterest = $('#AddedInterest').val().replace("$", "");
        addedInterest = addedInterest.replace(/\,/g,'');
        addedInterest = Number(addedInterest);

        var textJudgmentAmount = $('#textJudgmentAmount').val().replace("$", "");
        textJudgmentAmount = textJudgmentAmount.replace(/\,/g,'');
        textJudgmentAmount = Number(textJudgmentAmount);

        var initialPaymentAmount = $('#InitialPaymentAmount').val().replace("$", "");
        initialPaymentAmount = initialPaymentAmount.replace(/\,/g,'');
        initialPaymentAmount = Number(initialPaymentAmount);
    
    
    //add percentage in database start
     
       var percentoffer = '';
    if($("#PaymentPlan").is(":checked")){
      
      var amountOffer = $('#TotalAmountOfOffer').val().replace("$", "");
      amountOffer = amountOffer.replace(/\,/g,'');
      amountOffer = Number(amountOffer);

      var balType = '<?php echo $balanceType; ?>';
      if(balType == 'prin')
      {
         var totalBalDue = $('#txtFirmBal').val().replace("$", "");
         totalBalDue = totalBalDue.replace(/\,/g,'');
         totalBalDue = Number(totalBalDue);
         var balanceType = 'Principle Balance';
         var balanceTypeValue = $('#txtFirmBal').val();
      }
      else
      {
         var totalBalDue = $('#totalBalDue').text().replace("$", "");
         totalBalDue = totalBalDue.replace(/\,/g,'');
         totalBalDue = Number(totalBalDue);
         var balanceType = 'Total Balance Due';
         var balanceTypeValue = $('#totalBalDue').text();
      }
      
      var percentoffer = amountOffer/totalBalDue * 100;
      percentoffer = Math.round(percentoffer * 100) / 100;
      
      if(percentoffer == 'Infinity')
      {
        percentoffer = '0.00';
      }
    }else if($("#LumpSum").is(":checked")){
      
      var totalBalDue = $('#totalBalDue').text().replace("$", "");
      totalBalDue = totalBalDue.replace(/\,/g,'');
      totalBalDue = Number(totalBalDue);
      
      var lumSumAmt = $('#txtLumSumAmt').val().replace("$", "");
      lumSumAmt = lumSumAmt.replace(/\,/g,'');
      lumSumAmt = Number(lumSumAmt);

      var percentoffer = lumSumAmt/totalBalDue * 100;
      // percentoffer = Math.round(percentoffer * 100) / 100;
      

          
      if(percentoffer == 'Infinity')
      {
        percentoffer = '0.00';
      }
    }
      

    
        // if($("input[type='radio'][name='hrdshp']:checked").val() == 'Yes')
        // {
        //    var file_data = $('#FileUpload').prop('files')[0];   
        //    var form_data = new FormData();                  
        //    form_data.append('file', file_data);
        // }
        // else
        // {
        //   var form_data = '';
        // }
        
        var VERIFIED = $("input[type='radio'][name='Verify']:checked").val();
        if (typeof VERIFIED !== 'undefined') {
            VERIFIED = VERIFIED;
        }
        else
        {
          VERIFIED = '';
        }

        var RENTOWN = $("input[type='radio'][name='rblDebtorResidence']:checked").val();
        if (typeof RENTOWN !== 'undefined') {
            RENTOWN = RENTOWN;
        }
        else
        {
          RENTOWN = '';
        }

        var RBLREP = $("input[type='radio'][name='rblRep']:checked").val();
        if (typeof RBLREP !== 'undefined') {
            RBLREP = RBLREP;
        }
        else
        {
          RBLREP = '';
        }
        

    var RJDDTS = $("#dtofformation").val();
    // console.log(RJDDTS);
    if(RJDDTS == ''){
      RJDDTS = '';
    }else{
      RJDDTS = RJDDTS;
    }


        var CBXMRTYRS = $("input[type='checkbox'][name='CBXMRTYRS']:checked").val();
        if (typeof CBXMRTYRS !== 'undefined') {
            CBXMRTYRS = CBXMRTYRS;
        }
        else
        {
          CBXMRTYRS = '';
        }

        var CBXYRSLEFT = $("input[type='checkbox'][name='CBXYRSLEFT']:checked").val();
        if (typeof CBXYRSLEFT !== 'undefined') {
            CBXYRSLEFT = CBXYRSLEFT;
        }
        else
        {
          CBXYRSLEFT = '';
        }

        var CBXTOTDEBT = $("input[type='checkbox'][name='cbxTotDebt']:checked").val();
        if (typeof CBXTOTDEBT !== 'undefined') {
            CBXTOTDEBT = CBXTOTDEBT;
        }
        else
        {
          CBXTOTDEBT = '';
        }

        var rblStipJudg = $("input[type='radio'][name='rblStipJudg']:checked").val();
        if (typeof rblStipJudg !== 'undefined') {
            rblStipJudg = rblStipJudg;
        }
        else
        {
          rblStipJudg = '';
        }
        
        var cbxTotAutoNew = $("input[type='checkbox'][name='cbxTotAuto']:checked").val();
        if (typeof cbxTotAutoNew !== 'undefined') {
            cbxTotAutoNew = cbxTotAutoNew;
        }
        else
        {
          cbxTotAutoNew = '';
        }

        var cbsTotStudent = $("input[type='checkbox'][name='cbsTotStudent']:checked").val();
        if (typeof cbsTotStudent !== 'undefined') {
            cbsTotStudent = cbsTotStudent;
        }
        else
        {
          cbsTotStudent = '';
        }

        var cbxTotMortgages = $("input[type='checkbox'][name='cbxTotMortgages']:checked").val();
        if (typeof cbxTotMortgages !== 'undefined') {
            cbxTotMortgages = cbxTotMortgages;
        }
        else
        {
          cbxTotMortgages = '';
        }

        var rblDebtorEmployed = $("input[type='radio'][name='rblDebtorEmployed']:checked").val();
        if (typeof rblDebtorEmployed !== 'undefined') {
            rblDebtorEmployed = rblDebtorEmployed;
        }
        else
        {
          rblDebtorEmployed = '';
        }

        var hrdshp = $("input[type='radio'][name='hrdshp']:checked").val();
        if (typeof hrdshp !== 'undefined') {
            hrdshp = hrdshp;
        }
        else
        {
          hrdshp = '';
        }
    
    var conrep = $("input[type='radio'][name='rblRep']:checked").val();
    // var conrepdesc = $("input[type='radio'][name='rblRepDesc']:checked").val();
    if(conrep == 'Y'){
      var rblRepDesc = $("input[type='radio'][name='rblRepDesc']:checked").val();
      if (typeof rblRepDesc !== 'undefined') {
        rblRepDesc = rblRepDesc;
      }
      else
      {
        rblRepDesc = '';
      }
    }else
    {
      rblRepDesc = '';
    }
    

        // AACANet comment section

        if($("#AACAACCEPT").is(":checked")){
          var AACAACCEPT = 'True';
        }
        else
        {
          var AACAACCEPT = 'False';
        }

        // if($("#AACAREJECT").is(":checked")){
          // var AACAREJECT = 'True';
        // }
        // else
        // {
          // var AACAREJECT = 'False';
        // }


        if($("#REFERCLNT").is(":checked")){
          var REFERCLNT = 'True';
        }
        else
        {
          var REFERCLNT = 'False';
        }
     
        // Client comment section

        if($("#CLNTACCEPT").is(":checked")){
          var CLNTACCEPT = 'True';
        }
        else
        {
          var CLNTACCEPT = 'False';
        }

 
    //For Deny Offer Aaca
        if($("#AACAREJECT").is(":checked")){
            var AACAREJECT = 'True';
            var AACADENYR = $("#AACADENYR").val();  
        }
        else
        {
          var AACAREJECT = 'False';
          var AACADENYR = '';
        }
    
    if($("#AACAADDINF").is(":checked")){
            var AACAADDINF = 'True';
            var ADDINFREAS = $("#ADDINFREAS").val();  
        }
        else
        {
          var AACAADDINF = 'False';
          var ADDINFREAS = '';
        }

        if($("#CLNTREJECT").is(":checked")){
            var CLNTREJECT = 'True';
            var CLNTDENYR = $("#CLNTDENYR").val();  
        }
        else
        {
          var CLNTREJECT = 'False';
          var CLNTDENYR = '';
        }
    
    if($("#CBXCLNTINF").is(":checked")){
            var CBXCLNTINF = 'True';
            var CLNTREAS = $("#CLNTREAS").val();  
        }
        else
        {
          var CBXCLNTINF = 'False';
          var CLNTREAS = '';
        }


        if($("#SubmitCounterchk").is(":checked")){
            var AACACNTR = 'True';
            var AACACounterOfferAmount = $('#AACACounterOfferAmount').val();
            AACACounterOfferAmount = AACACounterOfferAmount.replace(/,/g, '');
            
            var AACACounterOfferAmountDown = $('#AACACounterOfferAmountDown').val();
            AACACounterOfferAmountDown = AACACounterOfferAmountDown.replace(/,/g, '');
            
            var AACACounterOfferMonthlyPayment = $('#AACACounterOfferMonthlyPayment').val();
            AACACounterOfferMonthlyPayment = AACACounterOfferMonthlyPayment.replace(/,/g, '');
            
            var AACACounterOfferNumberOfPayments = $('#AACACounterOfferNumberOfPayments').val();
            var deadlineDate = $('#AACACounterOfferDeadlineDate').val();
            var firstDuePymt = $('#AACACounterOfferFirstPaymentDate').val();

            var finalPymtAmt = $('#AACACounterOfferFinalPayment').val();
            finalPymtAmt = finalPymtAmt.replace(/,/g, '');


            var AACACNTOFF = "$!@#"+","+AACACounterOfferAmount+","+AACACounterOfferAmountDown+","+AACACounterOfferMonthlyPayment+","+AACACounterOfferNumberOfPayments+","+finalPymtAmt+","+deadlineDate+","+firstDuePymt;
        }
        else
        {
          var AACACNTR = 'False';
          var AACACNTOFF = '';
        }

        if($("#SubmitCounterchkclient").is(":checked")){
          var CLNTCNTR = 'True';
          var AmountofCounterOffer = $('#AACACounterOfferAmount').val();
          AmountofCounterOffer = AmountofCounterOffer.replace(/,/g, '');

          var AmountDown = $('#AACACounterOfferAmountDown').val();
          AmountDown = AmountDown.replace(/,/g, '');

          var MonthlyPaymentAmount = $('#AACACounterOfferMonthlyPayment').val();
          MonthlyPaymentAmount = MonthlyPaymentAmount.replace(/,/g, '');

          var NumberofPayments = $('#AACACounterOfferNumberOfPayments').val();
          var FinalPaymentAmountCntr = $('#AACACounterOfferFinalPayment').val();
          FinalPaymentAmountCntr = FinalPaymentAmountCntr.replace(/,/g, '');
          
          var DeadlineofAcceptance1 = $('#AACACounterOfferDeadlineDate').val();
          var FirstPaymentDue = $('#AACACounterOfferFirstPaymentDate').val();

          var CLNTCNINST = "$!@#"+","+AmountofCounterOffer+","+AmountDown+","+MonthlyPaymentAmount+","+NumberofPayments+","+FinalPaymentAmountCntr+","+DeadlineofAcceptance1+","+FirstPaymentDue;
        }
        else
        {
          var CLNTCNTR = 'False';
          var CLNTCNINST = '';
        }
         
          var AACAEMAIL = $('#ReviewerEmail').val();
          var AACAREVNAM = $('#ReviewerName').val();
        if (typeof AACAEMAIL !== 'undefined')
        {
           AACAEMAIL = $('#ReviewerEmail').val();
        }
        else
        {
           AACAEMAIL = '';
        }

        if(typeof AACAREVNAM !== 'undefined')
        {
           AACAREVNAM = $('#ReviewerName').val();
        }
        else
        {
           AACAREVNAM = '';
        }

        var AACAADDCOM = $('#AACAADDCOM').val();

        if(typeof AACAADDCOM !== 'undefined')
        {
           AACAADDCOM = $('#AACAADDCOM').val();
        }
        else
        {
           AACAADDCOM = '';
        }


    var formData = new FormData();    

    formData.append('WFORGNAME', $("#clientName").text());
    formData.append('RACTNM', $("#RACTNM").text());
    formData.append('WFNAME', $("#WFNAME").text());
    formData.append('RMSASNDE01', $("#RMSASNDE01").text());
    formData.append('SIFFCNAME', $("#textName").val());
    formData.append('SIFFCPHONE', $("#textPhone").val());
    formData.append('SIFFCEMAIL', $("#textEmailAddress").val());
    formData.append('RACTST', '<?php echo $fetchres1['RACTST']; ?>');
    // formData.append('SYSDESC', '<?php echo $fetchres1['NTE_DESC']; ?>');
    // Change NTE DESC to Current DESC on Date 04/10/2023 as the data inserted to setform table from this field.
    formData.append('SYSDESC', '<?php echo $fetchres1['SYSDESC']; ?>');

    formData.append('FILNUM', '<?php echo $fetchres1['FILNUM']; ?>');
    formData.append('ORGCODE', '<?php echo $fetchres1['ORGCODE']; ?>');
    formData.append('OTHACCTNUM', '<?php echo $fetchres1['RACTNM']; ?>');
    formData.append('DOADATE', $("#deadofaccpt").val());
    formData.append('TOTBALDUE', total_balance_due);
    formData.append('PERCOFFER', percentoffer);
    formData.append('VERIFIED', VERIFIED);
    formData.append('RENTOWN', RENTOWN);
    formData.append('MRTYRS', mRTYRS);
    formData.append('MRTPAY', mRTPAY);
    formData.append('MRTYRSLEFT', $("#YearsLeftontheMortgage").val());
    formData.append('REFI', rEFI);
    formData.append('TOTDEBT', $('#txtTotDebt').val().replace(",", ""));
    formData.append('FIRMUSERID', $("#textEmailAddress").val());
    formData.append('FSOURCE', '<?php echo $fetchres['FSOURCE']; ?>');
    formData.append('FREPRESENT', $('#consumerNotes').val());
    formData.append('RBLREP', RBLREP);
    formData.append('WFDTCHGDT', $("#WFDTCHGDT").text());
    formData.append('RJDDT', RJDDTS);
    formData.append('ADDITCOST', txtAdditionalCosts);
    formData.append('COSTPROC', lblAACACosts);
    formData.append('FIRMINTAMT', txtFIRMINTAMNT);
    formData.append('PAYPLANDET', $("#PAYPLANDET").val().trim());
    formData.append('SIFFIRMBAL', txtFirmBal);
    formData.append('SIFFRMCOST', txtAttorneyFees);
    formData.append('CKBXLUMSUM', CKBXLUMSUM);
    formData.append('LUMSUMAMNT', txtLumSumAmt);
    formData.append('LUMPAYDATE', $("#PaymentDate").val());
    formData.append('CHKBXPAYPL', CHKBXPAYPL);
    formData.append('PPGROSSAMT', grandTotal);
    formData.append('LASTPAYDT', $('#FinalPaymentDate').val());
    formData.append('PPTOTDUE', totalAmountOfOffer);
    formData.append('PPNUMMONTH', $('#NumberofInstallments').val());
    formData.append('PPMTHPYMT', monthlyPaymentAmt);
    formData.append('PPLSTPAYAM', finalPaymentAmount);
    formData.append('FIRSTPAYDT', $('#InitialInstallmentDate').val());
    formData.append('CBXINT', CBXINT);
    formData.append('PPINTAMNT', $('#EnterInterestRate').val());
    formData.append('PRTAMNT', grandTotal);
    formData.append('ADDEDINT', addedInterest);
    formData.append('CBXMRTYRS', CBXMRTYRS);
    formData.append('CBXMTHPAY', CBXMTHPAY);
    formData.append('CBXYRSLEFT', CBXYRSLEFT);
    formData.append('CBXTOTDEBT', CBXTOTDEBT);
    formData.append('FRMJUGAMNT', textJudgmentAmount);
    formData.append('STIPJUDG', rblStipJudg);
    formData.append('DDBFUNDS', $("#DDBFUNDS").val());
    formData.append('CBXTOTAUTO', cbxTotAutoNew);
    formData.append('CBXTOTSTUD', cbsTotStudent);
    formData.append('TOTMRTDEBT', $('#txtTotalMorgages').val());
    formData.append('TOTAUTODBT', $('#txtTotalAuto').val());
    formData.append('TOTSTUDDBT', $('#txtStudentLoans').val());
    formData.append('REFIDATE', $('#DateofLastRefinancing').val());
    formData.append('CBXTOTMORT', cbxTotMortgages);
    formData.append('SIFFCEXT', '<?php echo $fetchres['SIFFCEXT']; ?>');
    formData.append('DBTREMPLYD', rblDebtorEmployed);
    formData.append('PPFSTPAYAM', initialPaymentAmount);
    formData.append('ROFFCD', '<?php echo $fetchres1['ROFFCD'] ?>');
    formData.append('HARDSHIPCLAIM', hrdshp);
    formData.append('PNDGARN', $("input[type='radio'][name='Garnish']:checked").val());
    formData.append('PNDBNKLEVY', $("input[type='radio'][name='bank']:checked").val());
    formData.append('PNDLIENS', $("input[type='radio'][name='liens']:checked").val());
    formData.append('PNDJUDGEXE', $("input[type='radio'][name='Judg']:checked").val());
    formData.append('CONSMR_NOTES', $("#consumerNotes").val());
    formData.append('SPR_DATERANGE', '<?php echo $DATERANGE; ?>');
    formData.append('SPR_BALRANGE', '<?php echo $BALRANGE; ?>');
    formData.append('SPR_USSTATE', '<?php echo $USSTATE; ?>');
    formData.append('SPR_STAGECODE', '<?php echo $STAGECODE; ?>');
    formData.append('SPR_FILELOC', '<?php echo $FILELOC; ?>');
    formData.append('SPR_STRACCTNUM', '<?php echo $STRACCTNUM; ?>');
    formData.append('SPR_ENDACCTNUM', '<?php echo $ENDACCTNUM; ?>');
    formData.append('SPR_FIRMCODE', '<?php echo $FIRMCODE; ?>');
    formData.append('SPR_OFFERRANGE', '<?php echo $OFFERRANGE; ?>');
    formData.append('SPR_DAYSCODE', '<?php echo $DAYSCODE; ?>');
    formData.append('SPR_PIFPPLANOK', '<?php echo $PIFPPLANOK; ?>');
    formData.append('SPR_PIFMONTHS', '<?php echo $pifMonths; ?>');
    formData.append('SPR_PIFMTHPYMT', '<?php echo $pifMonthPymt; ?>');
    formData.append('SPR_PIFMTHPERC', '<?php echo $PIFMTHPERC; ?>');
    formData.append('SPR_PIFMINVAL', '<?php echo $PIFMINVAL; ?>');
    formData.append('SPR_PIFDWNPERC', '<?php echo $PIFDWNPERC; ?>');
    formData.append('SPR_LUMPPERC', '<?php echo $LUMPPERC; ?>');
    formData.append('SPR_SIFPPLANOK', '<?php echo $SIFPPLANOK; ?>');
    formData.append('SPR_PPLANPERC', '<?php echo $pPlanPerc; ?>');
    formData.append('SPR_SIFMONTHS', '<?php echo $sifMonths; ?>');
    formData.append('SPR_SIFMTHPYMT', '<?php echo $SIFMTHPYMT; ?>');
    formData.append('SPR_SIFMTHPERC', '<?php echo $SIFMTHPERC; ?>');
    formData.append('SPR_SIFMINVAL', '<?php echo $SIFMINVAL; ?>');
    formData.append('SPR_SIFDWNPERC', '<?php echo $SIFDWNPERC; ?>');
    formData.append('SPR_SPECCOND', '<?php echo $SPECCOND; ?>');
    formData.append('SPR_ADDEDNOTES', '<?php echo $ADDEDNOTES; ?>');
    formData.append('SPR_CONFEMAIL', '<?php echo $CONFEMAIL; ?>');
    formData.append('SPR_SIFNAME1', '<?php echo $SIFNAME1; ?>');
    formData.append('SPR_SIFEMAIL1', '<?php echo $SIFEMAIL1; ?>');
    formData.append('SPR_SIFCOND1', '<?php echo $SIFCOND1; ?>');
    formData.append('SPR_SIFNAME2', '<?php echo $SIFNAME2; ?>');
    formData.append('SPR_SIFEMAIL2', '<?php echo $SIFEMAIL2; ?>');
    formData.append('SPR_SIFCOND2', '<?php echo $SIFCOND2; ?>');
    formData.append('SPR_SIFNAME3', '<?php echo $SIFNAME3; ?>');
    formData.append('SPR_SIFEMAIL3', '<?php echo $SIFEMAIL3; ?>');
    formData.append('SPR_SIFCOND3', '<?php echo $SIFCOND3; ?>');
    formData.append('SPR_SIFNAME4', '<?php echo $SIFNAME4; ?>');
    formData.append('SPR_SIFEMAIL4', '<?php echo $SIFEMAIL4; ?>');
    formData.append('SPR_SIFCOND4', '<?php echo $SIFCOND4; ?>');
    formData.append('SPR_SIFFORMOK', '<?php echo $SIFFORMOK; ?>');
    formData.append('SPR_STIPREQ', '<?php echo $stipReq; ?>');
    formData.append('SPR_CLNTGUIDE', '<?php echo $CLNTGUIDE; ?>');
    formData.append('SPR_HARDSHIPOFF', '<?php echo $HARDSHIPOFF; ?>');
    formData.append('SPR_STPPENEXE', '<?php echo $STPPENEXE; ?>');
    formData.append('SPR_STPINT', '<?php echo $STPINT; ?>');
    formData.append('SPR_Default_Record', '<?php echo $Default_Record; ?>');
    formData.append('SPR_BALTYPE', '<?php echo $BALTYPE; ?>');
    formData.append('AACAACCEPT', AACAACCEPT);
    formData.append('AACAREJECT', AACAREJECT);
    formData.append('AACADENYR', AACADENYR);
    formData.append('REFERCLNT', REFERCLNT);
    formData.append('CLNTACCEPT', CLNTACCEPT);
    formData.append('CLNTREJECT', CLNTREJECT);
    formData.append('CLNTDENYR', CLNTDENYR);
    formData.append('AACAEMAIL', AACAEMAIL);
    formData.append('AACAREVNAM', AACAREVNAM);
    formData.append('CLNTREVNAM', $('#textName2').val());
    formData.append('CLNTEMAIL', $('#ReviewerEmail2').val());
    formData.append('AACAADDINF', AACAADDINF);
    formData.append('ADDINFREAS', ADDINFREAS);
    formData.append('CBXCLNTINF', CBXCLNTINF);
    formData.append('CLNTREAS', CLNTREAS);
    formData.append('AACACNTR', AACACNTR);
    formData.append('AACACNTOFF', AACACNTOFF);
    formData.append('CLNTCNTR', CLNTCNTR);
    formData.append('CLNTCNINST', CLNTCNINST);
    formData.append('CLNTADDINF', $('#CLNTADDINF').val());
    formData.append('AACAADDCOM', AACAADDCOM);
    formData.append('RBLREP_DESC', rblRepDesc);
    formData.append('FIRMCODENEWADDED', '<?php echo $fetchres['RAATTY']; ?>');

    if(typeof hrdshp !== 'undefined'  && hrdshp == 'Yes')
    {
      formData.append('HARDSHIPCOPY', $("#FileUpload")[0].files[0]);                  
    }
    else
    {
      formData.append('HARDSHIPCOPY', '');   
    }

      
    return formData;
  }

  

      $("#btnSubmitReply").click(function(e){ 
        e.preventDefault();

        if(!validateAacaCkbx())
        {
          return false;
        }
    
    if($("#SubmitCounterchk").is(':checked'))
    {
      if(!validateAACAcounterCalc())
      {
        return false;
      }
      if(!validatenegativefinalpaymentforaacaclient())
      {
        return false;
      }
    }

    var aacaaccept='<?php echo $fetchres['AACAACCEPT'];?>'; 
    var aacareject='<?php echo $fetchres['AACAREJECT'];?>'; 
    var aacarfrtoclient='<?php echo $fetchres['AACARFRTOCLNT'];?>'; 
    var aacaacounter='<?php echo $fetchres['AACACNTR'];?>'; 
    var aacaaddinfo='<?php echo $fetchres['AACAADDINF'];?>'; 
    var clientaccept='<?php echo $fetchres['CLNTACCEPT'];?>'; 
    var clientreject='<?php echo $fetchres['CLNTREJECT'];?>'; 
    var clientcounter='<?php echo $fetchres['CLNTCNTR'];?>'; 
    var clientaddinfo='<?php echo $fetchres['CBXCLNTINF'];?>';
    if(aacaaccept=='True' || aacareject=='True' || aacarfrtoclient=='True' || aacaacounter=='True' || aacaaddinfo=='True' || clientaccept=='True' || clientreject=='True' || clientcounter=='True' || clientaddinfo=='True'){
        
        swal({
          title: "Confirm Entry",
          text: "There is already a response submitted, do you want to proceed with new response?", 
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#6A9944",
          confirmButtonText: "Proceed",
          cancelButtonText: "Cancel",
          closeOnConfirm: true
        }, function(isConfirm){
          
        if(isConfirm){

          submitPopupAACA();
        }else{}
        });
      
        }
        else
        {

          submitPopupAACA();
        }
      
      })
function submitPopupAACA(){
  if($("#LumpSum").is(":checked")){
         var totalBalDue = $('#totalBalDue').text().replace("/^\s+|\s+$/gm", "");
         totalBalDue = totalBalDue.replace("$", "");
         totalBalDue = totalBalDue.replace(/\,/g,'');
         totalBalDue = Number(totalBalDue);

         var balType = '<?php echo $balanceType; ?>';
   
        if(balType == 'prin')
        {
           var totalBalDue = $('#txtFirmBal').val().replace("$", "");
           totalBalDue = totalBalDue.replace(/\,/g,'');
           totalBalDue = Number(totalBalDue);
           var balanceType = 'Principle Balance';
           var balanceTypeValue = $('#txtFirmBal').val();
        }
        else
        {
           var totalBalDue = $('#totalBalDue').text().replace("$", "");
           totalBalDue = totalBalDue.replace(/\,/g,'');
           totalBalDue = Number(totalBalDue);
           var balanceType = 'Total Balance Due';
           var balanceTypeValue = $('#totalBalDue').text();
        }

       
         var lumSumAmt = $('#txtLumSumAmt').val().replace("$", "");
         lumSumAmt = lumSumAmt.replace(/\,/g,'');
         lumSumAmt = Number(lumSumAmt);



         var percent = lumSumAmt/totalBalDue * 100;
         percent = Math.round(percent * 100) / 100;

         if(percent == 'Infinity')
         {
          percent = '0.00';
         }



         if($("#SubmitCounterchk").is(":checked"))
          {
            $('#myAacaModal').modal('show');
            $('.showLumSum').hide();
            $('.showPaymentPlan').hide();
            $('.showCounterPlan').show();

            $('#conterOfferPopup').text($('#AACACounterOfferAmount').val());
            $('#amountDownPopup').text($('#AACACounterOfferAmountDown').val());
            $('#monthlyPaymentPopup').text($('#AACACounterOfferMonthlyPayment').val());
            $('#noOfPmtPopup').text($('#AACACounterOfferNumberOfPayments').val());
            $('#finalPmtPopup').text($('#AACACounterOfferNumberOfPayments').val());
            $('#firstPymtPopup').text($('#AACACounterOfferDeadlineDate').val());
            $('#deadlineDatePopup').text($('#AACACounterOfferFirstPaymentDate').val());
            $('#aacatotalRePmtPopup').text($('#txtLumSumAmt').val());
            $('#totalBalancePopup1').text(balanceTypeValue);
            $('#balanceType01').text(balanceType);
            $('#aacapercntOffer').text(percent+'%');

            document.getElementById("innerTextData").innerHTML = "Are you sure you want to submit this counter offer ?";
          }
          else
          {

           $('#myAacaModal').modal('show');
           $('.showPaymentPlan').hide();
           $('.showCounterPlan').hide();
           $('.showLumSum').show();

           $('#aacalumbSumAmtPopup').text($('#txtLumSumAmt').val());
           $('#aacapaidFullPopup').text($('#PaymentDate').val());
           $('#aacatotalRePmtPopup').text($('#txtLumSumAmt').val());
           $('#totalBalancePopup1').text(balanceTypeValue);
           $('#balanceType01').text(balanceType);
           $('#aacapercntOffer').text(percent+'%');

           if($("#AACAACCEPT").is(":checked"))
          {
            document.getElementById("innerTextData").innerHTML = "Are you sure you want to accept this offer ?";
          }
          // else if($("#AACAREJECT").is(":checked"))
          // {
            // document.getElementById("innerTextData").innerHTML = "Are you sure you want to deny this offer ?";
          // }
          else if($("#REFERCLNT").is(":checked"))
          {
            document.getElementById("innerTextData").innerHTML = "Are you sure you want to refer this offer to client for review ?";
          }
          else
          {
            document.getElementById("innerTextData").innerHTML = "";
          }
        }
      }
      else if($("#PaymentPlan").is(":checked"))
      {
          var amountOffer = $('#TotalAmountOfOffer').val().replace("$", "");
          amountOffer = amountOffer.replace(/\,/g,'');
          amountOffer = Number(amountOffer);


          var balType = '<?php echo $balanceType; ?>';
   
          if(balType == 'prin')
          {
             var totalBalDue = $('#txtFirmBal').val().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Principle Balance';
             var balanceTypeValue = $('#txtFirmBal').val();
          }
          else
          {
             var totalBalDue = $('#totalBalDue').text().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Total Balance Due';
             var balanceTypeValue = $('#totalBalDue').text();
          }

          var percent = amountOffer/totalBalDue * 100;
          percent = Math.round(percent * 100) / 100;

          if(percent == 'Infinity')
          {
            percent = '0.00';
          }


          if($("#SubmitCounterchk").is(":checked"))
          {
            
            $('#myAacaModal').modal('show');
            $('.showPaymentPlan').hide();
            $('.showCounterPlan').show();
            $('.showLumSum').hide();

            $('#conterOfferPopup').text($('#AACACounterOfferAmount').val());
            $('#amountDownPopup').text($('#AACACounterOfferAmountDown').val());
            $('#monthlyPaymentPopup').text($('#AACACounterOfferMonthlyPayment').val());
            $('#noOfPmtPopup').text($('#AACACounterOfferNumberOfPayments').val());
            $('#finalPmtPopup').text($('#AACACounterOfferFinalPayment').val());
            $('#firstPymtPopup').text($('#AACACounterOfferDeadlineDate').val());
            $('#deadlineDatePopup').text($('#AACACounterOfferFirstPaymentDate').val());
            $('#aacatotalRePmtPopup').text($('#GrandTotal').val());
            $('#totalBalancePopup1').text(balanceTypeValue);
            $('#balanceType01').text(balanceType);
            $('#aacapercntOffer').text(percent+'%');



             document.getElementById("innerTextData").innerHTML = "Are you sure you want to submit this counter offer ?";

          }
          else
          {

          $('#myAacaModal').modal('show');
          $('.showCounterPlan').hide();
          $('.showPaymentPlan').show();
          $('.showLumSum').hide();
         
          $('#pymtPlnAacaPopup').text($('#TotalAmountOfOffer').val());
          $('#intPmtDateAacaPopup').text($('#InitialInstallmentDate').val());
          $('#mthlyPmtAacaPopup').text($('#monthlyPaymentAmt').val());
          $('#finalPmtDateAacaPopup').text($('#FinalPaymentDate').val());
          $('#totlNoPymtAacaPopup').text($('#NumberofInstallments').val());
          $('#dwnPmtAacaPopup').text($('#InitialPaymentAmount').val());
          $('#finlPmtAacaPopup').text($('#FinalPaymentAmount').val());
          $('#addedIntrAacaPopup').text($('#EnterInterestRate').val());
          $('#aacatotalRePmtPopup').text($('#GrandTotal').val());
          $('#totalBalancePopup1').text(balanceTypeValue);
          $('#balanceType01').text(balanceType);
          $('#aacapercntOffer').text(percent+'%');

            if($("#AACAACCEPT").is(":checked"))
            {
              document.getElementById("innerTextData").innerHTML = "Are you sure you want to accept this offer ?";
            }
           
            else if($("#REFERCLNT").is(":checked"))
            {
              document.getElementById("innerTextData").innerHTML = "Are you sure you want to refer this offer to client for review ?";
            }
            else
            {
              document.getElementById("innerTextData").innerHTML = "";
            }
          }
        }
}

      function validateAacaCkbx()
      {
        var flag = true;
        var checkbox = $('.cbxAACA:checkbox:checked');

         if (checkbox.length == 0) 
            {          
               $('#cbxAACAError').css('display', 'block');
               $('#cbxAACAError').text('Please select at least one checkbox');
         flag = false;
            }
      else
      {
        $('#cbxAACAError').css('display', 'none');
      }

           if($('#ReviewerEmail').val() != $('#textPhone2').val())
      {
          $('#verifyEmailError').css('display', 'block');
          $('#verifyEmailError').text("Please verify email address");
           flag = false;
      }
      else
      {
         $('#verifyEmailError').css('display', 'none');  
      }

       $('#textPhone2').on('keyup', function(){
       if(this.value != $('#ReviewerEmail').val())
         {
             $('#verifyEmailError').css('display', 'block');
             $('#verifyEmailError').text("Please verify email address"); 
             flag = false;
         }
         else
         {
              $('#verifyEmailError').css('display', 'none');
         }
       })

        if(flag)
        {
          return true;
        }
        else
        {
          return false;
        }
      }


      function validateClientCkbx()
      {
        var flag = true;
        var checkbox = $('.cbxClient:checkbox:checked');

         if (checkbox.length == 0) 
            {          
               $('#cbxClientError').css('display', 'block');
               $('#cbxClientError').text('Please select at least one checkbox');
               flag = false;
                
             } else
             {
                $('#cbxClientError').css('display', 'none');
             }

           if($('#ReviewerEmail2').val() != $('#textPhone4').val())
      {
          $('#verifyEmail2Error').css('display', 'block');
          $('#verifyEmail2Error').text("Please verify email address");
           flag = false;
      }
      else
      {
         $('#verifyEmail2Error').css('display', 'none');  
      }

       $('#textPhone4').on('keyup', function(){
       if(this.value != $('#ReviewerEmail2').val())
         {
             $('#verifyEmail2Error').css('display', 'block');
             $('#verifyEmail2Error').text("Please verify email address"); 
             flag = false;
         }
         else
         {
              $('#verifyEmail2Error').css('display', 'none');
         }
       })

        if(flag)
        {
          return true;
        }
        else
        {
          return false;
        }
      }
      

      $("#btnSubmitAaca").click(function(e){
        e.preventDefault();

        $('#btnSubmitAaca').prop('disabled', true);
        $('#loader').show();
    // $('#modalPopup').modal('hide');
        var getData = fetchFormdata();
        // alert(getData);

        $.ajax({
       type: "POST",
       url: "ajaxSubmitAacaData.php",
       data:getData,
       enctype: 'multipart/form-data',
       dataType:"json",
       cache : false,
       processData: false,
       contentType: false,
       success: function(data){

          
    if(data.status == 1)
    {
     $('#loader').hide();
     $('#myAacaModal').modal('hide');
     $('#btnSubmitFrm').prop('disabled', false);
      swal({
      title: "Your offer has been submitted successfully!",
      // text: "Where do yo want to redirect?",
      type: "success",
      showCancelButton: true,
      closeOnConfirm: false,
      confirmButtonText: "Return to dashboard",
      cancelButtonText: "Return to account detail",
      }, 
      function (isConfirm) {
        if (isConfirm) {
            window.location = '../inventory_layout';
          } else {
             window.location = '../searchacc';
          }
      });
    }
      },
      error: function(){
             $('#loader').hide();
               $('#btnSubmitAaca').prop('disabled', false);
               
        alert("Something went wrong. There is an error with your submission.");
      } 
    }); 
       })

     $('#AACAACCEPT').change(function(){ 
          if ($(this).prop('checked')) {
                     document.getElementById("AACAREJECT").checked = false;
                     document.getElementById("REFERCLNT").checked = false;
                     document.getElementById("SubmitCounterchk").checked = false;
                     document.getElementById("AACAADDINF").checked = false;
                     $("#SubmitCounterdiv").hide();
          }
      })

      $('#AACAREJECT').change(function(){ 
          if ($(this).prop('checked')) {
                     document.getElementById("AACAACCEPT").checked = false;
                     document.getElementById("REFERCLNT").checked = false;
                     document.getElementById("SubmitCounterchk").checked = false;
                     document.getElementById("AACAADDINF").checked = false;
                     $("#SubmitCounterdiv").hide();
               var aacaaccept='<?php echo $fetchres['AACAACCEPT'];?>'; 
                var aacareject='<?php echo $fetchres['AACAREJECT'];?>'; 
                var aacarfrtoclient='<?php echo $fetchres['AACARFRTOCLNT'];?>'; 
                var aacaacounter='<?php echo $fetchres['AACACNTR'];?>'; 
                var aacaaddinfo='<?php echo $fetchres['AACAADDINF'];?>'; 
                var clientaccept='<?php echo $fetchres['CLNTACCEPT'];?>'; 
                var clientreject='<?php echo $fetchres['CLNTREJECT'];?>'; 
                var clientcounter='<?php echo $fetchres['CLNTCNTR'];?>'; 
                var clientaddinfo='<?php echo $fetchres['CBXCLNTINF'];?>';
    if(aacaaccept=='True' || aacareject=='True' || aacarfrtoclient=='True' || aacaacounter=='True' || aacaaddinfo=='True' || clientaccept=='True' || clientreject=='True' || clientcounter=='True' || clientaddinfo=='True'){
        
        swal({
          title: "Confirm Entry",
          text: "There is already a response submitted, do you want to proceed with new response?", 
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#6A9944",
          confirmButtonText: "Proceed",
          cancelButtonText: "Cancel",
          closeOnConfirm: true
        }, function(isConfirm){
          
        if(isConfirm){
         $('#modalDenyPopup').modal('show');
        }else{}
        });
      
        }
        else
        {
         $('#modalDenyPopup').modal('show');
        }
          }
      })

      $('#REFERCLNT').change(function(){ 
          if ($(this).prop('checked')) {
                     document.getElementById("AACAREJECT").checked = false;
                     document.getElementById("AACAACCEPT").checked = false;
                     document.getElementById("SubmitCounterchk").checked = false;
                     document.getElementById("AACAADDINF").checked = false;
                     $("#SubmitCounterdiv").hide();
          }
      })

      $('#SubmitCounterchk').change(function(){ 
          if ($(this).prop('checked')) {
                     document.getElementById("AACAREJECT").checked = false;
                     document.getElementById("REFERCLNT").checked = false;
                     document.getElementById("AACAREJECT").checked = false;
                     document.getElementById("AACAADDINF").checked = false;
                     document.getElementById("AACAACCEPT").checked = false;
          }
      })

      $('#AACAADDINF').change(function(){ 
          if ($(this).prop('checked')) {
                     document.getElementById("AACAREJECT").checked = false;
                     document.getElementById("REFERCLNT").checked = false;
                     document.getElementById("SubmitCounterchk").checked = false;
                     document.getElementById("AACAACCEPT").checked = false;
                     $("#SubmitCounterdiv").hide();
    var aacaaccept='<?php echo $fetchres['AACAACCEPT'];?>'; 
    var aacareject='<?php echo $fetchres['AACAREJECT'];?>'; 
    var aacarfrtoclient='<?php echo $fetchres['AACARFRTOCLNT'];?>'; 
    var aacaacounter='<?php echo $fetchres['AACACNTR'];?>'; 
    var aacaaddinfo='<?php echo $fetchres['AACAADDINF'];?>'; 
    var clientaccept='<?php echo $fetchres['CLNTACCEPT'];?>'; 
    var clientreject='<?php echo $fetchres['CLNTREJECT'];?>'; 
    var clientcounter='<?php echo $fetchres['CLNTCNTR'];?>'; 
    var clientaddinfo='<?php echo $fetchres['CBXCLNTINF'];?>';
    if(aacaaccept=='True' || aacareject=='True' || aacarfrtoclient=='True' || aacaacounter=='True' || aacaaddinfo=='True' || clientaccept=='True' || clientreject=='True' || clientcounter=='True' || clientaddinfo=='True'){
        
        swal({
          title: "Confirm Entry",
          text: "There is already a response submitted, do you want to proceed with new response?", 
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#6A9944",
          confirmButtonText: "Proceed",
          cancelButtonText: "Cancel",
          closeOnConfirm: true
        }, function(isConfirm){
          
        if(isConfirm){
         $('#modalPopup').modal('show');
        }else{}
        });
      
        }
        else
        {
         $('#modalPopup').modal('show');
        }
          }
      })



      //submitting client data

       $("#btnSubmitClient").click(function(e){
        // alert("test");
        e.preventDefault();
    
    
    
        if(!validateClientCkbx())
        {
          return false;
        }
    
    if($("#SubmitCounterchkclient").is(':checked'))
    {
      if(!validateAACAcounterCalc2())
      {
        return false;
      }
       if(!validatenegativefinalpaymentforaacaclient())
      {
        return false;
      }
    }

    var aacaaccept='<?php echo $fetchres['AACAACCEPT'];?>'; 
    var aacareject='<?php echo $fetchres['AACAREJECT'];?>'; 
    var aacarfrtoclient='<?php echo $fetchres['AACARFRTOCLNT'];?>'; 
    var aacaacounter='<?php echo $fetchres['AACACNTR'];?>'; 
    var aacaaddinfo='<?php echo $fetchres['AACAADDINF'];?>'; 
    var clientaccept='<?php echo $fetchres['CLNTACCEPT'];?>'; 
    var clientreject='<?php echo $fetchres['CLNTREJECT'];?>'; 
    var clientcounter='<?php echo $fetchres['CLNTCNTR'];?>'; 
    var clientaddinfo='<?php echo $fetchres['CBXCLNTINF'];?>';
    if(aacaaccept=='True' || aacareject=='True' || aacarfrtoclient=='True' || aacaacounter=='True' || aacaaddinfo=='True' || clientaccept=='True' || clientreject=='True' || clientcounter=='True' || clientaddinfo=='True'){
        
        swal({
          title: "Confirm Entry",
          text: "There is already a response submitted, do you want to proceed with new response?", 
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#6A9944",
          confirmButtonText: "Proceed",
          cancelButtonText: "Cancel",
          closeOnConfirm: true
        }, function(isConfirm){
          
        if(isConfirm){
          submitPopupClient();
        }else{}
        });
      
        }
        else
        {
          submitPopupClient();
        }

      })

function submitPopupClient(){
  if($("#LumpSum").is(":checked")){
         var balType = '<?php echo $balanceType; ?>';

         if(balType == 'prin')
         {
           var totalBalDue = $('#txtFirmBal').val().replace("$", "");
           totalBalDue = totalBalDue.replace(/\,/g,'');
           totalBalDue = Number(totalBalDue);
           var balanceType = 'Principle Balance';
           var balanceTypeValue = $('#txtFirmBal').val();
         }
         else
         {
           var totalBalDue = $('#totalBalDue').text().replace("$", "");
           totalBalDue = totalBalDue.replace(/\,/g,'');
           totalBalDue = Number(totalBalDue);
           var balanceType = 'Total Balance Due';
           var balanceTypeValue = $('#totalBalDue').text();
         }

       
         var lumSumAmt = $('#txtLumSumAmt').val().replace("$", "");
         lumSumAmt = lumSumAmt.replace(/\,/g,'');
         lumSumAmt = Number(lumSumAmt);



         var percent = lumSumAmt/totalBalDue * 100;
         percent = Math.round(percent * 100) / 100;

         if(percent == 'Infinity')
         {
          percent = '0.00';
         }
          

          if($("#SubmitCounterchkclient").is(":checked"))
          {
            $('#myClientModal').modal('show');
            $('.showLumSum').hide();
            $('.showPaymentPlan').hide();
            $('.showClntCounterPlan').show();

            $('#conterOfferClntPopup').text($('#AACACounterOfferAmount').val());
            $('#amountDownClntPopup').text($('#AACACounterOfferAmountDown').val());
            $('#monthlyPaymentClntPopup').text($('#AACACounterOfferMonthlyPayment').val());
            $('#noOfPmtClntPopup').text($('#AACACounterOfferNumberOfPayments').val());
            $('#finalPmtClntPopup').text($('#AACACounterOfferFinalPayment').val());
            $('#firstPymtClntPopup').text($('#AACACounterOfferDeadlineDate').val());
            $('#deadlineDateClntPopup').text($('#AACACounterOfferFirstPaymentDate').val());
            $('#clienttotalRePmtPopup').text($('#txtLumSumAmt').val());
            $('#totalBalancePopup2').text(balanceTypeValue);
            $('#balanceType02').text(balanceType);
            $('#clientpercntOffer').text(percent+'%');

             document.getElementById("innerTextDataClnt").innerHTML = "Are you sure you want to submit this counter offer ?";
          }
          else
          {

           $('#myClientModal').modal('show');
           $('.showPaymentPlan').hide();
           $('.showClntCounterPlan').hide();
           $('.showLumSum').show();

           $('#clientlumbSumAmtPopup').text($('#txtLumSumAmt').val());
           $('#clientpaidFullPopup').text($('#PaymentDate').val());
           $('#clienttotalRePmtPopup').text($('#txtLumSumAmt').val());
           $('#totalBalancePopup2').text(balanceTypeValue);
           $('#balanceType02').text(balanceType);
           $('#clientpercntOffer').text(percent+'%');

           if($("#CLNTACCEPT").is(":checked"))
          {
            document.getElementById("innerTextDataClnt").innerHTML = "Are you sure you want to accept this offer ?";
          }
         
          else
          {
            document.getElementById("innerTextDataClnt").innerHTML = "Are you sure you want to accept this offer ?";
          }
        }
      }
      else if($("#PaymentPlan").is(":checked")){
          $('#myClientModal').modal('show');
          $('.showPaymentPlan').hide();
          $('.showLumSum').show();

          var balType = '<?php echo $balanceType; ?>';

           if(balType == 'prin')
           {
             var totalBalDue = $('#txtFirmBal').val().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Principle Balance';
             var balanceTypeValue = $('#txtFirmBal').val();
           }
           else
           {
             var totalBalDue = $('#totalBalDue').text().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Total Balance Due';
             var balanceTypeValue = $('#totalBalDue').text();
           }


        
         
          var amountOffer = $('#TotalAmountOfOffer').val().replace("$", "");
          amountOffer = amountOffer.replace(/\,/g,'');
          amountOffer = Number(amountOffer);
          var percent = amountOffer/totalBalDue * 100;
          percent = Math.round(percent * 100) / 100;

          if(percent == 'Infinity')
          {
            percent = '0.00';
          }

          if($("#SubmitCounterchkclient").is(":checked"))
          {
            
            $('#myClientModal').modal('show');
            $('.showPaymentPlan').hide();
            $('.showClntCounterPlan').show();
            $('.showLumSum').hide();

            $('#conterOfferClntPopup').text($('#AACACounterOfferAmount').val());
            $('#amountDownClntPopup').text($('#AACACounterOfferAmountDown').val());
            $('#monthlyPaymentClntPopup').text($('#AACACounterOfferMonthlyPayment').val());
            $('#noOfPmtClntPopup').text($('#AACACounterOfferNumberOfPayments').val());
            $('#finalPmtClntPopup').text($('#AACACounterOfferFinalPayment').val());
            $('#firstPymtClntPopup').text($('#AACACounterOfferDeadlineDate').val());
            $('#deadlineDateClntPopup').text($('#AACACounterOfferFirstPaymentDate').val());
            $('#clienttotalRePmtPopup').text($('#GrandTotal').val());
            $('#totalBalancePopup2').text(balanceTypeValue);
            $('#balanceType02').text(balanceType);
            $('#clientpercntOffer').text(percent+'%');

            document.getElementById("innerTextDataClnt").innerHTML = "Are you sure you want to submit this counter offer ?";
          }
          else
          {

          $('#myClientModal').modal('show');
          $('.showClntCounterPlan').hide();
          $('.showPaymentPlan').show();
          $('.showLumSum').hide();
         
          $('#pymtPlnClntPopup').text($('#TotalAmountOfOffer').val());
          $('#intPmtDateClntPopup').text($('#InitialInstallmentDate').val());
          $('#mthlyPmtClntPopup').text($('#monthlyPaymentAmt').val());
          $('#finalPmtDateClntPopup').text($('#FinalPaymentDate').val());
          $('#totlNoPymtClntPopup').text($('#NumberofInstallments').val());
          $('#dwnPmtClntPopup').text($('#InitialPaymentAmount').val());
          $('#finlPmtClntPopup').text($('#FinalPaymentAmount').val());
          $('#addedIntrClntPopup').text($('#EnterInterestRate').val());
          $('#clienttotalRePmtPopup').text($('#GrandTotal').val());
          $('#totalBalancePopup2').text(balanceTypeValue);
          $('#balanceType02').text(balanceType);
          $('#clientpercntOffer').text(percent+'%');
          }

          if($("#CLNTACCEPT").is(":checked"))
          {
            document.getElementById("innerTextDataClnt").innerHTML = "Are you sure you want to accept this offer ?";
          }
         
          else
          {
            document.getElementById("innerTextDataClnt").innerHTML = "";
          }
      }
   }


      $("#btnSubmitClient01").click(function(e){
        e.preventDefault();

        $('#btnSubmitClient01').prop('disabled', true);
        $('#loader').show();
        var getData = fetchFormdata();

        $.ajax({
       type: "POST",
       url: "ajaxSubmitClientData.php",
       data:getData,
       enctype: 'multipart/form-data',
       dataType:"json",
       cache : false,
       processData: false,
       contentType: false,
       success: function(data){

          
          if(data.status == 1)
          {
             
             $('#loader').hide();
             $('#myClientModal').modal('hide');
             $('#btnSubmitFrm').prop('disabled', false);
              swal({
          title: "Your offer has been submitted successfully!",
          // text: "Where do yo want to redirect?",
          type: "success",
          showCancelButton: true,
          closeOnConfirm: false,
          confirmButtonText: "Return to dashboard",
          cancelButtonText: "Return to account detail",
        }, function (isConfirm) {
            if (isConfirm) {
                            window.location = '../inventory_layout';
                    } else {
                            window.location = '../searchacc';
                    }
        });
          }
      },
      error: function(){
             $('#loader').hide();
               $('#btnSubmitClient01').prop('disabled', false);
               
        alert("Something went wrong. There is an error with your submission.");
      } 
    }); 
       })



       $('#CLNTACCEPT').change(function(){ 
          if ($(this).prop('checked')) {
                     document.getElementById("CLNTREJECT").checked = false;
                     document.getElementById("SubmitCounterchkclient").checked = false;
                     document.getElementById("CBXCLNTINF").checked = false;
                     $("#SubmitCounterclientdiv").hide();
          }
      })

      $('#CLNTREJECT').change(function(){ 
          if ($(this).prop('checked')) {
                     document.getElementById("CLNTACCEPT").checked = false;
                     document.getElementById("SubmitCounterchkclient").checked = false;
                     document.getElementById("CBXCLNTINF").checked = false;
                     $("#SubmitCounterclientdiv").hide();
                     var aacaaccept='<?php echo $fetchres['AACAACCEPT'];?>'; 
                    var aacareject='<?php echo $fetchres['AACAREJECT'];?>'; 
                    var aacarfrtoclient='<?php echo $fetchres['AACARFRTOCLNT'];?>'; 
                    var aacaacounter='<?php echo $fetchres['AACACNTR'];?>'; 
                    var aacaaddinfo='<?php echo $fetchres['AACAADDINF'];?>'; 
                   var clientaccept='<?php echo $fetchres['CLNTACCEPT'];?>'; 
                    var clientreject='<?php echo $fetchres['CLNTREJECT'];?>'; 
                    var clientcounter='<?php echo $fetchres['CLNTCNTR'];?>'; 
                    var clientaddinfo='<?php echo $fetchres['CBXCLNTINF'];?>';
    if(aacaaccept=='True' || aacareject=='True' || aacarfrtoclient=='True' || aacaacounter=='True' || aacaaddinfo=='True' || clientaccept=='True' || clientreject=='True' || clientcounter=='True' || clientaddinfo=='True'){
        
        swal({
          title: "Confirm Entry",
          text: "There is already a response submitted, do you want to proceed with new response?", 
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#6A9944",
          confirmButtonText: "Proceed",
          cancelButtonText: "Cancel",
          closeOnConfirm: true
        }, function(isConfirm){
          
        if(isConfirm){
         $('#modalDenyPopup01').modal('show');
        }else{}
        });
      
        }
        else
        {
          $('#modalDenyPopup01').modal('show');
        }
                    
           
           
          }
      })

      $('#SubmitCounterchkclient').change(function(){ 
          if ($(this).prop('checked')) {
                     document.getElementById("CLNTREJECT").checked = false;
                     document.getElementById("CLNTACCEPT").checked = false;
                     document.getElementById("CBXCLNTINF").checked = false;
                     
          }
      })

      $('#CBXCLNTINF').change(function(){ 
          if ($(this).prop('checked')) {
                     document.getElementById("CLNTREJECT").checked = false;
                     document.getElementById("SubmitCounterchkclient").checked = false;
                     document.getElementById("CLNTACCEPT").checked = false;
                     $("#SubmitCounterclientdiv").hide();
                     var aacaaccept='<?php echo $fetchres['AACAACCEPT'];?>'; 
                    var aacareject='<?php echo $fetchres['AACAREJECT'];?>'; 
                    var aacarfrtoclient='<?php echo $fetchres['AACARFRTOCLNT'];?>'; 
                    var aacaacounter='<?php echo $fetchres['AACACNTR'];?>'; 
                    var aacaaddinfo='<?php echo $fetchres['AACAADDINF'];?>'; 
                   var clientaccept='<?php echo $fetchres['CLNTACCEPT'];?>'; 
                    var clientreject='<?php echo $fetchres['CLNTREJECT'];?>'; 
                    var clientcounter='<?php echo $fetchres['CLNTCNTR'];?>'; 
                    var clientaddinfo='<?php echo $fetchres['CBXCLNTINF'];?>';
    if(aacaaccept=='True' || aacareject=='True' || aacarfrtoclient=='True' || aacaacounter=='True' || aacaaddinfo=='True' || clientaccept=='True' || clientreject=='True' || clientcounter=='True' || clientaddinfo=='True'){
        
        swal({
          title: "Confirm Entry",
          text: "There is already a response submitted, do you want to proceed with new response?", 
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#6A9944",
          confirmButtonText: "Proceed",
          cancelButtonText: "Cancel",
          closeOnConfirm: true
        }, function(isConfirm){
          
        if(isConfirm){
         $('#modalPopup01').modal('show');
        }else{}
        });
      
        }
        else
        {
          $('#modalPopup01').modal('show');
        }
                    
                     
          }
      })


      $("#submitInfoBtn").click(function(e){
        e.preventDefault();
    
    if(!emailerrorhaldel())
    {
      return false;
    }
    

      if(!validateAacaReqInfo())
      {
        return false;
      }

   submitPopupAACARequestInfo();
    
    
       })
      function submitPopupAACARequestInfo(){
        if($("#LumpSum").is(":checked")){
         var balType = '<?php echo $balanceType; ?>';
         
         if(balType == 'prin')
          {
             var totalBalDue = $('#txtFirmBal').val().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Principle Balance';
             var balanceTypeValue = $('#txtFirmBal').val();
          }
          else
          {
             var totalBalDue = $('#totalBalDue').text().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Total Balance Due';
             var balanceTypeValue = $('#totalBalDue').text();
          }
       
         var lumSumAmt = $('#txtLumSumAmt').val().replace("$", "");
         lumSumAmt = lumSumAmt.replace(/\,/g,'');
         lumSumAmt = Number(lumSumAmt);



         var percent = lumSumAmt/totalBalDue * 100;
         percent = Math.round(percent * 100) / 100;

         if(percent == 'Infinity')
         {
            percent = '0.00';
         }

          if($("#SubmitCounterchk").is(":checked"))
          {

          }
          else
          {
          $('#myAacaModal').modal('show');
          $('.showPaymentPlan').hide();
          $('.showCounterPlan').hide();
          $('.showLumSum').show();

          $('#aacalumbSumAmtPopup').text($('#txtLumSumAmt').val());
          $('#aacapaidFullPopup').text($('#PaymentDate').val());
          $('#aacatotalRePmtPopup').text($('#txtLumSumAmt').val());
          $('#totalBalancePopup1').text(balanceTypeValue);
          $('#balanceType01').text(balanceType);
          $('#aacapercntOffer').text(percent+'%');

          if($("#AACAADDINF").is(":checked"))
          {
             document.getElementById("innerTextData").innerHTML = '';
            var addInfo = $('#ADDINFREAS').val();
            document.getElementById("innerTextData").innerHTML += "Are you sure you want to request more info on this offer ? ";
            document.getElementById("innerTextData").innerHTML += "<br>";
            document.getElementById("innerTextData").innerHTML += "Additional Comment : "+ addInfo ;
          }
      
          }
      }
      else if($("#PaymentPlan").is(":checked"))
      {
          var amountOffer = $('#TotalAmountOfOffer').val().replace("$", "");
          amountOffer = amountOffer.replace(/\,/g,'');
          amountOffer = Number(amountOffer);


          var balType = '<?php echo $balanceType; ?>';
         
         if(balType == 'prin')
          {
             var totalBalDue = $('#txtFirmBal').val().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Principle Balance';
             var balanceTypeValue = $('#txtFirmBal').val();
          }
          else
          {
             var totalBalDue = $('#totalBalDue').text().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Total Balance Due';
             var balanceTypeValue = $('#totalBalDue').text();
          }

          var percent = amountOffer/totalBalDue * 100;
          percent = Math.round(percent * 100) / 100;

          if(percent == 'Infinity')
          {
            percent = '0.00';
          }

          if($("#SubmitCounterchk").is(":checked"))
          {

          }
          else
          {

          $('#myAacaModal').modal('show');
          $('.showCounterPlan').hide();
          $('.showPaymentPlan').show();
          $('.showLumSum').hide();
         
          $('#pymtPlnAacaPopup').text($('#TotalAmountOfOffer').val());
          $('#intPmtDateAacaPopup').text($('#InitialInstallmentDate').val());
          $('#mthlyPmtAacaPopup').text($('#monthlyPaymentAmt').val());
          $('#finalPmtDateAacaPopup').text($('#FinalPaymentDate').val());
          $('#totlNoPymtAacaPopup').text($('#NumberofInstallments').val());
          $('#dwnPmtAacaPopup').text($('#InitialPaymentAmount').val());
          $('#finlPmtAacaPopup').text($('#FinalPaymentAmount').val());
          $('#addedIntrAacaPopup').text($('#EnterInterestRate').val());
          $('#aacatotalRePmtPopup').text($('#GrandTotal').val());
          $('#totalBalancePopup1').text(balanceTypeValue);
          $('#balanceType01').text(balanceType);
          $('#aacapercntOffer').text(percent+'%');

          }


           if($("#AACAADDINF").is(":checked"))
          {
            document.getElementById("innerTextData").innerHTML = '';
            var addInfo = $('#ADDINFREAS').val();
            document.getElementById("innerTextData").innerHTML += "Are you sure you want to request more info on this offer ? ";
            document.getElementById("innerTextData").innerHTML += "<br>";
            document.getElementById("innerTextData").innerHTML += "Additional Comment : "+ addInfo ;
      }
      } 
      }
  
    $('#closesubmitInfoBtn').click(function(){
      $("#AACAADDINF").prop("checked", false);
      if($('#ADDINFREAS').val() != ''){
        $('#ADDINFREAS').val(null);
      }
    });

      function validateAacaReqInfo()
      {
        var flag = true;

        if($('#ADDINFREAS').val() == '')
        {
             $('#ADDINFREASError').css('display', 'block');
           $('#ADDINFREASError').text("This field is required"); 
             
          flag = false;
        }
        else
        { 
            $('#modalPopup').modal('hide');
      $('.AcaReq').css("overflow", "hidden");
      $('#myAacaModal').css({"overflow-x":"hidden", "overflow-y":"auto"});
            $('#ADDINFREASError').css('display', 'none');
        }

        if(flag)
        {
          return true;
        }
        else
        {
          return false;
        }
      }
    
    function emailerrorhaldel()
    {
    var flag = true;
    var vrfyemail = $("#textPhone2").val();
    var reviewemail = $("#ReviewerEmail").val();
    if(vrfyemail == '' || vrfyemail != reviewemail )
    { 
      if($("#AACAREJECT").is(':checked'))
      {
        $("#modalDenyPopup").modal('hide');
        $("#AACADENYR").val('');
        $("#AACAREJECT").prop('checked', false)
        $('#verifyEmailError').css('display', 'block');
        $('#verifyEmailError').text("Please verify email address"); 
        flag = false;
      }else if($("#AACAADDINF").is(':checked'))
      {
        $("#modalPopup").modal('hide');
        $("#ADDINFREAS").val('');
        $("#AACAADDINF").prop('checked', false)
        $('#verifyEmailError').css('display', 'block');
        $('#verifyEmailError').text("Please verify email address"); 
        flag = false;
      }else
      {
        $('#verifyEmailError').css('display', 'none');
      }
      
      $('#textPhone2').on('keyup', function(){
        if(this.value != $('#ReviewerEmail').val())
        {
          $('#verifyEmailError').css('display', 'block');
          $('#verifyEmailError').text("Please verify email address"); 
          flag = false;
        }
        else
        {
          $('#verifyEmailError').css('display', 'none');
        }
      })
      flag = false;
    }
    else
    {
      $('#verifyEmailError').css('display', 'none');  
    }
    if(flag)
    {
      return true;
    }
    else
    {
      return false;
    }
    }
    
    
    $("#denysubmitInfoBtn").click(function(e){
        e.preventDefault();
      
    if(!emailerrorhaldel())
    {
      return false;
    }

       
  
      if(!validateDenyOffer())
      {
        return false;
      }
    
    submitPopupAACAdeny();
   
       
       })
  function submitPopupAACAdeny(){
   if($("#LumpSum").is(":checked")){
         var balType = '<?php echo $balanceType; ?>';
         
         if(balType == 'prin')
          {
             var totalBalDue = $('#txtFirmBal').val().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Principle Balance';
             var balanceTypeValue = $('#txtFirmBal').val();
          }
          else
          {
             var totalBalDue = $('#totalBalDue').text().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Total Balance Due';
             var balanceTypeValue = $('#totalBalDue').text();
          }
       
         var lumSumAmt = $('#txtLumSumAmt').val().replace("$", "");
         lumSumAmt = lumSumAmt.replace(/\,/g,'');
         lumSumAmt = Number(lumSumAmt);



         var percent = lumSumAmt/totalBalDue * 100;
         percent = Math.round(percent * 100) / 100;

         if(percent == 'Infinity')
         {
            percent = '0.00';
         }

          if($("#SubmitCounterchk").is(":checked"))
          {

          }
          else
          {

          $('#myAacaModal').modal('show');
          $('.showPaymentPlan').hide();
          $('.showCounterPlan').hide();
          $('.showLumSum').show();

          $('#aacalumbSumAmtPopup').text($('#txtLumSumAmt').val());
          $('#aacapaidFullPopup').text($('#PaymentDate').val());
          $('#aacatotalRePmtPopup').text($('#txtLumSumAmt').val());
          $('#totalBalancePopup1').text(balanceTypeValue);
          $('#balanceType01').text(balanceType);
          $('#aacapercntOffer').text(percent+'%');

          if($("#AACAREJECT").is(":checked"))
          {
             document.getElementById("innerTextData").innerHTML = '';
            var addInfo = $('#AACADENYR').val();
            document.getElementById("innerTextData").innerHTML += "Are you sure you want to deny this offer ?";
            document.getElementById("innerTextData").innerHTML += "<br>";
            document.getElementById("innerTextData").innerHTML += "Additional Comment : "+ addInfo ;
          }
      
          }
      }
      else if($("#PaymentPlan").is(":checked"))
      {
          var amountOffer = $('#TotalAmountOfOffer').val().replace("$", "");
          amountOffer = amountOffer.replace(/\,/g,'');
          amountOffer = Number(amountOffer);


          var balType = '<?php echo $balanceType; ?>';
         
         if(balType == 'prin')
          {
             var totalBalDue = $('#txtFirmBal').val().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Principle Balance';
             var balanceTypeValue = $('#txtFirmBal').val();
          }
          else
          {
             var totalBalDue = $('#totalBalDue').text().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Total Balance Due';
             var balanceTypeValue = $('#totalBalDue').text();
          }

          var percent = amountOffer/totalBalDue * 100;
          percent = Math.round(percent * 100) / 100;

          if(percent == 'Infinity')
          {
            percent = '0.00';
          }

          if($("#SubmitCounterchk").is(":checked"))
          {

          }
          else
          {

          $('#myAacaModal').modal('show');
          $('.showCounterPlan').hide();
          $('.showPaymentPlan').show();
          $('.showLumSum').hide();
         
          $('#pymtPlnAacaPopup').text($('#TotalAmountOfOffer').val());
          $('#intPmtDateAacaPopup').text($('#InitialInstallmentDate').val());
          $('#mthlyPmtAacaPopup').text($('#monthlyPaymentAmt').val());
          $('#finalPmtDateAacaPopup').text($('#FinalPaymentDate').val());
          $('#totlNoPymtAacaPopup').text($('#NumberofInstallments').val());
          $('#dwnPmtAacaPopup').text($('#InitialPaymentAmount').val());
          $('#finlPmtAacaPopup').text($('#FinalPaymentAmount').val());
          $('#addedIntrAacaPopup').text($('#EnterInterestRate').val());
          $('#aacatotalRePmtPopup').text($('#GrandTotal').val());
          $('#totalBalancePopup1').text(balanceTypeValue);
          $('#balanceType01').text(balanceType);
          $('#aacapercntOffer').text(percent+'%');

          }


           if($("#AACAREJECT").is(":checked"))
          {
            document.getElementById("innerTextData").innerHTML = '';
            var addInfo = $('#AACADENYR').val();
            document.getElementById("innerTextData").innerHTML += "Are you sure you want to deny this offer ?";
            document.getElementById("innerTextData").innerHTML += "<br>";
            document.getElementById("innerTextData").innerHTML += "Additional Comment : "+ addInfo ;
          }
      } 
  }

  $('#closedenyofferAca').click(function(){
    $("#AACAREJECT").prop("checked", false);
    if($('#AACADENYR').val() != ''){
      $('#AACADENYR').val(null);      
    }
  });
  
  function validateDenyOffer()
  {
    var flag = true;
    if($('#AACADENYR').val() == '')
    {
      $('#AacaDenyCmtError').css('display', 'block');
      $('#AacaDenyCmtError').text("This field is required"); 
      flag = false;
    }
    else
    {
      $('#AacaDenyCmtError').css('display', 'none');
            $('#modalDenyPopup').modal('hide');
      $('.AcaReq').css("overflow", "hidden");
      $('#myAacaModal').css({"overflow-x":"hidden", "overflow-y":"auto"});
    }
    
    if(flag)
        {
          return true;
        }
        else
        {
          return false;
        }
  }

  
    

      function validateClientReqInfo()
      {
        var flag = true;

        if($('#CLNTREAS').val() == '')
        {
             $('#CLNTREASError').css('display', 'block');
           $('#CLNTREASError').text("This field is required"); 
             
          flag = false;
        }
        else
        {
      $('#modalPopup01').modal('hide');
      $('.AcaReq').css("overflow", "hidden");
      $('#myClientModal').css({"overflow-x":"hidden", "overflow-y":"auto"});
            $('#CLNTREASError').css('display', 'none');
        }

        if(flag)
        {
          return true;
        }
        else
        {
          return false;
        }
      }
    
    $('#ClosesubmitInfoBtn2').click(function(){
      $("#CBXCLNTINF").prop("checked", false);
      if($('#CLNTREAS').val() != ''){
        $('#CLNTREAS').val(null);
      }
    });

      $("#submitInfoBtn2").click(function(e){
        e.preventDefault();
    
    if(!Cltemailerrorhandel())
    {
      return false;
    }
    
 
       

     if(!validateClientReqInfo())
        {
          return false;
        }
    submitPopupClientRequestinfo();
       
       })
      function submitPopupClientRequestinfo(){
        if($("#LumpSum").is(":checked")){
         var balType = '<?php echo $balanceType; ?>';
         
         if(balType == 'prin')
          {
             var totalBalDue = $('#txtFirmBal').val().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Principle Balance';
             var balanceTypeValue = $('#txtFirmBal').val();
          }
          else
          {
             var totalBalDue = $('#totalBalDue').text().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Total Balance Due';
             var balanceTypeValue = $('#totalBalDue').text();
          }

       
         var lumSumAmt = $('#txtLumSumAmt').val().replace("$", "");
         lumSumAmt = lumSumAmt.replace(/\,/g,'');
         lumSumAmt = Number(lumSumAmt);



         var percent = lumSumAmt/totalBalDue * 100;
         percent = Math.round(percent * 100) / 100;

         if(percent == 'Infinity')
          {
           percent = '0.00';
          }

          if($("#SubmitCounterchkclient").is(":checked"))
          {

          }
          else
          {

          $('#myClientModal').modal('show');
          $('.showPaymentPlan').hide();
          $('.showClntCounterPlan').hide();
          $('.showLumSum').show();

          $('#clientlumbSumAmtPopup').text($('#txtLumSumAmt').val());
          $('#clientpaidFullPopup').text($('#PaymentDate').val());
          $('#clienttotalRePmtPopup').text($('#txtLumSumAmt').val());
          $('#totalBalancePopup2').text(balanceTypeValue);
          $('#balanceType02').text(balanceType);
          $('#clientpercntOffer').text(percent+'%');

          if($("#CBXCLNTINF").is(":checked"))
          {
             document.getElementById("innerTextDataClnt").innerHTML = '';
            var addInfo = $('#CLNTREAS').val();
            document.getElementById("innerTextDataClnt").innerHTML += "Are you sure you want to request more info on this offer ? ";
            document.getElementById("innerTextDataClnt").innerHTML += "<br>";
            document.getElementById("innerTextDataClnt").innerHTML += "Additional Comment : "+ addInfo ;
          }
          }
      }
      else if($("#PaymentPlan").is(":checked"))
      {
          var amountOffer = $('#TotalAmountOfOffer').val().replace("$", "");
          amountOffer = amountOffer.replace(/\,/g,'');
          amountOffer = Number(amountOffer);


          var balType = '<?php echo $balanceType; ?>';
         
         if(balType == 'prin')
          {
             var totalBalDue = $('#txtFirmBal').val().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Principle Balance';
             var balanceTypeValue = $('#txtFirmBal').val();
          }
          else
          {
             var totalBalDue = $('#totalBalDue').text().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Total Balance Due';
             var balanceTypeValue = $('#totalBalDue').text();
          }

          var percent = amountOffer/totalBalDue * 100;
          percent = Math.round(percent * 100) / 100;
          if(percent == 'Infinity')
          {
           percent = '0.00';
          }

          if($("#SubmitCounterchkclient").is(":checked"))
          {

          }
          else
          {

          $('#myClientModal').modal('show');
          $('.showClntCounterPlan').hide();
          $('.showPaymentPlan').show();
          $('.showLumSum').hide();
         
          $('#pymtPlnClntPopup').text($('#TotalAmountOfOffer').val());
          $('#intPmtDateClntPopup').text($('#InitialInstallmentDate').val());
          $('#mthlyPmtClntPopup').text($('#monthlyPaymentAmt').val());
          $('#finalPmtDateClntPopup').text($('#FinalPaymentDate').val());
          $('#totlNoPymtClntPopup').text($('#NumberofInstallments').val());
          $('#dwnPmtClntPopup').text($('#InitialPaymentAmount').val());
          $('#finlPmtClntPopup').text($('#FinalPaymentAmount').val());
          $('#addedIntrClntPopup').text($('#EnterInterestRate').val());
          $('#clienttotalRePmtPopup').text($('#GrandTotal').val());
          $('#totalBalancePopup2').text(balanceTypeValue);
          $('#balanceType02').text(balanceType);
          $('#clientpercntOffer').text(percent+'%');

          }


           if($("#CBXCLNTINF").is(":checked"))
          {
            document.getElementById("innerTextDataClnt").innerHTML = '';
            var addInfo = $('#CLNTREAS').val();
            document.getElementById("innerTextDataClnt").innerHTML += "Are you sure you want to request more info on this offer ? ";
            document.getElementById("innerTextDataClnt").innerHTML += "<br>";
            document.getElementById("innerTextDataClnt").innerHTML += "Additional Comment : "+ addInfo ;
          }
       } 
      }
     
     function Cltemailerrorhandel()
    {
    var flag = true;
    var vrfyemail = $("#textPhone4").val();
    var reviewemail = $("#ReviewerEmail2").val();
    if(vrfyemail == '' || vrfyemail != reviewemail )
    { 
      if($("#CLNTREJECT").is(':checked'))
      {
        $("#modalDenyPopup01").modal('hide');
        $("#CLNTDENYR").val('');
        $("#CLNTREJECT").prop('checked', false)
        $('#verifyEmail2Error').css('display', 'block');
        $('#verifyEmail2Error').text("Please verify email address"); 
        flag = false;
      }else if($("#CBXCLNTINF").is(':checked'))
      {
        $("#modalPopup01").modal('hide');
        $("#CLNTREAS").val('');
        $("#CBXCLNTINF").prop('checked', false)
        $('#verifyEmail2Error').css('display', 'block');
        $('#verifyEmail2Error').text("Please verify email address"); 
        flag = false;
      }else
      {
        $('#verifyEmail2Error').css('display', 'none');
      }
      
      $('#textPhone4').on('keyup', function(){
        if(this.value != $('#ReviewerEmail2').val())
        {
          $('#verifyEmail2Error').css('display', 'block');
          $('#verifyEmail2Error').text("Please verify email address"); 
          flag = false;
        }
        else
        {
          $('#verifyEmail2Error').css('display', 'none');
        }
      })
      flag = false;
    }
    else
    {
      $('#verifyEmail2Error').css('display', 'none');  
    }
    if(flag)
    {
      return true;
    }
    else
    {
      return false;
    }
    }
     
     $("#ClntDenysubmitInfoBtn2").click(function(e){
        e.preventDefault();
    
    if(!Cltemailerrorhandel())
    {
      return false;
    }
    
    
    
    
        if(!validateClientDenyOffer())
        {
          return false;
        }

       submitPopupClientdeny();
    
       })
     function submitPopupClientdeny(){
      if($("#LumpSum").is(":checked")){
         var balType = '<?php echo $balanceType; ?>';
         
         if(balType == 'prin')
          {
             var totalBalDue = $('#txtFirmBal').val().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Principle Balance';
             var balanceTypeValue = $('#txtFirmBal').val();
          }
          else
          {
             var totalBalDue = $('#totalBalDue').text().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Total Balance Due';
             var balanceTypeValue = $('#totalBalDue').text();
          }

       
         var lumSumAmt = $('#txtLumSumAmt').val().replace("$", "");
         lumSumAmt = lumSumAmt.replace(/\,/g,'');
         lumSumAmt = Number(lumSumAmt);



         var percent = lumSumAmt/totalBalDue * 100;
         percent = Math.round(percent * 100) / 100;

         if(percent == 'Infinity')
          {
           percent = '0.00';
          }

          if($("#SubmitCounterchkclient").is(":checked"))
          {

          }
          else
          {

          $('#myClientModal').modal('show');
          $('.showPaymentPlan').hide();
          $('.showClntCounterPlan').hide();
          $('.showLumSum').show();

          $('#clientlumbSumAmtPopup').text($('#txtLumSumAmt').val());
          $('#clientpaidFullPopup').text($('#PaymentDate').val());
          $('#clienttotalRePmtPopup').text($('#txtLumSumAmt').val());
          $('#totalBalancePopup2').text(balanceTypeValue);
          $('#balanceType02').text(balanceType);
          $('#clientpercntOffer').text(percent+'%');

          if($("#CLNTREJECT").is(":checked"))
          {
             document.getElementById("innerTextDataClnt").innerHTML = '';
            var addInfo = $('#CLNTDENYR').val();
            document.getElementById("innerTextDataClnt").innerHTML += "Are you sure you want to deny this offer ?";
            document.getElementById("innerTextDataClnt").innerHTML += "<br>";
            document.getElementById("innerTextDataClnt").innerHTML += "Additional Comment : "+ addInfo ;
          }
          }
      }
      else if($("#PaymentPlan").is(":checked"))
      {
          var amountOffer = $('#TotalAmountOfOffer').val().replace("$", "");
          amountOffer = amountOffer.replace(/\,/g,'');
          amountOffer = Number(amountOffer);


          var balType = '<?php echo $balanceType; ?>';
         
         if(balType == 'prin')
          {
             var totalBalDue = $('#txtFirmBal').val().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Principle Balance';
             var balanceTypeValue = $('#txtFirmBal').val();
          }
          else
          {
             var totalBalDue = $('#totalBalDue').text().replace("$", "");
             totalBalDue = totalBalDue.replace(/\,/g,'');
             totalBalDue = Number(totalBalDue);
             var balanceType = 'Total Balance Due';
             var balanceTypeValue = $('#totalBalDue').text();
          }

          var percent = amountOffer/totalBalDue * 100;
          percent = Math.round(percent * 100) / 100;
          if(percent == 'Infinity')
          {
           percent = '0.00';
          }

          if($("#SubmitCounterchkclient").is(":checked"))
          {

          }
          else
          {

          $('#myClientModal').modal('show');
          $('.showClntCounterPlan').hide();
          $('.showPaymentPlan').show();
          $('.showLumSum').hide();
         
          $('#pymtPlnClntPopup').text($('#TotalAmountOfOffer').val());
          $('#intPmtDateClntPopup').text($('#InitialInstallmentDate').val());
          $('#mthlyPmtClntPopup').text($('#monthlyPaymentAmt').val());
          $('#finalPmtDateClntPopup').text($('#FinalPaymentDate').val());
          $('#totlNoPymtClntPopup').text($('#NumberofInstallments').val());
          $('#dwnPmtClntPopup').text($('#InitialPaymentAmount').val());
          $('#finlPmtClntPopup').text($('#FinalPaymentAmount').val());
          $('#addedIntrClntPopup').text($('#EnterInterestRate').val());
          $('#clienttotalRePmtPopup').text($('#GrandTotal').val());
          $('#totalBalancePopup2').text(balanceTypeValue);
          $('#balanceType02').text(balanceType);
          $('#clientpercntOffer').text(percent+'%');

          }


           if($("#CLNTREJECT").is(":checked"))
          {
            document.getElementById("innerTextDataClnt").innerHTML = '';
            var addInfo = $('#CLNTDENYR').val();
            document.getElementById("innerTextDataClnt").innerHTML += "Are you sure you want to deny this offer ?";
            document.getElementById("innerTextDataClnt").innerHTML += "<br>";
            document.getElementById("innerTextDataClnt").innerHTML += "Additional Comment : "+ addInfo ;
          }
       } 
     }
     $('#CloseClntDenysubmitInfoBtn2').click(function(){
      $("#CLNTREJECT").prop("checked", false);
      if($('#CLNTDENYR').val() != ''){
        $('#CLNTDENYR').val(null);
      }
    });
     
     function validateClientDenyOffer()
      {
        var flag = true;

        if($('#CLNTDENYR').val() == '')
        {
             $('#ClientDenyCmtError').css('display', 'block');
           $('#ClientDenyCmtError').text("This field is required"); 
             
          flag = false;
        }
        else
        {
      $('#modalDenyPopup01').modal('hide');
      $('.AcaReq').css("overflow", "hidden");
      $('#myClientModal').css({"overflow-x":"hidden", "overflow-y":"auto"});
            $('#ClientDenyCmtError').css('display', 'none');
        }

        if(flag)
        {
          return true;
        }
        else
        {
          return false;
        }
      }
     

    var _validFileExtensions = ['.7z','.adt','.bmp','.cst','.csv','.dat','.doc','.docm','.docx','.exp','.fin','.inv','.jpg','.log','.lst','.mem','.mht','.mp3','.msg','.ods','.pdf','.pmt','.png','.rar','.rmt','.rtf','.tif','.tmp','.txt','.upd','.wav','.wpd','.xls','.xlsb','.xlsm','.xlsx','.xlt','.zip'];    
function ValidateSingleInput(oInput) {
    if (oInput.type == "file") {
        var sFileName = oInput.value;
         if (sFileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validFileExtensions.length; j++) {
                var sCurExtension = _validFileExtensions[j];
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }
             
            if (!blnValid) {
                swal("Error!", "The file type is not supported by this application, please submit using an acceptable format.", "error");
                oInput.value = "";
                return false;
            }
        }
    }
    return true;
}



function ispercentage2(obj, e, allowDecimal, allowNegative) {
        var key;
        var isCtrl = false;
        var keychar;
        var reg;
        if (window.event) {
            key = e.keyCode;
            isCtrl = window.event.ctrlKey
        } else if (e.which) {
            key = e.which;
            isCtrl = e.ctrlKey;
        }
        if (isNaN(key)) return true;
        keychar = String.fromCharCode(key);
        // check for backspace or delete, or if Ctrl was pressed
        if (key == 8 || isCtrl) {
            return true;
        }
        ctemp = obj.value;
        var index = ctemp.indexOf(".");
        var length = ctemp.length;
        ctemp = ctemp.substring(index, length);
         // alert(length+'--'+ctemp.length+'--'+index+'--'+keychar+'--'+ctemp);
        if (index < 0 && length > 9 && keychar != '.' && keychar != '0') {
         
            obj.focus();
            return false;
        }
        if(ctemp.indexOf('.') > -1)
        {         
        if (ctemp.length > 2) {         
            obj.focus();
            return false;
        }
        }
        if (keychar == '0' && length >= 10 && keychar != '.' && ctemp != '10') {
            obj.focus();
            return false;
        }
        reg = /\d/;
        var isFirstN = allowNegative ? keychar == '-' && obj.value.indexOf('-') == -1 : false;
        var isFirstD = allowDecimal ? keychar == '.' && obj.value.indexOf('.') == -1 : false;
        return isFirstN || isFirstD || reg.test(keychar);
    }


    function process(input){
      let value = input.value;
      let letters = value.replace(/[0-9]/g, "");
      input.value = letters;
    }
       

 
</script>
<!--for every 30 sec page logout automatically end-->


   </body>
</html>