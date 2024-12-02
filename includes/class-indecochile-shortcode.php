<?php
if (!defined('ABSPATH')) exit;

function indecochile_mostrar_indicador($indecochile_atributos) {
    // Llamar al método estático para obtener los datos de la API
    IndecoChile_API::obtener_datos_mindicador_api();
    
    global $indecochile_indicadores_data;

    // Configurar atributos con valores por defecto
    $indecochile_atributos = shortcode_atts(array(
        'divisa' => '',
        'nombre' => false,
        'class' => '',
        'id' => '',
        'separacion' => ' ', // Caracter de separación por defecto
    ), $indecochile_atributos);

    // Procesar las divisas en una lista
    $divisas = array_map('trim', explode(',', $indecochile_atributos['divisa']));

    // Definir el separador
    $separacion = trim($indecochile_atributos['separacion']);
    if ($separacion === '') {
        $separacion = ' '; // Separador por defecto: espacio
    } elseif ($separacion === 'br') {
        $separacion = '<br>'; // Separador como salto de línea
    } else {
        $separacion = ' ' . esc_html($separacion) . ' '; // Separador personalizado con espacio antes y después
    }

    $valores = array();

    foreach ($divisas as $divisa) {
        if (isset($indecochile_indicadores_data[$divisa])) {
            $indicador = $indecochile_indicadores_data[$divisa];
            $nombre = $indecochile_atributos['nombre'] 
                ? "<b>" . (isset($indicador->nombre) ? $indicador->nombre : ucfirst($divisa)) . "</b>: " 
                : '';

            // Formatear el valor dependiendo del tipo de divisa
            if (in_array($divisa, ['ipc', 'imacec', 'tpm'])) {
                $valor_formateado = $indicador->valor . '%';
            } elseif ($divisa === 'bitcoin') {
                $valor_formateado = formatearPeso($indicador->valor, 'USD$', 2);
            } else {
                $valor_formateado = formatearPeso($indicador->valor);
            }

            $valores[] = $nombre . $valor_formateado;
        }
    }

    // Unir los valores con el carácter de separación
    if (empty($valores)) {
        return '<p>No se encontraron divisas válidas.</p>';
    }

    $resultado = implode($separacion, $valores);

    // Generar el HTML de salida
    $output = '<p';
    if (!empty($indecochile_atributos['class'])) {
        $output .= ' class="' . esc_attr($indecochile_atributos['class']) . '"';
    }
    if (!empty($indecochile_atributos['id'])) {
        $output .= ' id="' . esc_attr($indecochile_atributos['id']) . '"';
    }
    $output .= '>' . $resultado . '</p>';

    return $output;
}

function formatearPeso($valor, $moneda = 'CLP$', $decimales = 0) {
    $valorFormateado = number_format($valor, $decimales, ',', '.');
    return $moneda . ' ' . $valorFormateado;
}
?>
