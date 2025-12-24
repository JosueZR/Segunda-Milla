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
    <title>Eventos - Segunda Milla</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <?php include("includes/navbar.php"); ?>

    <header class="encabezado" style="position:relative;">
        <?php botonEditar('eventos', 'titulo_principal'); ?>
        <h1><?php echo obtenerTexto($conn, 'eventos', 'titulo_principal'); ?></h1>
        
        <div style="position:relative; display:inline-block;">
            <?php botonEditar('eventos', 'subtitulo_header'); ?>
            <p><?php echo obtenerTexto($conn, 'eventos', 'subtitulo_header'); ?></p>
        </div>
    </header>

    <section class="contenido">
        <div class="seccion-ministerios">
            
            <div style="position:relative;">
                <?php botonEditar('eventos', 'titulo_seccion'); ?>
                <h2 class="titulo-seccion"><?php echo obtenerTexto($conn, 'eventos', 'titulo_seccion'); ?></h2>
            </div>
            
            <div class="nosotros-contenido" style="position:relative;">
                <?php botonEditar('eventos', 'contenido_principal'); ?>
                <p>
                    <?php echo obtenerTexto($conn, 'eventos', 'contenido_principal'); ?>
                </p>
            </div>

        </div>
    </section>

</body>
</html>