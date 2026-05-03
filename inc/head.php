<?php

/**
 * * Para evitar problemas de caché: agregue una cadena de consulta de versión basada en la
 * * hora de la última modificación del archivo.
 * * Al guardar el archivo CSS, la marca de tiempo cambia y los navegadores descargan el
 * * nuevo archivo.
 */
$bulmaVersion = @filemtime(__DIR__ . '/../css/bulma.min.css') ?: time();
$estilosVersion = @filemtime(__DIR__ . '/../css/estilos.css') ?: time();

?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Inventario</title>
<link rel="stylesheet" href="./css/bulma.min.css">
<link rel="stylesheet" href="./css/estilos.css">