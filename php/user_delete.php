<?php

// * Se lee desde POST en vez de GET...
$user_id_del = isset($_POST['user_id_del']) ? (int) $_POST['user_id_del'] : 0;

// * Validación básica...
if ($user_id_del <= 0) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        ID de usuario inválido.
                </div>
        ';
        return;
}

// * Opcional: impedir que te eliminen a ti mismo (seguridad adicional)...
$currentUserid = isset($_SESSION['id']) ? (int) $_SESSION['id'] : 0;
if ($currentUserid > 0 && $user_id_del === $currentUserid) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Acción no permitida!</strong><br>
                        No puedes eliminar tu propio usuario.
                </div>
        ';
        return;
}

$db = connect();

// * Se verifica si existe el usuario...
$check_user = $db->prepare('SELECT usuario_id FROM usuario WHERE usuario_id = :id');
$check_user->execute([':id' => $user_id_del]);

if ($check_user->rowCount() === 1) {
        // > Check if the user has products (prepared statement)
        $check_product = $db->prepare('SELECT usuario_id FROM producto WHERE usuario_id = :id LIMIT 1');
        $check_product->execute([':id' => $user_id_del]);
        if ($check_product->rowCount() <= 0) {
                // Eliminar usuario...
                $deleteUser = $db->prepare('DELETE FROM usuario WHERE usuario_id = :id');
                $deleteUser->execute([':id' => $user_id_del]);
                if ($deleteUser->rowCount() === 1) {
                        echo '
                        <div class="notification is-info is-light">
                                <strong>Usuario eliminado!</strong><br>
                                Los datos del usuario se eliminaron con éxito.
                        </div>
                        ';
                } else {
                        echo '
                        <div class="notification is-danger is-light">
                                <strong>Ocurrió un error inesperado!</strong><br>
                                No se pudo eliminar el usuario, por favor intente nuevamente.
                        </div>
                        ';
                }
        } else {
                echo '
                        <div class="notification is-danger is-light">
                                <strong>Ocurrió un error inesperado!</strong><br>
                                No se puede eliminar el usuario ya que posee productos registrados.
                        </div>
                ';
        }
} else {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        El usuario que intenta eliminar no existe.
                </div>
        ';
}

?>