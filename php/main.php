<?php

// ~ Conexión a la base de datos...

function connect()
{
        $pdo = new PDO('mysql:host=localhost;dbname=inventario', 'root', '321?Shalom?321');

        return $pdo;
}

?>