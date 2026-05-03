<?php

// ~ Conexión a la base de datos...

/**
 * * Load key=value pairs from a .env file into $_ENV and getenv().
 */
function loadEnv(string $path)
{
        if (!is_readable($path)) {
                return;
        }
}

function connect()
{
        $pdo = new PDO('mysql:host=?;dbname=?', '?', '?????');

        return $pdo;
}

?>