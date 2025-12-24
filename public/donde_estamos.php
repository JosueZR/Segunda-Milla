<?php 
session_start(); 
include("../php/includes/conexion.php"); 
include("includes/funciones_edicion.php");

// Verificamos si la función existe para evitar errores
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
    <title>¿Dónde Estamos? - Segunda Milla</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        /* Estilos auxiliares para posicionar los botones de edición */
        .editable-container { position: relative; }
    </style>
</head>
<body>
    <?php include("includes/navbar.php"); ?>

    <header class="encabezado" style="position:relative;">
        <?php botonEditar('donde_estamos', 'titulo_principal'); ?>
        <h1><?php echo obtenerTexto($conn, 'donde_estamos', 'titulo_principal'); ?></h1>
        
        <div style="display:inline-block; position:relative;">
             <?php botonEditar('donde_estamos', 'subtitulo_header'); ?>
             <p><?php echo obtenerTexto($conn, 'donde_estamos', 'subtitulo_header'); ?></p>
        </div>
    </header>

    <section class="seccion-ubicacion">
        <div class="imagen-iglesia-principal">
            <img src="images/iglesia.jpg" alt="Fachada de Segunda Milla">
            
            <div class="imagen-descripcion" style="position:relative;">
                <?php botonEditar('donde_estamos', 'direccion_texto'); ?>
                
                <h3><?php echo obtenerTexto($conn, 'donde_estamos', 'titulo_ubicacion'); ?></h3>
                <p><?php echo obtenerTexto($conn, 'donde_estamos', 'direccion_texto'); ?></p>
            </div>
        </div>

        <div class="horarios-servicios" style="position:relative;">
            <?php botonEditar('donde_estamos', 'titulo_horarios'); ?>
            <h3><?php echo obtenerTexto($conn, 'donde_estamos', 'titulo_horarios'); ?></h3>
            
            <div class="horarios-grid">
                
                <div class="horario-item" style="position:relative;">
                    <?php botonEditar('donde_estamos', 'horario_domingo'); ?>
                    <strong>Domingo</strong>
                    <p><?php echo obtenerTexto($conn, 'donde_estamos', 'horario_domingo'); ?></p>
                </div>

                <div class="horario-item" style="position:relative;">
                    <?php botonEditar('donde_estamos', 'horario_miercoles'); ?>
                    <strong>Miércoles</strong>
                    <p><?php echo obtenerTexto($conn, 'donde_estamos', 'horario_miercoles'); ?></p>
                </div>

                <div class="horario-item" style="position:relative;">
                    <?php botonEditar('donde_estamos', 'horario_viernes'); ?>
                    <strong>Viernes</strong>
                    <p><?php echo obtenerTexto($conn, 'donde_estamos', 'horario_viernes'); ?></p>
                </div>

                <div class="horario-item" style="position:relative;">
                    <?php botonEditar('donde_estamos', 'horario_sabado'); ?>
                    <strong>Sábado</strong>
                    <p><?php echo obtenerTexto($conn, 'donde_estamos', 'horario_sabado'); ?></p>
                </div>
            </div>
        </div>

        <div class="mapa-container" style="position:relative;">
            <?php botonEditar('donde_estamos', 'url_mapa'); ?>
            
            <iframe 
                src="<?php echo obtenerTexto($conn, 'donde_estamos', 'url_mapa'); ?>" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

        <div class="seccion-ministerios" style="margin-top: 50px; position:relative;">
            
            <div style="position:relative;">
                <?php botonEditar('donde_estamos', 'descripcion_llegar'); ?>
                <h2 class="titulo-seccion"><?php echo obtenerTexto($conn, 'donde_estamos', 'titulo_llegar'); ?></h2>
                <div class="nosotros-contenido">
                    <p>
                        <?php echo obtenerTexto($conn, 'donde_estamos', 'descripcion_llegar'); ?>
                    </p>
                </div>
            </div>

            <div class="valores-lista" style="position:relative; margin-top:20px;">
                <?php botonEditar('donde_estamos', 'info_transporte'); ?>
                <?php echo obtenerTexto($conn, 'donde_estamos', 'info_transporte'); ?>
            </div>

        </div>
    </section>

</body>
</html>