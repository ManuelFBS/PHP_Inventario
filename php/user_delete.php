<?php

$user_id_del = clean_String($_GET['user_id_del']);

// * Verificar usuario...
$db = connect();

$queryUser = "SELECT usuario_id FROM usuario WHERE usuario_id = $user_id_del";
$check_user = $db->prepare($queryUser);
$check_user->execute();

if ($check_user->rowCount() == 1) {
        // > Verificando si el usuario tiene productos registrados...
        $prodDB = connect();
        $queryProduct = "SELECT usuario_id FROM producto WHERE usuario_id = $user_id_del LIMIT 1";
        $check_product = $prodDB->prepare($queryProduct);
        $check_product->execute();

        if ($check_product->rowCount() <= 0) {
                $userDB = connect();
                $queryDeleteUser = 'DELETE FROM usuario WHERE usuario_id = :id';
                $deleteUser = $userDB->prepare($queryDeleteUser);
                $deleteUser->execute([':id' => $user_id_del]);

                if ($deleteUser->rowCount() == 1) {
                        echo '
                                <div class="notification is-info is-light">
                                        <strong>Usuario eliminado !</strong><br>
                                        los datos del usuario se eliminaron con éxito !!!
                                </div>
                        ';
                } else {
                        echo '
                                <div class="notification is-danger is-light">
                                        <strong>Ocurrió un error inesperado!</strong><br>
                                        NO se pudo eliminar el usuario, por favor intente nuevamente...!
                                </div>
                        ';
                }
                $userDB = null;
        } else {
                echo '
                        <div class="notification is-danger is-light">
                                <strong>Ocurrió un error inesperado!</strong><br>
                                NO se puede eliminar el usuario ya que posee productos registrados...!
                        </div>
                ';
                // exit();
        }
        $check_product = null;
} else {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        El usuario que intenta eliminar NO existe...!
                </div>
        ';
        // exit();
}
$check_user = null;

// $query="DELETE FROM usuario WHERE id = ";
// $stmt=$db->prepare()

?>