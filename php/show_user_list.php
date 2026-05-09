<?php
// ~ This file expects these variables to exist (they are set in the view):
// ~ $page, $numberOfRecords, $search, $url
// ~ And it expects connect() to return a PDO instance (defined in ./php/main.php).

// Defaults: prevent "Undefined variable" if this file is included elsewhere
if (!isset($page)) {
        $page = 1;
}
if (!isset($numberOfRecords)) {
        $numberOfRecords = 3;
}
if (!isset($search)) {
        $search = '';
}
if (!isset($url)) {
        $url = 'index.php?view=user_list&page=';
}
$page = (int) $page;
if ($page <= 0) {
        $page = 1;
}
$numberOfRecords = (int) $numberOfRecords;
if ($numberOfRecords <= 0) {
        $numberOfRecords = 10;
}

$init = ($page > 0) ? (($page * $numberOfRecords) - $numberOfRecords) : 0;
$table = '';

$search = isset($search) ? trim($search) : '';
$userId = isset($_SESSION['id']) ? (int) $_SESSION['id'] : 0;

$db = connect();

// * Build SQL safely using placeholders (prevents SQL injection)
$params = [
        ':current_user_id' => $userId,
];

if ($search !== '') {
        $params[':q'] = '%' . $search . '%';

        $dataQuery = "
                SELECT usuario_id, usuario_nombre, usuario_apellido, usuario_usuario, usuario_email
                FROM usuario
                WHERE usuario_id <> :current_user_id
                AND (
                        usuario_nombre   LIKE :q
                        OR usuario_apellido LIKE :q
                        OR usuario_usuario  LIKE :q
                        OR usuario_email    LIKE :q
                )
                ORDER BY usuario_nombre ASC
                LIMIT $init, $numberOfRecords
        ";

        $totalQuery = '
                SELECT COUNT(usuario_id)
                FROM usuario
                WHERE usuario_id <> :current_user_id
                AND (
                        usuario_nombre   LIKE :q
                        OR usuario_apellido LIKE :q
                        OR usuario_usuario  LIKE :q
                        OR usuario_email    LIKE :q
                )
        ';
} else {
        $dataQuery = "
                SELECT usuario_id, usuario_nombre, usuario_apellido, usuario_usuario, usuario_email
                FROM usuario
                WHERE usuario_id <> :current_user_id
                ORDER BY usuario_nombre ASC
                LIMIT $init, $numberOfRecords
        ";

        $totalQuery = '
                SELECT COUNT(usuario_id)
                FROM usuario
                WHERE usuario_id <> :current_user_id
        ';
}

// * 1) Se obtiene todas las filas...
$totalStmt = $db->prepare($totalQuery);
$totalStmt->execute($params);
$total = (int) $totalStmt->fetchColumn();

$nPage = ($numberOfRecords > 0) ? (int) ceil($total / $numberOfRecords) : 1;

// * 2) Obtener filas paginadas...
$dataStmt = $db->prepare($dataQuery);
$dataStmt->execute($params);
$rows = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

// * 3) Se construye una tabla HTML...
$table .= '
<div class="table-container">
        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                        <tr class="has-text-centered">
                                <th>#</th>
                                <th>Nombres</th>
                                <th>Apellidos</th>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th colspan="2">Opciones</th>
                        </tr>
                </thead>
                <tbody>
';

if ($total >= 1 && $page <= $nPage) {
        $count = $init + 1;
        $page_init = $count;

        foreach ($rows as $r) {
                $table .= '
                <tr class="has-text-centered">
                        <td class="has-text-left">' . $count . '</td>
                        <td class="has-text-left">' . htmlspecialchars($r['usuario_nombre']) . '</td>
                        <td class="has-text-left">' . htmlspecialchars($r['usuario_apellido']) . '</td>
                        <td class="has-text-left">' . htmlspecialchars($r['usuario_usuario']) . '</td>
                        <td class="has-text-left">' . htmlspecialchars($r['usuario_email']) . '</td>
                        <td class="has-text-centered">
                                <a href="index.php?view=user_update&user_id_up=' . (int) $r['usuario_id'] . '" class="button is-success is-rounded is-small">
                                Actualizar
                                </a>
                        </td>
                        <td class="has-text-centered">
                                <a href="index.php?view=user_update&user_id_del=' . (int) $r['usuario_id'] . '" class="button is-danger is-rounded is-small">
                                Eliminar
                                </a>
                        </td>
                </tr>
                ';
                $count++;
        }

        $page_end = $count - 1;

        $table .= '
                </tbody>
                </table>
        </div>

        <p class="has-text-right">
        Mostrando usuarios <strong>' . $page_init . '</strong> al <strong>' . $page_end . '</strong> de un <strong>total de ' . $total . '</strong>
        </p>
        ';

        // // * Paginación simple...
        // $table .= '<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';

        // if ($page > 1) {
        //         $table .= '<a class="pagination-previous" href="' . $url . ($page - 1) . '">Anterior</a>';
        // } else {
        //         $table .= '<a class="pagination-previous" disabled>Anterior</a>';
        // }

        // if ($page < $nPage) {
        //         $table .= '<a class="pagination-next" href="' . $url . ($page + 1) . '">Siguiente</a>';
        // } else {
        //         $table .= '<a class="pagination-next" disabled>Siguiente</a>';
        // }

        // * $table .= '</nav>';
        $table .= '<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';

        // * Previo...
        if ($page > 1) {
                $table .= '<a class="pagination-previous" href="' . $url . ($page - 1) . '">Anterior</a>';
        } else {
                $table .= '<a class="pagination-previous" disabled>Anterior</a>';
        }

        // * Siguiente...
        if ($page < $nPage) {
                $table .= '<a class="pagination-next" href="' . $url . ($page + 1) . '">Siguiente</a>';
        } else {
                $table .= '<a class="pagination-next" disabled>Siguiente</a>';
        }

        // * Botones de página...
        $table .= '<ul class="pagination-list">';

        // * ¿Cuántos botones de página mostrar alrededor de la página actual?...
        $maxButtons = 7;  // > Total de botones (approx)...
        $side = (int) floor($maxButtons / 2);

        $start = $page - $side;
        $end = $page + $side;

        if ($start < 1) {
                $end += (1 - $start);
                $start = 1;
        }
        if ($end > $nPage) {
                $start -= ($end - $nPage);
                $end = $nPage;
                if ($start < 1) {
                        $start = 1;
                }
        }

        // * Siempre muestra la página 1 (si no está incluída)...
        if ($start > 1) {
                $table .= '<li><a class="pagination-link" href="' . $url . '1">1</a></li>';

                if ($start > 2) {
                        $table .= '<li><span class="pagination-ellipsis">&hellip;</span></li>';
                }
        }

        // * Rango medio...
        for ($i = $start; $i <= $end; $i++) {
                if ($i == $page) {
                        $table .= '<li><a class="pagination-link is-current" aria-current="page" href="' . $url . $i . '">' . $i . '</a></li>';
                } else {
                        $table .= '<li><a class="pagination-link" href="' . $url . $i . '">' . $i . '</a></li>';
                }
        }

        // Siempre muestra la última página (si no está incluída)...
        if ($end < $nPage) {
                if ($end < ($nPage - 1)) {
                        $table .= '<li><span class="pagination-ellipsis">&hellip;</span></li>';
                }
                $table .= '<li><a class="pagination-link" href="' . $url . $nPage . '">' . $nPage . '</a></li>';
        }

        $table .= '</ul>';
        $table .= '</nav>';
} else {
        if ($total >= 1) {
                $table .= '
                                <tr class="has-text-centered">
                                        <td colspan="7">
                                        <a href="' . $url . '1" class="button is-link is-rounded is-small mt-4 mb-4">
                                        Haga clic acá para recargar el listado
                                        </a>
                                        </td>
                                </tr>
                                </tbody>
                        </table>
                </div>
                ';
        } else {
                $table .= '
                                <tr class="has-text-centered">
                                        <td colspan="7">
                                        No hay registros en el sistema.
                                        </td>
                                </tr>
                                </tbody>
                        </table>
                </div>
                ';
        }
}

echo $table;
