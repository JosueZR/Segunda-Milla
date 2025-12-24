<?php 
session_start(); 
include("../php/includes/conexion.php"); 
include("includes/funciones_edicion.php");

// 1. Función auxiliar para textos
if (!function_exists('obtenerTexto')) {
    function obtenerTexto($conn, $seccion, $clave) {
        $query = "SELECT contenido FROM textos WHERE seccion = '$seccion' AND clave = '$clave'";
        $res = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($res);
        return $data ? $data['contenido'] : "Texto no configurado...";
    }
}

// 2. OBTENER TODAS LAS PREDICACIONES
$query_predicas = "SELECT * FROM multimedia WHERE tipo = 'video_predicacion' OR tipo = 'video' ORDER BY id DESC";
$res_predicas = mysqli_query($conn, $query_predicas);
$videos_predicas = [];
while($v = mysqli_fetch_assoc($res_predicas)) { $videos_predicas[] = $v; }

// 3. OBTENER TODOS LOS EVENTOS
$query_eventos = "SELECT * FROM multimedia WHERE tipo = 'video_evento' ORDER BY id DESC";
$res_eventos = mysqli_query($conn, $query_eventos);
$videos_eventos = [];
while($v = mysqli_fetch_assoc($res_eventos)) { $videos_eventos[] = $v; }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recursos - Segunda Milla</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/pages/recursos.css">
</head>
<body class="page-recursos">
    
    <?php include("includes/navbar.php"); ?>

    <header class="encabezado" style="position:relative;">
        <?php botonEditar('recursos', 'titulo_principal'); ?>
        <h1><?php echo obtenerTexto($conn, 'recursos', 'titulo_principal'); ?></h1>
        
        <div style="position:relative; display:inline-block;">
             <?php botonEditar('recursos', 'subtitulo_header'); ?>
             <p><?php echo obtenerTexto($conn, 'recursos', 'subtitulo_header'); ?></p>
        </div>
    </header>

    <main class="contenido">
        
        <section class="recursos-seccion">
            <div style="position:relative; text-align:center;">
                <?php botonEditar('recursos', 'titulo_predicaciones'); ?>
                <h2 class="titulo-seccion"><?php echo obtenerTexto($conn, 'recursos', 'titulo_predicaciones'); ?></h2>
            </div>
            
            <?php if(count($videos_predicas) > 0): ?>
                <div class="video-grid">
                    <?php foreach($videos_predicas as $vid): ?>
                        <article class="video-card">
                            <div class="video-frame">
                                <iframe src="<?php echo $vid['url']; ?>" allowfullscreen loading="lazy"></iframe>
                            </div>
                            <div class="video-info">
                                <h4><?php echo $vid['descripcion']; ?></h4>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-videos">
                    <p>No hay predicaciones publicadas por el momento.</p>
                </div>
            <?php endif; ?>
        </section>

        <hr class="divisor">

        <section class="recursos-seccion">
            <div style="position:relative; text-align:center;">
                <?php botonEditar('recursos', 'titulo_eventos'); ?>
                <h2 class="titulo-seccion"><?php echo obtenerTexto($conn, 'recursos', 'titulo_eventos'); ?></h2>
            </div>
            
            <?php if(count($videos_eventos) > 0): ?>
                <div class="video-grid">
                    <?php foreach($videos_eventos as $vid): ?>
                        <article class="video-card">
                            <div class="video-frame">
                                <iframe src="<?php echo $vid['url']; ?>" allowfullscreen loading="lazy"></iframe>
                            </div>
                            <div class="video-info">
                                <h4><?php echo $vid['descripcion']; ?></h4>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-videos">
                    <p>No hay videos de eventos publicados por el momento.</p>
                </div>
            <?php endif; ?>
        </section>

    </main>
</body>
</html>