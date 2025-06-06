<?php
/*
Plugin Name: Indicadores Económicos Chile
Plugin URI: https://github.com/Mushroom0047/indicadores-economicos-chile-plugin-wp
Description: Muestra mediante un shortcode los Indicadores económicos actualizados en Chile.
Version: 1.2.1
Author: Mushroom Dev 🍄
Author URI: https://hectorvaldes.dev/
Donate link: https://ko-fi.com/mushroom47
Tags: indicadores, Chile, economía, uf, dólar, ipc
Requires at least: 4.0
Tested up to: 6.8
Stable tag: 1.2.1
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: indicadores-economicos-chile
*/

if (!defined('ABSPATH')) exit; // Salir si se accede directamente

// Incluir los archivos principales del plugin
require_once plugin_dir_path(__FILE__) . 'includes/class-indecochile-api.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-indecochile-shortcode.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-indecochile-admin.php';

// Inicializar las funcionalidades del plugin
indecochile_init();

function indecochile_init() {
    // Registrar el shortcode
    add_shortcode('indicadores', 'indecochile_mostrar_indicador');

    // Añadir la página al menú de Herramientas
    add_action('admin_menu', 'indecochile_agregar_pagina_herramientas');
}
?>
