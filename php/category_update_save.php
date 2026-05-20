<?php

require_once '../inc/session_start.php';
require_once 'main.php';

/*
 * categoria_id (POST): categoría que se edita (hidden en el formulario).
 * $_SESSION['id']: usuario logueado que confirma con usuario/clave...
 */
$categoria_id = isset($_POST['categoria_id']) ? (int) $_POST['categoria_id'] : 0;
$session_user_id = isset($_SESSION['id']) ? (int) $_SESSION['id'] : 0;

if ($session_user_id <= 0) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        No hay una sesión válida. Inicie sesión nuevamente.
                </div>
        ';
        exit();
}

if ($categoria_id <= 0) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        El identificador de la categoría no es válido.
                </div>
        ';
        exit();
}

$db = connect();

// * Usuario de la sesión existe...
$check_actor = $db->prepare('SELECT usuario_id FROM usuario WHERE usuario_id = ?');
$check_actor->execute([$session_user_id]);

if ($check_actor->rowCount() <= 0) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        El usuario de la sesión no existe en el sistema.
                </div>
        ';
        $db = null;
        exit();
}

// * Categoría a actualizar existe...
$check_category = $db->prepare('SELECT * FROM categoria WHERE categoria_id = ?');
$check_category->execute([$categoria_id]);

if ($check_category->rowCount() <= 0) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        La categoría NO existe en el sistema.
                </div>
        ';
        $db = null;
        exit();
}

// * Credenciales del administrador (mismo patrón que user_update_save.php)...
$administrator_user = clean_String($_POST['administrator_user'] ?? '');
$administrator_key = clean_String($_POST['administrator_key'] ?? '');

if ($administrator_user === '' || $administrator_key === '') {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        Debe ingresar su USUARIO y CLAVE de administrador.
                </div>
        ';
        $db = null;
        exit();
}

if (verify_Data('[a-zA-Z0-9]{4,20}', $administrator_user)) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong>
                        Su <b>USUARIO</b> no coincide con el formato solicitado.
                </div>
        ';
        $db = null;
        exit();
}

if (verify_Data('[a-zA-Z0-9$@.-]{7,100}', $administrator_key)) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong>
                        Su <b>CLAVE</b> no coincide con el formato solicitado.
                </div>
        ';
        $db = null;
        exit();
}

$sql_admin = 'SELECT usuario_usuario, usuario_clave
                FROM usuario
                WHERE usuario_id = :id AND usuario_usuario = :user
                LIMIT 1';
$check_admin = $db->prepare($sql_admin);
$check_admin->execute([
        ':id' => $session_user_id,
        ':user' => $administrator_user,
]);

if ($check_admin->rowCount() !== 1) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        Su <b>USUARIO</b> o <b>CLAVE</b> de administrador incorrectos.
                </div>
        ';
        $db = null;
        exit();
}

$admin_row = $check_admin->fetch(PDO::FETCH_ASSOC);

if (!password_verify($administrator_key, $admin_row['usuario_clave'])) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        Su <b>USUARIO</b> o <b>CLAVE</b> de administrador incorrectos.
                </div>
        ';
        $db = null;
        exit();
}

// * Datos del formulario (mismas reglas que category_save.php)...
$name = clean_String($_POST['categoria_nombre'] ?? '');
$location = clean_String($_POST['categoria_ubicacion'] ?? '');

if ($name === '') {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        No ha llenado el campo obligatorio <b>Nombre</b>.
                </div>
        ';
        $db = null;
        exit();
}

if (verify_Data('[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}', $name)) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        El <b>Nombre NO coincide</b> con el formato solicitado.
                </div>
        ';
        $db = null;
        exit();
}

if ($location !== '') {
        if (verify_Data('[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}', $location)) {
                echo '
                        <div class="notification is-danger is-light">
                                <strong>Ocurrió un error inesperado!</strong><br>
                                La <b>Ubicación NO coincide</b> con el formato solicitado.
                        </div>
                ';
                $db = null;
                exit();
        }
}

// * Nombre único (otra categoría no puede tener el mismo nombre)...
$stmt_name = $db->prepare(
        'SELECT categoria_id FROM categoria WHERE categoria_nombre = :name AND categoria_id != :id'
);
$stmt_name->execute([
        ':name' => $name,
        ':id' => $categoria_id,
]);

if ($stmt_name->rowCount() > 0) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        Ya existe otra categoría con ese <b>Nombre</b>.
                </div>
        ';
        $db = null;
        exit();
}

try {
        $sql_update = 'UPDATE categoria SET
                        categoria_nombre = :nombre,
                        categoria_ubicacion = :ubicacion
                        WHERE categoria_id = :id';

        $stmt_update = $db->prepare($sql_update);
        $ok = $stmt_update->execute([
                ':nombre' => $name,
                ':ubicacion' => $location,
                ':id' => $categoria_id,
        ]);

        if ($ok) {
                echo '
                <div class="notification is-success is-light">
                        <strong>Categoría actualizada!</strong><br>
                        Los datos se guardaron correctamente.
                </div>
                ';
        } else {
                echo '
                <div class="notification is-danger is-light">
                        <strong>Error!</strong><br>
                        No se pudo actualizar la categoría.
                </div>
                ';
        }
} catch (Exception $e) {
        echo '
        <div class="notification is-danger is-light">
                <strong>Error!</strong><br>
                Ocurrió un problema al actualizar la categoría.
        </div>
        ';
        echo '<pre>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
}

$db = null;

?>