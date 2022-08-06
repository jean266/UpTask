<?php include_once __DIR__ . '/header-dashboard.php'; ?>

    <?php if(count($proyectos) === 0): ?>
        <p class="no_proyectos">No hay Proyectos Aún <a href="/crear-proyecto">Comienza Creando uno</a></p>
    <?php else: ?>
        <ul class="listado_proyectos">
            <?php foreach($proyectos as $proyecto): ?>
                <li class="proyecto">
                    <a href="proyecto?id=<?php echo $proyecto->url; ?>"><?php echo $proyecto->proyecto; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>