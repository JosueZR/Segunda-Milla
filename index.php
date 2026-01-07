<?php 
session_start(); 
include("php/includes/conexion.php"); 

// Función para obtener texto (adaptada para el index)
if (!function_exists('obtenerTexto')) {
    function obtenerTexto($conn, $seccion, $clave) {
        $query = "SELECT contenido FROM textos WHERE seccion = '$seccion' AND clave = '$clave'";
        $res = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($res);
        return $data ? $data['contenido'] : "Texto no configurado...";
    }
}

// Función para botón de editar (Ruta ajustada para la raíz)
function botonEditarIndex($seccion) {
    if (isset($_SESSION['admin_id'])) {
        echo "<a href='php/admin/subir_recursos.php?seccion=$seccion' 
                 style='background:#e74c3c; color:white; padding:5px 10px; border-radius:5px; text-decoration:none; font-size:12px; position:absolute; z-index:999; top:10px; left:10px;'>
                 [ EDITAR ]
              </a>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iglesia Segunda Milla</title>
    <link rel="stylesheet" href="public/css/main.css" />
</head>
<body>
    <header class="navbar">
        <div class="navbar__logo">
            <img src="public/images/logo.png" alt="Centro de Vida Cristiana" />
        </div>

        <nav class="navbar__menu">
            <ul>
                <li><a href="#">Inicio</a></li>
                <li><a href="public/que_hacemos.php">¿Qué hacemos?</a></li>
                <li><a href="public/nosotros.php">Nosotros</a></li>
                <li><a href="public/donde_estamos.php">¿Dónde estamos?</a></li>
                <li><a href="public/recursos.php">Recursos</a></li>
                <li><a href="public/noticias.php">Noticias</a></li>
                <li><a href="public/eventos.php">Eventos</a></li>
                <li><a href="public/contactanos.php">Contáctanos</a></li>
                <?php if(isset($_SESSION['admin_id'])): ?>
                    <li style="margin-left: 15px;">
                        <a href="public/admin/admin.php" 
                            style="background-color: #2c3e50; color: white; padding: 8px 15px; border-radius: 5px; font-weight: bold; border: 1px solid #34495e;">
                            Panel Admin
                        </a>
                    </li>
                <?php else: ?>
                    <li><a href="public/admin/login.html">Admin</a></li>
                <?php endif; ?>
            </ul>
        </nav>

    </header>

    <section class="hero">
        <div style="position:absolute; top:120px; left:20px;">
            <?php botonEditarIndex('inicio_hero'); ?>
        </div>
        
        <div class="hero__content">
            <h1 class="hero__title"><?php echo obtenerTexto($conn, 'inicio', 'titulo_hero'); ?></h1>
            <p class="hero__subtitle"><?php echo obtenerTexto($conn, 'inicio', 'subtitulo_hero'); ?></p>
        </div>

        <div class="hero__organization">
            <h2>IGLESIA SEGUNDA MILLA</h2>
            <p class="hero__countries"><?php echo obtenerTexto($conn, 'inicio', 'ubicacion_hero'); ?></p>
        </div>
    </section>

    <section class="welcome" style="position:relative;">
        <?php botonEditarIndex('inicio_bienvenida'); ?>
        
        <h2 class="welcome__title"><?php echo obtenerTexto($conn, 'inicio', 'titulo_bienvenida'); ?></h2>
        <div class="welcome__content">
            <p class="welcome__text">
                <?php echo obtenerTexto($conn, 'inicio', 'texto_bienvenida'); ?>
            </p>
        </div>
        <a href="public/nosotros.php" class="btn-conocer">CONOCE MÁS ACERCA DE NOSOTROS</a>
    </section>

</body>
</html>