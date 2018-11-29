<?php
defined('_VALID') or die('Restricted Access!');

Auth::checkAdmin();

$adv    = array('name' => '', 'group' => 0, 'text' => '', 'status' => '1');
if ( isset($_POST['adv_add']) ) {
    $adv_name   = trim($_POST['adv_name']);
    $adv_group  = trim($_POST['adv_group']);
    $adv_text   = trim($_POST['adv_text']);
    $adv_status = trim($_POST['adv_status']);
    
    $adv_starttime = trim($_POST['adv_starttime']);
    $adv_exptime = trim($_POST['adv_exptime']);
    
    $adv_desc   = trim($_POST['adv_desc']);
    
    if ( $adv_name == '' ) {
        $errors[]       = 'Advertise name field cannot be left blank!';
		$err['adv_name'] = 1;
    }
    $adv['name']    = $adv_name;

    
    if ( $adv_group == '0' ) {
        $errors[]       = 'Please select an advertise group!';
		$err['adv_group'] = 1;		
    }
    $adv['group']   = intval($adv_group);

    
    if ( $adv_text == '' ) {
        $errors[]       = 'Advertise code textarea cannot be blank!';
		$err['adv_text'] = 1;			
    }
    
    if($adv_starttime){
        //日期转时间戳
        $adv_starttime = strtotime($adv_starttime."00:00:00");
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
        $adv_exptime = strtotime($adv_exptime."23:59:59");
        if(empty($adv_exptime)){
            $errors[]       = '请填写下架时间!';
            $err['adv_exptime'] = '请填写下架时间';
        }
    }else{
        $errors[]       = '请填写下架时间!';
        $err['adv_exptime'] = '请填写下架时间';
    }
    
    
    $adv['text']    = $adv_text;
    
 

	$adv['status']      = ( $adv_status == '1' ) ? '1' : '0';
    
    if ( !$errors ) {
        $sql            = "INSERT INTO adv (adv_name, adv_group, adv_text, adv_addtime, adv_status,adv_starttime,adv_exptime,adv_desc)
                           VALUES ('" .mysql_real_escape_string($adv_name). "', " .intval($adv_group). ",
                                   '" .mysql_real_escape_string($adv_text). "', " .time(). ", '" .mysql_real_escape_string($adv_status). "', '" .mysql_real_escape_string($adv_starttime). "', '" .mysql_real_escape_string($adv_exptime). "', '" .mysql_real_escape_string($adv_desc). "')";                                   
        $conn->execute($sql);
        $messages[]     = 'Advertising banner successfully added!';
    }
}

$sql        = "SELECT advgrp_id, advgrp_name FROM adv_group ORDER BY advgrp_name ASC";
$rs         = $conn->execute($sql);
$advgroups  = $rs->getrows();

$smarty->assign('adv', $adv);
$smarty->assign('advgroups', $advgroups);
?>

