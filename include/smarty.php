<?php
defined('_VALID') or die('Restricted Access!');
$tpl        = $config['template'];
$tpl_dir    = 'frontend';
if ( defined('_ADMIN') ) {
    $tpl        = $config['template_admin'];
    $tpl_dir    = 'backend';
}

$smarty  = new Smarty();
$smarty->compile_check = true;
$smarty->debugging = false;
$smarty->cache = 0;
$smarty->template_dir = $config['BASE_DIR']. '/templates/' .$tpl_dir. '/' .$tpl;
$smarty->compile_dir = $config['BASE_DIR']. '/cache/' .$tpl_dir;
foreach ( $config as $key => $value ) {
    $smarty->assign($key, $value);
}
$smarty->assign('languages', $languages);

$smarty->assign('bgcolor',      '#E8E8E8');
if($config['multi_server'] == '1'){
			if(!empty($config['SERVICE_URL'])){
				
			}else{
			global $conn;
			$sql = "SELECT * FROM servers WHERE status = '1'";
			$sql2 = "SELECT COUNT(server_id) AS total_server FROM servers WHERE status = '1' ORDER BY last_used ASC";
			$rs  = $conn->execute($sql);
			if ($conn->Affected_Rows()) {
				$servers = $rs->getrows();			
				$total_serv = $rs->RecordCount();
				$counter = $total_serv-1;	
				for ( $i = 0; $i < $total_serv; $i++)
				{	
					if ($servers[$i]['current_used'] == 1)
					{
					  if ($i < $counter)
					  {
						$params = $i+1;
					  }
					  else
					  {
						$params = 0;
					  }
					  $config['SERVICE_URL'] = $servers[$i]['video_url'];
					}

				}
			}	
		}
		$config['SERVICE_URL'] = str_replace('media/videos','',$config['SERVICE_URL']);
		$output = $config['SERVICE_URL'].'/'.$tmb_folder.'/'.$vid;
		$smarty->assign('pic_baseurl',    $config['SERVICE_URL']);
	}else{
		$smarty->assign('pic_baseurl',    $config['BASE_URL']);
	}
$smarty->assign('baseurl',    $config['BASE_URL']);
$smarty->assign('basedir',    $config['BASE_DIR']);
$smarty->assign('relative',   $config['RELATIVE']);
$smarty->assign('relative_tpl', $config['RELATIVE']. '/templates/' .$tpl_dir. '/' .$tpl);
$smarty->assign('imgurl',     $config['IMG_URL']);
$smarty->assign('vdourl',     $config['VDO_URL']);
$smarty->assign('flvdourl',   $config['FLVDO_URL']);
$smarty->assign('picurl',     $config['PHO_URL']);
$smarty->assign('tmburl',     $config['TMB_URL']);
$smarty->assign('photourl',   $config['PHO_URL']);
$smarty->assign('max_thumb_folders',   $config['max_thumb_folders']);
?>
