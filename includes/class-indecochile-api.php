<?php

if (!defined('ABSPATH')) exit; // Salir si se accede directamente

class IndecoChile_API {
    public static function obtener_datos_mindicador_api() {
        // Nombre del transient
        $transient_name = 'indecochile_datos_economicos';

        // Intentar obtener datos del caché
        $datos_cacheados = get_transient($transient_name);

        if ($datos_cacheados !== false) {
            // Si los datos están en caché, usarlos
            global $indecochile_indicadores_data;
            $indecochile_indicadores_data = $datos_cacheados;
            return "Datos obtenidos del caché.";
        }

        // URL de la API
        $indecochile_api_url = 'https://www.mindicador.cl/api';
        $indecochile_response = wp_remote_get($indecochile_api_url);

        if (is_wp_error($indecochile_response)) {
            error_log("Error al obtener datos de la API: " . $indecochile_response->get_error_message());
            return "Error al obtener datos de la API. Por favor, inténtalo de nuevo más tarde.";
        } else {
            $indecochile_body = wp_remote_retrieve_body($indecochile_response);
            $indecochile_data = json_decode($indecochile_body);

            if ($indecochile_data) {
                // Almacenar los datos en un array global
                global $indecochile_indicadores_data;
                $indecochile_indicadores_data = array(
                    'uf' => $indecochile_data->uf,
                    'ivp' => $indecochile_data->ivp,
                    'dolar' => $indecochile_data->dolar,
                    'dolar intercambio' => $indecochile_data->dolar_intercambio,
                    'euro' => $indecochile_data->euro,
                    'ipc' => $indecochile_data->ipc,
                    'utm' => $indecochile_data->utm,
                    'imacec' => $indecochile_data->imacec,
                    'tpm' => $indecochile_data->tpm,
                    'bitcoin' => $indecochile_data->bitcoin,
                );

                // Guardar los datos en el caché por 24 horas (86400 segundos)
                set_transient($transient_name, $indecochile_indicadores_data, 86400);

                return "Datos de la API almacenados correctamente.";
            } else {
                return "No se pudieron obtener datos válidos de la API.";
            }
        }
    }
}
?>
