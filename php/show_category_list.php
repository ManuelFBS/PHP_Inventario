<?php
// ~ Variables que debe definir la vista antes del require:
// ~ $page, $numberOfRecords, $search, $url

if (!isset($page)) {
        $page = 1;
}
if (!isset($numberOfRecords)) {
        $numberOfRecords = 10;
}
if (!isset($search)) {
        $search = '';
}
if (!isset($url)) {
        $url = 'index.php?view=category_list&page=';
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
$search = trim($search);

$db = connect();
$params = [];

// * Subconsulta: cantidad de productos por categoría (si tu tabla producto tiene categoria_id)
$productCountSql = '(SELECT COUNT(*) FROM producto WHERE producto.categoria_id = categoria.categoria_id)';

if ($search !== '') {
        $params[':q'] = '%' . $search . '%';

        $dataQuery = "
                SELECT
                        categoria_id,
                        categoria_nombre,
                        categoria_ubicacion,
                        {$productCountSql} AS total_productos
                FROM categoria
                WHERE (
                        categoria_nombre    LIKE :q
                        OR categoria_ubicacion LIKE :q
                )
                ORDER BY categoria_nombre ASC
                LIMIT $init, $numberOfRecords
        ";

        $totalQuery = '
                SELECT COUNT(categoria_id)
                FROM categoria
                WHERE (
                        categoria_nombre    LIKE :q
                        OR categoria_ubicacion LIKE :q
                )
        ';
} else {
        $dataQuery = "
                SELECT
                        categoria_id,
                        categoria_nombre,
                        categoria_ubicacion,
                        {$productCountSql} AS total_productos
                FROM categoria
                ORDER BY categoria_nombre ASC
                LIMIT $init, $numberOfRecords
        ";

        $totalQuery = 'SELECT COUNT(categoria_id) FROM categoria';
}

// * 1) Total de registros
$totalStmt = $db->prepare($totalQuery);
$totalStmt->execute($params);
$total = (int) $totalStmt->fetchColumn();

$nPage = ($numberOfRecords > 0) ? (int) ceil($total / $numberOfRecords) : 1;

// * 2) Filas de la página actual
$dataStmt = $db->prepare($dataQuery);
$dataStmt->execute($params);
$rows = $dataStmt->fetchAll(PDO::FETCH_ASSOC);

// * 3) Tabla HTML
$table .= '
<div class="table-container">
        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                <thead>
                        <tr class="has-text-centered">
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Ubicación</th>
                                <th>Productos</th>
                                <th colspan="2">Opciones</th>
                        </tr>
                </thead>
                <tbody>
';

if ($total >= 1 && $page <= $nPage) {
        $count = $init + 1;
        $page_init = $count;

        foreach ($rows as $r) {
                $categoryId = (int) $r['categoria_id'];
                $totalProducts = (int) $r['total_productos'];

                // > Enlace "Ver productos" (ajusta la vista cuando exista product_list)
                $productsLink = 'index.php?view=product_list&category_id=' . $categoryId;

                $table .= '
                <tr class="has-text-centered">
                        <td>' . $count . '</td>
                        <td class="has-text-left">' . htmlspecialchars($r['categoria_nombre']) . '</td>
                        <td class="has-text-left">' . htmlspecialchars($r['categoria_ubicacion']) . '</td>
                        <td>
                                <a href="' . $productsLink . '" class="button is-link is-rounded is-small">
                                        Ver productos (' . $totalProducts . ')
                                </a>
                        </td>
                        <td>
                                <a href="index.php?view=category_update&category_id_up=' . $categoryId . '"
                                   class="button is-success is-rounded is-small">
                                        Actualizar
                                </a>
                        </td>
                        <td>
                                <form action="" method="POST" class="is-inline"
                                      onsubmit="return confirm(\'¿Seguro que deseas eliminar esta categoría?\');">
                                        <input type="hidden" name="category_id_del" value="' . $categoryId . '">
                                        <input type="hidden" name="page" value="' . (int) $page . '">
                                        <button type="submit" class="button is-danger is-rounded is-small">
                                                Eliminar
                                        </button>
                                </form>
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
        Mostrando categorías <strong>' . $page_init . '</strong> al <strong>' . $page_end . '</strong>
        de un <strong>total de ' . $total . '</strong>
</p>
';

        // * Paginación (misma lógica que show_user_list.php)
        $table .= '<nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">';

        if ($page > 1) {
                $table .= '<a class="pagination-previous" href="' . $url . ($page - 1) . '">Anterior</a>';
        } else {
                $table .= '<a class="pagination-previous" disabled>Anterior</a>';
        }

        if ($page < $nPage) {
                $table .= '<a class="pagination-next" href="' . $url . ($page + 1) . '">Siguiente</a>';
        } else {
                $table .= '<a class="pagination-next" disabled>Siguiente</a>';
        }

        $table .= '<ul class="pagination-list">';

        $maxButtons = 7;
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

        if ($start > 1) {
                $table .= '<li><a class="pagination-link" href="' . $url . '1">1</a></li>';
                if ($start > 2) {
                        $table .= '<li><span class="pagination-ellipsis">&hellip;</span></li>';
                }
        }

        for ($i = $start; $i <= $end; $i++) {
                if ($i == $page) {
                        $table .= '<li><a class="pagination-link is-current" aria-current="page" href="' . $url . $i . '">' . $i . '</a></li>';
                } else {
                        $table .= '<li><a class="pagination-link" href="' . $url . $i . '">' . $i . '</a></li>';
                }
        }

        if ($end < $nPage) {
                if ($end < ($nPage - 1)) {
                        $table .= '<li><span class="pagination-ellipsis">&hellip;</span></li>';
                }
                $table .= '<li><a class="pagination-link" href="' . $url . $nPage . '">' . $nPage . '</a></li>';
        }

        $table .= '</ul></nav>';
} else {
        if ($total >= 1) {
                $table .= '
                        <tr class="has-text-centered">
                                <td colspan="6">
                                        <a href="' . $url . '1" class="button is-link is-rounded is-small mt-4 mb-4">
                                                Haga clic acá para recargar el listado
                                        </a>
                                </td>
                        </tr>
                </tbody>
        </table>
</div>';
        } else {
                $table .= '
                        <tr class="has-text-centered">
                                <td colspan="6">No hay registros en el sistema.</td>
                        </tr>
                </tbody>
        </table>
</div>';
        }
}

echo $table;
