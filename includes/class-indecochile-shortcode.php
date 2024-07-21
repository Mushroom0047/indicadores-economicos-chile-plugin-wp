<?php
if (!defined('ABSPATH')) exit;

function indecochile_mostrar_indicador($indecochile_atributos) {
    if (extension_loaded('intl')) {
        indecochile_obtener_datos_mindicador_api();
        
        global $indecochile_indicadores_data;
        $indecochile_numberFormat = NumberFormatter::create('es_CL', NumberFormatter::CURRENCY);
        $indecochile_numberFormatUSD = NumberFormatter::create('en_US', NumberFormatter::CURRENCY);
        $value_temp;

        $indecochile_atributos = shortcode_atts(array(
            'divisa' => '',
            'nombre' => false,
            'class' => '',
            'id' => '',
        ), $indecochile_atributos);

        if (!empty($indecochile_atributos['divisa']) && isset($indecochile_indicadores_data[$indecochile_atributos['divisa']])) {
            if ($indecochile_indicadores_data !== null) {
                if ($indecochile_atributos['divisa'] === 'ipc' || $indecochile_atributos['divisa'] === 'imacec' || $indecochile_atributos['divisa'] === 'tpm') {
                    $indecochile_converted_value = $indecochile_indicadores_data[$indecochile_atributos['divisa']]->valor . '%';
                } else if ($indecochile_atributos['divisa'] === 'bitcoin') {
                    $value_temp = $indecochile_indicadores_data[$indecochile_atributos['divisa']]->valor;
                    $indecochile_converted_value = $indecochile_numberFormatUSD->formatCurrency($value_temp, 'USD');
                } else {
                    $value_temp = $indecochile_indicadores_data[$indecochile_atributos['divisa']]->valor;
                    $indecochile_converted_value = $indecochile_numberFormat->formatCurrency($value_temp, 'CLP');
                }

                $output = '<p';            
                if (!empty($indecochile_atributos['class'])) {
                    $output .= ' class="' . esc_attr($indecochile_atributos['class']) . '"';
                }
                if (!empty($indecochile_atributos['id'])) {
                    $output .= ' id="' . esc_attr($indecochile_atributos['id']) . '"';
                }
                $output .= '>';
                if ($indecochile_atributos['nombre']) {
                    $output .= '<span><b>'.$indecochile_indicadores_data[$indecochile_atributos['divisa']]->nombre.': '.'</b>'. $indecochile_converted_value .'</span>';
                } else {
                    $output .= $indecochile_converted_value;
                }
                $output .= '</p>';

                return $output;
            } else {
                return "No se pudo obtener datos de la API.";
            }
        } else {
            return "divisa no válida o no encontrada.";
        }
    } else {
        return "Para poder usar el shortcode verifica que la extensión intl de PHP este activada.";
    }
}
?>
