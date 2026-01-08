<?php
// 1. Incluimos el sistema de permisos
// Usamos __DIR__ para asegurar la ruta correcta desde "public/includes/"
include_once(__DIR__ . '/../../php/includes/permisos.php');

// 2. Función auxiliar para mostrar los enlaces
function renderLink($texto, $url, $seccion_clave) {
    // Por defecto (visitante normal), el enlace está activo
    $acceso = true;
    $estilo = "";
    $icono = "";

    // Si hay un ADMIN logueado, verificamos sus permisos
    if (isset($_SESSION['admin_id'])) {
        if (!tiene_permiso($seccion_clave)) {
            $acceso = false;
            // Estilo visual de bloqueado
            $estilo = "cursor: not-allowed; opacity: 0.5; pointer-events: none; color: #aaa;";
            $icono = "🔒 ";
        }
    }

    // Renderizamos el <li>
    if ($acceso) {
        // Enlace normal
        echo "<li><a href='$url'>$texto</a></li>";
    } else {
        // Enlace bloqueado
        echo "<li><a href='#' style='$estilo'>$icono$texto</a></li>";
    }
}
?>

<header class="navbar">
    <div class="navbar__logo">
        <img src="images/logo.png" alt="Segunda Milla" />
    </div>
    <nav class="navbar__menu">
        <ul>
            <?php renderLink('Inicio', '../index.php', 'inicio'); ?>
            <?php renderLink('¿Qué hacemos?', 'que_hacemos.php', 'que_hacemos'); ?>
            <?php renderLink('Nosotros', 'nosotros.php', 'nosotros'); ?>
            <?php renderLink('¿Dónde estamos?', 'donde_estamos.php', 'donde_estamos'); ?>
            <?php renderLink('Recursos', 'recursos.php', 'recursos'); ?>
            <?php renderLink('Noticias', 'noticias.php', 'noticias'); ?>
            <?php renderLink('Eventos', 'eventos.php', 'eventos'); ?>
            <?php renderLink('Contáctanos', 'contactanos.php', 'contactanos'); ?>

            <?php if(isset($_SESSION['admin_id'])): ?>
                <li style="margin-left: 15px;">
                    <a href="admin/admin.php" 
                       style="background-color: #2c3e50; color: white; padding: 8px 15px; border-radius: 5px; font-weight: bold; border: 1px solid #34495e;">
                        Panel Admin
                    </a>
                </li>
            <?php endif; ?>
            </ul>
    </nav>
</header>