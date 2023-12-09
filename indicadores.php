<?php
/*
Plugin Name: Indicadores Económicos Chile
Description: Plugin Wordpress con indicadores económicos actualizados en Chile.
Version: 1.1
*/
global $indicadores_data;

//! Función para obtener los datos de la API de mindicador.cl
function obtener_datos_mindicador_api() {
    $api_url = 'https://www.mindicador.cl/api';

    // Realizar la solicitud GET a la API
    $response = wp_remote_get($api_url);

    // Verificar si la solicitud fue exitosa
    if (is_wp_error($response)) {
        // En caso de error al hacer la solicitud
        return "Error al obtener datos de la API: " . $response->get_error_message();
    } else {
        // Si la solicitud fue exitosa, obtener y decodificar los datos JSON
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);

         // Verificar si se obtuvieron datos válidos
         if ($data) {
            global $indicadores_data;
            // Almacenar los valores en las variables globales
            $indicadores_data = array(
                'uf' => $data->uf->valor,
                'ivp' => $data->ivp->valor,
                'dolar' => $data->dolar->valor,
                'dolar intercambio' => $data->dolar_intercambio->valor,
                'euro' => $data->euro->valor,
                'ipc' => $data->ipc->valor,
                'imacec' => $data->imacec->valor,
                'tpm' => $data->tpm->valor
            );

            return "Datos de la API almacenados correctamente.";
        } else {
            return "No se pudieron obtener datos válidos de la API.";
        }
    }
}

//! Función para el shortcode que mostrará los indicadores según el parámetro recibido
function mostrar_indicador($atts) {
    // Actualizar los datos de la API antes de mostrar el indicador solicitado
    obtener_datos_mindicador_api();
    
    global $indicadores_data;

    // Obtener el parámetro del shortcode
    $atts = shortcode_atts(array(
        'indicador' => '', // Parámetro para indicar el indicador a mostrar
    ), $atts);

    // Verificar si se proporcionó un indicador válido y existe en los datos almacenados
    if (!empty($atts['indicador']) && isset($indicadores_data[$atts['indicador']])) {
        $converter_value = number_format($indicadores_data[$atts['indicador']], 0, ',', '.')
        // Devolver el valor del indicador solicitado
        return $converter_value;
    } else {
        return "Indicador no válido o no encontrado.";
    }
}

// Registrar el shortcode
add_shortcode('indicadores', 'mostrar_indicador');

// Función para añadir una página en Herramientas
function agregar_pagina_herramientas() {
    add_submenu_page(
        'tools.php',             // Slug de la página padre (Herramientas)
        'Indicadores Económicos Chile',     // Título de la página
        'Indicadores Económicos Chile',     // Nombre en el menú
        'manage_options',        // Capacidad requerida para acceder
        'indicadores-económicos-chile-settings',   // Slug de la página
        'indicadores_pagina'      // Función que mostrará la página
    );
}

// Función que mostrará el contenido de la página
function indicadores_pagina() {
    echo '<div class="wrap">';
    echo '<h1>Hola Mundo Plugin</h1>';
    echo '<p>Esta es la página de configuración del plugin "Hola Mundo".</p>';
    echo '</div>';
}

// Acción para añadir la página al menú de Herramientas
add_action('admin_menu', 'agregar_pagina_herramientas');