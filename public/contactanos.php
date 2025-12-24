<?php 
session_start(); 
include("../php/includes/conexion.php"); 
include("includes/funciones_edicion.php");

// Función auxiliar para traer texto
if (!function_exists('obtenerTexto')) {
    function obtenerTexto($conn, $seccion, $clave) {
        $query = "SELECT contenido FROM textos WHERE seccion = '$seccion' AND clave = '$clave'";
        $res = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($res);
        return $data ? $data['contenido'] : "Texto no configurado...";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contáctanos - Segunda Milla</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/pages/contactanos.css">
</head>
<body class="page-contacto">
    
    <?php include("includes/navbar.php"); ?>

    <header class="encabezado" style="position:relative;">
        <?php botonEditar('contactanos', 'titulo_principal'); ?>
        <h1><?php echo obtenerTexto($conn, 'contactanos', 'titulo_principal'); ?></h1>
        
        <div style="position:relative; display:inline-block;">
            <?php botonEditar('contactanos', 'subtitulo_header'); ?>
            <p><?php echo obtenerTexto($conn, 'contactanos', 'subtitulo_header'); ?></p>
        </div>
    </header>

    <main class="contenido">
        <div class="contacto-container">
            
            <div class="contacto-form">
                <h3>Envíanos un mensaje</h3>
                <form action="#" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre Completo</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Tu nombre..." required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" id="email" name="email" placeholder="tu@correo.com" required>
                    </div>
                    <div class="form-group">
                        <label for="asunto">Asunto</label>
                        <select id="asunto" name="asunto">
                            <option value="informacion">Información General</option>
                            <option value="oracion">Petición de Oración</option>
                            <option value="ministerio">Interés en un Ministerio</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mensaje">Mensaje</label>
                        <textarea id="mensaje" name="mensaje" rows="5" placeholder="¿En qué podemos ayudarte?" required></textarea>
                    </div>
                    <button type="submit" class="btn-enviar">Enviar Mensaje</button>
                </form>
            </div>

            <div class="contacto-info">
                
                <div class="info-card" style="position:relative;">
                    <?php botonEditar('contactanos', 'direccion'); ?>
                    <h4>📍 Ubicación</h4>
                    <p><?php echo obtenerTexto($conn, 'contactanos', 'direccion'); ?></p>
                </div>
                
                <div class="info-card" style="position:relative;">
                    <?php botonEditar('contactanos', 'telefonos'); ?>
                    <h4>📞 Teléfonos</h4>
                    <p><?php echo obtenerTexto($conn, 'contactanos', 'telefonos'); ?></p>
                </div>

                <div class="info-card" style="position:relative;">
                    <?php botonEditar('contactanos', 'emails'); ?>
                    <h4>📧 Correo Electrónico</h4>
                    <p><?php echo obtenerTexto($conn, 'contactanos', 'emails'); ?></p>
                </div>

                <div class="redes-sociales">
                    <h4>Síguenos</h4>
                    <div class="redes-links">
                        <a href="#"><img src="images/facebook.webp" alt="Facebook"></a>
                        <a href="#"><img src="images/instagram.png" alt="Instagram"></a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>