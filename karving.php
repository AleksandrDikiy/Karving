<?php
/*
Plugin Name: karving
Plugin URI: http://WildWind.org.ua
Description: Клиенты и заказы по карвингу
Version: 1.0.0
Author: Aleksandr Dikiy
Author URI: https://www.facebook.com/AleksandDikiy
*/
/*
Copyright 2016  Aleksandr Dikiy (email: AleksandrDikiy@gmail.com)
*/

if (!function_exists('get_option')) {
  header('HTTP/1.0 403 Forbidden');
  die;  // Silence is golden, direct call is prohibited
}

if (defined('URE_KARVING_URL')) {
   wp_die('It seems that other version of User Role Editor is active. Please deactivate it before use this version');
}

define('URE_VERSION_KARVING', '1.0.0.0');
define('URE_KARVING_URL', plugin_dir_url(__FILE__));
define('URE_KARVING_PATH', plugin_dir_path(__FILE__));
define('URE_KARVING_FULL_PATH', __FILE__);

add_filter( 'page_template', 'wp_karving_page_template' );
add_filter('page_attributes_dropdown_pages_args', 'register_project_karving_templates');
function wp_karving_page_template( $page_template )
{
  global $post;

  $postMeta = get_post_meta($post->ID, '_wp_page_template', true);

  $dir = plugin_dir_path(__FILE__).'/templates';
  $pluginTemplates = array_diff(scandir($dir), array('..', '.'));
  $addedTemplates = array();
  foreach ($pluginTemplates as $key => $value) {
    if($value === $postMeta){
      $page_template = plugin_dir_path(__FILE__) . '/templates/' . $value;
    }
  }
  return $page_template;
}

add_filter('wp_insert_post_data', 'register_project_karving_templates');

function register_project_karving_templates( $atts ) {

  $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

  $templates = wp_get_theme()->get_page_templates();
  if ( empty( $templates ) ) {
        $templates = array();
  }
  // WORK
  wp_cache_delete( $cache_key , 'themes');

  $dir = plugin_dir_path(__FILE__).'/templates';
  $pluginTemplates = array_diff(scandir($dir), array('..', '.'));
  $addedTemplates = array();
  foreach ($pluginTemplates as $key => $value) {
    $templateName = get_file_data($dir . '/' . $value, array('Template Name'), '');
    $addedTemplates[$value] = $templateName[0];
  }

  $mergedTemplates = array_merge($templates, $addedTemplates);

  wp_cache_add( $cache_key, $mergedTemplates, 'themes', 1800 );

  return $atts;
}

?>
