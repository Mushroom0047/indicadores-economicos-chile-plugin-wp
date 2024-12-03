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
    $icon_url = plugin_dir_url( __FILE__ ) . '../assets/icon-128x128.png';


    echo <<<HTML
<div class="wrap">
    <img src="{$icon_url}" alt="Icono del plugin" width="32" height="32"/>
    <h1>Indicadores Económicos Chile</h1>
    <p>Este plugin te permite obtener fácilmente mediante shortcode los indicadores económicos más utilizados en Chile.</p>
    <h2>Instrucciones de uso del shortcode [indicadores]</h2>
    <p>El shortcode <b>[indicadores]</b> acepta los siguientes parámetros:</p>
    <ul style="list-style: inside;">
        <li><strong>divisa</strong>: Parámetro para indicar la divisa a mostrar. Puedes agregar las divisas que necesites separadas por comas ( , ). Los valores aceptados son:</li>
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
        <li><strong>separador</strong>: Opcional. Este parametro permite agregar un caracter separador entre cada divisa (ejemplo: | / -). Si usamos <code>br</code> se genera un salto de línea entre cada divisa.</li>
        <li><strong>class</strong>: Opcional. Define una clase CSS para el elemento generado por el shortcode.</li>
        <li><strong>id</strong>: Opcional. Define un identificador único para el elemento generado por el shortcode.</li>
    </ul>
    <h3>Ejemplo de uso del shortcode:</h3>
    <p>Shortcode divisa normal</p>
    <code>[indicadores divisa="dolar" nombre="true" class="mi-clase" id="mi-id"]</code>
    <br>
    <p>Shortcode con multiples divisas</p>
    <code>[indicadores divisa="dolar,euro,bitcoin" separacion="|" nombre="true"]</code>
    <br>
    <br>
    <hr/>
    <p>Datos obtenidos de <a href="https://mindicador.cl/" target="_blank">mindicador.cl.</a></p>
    <p>Si tienes sugerencias o encuentras algún problema, por favor contáctame a través de mi sitio web <a href="https://hectorvaldes.dev/" target="blank">hectorvaldes.dev.</a></p>
    <p>Este plugin es gratuito, si deseas apoyarme a que siga funcionando puedes donarme en <a href="https://ko-fi.com/G2G33MGN8" target="_blank">Kofi.</a></p>
</div>
HTML;
}
?>