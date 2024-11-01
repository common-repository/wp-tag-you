<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!class_exists('tagYouController')){

class tagYouController{

function __construct(){
add_action('wp_ajax_fetch_all_user',array($this,'fetch_all_user'));//;logged in users
add_action('wp_ajax_nopriv_fetch_all_user',array($this,'fetch_all_user')); // non logged in users
add_action('comment_post', array($this,'inform_tagged_user'));
add_action('trigger_tagged_user_email',array($this,'email_to_tagged_user'),10,2);
add_action('wpty_settings_page',array($this,'wpty_settings_page'));
} //construct

//Admin Settings
function wpty_settings_page(){

$msg = $this->wpty_handle_post($_POST);
echo wpty_messages($msg);

$params = array(
'switch' => get_option('wpty_on_off_option'),
'classes' => get_option('wpty_classes_to_use'),
'type' => get_option('wpty_who_can_use'),
'roles' => get_option('wpty_selected_roles'),
);

wpty_view('settings',array('params'=>$params));
}

function wpty_handle_post($post){
	$log=array();
	if(isset($post['wptys_being_submit'])){
	
	if(isset($post['on_off_option'])){
		update_option('wpty_on_off_option',$post['on_off_option']);
	}
	if(isset($post['classes_to_use'])){
		update_option('wpty_classes_to_use',sanitize_text_field(preg_replace( "/\r|\n/", "", trim($post['classes_to_use']))));
	}	
	if(isset($post['who_can_use'])){ //logged in or non logged in or both
		update_option('wpty_who_can_use',$post['who_can_use']);
	}
	
	update_option('wpty_selected_roles',(isset($post['selected_roles'])) ? $post['selected_roles'] : array());
	
	$log['success'][] = 'Settings has been updated!';
	}
	return $log;
}
/*---------------------------------------------*
* START : Set mail type
*--------------------------------------------*/

function ty_set_content_type( $content_type ) {
	return 'text/html';
}

/*---------------------------------------------*
* START : Format Parser
*--------------------------------------------*/
function parse_mail_keywords($mail_format,$post,$comment,$taggeduser){

	$mail_format = str_replace("[post-id]", $post->ID, $mail_format);
	$mail_format = str_replace("[post-title]", $post->post_title, $mail_format);
	$mail_format = str_replace("[comment-author]", $comment->comment_author, $mail_format);
	$mail_format = str_replace("[comment-author-email]", $comment->comment_author_email, $mail_format);
	$mail_format = str_replace("[comment-author-url]", $comment->comment_author_url, $mail_format);
	$mail_format = str_replace("[comment-text]", $comment->comment_content, $mail_format);
	$mail_format = str_replace("[comment-link]", get_permalink($comment->comment_post_ID), $mail_format);
	$mail_format = str_replace("[comment-id]", $commentID, $mail_format);
	$mail_format = str_replace("[site-url]", get_option('siteurl'), $mail_format);
	$mail_format = str_replace("[blog-name]", get_option('blogname'), $mail_format);
	$mail_format = str_replace("[tagged-user]", $taggeduser->display_name, $mail_format);

	return $mail_format;
}

/*---------------------------------------------*
* START : Send email to tagged users
*--------------------------------------------*/

function email_to_tagged_user($taggeduser,$comment){
	
global $wp_tagyou;
$post = get_post($comment->comment_post_ID);
$mail_format='';
$sent=false;
$mail_format=get_option('ty_notify_mail_format');

if(empty($mail_format)){
$mail_format=file_get_contents($wp_tagyou->wpty_dirpath.'templates/default_mail_format/default_mail_format.php');
}

$mail_format=apply_filters('ty_alter_mail_format',$mail_format);

$body=$this->parse_mail_keywords($mail_format,$post,$comment,$taggeduser);

$to=$user->user_email;
$subject = apply_filters('ty_alter_mail_subject','You are tagged in conversation');

$admin_email = get_option('admin_email');
$headers= "From:$admin_email\r\n";
$headers .= "Reply-To:$admin_email\r\n";
add_filter( 'wp_mail_content_type', array($this,'ty_set_content_type'));
//@file_put_contents(ABSPATH.'/sent_mail_body_'.$taggeduser->ID.'.txt', $body);
$sent=@wp_mail($to, $subject, $body, $headers);
remove_filter( 'wp_mail_content_type', array($this,'ty_set_content_type'));

return $sent;

}

/*---------------------------------------------*
* START : update comment text
*--------------------------------------------*/

function ty_update_comment_text($comment_id,$update_text){
	global $wpdb;
	if(empty($comment_id) || $comment_id==0){
		return false;
	}
	if(empty($update_text)){
		$update_text='';
	}
	
	$comments=$wpdb->prefix.'comments';

	$wpdb->update( 
	$comments, 
	array( 
		'comment_content' => $update_text
	), 
	array( 'comment_ID' => $comment_id ), 
	array( 
		'%s',
	), 
	array( '%d' ) 
	);
}
/*---------------------------------------------*
* START : Notify to users to been tagged in conversation
*--------------------------------------------*/

function inform_tagged_user($CID){
	$log = $this->wpty_validate_user();

	if($log['pass'] == false){
		return false;
	}

	$details=get_comment($CID);
	$text = $details->comment_content;
	$tagged_arr = array();
	preg_match_all("/\@[a-z0-9_]+/i", $text, $tagged_arr, PREG_SET_ORDER);


	if(!empty($tagged_arr[0]) && count($tagged_arr)>0){
		$ref_text=$text;
		if(count($tagged_arr) >0){
			foreach ($tagged_arr  as $tu) {
			$dn=str_replace('@','',$tu[0]);
			$ref_text = str_replace($tu[0], $dn, $ref_text);
			}
			$this->ty_update_comment_text($CID,$ref_text);
		}
		

		foreach ($tagged_arr as $tagged_user) {
			$withat=$tagged_user[0];
			$display_name=str_replace('@','',$withat);
			$user=get_user_by('slug',$display_name);
			if($user->spam==0 && $user->deleted==0){
				$email=$user->user_email;
				$notify_emails[$user->ID]=$email;
				do_action('trigger_tagged_user_email',$user,$details);
			}
		}
	}
	
}

/*---------------------------------------------*
* START : Get all registered users
*--------------------------------------------*/

function wpty_validate_user(){
global $current_user,$wp_tagyou;
	$log=array();
	$pass=true;
	$params = $wp_tagyou->settings;

	if($params['switch'] == 'off'){
	$pass=false;
	$log['error'][]='Feature is disabled.';
	}
	/*elseif(empty($params['classes'])){
	$pass=false;
	$log['error'][]='No class detected to apply feature.';
	}*/
	elseif((int)$params['type'] == 2 && $current_user->ID > 0){
	$pass = false;
	$log['error'][]='Feature only available for non logged in users.';
	}
	elseif((int)$params['type'] == 1 && $current_user->ID <= 0){ // 1 == logged in, 2 == non logged in, 3 == both 
	$pass = false;
	$log['error'][]='Feature only available for logged in users.';
	}
	elseif((int)$params['type'] == 1 && $current_user->ID > 0){
	$valid_role_count=0;
	if(!empty($params['roles'])){
		foreach ($current_user->roles as $role) {
			if(in_array($role, $params['roles'])){
				$valid_role_count++;
			}
		}
	}
	if($valid_role_count <= 0){
	$pass = false;
	$log['error'][]='Insuffient permission to user role.';
	}
	}
	return array('pass'=>$pass,'error'=>$log);
}

function fetch_all_user(){
global $current_user,$wp_tagyou;
$log = $this->wpty_validate_user();
if($log['pass'] == true){
$all=array();
$blog_id=$GLOBALS['blog_id'];
$blogusers = get_users( 'blog_id='.$blog_id.'&orderby=nicename' );
foreach ( $blogusers as $user ) {

	$each_user[$user->ID]=(object)array(
		'id' => $user->ID,
		'avatar_url' => get_avatar_url($user->ID),
		'first_name' => $user->first_name,
		'last_name' => $user->first_name,
		'display_name' => $user->display_name
		);
}
}

if(!empty($each_user)){
	$log['success']=(object)$each_user;
}
echo json_encode($log);
	wp_die();
}

} // class
new tagYouController;
} //if
?>