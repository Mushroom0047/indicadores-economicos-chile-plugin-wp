<?php
if (!defined('ABSPATH')) exit;

function indecochile_agregar_pagina_herramientas() {
    add_submenu_page(
        'tools.php',
        'Indicadores Económicos Chile',
        'Indicadores Económicos Chile',
        'manage_options',
        'indicadores-economicos-chile-settings',
        'indecochile_indicadores_pagina'
    );
}

function indecochile_indicadores_pagina() {
    if (!extension_loaded('intl')) {
        echo '<h2>**Para poder usar el shortcode verifica que la extensión intl este activada.**</h2>';
    }
    echo <<<HTML
<div class="wrap">
    <h1>Indicadores Económicos Chile</h1>
    <p>Este plugin te permite obtener fácilmente mediante shortcode los indicadores económicos más utilizados en Chile.</p>
    <h2>Instrucciones de uso del shortcode <b>[indicadores]</b></h2>
    <p>El shortcode <b>[indicadores]</b> acepta los siguientes parámetros:</p>
    <ul style="list-style: inside;">
        <li><strong>divisa</strong>: Parámetro para indicar la divisa a mostrar. Los valores aceptados son:</li>
        <ul style="list-style: square; padding-left: 40px;">
            <li>uf</li>
            <li>dolar</li>
            <li>dolar intercambio</li>
            <li>euro</li>
            <li>ipc</li>
            <li>utm</li>
            <li>ivp</li>
            <li>imacec</li>
            <li>tpm</li>
            <li>bitcoin</li>
        </ul>
        <li><strong>nombre</strong>: Opcional. debe ser igual a "true" para mostrar el nombre de la divisa junto con su valor. Se agrega una etiqueta span que contiene el nombre de la divisa.</li>
        <li><strong>class</strong>: Opcional. Define una clase CSS para el elemento generado por el shortcode.</li>
        <li><strong>id</strong>: Opcional. Define un identificador único para el elemento generado por el shortcode.</li>
    </ul>
    <h3>Ejemplo de uso del shortcode:</h3>
    <code>[indicadores divisa="dolar" nombre="true" class="mi-clase" id="mi-id"]</code>
</div>
HTML;
}
?>
