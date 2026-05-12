<?php

require_once './php/main.php';

$id = (isset($_GET['user_id_up'])) ? $_GET['user_id_up'] : 0;

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
        <p class="has-text-right pt-4 pb-4">
		<a href="#" class="button is-link is-rounded btn-back"><- Regresar atrás</a>
	</p>
	<script type="text/javascript">
                let btn_back = document.querySelector(".btn-back");

                btn_back.addEventListener('click', function(e){
                        e.preventDefault();
                        window.history.back();
                });
	</script>

        <div class="form-rest mb-6 mt-6"></div>

        <form action="" method="POST" class="FormularioAjax" autocomplete="off" >
                <input type="hidden" name="usuario_id" required >
		
		<div class="columns">
                        <div class="column">
                                <div class="control">
                                        <label>Nombres</label>
                                        <input 
                                                class="input" 
                                                type="text" 
                                                name="usuario_nombre" 
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
                                                name="administrador_usuario" 
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
                                                name="administrador_clave" 
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

        <div class="notification is-danger is-light mb-6 mt-6">
	        <strong>¡Ocurrio un error inesperado!</strong><br>
	        No podemos obtener la información solicitada...!
	</div>
</div>
