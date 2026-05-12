<div class="container is-fluid mb-6">
        <h1 class="title">Usuarios</h1>
        <h2 class="subtitle">Buscar usuarios</h2>
</div>

<div class="container pb-6 pt-6">
        <?php
        require_once './php/main.php';

        if (isset($_POST['search_module'])) {
                require_once './php/seeker.php';
        }

        if (!isset($_SESSION['search_user']) && empty($_SESSION['search_user'])) {
                ?>
        <div class="columns">
                <div class="column">
                        <form action="" method="POST" autocomplete="off" >
                                <input type="hidden" name="search_module" value="user">
                                <div class="field is-grouped">
                                        <p class="control is-expanded">
                                                <input class="input is-rounded" type="text" 
                                                name="txt_search" placeholder="Qué estás buscando?" 
                                                pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30" >
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
                                <input type="hidden" name="search_module" value="user"> 
                                <input type="hidden" name="delete_search" value="user">
                                <p>Estas buscando <strong>
                                        <?php echo $_SESSION['search_user']; ?></strong>
                                </p>
                                <br>
                                <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
                        </form>
                </div>
        </div>

        <?php
                // * Eliminar usuario...
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

                $url = 'index.php?view=user_search&page=';
                $numberOfRecords = 10;
                $search = $_SESSION['search_user'];

                require './php/show_user_list.php';
        }
        ?>
</div>