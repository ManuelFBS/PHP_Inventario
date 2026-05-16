<?php

$category_id_del = isset($POST['category_id_del']) ? (int) $_POST['category_id_del'] : 0;

if ($category_id_del <= 0) {
        echo '
                <div class="notification is-danger is-light">
                        <strong>Ocurrió un error inesperado!</strong><br>
                        ID de categoría inválido.
                </div>
        ';
        return;
}

$db = connect();

$check_category = $db->prepare('SELECT categoria_id FROM categoria WHERE categoria_id = :id');
$check_category->execute([':id' => $category_id_del]);

if ($check_category->rowCount() === 1) {
        $check_product = $db->prepare(
                'SELECT categoria_id FROM producto WHERE categoria_id = :id LIMIT 1'
        );
        $check_product->execute([':id' => $category_id_del]);
        if ($check_product->rowCount() <= 0) {
                $deleteCategory = $db->prepare('DELETE FROM categoria WHERE categoria_id = :id');
                $deleteCategory->execute([':id' => $category_id_del]);
                if ($deleteCategory->rowCount() === 1) {
                        echo '
                        <div class="notification is-info is-light">
                                <strong>Categoría eliminada!</strong><br>
                                El registro se eliminó con éxito.
                        </div>
                        ';
                } else {
                        echo '
                        <div class="notification is-danger is-light">
                                <strong>Ocurrió un error inesperado!</strong><br>
                                No se pudo eliminar la categoría.
                        </div>
                        ';
                }
        } else {
                echo '
                <div class="notification is-danger is-light">
                        <strong>No se puede eliminar!</strong><br>
                        La categoría tiene productos registrados.
                </div>
                ';
        }
} else {
        echo '
        <div class="notification is-danger is-light">
                <strong>Ocurrió un error inesperado!</strong><br>
                La categoría no existe.
        </div>
        ';
}

?>