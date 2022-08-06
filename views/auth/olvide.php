<div class="contenedor olvide">

<?php require_once __DIR__ . '/../templates/nombre_sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu Acceso a UpTask</p>

        <?php require_once __DIR__ . '/../templates/alertas.php'; ?>

        <form action="/olvide" method="POST" class="formulario">
            
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Tu Email"
                    name="email"
                />
            </div>

            <input type="submit" class="boton" value="Enviar Instruciones">

            <div class="acciones">
                <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
                <a href="/crear">¿Aun no tines una cuenta? Obtener una</a>
            </div>
        </form>
    </div> <!-- .contenedor-sm -->
</div>