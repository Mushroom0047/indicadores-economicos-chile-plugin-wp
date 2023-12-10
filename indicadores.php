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
                'uf' => $data->uf,
                'ivp' => $data->ivp,
                'dolar' => $data->dolar,
                'dolar intercambio' => $data->dolar_intercambio,
                'euro' => $data->euro,
                'ipc' => $data->ipc,
                'imacec' => $data->imacec,
                'tpm' => $data->tpm
            );

            return "Datos de la API almacenados correctamente.";
        } else {
            return "No se pudieron obtener datos válidos de la API.";
        }
    }
}

//! Función para el shortcode que mostrará los indicadores según el parámetro recibido
function mostrar_indicador($atts) {
    if(extension_loaded('intl')){
        // Actualizar los datos de la API antes de mostrar el divisa solicitado
        obtener_datos_mindicador_api();
        
        global $indicadores_data;
        $fmt = numfmt_create('es_CL', NumberFormatter::CURRENCY);
        $value_temp;

        // Obtener el parámetro del shortcode
        $atts = shortcode_atts(array(
            'divisa' => '',
            'nombre' => false,
            'class' => '',
            'id' => '',
        ), $atts);

        // Verificar si se proporcionó un divisa válido y existe en los datos almacenados
        if (!empty($atts['divisa']) && isset($indicadores_data[$atts['divisa']])) {
            // Verificar si los datos de la API están disponibles y el valor no es nulo
            if ($indicadores_data !== null) {
                //Comprobamos valores con porcentaje
                if($atts['divisa'] === 'ipc' || $atts['divisa'] === 'imacec' || $atts['divisa'] === 'tpm'){
                    $converted_value = $indicadores_data[$atts['divisa']]->valor . '%';
                }else{
                    $value_temp = $indicadores_data[$atts['divisa']]->valor;
                    $converted_value = numfmt_format_currency($fmt, $value_temp, 'CLP');
                }
                // Construir el elemento del párrafo con clase y ID
                $output = '<p';            
                if (!empty($atts['class'])) {
                    $output .= ' class="' . esc_attr($atts['class']) . '"';
                }
                if (!empty($atts['id'])) {
                    $output .= ' id="' . esc_attr($atts['id']) . '"';
                }
                $output .= '>';
                if($atts['nombre']){
                    $output .= '<span><b>'.$indicadores_data[$atts['divisa']]->nombre.': '.'</b>'. $converted_value .'</span>';
                }else{
                    $output .= $converted_value;
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
    if (!extension_loaded('intl')) {
        echo '<h2>**Para poder usar el shortcode verifica que la extensión intl este activada.**</h2>';
    } 
    echo '<div class="wrap">';
    echo '<h1>Indicadores Económicos Chile</h1>';
    echo '<p>Este plugin te permite obtener fácilmente los indicadores económicos más utilizados en Chile.</p>';
    echo '<h2>Instrucciones de uso del shortcode [indicadores]</h2>';
    echo '<p>El shortcode [indicadores] acepta los siguientes parámetros:</p>';
    echo '<ul>';
    echo '<li><strong>divisa</strong>: Parámetro para indicar la divisa a mostrar. Los valores aceptados son: uf, ivp, dolar, euro, ipc, imacec, tpm, dolar intercambio.</li>';
    echo '<li><strong>nombre</strong>: Opcional. Si es true, mostrará el nombre de la divisa junto con su valor. Se agrega una etiqueta span que contiene el nombre de la divisa.</li>';
    echo '<li><strong>class</strong>: Opcional. Define una clase CSS para el elemento generado por el shortcode.</li>';
    echo '<li><strong>id</strong>: Opcional. Define un identificador único para el elemento generado por el shortcode.</li>';
    echo '</ul>';
    echo '<h2>¡Apoya mi trabajo!</h2>';
    echo '<p>Puedes apoyarme comprándome un café en <a href="https://ko-fi.com/mushroom47" target="_blank" rel="noopener noreferrer">Kofi</a>.</p>';
    echo '<p><a href="https://hectorvaldesm.com/" target="_blank" rel="noopener noreferrer">Developed by 🍄</a></p>';
    echo '</div>';
}


// Acción para añadir la página al menú de Herramientas
add_action('admin_menu', 'agregar_pagina_herramientas');
