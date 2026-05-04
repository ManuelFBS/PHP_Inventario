<?php

// ~ Conexión a la base de datos...

/**
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

/**
 * * La función `connect` establece una conexión con la base de datos PDO
 * * utilizando parámetros de un archivo .env.
 *
 * @return PDO La función `connect()` devuelve una instancia de PDO
 * (PHP Data Objects), que es una base de datos.
 * * Objeto de conexión utilizado para interactuar con una base de datos MySQL.
 */
function connect(): PDO
{
        // > Ruta al archivo .env desde este archivo (ajustar si php/ se encuentra en
        // > otra ubicación relativa a la raíz del proyecto)...
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

// * Verifica los datos enviados por un formulario...
function verify_Data(string $filter, string $string)
{
        if (preg_match('/^' . $filter . '$/', $string)) {
                return false;
        } else {
                return true;
        }
}

// * Limpia una cadena, para evitar posibles "inyecciones de SQL" y
// * ataques XSS
function clean_String(string $string)
{
        // > Elimina espacios en blanco...
        $string = trim($string);
        //
        // > Elimina slash [barras invertidas ('\')], en caso de
        // > de existir doble slash ('\\'), elimina una de ellas...
        $string = stripslashes($string);
        //
        // > Devuelve una string de caracteres o un array en el que todas
        // > las ocurrencias de search en subject (ignorando mayúsculas
        // > y minúsculas), han sido reemplazadas por el valor de replace...
        $string = str_ireplace('<script>', '', $string);
        $string = str_ireplace('</script>', '', $string);
        $string = str_ireplace('<script src', '', $string);
        $string = str_ireplace('<script type=', '', $string);
        $string = str_ireplace('SELECT * FROM', '', $string);
        $string = str_ireplace('DELETE FROM', '', $string);
        $string = str_ireplace('INSERT INTO', '', $string);
        $string = str_ireplace('DROP TABLE', '', $string);
        $string = str_ireplace('DROP DATABASE', '', $string);
        $string = str_ireplace('TRUNCATE TABLE', '', $string);
        $string = str_ireplace('SHOW TABLES;', '', $string);
        $string = str_ireplace('SHOW DATABASES;', '', $string);
        $string = str_ireplace('<?php', '', $string);
        $string = str_ireplace('?>', '', $string);
        $string = str_ireplace('--', '', $string);
        $string = str_ireplace('^', '', $string);
        $string = str_ireplace('<', '', $string);
        $string = str_ireplace('[', '', $string);
        $string = str_ireplace(']', '', $string);
        $string = str_ireplace('==', '', $string);
        $string = str_ireplace(';', '', $string);
        $string = str_ireplace('::', '', $string);
        $string = trim($string);
        $string = stripslashes($string);
        return $string;
}

// * Función para renombrar fotos...
function rename_Photos(string $name)
{
        $name = str_ireplace(' ', '_', $name);
        $name = str_ireplace('/', '_', $name);
        $name = str_ireplace('#', '_', $name);
        $name = str_ireplace('-', '_', $name);
        $name = str_ireplace('$', '_', $name);
        $name = str_ireplace('.', '_', $name);
        $name = str_ireplace(',', '_', $name);

        $name = $name . '_' . rand(0, 100);

        return $name;
}

// * Función paginador de tablas...
function table_Paginator(
        int $page,
        int $nPage,
        string $url,
        int $buttons
) {
        $table = '<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';

        if ($page <= 1) {
                $table .= '
                <a class="pagination-previous is-disabled" disabled >Anterior</a>
                <ul class="pagination-list"';
        } else {
                $table .= '
                <a class="pagination-previous" href="' . $url . ($page - 1) . '" >Anterior</a>
                <ul class="pagination-list">
                <li><a class="pagination-link" href="' . $url . '1">1</a></li>
                <li><span class="pagination-ellipsis">&hellip;</span></li>';
        }

        $ci = 0;
        for ($i = 0; $i <= $nPage; $i++) {
                if ($ci >= $buttons) {
                        break;
                }
                if ($page == $i) {
                        $table .= '<li><a class="pagination-link is-current" href="' . $url . $i . '">' . $i . '</a></li>';
                } else {
                        $table .= '<li><a class="pagination-link" href="' . $url . $i . '">' . $i . '</a></li>';
                }
                $ci++;
        }

        if ($page == $nPage) {
                $table .= '
                </ul>
                <a class="pagination-next is-disabled" disabled >Siguiente</a>';
        } else {
                $table .= '
                <li><span class="pagination-ellipsis">&hellip;</span></li>
                <li><a class="pagination-link" href="' . $url . $nPage . '">' . $nPage . '</a></li>
                </ul>
                <a class="pagination-next" href="' . $url . ($page + 1) . '" >Siguiente</a>';
        }

        $table .= '</nav>';

        return $table;
}

?>