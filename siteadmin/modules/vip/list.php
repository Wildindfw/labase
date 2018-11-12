<?php
defined('_VALID') or die('Restricted Access!');

Auth::checkAdmin();



if ( isset($_POST['add_vip']) ) {
    $name   = trim($_POST['name']);
    $cost   = trim($_POST['cost']);
    settype($cost, 'float');
    if ( $name == '' ) {
        $errors[] = '等级名称不能为空!';
		$err['name'] = 1;
    }else if ( empty($cost) ) {
        $errors[] = '等级价格不能为空!';
        $err['cost'] = 1;
    }else if(!preg_match('/^[0-9]+(.[0-9]{1,2})?$/', $cost)){
        $errors[] = '等级价格只能是整数或小数二位!';
        $err['cost'] = 1;
    }
    else {        
        
        $sql        = "SELECT id FROM vip WHERE name = '" .mysql_real_escape_string($name). "' LIMIT 1";
        $conn->execute($sql);
        if ( $conn->Affected_Rows() > 0 ) {
            $errors[]   = '等级名称 \'' .htmlspecialchars($name, ENT_QUOTES, 'UTF-8'). ' 已存在,请换个名称!';
        }
    }
    $vip['name'] = $name;
    $vip['cost'] = $cost;
	
    
    if ( !$errors ) {
        $sql = "INSERT INTO vip (name, cost) VALUES ('" .mysql_real_escape_string($name). "', '".mysql_real_escape_string($cost)."')";
        $conn->execute($sql);
        $CID = $conn->Insert_ID(); 
		
    }    
   
}

$query      = constructQuery();
$sql        = $query['select'];
$rs         = $conn->execute($sql);
$vips   = $rs->getrows();

function constructQuery()
{
    global $smarty;

    $query              = array();
    $query_select       = "SELECT * FROM vip";
	
	    
    $query['select']    = $query_select ;
    
    
    return $query;
}

$smarty->assign('vips', $vips);
?>
