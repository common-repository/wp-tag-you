<?php
/**
 * @package Wp Tag You (mention users to comments)
 * @version 1.1
 */
/*
Plugin Name: WP Tag You
Description: Allows website users to tag(mention) each other like facebook using "@" keyword while posting comments on a WP Post/Pages/Custom Posts.
Author: Ankur Vishwakarma
Version: 1.1
Author URI: ankurvishwakarma54@yahoo.com 
*/
if (!class_exists('WP_TagYou')) {
class WP_TagYou {
var $wpty_dirpath;
var $wpty_urlpath;
var $settings;
function __construct(){
$this->wpty_urlpath=plugin_dir_url( __FILE__ );
$this->wpty_dirpath=plugin_dir_path(__FILE__);
add_action( 'wp_enqueue_scripts', array($this,'load_front_js_css_files' ));
add_action( 'admin_enqueue_scripts', array($this,'load_backend_js_css_files' ));
$this->includes();
add_action('admin_menu',array($this,'wpty_settings'));
register_activation_hook( __FILE__, array($this,'wpty_setup'));

$this->settings = array(
					'switch' => get_option('wpty_on_off_option'),
					'classes' => get_option('wpty_classes_to_use'),
					'type' => get_option('wpty_who_can_use'),
					'roles' => get_option('wpty_selected_roles'),
					);
}

//Setup
function wpty_setup(){
global $wp_roles;
update_option('wpty_on_off_option','on');
update_option('wpty_who_can_use',1);
$roles_arr=array();
$roles = $wp_roles->get_names();
foreach ($roles as $key => $name) {
	$roles_arr[]=$key;
}
update_option('wpty_selected_roles',$roles_arr);

}
//settings
function wpty_settings(){
add_menu_page('WP Tag You', 'WP Tag You', 'manage_options', 'wp-tag-you', array($this,'wp_tag_you_settings_page'),'dashicons-testimonial',10 );
}

function wp_tag_you_settings_page(){
	do_action('wpty_settings_page');
}
//Include files
function includes(){
	require_once $this->wpty_dirpath.'functions.php';
	require_once $this->wpty_dirpath.'tag-you-controller.php';
}

// Load JS
function load_front_js_css_files(){
    wp_enqueue_style( 'tagyou-css', $this->wpty_urlpath . 'css/tagyou.css' );
    wp_enqueue_style( 'parsor-css', $this->wpty_urlpath . 'css/parser.css' );
	wp_enqueue_script( 'caret-js', $this->wpty_urlpath .  'js/front/caret.js' );
    wp_enqueue_script( 'parsor-js', $this->wpty_urlpath . 'js/front/parsor.js' );
    wp_enqueue_script( 'tagyou-js', $this->wpty_urlpath . 'js/front/tagyou.js' );
	wp_localize_script( 'tagyou-js', 'tagyou',
		array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'settings' => $this->settings
			) 
	);
	
}

function load_backend_js_css_files($hook){
	if($hook!='toplevel_page_wp-tag-you'){
		return false;
	}
	wp_enqueue_style( 'tagyou-css', $this->wpty_urlpath . 'css/admin/settings.css' );
}
}
global $wp_tagyou;
$wp_tagyou=new WP_TagYou;
}