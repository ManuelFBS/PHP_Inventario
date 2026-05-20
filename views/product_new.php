<div class="container is-fluid mb-6">
	<h1 class="title">Productos</h1>
	<h2 class="subtitle">Nuevo producto</h2>
</div>

<div class="container pb-6 pt-6">
        <div class="form-rest mb-6 mt-6"></div>

        <form action="./php/product_save.php" method="POST"  class="FormAjax" autocomplete="off">
                <div class="columns">
		  	<div class="column">
                                <div class="control">
                                        <label class="tittle-of-label">Código</label>
                                        <input class="input" type="text" name="producto_codigo" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
                                </div>
                        </div>
		  	<div class="column">
		    	        <div class="control">
					<label class="tittle-of-label">Nombre</label>
				  	<input class="input" type="text" name="producto_nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required >
				</div>
		  	</div>
		</div>
        </form>
</div>