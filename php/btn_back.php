<!-- <p class="has-text-right pt-4 pb-4">
	<a href="#" class="button is-link is-rounded btn-back"><- Regresar al listado</a>
</p>
<script type="text/javascript">
        let btn_back = document.querySelector(".btn-back");

        btn_back.addEventListener('click', function(e){
                e.preventDefault();
                window.history.back();
        });
</script> -->

<?php

/*
 * Antes de hacer include de este archivo, la vista debe definir:
 *
 *   $btn_back_url   — URL de destino (ej: index.php?view=category_list)
 *   $btn_back_label — texto del botón (opcional)
 *
 * Si no se define $btn_back_url, se usa el inicio (home)...
 */

if (!isset($btn_back_url) || $btn_back_url === '') {
        $btn_back_url = 'index.php?view=home';
}

if (!isset($btn_back_label) || $btn_back_label === '') {
        $btn_back_label = 'Regresar al listado';
}

$url = htmlspecialchars($btn_back_url, ENT_QUOTES, 'UTF-8');
$label = htmlspecialchars($btn_back_label, ENT_QUOTES, 'UTF-8');

?>

<p class="has-text-right pt-4 pb-4">
        <a href="<?php echo $url; ?>" class="button is-link is-rounded">
                &larr; <?php echo $label ?>
        </a>
</p>