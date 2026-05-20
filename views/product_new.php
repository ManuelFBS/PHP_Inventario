<div class="container is-fluid mb-6">
	<h1 class="title">Productos</h1>
	<h2 class="subtitle">Nuevo producto</h2>
</div>

<div class="container pb-6 pt-6">
        <?php
        require_once './php/main.php';
        ?>
        <div class="form-rest mb-6 mt-6"></div>

        <form 
                action="./php/product_save.php" 
                method="POST" 
                 class="FormAjax" 
                 autocomplete="off" 
                 enctype="multipart/form-data"
        >
                <div class="columns">
		  	<div class="column">
                                <div class="control">
                                        <label class="tittle-of-label">Código de barra</label>
                                        <input 
                                                class="input" 
                                                type="text" 
                                                name="producto_codigo" 
                                                pattern="[a-zA-Z0-9- ]{1,70}" 
                                                maxlength="70" 
                                                required 
                                        >
                                </div>
                        </div>
		  	<div class="column">
		    	        <div class="control">
					<label class="tittle-of-label">Nombre</label>
				  	<input 
                                                class="input" 
                                                type="text" 
                                                name="producto_nombre" 
                                                pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" 
                                                maxlength="70" 
                                                required 
                                        >
				</div>
		  	</div>
		</div>

                <div class="columns">
		  	<div class="column">
                                <div class="control">
                                                <label class="tittle-of-label">Precio</label>
                                                <input class="input" type="text" name="producto_precio" pattern="[0-9.]{1,25}" maxlength="25" required >
                                        </div>
                                </div>
		  	<div class="column">
                                <div class="control">
                                                <label class="tittle-of-label">Stock</label>
                                                <input class="input" type="text" name="producto_stock" pattern="[0-9]{1,25}" maxlength="25" required >
                                        </div>
                                </div>
		  	<div class="column">
				<label class="tittle-of-label">Categoría</label><br>
		    	        <div class="select is-rounded">
				  	<select name="producto_categoria" >
				    	        <option value="" selected="" >Seleccione una opción</option>
				    	        <option value="1" >Nombre</option>
                                                <?php
                                                $db = connect();
                                                $category = $db->query('SELECT * FROM categoria');
                                                if ($category->rowCount() > 0) {
                                                        $category = $category->fetchAll();
                                                        foreach ($category as $row) {
                                                                echo '<option value="'
                                                                        . $row['categoria_id'] . '">'
                                                                        . $row['categoria_nombre']
                                                                        . '</option>';
                                                        }
                                                }
                                                $db = null;
                                                ?>
				  	</select>
				</div>
		  	</div>
		</div>

                <div class="columns">
			<div class="column">
				<label class="tittle-of-label">Foto o imagen del producto</label><br>
				<div class="file is-small has-name">
				  	<label class="file-label">
                                                <input class="file-input" type="file" name="producto_foto" accept=".jpg, .png, .jpeg" >
                                                <span class="file-cta">
                                                        <span class="file-label">Imagen</span>
                                                </span>
                                                <span class="file-name">JPG, JPEG, PNG. (MAX 3MB)</span>
				  	</label>
				</div>
			</div>
		</div>
		<p class="has-text-centered">
			<button type="submit" class="button is-info is-rounded">Guardar</button>
		</p>
        </form>
</div>