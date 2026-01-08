<?php 
session_start(); 
include("../php/includes/conexion.php"); 
include("includes/funciones_edicion.php");

// OBTENER VIDEOS
$query_predicas = "SELECT * FROM multimedia WHERE tipo = 'video_predicacion' OR tipo = 'video' ORDER BY id DESC";
$res_predicas = mysqli_query($conn, $query_predicas);
$videos_predicas = [];
while($v = mysqli_fetch_assoc($res_predicas)) { $videos_predicas[] = $v; }

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
    
    <style>
        /* Estilos Tarjeta Agregar */
        .card-agregar {
            background-color: #f0fdf4; border: 2px dashed #34b065;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            cursor: pointer; min-height: 200px; transition: all 0.3s ease;
        }
        .card-agregar:hover { background-color: #dcfce7; transform: translateY(-5px); }
        .card-agregar span { font-size: 3rem; color: #34b065; font-weight: bold; }
        .card-agregar p { color: #166534; font-weight: 600; margin-top: 10px; }

        /* Estilos Botones Admin (Flotantes sobre el video) */
        .video-card { position: relative; } /* Necesario para posicionar botones */

        .btn-video-flotante {
            position: absolute; top: 10px;
            width: 35px; height: 35px; border-radius: 50%;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.4); z-index: 10;
            font-size: 16px; border: none; color: white; transition: transform 0.2s;
        }
        .btn-video-flotante:hover { transform: scale(1.1); }

        .btn-editar-video { right: 50px; background: #f59e0b; }
        .btn-editar-video:hover { background: #d97706; }

        .btn-eliminar-video { right: 10px; background: #ef4444; }
        .btn-eliminar-video:hover { background: #dc2626; }

        /* Modal */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 10000;
            display: none; align-items: center; justify-content: center;
        }
        .modal-box {
            background: white; padding: 30px; border-radius: 12px;
            width: 90%; max-width: 500px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2); text-align: center;
        }
        .modal-box input, .modal-box select {
            width: 100%; padding: 12px; margin: 10px 0;
            border: 1px solid #ddd; border-radius: 6px;
        }
        .btn-guardar-video {
            background: #34b065; color: white; border: none;
            padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 16px;
        }
        .btn-cancelar {
            background: #ef4444; color: white; border: none;
            padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 16px; margin-left: 10px;
        }
    </style>
</head>
<body class="page-recursos">
    
    <?php include("includes/navbar.php"); ?>
    <?php include("includes/load_editor.php"); ?>

    <header class="encabezado">
        <?php editable($conn, 'recursos', 'titulo_principal', 'h1'); ?>
        <div style="display:inline-block;">
             <?php editable($conn, 'recursos', 'subtitulo_header', 'p'); ?>
        </div>
    </header>

    <main class="contenido">
        
        <section class="recursos-seccion">
            <div style="text-align:center;">
                <?php editable($conn, 'recursos', 'titulo_predicaciones', 'h2', 'titulo-seccion'); ?>
            </div>
            
            <div class="video-grid">
                <?php if(isset($_SESSION['admin_id'])): ?>
                    <article class="video-card card-agregar" onclick="abrirModalCrear('video_predicacion')">
                        <span>+</span>
                        <p>Agregar Predicación</p>
                    </article>
                <?php endif; ?>

                <?php foreach($videos_predicas as $vid): ?>
                    <article class="video-card">
                        
                        <?php if(isset($_SESSION['admin_id'])): ?>
                            <button class="btn-video-flotante btn-editar-video" 
                                    title="Editar"
                                    onclick='abrirModalEditar(<?php echo json_encode($vid); ?>)'>
                                ✎
                            </button>
                            <button class="btn-video-flotante btn-eliminar-video" 
                                    title="Eliminar"
                                    onclick="eliminarVideo(<?php echo $vid['id']; ?>)">
                                🗑️
                            </button>
                        <?php endif; ?>

                        <div class="video-frame">
                            <iframe src="<?php echo $vid['url']; ?>" allowfullscreen loading="lazy"></iframe>
                        </div>
                        <div class="video-info">
                            <h4><?php echo $vid['descripcion']; ?></h4>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <?php if(count($videos_predicas) == 0 && !isset($_SESSION['admin_id'])): ?>
                <div class="no-videos"><p>No hay predicaciones por el momento.</p></div>
            <?php endif; ?>
        </section>

        <hr class="divisor">

        <section class="recursos-seccion">
            <div style="text-align:center;">
                <?php editable($conn, 'recursos', 'titulo_eventos', 'h2', 'titulo-seccion'); ?>
            </div>
            
            <div class="video-grid">
                <?php if(isset($_SESSION['admin_id'])): ?>
                    <article class="video-card card-agregar" onclick="abrirModalCrear('video_evento')">
                        <span>+</span>
                        <p>Agregar Evento</p>
                    </article>
                <?php endif; ?>

                <?php foreach($videos_eventos as $vid): ?>
                    <article class="video-card">
                        
                        <?php if(isset($_SESSION['admin_id'])): ?>
                            <button class="btn-video-flotante btn-editar-video" 
                                    title="Editar"
                                    onclick='abrirModalEditar(<?php echo json_encode($vid); ?>)'>
                                ✎
                            </button>
                            <button class="btn-video-flotante btn-eliminar-video" 
                                    title="Eliminar"
                                    onclick="eliminarVideo(<?php echo $vid['id']; ?>)">
                                🗑️
                            </button>
                        <?php endif; ?>

                        <div class="video-frame">
                            <iframe src="<?php echo $vid['url']; ?>" allowfullscreen loading="lazy"></iframe>
                        </div>
                        <div class="video-info">
                            <h4><?php echo $vid['descripcion']; ?></h4>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if(count($videos_eventos) == 0 && !isset($_SESSION['admin_id'])): ?>
                <div class="no-videos"><p>No hay eventos por el momento.</p></div>
            <?php endif; ?>
        </section>

    </main>

    <?php if(isset($_SESSION['admin_id'])): ?>
    <div id="modalVideo" class="modal-overlay">
        <div class="modal-box">
            <h3 id="tituloModal" style="margin-bottom:15px; color:#333;">Nuevo Video</h3>
            
            <input type="hidden" id="idVideo">
            <input type="hidden" id="tipoVideo"> 
            
            <label style="display:block; text-align:left; font-size:14px; font-weight:bold;">URL de YouTube:</label>
            <input type="text" id="urlVideo" placeholder="Ej: https://youtube.com/watch?v=...">
            
            <label style="display:block; text-align:left; font-size:14px; font-weight:bold;">Título / Descripción:</label>
            <input type="text" id="descVideo" placeholder="Ej: Servicio Dominical - Fe">

            <div style="margin-top:20px;">
                <button class="btn-guardar-video" onclick="guardarVideo()">Guardar</button>
                <button class="btn-cancelar" onclick="cerrarModalVideo()">Cancelar</button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalVideo');
        const idInput = document.getElementById('idVideo');
        const tipoInput = document.getElementById('tipoVideo');
        const urlInput = document.getElementById('urlVideo');
        const descInput = document.getElementById('descVideo');
        const tituloModal = document.getElementById('tituloModal');

        // MODO CREAR
        function abrirModalCrear(tipo) {
            idInput.value = ''; // Sin ID = Crear
            tipoInput.value = tipo;
            urlInput.value = '';
            descInput.value = '';
            tituloModal.innerText = 'Nuevo Video';
            modal.style.display = 'flex';
        }

        // MODO EDITAR
        function abrirModalEditar(datos) {
            idInput.value = datos.id; // Con ID = Editar
            tipoInput.value = datos.tipo;
            urlInput.value = datos.url;
            descInput.value = datos.descripcion;
            tituloModal.innerText = 'Editar Video';
            modal.style.display = 'flex';
        }

        function cerrarModalVideo() {
            modal.style.display = 'none';
        }

        // GUARDAR (CREAR O EDITAR)
        function guardarVideo() {
            const id = idInput.value;
            const tipo = tipoInput.value;
            const url = urlInput.value;
            const desc = descInput.value;

            if(!url) { alert('La URL es obligatoria'); return; }

            const datos = { id: id, tipo: tipo, url: url, descripcion: desc };

            // Decidir a qué archivo llamar
            let rutaAjax = id ? '../php/admin/editar_video_ajax.php' : '../php/admin/guardar_video_ajax.php';

            fetch(rutaAjax, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datos)
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    alert(id ? '✅ Video actualizado' : '✅ Video agregado');
                    location.reload();
                } else {
                    alert('❌ Error: ' + data.message);
                }
            })
            .catch(err => console.error(err));
        }

        // ELIMINAR
        function eliminarVideo(id) {
            if(!confirm('¿Seguro que deseas eliminar este video?')) return;

            fetch('../php/admin/eliminar_video_ajax.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: id })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    alert('🗑️ Video eliminado');
                    location.reload();
                } else {
                    alert('❌ Error: ' + data.message);
                }
            })
            .catch(err => console.error(err));
        }

        // Cerrar al dar clic fuera
        modal.addEventListener('click', function(e) {
            if (e.target === this) cerrarModalVideo();
        });
    </script>
    <?php endif; ?>

</body>
</html>