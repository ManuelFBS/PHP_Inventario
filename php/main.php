<?php

// ~ Conexión a la base de datos...

/*
 * ~ Se utiliza una "carga manual" de las varibles de entorno.
 * ~ Esto se puede hacer (con fines de aprendizaje) en apps pequeñas
 * ~ o de poca complejidad.
 * ~ Para apps más grandes o más complejas, debe utilizarse "Composer"...
 */

/**
 * * Carga pares clave = valor desde un archivo .env en $_ENV y usa getenv().
 */
function loadEnv(string $path)
{
        if (!is_readable($path)) {
                return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
                $trimmed = trim($line);
                if ($trimmed === '' || str_starts_with($trimmed, '#')) {
                        continue;
                }
                $parts = explode('=', $line, 2);
                if (count($parts) !== 2) {
                        continue;
                }
                $name = trim($parts[0]);
                $value = trim($parts[1]);
                $_ENV[$name] = $value;
                putenv("$name=$value");
        }
}

function connect(): PDO
{
        // * Ruta al archivo .env desde este archivo (ajustar si php/ se encuentra en
        // * otra ubicación relativa a la raíz del proyecto)...
        loadEnv(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env');

        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $dbName = $_ENV['DB_NAME'] ?? '';
        $user = $_ENV['DB_USER'] ?? '';
        $password = $_ENV['DB_PASSWORD'] ?? '';

        $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8mb4";

        $pdo = new PDO(
                $dsn,
                $user,
                $password,
                [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]
        );

        return $pdo;
}

?>