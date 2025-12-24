<?php 
session_start(); 
include("../php/includes/conexion.php"); 
include("includes/funciones_edicion.php");

// 1. Función para textos generales
if (!function_exists('obtenerTexto')) {
    function obtenerTexto($conn, $seccion, $clave) {
        $query = "SELECT contenido FROM textos WHERE seccion = '$seccion' AND clave = '$clave'";
        $res = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($res);
        return $data ? $data['contenido'] : "Texto no configurado...";
    }
}

// 2. Consulta para obtener las noticias
$query_noticias = "SELECT * FROM noticias ORDER BY fecha DESC";
$resultado_noticias = mysqli_query($conn, $query_noticias);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias - Segunda Milla</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/pages/noticias.css">
</head>
<body>
    
    <?php include("includes/navbar.php"); ?>

    <header class="encabezado" style="position:relative;">
        <?php botonEditar('noticias', 'titulo_principal'); ?>
        <h1><?php echo obtenerTexto($conn, 'noticias', 'titulo_principal'); ?></h1>
        
        <div style="position:relative; display:inline-block;">
            <?php botonEditar('noticias', 'subtitulo_header'); ?>
            <p><?php echo obtenerTexto($conn, 'noticias', 'subtitulo_header'); ?></p>
        </div>
    </header>

    <section class="contenido">
        <div class="noticias-container">
            
            <?php if(mysqli_num_rows($resultado_noticias) > 0): ?>
                <?php while($fila = mysqli_fetch_assoc($resultado_noticias)): ?>
                    <article class="noticia-card">
                        <div class="noticia-imagen">
                            <img src="<?php echo !empty($fila['imagen']) ? $fila['imagen'] : 'images/referancia.jpeg'; ?>" alt="Noticia">
                        </div>
                        <div class="noticia-texto">
                            <span class="fecha"><?php echo date("d M Y", strtotime($fila['fecha'])); ?></span>
                            <h2><?php echo $fila['titulo']; ?></h2>
                            <p><?php echo substr($fila['contenido'], 0, 150) . '...'; ?></p>
                            <a href="#" class="leer-mas">Leer más →</a>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center;">No hay noticias publicadas por el momento.</p>
            <?php endif; ?>

        </div>
    </section>
</body>
</html>