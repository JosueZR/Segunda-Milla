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
    <title>Nosotros - Segunda Milla</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <?php include("includes/navbar.php"); ?>
    
    <?php include("includes/load_editor.php"); ?>

    <header class="encabezado">
        <?php editable($conn, 'nosotros', 'titulo_principal', 'h1'); ?>
        
        <div style="margin-top:10px;">
             <?php editable($conn, 'nosotros', 'subtitulo_principal', 'p'); ?>
        </div>
    </header>

    <section>
        <div class="seccion-ministerios">
            <?php editable($conn, 'nosotros', 'titulo_seccion_1', 'h2', 'titulo-seccion'); ?>

            <div class="nosotros-contenido">
                <?php editable($conn, 'nosotros', 'contenido_intro', 'p'); ?>
                
                <div class="valores-lista">
                    <h4>📜 Nuestro Fundamento:</h4>
                    <ul>
                         <?php editable($conn, 'nosotros', 'fundamento_texto', 'li'); ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="seccion-pastor">
            <?php editable($conn, 'nosotros', 'titulo_pastor', 'h2', 'titulo-seccion'); ?>
            
            <div class="pastor-contenido">
                <div class="pastor-foto">
                    <?php 
                        // Parámetros: conn, seccion, clave única, ruta original por defecto, texto alternativo
                        editableImagen($conn, 'nosotros', 'foto_pastor', 'images/pastor.webp', 'Pastor Principal'); 
                    ?>
                </div>
                <div class="pastor-info">
                    <?php editable($conn, 'nosotros', 'nombre_pastor', 'h3'); ?>
                    
                    <?php editable($conn, 'nosotros', 'cargo_pastor', 'p', 'cargo'); ?>

                    <?php editable($conn, 'nosotros', 'bio_pastor', 'p'); ?>
                </div>
            </div>
        </div>

        <div class="seccion-ministerios">
            <?php editable($conn, 'nosotros', 'titulo_creencias', 'h2', 'titulo-seccion'); ?>
            
            <div class="legado-lista">
                <div class="legado-item">
                    <?php editable($conn, 'nosotros', 'creencia_1', 'h4'); ?>
                </div>
                <div class="legado-item">
                    <?php editable($conn, 'nosotros', 'creencia_2', 'h4'); ?>
                </div>
                <div class="legado-item">
                    <?php editable($conn, 'nosotros', 'creencia_3', 'h4'); ?>
                </div>
            </div>
        </div>
    </section>

</body>
</html>