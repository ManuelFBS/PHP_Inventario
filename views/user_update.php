<?php

require_once './php/main.php';

/*
 * El ID de usuario (user_id_up) se obtiene de la URL como una cadena de texto (por ejemplo, "14").
 * Lo convertimos a entero para la lógica de la base de datos: un texto no válido se convierte en 0,
 * lo cual es correcto para "ningún usuario"...
 */
$id = isset($_GET['user_id_up']) ? (int) $_GET['user_id_up'] : 0;

?>

<div class="container is-fluid mb-6">
        <?php if ($id == $_SESSION['id']) { ?>
                <h1 class="title">Mi cuenta</h1>
                <h2 class="subtitle">Actualizar datos de mi cuenta</h2>
        <?php } else { ?>
                <h1 class="title">Usuarios</h1>
                <h2 class="subtitle">Actualizar usuario</h2>
        <?php } ?>
</div>

<div class="container pb-6 pt-6">
        <?php
        if ($id == $_SESSION['id']) {
                // * Llegaste desde "Mi cuenta" en el menú
                $btn_back_url = 'index.php?view=home';
                $btn_back_label = 'Regresar al inicio';
        } else {
                $return_page = isset($_GET['from_page']) ? (int) $_GET['from_page'] : 1;
                if ($return_page <= 0) {
                        $return_page = 1;
                }
                $btn_back_url = 'index.php?view=user_list&page=' . $return_page;
                $btn_back_label = 'Regresar al listado de usuarios';
        }

        include './php/btn_back.php';

        $db = connect();
        $check_user = $db->prepare('SELECT * FROM usuario WHERE usuario_id = ?');
        $check_user->execute([$id]);

        if ($check_user->rowCount() > 0) {
                $data = $check_user->fetch();
                ?>

        <div class="form-rest mb-6 mt-6"></div>

        <form 
                action="./php/user_update_save.php" 
                method="POST" 
                class="FormAjax" 
                autocomplete="off" 
        >
                <input 
                        type="hidden" 
                        value="<?php echo $data['usuario_id'] ?>" 
                        name="usuario_id" 
                        required 
                >
		
		<div class="columns">
                        <div class="column">
                                <div class="control">
                                        <label>Nombres</label>
                                        <input 
                                                class="input" 
                                                type="text" 
                                                name="usuario_nombre" 
                                                value="<?php echo $data['usuario_nombre'] ?>" 
                                                pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" 
                                                maxlength="40" required 
                                        >
                                </div>
                        </div>
                        <div class="column">
                                <div class="control">
                                        <label>Apellidos</label>
                                        <input 
                                                class="input" 
                                                type="text" 
                                                name="usuario_apellido" 
                                                pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" 
                                                value="<?php echo $data['usuario_apellido'] ?>" 
                                                maxlength="40" required 
                                        >
                                </div>
                        </div>
                </div>

                <div class="columns">
                        <div class="column">
                                <div class="control">
                                        <label>Usuario</label>
                                        <input 
                                                class="input" 
                                                type="text" 
                                                name="usuario_usuario" 
                                                pattern="[a-zA-Z0-9]{4,20}" 
                                                value="<?php echo $data['usuario_usuario'] ?>" 
                                                maxlength="20" 
                                                required 
                                        >
                                </div>
                        </div>
                        <div class="column">
                                <div class="control">
                                        <label>Email</label>
                                        <input 
                                                class="input" 
                                                type="email" 
                                                name="usuario_email" 
                                                value="<?php echo $data['usuario_email'] ?>" 
                                                maxlength="70" 
                                        >
                                </div>
                        </div>
                </div>

                <br><br>
		<p class="has-text-centered">
			SI desea actualizar la clave de este usuario por favor llene los 2 campos. Si NO desea actualizar la clave deje los campos vacíos.
		</p>		
                <br>

                <div class="columns">
                        <div class="column">
                                <div class="control">
                                        <label>Clave</label>
                                        <input 
                                                class="input" 
                                                type="password" 
                                                name="usuario_clave_1" 
                                                pattern="[a-zA-Z0-9$@.-]{7,100}" 
                                                maxlength="100" 
                                        >
                                </div>
                        </div>
                        <div class="column">
                                <div class="control">
                                        <label>Repetir Clave</label>
                                        <input 
                                                class="input" 
                                                type="password" 
                                                name="usuario_clave_2" 
                                                pattern="[a-zA-Z0-9$@.-]{7,100}" 
                                                maxlength="100" 
                                        >
                                </div>
                        </div>
                </div>

                <br><br><br>
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
