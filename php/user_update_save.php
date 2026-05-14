<?php

require_once '../inc/session_start.php';
require_once 'main.php';

// $id = clean_String($_POST['usuario_id']);

/*
 * usuario_id (POST): fila del usuario que se va a actualizar (elegido en la lista).
 * $_SESSION['id']: usuario que inició sesión y confirma con usuario/clave (administrador)...
 */
$usuario_id = isset($_POST['usuario_id']) ? (int) $_POST['usuario_id'] : 0;
$session_user_id = isset($_SESSION['id']) ? (int) $_SESSION['id'] : 0;

if ($session_user_id <= 0) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        No hay una sesión válida. Inicie sesión nuevamente...!
                </div>
        ';
        exit();
}

if ($usuario_id <= 0) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        El identificador del usuario a actualizar no es válido...!
                </div>
        ';
        exit();
}

// * Verificar que el usuario en sesión existe en la base de datos...
$db = connect();
$check_actor = $db->prepare('SELECT usuario_id FROM usuario WHERE usuario_id = ?');
$check_actor->execute([$session_user_id]);

if ($check_actor->rowCount() <= 0) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        El usuario de la sesión no existe en el sistema...!
                </div>
        ';
        $db = null;
        exit();
}

// * Verificar que el usuario a actualizar (registro seleccionado) existe...
$check_user = $db->prepare('SELECT * FROM usuario WHERE usuario_id = ?');
$check_user->execute([$usuario_id]);

if ($check_user->rowCount() <= 0) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        El usuario NO existe en el sistema...!
                </div>
        ';
        $db = null;
        exit();
}

$data = $check_user->fetch(PDO::FETCH_ASSOC);

$administrator_user = clean_String($_POST['administrator_user'] ?? '');
$administrator_key = clean_String($_POST['administrator_key'] ?? '');

if ($administrator_user == '' || $administrator_key == '') {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        No ha llenado los campos que son obligatorios, que<br>
                        corresponden a su USUARIO y CLAVE...!
                </div>
        ';
        $db = null;
        exit();
}
// * Verificando integridad de los datos del administrador...
if (verify_Data('[a-zA-Z0-9]{4,20}', $administrator_user)) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong>
                        Su <b>USUARIO</b> no coincide con el formato solicitado...!
                </div>
        ';
        $db = null;
        exit();
}

if (verify_Data('[a-zA-Z0-9$@.-]{7,100}', $administrator_key)) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong>
                        Su <b>CLAVE</b> no coincide con el formato solicitado...!
                </div>
        ';
        $db = null;
        exit();
}

// * Administrador: mismo usuario_id que la sesión + usuario escrito + clave correcta...
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
                        Su <b>USUARIO</b> o <b>CLAVE</b> de administrador incorrectos...!
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
                        Su <b>USUARIO</b> o <b>CLAVE</b> de administrador incorrectos...!
                </div>
        ';
        $db = null;
        exit();
}

// * Datos del formulario a actualizar...
$name = clean_String($_POST['usuario_nombre'] ?? '');
$lastName = clean_String($_POST['usuario_apellido'] ?? '');
$user = clean_String($_POST['usuario_usuario'] ?? '');
$email = clean_String($_POST['usuario_email'] ?? '');
$passW1 = clean_String($_POST['usuario_clave_1'] ?? '');
$passW2 = clean_String($_POST['usuario_clave_2'] ?? '');

if ($name == '' || $lastName == '' || $user == '') {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        No ha llenado los campos que son obligatorios...!
                </div>
        ';
        $db = null;
        exit();
}

if (verify_Data('[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}', $name)) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        El <b>Nombre NO coincide</b> con el formato solicitado...!
                </div>
        ';
        $db = null;
        exit();
}

if (verify_Data('[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}', $lastName)) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        El <b>Apellido NO coincide</b> con el formato solicitado...!
                </div>
        ';
        $db = null;
        exit();
}

if (verify_Data('[a-zA-Z0-9]{4,20}', $user)) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        El <b>Usuario NO coincide</b> con el formato solicitado...!
                </div>
        ';
        $db = null;
        exit();
}

$update_password = false;
$hashedPassword = '';

if ($passW1 != '' || $passW2 != '') {
        if ($passW1 == '' || $passW2 == '') {
                echo '
                        <div class="notification is-danger is-light">
                                <strong>Ocurrió un error inesperado!</strong><br>
                                Para cambiar la clave debe llenar <b>ambos</b> campos...!
                        </div>
                ';
                $db = null;
                exit();
        }

        if (verify_Data('[a-zA-Z0-9$@.-]{7,100}', $passW1) ||
                        verify_Data('[a-zA-Z0-9$@.-]{7,100}', $passW2)) {
                echo '
                        <div class="notification is-danger is-light">
                                <strong>Ocurrió un error inesperado!</strong><br>
                                Las <b>Claves NO coinciden</b> con el formato solicitado...!
                        </div>
                ';
                $db = null;
                exit();
        }

        if ($passW1 !== $passW2) {
                echo '
                        <div class="notification is-danger is-light">
                                <strong>Ocurrió un error inesperado!</strong><br>
                                Las <b>claves NO coinciden</b>...!
                        </div>
                ';
                $db = null;
                exit();
        }

        $hashedPassword = password_hash($passW1, PASSWORD_BCRYPT, ['cost' => 10]);
        $update_password = true;
}

if ($email != '') {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo '
                        <div class="notification is-danger is-light">
                                <strong>Ocurrió un error inesperado!</strong><br>
                                El <b>EMAIL ingresado</b> NO es válido...!
                        </div>
                ';
                $db = null;
                exit();
        }

        $stmt_email = $db->prepare(
                'SELECT usuario_email FROM usuario WHERE usuario_email = :email AND usuario_id != :id'
        );
        $stmt_email->execute([
                ':email' => $email,
                ':id' => $usuario_id,
        ]);

        if ($stmt_email->rowCount() > 0) {
                echo '
                        <div class="notification is-danger is-light">
                                <strong>Ocurrió un error inesperado!</strong><br>
                                El <b>EMAIL ingresado</b> ya se encuentra registrado...!
                        </div>
                ';
                $db = null;
                exit();
        }
}

$stmt_user = $db->prepare(
        'SELECT usuario_usuario FROM usuario WHERE usuario_usuario = :user AND usuario_id != :id'
);
$stmt_user->execute([
        ':user' => $user,
        ':id' => $usuario_id,
]);

if ($stmt_user->rowCount() > 0) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        El <b>USUARIO ingresado</b> ya se encuentra registrado...!
                </div>
        ';
        $db = null;
        exit();
}

try {
        if ($update_password) {
                $sql_update = 'UPDATE usuario SET
                        usuario_nombre = :nombre,
                        usuario_apellido = :apellido,
                        usuario_usuario = :usuario,
                        usuario_email = :email,
                        usuario_clave = :clave
                        WHERE usuario_id = :id';
                $params = [
                        ':nombre' => $name,
                        ':apellido' => $lastName,
                        ':usuario' => $user,
                        ':email' => $email,
                        ':clave' => $hashedPassword,
                        ':id' => $usuario_id,
                ];
        } else {
                $sql_update = 'UPDATE usuario SET
                        usuario_nombre = :nombre,
                        usuario_apellido = :apellido,
                        usuario_usuario = :usuario,
                        usuario_email = :email
                        WHERE usuario_id = :id';
                $params = [
                        ':nombre' => $name,
                        ':apellido' => $lastName,
                        ':usuario' => $user,
                        ':email' => $email,
                        ':id' => $usuario_id,
                ];
        }

        $stmt_update = $db->prepare($sql_update);
        $ok = $stmt_update->execute($params);

        if ($ok) {
                echo '
                <div class="notification is-success is-light">
                        <strong>Usuario actualizado!</strong><br>
                        Los datos se guardaron correctamente.
                </div>
                ';
        } else {
                echo '
                <div class="notification is-danger is-light">
                        <strong>Error!</strong><br>
                        No se pudo actualizar el usuario.
                </div>
                ';
        }
} catch (Exception $e) {
        echo '
        <div class="notification is-danger is-light">
                <strong>Error!</strong><br>
                Ocurrió un problema al actualizar el usuario.
        </div>
        ';
        echo '<pre>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
}

$db = null;

?>