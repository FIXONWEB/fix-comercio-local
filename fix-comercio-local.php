<?php
/**
 * Plugin Name:     Fix Comércio Local
 * Plugin URI:      https://github.com/fixonweb/fix-comercio-local
 * Description:     Gerenciar o cadastro de pontos comerciais do seu bairro
 * Author:          FIXONWEB
 * Author URI:      https://fixonweb.com.br
 * Text Domain:     fix-comercio-local
 * Domain Path:     /languages
 * Version:         0.1.23
 *
 * @package         Fix_Comercio_Local
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

require 'plugin-update-checker.php';
$fix1608230887_url_update 	= 'https://github.com/fixonweb/fix-comercio-local';
$fix1608230887_slug 		= 'fix-comercio-local/fix-comercio-local';
$fix1608230887_check 		= Puc_v4_Factory::buildUpdateChecker($fix1608230887_url_update,__FILE__,$fix1608230887_slug);

$plugin_dir_path = plugin_dir_path( __FILE__ );
$plugin_file_path = __FILE__;

include $plugin_dir_path."includes/functions.php";
include $plugin_dir_path."includes/parse_request.php";
include $plugin_dir_path."includes/activation_hook.php";
include $plugin_dir_path."includes/cpts_comercio_local.php";
include $plugin_dir_path."includes/taxonomy_comercio-categoria.php";
include $plugin_dir_path."includes/shortcode.php";
include $plugin_dir_path."includes/meta_boxes.php";
include $plugin_dir_path."includes/single_template.php";
