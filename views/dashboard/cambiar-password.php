<?php include_once __DIR__ . '/header-dashboard.php'; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

    <a href="/perfil" class="enlace">Volver a perfil</a>

    <form method="POST" class="formulario"  action="/cambiar-password">

        <div class="campo">
            <label for="password_actual">Password Actual</label>
            <input 
                type="password" 
                name="password_actual"
                id="password_actual"
                placeholder="Tu Password Actual"
            />
        </div>

        <div class="campo">
        <label for="password_nuevo">Password Nuevo</label>
            <input 
                type="password" 
                name="password_nuevo" 
                id="password_nuevo"
                placeholder="Tu Password Nuevo"
            />
        </div>

        <div class="campo">
        <label for="repetir_password">Repite el Password</label>
            <input 
                type="password" 
                name="repetir_password" 
                id="repetir_password"
                placeholder="Repite el Password"
            />
        </div>

        <input type="submit" value="Guardar Password">

    </form>
</div>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>