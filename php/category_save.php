<?php

require_once 'main.php';

// * Limpieza de todos los strings antes de ser pasados como valores a guardar...
$name = clean_String($_POST['categoria_nombre']);
$location = clean_String(($_POST['categoria_ubicacion']));

// * Comprobación de que el input "Nombre" NO este vacío...
if ($name == '') {
        echo '
        <div class="notification is-danger is-light">
                <strong>Ocurrió un error inesperado!</strong><br>
                No has llenado los campos que son obligatorios...
        </div>
        ';
        exit();
}

// ~ =======================================================================
// * Validación de todos los datos a almacenar...
if (verify_Data('[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}', $name)) {
        echo '
        <div class="notification is-danger is-light">
                <strong>Ocurrió un error inesperado!</strong><br>
                El <b>Nombre NO coincide</b> con el formato solicitado...
        </div>
        ';
        exit();
}

if ($location !== '') {
        if (verify_Data('[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}', $location)) {
                echo '
        <div class="notification is-danger is-light">
                <strong>Ocurrió un error inesperado!</strong><br>
                La <b>Ubicación NO coincide</b> con el formato solicitado...
        </div>
        ';
                exit();
        }
}
// ~ =======================================================================

try {
        $db = connect();

        $sql = 'INSERT INTO 
                                categoria (categoria_nombre, categoria_ubicacion) 
                                VALUES (:name, :location)';

        $stmt = $db->prepare($sql);

        $ok = $stmt->execute([
                ':name' => $name,
                ':location' => $location
        ]);

        if ($ok) {
                echo '
                        <div class="notification is-success is-light">
                                <strong>Categoría guardada!</strong><br>
                                El registro se almacenó correctamente.
                        </div>
                ';
        } else {
                echo '
                        <div class="notification is-danger is-light">
                                <strong>Error!</strong><br>
                                No se pudo guardar la Categoría.
                        </div>
                ';
        }
} catch (Exception $e) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Error!</strong><br>
                        Ocurrió un problema al guardar el usuario.
                </div>
        ';

        echo '<pre>' . $e->getMessage() . '</pre>';
}

?>