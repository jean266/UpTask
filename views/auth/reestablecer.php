<div class="contenedor reestablecer">

<?php require_once __DIR__ . '/../templates/nombre_sitio.php'; ?>

<div class="contenedor-sm">
    <p class="descripcion-pagina">Coloca tu nuevo Password</p>

    <?php require_once __DIR__ . '/../templates/alertas.php'; ?>
    
    <?php if($mostrar): ?>

        <form method="POST" class="formulario">
            
            <div class="campo">
                <label for="password">Password</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Tu Password"
                    name="password"
                />
            </div>

            <div class="campo">
                <label for="password2">Repite Password</label>
                <input 
                    type="password"
                    id="password2"
                    placeholder="Repite tu Password"
                    name="password2"
                />
            </div>

            <input type="submit" class="boton" value="Guardar Password">

        </form>
        
        <div class="acciones">
            <a href="/crear">¿Aun no tines una cuenta? Obtener una</a>
            <a href="/olvide">¿Olvidaste tu password?</a>
        </div>
        <?php endif; ?>
    </div> <!-- .contenedor-sm -->
</div>