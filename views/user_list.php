<div class="container is-fluid mb-6">
        <h1 class="title">Usuarios</h1>
        <h2 class="subtitle">Lista de usuarios</h2>
</div>

<div class="container pb-6 pt-6">
        <?php
        require_once './php/main.php';

        // * Eliminar usuario...
        if (isset($_POST['user_id_del'])) {
                require_once './php/user_delete.php';
        }

        $page = isset($_POST['page']) ? (int) $_POST['page'] : 1;
        if ($page <= 0) {
                $page = 1;
        }
        $page = clean_String($page);

        $url = 'index.php?view=user_list&page=';
        $numberOfRecords = 10;
        $search = '';

        require './php/show_user_list.php';
        ?>
</div>