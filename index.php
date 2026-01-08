<?php 
session_start(); 
include("php/includes/conexion.php"); 
include("public/includes/funciones_edicion.php"); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iglesia Segunda Milla</title>
    <link rel="stylesheet" href="public/css/main.css" />
    
    <style>
        .hero-bg-img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Para que cubra todo el fondo sin deformarse */
            filter: brightness(0.6); /* Oscurecer un poco para leer el texto */
        }
    </style>
</head>
<body>
    
    <?php if (isset($_SESSION['admin_id'])): ?>
        <?php
            $apiKey = 'no-api-key';
            $envPath = __DIR__ . '/.env';
            if (file_exists($envPath)) {
                $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos(trim($line), '#') === 0) continue;
                    list($name, $value) = explode('=', $line, 2);
                    if (trim($name) == 'TINYMCE_API_KEY') $apiKey = trim($value);
                }
            }
        ?>
        <script src="https://cdn.tiny.cloud/1/<?php echo $apiKey; ?>/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
        
        <script src="public/js/edicion-imagenes.js"></script>
        
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (document.querySelectorAll('.texto-editable').length > 0) {
                tinymce.init({
                    selector: '.texto-editable',
                    inline: true,
                    menubar: false,
                    plugins: 'save link lists',
                    toolbar: 'save | bold italic | alignleft aligncenter alignright',
                    save_onsavecallback: function () {
                        var editor = this;
                        // Ajuste de ruta AJAX para la raíz
                        fetch('php/admin/guardar_inline.php', {
                            method: 'POST',
                            headers: {'Content-Type': 'application/json'},
                            body: JSON.stringify({ 
                                seccion: editor.getElement().getAttribute('data-seccion'), 
                                clave: editor.getElement().getAttribute('data-clave'), 
                                contenido: editor.getContent() 
                            })
                        })
                        .then(r => r.json())
                        .then(d => {
                            if(d.status==='success') { editor.getElement().style.outline="2px solid #2ecc71"; setTimeout(()=>editor.getElement().style.outline="none",2000); }
                            else { alert('Error: '+d.message); }
                        });
                    }
                });
            }
        });
        </script>
        <style>
            .texto-editable:hover { outline: 2px dashed #3498db; cursor: text; }
            .imagen-editable:hover { outline: 4px solid #e74c3c; cursor: pointer; opacity: 0.9; }
        </style>
    <?php endif; ?>
    <header class="navbar">
        <div class="navbar__logo">
            <?php editableImagen($conn, 'inicio', 'logo_nav', 'public/images/logo.png', 'Logo Iglesia', '', 'max-height: 60px;'); ?>
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
                    <li><a href="public/admin/admin.html" style="color:#ffeb3b;">Panel</a></li>
                <?php else: ?>
                    <li><a href="public/admin/login.html">Admin</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <section class="hero" style="position: relative;">
        
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;">
            <?php 
            // Usamos la función editableImagen. 
            // Nota: 'public/images/referancia.jpeg' es la imagen por defecto si no has subido ninguna.
            editableImagen(
                $conn, 
                'inicio', 
                'hero_bg', 
                'public/images/referancia.jpeg', 
                'Fondo Principal', 
                'hero-bg-img' // Esta clase aplica los estilos CSS que creamos arriba
            ); 
            ?>
        </div>

        <div class="hero__content">
            <?php editable($conn, 'inicio', 'titulo_hero', 'h1', 'hero__title'); ?>
            
            <?php editable($conn, 'inicio', 'subtitulo_hero', 'p', 'hero__subtitle'); ?>
        </div>

        <div class="hero__organization">
            <h2>IGLESIA SEGUNDA MILLA</h2>
            <?php editable($conn, 'inicio', 'ubicacion_hero', 'p', 'hero__countries'); ?>
        </div>

    </section>

    <section class="welcome">
        <?php editable($conn, 'inicio', 'titulo_bienvenida', 'h2', 'welcome__title'); ?>
        <div class="welcome__content">
            <?php editable($conn, 'inicio', 'texto_bienvenida', 'p', 'welcome__text'); ?>
        </div>
        <a href="public/nosotros.php" class="btn-conocer">CONOCE MÁS ACERCA DE NOSOTROS</a>
    </section>

</body>
</html>