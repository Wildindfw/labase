<?php
defined('_VALID') or die('Restricted Access!');

Auth::checkAdmin();

$adv        = array('adv_id' => 0, 'adv_name' => '', 'adv_group' => 0, 'adv_text' => '', 'adv_status' => '0');

$AID    = ( isset($_GET['AID']) && advExists($_GET['AID']) ) ? intval($_GET['AID']) : NULL;
if ( !$AID ) {
    $errors[]    = 'Invalid advertise id!';
}

$adv['adv_id'] = $AID;

if ( isset($_POST['adv_edit']) && !$errors ) {
    $adv_name   = trim($_POST['adv_name']);
    $adv_group  = trim($_POST['adv_group']);
    $adv_text   = trim($_POST['adv_text']);
    $adv_status = trim($_POST['adv_status']);
    
    $adv_starttime = trim($_POST['adv_starttime']);
    $adv_exptime = trim($_POST['adv_exptime']);
    
    $adv_desc = trim($_POST['adv_desc']);
    
    if ( $adv_name == '' ) {
        $errors[]       = 'Advertise name field cannot be left blank!';
		$err['adv_name'] = 1;		
    }
    $adv['adv_name']    = $adv_name;

    
    if ( $adv_group == '0' ) {
        $errors[]       = 'Please select an advertise group!';
		$err['adv_group'] = 1;			
    }
    $adv['adv_group']   = intval($adv_group);

    
    if ( $adv_text == '' ) {
        $errors[]       = 'Advertise code textarea cannot be blank!';
		$err['adv_text'] = 1;			
    }
    $adv['adv_text']    = $adv_text;
        
    if($adv_starttime){
        //日期转时间戳
        $adv_starttime = strtotime($adv_starttime." 00:00:01");
        if(empty($adv_starttime)){
            $errors[]       = '请填写上架时间!';
            $err['adv_starttime'] = '请填写上架时间';
        }
    }else{
        $errors[]       = '请填写上架时间!';
        $err['adv_starttime'] = '请填写上架时间';
    }
    
    
    if($adv_exptime){
        //日期转时间戳
        $adv_exptime = strtotime($adv_exptime." 23:59:59");
        if(empty($adv_exptime)){
            $errors[]       = '请填写下架时间!';
            $err['adv_exptime'] = '请填写下架时间';
        }
    }else{
        $errors[]       = '请填写下架时间!';
        $err['adv_exptime'] = '请填写下架时间';
    }
    
    $adv['adv_status']      = ( $adv_status == '1' ) ? '1' : '0';
    
   
    
    if ( !$errors ) {
        $sql            = "UPDATE adv SET adv_name = '" .mysql_real_escape_string($adv_name). "', adv_group = " .intval($adv_group). ",
                                          adv_text = '" .mysql_real_escape_string($adv_text). "', adv_status = '" .mysql_real_escape_string($adv_status). "',
                                           adv_starttime = '" .mysql_real_escape_string($adv_starttime). "', adv_exptime = '" .mysql_real_escape_string($adv_exptime). "',
                                            adv_desc = '" .mysql_real_escape_string($adv_desc). "' 
                           WHERE adv_id = " .intval($AID). " LIMIT 1";
       
        $conn->execute($sql);
        $messages[]     = 'Advertising banner successfully updated!';
    }
}

if ( !$errors ) {
    $sql    = "SELECT * FROM adv WHERE adv_id = " .intval($AID). " LIMIT 1";
    $rs     = $conn->execute($sql);
    $adv    = $rs->getrows();
    $adv    = $adv['0'];
    
    if($adv['adv_starttime']){
        $adv['adv_starttime_format'] = date("Y-m-d",$adv['adv_starttime']);
    }
    
    if($adv['adv_exptime']){
        $adv['adv_exptime_format'] = date("Y-m-d",$adv['adv_exptime']);
    }
}

$sql        = "SELECT advgrp_id, advgrp_name FROM adv_group ORDER BY advgrp_name ASC";
$rs         = $conn->execute($sql);
$advgroups  = $rs->getrows();

function advExists( $adv_id ) {
    global $conn;
    
    $sql    = "SELECT adv_id FROM adv WHERE adv_id = " .intval($adv_id). " LIMIT 1";
    $conn->execute($sql);
    
    return $conn->Affected_Rows();
}

$smarty->assign('adv', $adv);
$smarty->assign('advgroups', $advgroups);
?>
