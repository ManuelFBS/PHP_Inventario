<?php
require './inc/session_start.php';

if (isset($_POST['search_module'])) {
        require_once './php/main.php';
        require_once './php/seeker.php';
        // > seeker.php hace header() + exit(); no llegará al HTML de abajo...
}

?>

<!DOCTYPE html>
<html lang="es" data-theme="light">
        <head>
                <?php include './inc/head.php' ?>
        </head>

        <body>
                <?php
                if (!isset($_GET['view']) || $_GET['view'] == '') {
                        $_GET['view'] = 'login';
                }

                if (is_file('./views/' . $_GET['view'] . '.php') &&
                                $_GET['view'] != 'login' &&
                                $_GET['view'] != '404') {
                        // > Cerrar sesión. Se bloquea cualquier acceso no loggeado...
                        if ((
                                !isset($_SESSION['id']) ||
                                $_SESSION['id'] == ''
                        ) ||
                                (!isset($_SESSION['usuario']) ||
                                        $_SESSION['usuario'] == '')) {
                                include './views/logout.php';
                                exit();
                        }

                        include './inc/navbar.php';

                        include './views/' . $_GET['view'] . '.php';

                        include './inc/script.php';
                } else {
                        if ($_GET['view'] == 'login') {
                                include './views/login.php';
                        } else {
                                include './views/404.php';
                        }
                }

                ?>
        </body>
</html>