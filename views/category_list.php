<div class="container is-fluid mb-6">
        <h1 class="title">Categorías</h1>
        <h2 class="subtitle">Lista de categorías</h2>
</div>

<div class="container pb-6 pt-6">
        <?php
        require_once './php/main.php';

        // * Eliminar categoría (POST desde el botón Eliminar)...
        if (isset($_POST['category_id_del'])) {
                require_once './php/category_delete.php';
        }

        // * Página actual: POST (al eliminar) o GET (paginación)...
        $page = 1;
        if (isset($_POST['page'])) {
                $page = (int) $_POST['page'];
        } elseif (isset($_GET['page'])) {
                $page = (int) $_GET['page'];
        }
        if ($page <= 0) {
                $page = 1;
        }
        $page = clean_String($page);

        $url = 'index.php?view=category_list&page=';
        $numberOfRecords = 10;
        $search = '';  // > Lista completa, sin filtro de búsqueda...

        require './php/show_category_list.php';
        ?>
</div>