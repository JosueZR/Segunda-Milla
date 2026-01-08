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
    <title>¿Qué Hacemos? - Segunda Milla</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <?php include("includes/navbar.php"); ?>
    <?php include("includes/load_editor.php"); ?>

    <header class="encabezado">
        <?php editable($conn, 'que_hacemos', 'titulo_principal', 'h1'); ?>
        <div style="margin-top:10px;">
             <?php editable($conn, 'que_hacemos', 'subtitulo_principal', 'p'); ?>
        </div>
    </header>

    <section class="contenido">
        <div style="margin-bottom: 40px;">
            <?php editable($conn, 'que_hacemos', 'intro_texto', 'p'); ?>
        </div>

        <div class="seccion-ministerios">
            <?php editable($conn, 'que_hacemos', 'titulo_areas', 'h2', 'titulo-seccion'); ?>
            
            <div class="tarjetas">
                <div class="tarjeta">
                    <div class="tarjeta-imagen">
                        <?php editableImagen($conn, 'que_hacemos', 'img_varones', 'images/varones.jpg', 'Ministerio de Varones'); ?>
                    </div>
                    <h3>Varones</h3>
                    <?php editable($conn, 'que_hacemos', 'desc_varones', 'p'); ?>
                </div>

                <div class="tarjeta">
                    <div class="tarjeta-imagen">
                        <?php editableImagen($conn, 'que_hacemos', 'img_damas', 'images/varones.jpg', 'Ministerio de Damas'); ?>
                    </div>
                    <h3>Damas</h3>
                    <?php editable($conn, 'que_hacemos', 'desc_damas', 'p'); ?>
                </div>

                <div class="tarjeta">
                    <div class="tarjeta-imagen">
                        <?php editableImagen($conn, 'que_hacemos', 'img_juvenil', 'images/varones.jpg', 'Ministerio Juvenil'); ?>
                    </div>
                    <h3>Juvenil</h3>
                    <?php editable($conn, 'que_hacemos', 'desc_juvenil', 'p'); ?>
                </div>

                <div class="tarjeta">
                    <div class="tarjeta-imagen">
                        <?php editableImagen($conn, 'que_hacemos', 'img_ninos', 'images/varones.jpg', 'Ministerio de Niños'); ?>
                    </div>
                    <h3>Niños</h3>
                    <?php editable($conn, 'que_hacemos', 'desc_ninos', 'p'); ?>
                </div>
            </div>
        </div>

        <div class="seccion-ministerios">
            <div>
                <?php editable($conn, 'que_hacemos', 'titulo_reuniones', 'h2', 'titulo-seccion'); ?>
                <?php editable($conn, 'que_hacemos', 'desc_reuniones', 'p', 'descripcion-seccion'); ?>
            </div>

            <div class="lista-reuniones">
                <div class="reunion-item">
                    <h4>📅 Servicio Dominical</h4>
                    <?php editable($conn, 'que_hacemos', 'horario_domingo', 'p'); ?>
                </div>

                <div class="reunion-item">
                    <h4>🕊️ Servicio entre Semana</h4>
                    <?php editable($conn, 'que_hacemos', 'horario_miercoles', 'p'); ?>
                </div>

                <div class="reunion-item">
                    <h4>🙏 Reunión de Oración</h4>
                    <?php editable($conn, 'que_hacemos', 'horario_oracion', 'p'); ?>
                </div>

                <div class="reunion-item">
                    <h4>📖 Instituto Bíblico</h4>
                    <?php editable($conn, 'que_hacemos', 'horario_instituto', 'p'); ?>
                </div>
            </div>
        </div>
    </section>
</body>
</html>