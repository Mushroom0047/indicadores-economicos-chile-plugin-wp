<?php
/*
*Plugin Name: Indicadores Económicos Chile
*Plugin URI: https://github.com/Mushroom0047/indicadores-economicos-chile-plugin-wp
*Description: Muestra mediante un shortcode los Indicadores económicos actualizados en Chile.
*Version: 1.0.0
*Author: Mushroom Dev 🍄
*Author URI: https://hectorvaldes.dev/
*Donate link: https://ko-fi.com/mushroom47
*Tags: indicadores, Chile, economía, uf, dolar, ipc
*Requires at least: 4.0
*Tested up to: 6.4
*Stable tag: 1.0.0
*License: GPLv2
*License URI: https://www.gnu.org/licenses/gpl-2.0.html
*Text Domain: indicadores-economicos-chile
*/
if ( ! defined( 'ABSPATH' ) ) exit; // Salir si se accede directamente

global $iec_indicadores_data;

//! Función para obtener los datos de la API de mindicador.cl
function iec_obtener_datos_mindicador_api() {
    $iec_api_url = 'https://www.mindicador.cl/api';

    // Realizar la solicitud GET a la API
    $iec_response = wp_remote_get($iec_api_url);

    // Verificar si la solicitud fue exitosa
    if (is_wp_error($iec_response)) {
        // En caso de error al hacer la solicitud, registrar el error en el log
        error_log("Error al obtener datos de la API: " . $iec_response->get_error_message());
        // Informar al usuario de manera más específica sobre el problema
        return "Error al obtener datos de la API. Por favor, inténtalo de nuevo más tarde.";
    } else {
        // Si la solicitud fue exitosa, obtener y decodificar los datos JSON
        $iec_body = wp_remote_retrieve_body($iec_response);
        $iec_data = json_decode($iec_body);

         // Verificar si se obtuvieron datos válidos
         if ($iec_data) {
            global $iec_indicadores_data;
            // Almacenar los valores en las variables globales
            $iec_indicadores_data = array(
                'uf' => $iec_data->uf,
                'ivp' => $iec_data->ivp,
                'dolar' => $iec_data->dolar,
                'dolar intercambio' => $iec_data->dolar_intercambio,
                'euro' => $iec_data->euro,
                'ipc' => $iec_data->ipc,
                'imacec' => $iec_data->imacec,
                'tpm' => $iec_data->tpm
            );

            return "Datos de la API almacenados correctamente.";
        } else {
            return "No se pudieron obtener datos válidos de la API.";
        }
    }
}

//! Función para el shortcode que mostrará los indicadores según el parámetro recibido
function iec_mostrar_indicador($iec_atributos) {
    if(extension_loaded('intl')){
        // Actualizar los datos de la API antes de mostrar el divisa solicitado
        iec_obtener_datos_mindicador_api();
        
        global $iec_indicadores_data;
        $iec_numberFormat = NumberFormatter::create('es_CL', NumberFormatter::CURRENCY);
        $value_temp;

        // Obtener el parámetro del shortcode
        $iec_atributos = shortcode_atts(array(
            'divisa' => '',
            'nombre' => false,
            'class' => '',
            'id' => '',
        ), $iec_atributos);

        // Verificar si se proporcionó un divisa válido y existe en los datos almacenados
        if (!empty($iec_atributos['divisa']) && isset($iec_indicadores_data[$iec_atributos['divisa']])) {
            // Verificar si los datos de la API están disponibles y el valor no es nulo
            if ($iec_indicadores_data !== null) {
                //Comprobamos valores con porcentaje
                if($iec_atributos['divisa'] === 'ipc' || $iec_atributos['divisa'] === 'imacec' || $iec_atributos['divisa'] === 'tpm'){
                    $iec_converted_value = $iec_indicadores_data[$iec_atributos['divisa']]->valor . '%';
                }else{
                    $value_temp = $iec_indicadores_data[$iec_atributos['divisa']]->valor;
                    $iec_converted_value = $iec_numberFormat->formatCurrency($value_temp, 'CLP');
                }
                // Construir el elemento del párrafo con clase y ID
                $output = '<p';            
                if (!empty($iec_atributos['class'])) {
                    $output .= ' class="' . esc_attr($iec_atributos['class']) . '"';
                }
                if (!empty($iec_atributos['id'])) {
                    $output .= ' id="' . esc_attr($iec_atributos['id']) . '"';
                }
                $output .= '>';
                if($iec_atributos['nombre']){
                    $output .= '<span><b>'.$iec_indicadores_data[$iec_atributos['divisa']]->nombre.': '.'</b>'. $iec_converted_value .'</span>';
                }else{
                    $output .= $iec_converted_value;
                }
                $output .= '</p>';
                

                // Devolver el valor del divisa solicitado dentro del elemento con clase e ID
                return $output;
            } else {
                return "No se pudo obtener datos de la API.";
            }
        } else {
            return "divisa no válido o no encontrado.";
        }
    }else{
        return "Para poder usar el shortcode verifica que la extensión intl este activada.";
    }
}

// Función para añadir una página en Herramientas
function iec_agregar_pagina_herramientas() {
    add_submenu_page(
        'tools.php',             // Slug de la página padre (Herramientas)
        'Indicadores Económicos Chile',     // Título de la página
        'Indicadores Económicos Chile',     // Nombre en el menú
        'manage_options',        // Capacidad requerida para acceder
        'indicadores-económicos-chile-settings',   // Slug de la página
        'iec_indicadores_pagina'      // Función que mostrará la página
    );
}

// Función que mostrará el contenido de la página
function iec_indicadores_pagina() {
    if (!extension_loaded('intl')) {
        echo '<h2>**Para poder usar el shortcode verifica que la extensión intl este activada.**</h2>';
    } 
    echo '<div class="wrap">';
    echo '<h1>Indicadores Económicos Chile</h1>';
    echo '<p>Este plugin te permite obtener fácilmente mediante shortcode los indicadores económicos más utilizados en Chile.</p>';
    echo '<h2>Instrucciones de uso del shortcode <b></b>[indicadores]</b></h2>';
    echo '<p>El shortcode [indicadores] acepta los siguientes parámetros:</p>';
    echo '<ul>';
    echo '<li><strong>divisa</strong>: Parámetro para indicar la divisa a mostrar. Los valores aceptados son: uf, ivp, dolar, euro, ipc, imacec, tpm, dolar intercambio.</li>';
    echo '<li><strong>nombre</strong>: Opcional. Si es true, mostrará el nombre de la divisa junto con su valor. Se agrega una etiqueta span que contiene el nombre de la divisa.</li>';
    echo '<li><strong>class</strong>: Opcional. Define una clase CSS para el elemento generado por el shortcode.</li>';
    echo '<li><strong>id</strong>: Opcional. Define un identificador único para el elemento generado por el shortcode.</li>';
    echo '</ul>';
    echo '<h2>¡Apoya mi trabajo!</h2>';
    echo '<p>Puedes apoyarme comprándome un café en <a href="https://ko-fi.com/mushroom47" target="_blank" rel="noopener noreferrer">Kofi</a>.</p>';
    echo '<p><a href="https://hectorvaldes.dev/" target="_blank" rel="noopener noreferrer">Developed by Mushroom Dev 🍄</a></p>';
    
    // Disclaimer y versión del plugin
    echo '<p>Los datos son obtenidos diariamente de la API REST <a href="https://mindicador.cl/" target="_blank" rel="noopener noreferrer">mindicador.cl</a>.</p>';
    echo '<p>Versión del plugin: 1.0.0</p>';
    
    echo '</div>';
}

// Registrar el shortcode
add_shortcode('indicadores', 'iec_mostrar_indicador');

// Acción para añadir la página al menú de Herramientas
add_action('admin_menu', 'iec_agregar_pagina_herramientas');
