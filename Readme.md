=== Indicadores Económicos Chile ===
Contributors:
Donate link: hhttps://ko-fi.com/mushroom47
Tags: indicadores, Chile, economía, uf, dolar, ipc
Requires at least: 4.0
Tested up to: 5.9
Stable tag: 1.0.0
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Muestra mediante un shortcode los Indicadores económicos actualizados en Chile.

== Description ==

Este plugin permite mostrar en tu sitio web de WordPress los indicadores económicos actualizados de Chile a través de un shortcode fácil de usar. Podrás insertar estos indicadores en cualquier página o entrada con solo utilizar el shortcode correspondiente.

Los indicadores que se pueden mostrar incluyen: uf, ivp, dolar, euro, ipc, imacec, tpm, dolar intercambio.

== Installation ==

1. Sube la carpeta 'indicadores-economicos-chile' al directorio `/wp-content/plugins/` o instálalo directamente desde el panel de administración de WordPress en "Plugins".
2. Activa el plugin a través del menú 'Plugins' en WordPress.
3. Utiliza el shortcode correspondiente en tus páginas o entradas para mostrar los indicadores económicos.

== Usage ==

Para mostrar los indicadores económicos, utiliza el siguiente shortcode en tus páginas o entradas:
[indicadores]


== Frequently Asked Questions ==

= ¿Puedo personalizar la apariencia de los indicadores? =
El shortcode acepta los parámetros `class` y `id` para personalizar los estilos de los indicadores en tu página. Por ejemplo, puedes asignar una clase CSS existente o un ID para aplicar estilos específicos a través de tu tema de WordPress.


= ¿Cómo puedo mostrar un indicador específico? =
Puedes utilizar parámetros en el shortcode para mostrar un indicador específico. Por ejemplo:
[indicadores_chile divisa="tipo_de_cambio"]

Las divisas aceptadas son:
uf, ivp, dolar, euro, ipc, imacec, tpm, dolar intercambio.

== Changelog ==

= 1.0.0 =
* Versión inicial del plugin.

== Upgrade Notice ==

= 1.0.0 =
¡Nueva versión del plugin "Indicadores Económicos Chile"! ¡Disfruta de los indicadores económicos actualizados en tu sitio web!

== Screenshots ==

![Página de detalles del plugin, ubicada en Herramientas>Indicadores](screenshots/screenshot_plugin_ind_eco_chile(1).png)
![Ejemplo de Shortcode usando Gutenberg](screenshots/screenshot_plugin_ind_eco_chile(2).png)
![Vista de cómo quedaría en el sitio los indicadores](screenshots/screenshot_plugin_ind_eco_chile(3).png)


== Other Notes ==
 
¡Gracias por usar el plugin "Indicadores Económicos Chile"! Si tienes sugerencias o encuentras algún problema, por favor contáctanos a través de nuestro sitio web.

== License ==

Este plugin está licenciado bajo GPL-2.0+.

## Política de Privacidad

Este plugin utiliza la API de [Mindicador.cl](https://www.mindicador.cl/api) para obtener datos económicos de Chile. Te recomendamos revisar la política de privacidad de Mindicador.cl para comprender cómo se manejan los datos.

**Enlace a la Política de Privacidad:** [Política de Privacidad](https://indicadores-economicos-chile.netlify.app/#/privacy-policy)

Asegúrate de revisar y cumplir con las políticas de privacidad tanto de tu propio servicio como del servicio de terceros que estás utilizando en tu plugin. Esto ayuda a proporcionar a los usuarios la información necesaria sobre cómo se manejan los datos y contribuye a la transparencia en el uso del plugin.
