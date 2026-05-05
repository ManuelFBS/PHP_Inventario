<?php

$user = clean_String($_POST['login_usuario']);
$password = clean_String($_POST['login_clave']);

// * Comprobación de que todos los inputs (a excepción del email) NO esten vacíos...
if ($user == '' || $password == '') {
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
if (verify_Data('[a-zA-Z0-9]{4,20}', $user)) {
        echo '
        <div class="notification is-danger is-light">
                <strong>Ocurrió un error inesperado!</strong><br>
                El <b>Usuario NO coincide</b> con el formato solicitado...
        </div>
        ';
        exit();
}

if (verify_Data('[a-zA-Z0-9$@.-]{7,100}', $password)) {
        echo '
        <div class="notification is-danger is-light">
                <strong>Ocurrió un error inesperado!</strong><br>
                Las <b>Claves NO coinciden</b> con el formato solicitado...
        </div>
        ';
        exit();
}
// ~ =======================================================================

try {
        $pdo = connect();

        $sql = 'SELECT usuario_id, usuario_nombre, usuario_apellido, usuario_usuario, usuario_clave 
                FROM usuario WHERE usuario_usuario = :user LIMIT 1';

        $stmt = $pdo->prepare($sql);

        // > IMPORTANTE: unir el :user placeholder...
        $stmt->bindValue(':user', $user, PDO::PARAM_STR);

        $stmt->execute();

        // > Obtener la fila del ESTADO (no de la conexión)...
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // > Si el usuario NO se encuentra...
        if (!$row) {
                echo '
                <div class="notification is-danger is-light">
                <strong>Ocurrió un error inesperado!</strong><br>
                Usuario o contraseña incorrecto...!
                </div>';
                exit();
        }

        if (!password_verify($password, $row['usuario_clave'])) {
                echo '
                <div class="notification is-danger is-light">
                <strong>Ocurrió un error inesperado!</strong><br>
                Usuario o contraseña incorrecto...!
                </div>';
                exit();
        }

        // > Login OK: se edstablecen los valores de la sesión...
        $_SESSION['id'] = $row['usuario_id'];
        $_SESSION['nombre'] = $row['usuario_nombre'];
        $_SESSION['apellido'] = $row['usuario_apellido'];
        $_SESSION['usuario'] = $row['usuario_usuario'];

        // > Se redirige a 'home'...
        header('Location: index.php?view=home');

        exit();
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