<div class="container is-fluid mb-6">
        <h1 class="title">Categoría</h1>
        <h2 class="subtitle">Buscar categoría</h2>
</div>

<div class="container pb-6 pt-6">
        <?php
        require_once './php/main.php';

        // ! if (isset($_POST['search_module'])) {
        // !        require_once './php/seeker.php';
        // ! }

        if (!isset($_SESSION['search_category'])) {
                ?>
                <div class="columns">
                        <div class="column">
                                <form action="" method="POST" autocomplete="off" >
                                        <!-- seeker.php valida que sea 'user', 'category' o 'product' -->
                                        <input type="hidden" name="search_module" value="category">
                                        <div class="field is-grouped">
                                                <p class="control is-expanded">
                                                        <input 
                                                        class="input is-rounded" 
                                                        type="text" 
                                                        name="txt_search" 
                                                        placeholder="¿Qué categoría buscas?" 
                                                        pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" 
                                                        maxlength="30" >
                                                </p>
                                                <p class="control">
                                                        <button class="button is-info" type="submit" >Buscar</button>
                                                </p>
                                        </div>
                                </form>
                        </div>
                </div>
        <?php } else { ?>

                <div class="columns">
                        <div class="column">
                                <form class="has-text-centered mt-6 mb-6" action="" method="POST" autocomplete="off" >
                                        <input type="hidden" name="search_module" value="category"> 
                                        <input type="hidden" name="delete_search" value="1">
                                        <p>Estas buscando <strong>
                                                <?php echo $_SESSION['search_category']; ?></strong>
                                        </p>
                                        <br>
                                        <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
                                </form>
                        </div>
                </div>

        <?php
                // * Opcional: eliminar categoría desde resultados de búsqueda...
                if (isset($_POST['category_id_del'])) {
                        require_once './php/category_delete.php';
                }

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

                $url = 'index.php?view=category_search&page=';
                $numberOfRecords = 10;
                $search = $_SESSION['search_category'];  // > Mismo $search que usa show_category_list.php...

                require './php/show_category_list.php';
        }
        ?>
</div>