<?php  
/* 
Plugin Name: JK Development Console
Plugin URI: http://lyan.no-ip.org
Version: 0.1
Author: Kevin & Jonas
Description: A development console for adding custom javascript and CSS.
*/  


require_once('dev-console-settings.php');	

function jkdevconsole_activation() {
	global $filepath, $cssFile, $jsFile;
	
	if(!file_exists($filepath . $cssFile)) {
		$newCssFile = fopen($filepath . $cssFile, "w") or die("Unable to open file!");
		$data = "/* This css is used by JK development console */\n";
		fwrite($newCssFile, $data);
		fclose($newCssFile);
	}
	if(!file_exists($filepath . $jsFile)) {
		$newJsFile = fopen($filepath . $jsFile, "w") or die("Unable to open file!");
		$data  = "/* This css is used by JK development console */\n"; 
		$data .= "window.onload=function(){\n\n};"; 
		fwrite($newJsFile, $data);
		fclose($newJsFile);
	}
	
}
register_activation_hook(__FILE__, 'jkdevconsole_activation');

function jkdevconsole_deactivation() {
	//DO NOTHING. FOR NOW!
}
register_deactivation_hook(__FILE__, 'jkdevconsole_deactivation');

function add_header_data() {
	global $pluginUrl, $cssFile, $jsFile;

	echo '<!-- JK-dev-console data -->' . PHP_EOL;
	echo '<link rel="stylesheet" type="text/css" href="'.$pluginUrl . $cssFile.'">' . PHP_EOL;
	echo '<script src="'.$pluginUrl . $jsFile.'"></script>' . PHP_EOL;
	echo '<!-- end JK-dev-console data -->' . PHP_EOL;
}
add_action('wp_head', 'add_header_data');

require_once 'dev-console-admin.php';
add_action('admin_menu', 'jkdev_console_menu');
function jkdev_console_menu() {
	add_menu_page('Development Console', 'Dev Console', 'administrator', __FILE__, 'JKDev_console_admin_page', plugin_dir_url( __FILE__ ).'/jklogo.svg');
	//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
}

?>