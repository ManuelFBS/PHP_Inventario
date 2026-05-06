<?php

// ~ Cierre de sesión...

session_destroy();

if (headers_sent()) {
        echo '<script> window.location.href="index.php?view=login"; </sript>';
} else {
        header('Location: index.php?view=login');
}

?>