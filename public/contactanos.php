<?php 
session_start(); 
include("../php/includes/conexion.php"); 
include("includes/funciones_edicion.php");
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
    <?php include("includes/load_editor.php"); ?>

    <header class="encabezado">
        <?php editable($conn, 'contactanos', 'titulo_principal', 'h1'); ?>
        <div style="display:inline-block;">
            <?php editable($conn, 'contactanos', 'subtitulo_header', 'p'); ?>
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
                <div class="info-card">
                    <h4>📍 Ubicación</h4>
                    <?php editable($conn, 'contactanos', 'direccion', 'p'); ?>
                </div>
                
                <div class="info-card">
                    <h4>📞 Teléfonos</h4>
                    <?php editable($conn, 'contactanos', 'telefonos', 'p'); ?>
                </div>

                <div class="info-card">
                    <h4>📧 Correo Electrónico</h4>
                    <?php editable($conn, 'contactanos', 'emails', 'p'); ?>
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