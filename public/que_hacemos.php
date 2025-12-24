<?php 
session_start(); 
include("../php/includes/conexion.php"); 
include("includes/funciones_edicion.php");

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
    <title>¿Qué Hacemos? - Segunda Milla</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        /* Estilos para posicionar los botones en las tarjetas */
        .tarjeta, .reunion-item { position: relative; }
    </style>
</head>
<body>
    <?php include("includes/navbar.php"); ?>

    <header class="encabezado" style="position:relative;">
        <?php botonEditar('que_hacemos', 'titulo_principal'); ?>
        <h1><?php echo obtenerTexto($conn, 'que_hacemos', 'titulo_principal'); ?></h1>
        
        <div style="position:relative; display:inline-block;">
             <?php botonEditar('que_hacemos', 'subtitulo_principal'); ?>
             <p><?php echo obtenerTexto($conn, 'que_hacemos', 'subtitulo_principal'); ?></p>
        </div>
    </header>

    <section class="contenido">
        <div style="position:relative; margin-bottom: 40px;">
            <?php botonEditar('que_hacemos', 'intro_texto'); ?>
            <p>
                <?php echo obtenerTexto($conn, 'que_hacemos', 'intro_texto'); ?>
            </p>
        </div>

        <div class="seccion-ministerios">
            <div style="position:relative;">
                <?php botonEditar('que_hacemos', 'titulo_areas'); ?>
                <h2 class="titulo-seccion"><?php echo obtenerTexto($conn, 'que_hacemos', 'titulo_areas'); ?></h2>
            </div>
            
            <div class="tarjetas">
                <div class="tarjeta">
                    <?php botonEditar('que_hacemos', 'desc_varones'); ?>
                    <div class="tarjeta-imagen">
                        <img src="images/varones.jpg" alt="Ministerio">
                    </div>
                    <h3>Varones</h3>
                    <p><?php echo obtenerTexto($conn, 'que_hacemos', 'desc_varones'); ?></p>
                </div>

                <div class="tarjeta">
                    <?php botonEditar('que_hacemos', 'desc_damas'); ?>
                    <div class="tarjeta-imagen">
                        <img src="images/varones.jpg" alt="Ministerio">
                    </div>
                    <h3>Damas</h3>
                    <p><?php echo obtenerTexto($conn, 'que_hacemos', 'desc_damas'); ?></p>
                </div>

                <div class="tarjeta">
                    <?php botonEditar('que_hacemos', 'desc_juvenil'); ?>
                    <div class="tarjeta-imagen">
                        <img src="images/varones.jpg" alt="Ministerio">
                    </div>
                    <h3>Juvenil</h3>
                    <p><?php echo obtenerTexto($conn, 'que_hacemos', 'desc_juvenil'); ?></p>
                </div>

                <div class="tarjeta">
                    <?php botonEditar('que_hacemos', 'desc_ninos'); ?>
                    <div class="tarjeta-imagen">
                        <img src="images/varones.jpg" alt="Ministerio">
                    </div>
                    <h3>Niños</h3>
                    <p><?php echo obtenerTexto($conn, 'que_hacemos', 'desc_ninos'); ?></p>
                </div>
            </div>
        </div>

        <div class="seccion-ministerios">
            <div style="position:relative;">
                <?php botonEditar('que_hacemos', 'titulo_reuniones'); ?>
                <h2 class="titulo-seccion"><?php echo obtenerTexto($conn, 'que_hacemos', 'titulo_reuniones'); ?></h2>
                
                <div style="position:relative;">
                    <?php botonEditar('que_hacemos', 'desc_reuniones'); ?>
                    <p class="descripcion-seccion">
                        <?php echo obtenerTexto($conn, 'que_hacemos', 'desc_reuniones'); ?>
                    </p>
                </div>
            </div>

            <div class="lista-reuniones">
                <div class="reunion-item">
                    <?php botonEditar('que_hacemos', 'horario_domingo'); ?>
                    <h4>📅 Servicio Dominical</h4>
                    <p><?php echo obtenerTexto($conn, 'que_hacemos', 'horario_domingo'); ?></p>
                </div>

                <div class="reunion-item">
                    <?php botonEditar('que_hacemos', 'horario_miercoles'); ?>
                    <h4>🕊️ Servicio entre Semana</h4>
                    <p><?php echo obtenerTexto($conn, 'que_hacemos', 'horario_miercoles'); ?></p>
                </div>

                <div class="reunion-item">
                    <?php botonEditar('que_hacemos', 'horario_oracion'); ?>
                    <h4>🙏 Reunión de Oración</h4>
                    <p><?php echo obtenerTexto($conn, 'que_hacemos', 'horario_oracion'); ?></p>
                </div>

                <div class="reunion-item">
                    <?php botonEditar('que_hacemos', 'horario_instituto'); ?>
                    <h4>📖 Instituto Bíblico</h4>
                    <p><?php echo obtenerTexto($conn, 'que_hacemos', 'horario_instituto'); ?></p>
                </div>
            </div>
        </div>

    </section>
</body>
</html>