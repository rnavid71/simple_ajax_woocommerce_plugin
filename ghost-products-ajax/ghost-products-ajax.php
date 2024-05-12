<?php
/**
 * Plugin Name: Ghost Products Ajax
 * Description: implement a form to search woocommerce products by ajax [ghost_ajax-search]
 * Author: Navid Rezaei
 */

use inc\AjaxApi;

require_once 'inc/AjaxApi.php';

// defines
define('PLUGIN_DIR_URI', plugin_dir_url(__file__));
define('PLUGIN_DIR_PATH', plugin_dir_path(__file__));

$gAjax = new AjaxApi();

add_shortcode('ghost_ajax-search', array($gAjax, 'import_form'));
add_action('wp_ajax_ghost_product', array($gAjax, 'search'));
add_action('wp_ajax_nopriv_ghost_product ', array($gAjax, 'search'));