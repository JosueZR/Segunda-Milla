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
    <title>Nosotros - Segunda Milla</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        /* Ajuste para que los botones de edición se vean bien */
        .editable-wrapper { position: relative; display: inline-block; }
    </style>
</head>
<body>
    <?php include("includes/navbar.php"); ?>

    <header class="encabezado" style="position:relative;">
        <?php botonEditar('nosotros', 'titulo_principal'); ?>
        <h1><?php echo obtenerTexto($conn, 'nosotros', 'titulo_principal'); ?></h1>
        
        <div style="position:relative; display:inline-block;">
             <?php botonEditar('nosotros', 'subtitulo_principal'); ?>
             <p><?php echo obtenerTexto($conn, 'nosotros', 'subtitulo_principal'); ?></p>
        </div>
    </header>

    <section>
        <div class="seccion-ministerios" style="position:relative;">
            
            <div style="position:relative;">
                <?php botonEditar('nosotros', 'titulo_seccion_1'); ?>
                <h2 class="titulo-seccion"><?php echo obtenerTexto($conn, 'nosotros', 'titulo_seccion_1'); ?></h2>
            </div>

            <div class="nosotros-contenido">
                <div style="position:relative;">
                    <?php botonEditar('nosotros', 'contenido_intro'); ?>
                    <p>
                        <?php echo obtenerTexto($conn, 'nosotros', 'contenido_intro'); ?>
                    </p>
                </div>
                
                <div class="valores-lista">
                    <h4>📜 Nuestro Fundamento:</h4>
                    <ul>
                        <div style="position:relative;">
                             <?php botonEditar('nosotros', 'fundamento_texto'); ?>
                             <li><?php echo obtenerTexto($conn, 'nosotros', 'fundamento_texto'); ?></li>
                        </div>
                    </ul>
                </div>
            </div>
        </div>

        <div class="seccion-pastor" style="position:relative;">
            <?php botonEditar('nosotros', 'titulo_pastor'); ?>
            <h2 class="titulo-seccion"><?php echo obtenerTexto($conn, 'nosotros', 'titulo_pastor'); ?></h2>
            
            <div class="pastor-contenido">
                <div class="pastor-foto">
                    <img src="images/pastor.webp" alt="Pastor Principal">
                </div>
                <div class="pastor-info">
                    <div style="position:relative; display:inline-block;">
                        <?php botonEditar('nosotros', 'nombre_pastor'); ?>
                        <h3><?php echo obtenerTexto($conn, 'nosotros', 'nombre_pastor'); ?></h3>
                    </div>
                    
                    <div style="position:relative;">
                        <?php botonEditar('nosotros', 'cargo_pastor'); ?>
                        <p class="cargo"><?php echo obtenerTexto($conn, 'nosotros', 'cargo_pastor'); ?></p>
                    </div>

                    <div style="position:relative;">
                        <?php botonEditar('nosotros', 'bio_pastor'); ?>
                        <p>
                            <?php echo obtenerTexto($conn, 'nosotros', 'bio_pastor'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="seccion-ministerios" style="position:relative;">
            <?php botonEditar('nosotros', 'titulo_creencias'); ?>
            <h2 class="titulo-seccion"><?php echo obtenerTexto($conn, 'nosotros', 'titulo_creencias'); ?></h2>
            
            <div class="legado-lista">
                <div class="legado-item" style="position:relative;">
                    <?php botonEditar('nosotros', 'creencia_1'); ?>
                    <h4><?php echo obtenerTexto($conn, 'nosotros', 'creencia_1'); ?></h4>
                </div>
                <div class="legado-item" style="position:relative;">
                    <?php botonEditar('nosotros', 'creencia_2'); ?>
                    <h4><?php echo obtenerTexto($conn, 'nosotros', 'creencia_2'); ?></h4>
                </div>
                <div class="legado-item" style="position:relative;">
                    <?php botonEditar('nosotros', 'creencia_3'); ?>
                    <h4><?php echo obtenerTexto($conn, 'nosotros', 'creencia_3'); ?></h4>
                </div>
            </div>
        </div>
    </section>

</body>
</html>