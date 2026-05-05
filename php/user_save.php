<?php

require_once 'main.php';

// * Limpieza de todos los strings antes de ser pasados como valores a guardar...
$name = clean_String($_POST['usuario_nombre']);
$lastName = clean_String($_POST['usuario_apellido']);
$user = clean_String($_POST['usuario_usuario']);
$email = clean_String($_POST['usuario_email']);
$passW1 = clean_String($_POST['usuario_clave_1']);
$passW2 = clean_String($_POST['usuario_clave_2']);

// * Comprobación de que todos los inputs (a excepción del email) NO esten vacíos...
if ($name == '' ||
                $lastName == '' ||
                $user == '' ||
                $passW1 == '' ||
                $passW2 == '') {
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
if (verify_Data('[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}', $name)) {
        echo '
        <div class="notification is-danger is-light">
                <strong>Ocurrió un error inesperado!</strong><br>
                El <b>Nombre NO coincide</b> con el formato solicitado...
        </div>
        ';
        exit();
}

if (verify_Data('[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}', $lastName)) {
        echo '
        <div class="notification is-danger is-light">
                <strong>Ocurrió un error inesperado!</strong><br>
                El <b>Apellido NO coincide</b> con el formato solicitado...
        </div>
        ';
        exit();
}

if (verify_Data('[a-zA-Z0-9]{4,20}', $user)) {
        echo '
        <div class="notification is-danger is-light">
                <strong>Ocurrió un error inesperado!</strong><br>
                El <b>Usuario NO coincide</b> con el formato solicitado...
        </div>
        ';
        exit();
}

if (verify_Data('[a-zA-Z0-9$@.-]{7,100}', $passW1) ||
                verify_Data('[a-zA-Z0-9$@.-]{7,100}', $passW2)) {
        echo '
        <div class="notification is-danger is-light">
                <strong>Ocurrió un error inesperado!</strong><br>
                Las <b>Claves NO coinciden</b> con el formato solicitado...
        </div>
        ';
        exit();
}
// ~ =======================================================================

/* =========================
   * Passwords must match...
   ========================== */
if ($passW1 !== $passW2) {
        echo '
        <div class="notification is-danger is-light">
                <strong>Ocurrió un error inesperado!</strong><br>
                Las <b>claves NO coinciden</b>...
        </div>
        ';
        exit();
} else {
        $hashedPassword = password_hash($passW1, PASSWORD_BCRYPT, ['cost' => 10]);
}

// * Validación del email...
if ($email != '') {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $check_email = connect();
                $stmt = $check_email->prepare(
                        'SELECT usuario_email FROM usuario WHERE usuario_email = :email'
                );
                $stmt->execute(['email' => $email]);

                if ($stmt->rowCount()) {
                        echo '
                        <div class="notification is-danger is-light">
                                <strong>Ocurrió un error inesperado!</strong><br>
                                El <b>EMAIL ingresado</b> ya se encuentra registrado, por
                                favor elija otro Email...
                        </div>
                        ';
                        exit();
                }
                $check_email = null;
        } else {
                echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        El <b>EMAIL ingresado</b> NO es válido...
                </div>
                ';
                exit();
        }
}

// * Procediendo a guardar los datos en la BD...
try {
        $db = connect();

        $sql = 'INSERT INTO 
                usuario (usuario_nombre, usuario_apellido, usuario_usuario, usuario_clave, usuario_email) 
                VALUES (:name, :lastName, :user, :passW1, :email)';

        $stmt = $db->prepare($sql);

        $ok = $stmt->execute([
                ':name' => $name,
                ':lastName' => $lastName,
                ':user' => $user,
                ':passW1' => $hashedPassword,
                ':email' => $email
        ]);

        if ($ok) {
                echo '
                <div class="notification is-success is-light">
                <strong>Usuario guardado!</strong><br>
                El registro se almacenó correctamente.
                </div>
                ';
        } else {
                echo '
                <div class="notification is-danger is-light">
                <strong>Error!</strong><br>
                No se pudo guardar el usuario.
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