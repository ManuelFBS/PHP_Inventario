<?php

require_once './php/main.php';

/*
 * El ID de categoria (category_id_up) se obtiene de la URL como una cadena
 * de texto (por ejemplo, "14").
 * Lo convertimos a entero para la lógica de la base de datos: un texto no válido
 * se convierte en 0, lo cual es correcto para "ningún usuario"...
 */
$id = isset($_GET['category_id_up']) ? (int) $_GET['category_id_up'] : 0;

?>

<div class="container is-fluid mb-6">
        <h1 class="title">Usuarios</h1>
        <h2 class="subtitle">Actualizar usuario</h2>
</div>

<div class="container pb-6 pt-6">
        <?php
        include './php/btn_back.php';

        $db = connect();
        $check_category = $db->prepare('SELECT * FROM categoria WHERE categoria_id = ?');
        $check_category->execute([$id]);

        if ($check_category->rowCount() > 0) {
                $data = $check_category->fetch();
                ?>

        <div class="form-rest mb-6 mt-6"></div>

        <form 
                action="./php/category_update_save.php" 
                method="POST" 
                class="FormAjax" 
                autocomplete="off" 
        >
                <input 
                        type="hidden" 
                        value="<?php echo $data['categoria_id'] ?>" 
                        name="categoria_id" 
                        required 
                >
		
		<!-- <div class="columns">
                        <div class="column">
                                <div class="control">
                                        <label>Nombre</label>
                                        <input 
                                                class="input" 
                                                type="text" 
                                                name="categoria_nombre" 
                                                pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}" 
                                                maxlength="50" 
                                                required 
                                        >
                                </div>
                        </div>
                        <div class="column">
                                <div class="control">
                                        <label class="tittle-of-label"><strong>Ubicación</strong></label>
                                        <input 
                                                class="input" 
                                                type="text" 
                                                name="categoria_ubicacion" 
                                                pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}" 
                                                maxlength="150" 
                                        >
                                </div>
                        </div>
                </div> -->

                <div class="columns">
                        <div class="column">
                                <div class="control">
                                        <label class="tittle-of-label"><strong>Nombre</strong></label>
                                        <input 
                                                class="input" 
                                                type="text" 
                                                name="categoria_nombre" 
                                                pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}" 
                                                value="<?php echo $data['categoria_nombre'] ?>" 
                                                maxlength="50" 
                                                required 
                                        >
                                </div>
                        </div>
                        <div class="column">
                                <div class="control">
                                        <label class="tittle-of-label"><strong>Ubicación</strong></label>
                                        <input 
                                                class="input" 
                                                type="text" 
                                                name="categoria_ubicacion" 
                                                pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}" 
                                                value="<?php echo $data['categoria_ubicacion'] ?>" 
                                                maxlength="150" 
                                        >
                                </div>
                        </div>
                </div>
		
                <br><br>
		<p class="has-text-centered">
			Para poder actualizar los datos de este usuario por favor ingrese su USUARIO y CLAVE con la que ha iniciado sesión
		</p>
                <div class="columns">
                        <div class="column">
                                <div class="control">
                                        <label>Usuario</label>
                                        <input 
                                                class="input" 
                                                type="text" 
                                                name="administrator_user" 
                                                pattern="[a-zA-Z0-9]{4,20}" 
                                                maxlength="20" required 
                                        >
                                </div>
                        </div>
                        <div class="column">
                                <div class="control">
                                        <label>Clave</label>
                                        <input 
                                                class="input" 
                                                type="password" 
                                                name="administrator_key" 
                                                pattern="[a-zA-Z0-9$@.-]{7,100}" 
                                                maxlength="100" 
                                                required 
                                        >
                                </div>
                        </div>
                </div>

                <p class="has-text-centered">
			<button type="submit" class="button is-success is-rounded">Actualizar</button>
		</p>
        </form>

        <?php
        } else {
                include './inc/error_alert.php';
                $db = null;
        }
        ?>

</div>
