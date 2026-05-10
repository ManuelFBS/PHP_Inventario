<?php

// ~ NOTA: Este archivo asume que `clean_String()` y `verify_Data()` ya existen...
// ~ Y que la sesión ya se ha iniciado en el arranque de tu aplicación...

// $search_module = clean_String($_POST['search_module']);
$search_module = clean_String($_POST['search_module'] ?? '');

// * Módulos permitidos para buscar...
$modules = ['user', 'category', 'product'];

if (!in_array($search_module, $modules, true)) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        NO se puede procesar la petición...
                </div>
        ';
        exit();
}

// * Módulo de mapa => nombre de vista existente
// * (IMPORTANTE: debe coincidir con los nombres de su enrutador/vista)...
$module_url_map = [
        'user' => 'user_search',
        'category' => 'category_search',
        'product' => 'product_search',
];

$modules_url = $module_url_map[$search_module];

// * Construye el nombre de la clave de sesión (por ejemplo, search_user,
// * search_category, search_product)...
$session_key = 'search_' . $search_module;

// * INICIA BUSQUEDA...
if (isset($_POST['txt_search'])) {
        $txt = clean_String($_POST['txt_search']);

        // > Validar entrada vacía...
        if ($txt === '') {
                echo '
                        <div class="notification is-danger is-light">
                                <strong>Ocurrió un error inesperado!</strong><br>
                                Introduce un término de búsqueda...
                        </div>
                ';
                exit();
        }

        // > Validar el formato permitido...
        if (verify_Data('[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}', $txt)) {
                echo '
                        <div class="notification is-danger is-light">
                                <strong>Ocurrió un error inesperado!</strong><br>
                                El término de búsqueda NO coincide con el formato solicitado...
                        </div>
                ';
                exit();
        }

        // > Almacenar el término de búsqueda en la sesión...
        $_SESSION[$session_key] = $txt;

        // > Redirige de vuelta a la vista correcta para que pueda mostrar los resultados...
        header("Location: index.php?view={$modules_url}", true, 303);
        exit();
}

// * ELIMINAR BUSQUEDA...
if (isset($_POST['delete_search'])) {
        unset($_SESSION[$session_key]);

        // > Redirige de vuelta a la misma vista (mostrará un formulario de búsqueda vacío)...
        header("Location: index.php?view={$modules_url}", true, 303);
        exit();
}

?>