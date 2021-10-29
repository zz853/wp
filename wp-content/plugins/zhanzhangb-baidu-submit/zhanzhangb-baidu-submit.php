<?php
/**
Plugin Name: zhanzhangb-baidu-submit
Plugin URI: https://www.zhanzhangb.com/2020-921.html
Text Domain: zhanzhangb-baidu-submit
Description: 发布新文章时推送文章URL至百度搜索资源平台，支持普通推送收录、快速收录。
Version: 1.4.2
Author: 站长帮
Author URI: https://www.zhanzhangb.com
License: GNU General Public License (GPL) version 3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Copyright (c) 2020, 站长帮（zhanzhangb.com）

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/*
*BOOTSTRAP FILE
*/
defined( 'ABSPATH' ) || exit;
if (!class_exists('zhanzhangb_baidu_submit')){

class zhanzhangb_baidu_submit{
	function __construct(){
		register_activation_hook( __FILE__, array( $this,'zhanzhangb_baidu_submit_install') );
		add_action( 'transition_post_status',array( $this,'baidu_submit_post' ), 1, 3 );
		if( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'zhanzhangb_baidu_submit_menu' ));
			add_action( 'admin_init', array( $this, 'settings_init' ) );
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'add_action_links' ));
		}
	}
	/***********************************************************************************/
	function zhanzhangb_baidu_submit_install() {
		//if (!get_option('zhanzhangb_baidu_push')){
			//update_option('zhanzhangb_baidu_push',1);
		//}
		delete_option('zhanzhangb_baidu_push');//禁用自动提交
		if ( intval(get_option('zhanzhangb_baidu_submit_number')) > 0){
			return;
		}
		update_option('zhanzhangb_baidu_submit_number',0);
	}
	/***********************************************************************************/
	function zhanzhangb_baidu_settings_title($args){
		echo '<a href="';
		echo esc_url( 'https://ziyuan.baidu.com/linksubmit/index' );
		echo '" target="_blank"><span>';
		echo esc_html__( '获取百度主动推送token ', 'zhanzhangb-baidu-submit' );
		echo '</span></a><br />';
		echo '<a href="';
		echo esc_url( 'https://ziyuan.baidu.com/dailysubmit/index' );
		echo '" target="_blank"><span>';
		echo esc_html__( '获取快速收录token ', 'zhanzhangb-baidu-submit' );
		echo '</span></a>';
	}

	function token_setting_cb(){
		$zhanzhangb_baidu_token = get_option('zhanzhangb_baidu_token');
		echo '<input maxlength="16" size="16" type="text" required="required" pattern="[A-z0-9]{16}" name="zhanzhangb_baidu_token" value="'.$zhanzhangb_baidu_token.'" />';
		if (empty($zhanzhangb_baidu_token)){
			echo esc_html__('*必填','zhanzhangb-baidu-submit');
		}
	}
	/***********************************************************************************/
	function realtime_token_setting_cb(){
		$zhanzhangb_baidu_realtime_token = get_option('zhanzhangb_baidu_realtime_token');
		echo '<input maxlength="16" size="16" type="text" pattern="[A-z0-9]{16}" name="zhanzhangb_baidu_realtime_token" value="'.$zhanzhangb_baidu_realtime_token.'" />';
		if (empty($zhanzhangb_baidu_realtime_token)){
			echo esc_html__('如空，则不开启快速收录','zhanzhangb-baidu-submit');
		}
	}
	/***********************************************************************************/
	function push_setting_cb(){
		$select = get_option('zhanzhangb_baidu_push');
		echo '<select name="zhanzhangb_baidu_push" style="background-color: #EEEEEE" disabled="disabled">';
		echo '<option value="1"';
		if ( $select == '1' ) {
			echo 'selected="selected"';
		}
		echo '>' . esc_html__('关闭自动推送','zhanzhangb-baidu-submit') . '</option><option value="2"';
		if ( $select == '2' ) {
			echo 'selected="selected"';
		}
		echo '>' . esc_html__('开启所有页面自动推送','zhanzhangb-baidu-submit') . '</option><option value="3"';
		if ( $select == '3' ) {
			echo 'selected="selected"';
		}
		echo '>' . esc_html__('首页、文章、页面、分类、标签页自动推送','zhanzhangb-baidu-submit') . '</option><option value="4"';
		if ( $select == '4' ) {
			echo 'selected="selected"';
		}
		echo '>' . esc_html__('仅文章页自动推送','zhanzhangb-baidu-submit') . '</option></select>';
		echo '<span style="color:#FF0000">' . esc_html__( '[百度官方升级维护中，暂停自动提交]', 'zhanzhangb-baidu-submit' ) . '</span>';;
	}
	/***********************************************************************************/
	function zhanzhangb_baidu_submit_menu() {
		if( is_admin() ) {
			add_options_page(
				'站长帮 - 百度搜索推送插件设置',
				'站长帮 - 百度搜索推送插件设置',
				'manage_options',
				'zhanzhangb_baidu_submit',
				array( $this, 'zhanzhangb_baidu_submit_options' )
			);
		}
	}
	/***********************************************************************************/
	function zhanzhangb_baidu_submit_options() {
		if ( !current_user_can( 'manage_options' ) ){
			wp_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );
		}
		echo '<div class="zhanzhangb_baidu">';
		echo '<form method="post" action="options.php">';
		echo '<h3>'.esc_html__( '百度搜索推送插件作者：', 'zhanzhangb-baidu-submit' ).'<a href="'.esc_url( 'https://www.zhanzhangb.com/').'" target="_blank">'.esc_html__( '站长帮', 'zhanzhangb-baidu-submit' ).'</a></h3>';
		echo esc_html__( '插件技术支持：', 'zhanzhangb-baidu-submit' ).'<a href="'.esc_url('https://www.zhanzhangb.com/2020-921.html').'" target="_blank">'.esc_url('https://www.zhanzhangb.com/2020-921.html').'</a><br />';
		echo esc_html__( '其它插件推荐：', 'zhanzhangb-baidu-submit' ).'<a href="'.esc_url('https://www.zhanzhangb.com/2020-817.html').'" target="_blank">'.esc_html__( '文章分享插件 含微信与QQ分享带缩略图', 'zhanzhangb-baidu-submit' ).'</a>，<a href="'.esc_url('https://www.zhanzhangb.com/2020-621.html').'" target="_blank">'.esc_html__( '自动刷新腾讯云CDN缓存插件', 'zhanzhangb-baidu-submit' ).'</a><br />';
		echo '<hr />';
		settings_fields( 'zhanzhangb_baidu_settings' );
		do_settings_sections( 'zhanzhangb_baidu_settings' );
		//echo '<h4>'.esc_html__('百度自动推送功能将在下一个版本推出','zhanzhangb-baidu-submit').'</h4>';
		submit_button();
		echo '<hr />';
		if (get_option('zhanzhangb_baidu_token') !== false){
			echo '<span style="color:#009933">' . esc_html__('主动提交功能已开启','zhanzhangb-baidu-submit') . '</span><br />';
		}else {
			echo '<span style="color:#FF0000">' . esc_html__( '主动提交功能未开启，请正确设置推送密钥（token值）', 'zhanzhangb-baidu-submit' ) . '</span><br />';
		}
		echo esc_html__('主动推送成功数量：','zhanzhangb-baidu-submit') . get_option('zhanzhangb_baidu_submit_number') . esc_html__('条','zhanzhangb-baidu-submit').'<br />';
		echo '<hr />';
		if (get_option('zhanzhangb_baidu_realtime_token')){
			echo '<span style="color:#009933">' . esc_html__('快速收录提交功能已开启','zhanzhangb-baidu-submit') . '</span><br />';
		}else {
			echo '<span style="color:#FF0000">' . esc_html__( '快速收录提交功能未开启，请正确设置token', 'zhanzhangb-baidu-submit' ) . '</span><br />';
		}
		date_default_timezone_set(get_option('timezone_string'));
		if (get_option('zhanzhangb_baidu_realtime_date') == date('y-m-d')){
			echo esc_html__('快速收录今日提交成功：','zhanzhangb-baidu-submit') . get_option('zhanzhangb_baidu_realtime_number').esc_html__('条','zhanzhangb-baidu-submit') .'<br />';
		}else {
			echo esc_html__( '快速收录今日尚无成功提交', 'zhanzhangb-baidu-submit' ) . '<br />';
		}
		echo '</form></div>';
	}
	/***********************************************************************************/
	function add_action_links ( $links ) {
	 $mylinks = array(
	 '<a href="' . admin_url( 'options-general.php?page=zhanzhangb_baidu_submit' ) . '">' . __('Settings') . '</a>'
	 );
	return array_merge( $links, $mylinks );
	}
	/***********************************************************************************/
	function settings_init(){
		add_settings_section(
			'zhanzhangb_baidu_set',
			__( '百度推送设置', 'zhanzhangb-baidu-submit' ),
			array( $this, 'zhanzhangb_baidu_settings_title' ),
			'zhanzhangb_baidu_settings'
		);
		add_settings_field(
			'zhanzhangb_baidu_token', //id
			__( '主动推送密钥（token）：', 'zhanzhangb-baidu-submit' ), 
			array( $this, 'token_setting_cb' ), 
			'zhanzhangb_baidu_settings', 
			'zhanzhangb_baidu_set',
			'SecretId',
			array( 'label_for' => 'zhanzhangb_baidu_token' ) 
		);
		add_settings_field(
			'zhanzhangb_baidu_realtime_token', //id
			__( '快速收录推送密钥（token）：', 'zhanzhangb-baidu-submit' ), 
			array( $this, 'realtime_token_setting_cb' ), 
			'zhanzhangb_baidu_settings', 
			'zhanzhangb_baidu_set',
			'SecretId',
			array( 'label_for' => 'zhanzhangb_baidu_realtime_token' ) 
		);
		register_setting( 'zhanzhangb_baidu_settings', 'zhanzhangb_baidu_token' );
		register_setting( 'zhanzhangb_baidu_settings', 'zhanzhangb_baidu_realtime_token' );
		//register_setting( 'zhanzhangb_baidu_settings', 'zhanzhangb_baidu_push' );
	}
	/***********************************************************************************/
	function baidu_submit_post($new_status, $old_status, $post){
		$WEB_TOKEN = get_option('zhanzhangb_baidu_token');
		if ( !empty($WEB_TOKEN) && $post->post_type == 'post' && $new_status == 'publish' && $old_status != 'publish' ){
		$WEB_DOMAIN = get_option('home');
		$url = get_permalink($post->ID);
		$api = 'http://data.zz.baidu.com/urls?site='.$WEB_DOMAIN.'&token='.$WEB_TOKEN;
		$res = wp_remote_post( $api, array(
			'body'    => $url,
			'headers' => array('Content-Type: text/plain'),
		) );
		$result = json_decode(wp_remote_retrieve_body($res),true);
		$number = intval(get_option('zhanzhangb_baidu_submit_number')) + intval($result["success"]);
		update_option('zhanzhangb_baidu_submit_number',$number);
		$realtime_TOKEN = get_option('zhanzhangb_baidu_realtime_token');
		if ( !empty($realtime_TOKEN) ){
			$api = 'http://data.zz.baidu.com/urls?site='.$WEB_DOMAIN.'&token='.$realtime_TOKEN.'&type=daily';
			$res = wp_remote_post( $api, array(
				'body'    => $url,
				'headers' => array('Content-Type: text/plain'),
			) );
			$result = json_decode(wp_remote_retrieve_body($res),true);
			date_default_timezone_set(get_option('timezone_string'));
			if (get_option('zhanzhangb_baidu_realtime_date') != date('y-m-d')){
				update_option('zhanzhangb_baidu_realtime_date',date('y-m-d'));
				update_option('zhanzhangb_baidu_realtime_number',0);
			}
			$number = intval(get_option('zhanzhangb_baidu_realtime_number')) + intval($result["success_daily"]);
			update_option('zhanzhangb_baidu_realtime_number',$number);
		}
		}
		return;
	}
	/***********************************************************************************/
}
}
new zhanzhangb_baidu_submit()
?>