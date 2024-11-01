<?php


function wpty_messages($log){
	$list='';
	if(!empty($log)){
		
		if(!empty($log['success'])){
		
		foreach ($log['success'] as $msg) {
			$list.='<p style="text-align:center;color:green">'.$msg.'</p><br />';
		}
	}
	if(!empty($log['error'])){
		
		foreach ($log['error'] as $msg) {
			$list.='<p style="text-align:center;color:red">'.$msg.'</p><br />';
		}
	}
	
}
return $list;
}


function wpty_view($file,$attr_arr=array()){


if(empty($file)){
return false;
}

if(!empty($attr_arr) && is_array($attr_arr)){
extract($attr_arr);
}

$extension='.php';
$twem_dirpath = plugin_dir_path(__FILE__).'view/';

if(file_exists($twem_dirpath.$file.$extension)){
include $twem_dirpath.$file.$extension;
}
}
?>