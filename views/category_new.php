<div class="container is-fluid mb-6">
	<h1 class="title">Categorías</h1>
	<h2 class="subtitle">Nueva categoría</h2>
</div>

<div class="container pb-6 pt-6">
        <div class="form-rest mb-6 mt-6"></div>

        <form action="./php/category_save.php" method="POST" class="FormAjax" autocomplete="off">
                <div class="columns">
                        <div class="column">
                                <div class="control">
                                        <label class="tittle-of-label"><strong>Nombre (*)</strong></label>
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
                </div>

                <p class="has-text-centered">
                        <button type="submit" class="button is-info is-rounded">Guardar</button>
                </p>
        </form>
</div>